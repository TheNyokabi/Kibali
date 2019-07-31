<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ProjectsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'Pdf', 'Paginator', 'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'ProjectStatus' => ['id', 'name'],
						'ProjectExpense' => [
							'fields' => ['id', 'description', 'date', 'amount'],
							'Comment',
							'Attachment',
							'NotificationObject'
						],
						'Comment',
						'Attachment',
						'Risk',
						'ThirdPartyRisk',
						'BusinessContinuity',
						'SecurityService',
						'SecurityPolicy' => [
							'SecurityPolicyDocumentType'
						],
						'ComplianceManagement' => 'CompliancePackageItem',
						'DataAsset',
						'SecurityServiceAuditImprovement' => [
							'SecurityServiceAudit' => [
								'fields' => ['id'],
								'SecurityService' => [
									'fields' => ['id', 'name']
								]
							]
						],
						'BusinessContinuityPlanAuditImprovement' => [
							'BusinessContinuityPlanAudit' => [
								'fields' => ['id'],
								'BusinessContinuityPlan' => [
									'fields' => ['id', 'title']
								]
							]
						],
						'Tag'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'CustomFields.CustomFieldsMgt' => array('model' => 'Project'),
		'UserFields.UserFields' => [
			'fields' => ['Owner']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Projects');
		$this->subTitle = __('This module will let you define and manage improvements across your program. Once defined here, projects can then be mapped to Compliance, Risk, Controls and Exceptions in order to clearly see what their impact is on the program.');
	}

	public function _beforePaginate(CakeEvent $event)
	{
		$event->subject->paginator->settings['contain'] = Hash::merge([
			'ProjectAchievement' => $this->UserFields->attachFieldsToArray('TaskOwner', [
				'fields' => ['id', 'description', 'date', 'completion', 'task_order', 'task_duration'],
				'Comment',
				'Attachment',
				'order' => ['ProjectAchievement.task_order' => 'ASC'],
				'NotificationObject'
			], 'ProjectAchievement')
		], $event->subject->paginator->settings['contain']);
	}

	public function _afterPaginate(CakeEvent $event) {
		$event->subject->items = $this->addCompletion($event->subject->items);
	}

	public function index() {
		$this->title = __('Project Management');

		$filterConditions = $this->Project->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions) && empty($this->request->query['advanced_filter'])) {
			$this->Paginator->settings['conditions'] = $filterConditions;
		}

		$this->Crud->on('beforePaginate', array($this, '_beforePaginate'));
		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		if (isset($this->request->query['open_id'])) {
			$this->set('openId', $this->request->query['open_id']);
		}

		return $this->Crud->execute();
	}

	private function addCompletion($data) {
		if (!empty($data)) {
			foreach ($data as $key => $item) {
				$data[$key]['Project']['ultimate_completion'] = $this->Project->getUltimateCompletion($item['Project']['id']);
			}
		}

		return $data;
	}

	private function getProjectStatuses() {
		$statuses = $this->Project->ProjectStatus->find( 'list', array(
			'order' => array('ProjectStatus.name' => 'ASC'),
			'recursive' => -1
		) );

		return $statuses;
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Project.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Project');
		$this->initAddEditSubtitle();

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Project');
		$this->initAddEditSubtitle();

		$this->initOptions();

		return $this->Crud->execute();
	}

	private function initOptions() {
		$this->set('tags', $this->Project->Tag->getTags('Project'));
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('This module will let you define and manage improvements across your program. Once defined here, projects can then be mapped to Compliance, Risk, Controls and Exceptions in order to clearly see what their impact is on the program.');
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->Project->find('first', array(
			'conditions' => array(
				'Project.id' => $id
			),
			'contain' => $this->UserFields->attachFieldsToArray(['Owner'], array(
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'ProjectStatus',
				'ProjectAchievement' => array(
					'User',
					'Comment',
					'Attachment',
					// 'fields' => array('*' )
					'order' => array('ProjectAchievement.task_order' => 'ASC'),
				),
				'ProjectExpense' => array(
					'Comment'
				),
				'Comment',
				'Attachment',
				'Risk',
				'ThirdPartyRisk',
				'BusinessContinuity',
				'SecurityService',
				'SecurityPolicy' => [
					'SecurityPolicyDocumentType'
				],
				'ComplianceManagement' => 'CompliancePackageItem',
				'DataAsset',
				'SecurityServiceAuditImprovement' => array(
					'SecurityServiceAudit' => array(
						'SecurityService'
					)
				),
				'BusinessContinuityPlanAuditImprovement' => array(
					'BusinessContinuityPlanAudit' => array(
						'BusinessContinuityPlan'
					)
				),
				'CustomFieldValue'
			))
		));

		$customFieldsData = $this->CustomFieldsMgt->setData();
		$item = array_merge($item, $customFieldsData);
		
		$this->set('item', $item);
		$vars = array(
			'item' => $item
		);

		$name = Inflector::slug($item['Project']['title'], '-');
		$this->Pdf->renderPdf($name, '..'.DS.'Projects'.DS.'export_pdf', 'pdf', $vars, true);
	}
}
