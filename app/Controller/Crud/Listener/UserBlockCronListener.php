<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('CronException', 'Cron.Error');

/**
 * UserBlock CRON listener.
 */
class UserBlockCronListener extends CronCrudListener
{
	public function beforeHandle(CakeEvent $event)
	{
		$this->User = ClassRegistry::init('User');
	}

	public function daily(CakeEvent $event)
	{
		if (!$this->User->checkAllBlockedStatuses()) {
			throw new CronException(__('Processing user bans failed'));
		}
	}

}
