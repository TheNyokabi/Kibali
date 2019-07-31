<?php
App::uses('ErambaHelper', 'View/Helper');
App::uses('LdapConnector', 'Model');

class LdapConnectorsHelper extends ErambaHelper {
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatuses($item) {
		$statuses = array();

		if ($item['LdapConnector']['status'] == 1) {
			$statuses[] = $this->getLabel(LdapConnector::statuses($item['LdapConnector']['status']), 'success');
			
		}
		elseif ($item['LdapConnector']['status'] == 0) {
			$statuses[] = $this->getLabel(LdapConnector::statuses($item['LdapConnector']['status']), 'warning');
		}

		return $this->processStatuses($statuses);
	}

}