<?php
App::uses('SectionBase', 'Model');
App::uses('UserFields', 'UserFields.Lib');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');

class ComplianceException extends SectionBase {
	public $displayField = 'title';

	public $mapping = array(
		'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'description', 'expiration', 'closure_date', 'status'
			)
		),
		'CustomFields.CustomFields',
		'Taggable',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => ['Requestor']
		]
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'expiration' => array(
			'rule' => 'date'
		),
		'closure_date' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field cannot be left blank'
			),
			'date' => array(
				'rule' => 'date',
				'message' => 'Please enter a valid date'
			)
		),
		'Requestor' => array(
			'rule' => array('multiple', array('min' => 1))
		)
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ComplianceException'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ComplianceException'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ComplianceException'
			)
		),
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Tag.model' => 'ComplianceException'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'ComplianceManagement'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Compliance Exceptions');
        $this->_group = parent::SECTION_GROUP_COMPLIANCE_MGT;

		$this->notificationSystem = array(
			'macros' => array(
				'EXCEPTION_ID' => array(
					'field' => 'ComplianceException.id',
					'name' => __('Compliance Exception ID')
				),
				'EXCEPTION_TITLE' => array(
					'field' => 'ComplianceException.title',
					'name' => __('Compliance Exception Title')
				),
				'EXCEPTION_REQUESTER' => $UserFields->getNotificationSystemData('Requestor', [
					'name' => __('Compliance Exception Requester')
				]),
				'EXCEPTION_EXPIRATION' => array(
					'field' => 'ComplianceException.expiration',
					'name' => __('Compliance Exception Expiration')
				),
				'EXCEPTION_CLOSURE_DATE' => array(
					'field' => 'ComplianceException.closure_date',
					'name' => __('Compliance Exception Closure Date')
				),
				'EXCEPTION_STATUS' => array(
					'type' => 'status',
					'name' => __('Compliance Exception Status'),
					'status' => array(
						'model' => 'ComplianceException'
					)
				),
			),
			'customEmail' =>  true
		);

		// $this->filterArgs = array(
		// 	'search' => array(
		// 		'type' => 'like',
		// 		'field' => array('ComplianceException.title', 'ComplianceException.description'),
		// 		'_name' => __('Search')
		// 	),
		// 	'status' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Status')
		// 	),
		// 	'author_id' => array(
		// 		'type' => 'subquery',
		// 		'method' => 'findByRequestor',
		// 		'field' => 'ComplianceException.id',
		// 		'_name' => __('Requester')
		// 	),
		// 	'expired' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Expired')
		// 	)
		// );

		$this->mapping['statusManager'] = array(
			'expired' => array(
				'column' => 'expired',
				'fn' => 'statusIsExpired',
				'migrateRecords' => array('ComplianceManagement'),
				'toggles' => array(
					array(
						'value' => ITEM_STATUS_EXPIRED,
						'message' => __('The Compliance Exception %s has expired'),
						'messageArgs' => array(
							0 => '%Current%.title'
						)
					),
					array(
						'value' => ITEM_STATUS_NOT_EXPIRED,
						'message' => __('The Compliance Exception %s is no longer expired %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Current%.expiration'
						)
					)
				)
			)
		);

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = [
			'title' => array(
				'label' => __('Title'),
				'editable' => true,
				'description' => __('Provide a descriptive title. Example: Lack of budgets for compliance with 2.3 of PCI-DSS'),
			),
			'description' => array(
				'label' => __('Description'),
				'editable' => true,
				'description' => __('OPTIONAL: A good description should include what the compliance is (threat, vulnerabilities, impact, etc.), the options which were considered and discarded, etc.'),
			),
			'expiration' => array(
				'label' => __('Expiration'),
				'editable' => true,
				'description' => __('Set the date at which this exception will be reconsidered. You can setup notifications to alarm the requester or anyone else before the date is due.'),
			),
			'expired' => array(
				'label' => __('Expired'),
				'editable' => false,
				'hidden' => true
			),
			'closure_date_toggle' => array(
				'label' => __('Closure date auto'),
				'type' => 'toggle',
				'editable' => false,
				'description' => __('Check this option if you want to manually set a closure date, otherwise the date will be set when the status of the exception is changed from “open” to “close”.')
			),
			'closure_date' => [
				'label' => __('Closure Date'),
				'editable' => true,
				'description' => __('Set the date when this exception was closed.')
			],
			'status' => array(
				'label' => __('Status'),
				'editable' => true,
				'options' => array($this, 'statuses'),
				'description' => __('Register if this exception is closed or open (valid).')
			),
			'Requestor' => $UserFields->getFieldDataEntityData($this, 'Requestor', [
				'label' => __('Requester'),
				'description' => __('This is usually the individual who approved the compliance exception, this is typically someone with sufficient authority to make such decisions.')
			]),
			'Tag' => array(
                'label' => __('Tags'),
				'editable' => true,
				'type' => 'tags',
				'description' => __('OPTIONAL: Use tags to profile your compliance exceptions, examples are "PCI-DSS", "Budget Issues", Etc.'),
				'empty' => __('Add a tag')
            ),
            'ComplianceManagement' => array(
				'label' => __('Compliance Management'),
				'editable' => false,
				'hidden' => true
			),
		];

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
						'findField' => 'ComplianceException.title',
						'field' => 'ComplianceException.id',
					)
				),
				'requestor_id' => $UserFields->getAdvancedFilterFieldData('ComplianceException', 'Requestor', [
					'name' => __('Requestor'),
				]),
				'expiration' => array(
					'type' => 'date',
					'comparison' => false,
					'name' => __('Expiration'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceException.expiration',
						'field' => 'ComplianceException.id',
					),
				),
				'closure_date' => array(
					'type' => 'date',
					'comparison' => true,
					'show_default' => true,
					'name' => __('Closure Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceException.closure_date',
						'field' => 'ComplianceException.id',
					),
				),
				'status' => array(
					'type' => 'select',
					'name' => __('Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceException.status',
						'field' => 'ComplianceException.id',
					),
					'data' => array(
						'method' => 'getExceptionStatuses',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'tag_title' => array(
                    'type' => 'multiple_select',
                    'name' => __('Tag'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByTags',
                        'field' => 'ComplianceException.id',
                        'model' => 'ComplianceException'
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
			),
			__('Mitigation') => array(
				'third_party_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compliance Package Name'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.third_party_id',
						'field' => 'ComplianceException.id',
						'path' => [
							'ComplianceExceptionsComplianceManagement' => [
								'findField' => 'ComplianceExceptionsComplianceManagement.compliance_management_id',
								'field' => 'ComplianceExceptionsComplianceManagement.compliance_exception_id',
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
							'fields' => array('id', 'compliance_package_item_id'),
							//contain data in ComplianceManagement::attachCompliancePackageData()
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
						'field' => 'ComplianceException.id',
						'path' => [
							'ComplianceExceptionsComplianceManagement' => [
								'findField' => 'ComplianceExceptionsComplianceManagement.compliance_management_id',
								'field' => 'ComplianceExceptionsComplianceManagement.compliance_exception_id',
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
							'fields' => array('id', 'compliance_package_item_id'),
							//contain data in ComplianceManagement::attachCompliancePackageData()
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
						'field' => 'ComplianceException.id',
						'path' => [
							'ComplianceExceptionsComplianceManagement' => [
								'findField' => 'ComplianceExceptionsComplianceManagement.compliance_management_id',
								'field' => 'ComplianceExceptionsComplianceManagement.compliance_exception_id',
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
							'fields' => array('id', 'compliance_package_item_id'),
							//contain data in ComplianceManagement::attachCompliancePackageData()
						)
					)
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Compliance Exceptions'),
			'pdf_file_name' => __('compliance_exceptions'),
			'csv_file_name' => __('compliance_exceptions'),
			'bulk_actions' => true,
			'trash' => true,
			'history' => true,
			'use_new_filters' => true,
			'add' => true,
		);

		parent::__construct($id, $table, $ds);
	}

	/*
     * static enum: Model::function()
     * @access static
     */
    public static function statuses($value = null) {
        $options = array(
            self::STATUS_CLOSED => __('Closed'),
            self::STATUS_OPEN => __('Open'),
        );
        return parent::enum($value, $options);
    }

    const STATUS_CLOSED = 0;
    const STATUS_OPEN = 1;

    public function getObjectStatusConfig() {
        return [
            'expired' => [
            	'title' => __('Expired'),
                'callback' => [$this, 'statusExpired'],
                'trigger' => [
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.compliance_exception_expired'
                    ],
                ]
            ],
        ];
    }

    public function statusExpired($conditions = null) {
        return parent::statusExpired([
			'ComplianceException.status !=' => COMPLIANCE_EXCEPTION_CLOSED,
			'DATE(ComplianceException.expiration) < NOW()'
        ]);
    }

	public function getStatuses() {
		if (isset($this->data['ComplianceException']['status'])) {
			$statuses = getCommonStatuses();

			return $statuses[$this->data['ComplianceException']['status']];
		}

		return false;
	}

	public function beforeValidate($options = array())
	{
		if ($this->data[$this->alias]['closure_date_toggle'] == 1) {
			$this->validate['closure_date']['notEmpty']['required'] = false;
			$this->validate['closure_date']['notEmpty']['allowEmpty'] = true;
		}

		return true;
	}

	public function beforeSave($options = array()){
		// transforms the data array to save the HABTM relation
		$this->transformDataToHabtm(array('Requestor'));

		//
		// Set closure date when status is set to CLOSED
		if (isset($this->data[$this->alias]['status']) &&
			isset($this->data[$this->alias]['closure_date_toggle'])) {
			if ($this->data[$this->alias]['status'] == self::STATUS_OPEN) {
				$this->data[$this->alias]['closure_date'] = null;
			} else {
				$id = $this->getId();
				$oldData = false;
				if ($id != false) {
					$oldData = $this->find('first', [
						'fields' => [
							'status'
						],
						'conditions' => [
							'id' => $id
						],
						'recursive' => -1
					]);
				}

				if ($this->data[$this->alias]['status'] == self::STATUS_CLOSED && 
					$this->data[$this->alias]['closure_date_toggle'] == 1 && 
					(!$oldData || $oldData[$this->alias]['status'] == self::STATUS_OPEN)) {
					$this->data[$this->alias]['closure_date'] = date('Y-m-d', time());
				}
			}
		}
		//

		return true;
	}

	public function getRequestors() {
		$data = $this->Requestor->find('list', array(
			'order' => array('Requestor.full_name' => 'ASC'),
			'fields' => array('Requestor.id', 'Requestor.full_name'),
			'recursive' => -1
		));
		return $data;
	}

	public function getPackages() {
		$data = $this->ComplianceManagement->CompliancePackageItem->CompliancePackage->find('list', array(
			'order' => array('CompliancePackage.name' => 'ASC'),
			'fields' => array('CompliancePackage.id', 'CompliancePackage.name'),
			'recursive' => -1
		));
		return $data;
	}

	public function getExceptionStatuses() {
		return getCommonStatuses();
	}

	/**
     * @deprecated status, in favor of ComplianceException::statusExpired()
     */
	public function statusIsExpired($id) {
		$today = date('Y-m-d', strtotime('now'));

		$isExpired = $this->find('count', array(
			'conditions' => array(
				'ComplianceException.id' => $id,
				'ComplianceException.status !=' => COMPLIANCE_EXCEPTION_CLOSED,
				'DATE(ComplianceException.expiration) <' => $today
			),
			'recursive' => -1
		));

		return $isExpired;
	}

	/**
	 * Callback used by Status Assessment to calculate expired field based on query data before saving and insert it into the query.
	 */
	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'expiration');
	}

	public function expiredStatusToQuery($expiredField = 'expired', $dateField = 'date') {
		if (!isset($this->data['ComplianceException']['expired']) && isset($this->data['ComplianceException']['expiration'])) {
			$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

			if ($this->data['ComplianceException']['expiration'] < $today && $this->data['ComplianceException']['status'] == 1) {
				$this->data['ComplianceException']['expired'] = '1';
			}
			else {
				$this->data['ComplianceException']['expired'] = '0';
			}
		}
	}

	public function logExpirations($ids) {
		$this->logToModel('CompliancePackageItem', $ids);
	}

	public function logToModel($model, $ids = array()) {

		$data = $this->ComplianceManagement->find('all', array(
			'conditions' => array(
				'ComplianceManagement.compliance_exception_id' => $ids
			),
			'fields' => array('ComplianceManagement.compliance_package_item_id', 'ComplianceException.title'),
			'recursive' => 0
		));

		foreach ($data as $item) {
			$msg = __('Compliance Exception "%s" expired', $item['ComplianceException']['title']);

			$this->ComplianceManagement->CompliancePackageItem->id = $item['ComplianceManagement']['compliance_package_item_id'];
			$this->ComplianceManagement->CompliancePackageItem->addNoteToLog($msg);
			$this->ComplianceManagement->CompliancePackageItem->setSystemRecord($item['ComplianceManagement']['compliance_package_item_id'], 2);
		}

	}

	public function expiredConditions($data = array()){
		$conditions = array();
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
		if($data['expired'] == 1){
			$conditions = array(
				'ComplianceException.status' => 1,
				'ComplianceException.expiration <' => $today
			);
		}
		elseif($data['expired'] == 0){
			$conditions = array(
				'ComplianceException.status' => 0
			);
		}

		return $conditions;
	}

	public function findByRequestor($data = array()) {
		$this->ComplianceExceptionsUser->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->ComplianceExceptionsUser->Behaviors->attach('Search.Searchable');

		$query = $this->ComplianceExceptionsUser->getQuery('all', array(
			'conditions' => array(
				'ComplianceExceptionsUser.user_id' => $data['author_id']
			),
			'fields' => array(
				'ComplianceExceptionsUser.compliance_exception_id'
			)
		));

		return $query;
	}

	public function getThirdParties() {
		return $this->ComplianceManagement->getThirdParties();
	}

}
