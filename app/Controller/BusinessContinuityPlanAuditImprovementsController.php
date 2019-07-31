<?php
App::uses('SectionBaseController', 'Controller');

class BusinessContinuityPlanAuditImprovementsController extends SectionBaseController {
	public $helpers = [];
	public $components = [
		'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['index', 'edit', 'delete', 'add'],
			'redirects' => [
				'index' => [
					'url' => ['controller' => 'businessContinuityPlanAudits', 'action' => 'index']
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

	private function addEditCommonProcess($auditId) {
		$audit = $this->getAudit($auditId);

		if (empty($audit)) {
			throw new NotFoundException();
		}

		$this->set('business_continuity_plan_name', $audit['BusinessContinuityPlan']['title']);
		$this->set('audit_id', $auditId);

		if ($this->request->is('post')) {
			$this->request->data['BusinessContinuityPlanAuditImprovement']['business_continuity_plan_audit_id'] = $auditId;
			$this->request->data['BusinessContinuityPlanAuditImprovement']['user_id'] = $this->logged['id'];
		}
	}

	private function getAudit($id) {
		$this->loadModel('BusinessContinuityPlanAudit');
		$audit = $this->BusinessContinuityPlanAudit->find('first', array(
			'conditions' => array(
				'BusinessContinuityPlanAudit.id' => $id
			),
			'fields' => array('BusinessContinuityPlan.title', 'BusinessContinuityPlan.id'),
			'recursive' => 0
		));

		return $audit;
	}
}
