<?php
App::uses('ErambaHelper', 'View/Helper');
App::uses('OauthConnector', 'Model');

class OauthConnectorsHelper extends ErambaHelper {
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatuses($item) {
		$statuses = array();

		if ($item['OauthConnector']['status'] == 1) {
			$statuses[] = $this->getLabel(OauthConnector::statuses($item['OauthConnector']['status']), 'success');
			
		}
		elseif ($item['OauthConnector']['status'] == 0) {
			$statuses[] = $this->getLabel(OauthConnector::statuses($item['OauthConnector']['status']), 'warning');
		}

		return $this->processStatuses($statuses);
	}

}