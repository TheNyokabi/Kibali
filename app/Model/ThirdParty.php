<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');
App::uses('UserFields', 'UserFields.Lib');

class ThirdParty extends SectionBase {
	const TYPE_REGULATORS = 3;
	
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
				'name', 'description', 'third_party_type_id'
			)
		),
		'CustomFields.CustomFields',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => [
				'Sponsor' => [
					'mandatory' => false
				]
			]
		]
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'description' => array(
		)
	);

	public $belongsTo = array(
		'ThirdPartyType'
	);

	public $hasMany = array(
		'ServiceContract',
		'CompliancePackage',
		'ComplianceAudit',
		// 'SecurityIncident',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ThirdParty'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ThirdParty'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ThirdParty'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'ThirdPartyRisk',
		'Legal',
		'SecurityIncident'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Third Parties');
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
				'description' => __('Give a name to this Third Party, for example: Internet Suppliers'),
			],
			'third_party_type_id' => [
				'label' => __('Type'),
				'editable' => true,
				'description' => __('INFORMATIVE: Select an applicable type for this Third Party, whatever you choose will be simply informative, it wont affect what can be done with this third party.'),
			],
			'Sponsor' => $UserFields->getFieldDataEntityData($this, 'Sponsor', [
				'label' => __('Sponsor'),
				'description' => __('INFORMATIVE: Choose a representative for this third party.'),
			]),
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('OPTIONAL: Provide a brief description on how your orgnization collaborates with this Third Party.'),
			],
			'Legal' => [
				'label' => __('Liabilities'),
				'editable' => true,
				'description' => __('OPTIONAL: Choose all applicable liabilites for this third party, this will affect the Risk Score for this Third Party (read our Risk Management documentation to understand how this works).'),
			],
			'ThirdPartyRisk' => [
				'label' => __('Third Party Risks'),
				'editable' => false,
			],
			'SecurityIncident' => [
				'label' => __('Security Incidents'),
				'editable' => false,
			],
			'ServiceContract' => [
				'label' => __('Service Contracts'),
				'editable' => false,
			],
			'CompliancePackage' => [
				'label' => __('Compliance Packages'),
				'editable' => false,
			],
			'ComplianceAudit' => [
				'label' => __('Compliance Audits'),
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
						'findField' => 'ThirdParty.name',
						'field' => 'ThirdParty.id',
					)
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ThirdParty.description',
						'field' => 'ThirdParty.id',
					)
				),
				'third_party_type_id' => array(
					'type' => 'select',
					'name' => __('Type'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ThirdParty.third_party_type_id',
						'field' => 'ThirdParty.id',
					),
					'data' => array(
						'method' => 'getThirdPartyTypes',
						'empty' => __('All')
					),
					'contain' => array(
						'ThirdPartyType' => array(
							'name'
						)
					)
				),
				'sponsor_id' => $UserFields->getAdvancedFilterFieldData('ThirdParty', 'Sponsor', [
					'name' => __('Sponsor'),
				]),
				'legal_id' => array(
					'type' => 'multiple_select',
					'name' => __('Liabilities'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Legal.id',
						'field' => 'ThirdParty.id',
					),
					'data' => array(
						'method' => 'getLegals',
					),
					'many' => true,
					'contain' => array(
						'Legal' => array(
							'name'
						)
					),
				),
			)
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Third Parties'),
			'pdf_file_name' => __('third_parties'),
			'csv_file_name' => __('third_parties'),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
            'use_new_filters' => true,
			'add' => true,
		);

		parent::__construct($id, $table, $ds);
	}

	public function beforeSave($options = array()){
		$this->transformDataToHabtm(['Legal']);

		return true;
	}

	public function beforeFind($query) {
		parent::beforeFind(null);
		if (!isset($query['conditions'][$this->alias . '._hidden']) || $query['conditions'][$this->alias . '._hidden'] != 1) {
			$query['conditions'][$this->alias . '._hidden'] = 0;
		}
		else {
			$query['conditions'][$this->alias . '._hidden'] = array(0, 1);
		}

		return $query;
	}

	public function beforeDelete($cascade = true) {
		$ret = $this->deleteUselessRisk();
		$this->setRisksData();

		return $ret;
	}

	public function afterDelete() {
		$this->updateRiskScores();
	}

	/**
	 * Delete Third Party Risks that are associated with currently deleted Third Party only.
	 */
	private function deleteUselessRisk() {
		$data = $this->ThirdPartiesThirdPartyRisk->find('all', array(
			'conditions' => array(
				'ThirdPartiesThirdPartyRisk.third_party_id' => $this->id
			)
		));

		$ret = true;
		foreach ($data as $risk) {
			$count = $this->ThirdPartiesThirdPartyRisk->find('count', array(
				'conditions' => array(
					'ThirdPartiesThirdPartyRisk.third_party_risk_id' => $risk['ThirdPartiesThirdPartyRisk']['third_party_risk_id']
				)
			));

			if ($count == 1) {
				$ret &= $this->ThirdPartyRisk->delete($risk['ThirdPartiesThirdPartyRisk']['third_party_risk_id']);
			}
		}

		return $ret;
	}

	public function getThirdPartyTypes() {
		$data = $this->ThirdPartyType->find('list', array(
			'order' => array('ThirdPartyType.name' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

	public function getLegals() {
		$data = $this->Legal->find('list', array(
			'order' => array('Legal.name' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

	/**
	 * Set Third Party Risk IDs to the model for afterDelete() risk score updates.
	 */
	public function setRisksData() {
		$data = $this->find('all', array(
			'conditions' => array(
				'ThirdParty.id' => $this->id
			),
			'contain' => array(
				'ThirdPartyRisk' => array(
					'fields' => array('id')
				)
			)
		));

		$this->TpRiskIds = array();
		foreach ($data as $tp) {
			foreach ($tp['ThirdPartyRisk'] as $tpr) {
				$this->TpRiskIds[] = $tpr['id'];
			}
		}
	}

	public function updateRiskScores() {
		$this->ThirdPartyRisk->calculateAndSaveRiskScoreById($this->TpRiskIds);
	}
}
