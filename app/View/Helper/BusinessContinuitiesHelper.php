<?php
App::uses('ErambaHelper', 'View/Helper');
class BusinessContinuitiesHelper extends ErambaHelper {
	public $helpers = array('NotificationSystem', 'Html');
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		$this->settings = $settings;
	}

	/**
	 * @deprecated
	 */
	public function getStatuses($item, $showAll = true) {
		$item = $this->processItemArray($item, 'BusinessContinuity');
		$statuses = array();

		if ($item['BusinessContinuity']['expired']) {
			$statuses[] = $this->getLabel(__('Risk Review Expired'), 'danger');
		}

		if ($item['BusinessContinuity']['exceptions_issues']) {
			$statuses[] = $this->getLabel(__('Exceptions with Issues'), 'danger');
		}

		$controlIssues = $item['BusinessContinuity']['controls_issues'];
		if ($controlIssues > 0) {
			if ($controlIssues == 1) {
				$label = 'warning';
			}
			if ($controlIssues == 2) {
				$label = 'danger';
			}
			if ($controlIssues == 3) {
				$label = 'primary';
			}
			$statuses[] = $this->getLabel(__('Controls with Issues'), $label);
		}

		$planIssues = $item['BusinessContinuity']['plans_issues'];
		if ($planIssues > 0) {
			if ($planIssues == 1) {
				$label = 'warning';
			}
			if ($planIssues == 2) {
				$label = 'danger';
			}
			if ($planIssues == 3) {
				$label = 'primary';
			}
			$statuses[] = $this->getLabel(__('Plans with Issues'), $label);
		}

		if ($item['BusinessContinuity']['residual_risk'] > RISK_APPETITE) {
			$statuses[] = $this->getLabel(__('Risk above appetite'), 'danger');
		}

		$statuses = $this->processStatusesGroup($statuses);

		$opts = array('inline' => false);
		if ($showAll) {
			$statuses = array_merge($statuses, $this->NotificationSystem->getStatuses($item));
			$opts['inline'] = true;
		}
		
		return $this->processStatuses($statuses, $opts);
	}

	/**
	 * Returns header widget status class based on associated items for a Risk.
	 * 
	 * @deprecated
	 */
	public function getHeaderClass($item) {
		$class = '';
		$item = $item['BusinessContinuity'];

		$controlIssues = $item['controls_issues'];
		$planIssues = $item['plans_issues'];

		if ($controlIssues == 1 || $planIssues == 1) {
			$class = 'widget-header-warning';
		}
		if ($item['expired'] || $item['exceptions_issues'] || $controlIssues == 2 || $planIssues == 2 || $item['residual_risk'] > RISK_APPETITE) {
			$class = 'widget-header-alert';
		}
		if ($controlIssues == 3 || $planIssues == 3) {
			$class = 'widget-header-improvement';
		}

		return $class;
	}

	/**
	 * @deprecated
	 */
	public function exceptionsExpired($exceptions) {
		foreach ($exceptions as $exception) {
			if ($this->isExpired($exception['expiration'], $exception['status'])) {
				return $this->Html->tag('span', __('Exception Expired'), array('class' => 'label label-danger'));
			}
		}
	}

}