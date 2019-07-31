<?php
App::uses('ReportsController', 'Controller');
class SecurityControlReportsController extends ReportsController {
	public $uses = array(
		'SecurityService', 'SecurityServiceClassification', 'BusinessContinuityPlan'
	);

	public function index() {
		$this->set('title_for_layout', __('Security Controls Report'));
		$this->set('hidePageHeader', true);

		$this->set('service_classification', $this->getServiceClassification());

		$this->set('security_policies', $this->getSecurityPolicies());

		$this->set('controlsByStatus', $this->getControlByStatus());
		$this->set('plansByStatus', $this->getPlanByStatus());

		$modelsToJoin = [
			'Risk',
			'ThirdPartyRisk',
			'BusinessContinuity',
			'DataAsset',
			'ComplianceManagement',
			'SecurityServiceAudit',
			'SecurityIncident'
		];

		$query = $this->SecurityService->buildJoinsQuery($modelsToJoin, 'LEFT');

		$query['fields'][] = 'SecurityService.id';
		$query['fields'][] = 'SecurityService.name';
		foreach ($modelsToJoin as $model) {
			$query['fields'][] = "COUNT({$model}.id) AS {$model}__count";
		}

		$query['recursive'] = -1;
		$query['group'] = ['SecurityService.id'];
		$query['contain'] = ['SecurityServiceAudit' => ['result']];

		$data = $this->SecurityService->find('all', $query);

		// top 10 used security services
		$top10_controls_tmp = array();
		foreach ($data as $item) {
			$useModels = ['Risk', 'ThirdPartyRisk', 'SecurityIncident', 'DataAsset', 'ComplianceManagement'];

			$values = [];
			foreach ($useModels as $model) {
				$values[] = $item[0][$model . '__count'];
			}

			$count = array_sum($values);
			$top10_controls_tmp[] = array(
				'label' => $item['SecurityService']['name'],
				'count' => $count
			);
		}

		$this->aasort( $top10_controls_tmp, 'count' );

		$top10_controls = array();

		$key = 0;
		foreach ( $top10_controls_tmp as $security_service ) {
			$tmp = array(
				'label' => $security_service['label'],
				'data' => array( array( $security_service['count'], 0 ) ),
				'bars' => array(
					'show' => true,
					'barWidth' => 0.1,
					'order' => 1
				)
			);

			$top10_controls[] = $tmp;

			if (++$key == 10) {
				break;
			}
		}

		$this->set( 'top10_controls', $top10_controls );

		// unused security services
		$unused_controls = array();
		foreach ( $data as $security_service ) {
			$useModels = [
				'Risk',
				'ThirdPartyRisk',
				'BusinessContinuity',
				'SecurityIncident',
				'DataAsset',
				'ComplianceManagement'
			];

			$unusedItem = true;
			foreach ($useModels as $model) {
				$unusedItem &= count($item[0][$model . '__count']) == 0;
			}

			if ($unusedItem) {
				$unused_controls[] = $security_service;
			}
		}

		$this->set( 'unused_controls', $unused_controls );

		// failed and missed audits
		$failed_audits_tmp = array();
		$missed_audits_tmp = array();
		foreach ( $data as $item ) {
			if ( empty( $item['SecurityServiceAudit'] ) ) {
				continue;
			}

			$audits_count = 0;
			$audits_failed = 0;
			$audits_missed = 0;
			foreach ( $item['SecurityServiceAudit'] as $audit ) {
				$audits_count++;
				if ( $audit['result'] == null ) {
					$audits_missed++;
				} else {
					if ( (int) $audit['result'] == AUDIT_FAILED ) {
						$audits_failed++;
					}
				}
			}

			$failed_audits_tmp[] = array(
				'label' => $item['SecurityService']['name'],
				'count' => CakeNumber::precision( ( $audits_failed / $audits_count ) * 100, 2 )
			);

			$missed_audits_tmp[] = array(
				'label' => $item['SecurityService']['name'],
				'count' => CakeNumber::precision( ( $audits_missed / $audits_count ) * 100, 2 )
			);
		}

		$this->aasort( $failed_audits_tmp, 'count' );
		$this->aasort( $missed_audits_tmp, 'count' );

		$failed_audits = array();
		$key = 0;
		foreach ( $failed_audits_tmp as $security_service ) {
			$tmp = array(
				'label' => $security_service['label'],
				'data' => array( array( $security_service['count'], 0 ) ),
				'bars' => array(
					'show' => true,
					'barWidth' => 0.1,
					'order' => 1
				)
			);

			$failed_audits[] = $tmp;

			if (++$key == 10) {
				break;
			}
		}

		$this->set( 'failed_audits', $failed_audits );

		$missed_audits = array();
		$key = 0;
		foreach ( $missed_audits_tmp as $security_service ) {
			$tmp = array(
				'label' => $security_service['label'],
				'data' => array( array( $security_service['count'], 0 ) ),
				'bars' => array(
					'show' => true,
					'barWidth' => 0.1,
					'order' => 1
				)
			);

			$missed_audits[] = $tmp;

			if (++$key == 10) {
				break;
			}
		}

		$this->set( 'missed_audits', $missed_audits );

		$this->render('../Reports/security_controls');
	}

	private function getServiceClassification() {
		// service classifications
		$data = $this->SecurityService->Classification->find('all', array(
			'fields' => array('name', 'COUNT(Classification.security_service_id) as controlCount'),
			'group' => array('Classification.name'),
			'recursive' => -1
		));
		//debug($data);


		$service_classification_list = array();
		foreach ( $data as $service_classification ) {
			$service_classification_list[] = array(
				'label' => $service_classification['Classification']['name'],
				'data' => (int) $service_classification[0]['controlCount']
			);
		}

		$categorized = $this->SecurityService->Classification->find('list', array(
			'fields' => array('security_service_id'),
			'group' => array('Classification.security_service_id'),
			'recursive' => -1
		));

		$uncategorized = $this->SecurityService->find('count', array(
			'conditions' => array(
				'SecurityService.id !=' => $categorized
			),
			'recursive' => -1
		));

		$service_classification_list[] = array(
			'label' => __('Uncategorized'),
			'data' => $uncategorized
		);

		return $service_classification_list;
	}

	private function getSecurityPolicies() {
		// security policies
		$data = $this->SecurityService->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'SecurityPolicy'
			),
			'recursive' => 1
		) );

		$controls_with_policies = $controls_with_draft_policies = $controls_without_policies = 0;
		foreach ( $data as $security_service ) {
			if ( empty( $security_service['SecurityPolicy'] ) ) {
				$controls_without_policies++;
			} else {
				$has_published_policy = false;
				foreach ( $security_service['SecurityPolicy'] as $security_policy ) {
					if ( ! $has_published_policy && $security_policy['status'] == SECURITY_POLICY_RELEASED ) {
						$has_published_policy = true;
					}
				}

				if ( $has_published_policy ) {
					$controls_with_policies++;
				} else {
					$controls_with_draft_policies++;
				}
			}
		}

		$security_policies = array(
			array(
				'label' => __( 'Controls With Policies' ),
				'data' => $controls_with_policies
			),
			array(
				'label' => __( 'Controls Without Policies' ),
				'data' => $controls_without_policies
			),
			array(
				'label' => __( 'Controls With Draft Policies' ),
				'data' => $controls_with_draft_policies
			)
		);

		return $security_policies;
	}

	private function getControlByStatus() {
		$statuses = array();

		$data = $this->SecurityService->find('all', array(
			'fields' => array('audits_all_done', 'audits_last_missing', 'maintenances_all_done', 'maintenances_last_missing'),
			'recursive' => -1
		));

		foreach ($data as $item) {
			$statuses = $this->addToControlStatuses($item['SecurityService'], $statuses);
		}

		return $statuses;
	}

	/**
	 * Updates control status counter.
	 */
	private function addToControlStatuses($item, $statuses) {
		if (empty($statuses)) {
			$statuses = array(
				array(
					'label' => __('All Audits Done'),
					'data' => 0
				),
				array(
					'label' => __('All Maintenances Done'),
					'data' => 0
				),
				array(
					'label' => __('Last Audit Missing'),
					'data' => 0
				),
				array(
					'label' => __('Last Maintenance Missing'),
					'data' => 0
				)
			);
		}

		if ($item['audits_all_done']) {
			$statuses[0]['data']++;
		}
		if ($item['maintenances_all_done']) {
			$statuses[1]['data']++;
		}
		if ($item['audits_last_missing']) {
			$statuses[2]['data']++;
		}
		if ($item['maintenances_last_missing']) {
			$statuses[3]['data']++;
		}

		return $statuses;
	}

	private function getPlanByStatus() {
		$statuses = array();

		$data = $this->BusinessContinuityPlan->find('all', array(
			'fields' => array('audits_all_done', 'audits_last_missing'),
			'recursive' => -1
		));

		foreach ($data as $item) {
			$statuses = $this->addToPlanStatuses($item['BusinessContinuityPlan'], $statuses);
		}
		
		return $statuses;
	}

	/**
	 * Updates plan status counter.
	 */
	private function addToPlanStatuses($item, $statuses) {
		if (empty($statuses)) {
			$statuses = array(
				array(
					'label' => __('All Audits Done'),
					'data' => 0
				),
				array(
					'label' => __('Last Audit Missing'),
					'data' => 0
				)
			);
		}

		if ($item['audits_all_done']) {
			$statuses[0]['data']++;
		}
		if ($item['audits_last_missing']) {
			$statuses[1]['data']++;
		}
		
		return $statuses;
	}

}