<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ComplianceExceptionsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = ['Search.Prg', 'Pdf', 'Paginator', 'AdvancedFilters', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'ComplianceException'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'ComplianceManagement',
						'Comment',
						'Attachment',
						'Tag'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => ['Requestor']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Compliance Exceptions');
		$this->subTitle = __('Compliance Exceptions are used to record the temporary acceptance of some compliance issue. They are used while analysing compliance (Compliance Management / Compliance Analysis) by asociating compliace requirements for which the organisation has no plans to be compliant with complaince  exceptions.');
	}

	public function index() {
		$this->title = __('Compliance Exception Management');

		$this->set('complianceManagementViewItemUrl', $this->ComplianceException->ComplianceManagement->advancedFilterSettings['view_item']['ajax_action']);
		$this->initOptions();

		$this->Prg->commonProcess('ComplianceException');
		unset($this->request->data['ComplianceException']);

		$filterConditions = $this->ComplianceException->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions) && empty($this->request->query['advanced_filter'])) {
			$this->Paginator->settings['conditions'] = $filterConditions;
			$this->set('filterConditions', true);
		}

		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		return $this->Crud->execute();
	}

	public function _afterPaginate(CakeEvent $event) {
		$data = $event->subject->items;

		$this->ComplianceException->ComplianceManagement->attachCompliancePackageData($data);

		$event->subject->items = $data;
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Compliance Exception.');

		return $this->Crud->execute();
	}

	public function trash() {
		$this->set('title_for_layout', __('Compliance Exceptions (Trash)'));

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Compliance Exception');
		$this->initAddEditSubtitle();

		$this->initOptions();

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Compliance Exception');
		$this->initAddEditSubtitle();

		$this->initOptions();

		return $this->Crud->execute();
	}

	private function initOptions() {
		$statuses = array(
			0 => __( 'Closed' ),
			1 => __( 'Open' )
		);

		$users = $this->getUsersList();

		$this->set( 'statuses', $statuses );
		$this->set( 'users', $users );
		$this->set('tags', $this->ComplianceException->Tag->getTags('ComplianceException'));
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Compliance Exceptions are used to record the temporary acceptance of some compliance issue. They are used while running a compliance management program by mapping them to those compliance requirements which the organization has decided not to be compliant');
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->ComplianceException->find('first', array(
			'conditions' => array(
				'ComplianceException.id' => $id
			),
			'contain' => $this->UserFields->attachFieldsToArray(['Requestor'], array(
				'ComplianceManagement' => array(
					'fields' => array('id'),
					'CompliancePackageItem' => array(
						'fields' => array('id', 'item_id', 'name'),
						'CompliancePackage' => array(
							'fields' => array('id', 'name'),
							'ThirdParty' => array(
								'fields' => array('name')
							)
						)
					)
				),
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'CustomFieldValue'
				
			)),
			'recursive' => -1
		));

		$item['ComplianceException']['compliance_packages'] = implode(', ',array_unique(Hash::extract($item, 'ComplianceManagement.{n}.CompliancePackageItem.CompliancePackage.ThirdParty.name')));

		$customFieldsData = $this->CustomFieldsMgt->setData();
		$item = array_merge($item, $customFieldsData);
		
		$this->set('item', $item);
		$vars = array(
			'item' => $item
		);

		$name = Inflector::slug($item['ComplianceException']['title'], '-');
		$this->Pdf->renderPdf($name, '..'.DS.'ComplianceExceptions'.DS.'export_pdf', 'pdf', $vars, true);
	}

}
