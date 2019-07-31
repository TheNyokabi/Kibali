<?php
App::uses('ErambaHelper', 'View/Helper');
class SecurityIncidentsHelper extends ErambaHelper {
	public $settings = array();

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatusArr($item, $allow = '*') {
		$item = $this->processItemArray($item, 'SecurityIncident');
		$statuses = array();

		if ($item['SecurityIncident']['security_incident_status_id'] == SECURITY_INCIDENT_ONGOING) {
			if ($this->getAllowCond($allow, 'ongoing_incident') && $item['SecurityIncident']['ongoing_incident'] == SECURITY_INCIDENT_ONGOING_INCIDENT) {
				$statuses[$this->getStatusKey('ongoing_incident')] = array(
					'label' => __('Ongoing Incident'),
					'type' => 'warning'
				);
			}

			if ($this->getAllowCond($allow, 'lifecycle_incomplete') && $item['SecurityIncident']['lifecycle_incomplete']) {
				$statuses[$this->getStatusKey('lifecycle_incomplete')] = array(
					'label' => __('Lifecycle Incomplete'),
					'type' => 'warning'
				);
			}
		}

		if ($this->getAllowCond($allow, 'security_incident_status_id') && $item['SecurityIncident']['security_incident_status_id'] == SECURITY_INCIDENT_CLOSED) {
			$statuses[$this->getStatusKey('security_incident_status_id')] = array(
				'label' => __('Closed'),
				'type' => 'success'
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