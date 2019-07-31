<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ProgramScopesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = [];
	public $components = [
		'Search.Prg', 'Paginator', 'Pdf', 'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'Attachment',
						'Comment'
					]
				]
			]
		],
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Program Scope');
		$this->subTitle = __('Describe the scope of your Security Program');
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Scope.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Scope.');

		$this->initAddEditSubtitle();
		$this->initOptions();

		return $this->Crud->execute();
	}

	public function edit( $id = null ) {
		$this->title = __('Edit a Scope.');

		$this->initAddEditSubtitle();
		$this->initOptions($id);

		return $this->Crud->execute();
	}

	private function initOptions($id = null) {
		$this->set('hasCurrent', $this->ProgramScope->hasCurrentStatus($id));
	}

	private function initAddEditSubtitle() {
		$this->subTitle = false;
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->ProgramScope->find('first', array(
			'conditions' => array(
				'ProgramScope.id' => $id
			),
			'contain' => array(
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				)
			)
		));

		$vars = array(
			'item' => $item
		);

		$this->set($vars);

		$name = Inflector::slug('program-scope-' . $item['ProgramScope']['version'], '-');
		$this->Pdf->renderPdf($name, '..'.DS.'ProgramScopes'.DS.'export_pdf', 'pdf', $vars, true);
	}

}
