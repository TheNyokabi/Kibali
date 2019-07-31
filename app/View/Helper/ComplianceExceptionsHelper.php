<?php
App::uses('ErambaHelper', 'View/Helper');
class ComplianceExceptionsHelper extends ErambaHelper {
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		$this->helpers[] = 'ErambaTime';
		$this->helpers[] = 'Taggable';
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	/**
	 * @deprecated
	 */
	public function getStatuses($item) {
		$item = $this->processItemArray($item, 'ComplianceException');
		$statuses = array();

		if ($item['ComplianceException']['status'] == COMPLIANCE_EXCEPTION_CLOSED) {
			$statuses[] = $this->getLabel(__('Closed'), 'success');
		}

		if ($item['ComplianceException']['status'] == COMPLIANCE_EXCEPTION_OPEN) {
			$statuses[] = $this->getLabel(__('Open'), 'success');
		}

		if ($item['ComplianceException']['expired'] == ITEM_STATUS_EXPIRED) {
			$statuses[] = $this->getLabel(__('Expired'), 'danger');
		}

		return $this->processStatuses($statuses);
	}

	/**
	 * Show tags for a compliance exception.
	 */
	public function getTags($item) {
		return $this->Taggable->showList($item, [
			'notFoundCallback' => [$this->Taggable, 'notFoundBlank']
		]);
	}

}