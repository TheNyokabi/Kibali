<?php
App::uses('Hash', 'Utility');
App::uses('ThirdParty', 'Model');
App::uses('ComplianceTreatmentStrategy', 'Model');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');
App::uses('SectionBaseController', 'Controller');

class ComplianceManagementsController extends SectionBaseController {

	public $helpers = [];
	public $components = [
		'Pdf', 'Search.Prg', 'AdvancedFilters', 'Paginator', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete', 'analyze']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'CompliancePackage' => [
							'CompliancePackageItem' => [
								'order' => ['CompliancePackageItem.item_id' => 'ASC'],
								'ComplianceManagement' => [
									'SecurityService' => [
										'SecurityServiceType'
									],
									'SecurityPolicy',
									'ComplianceException' => [
										'conditions' => [
											'expired' => 1
										]
									],
									'Project',
								],
							]
						],
						'Comment'
					]
				],
				'analyze' => [
					'className' => 'AppIndex',
					'viewVar' => 'data',
					'contain' => [
						'CompliancePackage' => [
							'CompliancePackageItem' => [
								'order' => ['CompliancePackageItem.item_id' => 'ASC'],
								'ComplianceManagement' => [
									'SecurityService' => [
										'SecurityServiceType'
									],
									'SecurityPolicy',
									'ComplianceException',
									'Project',
									'Risk',
									'ThirdPartyRisk',
									'BusinessContinuity',
									'Legal',
									'Project',
									'Owner'
								],
								'Attachment',
								'Comment'
							]
						],
						'Comment'
					]
				]
			]
		],
		'CustomFields.CustomFieldsMgt' => array('model' => 'ComplianceManagement'),
	];

	public function beforeFilter() {
		$this->Ajax->settings['modules'] = ['comments', 'records', 'attachments'];

		$this->Crud->enable(['add', 'edit']);

		parent::beforeFilter();

		$this->title = __('Compliance Management');
		$this->subTitle = __('Manage your compliance requirement by mapping controls, risks, exceptions, projects for each Compliance Package Item you have previously defined.');
	}

	public function _afterPaginate(CakeEvent $event) {
		$this->attachStats($event->subject->items);
	}

	public function index() {
		if (empty($this->request->query['advanced_filter'])
			&& empty($this->request->data['ComplianceManagement']['advanced_filter'])
		) {
			$this->Crud->useModel('ThirdParty');
			$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));
		}

		$this->Crud->enable(['index']);

		$this->Paginator->settings['conditions'] = [
			'ThirdParty.id' => $this->getFilteredThirdPartyIds()
		];

		return $this->Crud->execute();
	}

	private function getFilteredThirdPartyIds() {
		$this->loadModel('ThirdParty');

		// we get the list of Third Party IDs to fetch paginated results from
		$thirdParties = $this->ThirdParty->find('all', array(
			'conditions' => [
				'ThirdParty.third_party_type_id' => ThirdParty::TYPE_REGULATORS
			],
			'fields' => array(
				'ThirdParty.id'
			),
			'contain' => array(
				'CompliancePackage' => array(
					'fields' => array('id'),
					'CompliancePackageItem' => array(
						'fields' => array('id')
					)
				)
			),
			'order' => array( 'ThirdParty.id' => 'ASC' )
		));

		$thirdParties = $this->ComplianceManagement->filterComplianceData($thirdParties);
		
		$thirdPartyIds = Hash::extract($thirdParties, '{n}.ThirdParty.id');

		return $thirdPartyIds;
	}

	private function attachStats(&$data) {
		foreach ($data as $key => $item) {
			$stats = [];

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
			]);
			$stats['compliance_management_count'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'compliance_treatment_strategy_id' => [ComplianceTreatmentStrategy::STRATEGY_COMPLIANT],
				'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IN
			]);
			$stats['compliance_mitigate'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'compliance_treatment_strategy_id' => [0],
				'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IS_NULL,
			]);
			$stats['compliance_overlooked'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'compliance_treatment_strategy_id' => [ComplianceTreatmentStrategy::STRATEGY_NOT_APPLICABLE],
				'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IN
			]);
			$stats['compliance_not_applicable'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'compliance_treatment_strategy_id' => [ComplianceTreatmentStrategy::STRATEGY_NOT_COMPLIANT],
				'compliance_treatment_strategy_id__comp_type' => AbstractQuery::COMPARISON_IN
			]);
			$stats['compliance_not_compliant'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'security_service_audits_last_missing' => 1,
			]);
			$stats['compliance_without_controls'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'security_service_audits_last_passed' => 0,
			]);
			$stats['failed_controls'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'security_policy_expired_reviews' => 1,
			]);
			$stats['missing_review'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$conditions = $this->AdvancedFilters->buildConditions('ComplianceManagement', [
				'third_party' => [$item['ThirdParty']['id']],
				'third_party__comp_type' => AbstractQuery::COMPARISON_IN,
				'asset_id' => [0],
				'asset_id__comp_type' => AbstractQuery::COMPARISON_IS_NOT_NULL,
			]);
			$stats['assets'] = $this->ComplianceManagement->find('count', ['conditions' => $conditions]);

			$data[$key]['stats'] = $stats;
		}
	}

	public function _afterPaginateAnalyze(CakeEvent $event) {
		if (!empty($event->subject->items)) {
			$event->subject->items = $event->subject->items[0];
			$this->title = __('Compliance Management:') . ' ' . $event->subject->items['ThirdParty']['name'];
		}
	}

	public function analyze($tp_id = null) {
		$this->Crud->useModel('ThirdParty');
		$this->Crud->enable(['index']);

		$this->CustomFieldsMgt->setData();

		// customized for filtering a single item
		if (!empty($this->request->query['id'])) {
			$filterData = $this->ComplianceManagement->find('first', array(
				'conditions' => array(
					'ComplianceManagement.id' => $this->request->query['id']
				),
				'fields' => array('ThirdParty.id'),
				'contain' => array('CompliancePackageItem'),
				'joins' => $this->ComplianceManagement->thirdPartyJoins,
				'recursive' => -1
			));
			$tp_id = $filterData['ThirdParty']['id'];
		}

		$this->Paginator->settings['conditions'] = [
			'ThirdParty.id' => $tp_id
		];

		$this->Crud->on('afterPaginate', array($this, '_afterPaginateAnalyze'));
		$this->Crud->on('beforeRender', array($this, '_beforeAnalyzeRender'));

		$this->render('analyze');

		return $this->Crud->execute();
	}

	public function _beforeAnalyzeRender(CAkeEvent $e) {
		$this->set('appetiteMethod', ClassRegistry::init('RiskAppetite')->getCurrentType());
	}

	private function getAnalyzeData($tp_id) {
		$this->loadModel( 'ThirdParty' );

		return $this->ThirdParty->find( 'first', array(
			'conditions' => array(
				'ThirdParty.id' => $tp_id
			),
			'fields' => array( 'ThirdParty.id', 'ThirdParty.name' ),
			'contain' => array(
				'CompliancePackage' => array(
					'CompliancePackageItem' => array(
						'order' => array('CompliancePackageItem.item_id' => 'ASC'),
						'ComplianceManagement' => array(
							'fields' => array('*'),
							'SecurityService'/* => array(
								'fields' => array( 'id', 'name' )
							)*/,
							'SecurityPolicy'/* => array(
								'fields' => array( 'id', 'index', 'status' )
							)*/,
							'ComplianceException'/* => array(
								'fields' => '*'
							)*/,
							'Risk'/* => array(
								'fields' => array( 'id', 'title' )
							)*/,
							'ThirdPartyRisk'/* => array(
								'fields' => array( 'id', 'title' )
							)*/,
							'BusinessContinuity'/* => array(
								'fields' => array( 'id', 'title' )
							)*/,
							'Legal'/* => array(
								'fields' => array( 'name' )
							)*/,
							'Project',
							'Owner',
							'Asset',
						),
						'Attachment' => array(
							'fields' => array( 'id' )
						),
						'Comment'  => array(
							'fields' => array( 'id' )
						)
					)
				)
			),
			'recursive' => 2
		) );
	}

	private function initAnalyzeOptions() {
		$strategies = $this->ComplianceManagement->getStrategies();

		$exceptions = $this->ComplianceManagement->ComplianceException->find('list', array(
			'order' => array('ComplianceException.title' => 'ASC'),
			'recursive' => -1
		));

		$this->set( 'strategies', $strategies );
		$this->set( 'exceptions', $exceptions );

		return array(
			'strategies' => $strategies,
			'exceptions' => $exceptions
		);
	}

	public function add($compliance_package_item_id = null) {
		$this->title = __('Create a Compliance Package');
		$this->initAddEditSubtitle();

		$tmp = $this->ComplianceManagement->CompliancePackageItem->find('first', array(
			'conditions' => array(
				'CompliancePackageItem.id' => $compliance_package_item_id
			),
			'recursive' => 0
		));

		$this->set('data', $tmp);

		$this->set('compliance_package_item_id', $compliance_package_item_id);

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Compliance Package');
		$this->initAddEditSubtitle();

		$data = $this->ComplianceManagement->find('first', array(
			'conditions' => array(
				'ComplianceManagement.id' => $id
			),
			'recursive' => 1
		));
		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('compliance_package_item_id', $data['ComplianceManagement']['compliance_package_item_id']);
		$this->set('complianceId', $id);
		$this->set('data', $data);
		$this->initOptions();

		return $this->Crud->execute();
	}

	/**
	 * Initialize options for join elements.
	 */
	private function initOptions() {
		$security_services = $this->ComplianceManagement->SecurityService->find('list', array(
			'conditions' => array(
				'SecurityService.security_service_type_id' => SECURITY_SERVICE_PRODUCTION
			),
			'order' => array('SecurityService.name' => 'ASC'),
			'recursive' => -1
		));

		$security_policies = $this->getSecurityPoliciesList();

		$this->set( 'security_services', $security_services );
		$this->set( 'security_policies', $security_policies );
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('You can manage your compliance requirement by mapping controls, risks, exceptions, projects for each Compliance Package Item you have previously defined');
	}

	/**
	 * CSV Export for specific compliance package items.
	 */
	public function export( $tp_id = null ) {
		$this->loadModel( 'ThirdParty' );
		$tmpData = $this->ThirdParty->find( 'first', array(
			'conditions' => array(
				'ThirdParty.id' => $tp_id
			),
			'fields' => array( 'ThirdParty.id', 'ThirdParty.name' ),
			'contain' => array(
				'CompliancePackage' => array(
					'CompliancePackageItem' => array(
						'ComplianceManagement' => array(
							'SecurityService' => array(
								'fields' => array( 'id', 'name' )
							),
							'SecurityPolicy' => array(
								'fields' => array( 'id', 'index' )
							),
							'Risk' => array(
								'fields' => array( 'id', 'title' )
							),
							'ThirdPartyRisk' => array(
								'fields' => array( 'id', 'title' )
							),
							'Project' => array(
								'fields' => array( 'id' , 'title' )
							),
							'BusinessContinuity' => array(
								'fields' => array( 'id', 'title' )
							),
							'ComplianceException' => array(
								'fields' => array( 'id', 'title' )
							),
							'Legal' => array(
								'fields' => array( 'id' , 'name' )
							),
							'Owner',
							'Asset'
						),
						'Attachment' => array(
							'fields' => array( 'id' )
						)
					)
				)
			),
			'recursive' => 2
		) );

		//debug($tmpData);

		//exit;

		$strategies = $this->ComplianceManagement->ComplianceTreatmentStrategy->find('list', array(
			'order' => array('ComplianceTreatmentStrategy.name' => 'ASC'),
			'recursive' => -1
		));

		$exceptions = $this->ComplianceManagement->ComplianceException->find('list', array(
			'order' => array('ComplianceException.title' => 'ASC'),
			'recursive' => -1
		));

		$data = array();
		foreach ( $tmpData['CompliancePackage'] as $compliancePackage ) {
			foreach ( $compliancePackage['CompliancePackageItem'] as $compliancePackageItem ) {
				if ( empty( $compliancePackageItem['ComplianceManagement'] ) ) {
					$treatmentStrategy = '-';
					$securityService = '-';
					$securityPolicy = '-';
					$risk = '-';
					$thirdPartyRisk = '-';
					$project = '-';
					$businessContinuity = '-';

					$complianceException = '-';
					$legal = '-';
					$owner = '-';
					$description = '-';
					$efficacy = '-';
				}
				else {
					$cmgt = $compliancePackageItem['ComplianceManagement'];

					$securityServices = array();
					foreach ( $cmgt['SecurityService'] as $security_service ) {
						$securityServices[] = $security_service['name'];
					}

					$securityPolicies = array();
					foreach ( $cmgt['SecurityPolicy'] as $security_policy ) {
						$securityPolicies[] = $security_policy['index'];
					}

					$risks = array();
					foreach ( $cmgt['Risk'] as $risk ) {
						$risks[] = $risk['title'];
					}

					$tprisks = array();
					foreach ( $cmgt['ThirdPartyRisk'] as $tprisk ) {
						$tprisks[] = $tprisk['title'];
					}

					$projects = array();
					foreach ( $cmgt['Project'] as $project ) {
						$projects[] = $project['title'];
					}

					$businessContinuities = array();
					foreach ( $cmgt['BusinessContinuity'] as $bcm ) {
						$businessContinuities[] = $bcm['title'];
					}

					$complianceExceptions = array();
					foreach ( $cmgt['ComplianceException'] as $exception ) {
						$complianceExceptions[] = $exception['title'];
					}

					$assets = array();
					foreach ( $cmgt['Asset'] as $asset ) {
						$assets[] = $asset['name'];
					}

					if (!empty($cmgt['compliance_treatment_strategy_id'])) {
						$treatmentStrategy = $strategies[$cmgt['compliance_treatment_strategy_id']];
					}
					else {
						$treatmentStrategy = __('None');
					}
					$securityService = implode(':', $securityServices);
					$securityPolicy = implode(':', $securityPolicies);
					$risk = implode(':', $risks);
					$thirdPartyRisk = implode(':', $tprisks);
					$project = implode(':', $projects);
					$businessContinuity = implode(':', $businessContinuities);
					$complianceException = implode(':', $complianceExceptions);
					$asset = implode(':', $assets);
					$legal = isset( $cmgt['legal_id'] ) ? $cmgt['Legal']['name'] : 'None';
					if (!empty($cmgt['Owner'])) {
						$owner = $cmgt['Owner']['name'] . ' ' . $cmgt['Owner']['surname'];
					}
					else {
						$owner = __('None');
					}
					$description = isset( $cmgt['description'] ) ? trim(preg_replace('/\s\s+/', ' ', $cmgt['description'])): '';
					$efficacy = CakeNumber::toPercentage($cmgt['efficacy'], 0);
				}

				$tmp = array(
					'ComplianceManagement' => array(
						'item_id' => $compliancePackageItem['item_id'],
						'name' => $compliancePackageItem['name'],
						'description' => trim(preg_replace('/\s\s+/', ' ', $compliancePackageItem['description'])),
						'treatment_strategy' => $treatmentStrategy,
						'security_service' => $securityService,
						'security_policy' => $securityPolicy,
						'risk' => $risk,
						'third_party_risk' => $thirdPartyRisk,
						'project' => $project,
						'business_continuity' => $businessContinuity,
						'compliance_exception' => $complianceException,
						'asset' => $asset,
						'legal' => $legal,
						'owner' => $owner,
						'description' => $description,
						'efficacy' => $efficacy
					)
				);

				$data[] = $tmp;
			}
		}

		$_serialize = 'data';
		$_header = array(
			'Compliance Package Item ID',
			'Compliance Package Name',
			'Compliance Package Description',
			'Treatment Strategy',
			'Mitigation Controls',
			'Security Policy Items',
			'Risks',
			'Third Party Risks',
			'Projects',
			'Assets',
			'Business Continuity Items',
			'Compliance Exception',
			'Legal',
			'Owner',
			'Description',
			'Efficacy'
		);
		$_extract = array(
			'ComplianceManagement.item_id',
			'ComplianceManagement.name',
			'ComplianceManagement.description',
			'ComplianceManagement.treatment_strategy',
			'ComplianceManagement.security_service',
			'ComplianceManagement.security_policy',
			'ComplianceManagement.risk',
			'ComplianceManagement.third_party_risk',
			'ComplianceManagement.project',
			'ComplianceManagement.asset',
			'ComplianceManagement.business_continuity',
			'ComplianceManagement.compliance_exception',
			'ComplianceManagement.legal',
			'ComplianceManagement.owner',
			'ComplianceManagement.description',
			'ComplianceManagement.efficacy',
		);

		$_bom = true;
		// $_newline = '\r\n';

		$title = $tmpData['ThirdParty']['name'];
		$this->response->download( 'compliance_management_' . $title . '.csv' );
		$this->viewClass = 'CsvView.Csv';
		$this->set( compact( 'data', '_serialize', '_header', '_extract', '_newline', '_bom' ) );
	}

	public function exportPdf($id = null) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->getAnalyzeData($id);
		$options = $this->initAnalyzeOptions();

		$this->set('item', $item);
		$vars = array(
			'item' => $item,
			'strategies' => $options['strategies'],
			'exceptions' => $options['exceptions']
		);

		$name = Inflector::slug($item['ThirdParty']['name'], '-');

		$this->Pdf->renderPdf($name, '..'.DS.'ComplianceManagements'.DS.'export_pdf', 'pdf', $vars, true);
	}

	public function getPolicies() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$securityServiceIds = json_decode($this->request->query['securityServiceIds']);
		$data = $this->ComplianceManagement->SecurityService->getSecurityPolicyIds($securityServiceIds);

		echo json_encode($data);
	}

}
