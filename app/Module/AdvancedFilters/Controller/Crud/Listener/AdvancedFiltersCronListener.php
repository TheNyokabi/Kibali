<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');

class AdvancedFiltersCronListener extends CronCrudListener
{

	public function beforeHandle(CakeEvent $event)
	{
		$this->_ensureComponent();
	}

	protected function _ensureComponent()
	{
		// short-hand call to load a component
		$this->AdvancedFiltersCron = $this->_loadComponent('AdvancedFiltersCron');
	}

	public function daily(CakeEvent $event)
	{
		if (!$this->AdvancedFiltersCron->execute()) {
			throw new CronException($this->AdvancedFiltersCron->getErrorMessages(true));
		}
	}

	public function afterJob(CakeEvent $event)
	{
		$controller = $this->_controller();

		if ($this->_model()->id)
		{
			$controller->AdvancedFiltersCron->assignCronIdToRecords($this->_model()->id);
		}
	}

}
