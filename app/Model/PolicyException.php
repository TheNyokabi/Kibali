<?php
App::uses('SectionBase', 'Model');
App::uses('UserFields', 'UserFields.Lib');

class PolicyException extends SectionBase {
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
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'description', 'expiration', 'closure_date', 'status'
			)
		),
		'CustomFields.CustomFields',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
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
				'Comment.model' => 'PolicyException'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'PolicyException'
			)
		),
		'Classification' => array(
			'className' => 'PolicyExceptionClassification'
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'PolicyException'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'SecurityPolicy',
		'ThirdParty',
		'Asset'
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
		
		$this->label = __('Policy Exceptions');
		$this->_group = parent::SECTION_GROUP_CONTROL_CATALOGUE;

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
			'asset' => [
				'label' => __('Asset')
			],
		];

		$this->fieldData = [
			'title' => [
				'label' => __('Title'),
				'editable' => true,
				'description' => __('Give a descriptive title to this Exception')
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('OPTIONAL: Describe the Policy Exception in detail (when, what, where, why, whom, how).')
			],
			'expiration' => [
				'label' => __('Expiration'),
				'editable' => true,
				'description' => __('Exceptions are not eternal, they must expire at some point time. This setting will let you define notifications before this date to be sent to the requestor or anyone you define.')
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
				'description' => __('Describe the exception status, valid or closed.')
			],
			'SecurityPolicy' => [
				'label' => __('Security Policy Items'),
				'editable' => true,
				'description' => __('OPTIONAL: If a policy exception is requested is expected that you have a policy documented in eramba. Being able to link your exceptions to your policies allows you further analysis on which policies are not business aligned.')
			],
			'ThirdParty' => [
				'label' => __('Third Parties'),
				'editable' => true,
				'description' => __('OPTIONAL: Select any third parties affected by this policy exception.')
			],
			'Requestor' => $UserFields->getFieldDataEntityData($this, 'Requestor', [
				'label' => __('Requester'),
				'description' => __('This is usually the individual who requested the exception.')
			]),
			'Classification' => array(
                'label' => __('Tag'),
				'editable' => true,
				'type' => 'tags',
				'options' => [$this, 'getClassifications'],
				'description' => __('OPTIONAL: Use tags to classify your exceptions (high relevance, etc).'),
				'empty' => __('Add a tag')
            ),
            'Asset' => array(
                'label' => __('Assets'),
                'group' => 'asset',
				'editable' => true,
				'description' => __('OPTIONAL: select one or more assets that are affected by this policy exception.'),
            ),
		];

		$this->notificationSystem = array(
			'macros' => array(
				'EXCEPTION_ID' => array(
					'field' => 'PolicyException.id',
					'name' => __('Policy Exception ID')
				),
				'EXCEPTION_TITLE' => array(
					'field' => 'PolicyException.title',
					'name' => __('Policy Exception Title')
				),
				'EXCEPTION_DESCRIPTION' => array(
					'field' => 'PolicyException.description',
					'name' => __('Policy Exception Description')
				),
				'EXCEPTION_REQUESTER' => $UserFields->getNotificationSystemData('Requestor', [
					'name' => __('Policy Exception Requester')
				]),
				'EXCEPTION_EXPIRATION' => array(
					'field' => 'PolicyException.expiration',
					'name' => __('Policy Exception Expiration')
				),
				'EXCEPTION_CLOSURE_DATE' => array(
					'field' => 'PolicyException.closure_date',
					'name' => __('Policy Exception Closure Date')
				),
				'EXCEPTION_STATUS' => array(
					'type' => 'status',
					'name' => __('Policy Exception Status'),
					'status' => array(
						'model' => 'PolicyException'
					)
				),
			),
			'customEmail' =>  true
		);

		// $this->filterArgs = array(
		// 	'id' => array(
		// 		'type' => 'value',
		// 		'field' => array('PolicyException.id'),
		// 		'_name' => __('ID')
		// 	),
		// 	'search' => array(
		// 		'type' => 'like',
		// 		'field' => array('PolicyException.title', 'PolicyException.description'),
		// 		'_name' => __('Search')
		// 	),
		// 	'third_party_id' => array(
		// 		'type' => 'subquery',
		// 		'method' => 'findByThirdParty',
		// 		'field' => 'PolicyException.id',
		// 		'_name' => __('Third Party')
		// 	),
		// 	'status' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Status')
		// 	),
		// 	'user_id' => array(
		// 		'type' => 'subquery',
		// 		'method' => 'findByRequestor',
		// 		'field' => 'PolicyException.id',
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
				'migrateRecords' => array(),
				'toggles' => array(
					array(
						'value' => ITEM_STATUS_EXPIRED,
						'message' => __('The Policy Exception %s has expired'),
						'messageArgs' => array(
							0 => '%Current%.title'
						)
					),
					array(
						'value' => ITEM_STATUS_NOT_EXPIRED,
						'message' => __('The Policy Exception %s is no longer expired %s'),
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
						'findField' => 'PolicyException.title',
						'field' => 'PolicyException.id',
					)
				),
				'requestor_id' => $UserFields->getAdvancedFilterFieldData('PolicyException', 'Requestor', [
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
						'findField' => 'PolicyException.expiration',
						'field' => 'PolicyException.id',
					),
				),
				'classification_name' => array(
					'type' => 'multiple_select',
					'name' => __('Tag'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Classification.name',
						'field' => 'PolicyException.id',
					),
					'data' => array(
						'method' => 'getClassifications',
					),
					'many' => true,
					'field' => 'Classification.{n}.name',
					'containable' => array(
						'Classification' => array(
							'fields' => array('name'),
						)
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
						'findField' => 'PolicyException.closure_date',
						'field' => 'PolicyException.id',
					),
				),
				'status' => array(
					'type' => 'select',
					'name' => __('Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'PolicyException.status',
						'field' => 'PolicyException.id',
					),
					'data' => array(
						'method' => 'getExceptionStatuses',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
			),
			__('Asset') => array(
				'asset_id' => array(
					'type' => 'multiple_select',
					'name' => __('Assets'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Asset.id',
						'field' => 'PolicyException.id',
					),
					'data' => array(
						'method' => 'getAssets',
					),
					'many' => true,
					'contain' => array(
						'Asset' => array(
							'name'
						)
					),
				),
			),
			__('Mitigation') => array(
				'security_policy_id' => array(
					'type' => 'multiple_select',
					'name' => __('Security Policies'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityPolicy.id',
						'field' => 'PolicyException.id',
					),
					'data' => array(
						'method' => 'getSecurityPolicies',
					),
					'many' => true,
					'contain' => array(
						'SecurityPolicy' => array(
							'index'
						)
					),
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Policy Exceptions'),
			'pdf_file_name' => __('policy_exceptions'),
			'csv_file_name' => __('policy_exceptions'),
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

	public function beforeSave($options = array()) {
		$this->transformDataToHabtm(['SecurityPolicy', 'ThirdParty', 'Asset']);

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

	public function afterSave($created, $options = array()) {
		if (isset($this->data['PolicyException']['Classification'])) {
			$this->Classification->deleteAll(['Classification.policy_exception_id' => $this->id]);
			$this->saveClassifications($this->data['PolicyException']['Classification'], $this->id);
		}
	}

	private function saveClassifications($labels, $id) {
		if (empty($labels)) {
			return true;
		}

		$labels = explode(',', $labels);

		foreach ($labels as $name) {
			$tmp = array(
				'policy_exception_id' => $id,
				'name' => $name
			);

			$this->Classification->create();
			if (!$this->Classification->save($tmp)) {
				return false;
			}
		}

		return true;
	}

	public function getObjectStatusConfig() {
        return [
            'expired' => [
            	'title' => __('Exception Expired'),
                'callback' => [$this, 'statusExpired'],
            ],
        ];
    }

    public function statusExpired($conditions = null) {
        return parent::statusExpired([
            'PolicyException.status !=' => POLICY_EXCEPTION_CLOSED,
			'DATE(PolicyException.expiration) < NOW()'
        ]);
    }

    /**
     * @deprecated status, in favor of PolicyException::statusExpired()
     */
	public function statusIsExpired($id) {
		$today = date('Y-m-d', strtotime('now'));

		$isExpired = $this->find('count', array(
			'conditions' => array(
				'PolicyException.id' => $id,
				'PolicyException.status !=' => POLICY_EXCEPTION_CLOSED,
				'DATE(PolicyException.expiration) <' => $today
			),
			'recursive' => -1
		));

		return $isExpired;
	}

	public function getStatuses() {
		if (isset($this->data['PolicyException']['status'])) {
			$statuses = getCommonStatuses();

			return $statuses[$this->data['PolicyException']['status']];
		}

		return false;
	}

	public function getSecurityPolicies() {
		return $this->SecurityPolicy->getListWithType();
	}

	public function getExceptionStatuses() {
		return static::statuses();
	} 

	public function getClassifications() {
		$rawData = $this->Classification->find('list', array(
			'order' => array('Classification.name' => 'ASC'),
			'fields' => array('Classification.id', 'Classification.name'),
			'group' => array('Classification.name'),
			'recursive' => -1
		));

		$data = array();
		foreach ($rawData as $item) {
			$data[$item] = $item;
		}

		return $data;
	}

	public function findByClassifications($data) {
		$this->Classification->Behaviors->attach('Containable', array('autoFields' => false));
		$this->Classification->Behaviors->attach('Search.Searchable');

		$query = $this->Classification->getQuery('all', array(
			'conditions' => array(
				'Classification.name' => $data['classification_name']
			),
			'contain' => array(),
			'fields' => array(
				'Classification.policy_exception_id'
			),
		));

		return $query;
	}

	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'expiration');
	}

	public function expiredStatusToQuery($expiredField = 'expired', $dateField = 'date') {
		if (!isset($this->data['PolicyException']['expired']) && isset($this->data['PolicyException']['expiration'])) {
			$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
			if ($this->data['PolicyException']['expiration'] < $today && $this->data['PolicyException']['status'] == 1) {
				$this->data['PolicyException']['expired'] = '1';
			}
			else {
				$this->data['PolicyException']['expired'] = '0';
			}
		}
	}

	public function findByThirdParty($data = array()) {
		$this->PolicyExceptionsThirdParty->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->PolicyExceptionsThirdParty->Behaviors->attach('Search.Searchable');

		$query = $this->PolicyExceptionsThirdParty->getQuery('all', array(
			'conditions' => array(
				'PolicyExceptionsThirdParty.third_party_id' => $data['third_party_id']
			),
			'fields' => array(
				'PolicyExceptionsThirdParty.policy_exception_id'
			)
		));

		return $query;
	}

	public function findByRequestor($data = array()) {
		$this->PolicyExceptionsUser->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->PolicyExceptionsUser->Behaviors->attach('Search.Searchable');

		$query = $this->PolicyExceptionsUser->getQuery('all', array(
			'conditions' => array(
				'PolicyExceptionsUser.user_id' => $data['user_id']
			),
			'fields' => array(
				'PolicyExceptionsUser.policy_exception_id'
			)
		));

		return $query;
	}

	public function logExpirations($ids) {
		$this->logToModel('SecurityPolicy', $ids);
	}

	public function logToModel($model, $ids = array()) {
		$assocId = $this->hasAndBelongsToMany[$model]['associationForeignKey'];

		$habtmModel = $this->hasAndBelongsToMany[$model]['with'];

		$this->{$habtmModel}->bindModel(array(
			'belongsTo' => array('PolicyException')
		));

		//risk_exception_id
		$foreignKey = $this->hasAndBelongsToMany[$model]['foreignKey'];
		$data = $this->{$habtmModel}->find('all', array(
			'conditions' => array(
				$habtmModel . '.' . $foreignKey => $ids
			),
			'fields' => array($habtmModel . '.' . $assocId, 'PolicyException.title'),
			'recursive' => 0
		));
// debug($ids);
// exit;
		foreach ($data as $item) {
			$msg = __('Policy Exception "%s" expired', $item['PolicyException']['title']);

			$this->{$model}->id = $item[$habtmModel][$assocId];
			$this->{$model}->addNoteToLog($msg);
			$this->{$model}->setSystemRecord($item[$habtmModel][$assocId], 2);
		}

	}

	public function expiredConditions($data = array()){
		$conditions = array();
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
		if($data['expired'] == 1){
			$conditions = array(
				'PolicyException.status' => 1,
				'PolicyException.expiration <' => $today
			);
		}
		elseif($data['expired'] == 0){
			$conditions = array(
				'PolicyException.status' => 0
			);
		}

		return $conditions;
	}

	public function getAssets() {
		return $this->Asset->getList();
	}
}
