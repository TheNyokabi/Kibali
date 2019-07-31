<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class SecurityServiceAuditsController extends SectionBaseController {

	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'NotificationSystemMgt', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['index', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications'],
			'redirects' => [
				'index' => [
					'url' => ['controller' => 'securityServices', 'action' => 'index']
				]
			]
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'SecurityServiceAudit'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'SecurityService' => [
							'fields' => ['id', 'name', 'audit_metric_description', 'audit_success_criteria']
						],
						'Attachment' => [
							'fields' => ['id']
						],
						'SecurityServiceAuditImprovement' => [
							'User',
							'Project' => [
								'fields' => ['title']
							],
							'SecurityIncident' => [
								'fields' => ['title']
							]
						],
						'Comment'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => ['AuditOwner', 'AuditEvidenceOwner']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Security Service Audits');
		$this->subTitle = __('');
	}

	public function index($id = null, $year = null) {
		$this->title = __('Security Services Audit Report');
		$this->subTitle = __('This is a report of all the audits registed for this service.');

		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		$this->SecurityServiceAudit->virtualFields['year'] = 'YEAR(SecurityServiceAudit.planned_date)';

		if (empty($year)) {
			$year = date('Y');
		}

		$availableYears = $this->getAvailableYears($id);
		if (!in_array(date('Y'), $availableYears)) {
			$availableYears[] = date('Y');
		}
		
		$availableYears = array_unique($availableYears);
		sort($availableYears);

		$this->paginate = [
			'conditions' => [
				'SecurityServiceAudit.security_service_id' => $id,
				'YEAR(SecurityServiceAudit.planned_date)' => $year
			],
			'order' => ['SecurityServiceAudit.planned_date' => 'ASC'],
		];

		$this->Prg->commonProcess('SecurityServiceAudit');
		unset($this->request->data['SecurityServiceAudit']);

		$filterConditions = $this->SecurityServiceAudit->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions) && empty($this->request->query['advanced_filter'])) {
			$this->Paginator->settings['conditions'] = $filterConditions;
			$this->Crud->action()->config('filter.enabled', false);
			$this->set('filterConditions', true);
		}

		$this->set('security_service_id', $id);
		$this->set('page', $this->getItemPage($id));
		$this->set('modalPadding', true);

		$this->set('availableYears', $availableYears);
		$this->set('currentYear', $year);

		$this->Crud->execute();
	}

	public function _afterPaginate(CakeEvent $event) {
		if (!empty($this->viewVars['filterConditions']) && !empty($event->subject->items)) {
			$data = $event->subject->items;
			$id = $data[0]['SecurityServiceAudit']['security_service_id'];
			$availableYears = $this->getAvailableYearsFromAudits($data);
			$year = $availableYears[0];

			$this->set('security_service_id', $id);
			$this->set('availableYears', $availableYears);
			$this->set('currentYear', $year);
		}
	}

	/**
	 * Get an array of Year values from a data of audits.
	 */
	private function getAvailableYearsFromAudits($data) {
		$years = array();
		if (!empty($data)) {
			foreach ($data as $item) {
				$years[] = $item['SecurityServiceAudit']['year'];
			}
		}

		return $years;
	}

	/**
	 * Group audits into years.
	 */
	private function getAvailableYears($securityServiceId) {
		$data = $this->SecurityServiceAudit->find('list', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $securityServiceId
			),
			'fields' => array(
				'SecurityServiceAudit.id',
				'SecurityServiceAudit.year'
			),
			'group' => 'SecurityServiceAudit.year',
			'order' => array('SecurityServiceAudit.year' => 'ASC'),
			'recursive' => -1
		));

		return $data;
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
		$this->title = __('Delete a Security Service Audit.');

		return $this->Crud->execute();
	}

	public function trash() {
		$this->set('title_for_layout', __('Security Service Audits (Trash)'));
		$this->set('subtitle_for_layout', __('This is the list of audits.'));

		return $this->Crud->execute();
	}

	public function add($securityServiceId) {
		$this->title = __('Create a Security Service Audit');
		$this->subTitle = __('The objective is to audit the security control for efficiency utilizing the metrics reviews and success criteria defined on the control. You should be able to add evidence that suppors the audit.');

		$this->loadModel('SecurityService');
		$securityService = $this->SecurityService->find('first', [
			'conditions' => [
				'SecurityService.id' => $securityServiceId
			],
			'contain' => $this->UserFields->attachFieldsToArray(['AuditOwner', 'AuditEvidenceOwner'], []),
			'recursive' => -1
		]);

		if (empty($securityService)) {
			throw new NotFoundException();
		}

		$this->set('securityService', $securityService);

		$this->request->data['SecurityServiceAudit']['security_service_id'] = $securityService['SecurityService']['id'];

		$this->SecurityServiceAudit->setCreateValidation();

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Security Service Audit');
		$this->subTitle = __('The objective is to audit the security control for efficiency utilizing the metrics reviews and success criteria defined on the control. You should be able to add evidence that suppors the audit.');

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->Crud->execute();
	}

	public function _afterSave(CakeEvent $event) {
		if (empty($event->subject->success)) {
			return false;
		}

		$auditId = $event->subject->id;

		$ret = true;

		$this->NotificationSystemMgt->setupDefaultTypes();
		if ($this->request->data['SecurityServiceAudit']['result'] == AUDIT_FAILED) {
			$data = $this->SecurityServiceAudit->find( 'first', array(
				'conditions' => array(
					'SecurityServiceAudit.id' => $auditId
				),
				'recursive' => 0
			));

			// deprecated and removed in a near future
			$ret = $this->NotificationSystemMgt->triggerHandler(array(
				'model' => 'SecurityService',
				'callback' => 'afterSave'
			), 'SecurityService', $data['SecurityServiceAudit']['security_service_id'], array());

			// replacement
			$ret &= $this->NotificationSystemMgt->triggerHandler(array(
				'model' => 'SecurityServiceAudit',
				'callback' => 'afterSave',
				'type' => 'AuditFailed',
			), 'SecurityServiceAudit', $auditId, array());
		}

		if ($this->request->data['SecurityServiceAudit']['result'] == AUDIT_PASSED) {
			$ret &= $this->NotificationSystemMgt->triggerHandler(array(
				'model' => 'SecurityServiceAudit',
				'callback' => 'afterSave',
				'type' => 'AuditPassed',
			), 'SecurityServiceAudit', $auditId, array());
		}

		return $ret;
	}

	public function getIndexUrlFromComponent($model, $foreign_key) {
		return parent::getIndexUrl($model, $foreign_key);
	}

	public function initEmailFromComponent($to, $subject, $template, $data = array(), $layout = 'default', $from = NO_REPLY_EMAIL, $type = 'html') {
		return parent::initEmail($to, $subject, $template, $data, $layout, $from, $type);
	}

}
