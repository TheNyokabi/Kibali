<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('CronException', 'Cron.Error');

/**
 * NotificationSystem CRON listener.
 */
class NotificationSystemCronListener extends CronCrudListener
{
	public function beforeHandle(CakeEvent $event)
	{
		$this->_ensureComponent();
	}

	protected function _ensureComponent()
	{
		// short-hand call to load a component
		$this->NotificationSystemMgt = $this->_loadComponent('NotificationSystemMgt');
	}

	public function daily(CakeEvent $event)
	{
		$controller = $this->_controller();

		$ret = true;
		$ret &= $controller->NotificationSystemMgt->cron(NOTIFICATION_TYPE_WARNING);
		$ret &= $controller->NotificationSystemMgt->cron(NOTIFICATION_TYPE_AWARENESS);
		$ret &= $controller->NotificationSystemMgt->cron(NOTIFICATION_TYPE_REPORT);
		
		if (!$ret) {
			throw new CronException(__('Notifications processing failed'));
		}
	}

}
