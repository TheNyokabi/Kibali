<?php
App::uses('ErambaHelper', 'View/Helper');
class BusinessContinuityPlansHelper extends ErambaHelper {
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		$this->settings = $settings;
	}

	public function getStatusArr($item, $allow = '*') {
		$item = $this->processItemArray($item, 'BusinessContinuityPlan');
		$statuses = array();

		if ($this->getAllowCond($allow, 'audits_last_passed') && !$item['BusinessContinuityPlan']['audits_last_passed']) {
			$statuses[$this->getStatusKey('audits_last_passed')] = array(
				'label' => __('Last audit failed'),
				'type' => 'danger'
			);
		}

		if ($this->getAllowCond($allow, 'audits_last_missing') && $item['BusinessContinuityPlan']['audits_last_missing']) {
			$statuses[$this->getStatusKey('audits_last_missing')] = array(
				'label' => __('Last audit missing'),
				'type' => 'warning'
			);
		}

		if ($this->getAllowCond($allow, 'ongoing_corrective_actions') && $item['BusinessContinuityPlan']['ongoing_corrective_actions']) {
			$statuses[$this->getStatusKey('ongoing_corrective_actions')] = array(
				'label' => __('Ongoing Corrective Actions'),
				'type' => 'improvement'
			);
		}

		if ($this->getAllowCond($allow, 'security_service_type_id') && $item['BusinessContinuityPlan']['security_service_type_id'] == SECURITY_SERVICE_DESIGN) {
			$statuses[$this->getStatusKey('security_service_type_id')] = array(
				'label' => __('Plan in Design'),
				'type' => 'warning'
			);
		}

		return $statuses;
	}

	public function getStatuses($item, $options = array()) {
		$options = $this->processStatusOptions($options);
		$statuses = $this->getStatusArr($item, $options['allow']);
		
		return $this->styleStatuses($statuses, $options);
	}
	
}