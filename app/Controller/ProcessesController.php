<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ProcessesController extends SectionBaseController {
	use SectionCrudTrait;
	
	public $helpers = [];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'Process'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'BusinessUnit'
					],
					'className' => 'Filter',
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Processes');
		$this->subTitle = __('');
	}

	public function index($id = null) {
		$response = $this->handleCrudAction('index');

		if ($response === false) {
			$this->redirect(array('controller' => 'businessUnits', 'action' => 'index'));
		}
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Process.');

		return $this->Crud->execute();
	}

	public function add($buId = null) {
		$this->title = __('Create a Business Process');
		$this->initAddEditSubtitle();

		$buData = $this->Process->BusinessUnit->find('first', array(
			'conditions' => array(
				'BusinessUnit.id' => $buId
			),
			'recursive' => -1
		));

		if (empty($buData)) {
			throw new NotFoundException();
		}

		$this->set('bu_id', $buId);

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Business Process');
		$this->initAddEditSubtitle();

		$this->set('id', $id);

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->title = __('Describe the main functions of each Business Unit. There shouldnt be more than three or four. If you dare going too much in detail you might exponentially increase the task of understanding your organization and all that level of detail might not bring substantial value. Start small.');
	}

}
