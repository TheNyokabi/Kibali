<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ProjectAchievementsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
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
		'UserFields.UserFields' => [
			'fields' => ['TaskOwner']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Project Tasks');
		$this->subTitle = __('This is the list of tasks for a given project.');
	}

	public function index($project_id = null) {
		$this->title = __('List of Project Tasks');

		$response = $this->handleCrudAction('index');

		if ($response === false) {
			$url = array('controller' => 'projects', 'action' => 'index');
			if (isset($this->request->query['id'])) {
				$projectId = $this->ProjectAchievement->field('project_id', [
					'ProjectAchievement.id' => $this->request->query['id']
				]);

				$url['?'] = ['open_id' => $projectId, 'id' => $projectId];
			}
			$this->redirect($url);
		}
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Project Task.');

		return $this->Crud->execute();
	}

	public function add($project_id = null) {
		$this->title = __('Create a Project Task');
		$this->initAddEditSubtitle();

		$project_id = (int) $project_id;

		$this->set('project_id', $project_id);
		$this->initOptions($project_id);

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Project Task');
		$this->initAddEditSubtitle();

		$data = $this->ProjectAchievement->find('first', array(
			'conditions' => array(
				'ProjectAchievement.id' => $id
			),
			'recursive' => -1
		));
		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('project_id', $data['ProjectAchievement']['project_id']);
		$this->set('id', $id);
		$this->initOptions($data['ProjectAchievement']['project_id'], $id);

		return $this->Crud->execute();
	}

	/**
	 * Initialize options for join elements.
	 */
	private function initOptions($project_id = null, $achievement_id = null) {
		$conds = array();
		if ($project_id) {
			$conds['ProjectAchievement.project_id'] = $project_id;
		}
		if ($achievement_id) {
			$conds['ProjectAchievement.id !='] = $achievement_id;
		}

		$lastAchievement = $lastOrder = false;
		// when on /add, autocomplete for the date and order field, with the latest available information
		if ($achievement_id === null) {
			$lastAchievement = $this->ProjectAchievement->field(
				'date',
				['ProjectAchievement.project_id' => $project_id],
				'ProjectAchievement.date DESC'
			);

			$lastOrder = $this->ProjectAchievement->field(
				'task_order',
				['ProjectAchievement.project_id' => $project_id],
				'ProjectAchievement.task_order DESC'
			);
		}
		
		$this->set('lastAchievement', $lastAchievement);
		$this->set('lastOrder', $lastOrder);
		/*$achievements = $this->ProjectAchievement->find('list', array(
			'conditions' => $conds,
			'fields' => array('id', 'description')
		));*/

		$order = array();
		for ($i = 20; $i >= 1; $i--) {
			$order[$i] = $i;
		}

		$this->set('order', $order);
		//$this->set('achievements', $achievements);
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Define and update tasks involved on this project.');
	}

}
