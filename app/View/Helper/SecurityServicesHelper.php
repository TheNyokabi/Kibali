<?php
App::uses('ErambaHelper', 'View/Helper');
class SecurityServicesHelper extends ErambaHelper {
	public $helpers = array('NotificationSystem', 'Html');
	public $settings = array();

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatusArr($item, $allow = '*') {
		$item = $this->processItemArray($item, 'SecurityService');
		$statuses = array();
		
		if ($this->getAllowCond($allow, 'audits_last_passed') && !$item['SecurityService']['audits_last_passed']) {
			$statuses[$this->getStatusKey('audits_last_passed')] = array(
				'label' => __('Last audit failed'),
				'type' => 'danger'
			);
		}

		if ($this->getAllowCond($allow, 'audits_last_missing') && $item['SecurityService']['audits_last_missing']) {
			$statuses[$this->getStatusKey('audits_last_missing')] = array(
				'label' => __('Last audit missing'),
				'type' => 'warning'
			);
		}

		if ($this->getAllowCond($allow, 'maintenances_last_passed') && !$item['SecurityService']['maintenances_last_passed']) {
			$statuses[$this->getStatusKey('maintenances_last_passed')] = array(
				'label' => __('Last maintenance failed'),
				'type' => 'danger'
			);
		}

		if ($this->getAllowCond($allow, 'maintenances_last_missing') && $item['SecurityService']['maintenances_last_missing']) {
			$statuses[$this->getStatusKey('maintenances_last_missing')] = array(
				'label' => __('Last maintenance missing'),
				'type' => 'warning'
			);
		}

		if ($this->getAllowCond($allow, 'ongoing_corrective_actions') && $item['SecurityService']['ongoing_corrective_actions']) {
			$statuses[$this->getStatusKey('ongoing_corrective_actions')] = array(
				'label' => __('Ongoing Corrective Actions'),
				'type' => 'improvement'
			);
		}

		if ($this->getAllowCond($allow, 'security_service_type_id') && $item['SecurityService']['security_service_type_id'] == SECURITY_SERVICE_DESIGN) {
			$statuses[$this->getStatusKey('security_service_type_id')] = array(
				'label' => __('Control in Design'),
				'type' => 'warning'
			);
		}

		if ($this->getAllowCond($allow, 'control_with_issues') && $item['SecurityService']['control_with_issues']) {
			$statuses[$this->getStatusKey('control_with_issues')] = array(
				'label' => __('Control with Issues'),
				'type' => 'danger'
			);
		}

		$inherit = array(
			'SecurityIncidents' => array(
				'model' => 'SecurityIncident',
				'config' => array('ongoing_incident')
			),
			'Projects' => array(
				'model' => 'Projects',
				'config' => array('expired')
			),
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

	/**
	 * Returns status labels for audit and maintenance statuses.
	 *
	 */
	public function getStatusLabels($item, $includeType = false) {
		$msg = array();
		if (/*$includeType && */$item['security_service_type_id'] == SECURITY_SERVICE_DESIGN) {
			$msg[] = $this->Html->tag('span', __('Status is Design'), array('class' => 'label label-danger'));
		}
		else {
			if (!$item['audits_all_done']) {
				$msg[] = $this->Html->tag('span', __('Missing audits'), array('class' => 'label label-warning'));

			}
			if (!$item['audits_last_passed']) {
				$msg[] = $this->Html->tag('span', __('Last audit failed'), array('class' => 'label label-danger'));
			}
			if ($item['audits_improvements']) {
				$msg[] = $this->Html->tag('span', __('Being fixed'), array('class' => 'label label-primary'));
			}
			if ($item['audits_all_done'] && $item['audits_last_passed']) {
				$msg[] = $this->Html->tag('span', __('No audit issues'), array('class' => 'label label-success'));
			}

			if (!$item['maintenances_all_done']) {
				$msg[] = $this->Html->tag('span', __('Missing maintenances'), array('class' => 'label label-warning'));

			}
			if (!$item['maintenances_last_passed']) {
				$msg[] = $this->Html->tag('span', __('Last maintenance failed'), array('class' => 'label label-danger'));
			}
			if ($item['maintenances_all_done'] && $item['maintenances_last_passed']) {
				$msg[] = $this->Html->tag('span', __('No maintenance issues'), array('class' => 'label label-success'));
			}
		}

		return $msg;
	}

	public function statusLabels($item, $includeType = false, $implodeGlue = '<br>') {
		$labels = $this->getStatusLabels($item, $includeType);

		echo implode($implodeGlue, $labels);
	}

	/**
	 * Returns status labels for audit and maintenance statuses that has issues.
	 *
	 */
	public function getIssueStatusLabels($items) {
		$msg = array();

		$failedAudit = $missingAudit = $failedMaintenance = $missingMaintenance = false;
		foreach ($items as $item) {
			if (!$failedAudit && !$item['audits_last_passed']) {
				$msg[] = $this->Html->tag('span', __('Failed audit'), array('class' => 'label label-danger'));
				$failedAudit = true;
			}
			if (!$missingAudit && !$item['audits_all_done']) {
				$msg[] = $this->Html->tag('span', __('Missing audit'), array('class' => 'label label-warning'));
				$missingAudit = true;
			}

			if (!$failedMaintenance && !$item['maintenances_last_passed']) {
				$msg[] = $this->Html->tag('span', __('Failed maintenance'), array('class' => 'label label-danger'));
				$failedMaintenance = true;
			}
			if (!$missingMaintenance && !$item['maintenances_all_done']) {
				$msg[] = $this->Html->tag('span', __('Missing maintenance'), array('class' => 'label label-warning'));
				$missingMaintenance = true;
			}
		}

		return $msg;
	}

	public function issueStatusLabels($items, $implodeGlue = '<br>') {
		$labels = $this->getIssueStatusLabels($items);

		echo implode($implodeGlue, $labels);
	}

}