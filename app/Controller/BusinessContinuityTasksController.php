<?php
App::uses('SectionBaseController', 'Controller');

class BusinessContinuityTasksController extends SectionBaseController {
	
	public $helpers = [];
	public $components = ['Paginator', 
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
					]
				]
			]
		],
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Business Continuity Task');
		$this->subTitle = __('');
	}

	public function index() {
		$this->redirect(['controller' => 'businessContinuityPlans', 'action' => 'index']);
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Business Continuity Task.');
		
		return $this->Crud->execute();
	}

	public function add($id = null) {
		$this->title = __('Create a Business Continuity Task');
		$this->initAddEditSubtitle();

		$id = (int) $id;

		$data = $this->BusinessContinuityTask->BusinessContinuityPlan->find('first', [
			'conditions' => [
				'BusinessContinuityPlan.id' => $id
			],
			'recursive' => -1
		]);

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('id', $id);

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Business Continuity Task');

		$this->set('id', $id);

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('This is the tools used to create an emergency plan. Emergency plans are short and very much to the point. Have you noticed aircraft emergency plans? there\'s no point in writing long manuals since at emergency times there\'s no time to read. Keep it to the point and you\'ll do fine.');
	}

}
