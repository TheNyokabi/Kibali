<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');
App::uses('UserFields', 'UserFields.Lib');

class BusinessUnit extends SectionBase {
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
				'name', 'description'
			)
		),
		'CustomFields.CustomFields',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'Visualisation.Visualisation',
		'UserFields.UserFields' => [
			'fields' => ['BusinessUnitOwner']
		]
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		)
	);

	public $hasMany = array(
		'Process',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'BusinessUnit'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'BusinessUnit'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'BusinessUnit'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'Asset',
		'BusinessContinuity',
		'Legal'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Business Units');
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
				'description' => __('Name this business unit, for example: Finance'),
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Give a brief description of what this Business Unit delivers'),
			],
			'BusinessUnitOwner' => $UserFields->getFieldDataEntityData($this, 'BusinessUnitOwner', [
				'label' => __('Business Unit Owner'),
				'description' => __('INFORMATIVE: Select one system user that will act as representative for this business unit, if you havent got a user account you can use admin.'),
			]),
			'Legal' => [
				'label' => __('Liabilities'),
				'editable' => true,
				'description' => __('OPTIONAL: Select one or more liabilities to which this BU is subject to. Remeber you can define liabilities under Organisation / Legal Constrains or create a new one using the icon on the right.'),
			],
			'Asset' => [
				'label' => __('Assets'),
				'editable' => false,
			],
			'BusinessContinuity' => [
				'label' => __('Business Continuities'),
				'editable' => false,
			],
			'Process' => [
				'label' => __('Process'),
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
						'findField' => 'BusinessUnit.name',
						'field' => 'BusinessUnit.id',
					)
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'BusinessUnit.description',
						'field' => 'BusinessUnit.id',
					)
				),
				'business_unit_owner_id' => $UserFields->getAdvancedFilterFieldData('BusinessUnit', 'BusinessUnitOwner', [
					'name' => __('Business Unit Owner')
				]),
				'legal_id' => array(
					'type' => 'multiple_select',
					'name' => __('Liabilities'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Legal.id',
						'field' => 'BusinessUnit.id',
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
			'pdf_title' => __('Business Units'),
			'pdf_file_name' => __('business_units'),
			'csv_file_name' => __('business_units'),
			'additional_actions' => array(
				'Process' => __('Processes')
			),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
            'use_new_filters' => true,
			'add' => true,
		);

		parent::__construct($id, $table, $ds);
	}

	public function beforeFind($query) {
		parent::beforeFind(null);

		$alias = $this->alias;
		if (!isset( $query['conditions'][$alias . '._hidden'] ) || $query['conditions'][$alias . '._hidden'] != 1) {
			$query['conditions'][$alias . '._hidden'] = 0;
		} else {
			$query['conditions'][$alias . '._hidden'] = array(0, 1);
		}

		return $query;
	}

	public function beforeSave($options = array()){
		$this->transformDataToHabtm(['Legal']);

		return true;
	}

	public function afterSave($created, $options = array()) {
		$ret = true;

		// $ret &= $this->resaveNotifications($this->id);
		return $ret;
	}

	public function resaveNotifications($id) {
		$ret = true;

		$this->bindNotifications();
		$ret &= $this->NotificationObject->NotificationSystem->saveCustomUsersByModel($this->alias, $id);

		$processIds = $this->Process->find('list', array(
			'conditions' => array(
				'Process.business_unit_id' => $id
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$this->Process->bindNotifications();
		$ret &= $this->Process->NotificationObject->NotificationSystem->saveCustomUsersByModel('Process', $processIds);

		return $ret;
	}

	public function beforeDelete($cascade = true) {
		$ret = $this->deleteUselessRisk();
		$this->setAssociatedBusinessContinuities();

		return $ret;
	}

	public function afterDelete() {
		$this->updateRiskScores();
	}

	public function setAssociatedBusinessContinuities() {
		// set associated business continuity IDs and use them in afterDelete() to update risk scores.
		$this->businessContinuityIds = $this->BusinessContinuitiesBusinessUnit->find('list', array(
			'conditions' => array(
				'BusinessContinuitiesBusinessUnit.business_unit_id' => $this->id
			),
			'fields' => array('id', 'business_continuity_id')
		));
	}

	public function updateRiskScores() {
		// update all related business continuity risk scores.
		//foreach ($this->businessContinuityIds as $id) {
			$this->BusinessContinuity->calculateAndSaveRiskScoreById($this->businessContinuityIds);
		//}
	}

	private function deleteUselessRisk() {
		$data = $this->BusinessContinuitiesBusinessUnit->find('all', array(
			'conditions' => array(
				'BusinessContinuitiesBusinessUnit.business_unit_id' => $this->id
			)
		));

		$ret = true;
		foreach ($data as $risk) {
			$count = $this->BusinessContinuitiesBusinessUnit->find('count', array(
				'conditions' => array(
					'BusinessContinuitiesBusinessUnit.business_continuity_id' => $risk['BusinessContinuitiesBusinessUnit']['business_continuity_id']
				)
			));

			if ($count == 1) {
				$ret &= $this->BusinessContinuity->delete($risk['BusinessContinuitiesBusinessUnit']['business_continuity_id']);
			}
		}

		return $ret;
	}
	
	public function getThreatsVulnerabilities($businessUnitIds) {
		$assetIds = $this->AssetsBusinessUnit->find('list', array(
			'conditions' => array(
				'AssetsBusinessUnit.business_unit_id' => $businessUnitIds
			),
			'fields' => array('AssetsBusinessUnit.asset_id'),
			'recursive' => -1
		));

		return $this->Asset->getThreatsVulnerabilities($assetIds);
	}

	public function getLegals() {
		$data = $this->Legal->find('list', array(
			'order' => array('Legal.name' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}

	/**
	 * Get related Legal IDs for selected Business Unit IDs array.
	 * @param  array  $buIds Business Unit IDs.
	 * @return array         Legal Ids.
	 */
	public function getLegalIds($buIds = array()) {
		$legalIds = $this->BusinessUnitsLegal->find('list', array(
			'conditions' => array(
				'BusinessUnitsLegal.business_unit_id' => $buIds
			),
			'fields' => array(
				'BusinessUnitsLegal.legal_id'
			)
		));

		return array_values($legalIds);
	}

}
