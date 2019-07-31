<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');

/**
 * Reports CRON listener.
 */
class ReportsCronListener extends CronCrudListener
{
	public function beforeHandle(CakeEvent $event)
	{
		$this->_ensureComponent();
	}

	protected function _ensureComponent()
	{
		// short-hand call to load a component
		$this->ReportsMgt = $this->_loadComponent('ReportsMgt');
	}

	public function daily(CakeEvent $event)
	{
		$this->_controller()->ReportsMgt->cron();
	}

}
