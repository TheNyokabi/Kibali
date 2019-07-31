<?php
App::uses('SectionBaseController', 'Controller');
App::uses('AdvancedFiltersComponent', 'Controller/Component');
App::uses('SectionCrudTrait', 'Controller/Trait');
App::uses('RiskClassification', 'Model');
App::uses('RiskCalculation', 'Model');

class RisksController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = array(
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'Pdf', 'ObjectStatus.ObjectStatus',
		'CustomValidator.CustomValidator',
		'Ajax' => array(
			'actions' => array('add', 'edit', 'delete'),
			'modules' => array('comments', 'records', 'attachments', 'notifications')
		),
		'CustomFields.CustomFieldsMgt' => array('model' => 'Risk'),
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'RiskMitigationStrategy' => [
							'fields' => ['name']
						],
						'Asset' => [
							'fields' => ['expired_reviews']
						],
						'Threat' => [
							'fields' => ['name']
						],
						'Vulnerability' => [
							'fields' => ['name']
						],
						'SecurityService' => [],
						'RiskException' => [
							'fields' => ['title', 'description', 'expiration', 'expired', 'status'],
						],
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
	);

	public function beforeFilter() {
		$this->Auth->allow('processClassifications');
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Asset based - Risk Analysis');
		$this->subTitle = __('Manage your Asset based risk management framework including the analysis, treatment and communication of risks. This module will require you to have identified Assets (under Asset Management / Asset Identification).');
		
		$this->set('appetiteMethod', ClassRegistry::init('RiskAppetite')->getCurrentType());
	}

	/**
	 * Section callback to set additional variables and options for an action.
	 */
	public function _beforeRender(CakeEvent $event) {
		//index
		$this->setIndexData();
		
		//add/edit
		$this->initOptions();

		parent::_beforeRender($event);
	}

	public function index( $view = 'listRisks' ) {
		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));
		$this->set('view', $view);

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
		$this->set('riskClassificationData', $this->Risk->RiskClassification->getAllData('Risk'));
		$this->set('assetClassificationData', $this->Risk->Asset->getAssetClassificationsData());

		$assetData = $this->Risk->getAllHabtmData('Asset', array(
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

		$securityServicesData = $this->Risk->getAllHabtmData('SecurityService', array(
			'contain' => $this->UserFields->attachFieldsToArray(['ServiceOwner'], [
				'SecurityServiceType'
			], 'SecurityService')
		));

		$riskExceptionData = $this->Risk->getAllHabtmData('RiskException', array(
			'contain' => $this->UserFields->attachFieldsToArray(['Requester'], [], 'RiskException')
		));

		$this->set('assetData', $assetData);
		$this->set('riskExceptionData', $riskExceptionData);
		$this->set('securityServicesData', $securityServicesData);
	}

	public function delete($id = null) {
		$this->title =  __('Risk');
		$this->subTitle = __('Delete a Risk.');
		return $this->Crud->execute();
	}

	public function add() {
		$this->title =  __('Create a Risk');
		$this->subTitle = __('Manage your Asset Based risk management framework including the analysis, treatment and communication of risks.');
		return $this->Crud->execute();
	}

	public function edit( $id = null ) {
		$this->title =  __('Edit a Risk');
		$this->subTitle = __('Manage your Asset Based risk management framework including the analysis, treatment and communication of risks.');
		return $this->Crud->execute();
	}

	public function trash() {
	    $this->set('title_for_layout', __('Risks (Trash)'));
	    return $this->Crud->execute();
	}

	private function setValidationErrorFlash() {
		$errorMsg = __('One or more inputs you entered are invalid. Please try again.');
		if (!empty($this->Risk->validationErrors['risk_score'])) {
			$errorMsg = implode('<br />', $this->Risk->validationErrors['risk_score']);
		}

		$this->Session->setFlash($errorMsg, FLASH_ERROR);
	}

	private function invalidateDependencies() {
		if ($this->request->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_ACCEPT) {
			if (empty($this->request->data['Risk']['RiskException'])) {
				$this->Risk->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->Risk->validate['SecurityService']);
		}

		if ($this->request->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_AVOID) {
			if (empty($this->request->data['Risk']['RiskException'])) {
				$this->Risk->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->Risk->validate['SecurityService']);
		}

		if ($this->request->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_MITIGATE) {
			if (empty($this->request->data['Risk']['SecurityService'])) {
				$this->Risk->invalidate('SecurityService', __('This field cannot be left blank'));
			}
			unset($this->Risk->validate['RiskException']);
		}

		if ($this->request->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_TRANSFER) {
			if (empty($this->request->data['Risk']['RiskException'])) {
				$this->Risk->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->Risk->validate['SecurityService']);
		}
	}

	private function fixClassificationIds() {
		$tmp = array();
		if (!empty($this->request->data['Risk']['RiskClassification'])) {
			foreach ( $this->request->data['Risk']['RiskClassification'] as $classification_id ) {
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

	public function calculateRiskScoreAjax() {
		// $this->allowOnlyAjax();
		$this->autoRender = false;

		$assetIds = json_decode($this->request->data['relatedItemIds']);
		$classificationIds = json_decode($this->request->data['classificationIds']);

		$riskScore = $this->Risk->calculateRiskScore($classificationIds, $assetIds);
		$appetiteThreshold = $this->Risk->RiskClassification->getRiskAppetiteThreshold($classificationIds);

		echo json_encode(array(
			'riskScore' => $riskScore,
			'riskAppetite' => RISK_APPETITE,
			'riskCalculationMath' => $this->Risk->getCalculationMath(),
			'otherData' => $this->Risk->getOtherData(),
			'classificationCriteria' => $this->Risk->RiskClassification->getRiskCriteria($classificationIds),
			'riskAppetiteThreshold' => [
				'data' => $appetiteThreshold,
				'class' => RiskAppetitesHelper::colorClasses($appetiteThreshold['color'])
			]
		));
	}

	public function getThreatsVulnerabilities() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$assetIds = json_decode($this->request->data['assocIds']);
		$data = $this->Risk->Asset->getThreatsVulnerabilities($assetIds);

		echo json_encode($data);
	}

	public function getPolicies() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$controlIds = json_decode($this->request->query['controlIds']);
		$data = $this->Risk->SecurityService->getSecurityPolicyIds($controlIds);

		echo json_encode($data);
	}

	/**
	 * Initialize options for join elements.
	 */
	public function initOptions() {
		$mitigate_id = RISK_MITIGATION_MITIGATE;
		$accept_id = RISK_MITIGATION_ACCEPT;
		$transfer_id = RISK_MITIGATION_TRANSFER;
		
		$this->set( 'mitigate_id', $mitigate_id );
		$this->set( 'accept_id', $accept_id );
		$this->set( 'transfer_id', $transfer_id );
		$this->set('calculationMethod', $this->Risk->getMethod());
		$this->set( 'classifications', $this->Risk->getFormClassifications() );
		$this->_initAdditionalOptions();
	}

	/**
	 * Additional Risk score data thats used for magerit calculation
	 * 
	 * @return void
	 */
	protected function _initAdditionalOptions()
	{
		$sectionValues = $this->Risk->getSectionValues();
		$calculationValues = $this->Risk->getClassificationTypeValues($sectionValues);
		$calculationMethod = $this->Risk->getMethod();

		if ($calculationMethod == RiskCalculation::METHOD_MAGERIT) {
			$specialClassificationTypeUsed = $calculationValues[1];
			$specialClassificationTypeData = $this->Risk->RiskClassification->RiskClassificationType->find('first', array(
				'conditions' => array(
					'RiskClassificationType.id' => $specialClassificationTypeUsed
				),
				'order' => array('RiskClassificationType.name' => 'ASC'),
				'recursive' => 1
			));
			$this->set('specialClassificationTypeData', $specialClassificationTypeData);
		}
	}

	/*
	private function initFilterOptions() {
		$this->Risk->virtualFields['user_name'] = $this->Risk->User->virtualFields['full_name'];
		$owners = $this->Risk->find('list', array(
			'contain' => array(
				'User'
			),
			'group' => array('User.id'),
			'fields' => array('User.id', 'Risk.user_name'),
			'order' => array('Risk.user_name' => 'ASC'),
		));

		$assets = $this->Risk->Asset->find('list', array(
			'fields' => array('Asset.id', 'Asset.name'),
			'order' => array('Asset.name' => 'ASC'),
		));

		$users = $this->getUsersList();

		$this->set( 'owners', $owners );
		$this->set( 'assets', $assets );
		$this->set('users', $users);

	}
	*/

	public function exportPdf($id) {
		$item = $this->Risk->find('first', array(
			'conditions' => array(
				'Risk.id' => $id
			),
			'contain' => $this->UserFields->attachFieldsToArray(['Owner', 'Stakeholder'], array(
				'Asset' => array(
					'AssetLabel',
					'Legal'
				),
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'RiskClassification' => array(
					'RiskClassificationType'
				),
				'Threat',
				'Vulnerability',
				'RiskException' => $this->UserFields->attachFieldsToArray(['Requester'], [
					//'fields' => ['title', 'description', 'expiration', 'expired', 'status']
				], 'RiskException'),
				'SecurityService' => array(
					'SecurityServiceType',
				),
				'Project',
				'RiskMitigationStrategy',
				'Review' => array(
					'User'
				),
				'SecurityPolicyIncident' => [
					'SecurityPolicyDocumentType'
				],
				'SecurityPolicyTreatment' => [
					'SecurityPolicyDocumentType'
				],
				'SecurityIncident',
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

		return $this->Pdf->renderPdfItem($item['Risk']['title'], $vars);
	}

}
