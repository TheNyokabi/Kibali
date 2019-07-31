<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class SecurityServicesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['ImportTool.ImportTool', 'UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'Pdf', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'SecurityService'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'SecurityServiceType' => [
							'fields' => ['id', 'name']
						],
						'Classification',
						'SecurityPolicy' => [
							'fields' => ['id', 'index', 'description', 'short_description', 'status', 'expired_reviews']
						],
						'Risk' => [
							'fields' => ['id', 'title']
						],
						'ThirdPartyRisk' => [
							'fields' => ['id', 'title']
						],
						'BusinessContinuity' => [
							'fields' => ['id', 'title']
						],
						'SecurityIncident' => [
							'fields' => ['id', 'title', 'ongoing_incident', 'security_incident_status_id']
						],
						'ServiceContract' => [
							'fields' => ['id', 'name', 'description', 'value', 'start', 'end']
						],
						'DataAsset' => [
							'fields' => ['id', 'description']
						],
						'SecurityServiceAuditDate' => [
							'fields' => ['day', 'month']
						],
						'SecurityServiceMaintenanceDate' => [
							'fields' => ['day', 'month']
						],
						'Comment',
						'Attachment',
						'Projects' => [
							'fields' => ['id', 'title', 'goal', 'deadline', 'expired', 'over_budget', 'expired_tasks'],
							'ProjectStatus'
						],
						// projects will be removed because it does not match the convention standart
						'Project' => [
							'ProjectStatus'
						],
						'ComplianceManagement'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => ['ServiceOwner', 'Collaborator', 'AuditOwner', 'AuditEvidenceOwner', 'MaintenanceOwner']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Security Services');
		$this->subTitle = __('system and must be carefully populated and managed. Controls defined in this section will be used to protect your Data Flows, and utilised to mitigate Risks and Compliance requirements.');
	}

	public function index($openId = null) {
		$this->title = __('Security Services Catalogue');

		$this->set('openId', $openId);

		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		return $this->Crud->execute();
	}

	public function _afterPaginate(CakeEvent $event) {
		if (empty($this->request->query['advanced_filter'])) {
			$this->addProjects($event->subject->items);
		}

		$this->SecurityService->ComplianceManagement->attachCompliancePackageData($event->subject->items);
	}

	private function addProjects(&$data) {
		$this->loadModel('SecurityServiceAudit');

		$correctionProjects = array();
		foreach ($data as $key => $security_service) {
			$audits = $this->SecurityServiceAudit->find('all', array(
				'conditions' => array(
					'SecurityServiceAudit.security_service_id' => $security_service['SecurityService']['id']
				),
				'contain' => array(
					'SecurityServiceAuditImprovement' => array(
						'Project' => array(
							// 'fields' => array('id', 'title', 'goal', 'deadline')
						)
					)
				)
			));

			$projects = array();
			$usedProjects = array();
			foreach ($audits as $audit) {
				if (isset($audit['SecurityServiceAuditImprovement']['Project'])) {
					foreach ($audit['SecurityServiceAuditImprovement']['Project'] as $project) {
						if (in_array($project['id'], $usedProjects)) {
							continue;
						}

						$projects[] = $project;
						$usedProjects[] = $project['id'];
					}
				}
			}

			foreach ($security_service['Project'] as $project) {
				if (in_array($project['id'], $usedProjects)) {
					continue;
				}

				$projects[] = $project;
				$usedProjects[] = $project['id'];
			}

			$data[$key]['Project'] = $projects;
		}

		return $data;
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Security Service.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Security Service');
		$this->initAddEditSubtitle();

		$this->initOptions();

		$this->handleEmptyDates();

		$this->updateFieldsWhenDesign();

		$this->Crud->action()->saveMethod('saveAssociated');

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$id = (int) $id;

		$this->title = __('Edit a Security Service');
		$this->initAddEditSubtitle();

		$this->initOptions();

		$this->handleEmptyDates();

		$this->updateFieldsWhenDesign();

		$this->Crud->action()->saveMethod('saveAssociated');

		return $this->Crud->execute();
	}

	private function handleEmptyDates() {
		if ($this->request->is(['post', 'put'])) {
			$this->request->data['SecurityServiceAuditDate'] = array_filter((array) $this->request->data['SecurityServiceAuditDate']);
			$this->request->data['SecurityServiceMaintenanceDate'] = array_filter((array) $this->request->data['SecurityServiceMaintenanceDate']);
		}
	}

	public function trash() {
	    $this->set( 'title_for_layout', __( 'Security Services (Trash)' ) );
	    $this->set( 'subtitle_for_layout', __( 'This is the list of security services.' ) );

	    return $this->Crud->execute();
	}

	private function updateFieldsWhenDesign() {
		//only on form submit
		if (!$this->request->is('post') && !$this->request->is('put')) {
			return;
		}

		$auditsText = __('The control is in design. Audits not possible.');
		$maintenancesText = __('The control is in design. Maintenances not possible.');

		if ($this->request->data['SecurityService']['security_service_type_id'] == SECURITY_SERVICE_DESIGN) {
			$text = $this->request->data['SecurityService']['audit_metric_description'];
			$pos = strpos($text, $auditsText);

			if ($pos === false) {
				$this->request->data['SecurityService']['audit_metric_description'] .= ' ' . $auditsText;
			}

			$text = $this->request->data['SecurityService']['audit_success_criteria'];
			$pos = strpos($text, $auditsText);

			if ($pos === false) {
				$this->request->data['SecurityService']['audit_success_criteria'] .= ' ' . $auditsText;
			}

			$text = $this->request->data['SecurityService']['maintenance_metric_description'];
			$pos = strpos($text, $maintenancesText);

			if ($pos === false) {
				$this->request->data['SecurityService']['maintenance_metric_description'] .= ' ' . $maintenancesText;
			}
		}
	}

	public function auditCalendarFormEntry() {
		$this->allowOnlyAjax();

		$data = $this->request->data;

		$this->set( 'formKey', (int) $data['formKey'] );
		$this->set( 'model', $data['model'] );
		// if ( ! isset( $data['field'] ) ) {
		// 	$data['field'] = 'audit_calendar';
		// }
		// $this->set( 'field', $data['field'] );
		$this->set('useNewCalendarConvention', true);
		$this->render( '/Elements/ajax/audit_calendar_entry' );
	}

	private function initOptions() {
		$classificationsTmp = $this->SecurityService->Classification->find('list', array(
			'order' => array('Classification.name' => 'ASC'),
			'fields' => array('Classification.id', 'Classification.name'),
			'group' => array('Classification.name'),
			'recursive' => -1
		));
		$classifications = array();
		foreach ($classificationsTmp as $c) {
			$classifications[] = $c;
		}

		$security_policies = $this->getSecurityPoliciesList();

		$this->set('classifications', $classifications);
		$this->set('security_policies', $security_policies);
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('The security controls are the core of the security management system and must be carefully populated and managed. Controls defined in this section will be used to protect your Data Flows, and utilised to mitigate Risks and Compliance requirements.');
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->SecurityService->find('first', array(
			'conditions' => array(
				'SecurityService.id' => $id
			),
			'contain' => $this->UserFields->attachFieldsToArray(['ServiceOwner', 'Collaborator', 'AuditOwner', 'AuditEvidenceOwner', 'MaintenanceOwner'], array(
				'Comment' => array('User'),
				'Attachment',
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'SecurityServiceType',
				'Classification',
				'SecurityPolicy',
				'ServiceContract',
				'ThirdPartyRisk',
				'Risk',
				'BusinessContinuity',
				'SecurityIncident',
				'DataAsset',
				'ComplianceManagement',
				'Projects',
				'SecurityServiceAudit' => $this->UserFields->attachFieldsToArray(['AuditOwner', 'AuditEvidenceOwner'], [
					'limit' => 20,
					'order' => array('created' => 'DESC')
				], 'SecurityServiceAudit'),
				'SecurityServiceMaintenance' => $this->UserFields->attachFieldsToArray(['MaintenanceOwner'], [
					'limit' => 20,
					'order' => array('created' => 'DESC')
				], 'SecurityServiceMaintenance'),
				'CustomFieldValue'
			)),
			'recursive' => -1
		));

		$this->SecurityService->ComplianceManagement->attachCompliancePackageData($item, true);

		$customFieldsData = $this->CustomFieldsMgt->setData();
		$item = array_merge($item, $customFieldsData);
		
		if (!isset($item['SecurityService'])) {
			$item = reset($item);
		}

		$this->set('item', $item);
		$vars = array(
			'item' => $item
		);

		$name = Inflector::slug($item['SecurityService']['name'], '-');
		$this->Pdf->renderPdf($name, '..'.DS.'SecurityServices'.DS.'export_pdf', 'pdf', $vars, true);
	}
}
