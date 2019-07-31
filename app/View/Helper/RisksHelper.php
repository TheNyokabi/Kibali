<?php
App::uses('SectionBaseHelper', 'View/Helper');
App::uses('RiskAppetite', 'Model');

class RisksHelper extends SectionBaseHelper {
	public $helpers = array('Assets', 'Html', 'Status', 'Eramba', 'Ajax');
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		$this->settings = $settings;
	}

	public function actionList($item, $options = []) {
		$reviewUrl = array(
			'plugin' => null,
			'controller' => 'reviews',
			'action' => 'index',
			'Risk',
			$item['Risk']['id']
		);

		$this->Ajax->addToActionList(__('Reviews'), $reviewUrl, 'search', 'index');

		$exportUrl = array(
			'controller' => 'risks',
			'action' => 'exportPdf',
			$item['Risk']['id']
		);

		$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

		return parent::actionList($item, $options);
	}

	public function getStatusArr($item, $allow = '*', $modelName = 'Risk') {
		$item = $this->Status->processItemArray($item, $modelName);
		$statuses = array();

		if ($this->Status->getAllowCond($allow, 'expired_reviews') && $item[$modelName]['expired_reviews']) {
			$statuses[$this->Status->getStatusKey('expired_reviews')] = array(
				'label' => __('Risk Review Expired'),
				'type' => 'warning'
			);
		}

		$appetiteConds = $this->_View->get('appetiteMethod') == RiskAppetite::TYPE_INTEGER;
		$appetiteConds &= $this->Status->getAllowCond($allow, 'risk_above_appetite');
		$appetiteConds &= $item[$modelName]['risk_above_appetite'];
		if ($appetiteConds) {
			$statuses[$this->Status->getStatusKey('risk_above_appetite')] = array(
				'label' => __('Risk Above Appetite'),
				'type' => 'danger'
			);
		}

		$inherit = array(
			'Projects' => array(
				'model' => 'Project',
				'config' => array('expired')
			),
			'SecurityIncidents' => array(
				'model' => 'SecurityIncident',
				'config' => array('ongoing_incident')
			),
			'PolicyExceptions' => array(
				'model' => 'RiskException',
				'config' => array('expired')
			),
			/*'Goals' => array(
				'model' => 'Goal',
				'config' => array('metric_last_missing', 'ongoing_corrective_actions')
			),*/
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
			/*'BusinessContinuityPlans' => array(
				'model' => 'BusinessContinuityPlan',
				'config' => array(
					'audits_last_passed',
					'audits_last_missing',
					'ongoing_corrective_actions',
					'security_service_type_id'
				)
			),*/
		);

		if ($modelName == 'Risk') {
			$inherit['Assets'] = array(
				'model' => 'Asset',
				'config' => array('expired_reviews')
			);
		}

		if ($this->Status->getAllowCond($allow, INHERIT_CONFIG_KEY)) {
			$statuses = am($statuses, $this->getInheritedStatuses($item, $inherit));
		}

		return $statuses;
	}

	public function getHeaderClass($item, $modelName, $allow = true) {
		$statuses = $this->getStatusArr($item, $allow, $modelName);
		$type = $this->Eramba->getColorType($statuses);
		$class = $this->Eramba->processHeaderType($type);

		return $class;
	}

	public function getStatuses($item, $modelName = 'Risk', $options = array()) {
		$options = $this->Status->processStatusOptions($options);
		
		$statuses = $this->getStatusArr($item, $options['allow'], $modelName);
		
		return $this->Status->styleStatuses($statuses/*, $opts*/);
	}

	public function getGranularityList() {
		$granularity = Configure::read('Eramba.Settings.RISK_GRANULARITY');

		if ($granularity === null) {
			trigger_error('Failed to read Risk Granularity setting from the Configure class!');
			return false;
		}

		$current = 0;
		$list = [];
		while ($current <= 100) {
			$list[$current] = CakeNumber::toPercentage($current, 0);
			$current = $current + $granularity;
		}

		return $list;
	}

	public function exceptionsExpired($exceptions) {
		foreach ($exceptions as $exception) {
			if ($this->isExpired($exception['expiration'], $exception['status'])) {
				return $this->Html->tag('span', __('Exception Expired'), array('class' => 'label label-danger'));
			}
		}
	}

}