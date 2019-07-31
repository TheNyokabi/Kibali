<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class LegalsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'Comment',
						'Attachment' => [
							'fields' => ['id']
						]
					],
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'CustomFields.CustomFieldsMgt' => array('model' => 'Legal'),
		'UserFields.UserFields' => [
			'fields' => ['LegalAdvisor']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Legal Constraints');
		$this->subTitle = __('This section allows you to define all applicable business liabilities. This will be used later in the risk management module to magnify those risks which are subject to them');
	}

	/**
	 * Prototype.
	 */
	/*public function show($id) {
		$data = $this->Legal->find('first', array(
			'conditions' => array(
				'Legal.id' => $id
			)
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('title_for_layout', __('Legal Constrain'));
		$this->set('data', $data);
	}*/

	public function delete($id = null) {
		$this->subTitle = __('Delete a Legal.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Legal Constraint.');
		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	public function edit( $id = null ) {
		$this->title = __('Create a Legal Constraint.');
		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('This section allows you to define all applicable business liabilities. This will be used later in the risk management module to magnify those risks which are subject to them');
	}

}
