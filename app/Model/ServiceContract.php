<?php
App::uses('SectionBase', 'Model');
App::uses('UserFields', 'UserFields.Lib');

class ServiceContract extends SectionBase {
	public $displayField = 'name';
	
	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description', 'third_party_id', 'value', 'start', 'end'
			)
		),
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => ['Owner']
		]
	);

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'third_party_id' => array(
			'rule' => 'notBlank',
			'required' => true,
		),
		'value' => array(
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'This field can only contain numeric values'
			]
		),
		'start' => array(
			'rule' => 'date',
			'required' => true
		),
		'end' => array(
			'rule' => 'date',
			'required' => true
		)
	);

	public $belongsTo = array(
		'ThirdParty' => array(
			'counterCache' => true
		)
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ServiceContract'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ServiceContract'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ServiceContract'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'SecurityService'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Service Contracts');

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			]
		];

		$this->fieldData = [
			'name' => [
				'label' => __('Name'),
				'editable' => true,
				'description' => __('Give a name to the contract you have in between this provider and your organization. Examples: Firewall Hardware Support, Firewall Consulting Time, Etc.')
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Service contracts usually have a start and end dates. This will help you to keep track of renewals.')
			],
			'Owner' => $UserFields->getFieldDataEntityData($this, 'Owner', [
				'label' => __('Owner'), 
				'description' => __('Select one or more user accounts that are related to this contract.')
			]),
			'third_party_id' => [
				'label' => __('Third Party'),
				'editable' => true,
				'description' => __('Select the previously defined third party that delivers this support contract.')
			],
			'value' => [
				'label' => __('Service Value'),
				'editable' => true,
				'description' => __('The value of contract.')
			],
			'start' => [
				'label' => __('Start Date'),
				'editable' => true,
				'description' => __('When the contract starts')
			],
			'end' => [
				'label' => __('End Date'),
				'editable' => true,
				'description' => __('Service contracts usually have a start and end dates. This will help you to keep track of renewals.')
			],
			'expired' => [
				'label' => __('Expired'),
				'editable' => false,
			],
			'SecurityService' => [
				'label' => __('Security Service'),
				'editable' => false,
			],
		];

		$this->notificationSystem = array(
			'macros' => array(
				'SECSERV_ID' => array(
					'field' => 'SecurityService.{n}.id',
					'name' => __('Security Service ID')
				),
				'SECSERV_NAME' => array(
					'field' => 'SecurityService.{n}.name',
					'name' => __('Security Service Name')
				),
				'SECSERV_OBJECTIVE' => array(
					'field' => 'SecurityService.{n}.objective',
					'name' => __('Security Service Objective')
				),
				'SECSERV_OWNER' => $UserFields->getNotificationSystemData('Owner', [
					'name' => __('Security Service Owner')
				]),
				'SECCONTRACT_NAME' => array(
					'field' => 'ServiceContract.name',
					'name' => __('Security Contract Name')
				),
				'SECCONTRACT_THIRDPARTY' => array(
					'field' => 'ThirdParty.name',
					'name' => __('Third Party Name')
				),
				'SECCONTRACT_VALUE' => array(
					'field' => 'ServiceContract.value',
					'name' => __('Security Contract Value')
				),
				'SECCONTRACT_STARTDATE' => array(
					'field' => 'ServiceContract.start',
					'name' => __('Security Contract Start')
				),
				'SECCONTRACT_ENDDATE' => array(
					'field' => 'ServiceContract.end',
					'name' => __('Security Contract End')
				)
			),
			'customEmail' =>  true
		);

		parent::__construct($id, $table, $ds);

		$this->mapping['statusManager'] = array(
			'expired' => array(
				'column' => 'expired',
				'fn' => 'statusIsExpired',
				'toggles' => array(
					array(
						'value' => SERVICE_CONTRACT_EXPIRED,
						'message' => __('The Service Contract %s has expired'),
						'messageArgs' => array(
							0 => '%Current%.name'
						)
					),
					array(
						'value' => SERVICE_CONTRACT_NOT_EXPIRED,
						'message' => __('The Service Contract %s new expiration date is %s'),
						'messageArgs' => array(
							0 => '%Current%.name',
							1 => '%Current%.end'
						)
					)
				)
			)
		);
	}

	public function getObjectStatusConfig() {
        return [
            'expired' => [
            	'title' => __('Expired'),
                'callback' => [$this, 'statusExpired'],
                'type' => 'danger'
            ],
        ];
    }

    public function statusExpired($conditions = null) {
        return parent::statusExpired([
            'ServiceContract.end < DATE(NOW())'
        ]);
    }

    /**
	 * @deprecated status, in favor of ServiceContract::statusExpired()
	 */
	public function statusIsExpired($id) {
		$today = date('Y-m-d', strtotime('now'));

		$isExpired = $this->find('count', array(
			'conditions' => array(
				'ServiceContract.id' => $id,
				'DATE(ServiceContract.end) <' => $today
			),
			'recursive' => -1
		));

		return $isExpired;
	}

	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'end');
	}
}
