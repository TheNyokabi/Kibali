<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class TeamRolesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = [];
	public $components = [
		'Search.Prg', 'Pdf', 'Paginator', 
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'Crud.Crud' => [
            'actions' => [
                'index' => [
                    'contain' => [
                    	'User'
                    ],
                ],
            ]
        ],
        'CustomFields.CustomFieldsMgt' => array('model' => 'TeamRole'),
	];

	public function beforeFilter() {
        $this->Crud->enable(['index', 'add', 'edit', 'delete']);

        parent::beforeFilter();

        $this->title = __('Program Team Members & Roles');
        $this->subTitle = __('Define program members, roles, teams and their competences. This is of particular relevance for those programs aligned with ISO 27001');
    }

	public function delete($id = null) {
		$this->title = __('Team Roles');
		$this->subTitle = __('Delete a Team Role.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Team Role');
		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Team Role');
		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = false;
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->TeamRole->find('first', array(
			'conditions' => array(
				'TeamRole.id' => $id
			),
			'contain' => array(
				'User',
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
			)
		));

		$vars = array(
			'item' => $item
		);

		$this->set($vars);

		$name = Inflector::slug($item['TeamRole']['role'], '-');
		$this->Pdf->renderPdf($name, '..'.DS.'TeamRoles'.DS.'export_pdf', 'pdf', $vars, true);
	}

}
