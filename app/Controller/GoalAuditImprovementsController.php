<?php
App::uses('SectionBaseController', 'Controller');

class GoalAuditImprovementsController extends SectionBaseController {
	public $helpers = [];
	public $components = [
		'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['index', 'edit', 'delete', 'add'],
			'redirects' => [
				'index' => [
					'url' => ['controller' => 'goalAudits', 'action' => 'index']
				]
			]
		],
		'Crud.Crud' => [
		],
	];

	public function beforeFilter() {
		$this->Ajax->settings['modules'] = [];

		$this->Crud->enable(['add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Audit Improvement');
		$this->subTitle = __('');
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete an Audit Improvement.');

		return $this->Crud->execute();
	}

	public function add($auditId = null) {
		$this->title = __('Create an Audit Improvement');

		$this->addEditCommonProcess($auditId);

		return $this->Crud->execute();
	}

	public function edit($auditId = null) {
		$this->title = __('Edit an Audit Improvement');

		$this->addEditCommonProcess($auditId);

		return $this->Crud->execute();
	}

	private function addEditCommonProcess() {
		$audit = $this->getAudit($auditId);

		if (empty($audit)) {
			throw new NotFoundException();
		}

		$this->set('goal_name', $audit['Goal']['name']);
		$this->set('audit_id', $auditId);

		if ($this->request->is('post')) {
			$this->request->data['GoalAuditImprovement']['goal_audit_id'] = $auditId;
			$this->request->data['GoalAuditImprovement']['user_id'] = $this->logged['id'];
		}
	}

	private function getAudit($id) {
		$this->loadModel('GoalAudit');
		$audit = $this->GoalAudit->find('first', array(
			'conditions' => array(
				'GoalAudit.id' => $id
			),
			'fields' => array('Goal.name', 'Goal.id'),
			'recursive' => 0
		));

		return $audit;
	}
}
