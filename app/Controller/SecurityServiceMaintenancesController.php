<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class SecurityServiceMaintenancesController extends SectionBaseController {

	use SectionCrudTrait;
	
	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'ObjectStatus.ObjectStatus',
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
			'model' => 'SecurityServiceMaintenance'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'SecurityService' => [
							'fields' => ['id', 'name', 'maintenance_metric_description']
						],
						'Attachment' => [
							'fields' => ['id']
						],
						'Comment'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => ['MaintenanceOwner']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Security Service Maintenances');
		$this->subTitle = __('');
	}

	public function index( $id = null ) {
		$this->title = __('Security Services Maintenance Report');
		$this->subTitle = __('This is a report of all maintenance records for this service.');

		$this->paginate = array(
			'conditions' => array(
				'SecurityServiceMaintenance.security_service_id' => $id
			),
			'order' => array('SecurityServiceMaintenance.id' => 'ASC'),
		);

		$this->Prg->commonProcess('SecurityServiceMaintenance');
		unset($this->request->data['SecurityServiceMaintenance']);

		$filterConditions = $this->SecurityServiceMaintenance->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions) && empty($this->request->query['advanced_filter'])) {
			$this->Paginator->settings['conditions'] = $filterConditions;
			$this->Crud->action()->config('filter.enabled', false);
		}

		$this->set('security_service_id', $id);
		$this->set('page', $this->getItemPage($id));
		$this->set('modalPadding', true);

		return $this->Crud->execute();
	}

	private function getItemPage($id) {
		$this->loadModel('SecurityService');
		$order = $this->SecurityService->find('count', array(
			'conditions' => array(
				'SecurityService.id <=' => $id
			),
			'recursive' => -1
		));

		$page = ceil($order/10);
		return $page;
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Security Service Maintenance.');

		return $this->Crud->execute();
	}

	public function trash() {
		$this->set('title_for_layout', __( 'Security Service Maintenances (Trash)') );
		$this->set('subtitle_for_layout', __( 'This is the list of maintenances.') );

		return $this->Crud->execute();
	}

	public function add($securityServiceId) {
		$this->title = __('Create a Security Service Maintenance');
		$this->subTitle = __('The objective is to keep track of the regular tasks Service Controls require in order to function properly.');

		$this->loadModel('SecurityService');
		$securityService = $this->SecurityService->find('first', [
			'conditions' => [
				'SecurityService.id' => $securityServiceId
			],
			'contain' => $this->UserFields->attachFieldsToArray(['MaintenanceOwner'], []),
			'recursive' => -1
		]);

		if (empty($securityService)) {
			throw new NotFoundException();
		}

		$this->set('securityService', $securityService);

		$this->request->data['SecurityServiceMaintenance']['security_service_id'] = $securityService['SecurityService']['id'];

		$this->SecurityServiceMaintenance->setCreateValidation();

		return $this->Crud->execute();
	}

	public function edit( $id = null ) {
		$this->title = __('Edit a Security Service Maintenance');
		$this->subTitle = __( 'The objective is to keep track of the regular tasks Service Controls require in order to function properly.' );

		return $this->Crud->execute();
	}
}
