<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');
App::uses('DataAssetInstance', 'Model');
App::uses('Hash', 'Utility');

class DataAssetsController extends SectionBaseController {
	use SectionCrudTrait;

	public $uses = ['DataAsset', 'DataAssetInstance'];
	public $helpers = [];
	public $components = [
		'Search.Prg', 'Paginator', 'AdvancedFilters', 'Pdf', 'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'formDataModels' => ['DataAsset', 'DataAssetGdpr']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'DataAsset'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'class' => 'Filter'
				],
				'add' => [
					'saveMethod' => 'saveAssociated'
				],
				'edit' => [
					'saveMethod' => 'saveAssociated'
				],
			]
		],
	];

	public function beforeFilter() {
		$this->Ajax->settings['modules'] = ['comments', 'records', 'attachments'];
		
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Data Flows');
		$this->subTitle = __('For the data assets previously identified, describe the process of how it is created, processed, stored, tansmitted and disposed of in order to ensure that the correct controls are in place for each phase of the lifecycle of the data.');
	}

	public function index() {
		$this->handleItemRedirect();

		$result = $this->handleCrudAction('index');

		if ($result == false) {
			return $this->redirect(['controller' => 'dataAssetInstances', 'action' => 'index']);
		}
	}

	private function handleItemRedirect() {
		if (empty($this->request->query['advanced_filter']) && isset($this->request->query['id'])) {
			$data = $this->DataAsset->find('first', [
				'conditions' => [
					'DataAsset.id' => $this->request->query['id'],
				],
				'fields' => [
					'DataAsset.data_asset_status_id', 'DataAsset.data_asset_instance_id'
				],
				'recursive' => -1
			]);

			if (!empty($data)) {
				$this->redirect(['controller' => 'dataAssetInstances', 'action' => 'index', '?' => [
					'id' => $data['DataAsset']['data_asset_instance_id'],
					'data_asset_status_id' => $data['DataAsset']['data_asset_status_id']
				]]);
			}
		}
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Data asset.');

		return $this->Crud->execute();
	}

	public function add($dataAssetInstanceId = null) {
		$this->title = __('Analyse a Data Asset');
		$this->initAddEditSubtitle();

		$this->_commonUpdateProcess($dataAssetInstanceId);

		return $this->Crud->execute();
	}

	private function _setDataAssetGdprData() {
		$this->loadModel('DataAssetGdpr');

		$gdprData = $this->DataAssetGdpr->getFieldDataEntity()->getViewOptions();
		$gdprData['FieldDataCollectionGdpr'] = $gdprData['FieldDataCollection'];
		unset($gdprData['FieldDataCollection']);

		$this->set($gdprData);
	}

	private function _commonUpdateProcess($dataAssetInstanceId, $dataAssetId = null) {
		$dataAssetInstance = $this->DataAssetInstance->getItem($dataAssetInstanceId);
		if ($dataAssetInstance['DataAssetInstance']['analysis_unlocked'] != DataAssetInstance::ANALYSIS_STATUS_UNLOCKED) {
			throw new NotFoundException();
		}

		if ($dataAssetInstance['DataAssetSetting']['gdpr_enabled']) {
			$this->DataAsset->enableGdprValidation();
			if ($this->request->is('post') || $this->request->is('put')) {
				$this->DataAsset->DataAssetGdpr->setValidation($this->request->data['DataAsset']['data_asset_status_id']);
			}
		}

		$this->_setDataAssetGdprData();

		$this->request->data['DataAsset']['data_asset_instance_id'] = $dataAssetInstance['DataAssetInstance']['id'];
		$this->initOptions($dataAssetInstance['DataAssetInstance']['id'], $dataAssetId);
		$this->set('dataAssetInstance', $dataAssetInstance);
	}

	public function edit($id = null) {
		$this->title = __('Analyse a Data Asset');
		$this->initAddEditSubtitle();

		$data = $this->DataAsset->find('first', array(
			'conditions' => array(
				'DataAsset.id' => $id
			),
			'recursive' => -1
		));
		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->_commonUpdateProcess($data['DataAsset']['data_asset_instance_id'], $data['DataAsset']['id']);

		$this->Crud->on('beforeFind', array($this, '_beforeFind'));
		$this->Crud->on('afterFind', array($this, '_afterFind'));

		return $this->Crud->execute();
	}

	public function _beforeFind(CakeEvent $event) {
		$event->subject->query['recursive'] = 2;
	}

	public function _afterFind(CakeEvent $event) {
		$data = $event->subject->item;
		if (!empty($data['DataAssetGdpr']['DataAssetGdprDataType'])) {
			$data['DataAssetGdpr']['DataAssetGdprDataType'] = Hash::extract($data['DataAssetGdpr']['DataAssetGdprDataType'], '{n}.data_type');
		}
		if (!empty($data['DataAssetGdpr']['DataAssetGdprCollectionMethod'])) {
			$data['DataAssetGdpr']['DataAssetGdprCollectionMethod'] = Hash::extract($data['DataAssetGdpr']['DataAssetGdprCollectionMethod'], '{n}.collection_method');
		}
		if (!empty($data['DataAssetGdpr']['DataAssetGdprLawfulBase'])) {
			$data['DataAssetGdpr']['DataAssetGdprLawfulBase'] = Hash::extract($data['DataAssetGdpr']['DataAssetGdprLawfulBase'], '{n}.lawful_base');
		}
		if (!empty($data['DataAssetGdpr']['DataAssetGdprThirdPartyCountry'])) {
			$data['DataAssetGdpr']['DataAssetGdprThirdPartyCountry'] = Hash::extract($data['DataAssetGdpr']['DataAssetGdprThirdPartyCountry'], '{n}.third_party_country');
		}
		if (!empty($data['DataAssetGdpr']['DataAssetGdprArchivingDriver'])) {
			$data['DataAssetGdpr']['DataAssetGdprArchivingDriver'] = Hash::extract($data['DataAssetGdpr']['DataAssetGdprArchivingDriver'], '{n}.archiving_driver');
		}
        if (!empty($data['DataAssetGdpr']['ThirdPartyInvolved'])) {
            $data['DataAssetGdpr']['ThirdPartyInvolved'] = Hash::extract($data['DataAssetGdpr']['ThirdPartyInvolved'], '{n}.country_id');
        }

		$event->subject->item = $data;
	}

	private function initOptions($dataAssetInstanceId, $dataAssetId) {
		$securityServices = $this->DataAsset->SecurityService->find('list', array(
			'conditions' => array(
				'SecurityService.security_service_type_id !=' => SECURITY_SERVICE_DESIGN
			),
			'order' => array('SecurityService.name' => 'ASC'),
			'recursive' => -1
		));

		$thirdParties = $this->DataAsset->ThirdParty->find('list', array(
			'conditions' => array('ThirdParty._hidden' => 1),
			'order' => array('ThirdParty.id' => 'ASC'),
			'recursive' => -1
		));

		$parents = $this->DataAsset->find('all', [
			'conditions' => [
				'DataAsset.data_asset_instance_id' => $dataAssetInstanceId,
				'DataAsset.id !=' => $dataAssetId
			],
			'order' => ['DataAsset.order' => 'ASC'],
			'contain' => ['DataAssetStatus'],
			'recursive' => -1
		]);
		$order = [
			0 => __('1. Set this stage as the first one.')
		];
		foreach ($parents as $item) {
			$order[$item['DataAsset']['order']+1] = ($item['DataAsset']['order']+2) . '. put item after ' . $item['DataAsset']['title'] . ' (' . $item['DataAssetStatus']['name'] . ')';
		}

		$this->set('securityServices', $securityServices);
		$this->set('thirdParties', $thirdParties);
		$this->set('order', $order);
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('In the end, is your core data assets that you struggle to protect every day, isnt it?. It\'s important you identify for each data asset status (creation, modification, storage, transit and deletion) how those assets are protected.');
	}

	public function getStatusInfo($statusId) {
		$this->autoRender = false;
		echo $this->DataAsset->statusesInfo($statusId);
	}

	public function getAssociatedRiskData() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$riskIds = json_decode($this->request->query['riskIds']);
		$data = [
			'securityServices' => array_values($this->DataAsset->Risk->RisksSecurityService->find('list', [
				'conditions' => [
					'RisksSecurityService.risk_id' => $riskIds
				],
				'fields' => [
					'RisksSecurityService.security_service_id'
				]
			])),
			'projects' => array_values($this->DataAsset->Risk->ProjectsRisk->find('list', [
				'conditions' => [
					'ProjectsRisk.risk_id' => $riskIds
				],
				'fields' => [
					'ProjectsRisk.project_id'
				]
			])),
			'securityPolicies' => array_values($this->DataAsset->Risk->RisksSecurityPolicy->find('list', [
				'conditions' => [
					'RisksSecurityPolicy.risk_id' => $riskIds,
					'RisksSecurityPolicy.risk_type' => 'asset-risk',
				],
				'fields' => [
					'RisksSecurityPolicy.security_policy_id'
				]
			])),
		];

		echo json_encode($data);
	}

	public function getAssociatedThirdPartyRiskData() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$riskIds = json_decode($this->request->query['riskIds']);
		$data = [
			'securityServices' => array_values($this->DataAsset->ThirdPartyRisk->SecurityServicesThirdPartyRisk->find('list', [
				'conditions' => [
					'SecurityServicesThirdPartyRisk.third_party_risk_id' => $riskIds
				],
				'fields' => [
					'SecurityServicesThirdPartyRisk.security_service_id'
				]
			])),
			'projects' => array_values($this->DataAsset->ThirdPartyRisk->ProjectsThirdPartyRisk->find('list', [
				'conditions' => [
					'ProjectsThirdPartyRisk.third_party_risk_id' => $riskIds
				],
				'fields' => [
					'ProjectsThirdPartyRisk.project_id'
				]
			])),
			'securityPolicies' => array_values($this->DataAsset->ThirdPartyRisk->RisksSecurityPolicy->find('list', [
				'conditions' => [
					'RisksSecurityPolicy.risk_id' => $riskIds,
					'RisksSecurityPolicy.risk_type' => 'third-party-risk',
				],
				'fields' => [
					'RisksSecurityPolicy.security_policy_id'
				]
			])),
		];

		echo json_encode($data);
	}

	public function getAssociatedBusinessContinuityData() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$riskIds = json_decode($this->request->query['riskIds']);
		$data = [
			'securityServices' => array_values($this->DataAsset->BusinessContinuity->BusinessContinuitiesSecurityService->find('list', [
				'conditions' => [
					'BusinessContinuitiesSecurityService.business_continuity_id' => $riskIds
				],
				'fields' => [
					'BusinessContinuitiesSecurityService.security_service_id'
				]
			])),
			'projects' => array_values($this->DataAsset->BusinessContinuity->BusinessContinuitiesProjects->find('list', [
				'conditions' => [
					'BusinessContinuitiesProjects.business_continuity_id' => $riskIds
				],
				'fields' => [
					'BusinessContinuitiesProjects.project_id'
				]
			])),
			'securityPolicies' => array_values($this->DataAsset->BusinessContinuity->RisksSecurityPolicy->find('list', [
				'conditions' => [
					'RisksSecurityPolicy.risk_id' => $riskIds,
					'RisksSecurityPolicy.risk_type' => 'business-risk',
				],
				'fields' => [
					'RisksSecurityPolicy.security_policy_id'
				]
			])),
		];

		echo json_encode($data);
	}

	public function getAssociatedSecurityServices() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$serviceIds = json_decode($this->request->query['serviceIds']);
		$data = array_values($this->DataAsset->SecurityService->SecurityPoliciesSecurityService->find('list', [
			'conditions' => [
				'SecurityPoliciesSecurityService.security_service_id' => $serviceIds
			],
			'fields' => [
				'SecurityPoliciesSecurityService.security_policy_id'
			]
		]));

		echo json_encode($data);
	}

}
