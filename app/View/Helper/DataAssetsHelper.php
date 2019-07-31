<?php
App::uses('ErambaHelper', 'View/Helper');
class DataAssetsHelper extends ErambaHelper {
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatusArr($item, $allow = '*') {
		$item = $this->processItemArray($item, 'DataAsset');
		$statuses = array();

		$inherit = array(
			'SecurityServices' => array(
				'model' => 'SecurityService',
				'config' => array(
					'audits_last_passed',
					'audits_last_missing',
					'maintenances_last_missing',
					'ongoing_corrective_actions',
					'security_service_type_id',
					'control_with_issues'
				)
			),
			'Projects' => array(
				'model' => 'Project',
				'config' => array('expired')
			),
			/*'Goals' => array(
				'model' => 'Goal',
				'config' => array('metrics_last_missing', 'ongoing_corrective_actions')
			),*/
		);

		if ($this->getAllowCond($allow, INHERIT_CONFIG_KEY)) {
			$statuses = am($statuses, $this->getInheritedStatuses($item, $inherit));
		}

		return $statuses;
	}

	public function getStatuses($item, $options = array()) {
		$options = $this->processStatusOptions($options);
		$statuses = $this->getStatusArr($item, $options['allow']);
		
		return $this->styleStatuses($statuses, $options);
	}




	/*public function getStatuses($item) {
		$statuses = array();

		foreach ($item['SecurityService'] as $control) {
			$statuses = am($statuses, $this->SecurityServices->getStatuses($control));
		}

		foreach ($item['Project'] as $project) {
			$statuses = am($statuses, $this->Projects->getStatuses($project));
		}

		return $this->processStatuses($statuses);
	}*/

	public function missingStatus() {
		$statuses = array();
		$statuses[] = $this->getLabel(__('Missing Analysis'), 'warning');

		return $this->processStatuses($statuses);
	}

}