<?php
App::uses('SectionBaseController', 'Controller');

class GoalAuditsController extends SectionBaseController {
	public $helpers = [];
	public $components = [
		'Session', 'Paginator', 'ObjectStatus.ObjectStatus', 
		'Ajax' => [
			'actions' => ['index', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications'],
			'redirects' => [
				'index' => [
					'url' => ['controller' => 'goals', 'action' => 'index']
				]
			]
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'Goal' => [
							'fields' => ['id', 'name', 'audit_metric', 'audit_criteria']
						],
						'User' => [
							'fields' => ['id', 'name', 'surname']
						],
						'Attachment' => [
							'fields' => ['id']
						],
						'GoalAuditImprovement' => [
							'User',
							'Project' => [
								'fields' => ['title']
							]
						],
						'Comment'
					]
				]
			]
		],
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Goal Audits');
		$this->subTitle = __('This is a report of all the audits registed for this Goal.');
	}

	public function index($goalId = null) {
		$this->title = __('Goals Performance Review Report');

		$this->set('goal_id', $goalId);
		$this->set('page', $this->getItemPage($goalId));

		$this->Paginator->settings = [
			'conditions' => [
				'GoalAudit.goal_id' => $goalId
			],
		];

		return $this->Crud->execute();
	}

	private function getItemPage($id) {
		/*$this->loadModel('SecurityService');
		$order = $this->SecurityService->find('count', array(
			'conditions' => array(
				'SecurityService.id <=' => $id
			),
			'recursive' => -1
		));

		$page = floor($order/10);
		return $page;*/
		return 1;
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Goal Audit.');

		return $this->Crud->execute();
	}

	public function edit( $id = null ) {
		$this->title = __('Edit a Goal Audit.');

		unset($this->request->data['GoalAudit']['goal_id']);

		return $this->Crud->execute();
	}
}
