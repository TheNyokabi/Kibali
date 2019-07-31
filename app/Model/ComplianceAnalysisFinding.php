<?php
App::uses('SectionBase', 'Model');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');
App::uses('UserFields', 'UserFields.Lib');

class ComplianceAnalysisFinding extends SectionBase {
	public $displayField = 'title';

	public $mapping = array(
		'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => array('index')
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'description'
			)
		),
		'AuditLog.Auditable' => array(
			'ignore' => array(
				'created',
				'modified',
			)
		),
		'Utils.SoftDelete',
		'CustomFields.CustomFields',
		'Taggable',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => [
				'Owner',
				'Collaborator' => [
					'mandatory' => false
				]
			]
		]
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'due_date' => array(
			'rule' => 'date',
			'required' => true
		),
		'ThirdParty' => array(
			'minCount' => array(
				'rule' => array('multiple', array('min' => 1)),
				'message' => 'You have to select at least one Compliance Package'
			)
		),
		'CompliancePackageItem' => array(
			'minCount' => array(
				'rule' => array('multiple', array('min' => 1)),
				'message' => 'You have to select at least one Compliance Package Item'
			)
		),
		// 'asset_owner_id' => array(
		// 	'rule' => 'notBlank',
		// 	'required' => true,
		// 	'allowEmpty' => false
		// ),
		// 'business_unit_id' => array(
		// 	'rule' => array( 'multiple', array( 'min' => 1 ) )
		// ),
		// 'asset_media_type_id' => array(
		// 	'rule' => 'notBlank',
		// 	'required' => true
		// ),
		// 'review' => array(
		// 	'notEmpty' => array(
		// 		'rule' => 'notBlank',
		// 		'required' => true,
		// 		'message' => 'This field is required'
		// 	),
		// 	'date' => array(
		// 		'rule' => 'date',
		// 		'required' => true,
		// 		'message' => 'This date has incorrect format'
		// 	)
		// )
	);

	// public $belongsTo = array(
	// );

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ComplianceAnalysisFinding'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ComplianceAnalysisFinding'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ComplianceAnalysisFinding'
			)
		),
	);

	public $hasAndBelongsToMany = array(
		'ComplianceManagement' => array(
			'with' => 'ComplianceAnalysisFindingsComplianceManagement'
		),
		// 'CompliancePackage',
		'ThirdParty',
		'CompliancePackageItem'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Compliance Analysis Findings');
        $this->_group = parent::SECTION_GROUP_COMPLIANCE_MGT;

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = [
			'title' => array(
				'label' => __('Name'),
				'editable' => true,
				'description' => __('Provide a title for this finding, such as "FIND01 - Missing Firewall Policies"'),
			),
			'description' => array(
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Describe the finding.'),
			),
			'due_date' => array(
				'label' => __('Due Date'),
				'editable' => true,
				'description' => __('Input the date by which this compliance findings must be resolved, you can assign notifications to remind your team about this deadline.'),
			),
			'status' => array(
				'label' => __('Status'),
				'options' => array($this, 'statuses'),
				'editable' => true,
				'description' => __('Set the status of this compliance finding as "open" or "closed" (once is resolved).'),
			),
			'Tag' => array(
                'label' => __('Tags'),
				'editable' => true,
				'type' => 'tags',
				'description' => __('You can use tags to profile your findings, examples are "In Remediation", "Networks", Etc.'),
				'empty' => __('Add a tag')
            ),
            'Owner' => $UserFields->getFieldDataEntityData($this, 'Owner', [
				'label' => __('Owner'), 
				'description' => __('Use this field to select one or more individuals that are accountable to get this issue sorted out. This is typically someone at the GRC department.')
			]),
			'Collaborator' => $UserFields->getFieldDataEntityData($this, 'Collaborator', [
				'label' => __('Collaborator'), 
				'description' => __('Use this field to select one or more individuals that are responsible to get this issue sorted out. This is typically someone at the department where the finding was found, this could be a Technical department, Etc.')
			]),
			'ComplianceManagement' => array(
				'label' => __('Compliance Management'),
				'editable' => false,
				'hidden' => true
			)
		];

		$this->notificationSystem = array(
			'macros' => array(
				'FINDING_ID' => array(
					'field' => 'ComplianceAnalysisFinding.id',
					'name' => __('Finding ID')
				),
				'FINDING_NAME' => array(
					'field' => 'ComplianceAnalysisFinding.title',
					'name' => __('Finding Title')
				),
				'FINDING_DESCRIPTION' => array(
					'field' => 'ComplianceAnalysisFinding.description',
					'name' => __('Finding Description')
				),
				'FINDING_DUE_DATE' => array(
					'field' => 'ComplianceAnalysisFinding.due_date',
					'name' => __('Finding Due Date')
				)
			),
			'customEmail' =>  true
		);

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'title' => array(
					'type' => 'text',
					'name' => __('Title'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceAnalysisFinding.title',
						'field' => 'ComplianceAnalysisFinding.id',
					)
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceAnalysisFinding.description',
						'field' => 'ComplianceAnalysisFinding.id',
					)
				),
				'due_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Due Date'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceAnalysisFinding.due_date',
						'field' => 'ComplianceAnalysisFinding.id',
					),
				),
				'status' => array(
					'type' => 'select',
					'name' => __('Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceAnalysisFinding.status',
						'field' => 'ComplianceAnalysisFinding.id',
					),
					'data' => array(
						'method' => 'getStatuses',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'tag_title' => array(
					'type' => 'multiple_select',
					'name' => __('Tags'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Tag.title',
						'field' => 'ComplianceAnalysisFinding.id',
					),
					'data' => array(
						'method' => 'getTags',
					),
					'many' => true,
					'contain' => array(
						'Tag' => array(
							'title'
						)
					),
				),
				'owner_id' => $UserFields->getAdvancedFilterFieldData('ComplianceAnalysisFinding', 'Owner', [
					'name' => __('Owner'),
				]),
				'collaborator_id' => $UserFields->getAdvancedFilterFieldData('ComplianceAnalysisFinding', 'Collaborator', [
					'name' => __('Collaborator'),
				])
			),
			__('Compliance Findings') => array(
				'third_party_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compliance Package Name'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.third_party_id',
						'field' => 'ComplianceAnalysisFinding.id',
						'path' => [
							'ComplianceAnalysisFindingsComplianceManagement' => [
								'findField' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_management_id',
								'field' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_analysis_finding_id',
							],
							'ComplianceManagement' => [
								'findField' => 'ComplianceManagement.compliance_package_item_id',
								'field' => 'ComplianceManagement.id',
							],
							'CompliancePackageItem' => [
								'findField' => 'CompliancePackageItem.compliance_package_id',
								'field' => 'CompliancePackageItem.id',
							],
							'CompliancePackage' => [
								'findField' => 'CompliancePackage.third_party_id',
								'field' => 'CompliancePackage.id',
							],
						],
						'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
					),
					'data' => array(
						'method' => 'getThirdParties',
					),
					'many' => true,
					'field' => 'ComplianceManagement.{n}.CompliancePackageItem.CompliancePackage.ThirdParty.name',
					'containable' => array(
						'ComplianceManagement' => array(
							'fields' => array('id'),
							'CompliancePackageItem' => array(
								'fields' => array('id'),
								'CompliancePackage' => array(
									'fields' => array('id'),
									'ThirdParty' => array(
										'fields' => array('id', 'name')
									)
								)
							)
						)
					)
				),
				'compliance_package_item_item_id' => array(
					'type' => 'text',
					'name' => __('Item ID'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.item_id',
						'field' => 'ComplianceAnalysisFinding.id',
						'path' => [
							'ComplianceAnalysisFindingsComplianceManagement' => [
								'findField' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_management_id',
								'field' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_analysis_finding_id',
							],
							'ComplianceManagement' => [
								'findField' => 'ComplianceManagement.compliance_package_item_id',
								'field' => 'ComplianceManagement.id',
							],
							'CompliancePackageItem' => [
								'findField' => 'CompliancePackageItem.item_id',
								'field' => 'CompliancePackageItem.id',
							],
						]
					),
					'many' => true,
					'field' => 'ComplianceManagement.{n}.CompliancePackageItem.item_id',
					'containable' => array(
						'ComplianceManagement' => array(
							'fields' => array('id'),
							'CompliancePackageItem' => array(
								'fields' => array('item_id'),
							)
						)
					)
				),
				'compliance_package_item_name' => array(
					'type' => 'text',
					'name' => __('Item Name'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.name',
						'field' => 'ComplianceAnalysisFinding.id',
						'path' => [
							'ComplianceAnalysisFindingsComplianceManagement' => [
								'findField' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_management_id',
								'field' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_analysis_finding_id',
							],
							'ComplianceManagement' => [
								'findField' => 'ComplianceManagement.compliance_package_item_id',
								'field' => 'ComplianceManagement.id',
							],
							'CompliancePackageItem' => [
								'findField' => 'CompliancePackageItem.name',
								'field' => 'CompliancePackageItem.id',
							],
						]
					),
					'many' => true,
					'field' => 'ComplianceManagement.{n}.CompliancePackageItem.name',
					'containable' => array(
						'ComplianceManagement' => array(
							'fields' => array('id'),
							'CompliancePackageItem' => array(
								'fields' => array('name'),
							)
						)
					)
				),
				'compliance_package_item_description' => array(
					'type' => 'text',
					'name' => __('Item Description'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.description',
						'field' => 'ComplianceAnalysisFinding.id',
						'path' => [
							'ComplianceAnalysisFindingsComplianceManagement' => [
								'findField' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_management_id',
								'field' => 'ComplianceAnalysisFindingsComplianceManagement.compliance_analysis_finding_id',
							],
							'ComplianceManagement' => [
								'findField' => 'ComplianceManagement.compliance_package_item_id',
								'field' => 'ComplianceManagement.id',
							],
							'CompliancePackageItem' => [
								'findField' => 'CompliancePackageItem.description',
								'field' => 'CompliancePackageItem.id',
							],
						]
					),
					'many' => true,
					'field' => 'ComplianceManagement.{n}.CompliancePackageItem.description',
					'containable' => array(
						'ComplianceManagement' => array(
							'fields' => array('id'),
							'CompliancePackageItem' => array(
								'fields' => array('description'),
							)
						)
					)
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => $this->label,
			'pdf_file_name' => __('compliance_analysis_findings'),
			'csv_file_name' => __('compliance_analysis_findings'),
			'max_selection_size' => 10,
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
			'use_new_filters' => true,
			'add' => true,
		);

		parent::__construct($id, $table, $ds);
	}

	/*
	 * Type of statuses
	 */
	 public static function statuses($value = null) {
		$options = array(
			self::STATUS_CLOSED => __('Closed'),
			self::STATUS_OPEN => __('Open')
		);
		return parent::enum($value, $options);
	}
	const STATUS_CLOSED = 0;
	const STATUS_OPEN = 1;

	public function getStatuses() {
		return self::statuses();
	}

	public function getObjectStatusConfig() {
        return [
            'open' => [
            	'title' => __('Open'),
                'callback' => [$this, 'statusOpen'],
                'type' => 'success',
                'storageSelf' => false,
            ],
            'closed' => [
            	'title' => __('Closed'),
                'callback' => [$this, 'statusClosed'],
                'type' => 'success',
                'storageSelf' => false,
            ],
            'expired' => [
            	'title' => __('Expired'),
                'callback' => [$this, 'statusExpired'],
            ],
        ];
    }

    public function statusExpired($conditions = null)
    {
        $data = $this->find('count', [
        	'conditions' => [
        		'ComplianceAnalysisFinding.id' => $this->id,
	            'ComplianceAnalysisFinding.status !=' => self::STATUS_CLOSED,
				'DATE(ComplianceAnalysisFinding.due_date) < NOW()'
			],
			'recursive' => -1
        ]);

        return (bool) $data;
    }

    public function statusOpen() {
    	$data = $this->find('count', [
    		'conditions' => [
    			'ComplianceAnalysisFinding.id' => $this->id,
    			'ComplianceAnalysisFinding.status' => self::STATUS_OPEN
			]
		]);

		return (boolean) $data;
    }

    public function statusClosed() {
    	return !$this->statusOpen();
    }

	/*public function beforeValidate($options = array()) {
		if (!$this->checkRelatedExists('BusinessUnit', $this->data['Asset']['business_unit_id'])) {
			$this->invalidate('business_unit_id', __('At least one of the selected items does not exist.'));
		}
	}*/

	public function beforeSave($options = array()) {
        $this->transformDataToHabtm(array('ThirdParty', 'CompliancePackageItem'));

        if (isset($this->data['CompliancePackageItem']['CompliancePackageItem'])) {
        	$items = (array) $this->data['CompliancePackageItem']['CompliancePackageItem'];
	        $ret = $managementItems = $this->complianceIntegrityCheck($items);

	        $this->data['ComplianceManagement']['ComplianceManagement'] = $managementItems;
        }

        return true;
    }

    // list of related compliance items
    public function getComplianceManagements($items) {
    	return $this->ComplianceManagement->find('list', [
        	'conditions' => [
        		'ComplianceManagement.compliance_package_item_id' => $items
        	],
        	'fields' => ['id', 'compliance_package_item_id'],
        	'recursive' => -1
        ]);
    }

    /**
     * Adds a missing compliance rows into db.
     */
    public function complianceIntegrityCheck($items) {
    	$managementItems = $this->getComplianceManagements($items);

        $missingItems = array_diff($items, $managementItems);
        if (empty($missingItems)) {
        	return array_keys($managementItems);
        }

        // saves the new missing records in compliance managements table
        if (!$this->ComplianceManagement->addItem($missingItems)) {
        	return false;
        }

        return $this->complianceIntegrityCheck($items);
    }

	// public function afterSave($created, $options = array()) {
	// }

	// public function beforeDelete($cascade = true) {
	// }

	// public function afterDelete() {
	// }

	public function bindComplianceJoinModel() {
		$modelAssoc = $this->getAssociated('ComplianceManagement');
		$with = $modelAssoc['with'];

		$this->bindModel(array(
			'hasMany' => array($with)
		));

		return $with;
	}
	/**
	 * Get commonly accessed compliance data through Analysis Finding model.
	 * 
	 * @param  array  $ids Finding IDs.
	 */
	public function getCommonComplianceData($ids = array()) {
		$with = $this->bindComplianceJoinModel();

		$joinIds = $this->{$with}->find('list', array(
			'conditions' => array(
				$with . '.compliance_analysis_finding_id' => $ids
			),
			'fields' => array(
				$with . '.compliance_management_id',
				$with . '.compliance_management_id'
			),
			'recursive' => -1
		));
		
		$commonData = $this->ComplianceManagement->getCommonComplianceData($joinIds);

		return $commonData;	
	}

	public function getThirdParties() {
		return $this->ComplianceManagement->getThirdParties();
	}

	public function getList() {
		$data = $this->find('list', array(
			'order' => array('ComplianceAnalysisFinding.title' => 'ASC'),
		));

		return $data;
	}

	public function findByCompliancePackage($data = array(), $filterParams = array()) {
		$this->ComplianceAnalysisFindingsComplianceManagement->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->ComplianceAnalysisFindingsComplianceManagement->Behaviors->attach('Search.Searchable');

		$value = $data[$filterParams['name']];

		$joins = array(
			array(
				'table' => 'compliance_managements',
				'alias' => 'ComplianceManagement',
				'type' => 'LEFT',
				'conditions' => array(
					'ComplianceAnalysisFindingsComplianceManagement.compliance_management_id = ComplianceManagement.id'
				)
			),
			array(
				'table' => 'compliance_package_items',
				'alias' => 'CompliancePackageItem',
				'type' => 'LEFT',
				'conditions' => array(
					'ComplianceManagement.compliance_package_item_id = CompliancePackageItem.id'
				)
			),
		);

		$conditions = array();
		if ($filterParams['findByField'] == 'ThirdParty.id') {
			$conditions = array(
				$filterParams['findByField'] => $value
			);
			$joins[] = array(
				'table' => 'compliance_packages',
				'alias' => 'CompliancePackage',
				'type' => 'LEFT',
				'conditions' => array(
					'CompliancePackageItem.compliance_package_id = CompliancePackage.id'
				)
			);
			$joins[] = array(
				'table' => 'third_parties',
				'alias' => 'ThirdParty',
				'type' => 'LEFT',
				'conditions' => array(
					'ThirdParty.id = CompliancePackage.third_party_id'
				)
			);
		}
		else {
			$conditions = array(
				$filterParams['findByField'] . ' LIKE' => '%' . $value . '%'
			);
		}

		$query = $this->ComplianceAnalysisFindingsComplianceManagement->getQuery('all', array(
			'conditions' => $conditions,
			'joins' => $joins,
			'fields' => array(
				'ComplianceAnalysisFindingsComplianceManagement.compliance_analysis_finding_id'
			),
			// 'group' => 'ThirdParty.id'
		));

		return $query;
	}
}
