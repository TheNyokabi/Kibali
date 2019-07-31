<?php
App::uses('ErambaHelper', 'View/Helper');
class SecurityPolicyReviewsHelper extends ErambaHelper {
	public $helpers = array('NotificationSystem', 'Html');
	public $settings = array();

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatuses($item) {
		$statuses = array();

		$item = $this->processItemArray($item, 'SecurityPolicyReview');

		$statuses = array_merge($statuses, $this->NotificationSystem->getStatuses($item));

		return $this->processStatuses($statuses);
	}

}