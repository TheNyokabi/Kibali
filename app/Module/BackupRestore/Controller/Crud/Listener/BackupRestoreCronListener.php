<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('CronException', 'Cron.Error');

/**
 * BackupRestore CRON listener that handles database backup during the CRON.
 */
class BackupRestoreCronListener extends CronCrudListener
{
	/**
	 * Before handle callback should handle preloading of classes needed within this listener process.
	 * 
	 * @param  CakeEvent $event
	 * @return void
	 * @throws CronException     If there has been an issue while preparing this listener process for CRON Job.
	 */
	public function beforeHandle(CakeEvent $event)
	{
		$this->_ensureComponent();
	}

	/**
	 * Method loads all necassery components after it checks if components are not already loaded.
	 * 
	 * @return void
	 */
	protected function _ensureComponent()
	{
		// short-hand call to load a component
		$this->BackupRestoreMgt = $this->_loadComponent('BackupRestore.BackupRestoreMgt');
	}

	/**
	 * Daily CRON makes a backup of the database.
	 * 	
	 * @param  CakeEvent $event
	 * @return boolean True on success, False on failure.
	 */
	public function daily(CakeEvent $event)
	{
		$controller = $this->_controller();

		if (!$controller->BackupRestoreMgt->dailyBackup()) {
			throw new CronException(__('Backup of your database failed'));
		}
	}

}
