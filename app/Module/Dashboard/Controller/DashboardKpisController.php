<?php
App::uses('DashboardAppController', 'Dashboard.Controller');
App::uses('DashboardKpiValue', 'Dashboard.Model');
App::uses('Hash', 'Utility');
App::uses('DashboardKpi', 'Dashboard.Model');

class DashboardKpisController extends DashboardAppController {
	public $helpers = ['Dashboard.Dashboard', 'FieldData.FieldData'];

	public $components = [
		'Ajax' => [
			'actions' => ['add', 'delete'],
		],
		'Crud.Crud' => [
			'actions' => [
				'user' => [
					'className' => 'Index',
					'viewVar' => 'data',
					'type' => DashboardKpiValue::TYPE_USER
				],
				'admin' => [
					'className' => 'Index',
					'viewVar' => 'data',
					'type' => DashboardKpiValue::TYPE_ADMIN
				],

				'add' => [
					'className' => 'AppAdd',
				],
				'delete' => [
					'enabled' => true,
					'className' => 'AppDelete',
				]
			]
		]
	];

	public function beforeFilter() {
		$this->Crud->on('beforePaginate', array($this, '_beforePaginate'));
		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));
		
		parent::beforeFilter();
		$this->Auth->authorize = false;
	}

	public function _beforePaginate(CakeEvent $event) {
		$event->subject->paginator->settings['conditions']['DashboardKpi.owner_id'] = [
			null,
			$event->subject->controller->logged['id']
		];

		$event->subject->paginator->settings['recursive'] = 0;
		$event->subject->paginator->settings['contain'] = [
			'DashboardKpiAttribute',
			'DashboardKpiSingleAttribute'
		];

		$event->subject->paginator->settings['group'] = ['DashboardKpi.id'];
		$event->subject->paginator->settings['limit'] = 999;
		$event->subject->paginator->settings['maxLimit'] = 999;
	}

	public function _afterPaginate(CakeEvent $event) {
		$items = [];
		foreach ($event->subject->items as $item) {
			$item['attributes'] = DashboardKpi::formatAttributes($item['DashboardKpiAttribute']);

			$assignKpiToModel = $item['DashboardKpi']['model'];
			$items[$assignKpiToModel][$item['DashboardKpi']['category']][] = $item;
		}

		$event->subject->items = $items;
	}

	public function user() {
		$this->Crud->on('beforePaginate', array($this, '_beforePaginateUser'));
		$this->Crud->on('beforePaginate', array($this, '_beforePaginateValidation'));

		$this->set('title_for_layout', __('Your Dashboard'));
		$this->set('subtitle_for_layout', __('This KPI dashboard is updated periodically during the day and it only shows items that relate to your account.'));

		$AttributeInstance = $this->DashboardKpi->instance()->attributeInstance('User');
		$this->set('AttributeInstance', $AttributeInstance);

		$this->Crud->execute();
	}

	public function _beforePaginateUser(CakeEvent $event) {
		$event->subject->paginator->settings['joins'] = [
			[
				'table' => 'dashboard_kpi_attributes',
				'alias' => 'DashboardKpiAttribute',
				'type' => 'INNER',
				'conditions' => [
					'DashboardKpiAttribute.kpi_id = DashboardKpi.id'
				]

			]
		];
		$event->subject->paginator->settings['conditions'] = [
			'DashboardKpi.type' => DashboardKpi::TYPE_USER,
			'DashboardKpi.category' => [
				DashboardKpi::CATEGORY_GENERAL,
				DashboardKpi::CATEGORY_OWNER
			],
			'DashboardKpi.model' => DashboardKpi::listModelsForType(DashboardKpi::TYPE_USER),
			'OR' => [
				[
					"DashboardKpiAttribute.model = 'CustomRoles.CustomUser'",
					'DashboardKpiAttribute.foreign_key' => $event->subject->controller->logged['id']
				],
				[
					'DashboardKpi.owner_id' => $event->subject->controller->logged['id'],
					"DashboardKpiAttribute.model = 'AdvancedFilter'"
				]
			]
		];
	}

	public function _beforePaginateValidation(CakeEvent $event) {
		$settings = $event->subject->paginator->settings;

		$DashboardKpi = ClassRegistry::init('Dashboard.DashboardKpi');
		$dashboardReady = (boolean) $DashboardKpi->find('count', $settings);

		$settings['conditions']['DashboardKpi.status'] = DashboardKpi::STATUS_NOT_SYNCED; 
		$dashboardReady &= (boolean) !$DashboardKpi->find('count', $settings);
		$this->set('dashboardReady', $dashboardReady);
	}

	public function admin() {
		if (!isAdmin($this->logged)) {
			$this->Flash->error(__('You are not allowed to view admin dashboard as you are not in admin group.'));
			return $this->redirect(['plugin' => 'dashboard', 'controller' => 'dashboard_kpis', 'action' => 'user']);
		}

		$this->Crud->on('beforePaginate', array($this, '_beforePaginateAdmin'));
		$this->Crud->on('beforePaginate', array($this, '_beforePaginateValidation'));

		$AwarenessUserInstance = $this->DashboardKpi->instance()->attributeInstance('AwarenessProgramUserModel');
		$AwarenessInstance = $this->DashboardKpi->instance()->attributeInstance('AwarenessProgram');
		$this->set('AwarenessUserInstance', $AwarenessUserInstance);
		$this->set('AwarenessInstance', $AwarenessInstance);

		$AwarenessProgram = ClassRegistry::init('AwarenessProgram');
		$awarenessAttributes = $AwarenessInstance->listAttributes($AwarenessProgram);
		$awarenessPrograms = $AwarenessProgram->find('list', [
			'conditions' => [
				'AwarenessProgram.id' => $awarenessAttributes
			],
			'recursive' => -1
		]);
		$this->set('awarenessPrograms', $awarenessPrograms);

		$ComplianceTypeInstance = $this->DashboardKpi->instance()->attributeInstance('ComplianceType');
		$ComplianceInstance = $this->DashboardKpi->instance()->attributeInstance('ComplianceManagement');
		$this->set('ComplianceTypeInstance', $ComplianceTypeInstance);
		$this->set('ComplianceInstance', $ComplianceInstance);

		$ThirdParty = ClassRegistry::init('ThirdParty');
		$complianceAttributes = $ComplianceInstance->listAttributes($ThirdParty);

		$thirdParties = $ThirdParty->find('list', [
			'conditions' => [
				'ThirdParty.id' => $complianceAttributes
			],
			'recursive' => -1
		]);
		$this->set('thirdParties', $thirdParties);

		$this->set('title_for_layout', __('Admin Dashboard'));
		$this->set('subtitle_for_layout', __('Summary for all sections.'));

		$this->Crud->execute();
	}

	public function _beforePaginateAdmin(CakeEvent $event) {
		$event->subject->paginator->settings['conditions'] = [
			'DashboardKpi.type' => DashboardKpi::TYPE_ADMIN,
			'DashboardKpi.category' => [
				DashboardKpi::CATEGORY_GENERAL,
				DashboardKpi::CATEGORY_RECENT,
				DashboardKpi::CATEGORY_OWNER,
				DashboardKpi::CATEGORY_AWARENESS,
				DashboardKpi::CATEGORY_COMPLIANCE,
			],
			'DashboardKpi.model' => DashboardKpi::listModelsForType(DashboardKpi::TYPE_ADMIN)
		];
	}

	public function add($type = 1) {
		$this->set('dashboardKpiType', $type);

		$this->Crud->on('beforeRender', array($this, '_initOptions'));
		$this->Crud->on('beforeSave', array($this, '_beforeSave'));
		$this->Crud->on('afterSave', array($this, '_afterSave'));

		$this->set('title_for_layout', __('Add a new one'));
		$this->set('subtitle_for_layout', __('Summary for all sections.'));
		
		$this->Crud->execute();
	}

	public function delete() {
		$this->set('title_for_layout', __('Delete your KPI'));

		return $this->Crud->execute();
	}

	public function _initOptions(CakeEvent $event) {
		$event->subject->controller->set($event->subject->model->getFieldDataEntity()->getViewOptions());
		$Attribute = $event->subject->model->DashboardKpiSingleAttribute;

		$event->subject->controller->set('DashboardKpiSingleAttribute', $Attribute);
		$event->subject->controller->set($Attribute->getFieldDataEntity()->getViewOptions('Attribute'));
	}

	public function _beforeSave(CakeEvent $event) {
		$data = &$event->subject->request->data;

		$attrClass = $data['DashboardKpiAttribute'][0]['model'];
		$filterId = $data['DashboardKpiAttribute'][0]['foreign_key'];

		$AdvancedFilter = ClassRegistry::init('AdvancedFilter');
		$AdvancedFilter->id = $filterId;

		$data['DashboardKpi']['model'] = $AdvancedFilter->field('model');
		$data['DashboardKpi']['owner_id'] = $event->subject->controller->logged['id'];

		// we set the title as required field
		$Model = $event->subject->model;
		$titleField = $Model->validator()->getField('title');
		$titleRule = $titleField->getRule('notBlank');
		$titleRule->allowEmpty = false;
	}

	public function _afterSave(CakeEvent $event) {
		
	}

	public function sync($reset = false) {
		dd($this->DashboardKpi->sync(compact('reset')));
	}

}