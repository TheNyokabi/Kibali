<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ServiceContractsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg','Paginator', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'ServiceContract' => [
							'Comment',
							'Attachment',
							'NotificationObject' => [
								'fields' => ['id', 'foreign_key', 'status_feedback', 'log_count']
							],
						]
					],
				]
			]
		],
		'UserFields.UserFields'
	];

	public function beforeFilter() {
		$this->Crud->enable(['add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Service Contracts');
		$this->subTitle = __('Here you can define any support contracts you have with suppliers. Map them to Security Services in order to keep budgets clear, and also get warnings when they are set to expire. Also for example, if you manage multiple SSL certificates you can define them here to ensure they don’t expire.');
	}

	public function index() {
		$this->ServiceContract->bindNotifications();

		$this->Crud->useModel('ThirdParty');
		$this->Crud->enable(['index']);

		$this->Paginator->settings['conditions'] = [
			'ThirdParty.service_contract_count !=' => 0
		];

		return $this->Crud->execute();
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Service Contract.');

		return $this->Crud->execute();
	}

	public function add($tp_id = null) {
		$this->title = __('Create a Service Contract');
		$this->initAddEditSubtitle();

		if (!empty($tp_id)) {
			$data = $this->ServiceContract->ThirdParty->find('first', array(
				'conditions' => array(
					'ThirdParty.id' => $tp_id
				),
				'recursive' => -1
			));

			if (empty($data)) {
				throw new NotFoundException();
			}
		}

		$this->set('tp_id', $tp_id);

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Service Contract');
		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('You can Here you can define any support contracts you have with suppliers. Map them to Security Services in order to keep budgets clear, and also get warnings when they are set to expire. Also for example, if you manage multiple SSL certificates you can define them here to ensure they don’t expire.');
	}

}
