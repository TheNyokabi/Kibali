<?php
App::uses('ErambaHelper', 'View/Helper');
class PolicyExceptionsHelper extends ErambaHelper {
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		$this->helpers[] = 'ErambaTime';
		$this->helpers[] = 'NotificationSystem';

		parent::__construct($view, $settings);
		$this->settings = $settings;
	}

	public function getStatusArr($item, $allow = '*', $model = 'PolicyException') {
		$item = $this->processItemArray($item, $model);
		$statuses = array();

		if ($this->getAllowCond($allow, 'status') && $item[$model]['status'] == POLICY_EXCEPTION_CLOSED) {
			$statuses[$this->getStatusKey('status')] = array(
				'label' => __('Closed'),
				'type' => 'success'
			);
		}
		
		if ($this->getAllowCond($allow, 'expired') && $item[$model]['expired'] == ITEM_STATUS_EXPIRED) {
			$statuses[$this->getStatusKey('expired')] = array(
				'label' => __('Exception Expired'),
				'type' => 'danger'
			);
		}
		else {
			if ($this->getAllowCond($allow, 'status') && $item[$model]['status'] == POLICY_EXCEPTION_OPEN) {
				$statuses[$this->getStatusKey('status')] = array(
					'label' => __('Open'),
					'type' => 'success'
				);
			}
		}

		return $statuses;
	}

	public function getStatuses($item, $model = 'PolicyException', $options = array()) {
		$options = $this->processStatusOptions($options);
		$statuses = $this->getStatusArr($item, $options['allow'], $model);

		return $this->styleStatuses($statuses, $options);
	}

}
