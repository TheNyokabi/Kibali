<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('CronException', 'Cron.Error');

/**
 * AutoUpdate CRON listener.
 */
class AutoUpdateCronListener extends CronCrudListener
{
	public function beforeHandle(CakeEvent $event)
	{
		$this->_ensureComponent();
	}

	protected function _ensureComponent()
	{
		// short-hand call to load a component
		$this->AutoUpdate = $this->_loadComponent('AutoUpdate');
	}

	public function daily(CakeEvent $event)
	{
		$controller = $this->_controller();

		$controller->AutoUpdate->skipHealthCheck = [
			'cron-daily'
		];
		
		$controller->AutoUpdate->check();
		if ($controller->AutoUpdate->hasError()) {
			throw $controller->AutoUpdate->getLastCronException();
		}
	}

}
