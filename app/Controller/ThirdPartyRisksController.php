<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');
App::uses('RiskAppetitesHelper', 'View/Helper');
App::uses('RiskClassification', 'Model');

class ThirdPartyRisksController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Pdf',  'Paginator', 'ObjectStatus.ObjectStatus',
		'CustomValidator.CustomValidator',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'ThirdPartyRisk'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'RiskMitigationStrategy' => [
							'fields' => ['id', 'name']
						],
						'Asset' => [
							'fields' => ['id', 'name', 'description', 'expired_reviews'],
						],
						'Threat' => [
							'fields' => ['name']
						],
						'Vulnerability' => [
							'fields' => ['name']
						],
						'SecurityService',
						'RiskException' => [
							'fields' => ['title', 'description', 'expiration', 'expired', 'status'],
						],
						'ThirdParty' => [
							'fields' => ['id', 'name', 'description'],
							'Legal' => [
								'fields' => ['name']
							]
						],
						'Comment',
						'Attachment',
						'Project' => [
							'fields' => ['id', 'title', 'goal', 'deadline', 'over_budget', 'expired_tasks', 'expired']
						],
						'SecurityPolicyIncident' => [
							'SecurityPolicyDocumentType'
						],
						'SecurityPolicyTreatment' => [
							'SecurityPolicyDocumentType'
						],
						'SecurityIncident'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'ReviewsPlanner.Reviews',
		'UserFields.UserFields' => [
			'fields' => ['Owner', 'Stakeholder']
		],
		'RisksManager'
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Third Party Risks');
		$this->subTitle = __('Manage your Third Party based risk management framework including the analysis, treatment and communication of risks. This module will require you to have identified Assets (under Asset Management / Asset Identification) and Third Parties (under Organisation / Third Parties)');

		$this->set('appetiteMethod', ClassRegistry::init('RiskAppetite')->getCurrentType());
	}

	public function index($view = 'listTPRisks') {
		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));
		$this->title = __('Third Party - Risk Analysis');

		$this->set('view', $view);

		$this->setIndexData();

		return $this->Crud->execute();
	}

	public function _afterPaginate(CakeEvent $event) {
		$model = $event->subject->model;
		foreach ($event->subject->items as $key => $item) {
			$event->subject->items[$key]['risk_appetite_threshold'] = [
				RiskClassification::TYPE_ANALYSIS => $model->riskThreshold(
					$item[$model->alias]['id'],
					RiskClassification::TYPE_ANALYSIS
				),
				RiskClassification::TYPE_TREATMENT => $model->riskThreshold(
					$item[$model->alias]['id'],
					RiskClassification::TYPE_TREATMENT
				),
			];
		}
	}

	private function setIndexData() {
		$this->set('riskClassificationData', $this->ThirdPartyRisk->RiskClassification->getAllData('ThirdPartyRisk'));
		$this->set('assetClassificationData', $this->ThirdPartyRisk->Asset->getAssetClassificationsData());
		
		$assetData = $this->ThirdPartyRisk->getAllHabtmData('Asset', array(
			'contain' => array(
				'RelatedAssets' => array(
					'fields' => array('id', 'name')
				),
				'Legal' => array(
					'fields' => array( 'id', 'name' )
				),
				'AssetLabel' => array(
					'fields' => array( 'name' )
				)
			)
		));

		$securityServicesData = $this->ThirdPartyRisk->getAllHabtmData('SecurityService', array(
			'contain' => $this->UserFields->attachFieldsToArray(['ServiceOwner'], [
				'SecurityServiceType'
			], 'SecurityService')
		));

		$riskExceptionData = $this->ThirdPartyRisk->getAllHabtmData('RiskException', array(
			'contain' => $this->UserFields->attachFieldsToArray(['Requester'], [], 'RiskException')
		));

		$this->set('assetData', $assetData);
		$this->set('riskExceptionData', $riskExceptionData);
		$this->set('securityServicesData', $securityServicesData);
	}

	public function _afterDelete(CakeEvent $event) {
		if ($event->subject->success) {
			$this->deleteJoins($event->subject->id);
		}
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Third Party Risk.');

		$this->Crud->on('afterDelete', array($this, '_afterDelete'));

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Third Party Risk');
		$this->initAddEditSubtitle();

		$this->Crud->on('beforeSave', array($this, '_beforeSave'));

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function _beforeSave(CakeEvent $event) {
		$this->request->data['ThirdPartyRisk']['RiskClassification'] = $this->fixClassificationIds();

		$this->invalidateDependencies();
	}

	public function edit( $id = null ) {
		$id = (int) $id;

		$this->title = __('Edit a Third Party Risk');
		$this->initAddEditSubtitle();

		$this->Crud->on('beforeSave', array($this, '_beforeSave'));

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function trash() {
	    $this->set( 'title_for_layout', __( 'Third Party Risks (Trash)' ) );
	    $this->set( 'subtitle_for_layout', __( 'This is the list of third party risks.' ) );

	    return $this->Crud->execute();
	}

	private function invalidateDependencies() {
		if ($this->request->data['ThirdPartyRisk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_ACCEPT) {
			if (empty($this->request->data['ThirdPartyRisk']['RiskException'])) {
				$this->ThirdPartyRisk->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->ThirdPartyRisk->validate['SecurityService']);
		}

		if ($this->request->data['ThirdPartyRisk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_AVOID) {
			if (empty($this->request->data['ThirdPartyRisk']['RiskException'])) {
				$this->ThirdPartyRisk->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->ThirdPartyRisk->validate['SecurityService']);
		}

		if ($this->request->data['ThirdPartyRisk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_MITIGATE) {
			if (empty($this->request->data['ThirdPartyRisk']['SecurityService'])) {
				$this->ThirdPartyRisk->invalidate('SecurityService', __('This field cannot be left blank'));
			}
			unset($this->ThirdPartyRisk->validate['RiskException']);
		}

		if ($this->request->data['ThirdPartyRisk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_TRANSFER) {
			if (empty($this->request->data['ThirdPartyRisk']['RiskException'])) {
				$this->ThirdPartyRisk->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->ThirdPartyRisk->validate['SecurityService']);
		}
	}

	private function fixClassificationIds() {
		$tmp = array();
		if (isset($this->request->data['ThirdPartyRisk']['RiskClassification'])) {
			foreach ( $this->request->data['ThirdPartyRisk']['RiskClassification'] as $classification_id ) {
				if ( $classification_id ) {
					$tmp[] = $classification_id;
				}
			}
		}

		return $tmp;
	}

	/**
	 * Process classification fields via ajax request.
	 * 
	 * @return void
	 */
	public function processClassifications()
	{
		return $this->RisksManager->processClassifications();
	}

	/**
	 * Delete all many to many joins in related tables.
	 * @param  integer $id Risk ID
	 */
	private function deleteJoins($id) {
		$ret = $this->ThirdPartyRisk->ThirdPartiesThirdPartyRisk->deleteAll( array(
			'ThirdPartiesThirdPartyRisk.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->AssetsThirdPartyRisk->deleteAll( array(
			'AssetsThirdPartyRisk.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->ThirdPartyRisksThreat->deleteAll( array(
			'ThirdPartyRisksThreat.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->ThirdPartyRisksVulnerability->deleteAll( array(
			'ThirdPartyRisksVulnerability.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->SecurityServicesThirdPartyRisk->deleteAll( array(
			'SecurityServicesThirdPartyRisk.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->RiskExceptionsThirdPartyRisk->deleteAll( array(
			'RiskExceptionsThirdPartyRisk.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->RiskClassificationsThirdPartyRisk->deleteAll( array(
			'RiskClassificationsThirdPartyRisk.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->ProjectsThirdPartyRisk->deleteAll( array(
			'ProjectsThirdPartyRisk.third_party_risk_id' => $id
		) );

		$ret &= $this->ThirdPartyRisk->RisksSecurityPolicy->deleteAll(array(
			'RisksSecurityPolicy.risk_id' => $id,
			'risk_type' => 'third-party-risk'
		));

		if ($ret) {
			return true;
		}

		return false;
	}

	/**
	 * Initialize options for join elements.
	 */
	public function initOptions() {
		$this->loadModel( 'RiskClassificationType' );

		$calculationMethod = $this->ThirdPartyRisk->getMethod();

		$mitigate_id = RISK_MITIGATION_MITIGATE;

		$accept_id = RISK_MITIGATION_ACCEPT;

		$transfer_id = RISK_MITIGATION_TRANSFER;
		
		$this->set('calculationMethod', $this->ThirdPartyRisk->getMethod());
		$this->set('classifications', $this->ThirdPartyRisk->getFormClassifications());
		$this->set('mitigate_id', $mitigate_id);
		$this->set('accept_id', $accept_id);
		$this->set('transfer_id', $transfer_id);
		$this->set('tags', $this->ThirdPartyRisk->Tag->getTags('ThirdPartyRisk'));
	}

	/*
	private function initFilterOptions(){
		$this->ThirdPartyRisk->virtualFields['user_name'] = $this->ThirdPartyRisk->User->virtualFields['full_name'];
		$owners = $this->ThirdPartyRisk->find('list', array(
			'contain' => array(
				'User'
			),
			'group' => array('User.id'),
			'fields' => array('User.id', 'ThirdPartyRisk.user_name'),
			'order' => array('ThirdPartyRisk.user_name' => 'ASC'),
		));

		$tps = $this->ThirdPartyRisk->ThirdParty->find('list', array(
			'fields' => array('ThirdParty.id', 'ThirdParty.name'),
			'order' => array('ThirdParty.name' => 'ASC')
		));

		$users = $this->getUsersList();

		$this->set( 'owners', $owners );
		$this->set( 'tps', $tps);
		$this->set('users', $users);
	}
	*/

	private function initAddEditSubtitle() {
		$this->subTitle  = __('Manage your Third Party based risk management framework including the analysis, treatment and communication of risks.');
	}

	public function exportPdf($id) {
		$item = $this->ThirdPartyRisk->find('first', array(
			'conditions' => array(
				'ThirdPartyRisk.id' => $id
			),
			'contain' => $this->UserFields->attachFieldsToArray(['Owner', 'Stakeholder'], array(
				'Comment' => array('User'),
				'Attachment',
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'RiskMitigationStrategy',
				'RiskClassification' => array(
					'RiskClassificationType'
				),
				'Asset' => array(
					'AssetLabel',
					'Legal'
				),
				'ThirdParty' => array(
					'fields' => array('id', 'name', 'description'),
					'Legal' => array(
						'fields' => array('name')
					)
				),
				'Threat',
				'Vulnerability',
				'RiskException' => $this->UserFields->attachFieldsToArray(['Requester'], [
					//'fields' => ['title', 'description', 'expiration', 'expired', 'status'],
				], 'RiskException'),
				'SecurityService' => array(
					'SecurityServiceType'
				),
				'SecurityPolicyIncident' => [
					'SecurityPolicyDocumentType'
				],
				'SecurityPolicyTreatment' => [
					'SecurityPolicyDocumentType'
				],
				'Project',
				'Review' => array(
					'User'
				),
				'CustomFieldValue'
			)),
			'recursive' => -1
		));

		$customFieldsData = $this->CustomFieldsMgt->setData();
		$item = array_merge($item, $customFieldsData);
		
		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		$subject = $this->Crud->trigger('afterPaginate', array(
			'success' => true,
			'viewVar' => 'item',
			'items' => [$item]
		));

		$item = $subject->items[0];
		
		$this->set('item', $item);
		$vars = array(
			'item' => $item
		);

		return $this->Pdf->renderPdfItem($item['ThirdPartyRisk']['title'], $vars);
	}

	public function getPolicies() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$controlIds = json_decode($this->request->query['controlIds']);
		$data = $this->ThirdPartyRisk->SecurityService->getSecurityPolicyIds($controlIds);

		echo json_encode($data);
	}

}
