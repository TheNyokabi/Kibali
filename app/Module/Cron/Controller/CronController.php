<?php
App::uses('CronAppController', 'Cron.Controller');
App::uses('ErambaCakeEmail', 'Network/Email');
App::uses('AppModel', 'Model');
App::uses('Cron', 'Model');
App::uses('CronModule', 'Cron.Lib');

/**
 * Manages CRON tasks for the app.
 */
class CronController extends CronAppController {
	public $components = array(
		'Session',
		'AdvancedFilters',
		'AdvancedFiltersCron',

		'Crud.Crud' => [
			'eventPrefix' => 'Cron',
			
			'actions' => [
				CronModule::ACTION_NAME => [
					'className' => 'Cron.Cron'
				]
			],
			'listeners' => [
				'CronSetup' => [
					'className' => 'Cron.CronSetup',
					'title' => 'Cron Setup'
				],
				'AdvancedFiltersCron' => [
					'className' => 'AdvancedFilters.AdvancedFiltersCron',
					'title' => 'Advanced Filters'
				],
				'Dashboard.DashboardCron',
				'BackupRestoreCron' => [
					'className' => 'BackupRestore.BackupRestoreCron',
					'title' => 'Database Backup'
				],
				'StatusManagerCron' => [
					'className' => 'StatusManagerCron',
					'title' => 'Status manager'
				],
				'UserBlockCron' => [
					'className' => 'UserBlockCron',
					'title' => 'User Block'
				],
				'AwarenessCron' => [
					'className' => 'AwarenessCron',
					'title' => 'Awareness'
				],
				'NotificationSystemCron' => [
					'className' => 'NotificationSystemCron',
					'title' => 'Notification System'
				],
				'AutoUpdateCron' => [
					'className' => 'AutoUpdateCron',
					'title' => 'Auto Update'
				],
				'NewsCron' => [
					'className' => 'NewsCron',
					'title' => 'News'
				],
				'ObjectStatusCron' => [
					'className' => 'ObjectStatus.ObjectStatusCron',
					'title' => 'Object Status'
				],
				'AuditsCron' => [
					'className' => 'AuditsCron',
					'title' => 'Audits'
				],
				'ReportsCron' => [
					'className' => 'ReportsCron',
					'title' => 'Reports'
				],
				'EmailQueueCron' => [
					'className' => '.EmailQueueCron',
					'title' => 'Email Queue'
				]
			]
		]
	);

	public $uses = array(
		'Cron',
		'Workflows.WorkflowInstance'
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->autoRender = false;
		$this->Auth->allow(CronModule::ACTION_NAME);
	}

	/**
	 * Handler that executes all cron jobs.
	 */
	public function job($type, $key) {
		$this->layout = 'Cron.cron';

		return $this->Crud->execute();
	}

	public function index() {
		$this->set('title_for_layout', __('Cron Records'));
		$this->set('subtitle_for_layout', __('eramba has three crontabs that must run every year, day and hour. This log describes when a cron run and what was the result.'));

		if ($this->AdvancedFilters->filter('Cron')) {
			return;
		}

		$this->paginate = array(
			'fields' => array('*'),
			'order' => array('Cron.created' => 'DESC'),
			'limit' => $this->getPageLimit(),
		);
		$crons = $this->paginate('Cron');

		$this->autoRender = true;
		$this->set('crons', $crons);
	}

	public function getIndexUrlFromComponent($model, $foreign_key) {
		return parent::getIndexUrl($model, $foreign_key);
	}

	public function initEmailFromComponent($to, $subject, $template, $data = array(), $layout = 'default', $from = NO_REPLY_EMAIL, $type = 'html') {
		return parent::initEmail($to, $subject, $template, $data, $layout, $from, $type);
	}
}
