<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('CronException', 'Cron.Error');

/**
 * News CRON listener.
 */
class NewsCronListener extends CronCrudListener
{
	public function beforeHandle(CakeEvent $event)
	{
		$this->_ensureComponent();
	}

	protected function _ensureComponent()
	{
		// short-hand call to load a component
		$this->News = $this->_loadComponent('News');
	}

	public function daily(CakeEvent $event)
	{
		$controller = $this->_controller();

		$controller->News->check();
		if ($controller->News->hasError()) {
			throw new CronException($controller->News->getErrorMessage());
		}
	}

}
