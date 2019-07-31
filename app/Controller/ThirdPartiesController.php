<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ThirdPartiesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'Visualisation.Visualisation',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'ThirdParty'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'ThirdPartyType' => [
							'fields' => ['name']
						],
						'SecurityIncident' => [
							'fields' => ['ongoing_incident', 'security_incident_status_id']
						],
						'Attachment' => [
							'fields' => ['id']
						],
						'Comment',
						'Legal' => [
							'fields' => ['name']
						]
					],
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'UserFields.UserFields' => [
			'fields' => ['Sponsor']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Third Parties');
		$this->subTitle = __('Describe in this section all your third parties - typical examples include your suppliers (Internet providers, etc), business partners (customers, etc) and regulators (PCI-DSS, ISO, Etc). This section will be used for Risk Management (Risk Management / Third Party Risk Management) and to upload Compliance Packages (Compliance Management / Compliance Packages / Import).');
	}


	public function index() {
		$filterConditions = $this->ThirdParty->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions)) {
			$this->Paginator->settings['conditions'] = $filterConditions;
		}

		return $this->Crud->execute();
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Third Party.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Third Party');

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Third Party');

		return $this->Crud->execute();
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Most organizations execute businesses with the help of other partners (customers, suppliers, Etc). Understanding the exchange of information in between your organization and third parties is essential to the security program. Those Third Parties defined in this section will be used for Third Party Risk and Compliance Management purposes.');
	}
}
