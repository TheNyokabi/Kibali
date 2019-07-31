<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');
App::uses('UserFields', 'UserFields.Lib');

class Legal extends SectionBase {
	public $displayField = 'name';

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description', 'risk_magnifier'
			)
		),
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'Visualisation.Visualisation',
		'CustomFields.CustomFields',
		'UserFields.UserFields' => [
			'fields' => ['LegalAdvisor']
		]
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'description' => array(
		),
		'risk_magnifier' => array(
			'rule' => 'numeric',
			'allowEmpty' => true
		)
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'Legal'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'Legal'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'Legal'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'Asset',
		'BusinessUnit',
		'ThirdParty'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//

		$this->label = __('Legals');
		$this->_group = 'organization';

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			]
		];

		$this->fieldData = [
			'name' => [
				'label' => __('Name'),
				'editable' => true,
				'description' => __('Give a name to this Liability. For example "Contractual Liabilities, Legal Liabilities, Customer Liabilities, Etc'),
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Give a brief description of what this liability involves.'),
			],
			'risk_magnifier' => [
				'label' => __('RTO'),
				'editable' => true,
				'description' => __('OPTIONAL: If you are using "eramba" Risk Calculation, this value will automatically increase (if you set values over "1") or decrease (if you choose values under "1") Risk scores for any Risk that has in some way this liability asociated.<br><br> Remember that Assets (Asset Management / Asset Identification), Third Parties (Organisation / Third Parties) and Business Units (Organisatino / Bussiness Units) can be linked with these liabilities.'),
			],
			'LegalAdvisor' => $UserFields->getFieldDataEntityData($this, 'LegalAdvisor', [
				'label' => __('Legal Advisor'), 
				'description' => __('INFORMATIVE: Choose one representative for this liability, whatever you input here will not be used for notifications, etc. This field is simply informative.')
			]),
			'Asset' => [
				'label' => __('Assets'),
				'editable' => false,
			],
			'BusinessUnit' => [
				'label' => __('Business Units'),
				'editable' => false,
			],
			'ThirdParty' => [
				'label' => __('Third Parties'),
				'editable' => false,
			],
		];

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'name' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Legal.name',
						'field' => 'Legal.id',
					)
				),
				'legal_advisor_id' => $UserFields->getAdvancedFilterFieldData('Legal', 'LegalAdvisor', [
					'name' => __('Legal Advisor'),
				]),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Legal.description',
						'field' => 'Legal.id',
					)
				),
				'risk_magnifier' => array(
					'type' => 'number',
					'name' => __('Risk Magnifier'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Legal.risk_magnifier',
						'field' => 'Legal.id',
					),
				),
			)
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Legals'),
			'pdf_file_name' => __('legals'),
			'csv_file_name' => __('legals'),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
            'use_new_filters' => true
		);

		parent::__construct($id, $table, $ds);
	}

	public function afterSave($created, $options = array()) {
		if (!$created) {
			$this->setRisksData();
			$this->updateRiskScores();
		}
	}

	public function beforeDelete($cascade = true) {
		$this->setRisksData();
		return true;
	}

	public function afterDelete() {
		$this->updateRiskScores();
	}

	private function setRisksData() {
		$data = $this->find('first', array(
			'conditions' => array(
				'Legal.id' => $this->id,
			),
			'contain' => array(
				'BusinessUnit',
				'Asset',
				'ThirdParty'
			),
			'softDelete' => false
		));

		foreach ($data['BusinessUnit'] as $bu) {
			$this->BusinessUnit->id = $bu['id'];
			$this->BusinessUnit->setAssociatedBusinessContinuities();
		}

		foreach ($data['Asset'] as $asset) {
			$this->Asset->id = $asset['id'];
			$this->Asset->setRisksData();
		}

		foreach ($data['ThirdParty'] as $tp) {
			$this->ThirdParty->id = $tp['id'];
			$this->ThirdParty->setRisksData();
		}
	}

	private function updateRiskScores() {
		$this->BusinessUnit->updateRiskScores();
		$this->Asset->updateRiskScores();
		$this->ThirdParty->updateRiskScores();
	}

	public function getLegalAdvisors() {
		$data = $this->LegalAdvisor->find('list', array(
			'fields' => array('LegalAdvisor.id', 'LegalAdvisor.full_name'),
			'order' => array('LegalAdvisor.full_name' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}
}
