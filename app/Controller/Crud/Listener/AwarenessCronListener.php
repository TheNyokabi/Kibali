<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('CronException', 'Cron.Error');

/**
 * Awareness CRON listener.
 */
class AwarenessCronListener extends CronCrudListener
{
	public function beforeHandle(CakeEvent $event)
	{
		$this->_ensureComponent();
	}

	protected function _ensureComponent()
	{
		// short-hand call to load a component
		$this->AwarenessMgt = $this->_loadComponent('AwarenessMgt');
	}

	public function daily(CakeEvent $event)
	{
		$controller = $this->_controller();

		if (!$controller->AwarenessMgt->cron()) {
			throw new CronException(__('Awareness Program processing failed'));
		}
	}

}
