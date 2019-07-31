<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class BusinessUnitsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'BusinessUnit'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'Process' => [
							'fields' => ['id', 'name', 'description', 'rto', 'rpo', 'rpd'],
							'NotificationObject',
							'Comment',
							'Attachment'
						],
						'Legal' => [
							'fields' => ['name']
						],
						'Comment',
						'Attachment'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => ['BusinessUnitOwner']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Business Units');
		$this->subTitle = __('Use this section to describe your organisation and its core processes. For example Finance: (Accounting, Controlling , Paying Invoices, Etc). This section is only useful if you plan to use Risk Management functionalities in eramba.');
	}

	public function index() {
		$this->CustomFieldsMgt->setData('Process', 'process_');

		return $this->Crud->execute();
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Business Unit.');
		
		return $this->Crud->execute();
	}

	public function _afterItemSave(CakeEvent $event) {
		if ($event->subject->success) {
			if ($event->subject->created) {
				$this->BusinessUnit->setAssociatedBusinessContinuities();
				$this->BusinessUnit->updateRiskScores();
			}

			$this->BusinessUnit->resaveNotifications($event->subject->id);
		}

		parent::_afterItemSave($event);
	}

	public function add() {
		$this->title = __('Create a Business Unit');

		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	public function edit( $id = null ) {
		$this->title = __('Edit a Business Unit');

		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Define those business units (and later their processes) that are in the scope of your program. This will be later used to map assets and define risks.');
	}

}
