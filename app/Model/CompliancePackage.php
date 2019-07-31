<?php
App::uses('SectionBase', 'Model');
App::uses('ThirdParty', 'Model');

class CompliancePackage extends SectionBase {
	public $displayField = 'name';
	
	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'package_id', 'name', 'description', 'third_party_id'
			)
		)
	);

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $workflow = array(
		// 'pullWorkflowData' => array('CompliancePackageItem')
	);

	public $validate = array(
		'third_party_id' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'name' => array(
			'rule' => 'notBlank',
			'required' => false,
			'allowEmpty' => false
		),
		'package_id' => array(
			'rule' => 'notBlank',
			'required' => false,
			'allowEmpty' => false
		),
		'CsvFile' => array(
			'required' => false,
			'rule' => array(
				'extension',
				array( 'csv' )
			)
		)
	);

	public $belongsTo = array(
		'ThirdParty'
	);

	public $hasMany = array(
		'CompliancePackageItem',
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'CompliancePackage'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'CompliancePackage'
			)
		),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Compliance Packages');

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = [
			'third_party_id' => array(
				'label' => __('Third Party'),
				'editable' => true,
			),
			'package_id' => array(
				'label' => __('Package ID'),
				'editable' => false,
			),
			'name' => array(
				'label' => __('Name'),
				'editable' => true,
			),
			'description' => array(
				'label' => __('Description'),
				'editable' => true,
			),
			'CompliancePackageItem' => array(
				'label' => __('Compliance Package Item'),
				'editable' => false,
				'hidden' => true,
			),
		];

		$this->filterArgs = array(
			'search' => array(
				'type' => 'like',
				'field' => array('CompliancePackage.name'),
				'_name' => __('Search')
			)
		);

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'third_party_id' => array(
					'type' => 'multiple_select',
					'name' => __('Third Party'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.third_party_id',
						'field' => 'CompliancePackage.id',
					),
					'data' => array(
						'method' => 'getThirdParties',
					),
					'contain' => array(
						'ThirdParty' => array(
							'name'
						)
					)
				),
				'package_id' => array(
					'type' => 'text',
					'name' => __('Package ID'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.package_id',
						'field' => 'CompliancePackage.id',
					),
				),
				'name' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.name',
						'field' => 'CompliancePackage.id',
					),
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.description',
						'field' => 'CompliancePackage.id',
					),
				),
				'item_id' => array(
					'type' => 'text',
					'name' => __('Item ID'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.item_id',
						'field' => 'CompliancePackage.id',
					),
					'many' => true,
					'contain' => array(
						'CompliancePackageItem' => array(
							'item_id'
						)
					)
				),
				'item_name' => array(
					'type' => 'text',
					'name' => __('Item Name'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.name',
						'field' => 'CompliancePackage.id',
					),
					'many' => true,
					'contain' => array(
						'CompliancePackageItem' => array(
							'name'
						)
					)
				),
				'item_description' => array(
					'type' => 'text',
					'name' => __('Item Description'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.description',
						'field' => 'CompliancePackage.id',
					),
					'many' => true,
					'contain' => array(
						'CompliancePackageItem' => array(
							'description'
						)
					)
				),
				'item_audit_questionaire' => array(
					'type' => 'text',
					'name' => __('Item Audit Questionaire'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.audit_questionaire',
						'field' => 'CompliancePackage.id',
					),
					'many' => true,
					'contain' => array(
						'CompliancePackageItem' => array(
							'audit_questionaire'
						)
					)
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Compliance Packages'),
			'pdf_file_name' => __('compliance_packages'),
			'csv_file_name' => __('compliance_packages'),
			'actions' => true,
			// 'trash' => true,
			'history' => true,
			'bulk_actions' => true,
			'use_new_filters' => true,
			'add' => true,
		);

		parent::__construct($id, $table, $ds);
	}

	/**
	 * Get the default condition rules for a relation with other model that applies to the current section.
	 * 
	 * @return array Default conditions.
	 */
	public static function thirdPartyListingConditions() {
		return [
			'ThirdParty.third_party_type_id' => ThirdParty::TYPE_REGULATORS
		];
	}

	public function getThirdParties() {
		$data = $this->ThirdParty->find('list', [
			'order' => ['ThirdParty.name']
		]);

		return $data;
	}

	public function afterSave($created, $options = array()) {
		parent::afterSave($created, $options);

		$this->syncPackageItems($this->id);
	}

	/**
	 * Delete all items of package if package is soft deleted.
	 * If $id is null we find all soft deleted packages and delete all theirs items.
	 */
	public function syncPackageItems($id = null) {
		if ($id !== null) {
			if ($this->exists($id)) {
				return true;
			}
		}
		else {
			$id = $this->find('list', [
				'conditions' => ['CompliancePackage.deleted' => true],
				'fields' => ['CompliancePackage.id']
			]);
		}

		return $this->deletePackageItems($id);
	}

	/**
	 * Delete all items of package.
	 * 
	 * @param  mixed $packageId Id or array of Ids.
	 * @return boolean Success.
	 */
	public function deletePackageItems($packageId) {
		if (empty($packageId)) {
			return true;
		}

		$packageId = (array) $packageId;

		$itemIds = $this->CompliancePackageItem->find('list', [
			'conditions' => ['CompliancePackageItem.compliance_package_id' => $packageId],
			'fields' => ['CompliancePackageItem.id']
		]);

		$ret = true; 

		if (!empty($itemIds)) {
			foreach ($itemIds as $id) {
				$ret &= $this->CompliancePackageItem->delete($id);
			}
		}

		return $ret;
	}
}
