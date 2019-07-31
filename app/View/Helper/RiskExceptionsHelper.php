<?php
App::uses('ErambaHelper', 'View/Helper');
class RiskExceptionsHelper extends ErambaHelper {
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		$this->helpers[] = 'ErambaTime';
		$this->helpers[] = 'NotificationSystem';
		$this->helpers[] = 'Taggable';

		parent::__construct($view, $settings);
		$this->settings = $settings;
	}

	public function getStatuses($item, $showAll = true) {
		$item = $this->processItemArray($item, 'RiskException');
		$statuses = array();

		if ($item['RiskException']['status'] == RISK_EXCEPTION_CLOSED) {
			$statuses[] = $this->getLabel(__('Closed'), 'success');
		}

		if ($item['RiskException']['status'] == RISK_EXCEPTION_OPEN) {
			$statuses[] = $this->getLabel(__('Open'), 'success');
		}

		if ($item['RiskException']['expired'] == ITEM_STATUS_EXPIRED) {
			$statuses[] = $this->getLabel(__('Expired'), 'danger');
		}
		
		$options = array();
		if ($showAll) {
			//$statuses = array_merge($statuses, $this->NotificationSystem->getStatuses($item));
		}
		else {
			//$options['inline'] = false;
		}
		
		return $this->processStatuses($statuses, $options);
	}

	/**
	 * Show tags for a risk exception.
	 */
	public function getTags($item) {
		return $this->Taggable->showList($item, [
			'notFoundCallback' => [$this->Taggable, 'notFoundBlank']
		]);
	}

}
