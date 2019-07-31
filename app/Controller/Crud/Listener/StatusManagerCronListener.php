<?php
App::uses('CronCrudListener', 'Cron.Controller/Crud');
App::uses('CronException', 'Cron.Error');

/**
 * StatusManager CRON listener.
 */
class StatusManagerCronListener extends CronCrudListener
{
	public function beforeHandle(CakeEvent $event)
	{
		$this->PolicyException = ClassRegistry::init('PolicyException');
		$this->RiskException = ClassRegistry::init('RiskException');
		$this->ComplianceException = ClassRegistry::init('ComplianceException');
		$this->Project = ClassRegistry::init('Project');
		$this->ServiceContract = ClassRegistry::init('ServiceContract');
		$this->Risk = ClassRegistry::init('Risk');
		$this->ThirdPartyRisk = ClassRegistry::init('ThirdPartyRisk');
		$this->BusinessContinuity = ClassRegistry::init('BusinessContinuity');
		$this->SecurityPolicy = ClassRegistry::init('SecurityPolicy');
		$this->Asset = ClassRegistry::init('Asset');
		$this->SecurityService = ClassRegistry::init('SecurityService');
		$this->BusinessContinuityPlan = ClassRegistry::init('BusinessContinuityPlan');
		$this->Goal = ClassRegistry::init('Goal');
	}

	public function daily(CakeEvent $event)
	{
		$ret = true;
		$ret &= $this->PolicyException->triggerStatus('expired');
		$ret &= $this->RiskException->triggerStatus('expired');
		$ret &= $this->ComplianceException->triggerStatus('expired');
		$ret &= $this->Project->triggerStatus('expired');
		$ret &= $this->Project->triggerStatus('expiredTasks');
		$ret &= $this->ServiceContract->triggerStatus('expired');
		$ret &= $this->Risk->triggerStatus('expiredReviews');
		$ret &= $this->ThirdPartyRisk->triggerStatus('expiredReviews');
		$ret &= $this->BusinessContinuity->triggerStatus('expiredReviews');
		$ret &= $this->SecurityPolicy->triggerStatus('expiredReviews');
		$ret &= $this->Asset->triggerStatus('expiredReviews');
		$ret &= $this->SecurityService->triggerStatus('auditsLastMissing');
		$ret &= $this->SecurityService->triggerStatus('maintenancesLastMissing');
		$ret &= $this->BusinessContinuityPlan->triggerStatus('auditsLastMissing');
		$ret &= $this->Goal->triggerStatus('auditsLastMissing');

		if (!$ret) {
			throw new CronException(__('Status management failed'));
		}
	}

}
