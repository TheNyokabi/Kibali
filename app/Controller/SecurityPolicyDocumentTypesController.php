<?php
App::uses('SectionBaseController', 'Controller');

class SecurityPolicyDocumentTypesController extends SectionBaseController {

	public $helpers = [];

	public $components = [
		'Ajax' => [
			'actions' => ['index', 'add', 'delete'],
			'redirects' => [
				'index' => [
					'url' => ['controller' => 'securityPolicies', 'action' => 'index']
				]
			]
		]
	];

	public function beforeFilter() {
		$this->Ajax->settings['modules'] = [];

		$this->Crud->enable(['index', 'add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Security Policy Document Types');
		$this->subTitle = __('');
	}

	public function index() {
		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Security Policy Document Type');

		return $this->Crud->execute();
	}

	public function edit($id) {
		$this->title = __('Edit a Security Policy Document Type');

		if (!$this->SecurityPolicyDocumentType->isEditable($id)) {
			throw new NotFoundException();
		}

		return $this->Crud->execute();
	}

	public function _beforeDelete(CakeEvent $event) {
		if (!$this->SecurityPolicyDocumentType->isDeletable($event->subject->id)) {
			$this->Session->setFlash(__('There are policies using this tag and therefor we cant delete this item. Please use filters to select and update all policies using this tag to another tag.'), FLASH_ERROR);
			return false;
		}
	}

	public function delete() {
		$this->title = __('Delete a Security Policy Document Type');

		$this->Crud->on('beforeDelete', array($this, '_beforeDelete'));

		return $this->Crud->execute();
	}
}