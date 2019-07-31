<?php
App::uses('Component', 'Controller');
class ReportsMgtComponent extends Component {
	public $components = array('AwarenessMgt');

	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

	/**
	 * Update all overtime charts. Best used with CRON daily.
	 */
	public function cron() {
		$this->updateAwarenessChart();
		$this->updateThirdPartyIncidentChart();
		$this->updateProjectChart();
		$this->updateComplianceAuditChart();
		$this->updateThirdPartyAuditChart();
		$this->updateRiskChart();
		$this->updateThirdPartyRiskChart();
		$this->updateThirdPartyChart();
	}

	/**
	 * Updates Awareness overtime graph.
	 */
	private function updateAwarenessChart() {
		$this->controller->loadModel('AwarenessProgram');
		$programs = $this->controller->AwarenessProgram->find('list', array(
			'conditions' => array(
				'AwarenessProgram.status' => AWARENESS_PROGRAM_STARTED
			),
			'fields' => array('AwarenessProgram.id', 'AwarenessProgram.title'),
			'recursive' => -1
		));

		$ret = true;
		$saveData = array();
		$timestamp = CakeTime::fromString( 'now' );
		foreach ($programs as $programId => $programTitle) {
			$users = $this->controller->AwarenessProgram->getAllUsers($programId);
			if (empty($users)) {
				continue;
			}
			
			$ignoredUsers = $this->controller->AwarenessProgram->getIgnoredUsers($programId);

			$count = count($users) - count($ignoredUsers);
			if (!$count) {
				continue;
			}

			$recurrence = $this->controller->AwarenessProgram->AwarenessProgramRecurrence->find('first', array(
				'conditions' => array(
					'AwarenessProgramRecurrence.awareness_program_id' => $programId
				),
				'order' => array(
					'AwarenessProgramRecurrence.start' => 'DESC'
				),
				'recursive' => 1
			));

			$recurrenceCount = $this->controller->AwarenessProgram->AwarenessProgramRecurrence->find('count', array(
				'conditions' => array(
					'AwarenessProgramRecurrence.awareness_program_id' => $programId
				)
			));

			$recurrences = $this->controller->AwarenessProgram->AwarenessProgramRecurrence->find('all', array(
				'conditions' => array(
					'AwarenessProgramRecurrence.awareness_program_id' => $programId
				),
				'order' => array(
					'AwarenessProgramRecurrence.start' => 'DESC'
				),
				'recursive' => 1
			));

			$correctCount = 0;
			$count2 = 0;
			$avg = 0;
			foreach ( $recurrences as $item ) {
				foreach ( $item['AwarenessTraining'] as $training ) {
					if ( $training['wrong'] == 0 ) {
						$correctCount++;
					}

					if ($training['wrong'] != 0) {
						$avg += $training['correct']/($training['wrong']+$training['correct']);
					}
					else {
						$avg += 1;
					}

					$count2++;
				}
			}

			$correct = CakeNumber::precision( ($correctCount / ($count*$recurrenceCount)) * 100, 2 );
			if (!$count2) {
				$average = 0;
			}
			else {
				$average = CakeNumber::precision( ($avg / $count2) * 100, 2 );
			}

			$did = $recurrence['AwarenessProgramRecurrence']['awareness_training_count'];
			$doing = CakeNumber::precision( ($did / $count) * 100, 2 );
			$missing = CakeNumber::precision( (($count - $did) / $count) * 100, 2 );
			$saveData[] = array(
				'awareness_program_id' => $programId,
				'title' => $programTitle,
				'doing' => $doing,
				'missing' => $missing,
				'correct_answers' => $correct,
				'average' => $average,
				'timestamp' => $timestamp
			);
		}

		if ( ! empty( $saveData ) ) {
			$this->controller->loadModel( 'AwarenessOvertimeGraph' );
			if ( ! $this->controller->AwarenessOvertimeGraph->saveAll( $saveData ) ) {
				echo __( 'Error occured during saving Awareness Graph values while running CRON.' );
			}
		}
	}

	/**
	 * Updates Third Party Incident graph for incident monthly count.
	 */
	private function updateThirdPartyIncidentChart() {
		$this->autoRender = false;

		$this->controller->loadModel( 'ThirdPartyIncidentOvertimeGraph' );
		$data1 = $this->controller->ThirdPartyIncidentOvertimeGraph->find( 'count', array(
		) );
		$lastMonth = CakeTime::format( 'Y-m-d', CakeTime::fromString( '-1 month' ) );
		$data2 = $this->controller->ThirdPartyIncidentOvertimeGraph->find( 'count', array(
			'conditions' => array(
				'ThirdPartyIncidentOvertimeGraph.created >=' => $lastMonth
			)
		) );

		if ( ! $data1 || ! $data2 ) {
			$this->controller->loadModel( 'ThirdParty' );
			$data = $this->controller->ThirdParty->find( 'all', array(
				'fields' => array( 'id', 'name', 'security_incident_count' ),
				'recursive' => -1
			) );

			$saveData = array();
			$timestamp = CakeTime::fromString( 'now' );
			foreach ( $data as $item ) {
				$saveData[] = array(
					'third_party_id' => $item['ThirdParty']['id'],
					'security_incident_count' => $item['ThirdParty']['security_incident_count'],
					'timestamp' => $timestamp
				);
			}

			if ( ! empty( $saveData ) ) {
				if ( ! $this->controller->ThirdPartyIncidentOvertimeGraph->saveAll( $saveData ) ) {
					echo __( 'Error occured during saving Third Party Incident Count Graph values while running CRON.' );
				}
			}
		}
	}

	/**
	 * Updates Projects overtime graph for budget status.
	 */
	private function updateProjectChart() {
		$this->autoRender = false;

		$this->controller->loadModel( 'Project' );
		$data = $this->controller->Project->find( 'all', array(
			'conditions' => array(
				'Project.project_status_id' => PROJECT_STATUS_ONGOING
			),
			'fields' => array( 'id', 'plan_budget' ),
			'contain' => array(
				'ProjectExpense' => array(
					'fields' => array( 'amount' )
				)
			),
			'order' => array(
				'Project.deadline' => 'ASC'
			),
			'limit' => 10
		) );

		$saveData = array();
		$timestamp = CakeTime::fromString( 'now' );
		foreach ( $data as $key => $item ) {
			$current_budget = 0;
			foreach ( $item['ProjectExpense'] as $expense ) {
				$current_budget += $expense['amount'];
			}

			$saveData[] = array(
				'project_id' => $item['Project']['id'],
				'current_budget' => $current_budget,
				'budget' => $item['Project']['plan_budget'],
				'timestamp' => $timestamp
			);
		}

		if ( ! empty( $saveData ) ) {
			$this->controller->loadModel( 'ProjectOvertimeGraph' );
			if ( ! $this->controller->ProjectOvertimeGraph->saveAll( $saveData ) ) {
				echo __( 'Error occured during saving Project Graph values while running CRON.' );
			}
		}
	}

	/**
	 * Updates Compliance Audits overtime graph for findings status pecentage.
	 */
	private function updateComplianceAuditChart() {
		$this->controller->loadModel( 'ComplianceAudit' );
		$data = $this->controller->ComplianceAudit->find( 'all', array(
			'fields' => array( 'id', 'name', 'compliance_finding_count' ),
			'contain' => array(
				'ComplianceFinding' => array(
					'fields' => array( 'id', 'compliance_finding_status_id', 'deadline' )
				),
				'ComplianceAuditSetting'
			)
		) );

		$timestamp = CakeTime::fromString( 'now' );
		$saveData = array();
		foreach ( $data as $key => $item ) {
			if ( ! (int) $item['ComplianceAudit']['compliance_finding_count'] ) {
				continue;
			}

			$today = CakeTime::format( 'Y-m-d', $timestamp );
			$findings_count = $item['ComplianceAudit']['compliance_finding_count'];
			$open_count = 0;
			$closed_count = 0;
			$expired_count = 0;
			foreach ( $item['ComplianceFinding'] as $finding ) {
				if ( $finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_OPEN ) {
					$open_count++;
				}
				if ( $finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_CLOSED ) {
					$closed_count++;
				}

				if ( $finding['deadline'] > $today ) {
					$expired_count++;
				}
			}

			$settingsCount = count($item['ComplianceAuditSetting']);
			$noEvidenceNeeded = $waitingForEvidence = $evidenceProvided = 0;
			foreach ($item['ComplianceAuditSetting'] as $setting) {
				if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_NOT_EVIDENCE_NEEDED) {
					$noEvidenceNeeded++;
				}
				if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_WAITING_FOR_EVIDENCE) {
					$waitingForEvidence++;
				}
				if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_EVIDENCE_PROVIDED) {
					$evidenceProvided++;
				}
			}

			$open_percentage = ( $open_count / $findings_count ) * 100;
			$closed_percentage = ( $closed_count / $findings_count ) * 100;
			$expired_percentage = ( $expired_count / $findings_count ) * 100;

			if ($settingsCount) {
				$no_evidence_percentage = ( $noEvidenceNeeded / $settingsCount ) * 100;
				$waiting_evidence_percentage = ( $waitingForEvidence / $settingsCount ) * 100;
				$provided_evidence_percentage = ( $evidenceProvided / $settingsCount ) * 100;
			}

			$saveData[] = array(
				'compliance_audit_id' => $item['ComplianceAudit']['id'],
				'open' => $open_percentage,
				'closed' => $closed_percentage,
				'expired' => $expired_percentage,
				'no_evidence' => $no_evidence_percentage,
				'waiting_evidence' => $waiting_evidence_percentage,
				'provided_evidence' => $provided_evidence_percentage,
				'timestamp' => $timestamp
			);
		}

		if ( ! empty( $saveData ) ) {
			$this->controller->loadModel( 'ComplianceAuditOvertimeGraph' );
			if ( ! $this->controller->ComplianceAuditOvertimeGraph->saveAll( $saveData ) ) {
				echo __( 'Error occured during saving Compliance Audit Graph values while running CRON.' );
			}
		}
	}

	/**
	 * Updates Third Party Audits overtime graph.
	 */
	private function updateThirdPartyAuditChart() {

		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$this->controller->loadModel( 'ThirdParty' );
		$data = $this->controller->ThirdParty->find('all', array(
			'contain' => array(
				'ComplianceAudit' => array(
					/*'conditions' => array(
						'start_date <=' => $today,
						'end_date >=' => $today
					),*/
					'fields' => array('id', 'name', 'compliance_finding_count', 'start_date', 'end_date'),
					'ComplianceFinding' => array(
						'fields' => array( 'id', 'compliance_finding_status_id', 'deadline' )
					),
					'ComplianceAuditSetting'
				)
				
			)
		));

		//debug($data);
		//exit;

		$timestamp = CakeTime::fromString( 'now' );
		$saveData = array();
		foreach ($data as $key => $item) {
			if (empty($item['ComplianceAudit'])) continue;

			$findings_count = 0;
			$settingsCount = 0;
			$open_count = 0;
			$closed_count = 0;
			$expired_count = 0;

			$noEvidenceNeeded = $waitingForEvidence = $evidenceProvided = 0;

			foreach ($item['ComplianceAudit'] as $audit) {
				if (!(int) $audit['compliance_finding_count']) {
					//continue;
				}

				$today = CakeTime::format( 'Y-m-d', $timestamp );
				$findings_count += $audit['compliance_finding_count'];
				foreach ( $audit['ComplianceFinding'] as $finding ) {
					if ( $finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_OPEN ) {
						$open_count++;
					}
					if ( $finding['compliance_finding_status_id'] == COMPLIANCE_FINDING_CLOSED ) {
						$closed_count++;
					}

					if ( $finding['deadline'] < $today ) {
						$expired_count++;
					}
				}

				$settingsCount += count($audit['ComplianceAuditSetting']);
				foreach ($audit['ComplianceAuditSetting'] as $setting) {
					if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_NOT_EVIDENCE_NEEDED) {
						$noEvidenceNeeded++;
					}
					if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_WAITING_FOR_EVIDENCE) {
						$waitingForEvidence++;
					}
					if ($setting['status'] == COMPLIANCE_AUDIT_STATUS_EVIDENCE_PROVIDED) {
						$evidenceProvided++;
					}
				}
			}

			$no_evidence_percentage = $waiting_evidence_percentage = $provided_evidence_percentage = 0;
			$open_percentage = $closed_percentage = $expired_percentage = null;

			if ($findings_count) {
				$open_percentage = ( $open_count / $findings_count ) * 100;
				$closed_percentage = ( $closed_count / $findings_count ) * 100;
				$expired_percentage = ( $expired_count / $findings_count ) * 100;
			}

			if ($settingsCount) {
				$no_evidence_percentage = ( $noEvidenceNeeded / $settingsCount ) * 100;
				$waiting_evidence_percentage = ( $waitingForEvidence / $settingsCount ) * 100;
				$provided_evidence_percentage = ( $evidenceProvided / $settingsCount ) * 100;
			}

			$saveData[] = array(
				'third_party_id' => $item['ThirdParty']['id'],
				'open' => $open_percentage,
				'closed' => $closed_percentage,
				'expired' => $expired_percentage,
				'no_evidence' => $no_evidence_percentage,
				'waiting_evidence' => $waiting_evidence_percentage,
				'provided_evidence' => $provided_evidence_percentage,
				'timestamp' => $timestamp
			);
		}

		if (!empty($saveData)) {
			$this->controller->loadModel('ThirdPartyAuditOvertimeGraph');
			if (!$this->controller->ThirdPartyAuditOvertimeGraph->saveAll($saveData)) {
				echo __('Error occured during saving Third Party Audit Graph values while running CRON.');
			}
		}
	}

	/**
	 * Updates Third Party Compliance overtime graph for controls status pecentage.
	 */
	private function updateThirdPartyChart() {
		$this->controller->loadModel('ThirdParty');
		$data = $this->controller->ThirdParty->find('all', array(
			'conditions' => array(
			),
			'fields' => array(
				'ThirdParty.id',
				'ThirdParty.name',
				'ThirdParty.description'
			),
			'contain' => array(
				'CompliancePackage' => array(
					'CompliancePackageItem' => array(
						'ComplianceManagement' => array(
							'SecurityService',
							'SecurityPolicy' => array(
								'fields' => array( 'id', 'index', 'status' )
							),
							'ComplianceException' => array(
								'fields' => array( 'id', 'title', 'expiration' )
							)
						)
					)
				)
			),
			'order' => array( 'ThirdParty.id' => 'ASC' ),
			'recursive' => 4
		));
		
		$securityServicesHelper = 'SecurityServices';
		$securityServicesHelperClass = 'SecurityServicesHelper';

		App::import('Helper', $securityServicesHelper);
		$securityServicesClass = new $securityServicesHelperClass(new View());

		$timestamp = CakeTime::fromString( 'now' );
		$saveData = array();
		foreach ( $data as $key => $item ) {
			if ( empty( $item['CompliancePackage'] ) ) {
				continue;
			}

			$hasItems = false;
			foreach ( $item['CompliancePackage'] as $compliance_package ) {
				foreach ( $compliance_package['CompliancePackageItem'] as $compliance_package_item ) {
					if ( ! $hasItems && ! empty( $compliance_package_item['ComplianceManagement'] ) ) {
						$hasItems = true;
					}
				}

			}
			if ( ! $hasItems ) {
				continue;
			}

			$compliance_management_count = $compliance_mitigate = $compliance_without_controls = $controls = 0;
			$failed_controls = 0;
			$ok_controls = 0;
			$efficacy = 0;
			$compliance_with_controls = 0;

			foreach ( $item['CompliancePackage'] as $compliance_package ) {
				foreach ( $compliance_package['CompliancePackageItem'] as $compliance_package_item ) {
					//if ( ! empty( $compliance_package_item['ComplianceManagement'] ) ) {
						$compliance_management_count++;
						if ( empty( $compliance_package_item['ComplianceManagement'] ) ) {
							continue;
						}

						if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_MITIGATE ) {
							$compliance_mitigate++;

							if ( empty( $compliance_package_item['ComplianceManagement']['SecurityService'] ) && empty( $compliance_package_item['ComplianceManagement']['SecurityPolicy'] ) ) {
								$compliance_without_controls++;
							}

							$efficacy += $compliance_package_item['ComplianceManagement']['efficacy'];
						}

						if (!empty($compliance_package_item['ComplianceManagement']['SecurityService'])) {
							$compliance_with_controls++;
						}

						$failed_controls_tmp = false;
						foreach ( $compliance_package_item['ComplianceManagement']['SecurityService'] as $security_service ) {
							// if ( ! $security_service['audits_all_done'] || ! $security_service['audits_last_passed'] || ! $security_service['maintenances_all_done'] || ! $security_service['maintenances_last_passed'] ) {
							// 	$failed_controls++;
							// } else {
							// 	$ok_controls++;
							// }
							
							$controlStatusArr = $securityServicesClass->getStatusArr($security_service);
							if (!empty($controlStatusArr)) {
								$failed_controls++;
								$failed_controls_tmp = true;
								break;
							}

							$controls++;
						}

						if (!$failed_controls_tmp) {
							$ok_controls++;
						}
					//}
				}
			}

			$no_controls_items = $failed_controls_items = $ok_controls_items = $efficacy_average = 0;
			if ( $compliance_management_count != 0 ) {
				if ($compliance_mitigate != 0) {
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

			$saveData[] = array(
				'third_party_id' => $item['ThirdParty']['id'],
				'no_controls' => $no_controls_items * 100,
				'failed_controls' => $failed_controls_items * 100,
				'ok_controls' => $ok_controls_items * 100,
				'average_effectiveness' => $efficacy_average,
				'timestamp' => $timestamp
			);
		}

		if ( ! empty( $saveData ) ) {
			$this->controller->loadModel( 'ThirdPartyOvertimeGraph' );
			if ( ! $this->controller->ThirdPartyOvertimeGraph->saveAll( $saveData ) ) {
				echo __( 'Error occured during saving Third Party Compliance Graph values while running CRON.' );
			}
		}
	}

	/**
	 * Updates Risk overtime graph for risk score and residual score.
	 */
	private function updateRiskChart() {
		$this->controller->loadModel( 'Risk' );

		$count = $this->controller->Risk->find( 'count' );
		if ( ! $count ) {
			return true;
		}

		//residual_score is in fact residual_risk
		$this->controller->Risk->virtualFields = array(
			'residual_score' => 'SUM(Risk.residual_risk)',
			'risk_score' => 'SUM(Risk.risk_score)',
			'risk_count' => 'COUNT(Risk.id)'
		);
		$risk = $this->controller->Risk->find( 'first', array(
			'fields' => array( 'residual_score', 'risk_score', 'risk_count' ),
			'recursive' => -1
		) );

		if ( $risk ) {
			$risk['Risk']['timestamp'] = CakeTime::fromString( 'now' );

			$this->controller->loadModel( 'RiskOvertimeGraph' );
			$this->controller->RiskOvertimeGraph->set( $risk['Risk'] );
			if ( ! $this->controller->RiskOvertimeGraph->save() ) {
				echo __( 'Error occured during saving Risk Graph values while running CRON.' );
			}
		}
	}

	/**
	 * Updates Third Party Risk overtime graph for risk score and residual score.
	 */
	private function updateThirdPartyRiskChart() {
		$this->controller->loadModel( 'ThirdPartyRisk' );

		$count = $this->controller->ThirdPartyRisk->find( 'count' );
		if ( ! $count ) {
			return true;
		}

		//residual_score is in fact residual_risk
		$this->controller->ThirdPartyRisk->virtualFields = array(
			'residual_score' => 'SUM(ThirdPartyRisk.residual_risk)',
			'risk_score' => 'SUM(ThirdPartyRisk.risk_score)',
			'risk_count' => 'COUNT(ThirdPartyRisk.id)'
		);
		$risk = $this->controller->ThirdPartyRisk->find( 'first', array(
			'fields' => array( 'residual_score', 'risk_score', 'risk_count' ),
			'recursive' => -1
		) );

		if ( $risk ) {
			$risk['ThirdPartyRisk']['timestamp'] = CakeTime::fromString( 'now' );

			$this->controller->loadModel( 'ThirdPartyRiskOvertimeGraph' );
			$this->controller->ThirdPartyRiskOvertimeGraph->set( $risk['ThirdPartyRisk'] );
			if ( ! $this->controller->ThirdPartyRiskOvertimeGraph->save() ) {
				echo __( 'Error occured during saving Third Party Risk Graph values while running CRON.' );
			}
		}

	}

}
