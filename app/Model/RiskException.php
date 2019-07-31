<?php
App::uses('SectionBase', 'Model');
App::uses('UserFields', 'UserFields.Lib');

class RiskException extends SectionBase {
	public $displayField = 'title';

	public $mapping = array(
		'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => true,
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'description', 'author_id', 'expiration', 'closure_date', 'status'
			)
		),
		'CustomFields.CustomFields',
		'Taggable',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => [
				'Requester' => [
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
		'expiration' => array(
			'rule' => 'date',
			'required' => true
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
		)
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'RiskException'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'RiskException'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'RiskException'
			)
		),
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Tag.model' => 'RiskException'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'Risk',
		'ThirdPartyRisk',
		'BusinessContinuity'
	);

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


	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Risk Exceptions');
		$this->_group = parent::SECTION_GROUP_RISK_MGT;

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
		];

		$this->fieldData = [
			'title' => [
				'label' => __('Title'),
				'editable' => true,
				'description' => __('Provide a descriptive title. Example: Lack of budget for Antivirus for Mac-OS.')
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Describe why this Exception is required, who authorized, Etc.')
			],
			'Requester' => $UserFields->getFieldDataEntityData($this, 'Requester', [
				'label' => __('Requester'), 
				'description' => __('The individual that has approved this exception, this typically is the same person that "Owns" the risk or someone with higher hierarchy. This field will be later used for notifications (such as when the exception is due, Etc).')
			]),
			'expiration' => [
				'label' => __('Expiration'),
				'editable' => true,
				'description' => __('Set the deadline for this Risk Exception. At the expiration day, a full re-assessment on this exception is usually done.')
			],
			'expired' => [
				'label' => __('Expired'),
				'editable' => false,
			],
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
			'status' => [
				'label' => __('Status'),
				'editable' => true,
				'options' => [$this, 'statuses'],
				'description' => __('Defines if the exception is still valid or not.')
			],
			'Tag' => array(
                'label' => __('Tags'),
				'editable' => true,
				'type' => 'tags',
				'description' => __('OPTIONAL: Use tags for this exception, examples are: Approved, To be reviewed, Important, Etc.'),
				'empty' => __('Add a tag')
            ),
			'Risk' => [
				'label' => __('Risk'),
				'editable' => false,
			],
			'ThirdPartyRisk' => [
				'label' => __('Third Party Risk'),
				'editable' => false,
			],
			'BusinessContinuity' => [
				'label' => __('Business Continuity'),
				'editable' => false,
			],
		];

		$this->notificationSystem = array(
			'macros' => array(
				'EXCEPTION_ID' => array(
					'field' => 'RiskException.id',
					'name' => __('Risk Exception ID')
				),
				'EXCEPTION_TITLE' => array(
					'field' => 'RiskException.title',
					'name' => __('Risk Exception Title')
				),
				'EXCEPTION_REQUESTER' => $UserFields->getNotificationSystemData('Requester', [
					'name' => __('Risk Exception Requester')
				]),
				'EXCEPTION_EXPIRATION' => array(
					'field' => 'RiskException.expiration',
					'name' => __('Risk Exception Expiration')
				),
				'EXCEPTION_CLOSURE_DATE' => array(
					'field' => 'RiskException.closure_date',
					'name' => __('Risk Exception Closure Date')
				),
				'EXCEPTION_STATUS' => array(
					'type' => 'status',
					'name' => __('Risk Exception Status'),
					'status' => array(
						'model' => 'RiskException'
					)
				),
			),
			'customEmail' =>  true
		);

		// $this->filterArgs = array(
		// 	'search' => array(
		// 		'type' => 'like',
		// 		'field' => array('RiskException.title', 'RiskException.description'),
		// 		'_name' => __('Search')
		// 	)
		// );

		$this->mapping['statusManager'] = array(
			'expired' => array(
				'column' => 'expired',
				'fn' => 'statusIsExpired',
				'migrateRecords' => array(
					'Risk',
					'ThirdPartyRisk',
					'BusinessContinuity'
				),
				'toggles' => array(
					array(
						'value' => ITEM_STATUS_EXPIRED,
						'message' => __('The Risk Exception %s has expired'),
						'messageArgs' => array(
							0 => '%Current%.title'
						)
					),
					array(
						'value' => ITEM_STATUS_NOT_EXPIRED,
						'message' => __('The Risk Exception %s is no longer expired %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Current%.expiration'
						)
					)
				)
			)
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
						'findField' => 'RiskException.title',
						'field' => 'RiskException.id',
					)
				),
				'requester_id' => $UserFields->getAdvancedFilterFieldData('RiskException', 'Requester', [
					'name' => __('Requester'),
				]),
				'expiration' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Expiration'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'RiskException.expiration',
						'field' => 'RiskException.id',
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
						'findField' => 'RiskException.closure_date',
						'field' => 'RiskException.id',
					),
				),
				'status' => array(
					'type' => 'select',
					'name' => __('Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'RiskException.status',
						'field' => 'RiskException.id',
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
						'method' => 'findComplexType',
						'findField' => 'Tag.title',
						'field' => 'RiskException.id',
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
				'risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Asset Risks'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Risk.id',
						'field' => 'RiskException.id',
					),
					'data' => array(
						'method' => 'getRisks',
					),
					'many' => true,
					'contain' => array(
						'Risk' => array(
							'title'
						)
					),
				),
				'third_party_risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Third Party Risks'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ThirdPartyRisk.id',
						'field' => 'RiskException.id',
					),
					'data' => array(
						'method' => 'getThirdPartyRisks',
					),
					'many' => true,
					'contain' => array(
						'ThirdPartyRisk' => array(
							'title'
						)
					),
				),
				'business_continuity_id' => array(
					'type' => 'multiple_select',
					'name' => __('Business Risks'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'BusinessContinuity.id',
						'field' => 'RiskException.id',
					),
					'data' => array(
						'method' => 'getBusinessContinuities',
					),
					'many' => true,
					'contain' => array(
						'BusinessContinuity' => array(
							'title'
						)
					),
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Risk Exceptions'),
			'pdf_file_name' => __('risk_exceptions'),
			'csv_file_name' => __('risk_exceptions'),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
			'use_new_filters' => true,
			'add' => true,
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
			'use_new_filters' => true
		);

		parent::__construct($id, $table, $ds);
	}

	public function beforeValidate($options = array())
	{
		if ($this->data[$this->alias]['closure_date_toggle'] == 1) {
			$this->validate['closure_date']['notEmpty']['required'] = false;
			$this->validate['closure_date']['notEmpty']['allowEmpty'] = true;
		}

		return true;
	}

	public function beforeSave($options = array())
	{
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

	public function getObjectStatusConfig() {
        return [
            'expired' => [
            	'title' => __('Exception Expired'),
                'callback' => [$this, 'statusExpired'],
                'trigger' => [
                    [
                        'model' => $this->Risk,
                        'trigger' => 'ObjectStatus.trigger.risk_exception_expired'
                    ],
                    [
                        'model' => $this->ThirdPartyRisk,
                        'trigger' => 'ObjectStatus.trigger.risk_exception_expired'
                    ],
                    [
                        'model' => $this->BusinessContinuity,
                        'trigger' => 'ObjectStatus.trigger.risk_exception_expired'
                    ],
                    [
                        'model' => $this->Risk,
                        'trigger' => 'ObjectStatus.trigger.exceptions_issues'
                    ],
                    [
                        'model' => $this->ThirdPartyRisk,
                        'trigger' => 'ObjectStatus.trigger.exceptions_issues'
                    ],
                    [
                        'model' => $this->BusinessContinuity,
                        'trigger' => 'ObjectStatus.trigger.exceptions_issues'
                    ],
                ]
            ],
        ];
    }

    public function statusExpired($conditions = null) {
        return parent::statusExpired([
            'RiskException.status !=' => RISK_EXCEPTION_CLOSED,
			'DATE(RiskException.expiration) < NOW()'
        ]);
    }

	public function afterSave($created, $options = array()) {
		if (!empty($this->id)) {
			return $this->updateRiskIssues();
		}

		return true;
	}

	/**
     * @deprecated status, in favor of RiskException::statusExpired()
     */
	public function statusIsExpired($id) {
		$today = date('Y-m-d', strtotime('now'));

		$isExpired = $this->find('count', array(
			'conditions' => array(
				'RiskException.id' => $id,
				'RiskException.status !=' => RISK_EXCEPTION_CLOSED,
				'DATE(RiskException.expiration) <' => $today
			),
			'recursive' => -1
		));

		return $isExpired;
	}

	public function getRisks() {
		$data = $this->Risk->find('list', array(
			'order' => array('Risk.title' => 'ASC'),
			'fields' => array('Risk.id', 'Risk.title'),
			'recursive' => -1
		));

		return $data;
	}

	public function getThirdPartyRisks() {
		$data = $this->ThirdPartyRisk->find('list', array(
			'order' => array('ThirdPartyRisk.title' => 'ASC'),
			'fields' => array('ThirdPartyRisk.id', 'ThirdPartyRisk.title'),
			'recursive' => -1
		));

		return $data;
	}

	public function getBusinessContinuities() {
		$data = $this->BusinessContinuity->find('list', array(
			'order' => array('BusinessContinuity.title' => 'ASC'),
			'fields' => array('BusinessContinuity.id', 'BusinessContinuity.title'),
			'recursive' => -1
		));

		return $data;
	}

	public function getExceptionStatuses() {
		return getCommonStatuses();
	}

	public function getStatuses() {
		if (isset($this->data['RiskException']['status'])) {
			$statuses = getCommonStatuses();

			return $statuses[$this->data['RiskException']['status']];
		}

		return false;
	}

	/**
	 * Trigger updating exception issues field for Risks.
	 */
	private function updateRiskIssues() {
		$ret = $this->Risk->saveExceptionIssues($this->getRelatedRisks('Risk', $this->id));
		$ret &= $this->ThirdPartyRisk->saveExceptionIssues($this->getRelatedRisks('ThirdPartyRisk', $this->id));
		$ret &= $this->BusinessContinuity->saveExceptionIssues($this->getRelatedRisks('BusinessContinuity', $this->id));

		return $ret;
	}

	public function getRelatedRisks($model, $riskId) {
		$assocId = $this->hasAndBelongsToMany[$model]['associationForeignKey'];
		$with = $this->hasAndBelongsToMany[$model]['with'];
		$this->{$with}->bindModel(array(
			'belongsTo' => array($model)
		));

		//risk_exception_id
		$foreignKey = $this->hasAndBelongsToMany[$model]['foreignKey'];
		$data = $this->{$with}->find('list', array(
			'conditions' => array(
				$with . '.' . $foreignKey => $riskId
			),
			'fields' => array($with . '.' . $assocId),
			'recursive' => 0
		));

		return $data;
	}

	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'expiration');
	}

	public function expiredStatusToQuery($expiredField = 'expired', $dateField = 'date') {
		if (!isset($this->data['RiskException']['expired']) && isset($this->data['RiskException']['expiration'])) {
			$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
			if ($this->data['RiskException']['expiration'] < $today && $this->data['RiskException']['status'] == 1) {
				$this->data['RiskException']['expired'] = '1';
			}
			else {
				$this->data['RiskException']['expired'] = '0';
			}
		}
	}

	public function logExpirations($ids) {
		$this->logToModel('Risk', $ids);
		$this->logToModel('ThirdPartyRisk', $ids);
		$this->logToModel('BusinessContinuity', $ids);
	}

	public function logToModel($model, $ids = array()) {
		$assocId = $this->hasAndBelongsToMany[$model]['associationForeignKey'];

		$habtmModel = $this->hasAndBelongsToMany[$model]['with'];

		$this->{$habtmModel}->bindModel(array(
			'belongsTo' => array('RiskException')
		));

		//risk_exception_id
		$foreignKey = $this->hasAndBelongsToMany[$model]['foreignKey'];
		$data = $this->{$habtmModel}->find('all', array(
			'conditions' => array(
				$habtmModel . '.' . $foreignKey => $ids
			),
			'fields' => array($habtmModel . '.' . $assocId, 'RiskException.title'),
			'recursive' => 0
		));

		foreach ($data as $item) {
			$msg = __('Risk Exception "%s" expired', $item['RiskException']['title']);

			$this->{$model}->id = $item[$habtmModel][$assocId];
			$this->{$model}->addNoteToLog($msg);
			$this->{$model}->setSystemRecord($item[$habtmModel][$assocId], 2);
		}
	}

	/*public function getIssues($id = array()) {
		if (empty($id)) {
			return false;
		}

		$data = $this->find('list', array(
			'conditions' => array(
				'OR' => array(
					array(
						'SecurityService.id' => $id,
						'SecurityService.audits_all_done' => 0
					),
					array(
						'SecurityService.id' => $id,
						'SecurityService.audits_last_passed' => 0
					)
				)
			),
			'fields' => array('SecurityService.id', 'SecurityService.name'),
			'recursive' => 0
		));

		return $data;
	}*/
}
