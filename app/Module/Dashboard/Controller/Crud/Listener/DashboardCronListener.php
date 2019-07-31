<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('Dashboard', 'Dashboard.Lib');

/**
 * Dashboard CRON listener that handles all tasks that needs to be processed during the CRON.
 */
class DashboardCronListener extends CronCrudListener {

	public function implementedEvents() {
		return array(
			'Cron.beforeHandle' => 'beforeHandle',
			'Cron.hourly' => array('callable' => 'hourly', 'priority' => 2),
		);
	}

	public function beforeHandle(CakeEvent $event) {
		$request = $this->_request();
		$model = $this->_model();
		$controller = $this->_controller();

		$DashboardKpi = ClassRegistry::init('Dashboard.DashboardKpi');
		$this->DashboardKpi = $DashboardKpi;
		$this->Dashboard = $DashboardKpi->instance();
	}

	/**
	 * Hourly CRON listener for Dashboards.
	 * 	
	 * @param  CakeEvent $event
	 * @return boolean True on success, False on failure.
	 */
	public function hourly(CakeEvent $event) {
		$ret = true;

		ClassRegistry::init('Risk')->label = __('Risks');
		ClassRegistry::init('ThirdPartyRisk')->label = __('Risks');
		ClassRegistry::init('BusinessContinuity')->label = __('Risks');

		// execute at 1am in the morning after the daily cron runs and not during daily for performance reasons
		// @see EmailQueueCronListener class
		if (date('G') == '1') {
			$params = [
				'structure' => true,
				'values' => true
			];

			$ret &= $this->Dashboard->sync($params);
		}

		if (!$ret) {
			throw new CronException(__('Dashboards module failed to synchronize your data.'));
		}
	}

}
