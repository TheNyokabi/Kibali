<?php
App::uses('AppHelper', 'View/Helper');
App::uses('StatusInterface', 'View/Helper');

class ComplianceManagementsHelper extends AppHelper implements StatusInterface {
	public $settings = array();
	public $helpers = array('Html', 'Risk', 'Status', 'SecurityServices', 'Projects', 'Assets');

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatusArr($item, $allow = '*', $modelName = null) {
		$item = $this->Status->processItemArray($item, 'ComplianceManagement');
		$statuses = array();

		/*if ($this->getAllowCond($allow, 'audits_last_passed') && !$item['SecurityService']['audits_last_passed']) {
			$statuses[$this->getStatusKey('audits_last_passed')] = array(
				'label' => __('Last audit failed'),
				'type' => 'danger'
			);
		}*/

		$riskConfig = ['expired_reviews'];

		$appetiteConds = $this->_View->get('appetiteMethod') == RiskAppetite::TYPE_INTEGER;
		if ($appetiteConds) {
			$riskConfig[] = 'risk_above_appetite';
		}

		$inherit = array(
			'Projects' => array(
				'model' => 'Project',
				'config' => array('expired', 'expired_tasks', 'over_budget')
			),
			'SecurityPolicies' => array(
				'model' => 'SecurityPolicy',
				'config' => array('expired_reviews')
			),
			'Risks' => array(
				array(
					'model' => 'Risk',
					'config' => $riskConfig
				),
				array(
					'model' => 'ThirdPartyRisk',
					'config' => $riskConfig
				),
				array(
					'model' => 'BusinessContinuity',
					'config' => $riskConfig
				)
			),
			'PolicyExceptions' => array(
				'model' => 'ComplianceException',
				'config' => array('expired')
			),
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
			)
		);

		if ($this->Status->getAllowCond($allow, INHERIT_CONFIG_KEY)) {
			$statuses = am($statuses, $this->Status->getInheritedStatuses($item, $inherit));
		}

		return $statuses;
	}

	public function getStatuses($item, $modelName = null, $options = array()) {
		$options = $this->Status->processStatusOptions($options);
		$statuses = $this->getStatusArr($item, $options['allow']);
		
		return $this->Status->styleStatuses($statuses, $options);
	}

	/**
	 * Package statistics caluclation helper function.
	 */
	public function getPackagesStats($packages) {
		// debug($packages);
		$compliance_management_count = $compliance_management_count_analysed = $compliance_mitigate = $compliance_not_applicable = $compliance_overlooked = $compliance_without_controls = $controls = $compliance_not_compliant = $compliance_with_controls = $missing_review = 0;
		$failed_controls = 0;
		$ok_controls = 0;
		$efficacy = 0;
		foreach ( $packages as $compliance_package ) {
			foreach ( $compliance_package['CompliancePackageItem'] as $compliance_package_item ) {
				//if ( ! empty( $compliance_package_item['ComplianceManagement'] ) ) {
					$compliance_management_count++;

					if (empty($compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'])) {
						$compliance_overlooked++;
					}

					$compliance_management_count_analysed++;

					if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_APPLICABLE ) {
						$compliance_not_applicable++;
					}

					if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_COMPLIANT ) {
						$compliance_not_compliant++;
					}

					if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_MITIGATE ) {
						$compliance_mitigate++;
						$efficacy += $compliance_package_item['ComplianceManagement']['efficacy'];
					}

					// 'model' => 'SecurityService',
					// 		'field' => 'audits_last_missing'

					if (!empty($compliance_package_item['ComplianceManagement']['SecurityService'])) {
						$compliance_with_controls++;
					}

					$has_compliance_without_controls = false;
					$has_failed_controls = false;
					foreach ( $compliance_package_item['ComplianceManagement']['SecurityService'] as $security_service ) {
						if ($security_service['audits_last_missing']) {
							$has_compliance_without_controls = true;
						}
						if (!$security_service['audits_last_passed']) {
							$has_failed_controls = true;
						}
						// debug($this->SecurityServices->getStatusArr($security_service));

						/*if ( ! $security_service['audits_all_done'] || ! $security_service['audits_last_passed'] || ! $security_service['maintenances_all_done'] || ! $security_service['maintenances_last_passed'] ) {*/
						
						// $controlStatusArr = $this->SecurityServices->getStatusArr($security_service);
						// debug($controlStatusArr);
						// if (!empty($controlStatusArr)) {
						// 	$failed_controls++;
						// 	$failed_controls_tmp = true;
						// 	break;
						// }
						/*else {
							$ok_controls++;
						}*/

						$controls++;
					}

					$has_missing_review = false;
					foreach ($compliance_package_item['ComplianceManagement']['SecurityPolicy'] as $security_policy) {
						if ($security_policy['expired_reviews']) {
							$has_missing_review = true;
						}
					}

					if ($has_compliance_without_controls) {
						$compliance_without_controls++;
					}
					if ($has_failed_controls) {
						$failed_controls++;
					}
					else {
						$ok_controls++;
					}
					if ($has_missing_review) {
						$missing_review++;
					}
				//}
			}
		}
		// debug( $failed_controls );
		$overlooked_items = $not_applicable_items = $addressed_items = $no_controls_items = $failed_controls_items = $ok_controls_items = $efficacy_average = $not_compliant_item = 0;

		if ( $compliance_management_count != 0 ) {
			$overlooked_items = $compliance_overlooked / $compliance_management_count;
			$not_applicable_items = $compliance_not_applicable / $compliance_management_count;
			$addressed_items = $compliance_mitigate / $compliance_management_count;
			$not_compliant_item = $compliance_not_compliant / $compliance_management_count;
			if ($compliance_mitigate == 0) {
				$no_controls_items = 0;
				$efficacy_average = 0;
			}
			else {
				$no_controls_items = $compliance_without_controls / $compliance_mitigate;
				$efficacy_average = $efficacy / $compliance_mitigate;
			}


			if ( $compliance_with_controls != 0 ) {
				if ($compliance_mitigate != 0) {
					$failed_controls_items = $failed_controls / $compliance_mitigate;
				}
				$ok_controls_items = $ok_controls / $compliance_with_controls;
			}
		}

		$reviewed = CakeNumber::toPercentage(1 - $overlooked_items, 0, array('multiply' => true));
		$reviewedItemsCount = $compliance_management_count - $compliance_overlooked;

		$reviewedLabel = __('(%s Reviewed)', $reviewed);

		return array(
			'compliance_management_count' => $compliance_management_count,
			'reviewedLabel' => $reviewedLabel,
			'addressed_items' => $addressed_items,
			'compliance_mitigate' => $compliance_mitigate,
			'overlooked_items' => $overlooked_items,
			'compliance_overlooked' => $compliance_overlooked,
			'not_applicable_items' => $not_applicable_items,
			'compliance_not_applicable' => $compliance_not_applicable,
			'not_compliant_item' => $not_compliant_item,
			'compliance_not_compliant' => $compliance_not_compliant,
			'no_controls_items' => $no_controls_items,
			'compliance_without_controls' => $compliance_without_controls,
			'failed_controls_items' => $failed_controls_items,
			'failed_controls' => $failed_controls,
			'efficacy' => $efficacy,
			'efficacy_average' => $efficacy_average,
			'missing_review' => $missing_review,
		);
	}
}