<?php
App::uses('BusinessContinuityPlanAudit', 'Model');
App::uses('SectionBaseController', 'Controller');

class BusinessContinuityPlanAuditsController extends SectionBaseController {

	public $helpers = [];
	public $components = [
		'Paginator', 'NotificationSystemMgt', 'Search.Prg', 'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['index', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications'],
			'redirects' => [
				'index' => [
					'url' => ['controller' => 'businessContinuityPlans', 'action' => 'index']
				]
			]
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'BusinessContinuityPlan' => [
							'fields' => ['id', 'title', 'audit_metric', 'audit_success_criteria']
						],
						'User' => [
							'fields' => ['id', 'name', 'surname']
						],
						'Attachment' => [
							'fields' => ['id']
						],
						'Comment',
						'BusinessContinuityPlanAuditImprovement' => [
							'User',
							'Project' => [
								'fields' => ['title']
							]
						]
					],
				]
			]
		],
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Business Continuity Plans Audit');
		$this->subTitle = __('This is a report of all the audits registed for this service.');
	}

	public function index($id = null) {
		$this->title = __('Business Continuity Plans Audit Report');

		$this->Paginator->settings['conditions'] = [
			'BusinessContinuityPlanAudit.business_continuity_plan_id' => $id
		];

		$this->set('business_continuity_plan_id', $id);

		return $this->Crud->execute();
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Business Continuity Plans Audit.');

		return $this->Crud->execute();
	}

	public function _afterItemSave(CakeEvent $event) {
		parent::_afterItemSave($event);
		
		if ($event->subject->success && isset($event->subject->id)) {
			$data = $this->BusinessContinuityPlanAudit->find('first', array(
				'conditions' => array(
					'BusinessContinuityPlanAudit.id' => $event->subject->id
				),
				'recursive' => -1
			));

			if (!empty($data) && $data['BusinessContinuityPlanAudit']['result'] == BusinessContinuityPlanAudit::RESULT_FAILED) {
				$this->NotificationSystemMgt->setupDefaultTypes();
				$ret = $this->NotificationSystemMgt->triggerHandler(array(
					'model' => 'BusinessContinuityPlan',
					'callback' => 'afterSave'
				), 'BusinessContinuityPlan', $data['BusinessContinuityPlanAudit']['business_continuity_plan_id'], array());
			}
		}
	}

	public function edit($id = null) {
		$this->title = __('Edit a Business Continuity Plans Audit.');
		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('The objective is to audit the security control for efficiency utilizing the metrics reviews and success criteria defined on the continuity plan. You should be able to add evidence that suppors the audit.' );
	}

	public function getIndexUrlFromComponent($model, $foreign_key) {
		return parent::getIndexUrl($model, $foreign_key);
	}

	public function initEmailFromComponent($to, $subject, $template, $data = array(), $layout = 'default', $from = NO_REPLY_EMAIL, $type = 'html') {
		return parent::initEmail($to, $subject, $template, $data, $layout, $from, $type);
	}

}
