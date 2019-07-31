<?php
App::uses('ReportsController', 'Controller');
class RiskReportsController extends ReportsController {
	public $uses = array(
		'Risk', 'ThirdPartyRisk', 'BusinessContinuity', 'RiskException', 'BusinessUnit', 'AssetLabel', 'Legal',
		'RiskOvertimeGraph', 'ThirdPartyRiskOvertimeGraph', 'SecurityService'
	);

	/**
	 * Risk Report.
	 */
	public function index() {
		$this->set('title_for_layout', __('Risk Report'));
		$this->set('hidePageHeader', true);

		$this->set('mitigationStrategies', $this->getRiskMitigationStrategy());
		$this->set('riskByStatus', $this->getRiskByStatus());
		$this->set('topRiskExceptions', $this->getTopRiskExceptions());

		$data = $this->BusinessUnit->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'Asset' => array(
					'fields' => array( 'id' ),
					'Risk' => array(
						'fields' => array( 'id', 'title', 'risk_score' ),
						'SecurityService' => array(
							'fields' => array( 'id', 'opex', 'capex', 'resource_utilization' )
						)
					)
				)
			),
			'order' => array( 'BusinessUnit.id' => 'ASC' )
		) );

		$risk_score_list = array();
		$resource_utilization_list = array();
		$opex_list = array();
		$capex_list = array();


		foreach ( $data as $bu ) {
			$used_security_services = array();
			$used_risks = array();

			$bu_name = $bu['BusinessUnit']['name'];
			$risk_score = 0;
			$resource_utilization = 0;
			$opex = 0;
			$capex = 0;
			foreach ( $bu['Asset'] as $asset ) {
				foreach ( $asset['Risk'] as $risk ) {
					if ( in_array( $risk['id'], $used_risks ) ) {
						continue;
					}
					$risk_score += $risk['risk_score'];

					$used_risks[] = $risk['id'];
					foreach ( $risk['SecurityService'] as $security_service ) {
						if ( in_array( $security_service['id'], $used_security_services ) ) {
							continue;
						}

						$resource_utilization += $security_service['resource_utilization'];
						$opex += $security_service['opex'];
						$capex += $security_service['capex'];

						$used_security_services[] = $security_service['id'];
					}
				}
			}

			$risk_score_list[] = array(
				'label' => $bu_name,
				'data' => $risk_score
			);
			$resource_utilization_list[] = array(
				'label' => $bu_name,
				'data' => $resource_utilization
			);
			$opex_list[] = array(
				'label' => array( $bu_name, CakeNumber::currency( $opex ) ),
				'data' => $opex
			);
			$capex_list[] = array(
				'label' => array( $bu_name, CakeNumber::currency( $capex ) ),
				'data' => $capex
			);
		}

		if (empty($risk_score)) {
			$risk_score_list = array();
		}
		if (empty($resource_utilization)) {
			$resource_utilization_list = array();
		}
		if (empty($opex)) {
			$opex_list = array();
		}
		if (empty($capex)) {
			$capex_list = array();
		}

		$this->set( 'risk_score', $risk_score_list );
		$this->set( 'resource_utilization', $resource_utilization_list );
		$this->set( 'opex', $opex_list );
		$this->set( 'capex', $capex_list );

		$data = $this->AssetLabel->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'Asset' => array(
					'fields' => array( 'id', 'name' )
				)
			),
			'order' => array( 'AssetLabel.id' => 'ASC' ),
			'recursive' => 1
		) );

		$asset_label_list = array();
		foreach ( $data as $asset_label ) {
			$asset_label_list[] = array(
				'label' => $asset_label['AssetLabel']['name'],
				'data' => count( $asset_label['Asset'] )
			);
		}

		$this->set( 'asset_label', $asset_label_list );

		$data = $this->Legal->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'Asset' => array(
					'fields' => array( 'id', 'name' )
				)
			),
			'order' => array( 'Legal.id' => 'ASC' ),
			'recursive' => 1
		) );

		$legal_list = array();
		foreach ( $data as $legal ) {
			$legal_list[] = array(
				'label' => $legal['Legal']['name'],
				'data' => count( $legal['Asset'] )
			);
		}

		$this->set( 'legal', $legal_list );

		// Risk Overtime Graph
		$data = $this->RiskOvertimeGraph->find( 'all', array(
			'conditions' => array(
				'RiskOvertimeGraph.created >=' => CakeTime::format( 'Y-m-d', CakeTime::fromString( '-1 year' ) )
			),
			'fields' => array( 'id', 'risk_score', 'residual_score', 'timestamp' ),
			'order' => array( 'RiskOvertimeGraph.id' => 'ASC' )
		) );

		$risk_score_overtime_list = array();
		$residual_score_overtime_list = array();
		foreach ( $data as $risk_graph_entry ) {
			$timestamp = ( (int) $risk_graph_entry['RiskOvertimeGraph']['timestamp'] ) * 1000;
			$risk_score_item = (int) $risk_graph_entry['RiskOvertimeGraph']['risk_score'];
			$residual_score_item = (int) $risk_graph_entry['RiskOvertimeGraph']['residual_score'];

			$risk_score_overtime_list[] = array( $timestamp, (int) $risk_score_item );
			$residual_score_overtime_list[] = array( $timestamp, (int) $residual_score_item );
		}

		$this->set( 'risk_score_overtime_list', $risk_score_overtime_list );
		$this->set( 'residual_score_overtime_list', $residual_score_overtime_list );

		// Third Party Risk Overtime Graph
		$data = $this->ThirdPartyRiskOvertimeGraph->find( 'all', array(
			'conditions' => array(
				'ThirdPartyRiskOvertimeGraph.created >=' => CakeTime::format( 'Y-m-d', CakeTime::fromString( '-1 year' ) )
			),
			'fields' => array( 'id', 'risk_score', 'residual_score', 'timestamp' ),
			'order' => array( 'ThirdPartyRiskOvertimeGraph.id' => 'ASC' )
		) );

		$tp_risk_score_overtime_list = array();
		$tp_residual_score_overtime_list = array();
		foreach ( $data as $risk_graph_entry ) {
			$timestamp = ( (int) $risk_graph_entry['ThirdPartyRiskOvertimeGraph']['timestamp'] ) * 1000;
			$risk_score_item = (int) $risk_graph_entry['ThirdPartyRiskOvertimeGraph']['risk_score'];
			$residual_score_item = (int) $risk_graph_entry['ThirdPartyRiskOvertimeGraph']['residual_score'];

			$tp_risk_score_overtime_list[] = array( $timestamp, (int) $risk_score_item );
			$tp_residual_score_overtime_list[] = array( $timestamp, (int) $residual_score_item );
		}

		$this->set( 'tp_risk_score_overtime_list', $tp_risk_score_overtime_list );
		$this->set( 'tp_residual_score_overtime_list', $tp_residual_score_overtime_list );

		// Top 10 Risks
		$this->Risk->virtualFields = array(
			'total_score' => '(Risk.residual_risk + Risk.risk_score)'
		);
		$data = $this->Risk->find( 'all', array(
			'fields' => array(
				'id',
				'title',
				'risk_score',
				'residual_risk',
				'total_score'
			),
			'order' => array('Risk.total_score' => 'DESC'),
			'limit' => 10,
			'recursive' => -1
		) );

		$data = array_reverse($data);

		$top10_risk_names = array();
		$top10_risk_score = array();
		$top10_residual_score = array();
		$top10_total_score = array();
		foreach ( $data as $key => $risk ) {
			$top10_risk_names[ $key ] = $risk['Risk']['title'];
			$top10_risk_score[ $key ] = array( (int) $risk['Risk']['risk_score'], (int) $key );
			$top10_residual_score[ $key ] = array( $risk['Risk']['residual_risk'], (int) $key );
			$top10_total_score[ $key ] = array( (int) $risk['Risk']['total_score'], (int) $key );
		}
		$this->set( 'top10_risk_names', $top10_risk_names );
		$this->set( 'top10_risk_score', $top10_risk_score );
		$this->set( 'top10_residual_score', $top10_residual_score );
		$this->set( 'top10_total_score', $top10_total_score );

		// Top 10 Third Party Risks
		$this->ThirdPartyRisk->virtualFields = array(
			'total_score' => '(ThirdPartyRisk.residual_risk + ThirdPartyRisk.risk_score)'
		);
		$data = $this->ThirdPartyRisk->find( 'all', array(
			'fields' => array(
				'id',
				'title',
				'risk_score',
				'residual_risk',
				'total_score'
			),
			'order' => array('ThirdPartyRisk.total_score' => 'DESC'),
			'limit' => 10,
			'recursive' => -1
		) );

		$data = array_reverse($data);

		$top10_tp_risk_names = array();
		$top10_tp_risk_score = array();
		$top10_tp_residual_score = array();
		$top10_tp_total_score = array();
		foreach ( $data as $key => $risk ) {
			$top10_tp_risk_names[ $key ] = $risk['ThirdPartyRisk']['title'];
			$top10_tp_risk_score[ $key ] = array( (int) $risk['ThirdPartyRisk']['risk_score'], (int) $key );
			$top10_tp_residual_score[ $key ] = array( (int) $risk['ThirdPartyRisk']['residual_risk'], (int) $key );
			$top10_tp_total_score[ $key ] = array( (int) $risk['ThirdPartyRisk']['total_score'], (int) $key );
		}

		$this->set( 'top10_tp_risk_names', $top10_tp_risk_names );
		$this->set( 'top10_tp_risk_score', $top10_tp_risk_score );
		$this->set( 'top10_tp_residual_score', $top10_tp_residual_score );
		$this->set( 'top10_tp_total_score', $top10_tp_total_score );

		// Top 10 Security Services
		/*$this->SecurityService->virtualFields = array(
			'risk_count' => 'COUNT(SecurityService.Risk)'
		);*/
		$data = $this->SecurityService->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'Risk' => array(
					'fields' => array( 'id' )
				)
			),
			'limit' => 10,
			'recursive' => -1
		) );

		$top10_controls_tmp = array();
		foreach ( $data as $security_service ) {
			$top10_controls_tmp[] = array(
				'label' => $security_service['SecurityService']['name'],
				'count' => count( $security_service['Risk'] )
			);
		}

		$this->aasort( $top10_controls_tmp, 'count' );

		$this->render('../Reports/risk');
	}

	private function getRiskMitigationStrategy() {
		$strategies = getMitigationStrategies();

		$riskByMitigationStrategy = array();
		foreach ($strategies as $id => $label) {
			/*$sum = 0;

			$this->Risk->virtualFields = array(
				'total_score' => 'SUM(residual_risk + risk_score)'
			);
			$data = $this->Risk->find('first', array(
				'conditions' => array(
					'Risk.risk_mitigation_strategy_id' => $id
				),
				'fields' => array('total_score'),
				'recursive' => -1
			));

			$sum += (int) $data['Risk']['total_score'];

			$this->ThirdPartyRisk->virtualFields['total_score'] = $this->Risk->virtualFields['total_score'];
			$data = $this->ThirdPartyRisk->find('first', array(
				'conditions' => array(
					'ThirdPartyRisk.risk_mitigation_strategy_id' => $id
				),
				'fields' => array('total_score'),
				'recursive' => -1
			));
			$sum += (int) $data['ThirdPartyRisk']['total_score'];

			$this->BusinessContinuity->virtualFields['total_score'] = $this->Risk->virtualFields['total_score'];
			$data = $this->BusinessContinuity->find('first', array(
				'conditions' => array(
					'BusinessContinuity.risk_mitigation_strategy_id' => $id
				),
				'fields' => array('total_score'),
				'recursive' => -1
			));
			$sum += (int) $data['BusinessContinuity']['total_score'];*/

			$sum = $this->Risk->find('count', array(
				'conditions' => array(
					'Risk.risk_mitigation_strategy_id' => $id
				),
				'recursive' => -1
			));

			$sum += $this->ThirdPartyRisk->find('count', array(
				'conditions' => array(
					'ThirdPartyRisk.risk_mitigation_strategy_id' => $id
				),
				'recursive' => -1
			));

			$sum += $this->BusinessContinuity->find('count', array(
				'conditions' => array(
					'BusinessContinuity.risk_mitigation_strategy_id' => $id
				),
				'recursive' => -1
			));

			$riskByMitigationStrategy[] = array(
				'label' => $label,
				'data' => $sum
			);
		}

		return $riskByMitigationStrategy;
	}

	/**
	 * Risk chart data by status.
	 */
	private function getRiskByStatus() {
		$statuses = array();

		$data = $this->Risk->find('all', array(
			'fields' => array('residual_risk', 'expired', 'exceptions_issues', 'controls_issues'),
			'recursive' => -1
		));

		foreach ($data as $item) {
			$statuses = $this->addToRiskStatuses($item['Risk'], $statuses);
		}

		$data = $this->ThirdPartyRisk->find('all', array(
			'fields' => array('residual_risk', 'expired', 'exceptions_issues', 'controls_issues'),
			'recursive' => -1
		));

		foreach ($data as $item) {
			$statuses = $this->addToRiskStatuses($item['ThirdPartyRisk'], $statuses);
		}

		$data = $this->BusinessContinuity->find('all', array(
			'fields' => array('residual_risk', 'expired', 'exceptions_issues', 'controls_issues'),
			'recursive' => -1
		));

		foreach ($data as $item) {
			$statuses = $this->addToRiskStatuses($item['BusinessContinuity'], $statuses);
		}


		return $statuses;
	}

	/**
	 * Updates risk status counter.
	 */
	private function addToRiskStatuses($item, $statuses) {
		if (empty($statuses)) {
			$statuses = array(
				array(
					'label' => __('Exceptions Issues'),
					'data' => 0
				),
				array(
					'label' => __('Controls Issues'),
					'data' => 0
				),
				array(
					'label' => __('Expired'),
					'data' => 0
				),
				array(
					'label' => __('Risk Over Appetite'),
					'data' => 0
				),
				array(
					'label' => __('Ok'),
					'data' => 0
				)
			);
		}

		$hasIssue = false;
		if ($item['exceptions_issues']) {
			$statuses[0]['data']++;
			$hasIssue = true;
		}
		if ($item['controls_issues']) {
			$statuses[1]['data']++;
			$hasIssue = true;
		}
		if ($item['expired']) {
			$statuses[2]['data']++;
			$hasIssue = true;
		}
		if ($item['residual_risk'] > RISK_APPETITE) {
			$statuses[3]['data']++;
			$hasIssue = true;
		}

		if (!$hasIssue) {
			$statuses[4]['data']++;
		}

		return $statuses;
	}

	/**
	 * Top 10 Risk Exceptions.
	 */
	private function getTopRiskExceptions() {
		$topRiskExceptions = array();

		/*$this->Risk->virtualFields = array(
			'total_residual' => 'SUM(residual_score)',
			'total_risk' => 'SUM(risk_score)',
			'total_score' => 'SUM(residual_score) + SUM(risk_score)'
		);*/
		$data = $this->RiskException->find('all', array(
			'fields' => array(
				'id',
				'title'
			),
			'contain' => array(
				'Risk' => array(
					'fields' => array('id')
				),
				'ThirdPartyRisk' => array(
					'fields' => array('id')
				),
				'BusinessContinuity' => array(
					'fields' => array('id')
				),
			)
		));


		foreach ($data as $item) {
			$score = $this->getRisksScores($item);


			$topRiskExceptions[] = array(
				'title' => $item['RiskException']['title'],
				'totalResidual' => $score['total_residual'],
				'totalRisk' => $score['total_risk'],
				'totalScore' => $score['total_score']
			);
		}

		$this->aasort($topRiskExceptions, 'totalScore');
		return $topRiskExceptions;
	}

	/**
	 * Get risk scores sum for a risk exception.
	 */
	private function getRisksScores($item) {
		$riskIds = Hash::extract($item, 'Risk.{n}.id');
		$thirdPartyRiskIds = Hash::extract($item, 'ThirdPartyRisk.{n}.id');
		$businessContinuityIds = Hash::extract($item, 'BusinessContinuity.{n}.id');

		$total_score = $total_risk = $total_residual = 0;

		$this->Risk->virtualFields = array(
			'total_residual' => 'SUM(residual_risk)',
			'total_risk' => 'SUM(risk_score)',
			'total_score' => 'SUM(residual_risk + risk_score)'
		);

		if (!empty($riskIds)) {
			$data = $this->Risk->find('first', array(
				'conditions' => array(
					'Risk.id' => $riskIds
				),
				'fields' => array('total_residual', 'total_risk', 'total_score'),
				'recursive' => -1
			));

			$total_residual += (int) $data['Risk']['total_residual'];
			$total_risk += (int) $data['Risk']['total_risk'];
			$total_score += (int) $data['Risk']['total_score'];
		}

		if (!empty($thirdPartyRiskIds)) {
			$this->ThirdPartyRisk->virtualFields['total_residual'] = $this->Risk->virtualFields['total_residual'];
			$this->ThirdPartyRisk->virtualFields['total_risk'] = $this->Risk->virtualFields['total_risk'];
			$this->ThirdPartyRisk->virtualFields['total_score'] = $this->Risk->virtualFields['total_score'];

			$data = $this->ThirdPartyRisk->find('first', array(
				'conditions' => array(
					'ThirdPartyRisk.id' => $thirdPartyRiskIds
				),
				'fields' => array('total_residual', 'total_risk', 'total_score'),
				'recursive' => -1
			));

			$total_residual += (int) $data['ThirdPartyRisk']['total_residual'];
			$total_risk += (int) $data['ThirdPartyRisk']['total_risk'];
			$total_score += (int) $data['ThirdPartyRisk']['total_score'];
		}

		if (!empty($businessContinuityIds)) {
			$this->BusinessContinuity->virtualFields['total_residual'] = $this->Risk->virtualFields['total_residual'];
			$this->BusinessContinuity->virtualFields['total_risk'] = $this->Risk->virtualFields['total_risk'];
			$this->BusinessContinuity->virtualFields['total_score'] = $this->Risk->virtualFields['total_score'];

			$data = $this->BusinessContinuity->find('first', array(
				'conditions' => array(
					'BusinessContinuity.id' => $businessContinuityIds
				),
				'fields' => array('total_residual', 'total_risk', 'total_score'),
				'recursive' => -1
			));

			$total_residual += (int) $data['BusinessContinuity']['total_residual'];
			$total_risk += (int) $data['BusinessContinuity']['total_risk'];
			$total_score += (int) $data['BusinessContinuity']['total_score'];
		}

		return array(
			'total_residual' => $total_residual,
			'total_risk' => $total_risk,
			'total_score' => $total_score
		);
	}
}