<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('Cron', 'Model');
App::uses('CronModule', 'Cron.Lib');

/**
 * Dashboard CRON listener that handles all tasks that needs to be processed during the CRON.
 */
class CronSetupListener extends CronCrudListener {

	/**
	 * Store login session data about the current logged in user so we can safely login admin user during runtime,
	 * to keep cron process dry. In the end, this value, in case when its not null, is used to relog actual user.
	 * 
	 * @var null
	 */
	protected $_loginSession = null;

	/**
	 * Specifically prioritized callbacks for this listener, as it is the primary core configuration
	 * for CRON jobs and some callbacks should be executed as first before the other listeners,
	 * and some last just after the others because they require to summarize what all has already happened.
	 * 
	 * @see    inspired by DebugKitListener class
	 * @return void
	 */
	public function implementedEvents() {
		return array(
			'Cron.beforeHandle' => array('callable' => 'beforeHandle', 'priority' => 1),
			'Cron.beforeJob' => array('callable' => 'beforeJob', 'priority' => 1),
			'Cron.afterJob' => array('callable' => 'afterJob', 'priority' => 1),
			'Cron.beforeRender' => array('callable' => 'beforeRender', 'priority' => 5000)
		);
	}

	/**
	 * Set some variables before the cron job begins.
	 */
	public function beforeHandle(CakeEvent $event) {
		$this->_loginSession = $this->_controller()->Auth->user();
	}

	/**
	 * Manually login admin user for a case where objects are automatically created
	 * and expects and owner user for visualisations or ACL.
	 * 
	 * @return void
	 */
	protected function _switchLoginSession($switchToAdmin) {
		$controller = $this->_controller();

		if ($switchToAdmin === true) {
			$user = ClassRegistry::init('User')->find('first', [
				'conditions' => [
					'User.id' => ADMIN_ID
				],
				'contain' => [
					'Group'
				]
			]);

			$user['User']['Group'] = $user['Group'];
			$user = $user['User'];
		}
		else {
			$user = $this->_loginSession;
		}

		// if user was not logged in with different user when the cron job began
		// or simply real cron job is executing right now which does not require login session reverting
		if ($user === null) {
			if ($switchToAdmin === false) {
				return $controller->Auth->logout();
			}

			return null;
		}
		
		$controller->Auth->login($user);
		$controller->logged = $controller->Auth->user();

		return true;
	}

	/**
	 * Authenticates configured security key for CRON jobs.
	 *
	 * @param  string $key Provided key for authentication
	 * @return boolean    True if authentication passes, False otherwise
	 */
	protected function _authenticate(CakeEvent $event) {
		return Configure::read('Eramba.Settings.CRON_SECURITY_KEY') === $event->subject->key;
	}

	/**
	 * General validation for the CRON job.
	 */
	protected function _validate(CakeEvent $event) {
		$valid = true;

		$valid &= !$this->_model()->cronTaskExists($event->subject->type);
		$valid &= !$this->_model()->isCronTaskRunning($event->subject->type);

		return $valid;
	}

	/**
	 * Startup the CRON job by configuring it as running at the moment.
	 */
	public function beforeJob(CakeEvent $event) {
		if ($event->subject->type === null) {
			throw new CronException(__('Cron job must have a type of the job (hourly/daily/yearly) configured when executed!'));
		}

		// switch to admin user account for cron jobs
		$this->_switchLoginSession(true);

		if (!$this->_authenticate($event)) {
			throw new CronException(__('The Security key you provided on the cron URL does not match what is defined at System / Settings / Crontab Security Key'));
		}

		if (!$this->_validate($event)) {
			throw new CronException(__("Your request to execute a %s CRON job is invalid, either it has been processed already or it is still being processed", $event->subject->type));
		}

		return $this->_model()->setCronTaskAsRunning($event->subject->type);
	}

	/**
	 * Prioritized callback that saves the record about current cron job's status.
	 */
	public function afterJob(CakeEvent $event) {
		$status = $event->subject->success ? Cron::STATUS_SUCCESS : Cron::STATUS_ERROR;
		$message = $event->subject->message;
		// lets save the record about status of the current cron
		// questionable right now is if this would be better placed here or in afterJob callback in CronSetupListener
		$cronId = $this->_model()->saveCronTaskRecord($event->subject->type, $status, $message);

		// revert login session
		$this->_switchLoginSession(false);
	}

	public function beforeRender(CakeEvent $event) {
		// process only for job action
		if ($event->subject->crud->action() instanceof CronCrudAction) {
			$this->_controller()->set([
				'success' => $event->subject->success,
				'type' => $event->subject->type,
				'errors' => $event->subject->errors
			]);
		}
	}


}
