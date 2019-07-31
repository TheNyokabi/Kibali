<?php
App::uses('ReportsController', 'Controller');
class ComplianceReportsController extends ReportsController {
	public $uses = array(
		'ThirdParty', 'ComplianceAudit'
	);

	public function index() {
		$this->set('title_for_layout', __('Compliance Report'));
		$this->set('hidePageHeader', true);

		$data = $this->ThirdParty->find( 'all', array(
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
							'SecurityService' => array(
								'fields' => array( 'id', 'name', 'opex', 'capex', 'resource_utilization' )
							),
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
		) );

		// third party analysis
		$non_applicable_item_list = $overlooked_item_list = $non_compliant_item_list = $third_party_names = array();
		$index = 0;
		$complianceData = $complianceSort = array();
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

			$compliance_management_count = $compliance_mitigate = $compliance_not_applicable = $compliance_overlooked = $compliance_not_compliant = 0;
			foreach ( $item['CompliancePackage'] as $compliance_package ) {
				foreach ( $compliance_package['CompliancePackageItem'] as $compliance_package_item ) {
					//if ( ! empty( $compliance_package_item['ComplianceManagement'] ) ) {
						$compliance_management_count++;
						if ( empty( $compliance_package_item['ComplianceManagement'] ) ) {
							$compliance_overlooked++;
							continue;
						}

						if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_APPLICABLE ) {
							$compliance_not_applicable++;
						}

						if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_MITIGATE ) {
							$compliance_mitigate++;
						}

						if ( $compliance_package_item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_COMPLIANT ) {
							$compliance_not_compliant++;
						}
					//}
				}
			}

			$complianceData[] = array(
				'thirdPartyName' => $item['ThirdParty']['name'],
				'count' => $compliance_management_count,
				'mitigate' => $compliance_mitigate,
				'notApplicable' => $compliance_not_applicable,
				'overlooked' => $compliance_overlooked,
				'notCompliant' => $compliance_not_compliant,
				'_sort' => ($compliance_not_applicable + $compliance_overlooked)
			);

			$complianceSort[] = ($compliance_not_applicable + $compliance_overlooked);
		}

		array_multisort($complianceSort, SORT_ASC, $complianceData);

		foreach ($complianceData as $item) {
			$not_applicable_items = $overlooked_items = 0;
			if ($item['count'] != 0) {
				$not_applicable_items = ($item['notApplicable'] / $item['count']) * 100;
				$overlooked_items = ($item['overlooked'] / $item['count']) * 100;
				$not_compliant_items = ($item['notCompliant'] / $item['count']) * 100;
			}

			$non_applicable_item_list[$index] = array($not_applicable_items, $index);
			$overlooked_item_list[$index] = array($overlooked_items, $index);
			$non_compliant_item_list[$index] = array($not_compliant_items, $index);
			$third_party_names[$index] = $item['thirdPartyName'];
			$index++;
		}

		$this->set( 'non_applicable_item_list', $non_applicable_item_list );
		$this->set( 'overlooked_item_list', $overlooked_item_list );
		$this->set( 'non_compliant_item_list', $non_compliant_item_list );
		$this->set( 'third_party_names', $third_party_names );

		$this->ThirdParty->bindModel( array(
			'hasMany' => array( 'ThirdPartyOvertimeGraph' )
		) );
		$data_overtime = $this->ThirdParty->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'ThirdPartyOvertimeGraph' => array(
					'conditions' => array(
						'ThirdPartyOvertimeGraph.created >=' => CakeTime::format( 'Y-m-d', CakeTime::fromString( '-1 year' ) )
					)
				)
			)
		) );

		// control statuses overtime graphs
		$index = 0;
		$no_controls_overtime = $failed_controls_overtime = $average_effectiveness_overtime = array();
		foreach ( $data_overtime as $item ) {
			if ( empty( $item['ThirdPartyOvertimeGraph'] ) )
				continue;

			$name = $item['ThirdParty']['name'];
			$no_controls_overtime[ $index ]['label'] = $name;
			$no_controls_overtime[ $index ]['data'] = array();

			$failed_controls_overtime[ $index ]['label'] = $name;
			$failed_controls_overtime[ $index ]['data'] = array();

			$average_effectiveness_overtime[ $index ]['label'] = $name;
			$average_effectiveness_overtime[ $index ]['data'] = array();
			foreach ( $item['ThirdPartyOvertimeGraph'] as $chart ) {
				$no_controls_overtime[ $index ]['data'][] = array( (int) $chart['timestamp'] * 1000, (int) $chart['no_controls'] );
				$failed_controls_overtime[ $index ]['data'][] = array( (int) $chart['timestamp'] * 1000, (int) $chart['failed_controls'] );
				$average_effectiveness_overtime[ $index ]['data'][] = array( (int) $chart['timestamp'] * 1000, (int) $chart['average_effectiveness'] );
			}

			$index++;
		}

		$this->set( 'no_controls_overtime', $no_controls_overtime );
		$this->set( 'failed_controls_overtime', $failed_controls_overtime );
		$this->set( 'average_effectiveness_overtime', $average_effectiveness_overtime );

		// opex, capex, resource utilization horizontal graph
		$opex_list = $capex_list = $resource_utilization_list = $third_party_names = array();
		$index = 0;
		$complianceData = $complianceSort = array();
		foreach ( $data as $key => $item ) {
			if ( empty( $item['CompliancePackage'] ) ) {
				continue;
			}

			$used_security_services = array();
			$opex = $capex = $resource_utilization = 0;
			foreach ( $item['CompliancePackage'] as $compliance_package ) {
				if ( empty( $compliance_package['CompliancePackageItem'] ) ) {
					continue;
				}

				foreach ( $compliance_package['CompliancePackageItem'] as $compliance_package_item ) {
					if ( empty( $compliance_package_item['ComplianceManagement']['SecurityService'] ) ) {
						continue;
					}

					foreach ( $compliance_package_item['ComplianceManagement']['SecurityService'] as $security_service ) {
						if ( in_array( $security_service['id'], $used_security_services ) ) {
							continue;
						}

						$opex += $security_service['opex'];
						$capex += $security_service['capex'];
						$resource_utilization += $security_service['resource_utilization'];

						$used_security_services[] = $security_service['id'];
					}
				}
			}

			if ( ! $opex && ! $capex && ! $resource_utilization ) {
				continue;
			}

			$complianceData[] = array(
				'thirdPartyName' => $item['ThirdParty']['name'],
				'opex' => $opex,
				'capex' => $capex,
				'resourceUtilization' => $resource_utilization,
				'_sort' => ($opex + $capex)
			);

			$complianceSort[] = ($opex + $capex);
		}

		array_multisort($complianceSort, SORT_ASC, $complianceData);

		foreach ($complianceData as $item) {
			$opex_list[ $index ] = array( $item['opex'], $index );
			$capex_list[ $index ] = array( $item['capex'], $index );
			$resource_utilization_list[ $index ] = array( $item['resourceUtilization'], $index );
			$third_party_names[ $index ] = $item['thirdPartyName'];
			$index++;
		}

		$this->set( 'cost_list', array(
			'opex' => $opex_list,
			'capex' => $capex_list,
			'resource_utilization' => $resource_utilization_list,
			'names' => $third_party_names
		) );

		////////////////////////////////

		// compliance audits findings graphs
		$this->ComplianceAudit->bindModel( array(
			'hasMany' => array( 'ComplianceAuditOvertimeGraph' )
		) );
		$data = $this->ComplianceAudit->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'ComplianceAuditOvertimeGraph'
			)
		) );

		$this->set( 'compliance_audit_overtime', $data );

		// third party audits findings graphs
		$this->ThirdParty->bindModel(array(
			'hasMany' => array('ThirdPartyAuditOvertimeGraph')
		));
		$data = $this->ThirdParty->find('all', array(
			'fields' => array('id', 'name'),
			'contain' => array(
				'ThirdPartyAuditOvertimeGraph'
			)
		));

		$this->set('third_party_audit_overtime', $data);

		$this->render('../Reports/compliance');
	}

}