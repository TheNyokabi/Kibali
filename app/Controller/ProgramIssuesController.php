<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ProgramIssuesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = [];
	public $components = [
		'Search.Prg', 'Paginator', 'Pdf', 'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'CustomFields.CustomFieldsMgt' => array('model' => 'ProgramIssue'),
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'ProgramIssueType'
					]
				]
			]
		],
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Program Issues & Challenges');
		$this->subTitle = __('Issues are elements in the internal and/or external environment that drive the development of the program and its goals. This is of particular interest for those programs looking for ISO 27001 compliance.');
	}

	public function _beforeRender(CakeEvent $event) {
		parent::beforeRender($event);

		$this->initOptions();
	}

	public function delete($id = null) {
		$this->subTitle =  __('Delete an Issue.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title =  __('Create an Issue.');

		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title =  __('Edit an Issue.');

		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	private function initOptions() {
		$this->set('internalTypes', $this->ProgramIssue->getInternalTypes());
		$this->set('externalTypes', $this->ProgramIssue->getExternalTypes());
	}

	private function initAddEditSubtitle() {
		$this->subTitle = false;
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->ProgramIssue->find('first', array(
			'conditions' => array(
				'ProgramIssue.id' => $id
			),
			'contain' => array(
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'ProgramIssueType'
			)
		));

		$vars = array(
			'item' => $item
		);

		$this->set($vars);

		$name = Inflector::slug($item['ProgramIssue']['name'], '-');
		$this->Pdf->renderPdf($name, '..'.DS.'ProgramIssues'.DS.'export_pdf', 'pdf', $vars, true);
	}

}
