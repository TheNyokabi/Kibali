<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ProjectExpensesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = [];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'className' => 'Filter',
				]
			]
		],
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Project Expenses');
		$this->subTitle = __('This is the list of expenses for a given project.');
	}

	public function index( $project_id = null ) {
		$this->title = __('List of Expenses');

		$response = $this->handleCrudAction('index');

		if ($response === false) {
			$this->redirect(array('controller' => 'projects', 'action' => 'index'));
		}
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Project Expense.');

		return $this->Crud->execute();
	}

	public function add($project_id = null) {
		$this->title = __('Create a Project Expense');
		$this->initAddEditSubtitle();

		$project_id = (int) $project_id;

		$this->set('project_id', $project_id);

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Project Expense');
		$this->initAddEditSubtitle();

		$data = $this->ProjectExpense->find('first', array(
			'conditions' => array(
				'ProjectExpense.id' => $id
			),
			'recursive' => -1
		));
		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('project_id', $data['ProjectExpense']['project_id']);
		$this->set('id', $id);

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Use this form to create or edit new improvement expense. In this way you can control financial expenses on your projects.');
	}

}
