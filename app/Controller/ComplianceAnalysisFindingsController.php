<?php
App::uses('Hash', 'Utility');
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ComplianceAnalysisFindingsController extends SectionBaseController {
	use SectionCrudTrait;
	
	public $helpers = ['UserFields.UserField'];
	public $components = ['Paginator', 'Pdf', 'Search.Prg', 'AdvancedFilters', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['add', 'edit'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'ComplianceAnalysisFinding'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'ComplianceManagement' => ['fields' => ['id']],
						'Comment',
						'Attachment',
						'Tag'
					]
				]
			]
		],
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => ['Owner', 'Collaborator']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Compliance Analysis Findings');
		$this->subTitle = __('Use this section to document your compliance findings (while you receive external audits or as they pop in the organisation. You can also document compliance findings from the Compliance Analysis module (Under Compliance Management).');

		$this->Auth->allow('loadPackageItems');
	}

	public function index() {
		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		$this->initOptions();

		$this->Prg->commonProcess('ComplianceAnalysisFinding');
		unset($this->request->data['ComplianceAnalysisFinding']);

		$filterConditions = $this->ComplianceAnalysisFinding->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions) && empty($this->request->query['advanced_filter'])) {
			$this->Paginator->settings['conditions'] = $filterConditions;
			$this->set('filterConditions', true);
		}

		return $this->Crud->execute();
	}

	public function _afterPaginate(CakeEvent $event) {
		$data = $event->subject->items;

		$findingIds = Hash::extract($data, '{n}.ComplianceAnalysisFinding.id');
		$findingIds = array_unique($findingIds);

		$commonComplianceData = $this->ComplianceAnalysisFinding->getCommonComplianceData($findingIds);

		$this->set('commonComplianceData', $commonComplianceData);
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Finding.');

		return $this->Crud->execute();
	}

	public function trash() {
		$this->set('title_for_layout', __( 'Compliance Findings (Trash)'));

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create an Compliance Analysis Finding');

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function edit( $id = null ) {
		$this->title = __('Edit a Compliance Analysis Finding');

		$this->Crud->on('afterFind', array($this, '_afterFind'));

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function _afterFind(CakeEvent $event) {
		$data = $event->subject->item;
		$data['ComplianceAnalysisFinding']['ThirdParty'] = Hash::extract($data, 'ThirdParty.{n}.id');
		$this->request->data = $data;
	}

	private function initOptions() {
		$this->set('tags', $this->ComplianceAnalysisFinding->Tag->getTags('ComplianceAnalysisFinding'));

		$packages = $this->ComplianceAnalysisFinding->ComplianceManagement->getThirdParties();

		// $CompliancePackage = $this->ComplianceAnalysisFinding->CompliancePackage;
		// $CompliancePackage->virtualFields['tp_list_name'] = 'CONCAT(CompliancePackage.name, " (", ThirdParty.name, ")")';
		// $packages = $CompliancePackage->find('list', [
		// 	'order' => ['CompliancePackage.name' => 'ASC', 'ThirdParty.name' => 'ASC'],
		// 	'fields' => [
		// 		'CompliancePackage.id',
		// 		'ThirdParty.name'
		// 	],
		// 	'recursive' => 0
		// ]);
		// unset($CompliancePackage->virtualFields['tp_list_name']);

		$this->set(compact('packages'));
		$this->initPackageItems();
	}

	public function loadPackageItems($id = null) {
		// debug($this->request);
		$this->request->data = $this->request->query['data'];
		// $this->set('packageIds', $data['ComplianceAnalysisFinding']['ThirdParty']);
		if (isset($data['ComplianceAnalysisFinding']['CompliancePackageItem'])) {
			// $this->request->data['ComplianceAnalysisFinding']['CompliancePackageItem'] = $data['ComplianceAnalysisFinding']['CompliancePackageItem'];
		}
		$this->initOptions();
	}

	public function initPackageItems($packageIds = null) {
		if (isset($this->request->data['ComplianceAnalysisFinding']['ThirdParty'])) {
			$packageIds = $this->request->data['ComplianceAnalysisFinding']['ThirdParty'];
		}
		$this->set('packageIds', $packageIds);
		// $this->request->data['ComplianceAnalysisFinding']['CompliancePackageItem']

		$conds = [];
		// debug($packageIds);
		if ($packageIds !== null) {
			$conds = [
				'ThirdParty.id' => $packageIds
			];
		}

		$data = $this->ComplianceAnalysisFinding->ThirdParty->find('all', array(
			'conditions' => $conds,
			'fields' => array(
				'ThirdParty.id',
				'ThirdParty.name',
				'ThirdParty.description'
			),
			'contain' => array(
				'CompliancePackage' => array(
					'CompliancePackageItem'
				)
			),
			'order' => array( 'ThirdParty.id' => 'ASC' ),

		));
		$data = filterComplianceData($data);
		// debug($data);
		$packageItemsList = [];
		foreach ($data as $key => $item) {
			$packageItemsList[$item['ThirdParty']['id']] = array_combine(Hash::extract($item, 'CompliancePackage.{n}.CompliancePackageItem.{n}.id'),
				Hash::format($item, array('CompliancePackage.{n}.CompliancePackageItem.{n}.item_id', 'CompliancePackage.{n}.CompliancePackageItem.{n}.name'), '(%s) %s'));
				//Hash::extract($item, 'CompliancePackage.{n}.CompliancePackageItem.{n}.item_id'));
		}

		$this->set('packageItemsList', $packageItemsList);

		$CompliancePackageItem = $this->ComplianceAnalysisFinding->CompliancePackageItem;
		$CompliancePackageItem->virtualFields['list_name'] = 'CONCAT("(", CompliancePackageItem.item_id, ") ", CompliancePackageItem.name, "")';

		$conds = [];
		// debug($packageIds);
		if ($packageIds !== null) {
			$conds = [
				'CompliancePackageItem.compliance_package_id' => $packageIds
			];
		}

		$packageItems = $CompliancePackageItem->find('all', [
			'conditions' => $conds,
			'order' => ['CompliancePackageItem.item_id' => 'ASC'],
			'fields' => [
				'CompliancePackageItem.compliance_package_id',
				'CompliancePackageItem.id',
				'CompliancePackage.name',
				'list_name'
			],
			'recursive' => 0
		]);
		// debug($data);
		$packageItemsGroup = Hash::combine($data, '{n}.CompliancePackage.{n}.CompliancePackageItem.{n}.id', '{n}.CompliancePackage.{n}.CompliancePackageItem.{n}.name', '{n}.ThirdParty.id');
		// debug($packageItemsGroup);
		unset($CompliancePackageItem->virtualFields['tp_list_name']);

		$this->set(compact('packageItems', 'packageItemsGroup'));
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$conds = [
			'ComplianceAnalysisFinding.id' => $id
		];

		$data = $this->ComplianceAnalysisFinding->find('first', array(
			'conditions' => [
				'ComplianceAnalysisFinding.id' => $id
			],
			'contain' => $this->UserFields->attachFieldsToArray(['Owner', 'Collaborator'], array(
				'ComplianceManagement' => array('fields' => array('id')),
				'Comment',
				'Attachment',
				'Tag'
			))
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$viewVars = [
			'item' => $data,
			'commonComplianceData' => $this->ComplianceAnalysisFinding->getCommonComplianceData(
				[$data['ComplianceAnalysisFinding']['id']]
			),
			'title_for_layout' => $this->ComplianceAnalysisFinding->label()
		];

		$customFieldsData = $this->CustomFieldsMgt->setData();

		$this->set(array_merge($viewVars, $customFieldsData));

		$name = Inflector::slug($viewVars['item']['ComplianceAnalysisFinding']['title'], '-');
		// $this->render('..'.DS.'ComplianceAnalysisFindings'.DS.'export_pdf');

		$this->Pdf->renderPdf($name, '..'.DS.'ComplianceAnalysisFindings'.DS.'export_pdf', 'pdf', $viewVars, true);
	}

}
