<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class RiskExceptionsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'CsvView.CsvView', 'Search.Prg', 'AdvancedFilters', 'Pdf', 'Paginator', 'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		], 
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'RiskException'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'Comment',
						'Attachment',
						'Risk',
						'ThirdPartyRisk',
						'BusinessContinuity',
						'Tag'
					],
				],
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'UserFields.UserFields' => [
			'fields' => ['Requester']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Risk Exception Management');
		$this->subTitle = __('Risk Exceptions are used to evidence the decision of accepting rather than mitigating a Risk. Risk exceptions are commonly mapped to Risks which the business intends to accept.');
	}

	public function index() {
		$filterConditions = $this->RiskException->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions) && empty($this->request->query['advanced_filter'])) {
			$this->Paginator->settings['conditions'] = $filterConditions;
		}

		return $this->Crud->execute();
	}

	public function delete($id = null) {
		$this->title = __('Risk Exception');
		$this->subTitle = __('Delete a Risk Exception.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Risk Exception');
		$this->initAddEditSubtitle();

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Risk Exception');
		$this->initAddEditSubtitle();

		$this->initOptions();

		return $this->Crud->execute();
	}

	private function initOptions() {
		$this->set('tags', $this->RiskException->Tag->getTags('RiskException'));
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Risk Exceptions are used to evidence the decision of accepting rather than mitigating a Risk. Risk exceptions are commonly mapped to Risks which the business intends to accept.');
	}

	public function exportPdf($id) {
		$item = $this->RiskException->find('first', array(
			'conditions' => array(
				'RiskException.id' => $id
			),
			'contain' => $this->UserFields->attachFieldsToArray(['Requester'], array(
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'Risk',
				'ThirdPartyRisk',
				'BusinessContinuity',
				'CustomFieldValue'
			)),
			'recursive' => -1
		));

		$customFieldsData = $this->CustomFieldsMgt->setData();
		$item = array_merge($item, $customFieldsData);
		
		$this->set('item', $item);
		$vars = array(
			'item' => $item
		);

		$name = Inflector::slug($item['RiskException']['title'], '-');

		return $this->Pdf->renderPdfItem($item['RiskException']['title'], $vars);
	}

}
