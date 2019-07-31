<?php
App::uses('SectionBase', 'Model');
App::uses('InheritanceInterface', 'Model/Interface');
App::uses('UserFields', 'UserFields.Lib');

class SecurityServiceMaintenance extends SectionBase implements InheritanceInterface {
	public $displayField = 'planned_date';

	public $mapping = array(
		'indexController' => array(
			'basic' => 'securityServiceMaintenances',
			'advanced' => 'securityServiceMaintenances',
			'params' => array('security_service_id')
		),
		'titleColumn' => false,
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'AuditLog.Auditable' => array(
			'ignore' => array(
				'created',
				'modified',
			)
		),
		'Utils.SoftDelete',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'task', 'task_conclusion'
			)
		),
		'CustomFields.CustomFields',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => [
				'MaintenanceOwner'
			]
		]
	);

	public $validate = array(
		'start_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => true,
				'allowEmpty' => true,
				'message' => 'Enter a valid date.'
			),
		),
		'end_date' => array(
			'date' => array(
				'rule' => array('date', 'ymd'),
				'required' => true,
				'allowEmpty' => true,
				'message' => 'Enter a valid date.'
			),
			'afterStartDate' => array(
				'rule' => array('checkEndDate', 'start_date'),
				'message' => 'End date must happen after the start date.'
			)
		)
	);

	public $createValidate = [
		'planned_date' => [
			'date' => [
				'rule' => ['date', 'ymd'],
				'required' => true,
				'message' => 'Enter a valid date.'
			],
		],
	];

	public $belongsTo = array(
		'SecurityService'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'SecurityServiceMaintenance'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'SecurityServiceMaintenance'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'SecurityServiceMaintenance'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Security Service Maintenances');
        $this->_group = parent::SECTION_GROUP_CONTROL_CATALOGUE;

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = array(
			'security_service_id' => array(
				'label' => __('Security Service'),
				'editable' => false,
				'hidden' => true
			),
			'task' => array(
				'label' => __('Maintenance Task'),
				'editable' => true,
				'description' => __('What is required to do in order to execute this maintenance task?')
			),
			'task_conclusion' => array(
				'label' => __('Task Conclusion'),
				'editable' => true,
				'description' => __('How did the task go?')
			),
			'MaintenanceOwner' => $UserFields->getFieldDataEntityData($this, 'MaintenanceOwner', [
				'label' => __('Maintenance Owner'), 
				'description' => __('Who executed the task?')
			]),
			'planned_date' => array(
				'label' => __('Planned Start'),
				'editable' => true,
				'hidden' => true
			),
			'start_date' => array(
				'label' => __('Maintenance Start Date'),
				'editable' => true,
				'description' => __('Register the date at which this maintenance started.')
			),
			'end_date' => array(
				'label' => __('Maintenance End Date'),
				'editable' => true,
				'description' => __('Register the date at which this maintenance ended.')
			),
			'result' => array(
				'label' => __('Task Result'),
				'options' => array($this, 'getAuditStatuses'),
				'editable' => true,
				'description' => __('Altough this is not strictly an audit, this maintenance task are a good indication to know if services are working or not.')
			),
		);

		$this->notificationSystem = array(
			'macros' => array(
				'SECSERV_ID' => array(
					'field' => 'SecurityService.id',
					'name' => __('Security Service ID')
				),
				'SECSERV_NAME' => array(
					'field' => 'SecurityService.name',
					'name' => __('Security Service Name')
				),
				'SECSERV_OBJECTIVE' => array(
					'field' => 'SecurityService.objective',
					'name' => __('Security Service Objective')
				),
				'SECSERV_OWNER' => array(
					'field' => 'SecurityService.User.full_name',
					'name' => __('Security Service Owner')
				),
				'SECSERV_MAINTENANCETASK' => array(
					'field' => 'SecurityServiceMaintenance.task',
					'name' => __('Security Service Maintenance Task')
				),
				'SECSERV_MAINTENANCECONCLUSION' => array(
					'field' => 'SecurityServiceMaintenance.task_conclusion',
					'name' => __('Security Service Maintenance Conclusion')
				),
				'SECSERV_MAINTENANCERESULT' => array(
					'type' => 'callback',
					'field' => 'SecurityServiceMaintenance.result',
					'name' => __('Security Service Maintenance Result'),
					'callback' => array($this, 'getFormattedResult')
				),
				'SECSERV_MAINTENANCEDATE' => array(
					'field' => 'SecurityServiceMaintenance.planned_date',
					'name' => __('Security Service Maintenance Date')
				),
				'SECSERV_MAINTENANCEOWNER' => array(
					'field' => 'User.full_name',
					'name' => __('Security Service Maintenance Owner')
				)
			),
			'customEmail' =>  true,
			'associateCustomFields' => array('SecurityService' => 'SECSERV')
		);

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'show_default' => true,
					'filter' => false
				),
				'security_service_id' => array(
					'type' => 'multiple_select',
					'name' => __('Control Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.id',
						'field' => 'SecurityServiceMaintenance.security_service_id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'SecurityService',
					),
					'contain' => array(
						'SecurityService' => array(
							'name'
						)
					),
				),
				/*'classifications' => array(
					'type' => 'multiple_select',
					'name' => __('Classification'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByClassifications',
						'field' => 'SecurityServiceMaintenance.security_service_id',
					),
					'joins' => array(
						array(
							'table' => 'security_service_classifications',
							'alias' => 'SecurityServiceClassification',
							'type' => 'LEFT',
							'conditions' => array(
								'SecurityServiceClassification.security_service_id = SecurityServiceMaintenance.security_service_id'
							)
						),
					),
					'data' => array(
						'method' => 'getClassifications',
					),
					'field' => 'SecurityServiceClassification.name'
				),*/
				'task' => array(
					'type' => 'text',
					'name' => __('Maintenance Task'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.task',
						'field' => 'SecurityServiceMaintenance.id',
					)
				),
				'planned_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Planned Start'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.planned_date',
						'field' => 'SecurityServiceMaintenance.id',
					),
				),
				'start_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Maintenance Start Date'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.start_date',
						'field' => 'SecurityServiceMaintenance.id',
					),
				),
				'end_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Maintenance End Date'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.end_date',
						'field' => 'SecurityServiceMaintenance.id',
					),
				),
				'result' => array(
					'type' => 'select',
					'name' => __('Task Result'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.result',
						'field' => 'SecurityServiceMaintenance.id',
					),
					'data' => array(
						'method' => 'getAuditStatuses',
						'empty' => __('All'),
						// 'result_key' => true,
					),
					'outputFilter' => array('SecurityServiceMaintenances', 'outputResult')
				),
				'task_conclusion' => array(
					'type' => 'text',
					'name' => __('Task Conclusion'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.task_conclusion',
						'field' => 'SecurityServiceMaintenance.id',
					)
				),
				'maintenance_owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityServiceMaintenance', 'MaintenanceOwner', [
					'name' => __('Maintenance Owner')
				])
			),
			__('Mitigation') => array(
				'third_party_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compliance Package Name'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByCompliancePackage',
						'field' => 'SecurityServiceMaintenance.security_service_id',
					),
					// 'field' => 'ThirdParty.name',
					'data' => array(
						'method' => 'getThirdParties',
					),
					'many' => true,
					'field' => 'SecurityService.ComplianceManagement.{n}.CompliancePackageItem.CompliancePackage.ThirdParty.name',
					'containable' => array(
						'SecurityService' => array(
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
					)
				),
				'risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Asset Risks'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityService',
						'findByModel' => 'Risk',
						'field' => 'SecurityServiceMaintenance.security_service_id'
					),
					'data' => array(
						'method' => 'getSecurityServiceRelatedData',
						'findByModel' => 'Risk',
					),
					'field' => 'SecurityService.Risk.{n}.title',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'Risk' => array(
								'fields' => array('id', 'title')
							)
						)
					)
				),
				'third_party_risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Third Party Risks'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityService',
						'findByModel' => 'ThirdPartyRisk',
						'field' => 'SecurityServiceMaintenance.security_service_id'
					),
					'data' => array(
						'method' => 'getSecurityServiceRelatedData',
						'findByModel' => 'ThirdPartyRisk',
					),
					'field' => 'SecurityService.ThirdPartyRisk.{n}.title',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'ThirdPartyRisk' => array(
								'fields' => array('id', 'title')
							)
						)
					)
				),
				'business_continuity_id' => array(
					'type' => 'multiple_select',
					'name' => __('Business Risks'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityService',
						'findByModel' => 'BusinessContinuity',
						'field' => 'SecurityServiceMaintenance.security_service_id'
					),
					'data' => array(
						'method' => 'getSecurityServiceRelatedData',
						'findByModel' => 'BusinessContinuity',
					),
					'field' => 'SecurityService.BusinessContinuity.{n}.title',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'BusinessContinuity' => array(
								'fields' => array('id', 'title')
							)
						)
					)
				),
				'data_asset_id' => array(
					'type' => 'multiple_select',
					'name' => __('Data Asset Flows'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityService',
						'findByModel' => 'DataAsset',
						'field' => 'SecurityServiceMaintenance.security_service_id'
					),
					'data' => array(
						'method' => 'getSecurityServiceRelatedData',
						'findByModel' => 'DataAsset',
					),
					'field' => 'SecurityService.DataAsset.{n}.description',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'DataAsset' => array(
								'fields' => array('id', 'description')
							)
						)
					)
				),
			)
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Security Service Maintenances'),
			'pdf_file_name' => __('security_service_maintenances'),
			'csv_file_name' => __('security_service_maintenances'),
			'view_item' => array(
				'ajax_action' => array(
					'controller' => 'securityServiceMaintenances',
					'action' => 'index'
				)
			),
			'bulk_actions' => true,
			'history' => true,
			'trash' => true,
			'use_new_filters' => true
		);

		parent::__construct($id, $table, $ds);
	}

	public function getObjectStatusConfig() {
        return [
        	'maintenances_all_done' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'maintenances_not_all_done' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'maintenances_last_passed' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'maintenances_last_missing' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
        ];
    }

	/**
     * Get the parent model name, required for InheritanceInterface class.
     */
    public function parentModel() {
        return 'SecurityService';
    }

    public function parentNode() {
    	return $this->visualisationParentNode('security_service_id');
    }

	public function checkEndDate($endDate, $startDate) {
		if (!isset($this->data[$this->name][$startDate])) {
			return true;
		}

		return $this->data[$this->name][$startDate] <= $endDate['end_date'];
	}

	public function beforeDelete($cascade = true) {
		$ret = true;
		if (!empty($this->id)) {
			$Maintenance = $this->getMaintenance();
			if (empty($Maintenance)) {
				return true;
			}

			$this->serviceId = $this->parentNodeId();

			$settings = array(
				'disableToggles' => array('notMissing'),
				'customToggles' => array('MaintenanceDelete'),
				'customValues' => array(
					'failedMaintenanceDateBeforeDelete' => $Maintenance['SecurityServiceMaintenance']['planned_date'],
					'missingMaintenanceDateBeforeDelete' => $Maintenance['SecurityServiceMaintenance']['planned_date']
				)
			);

			$ret &= $this->SecurityService->triggerStatus('maintenancesLastMissing', $this->serviceId, 'before', $settings);
		}

		return $ret;
	}

	public function afterDelete() {
		$ret = true;
		if (isset($this->serviceId)) {
			$settings = array(
				'disableToggles' => array('notMissing'),
				'customToggles' => array('MaintenanceDelete')
			);

			$ret &= $this->SecurityService->triggerStatus('maintenancesLastMissing', $this->serviceId, 'after', $settings);
		}

		return $ret;
	}

	public function beforeSave($options = array()) {
		parent::beforeSave($options);

		$ret = true;

		$ret &= $this->SecurityService->saveMaintenances($this->parentNodeId(), 'before');
		return $ret;
	}

	public function afterSave($created, $options = array()) {
		$ret = $this->SecurityService->saveMaintenances($this->parentNodeId(), 'after');

		if ($created && !empty($this->id) && isset($this->data['SecurityServiceMaintenance']['result'])) {
			$ret &= $this->createMaintenanceDate($this->id);

			$this->triggerObjectStatus();
		}

		return $ret;
	}

	public function setCreateValidation() {
		$this->validate = array_merge($this->validate, $this->createValidate);
	}

	public function createMaintenanceDate($maintenanceId) {
		$maintenance = $this->find('first', [
    		'conditions' => [
    			'SecurityServiceMaintenance.id' => $maintenanceId
			],
			'recursive' => -1
		]);

		if (empty($maintenance)) {
			return false;
		}

		$date = $maintenance['SecurityServiceMaintenance']['planned_date'];
		$data = [
			'security_service_id' => $maintenance['SecurityServiceMaintenance']['security_service_id'],
			'day' => date('d', strtotime($maintenance['SecurityServiceMaintenance']['planned_date'])),
			'month' => date('m', strtotime($maintenance['SecurityServiceMaintenance']['planned_date'])),
		];

		$this->SecurityService->SecurityServiceMaintenanceDate->create();
		return $this->SecurityService->SecurityServiceMaintenanceDate->save($data);
	}

	public function getRecordTitle($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'SecurityServiceMaintenance.id' => $id
			),
			'fields' => array(
				'SecurityServiceMaintenance.planned_date',
				'SecurityService.name'
			),
			'recursive' => 0
		));
		
		return sprintf('%s (%s)', $data['SecurityServiceMaintenance']['planned_date'], $data['SecurityService']['name']);
	}

	private function getMaintenance() {
		$maintenance = $this->find('first', array(
			'conditions' => array(
				'id' => $this->id
			),
			'recursive' => -1
		));

		return $maintenance;
	}

	private function logStatusToService() {
		$record = $this->find('first', array(
			'conditions' => array(
				'id' => $this->id
			),
			'fields' => array('result'),
			'recursive' => -1
		));

		if ($record['SecurityServiceMaintenance']['result'] != $this->data['SecurityServiceMaintenance']['result']) {
			$statuses = getAuditStatuses();
			$this->SecurityService->addNoteToLog(__('Maintenance status changed to %s', $statuses[$this->data['SecurityServiceMaintenance']['result']]));
			$this->SecurityService->setSystemRecord($this->data['SecurityServiceMaintenance']['security_service_id'], 2);
		}
	}

	/**
	 * Get maintenance completion.
	 * @param  int $id   Security Service ID.
	 * @return array     Result.
	 */
	public function getStatuses($id = null) {
		$maintenances = $this->find('count', array(
			'conditions' => array(
				'SecurityServiceMaintenance.security_service_id' => $id,
				'SecurityServiceMaintenance.result' => null,
				'SecurityServiceMaintenance.planned_date <' => date('Y-m-d', strtotime('now'))
			),
			'recursive' => -1
		));

		$all_done = false;
		if (empty($maintenances)) {
			$all_done = true;
		}

		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$maintenances = $this->find('all', array(
			'conditions' => array(
				'SecurityServiceMaintenance.security_service_id' => $id,
				'SecurityServiceMaintenance.planned_date <=' => $today
			),
			'fields' => array('SecurityServiceMaintenance.id', 'SecurityServiceMaintenance.result'),
			'order' => array('SecurityServiceMaintenance.planned_date' => 'DESC'),
			'recursive' => -1,
			'limit' => 1
		));

		$maintenance = $this->find('first', array(
			'conditions' => array(
				'SecurityServiceMaintenance.security_service_id' => $id,
				'SecurityServiceMaintenance.planned_date <=' => $today
			),
			'fields' => array('SecurityServiceMaintenance.id', 'SecurityServiceMaintenance.result'),
			'order' => array('SecurityServiceMaintenance.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$last_passed = false;
		if (empty($maintenance) ||
			(!empty($maintenance) && in_array($maintenance['SecurityServiceMaintenance']['result'], array(1, null)))) {
			$last_passed = true;
		}

		$maintenance = $this->find('first', array(
			'conditions' => array(
				'SecurityServiceMaintenance.security_service_id' => $id,
				'SecurityServiceMaintenance.planned_date <' => $today
			),
			'fields' => array('SecurityServiceMaintenance.id', 'SecurityServiceMaintenance.result', 'SecurityServiceMaintenance.planned_date'),
			'order' => array('SecurityServiceMaintenance.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$lastMissing = false;
		if (!empty($maintenance) && $maintenance['SecurityServiceMaintenance']['result'] == null) {
			$this->SecurityService->lastMaintenanceMissing = $maintenance['SecurityServiceMaintenance']['planned_date'];
			$lastMissing = true;
		}

		return array(
			'maintenances_all_done' => (string) (int) $all_done,
			'maintenances_last_missing' => (string) (int) $lastMissing,
			'maintenances_last_passed' => (string) (int) $last_passed
		);
	}

	public function logMissingMaintenances() {
		$yesterday = CakeTime::format('Y-m-d', CakeTime::fromString('-1 day'));

		$audits = $this->find('all', array(
			'conditions' => array(
				'SecurityServiceMaintenance.planned_date' => $yesterday
			),
			'fields' => array(
				'SecurityServiceMaintenance.id',
				'SecurityServiceMaintenance.result',
				'SecurityServiceMaintenance.planned_date',
				'SecurityServiceMaintenance.security_service_id'
			),
			'order' => array('SecurityServiceMaintenance.planned_date' => 'DESC'),
			'recursive' => -1
		));

		foreach ($audits as $item) {
			$msg = __('Last maintenance missing (%s)', $item['SecurityServiceMaintenance']['planned_date']);

			if ($item['SecurityServiceMaintenance']['result'] == null) {
				$securityServiceId = $item['SecurityServiceMaintenance']['security_service_id'];

				$this->SecurityService->id = $securityServiceId;
				$this->SecurityService->addNoteToLog($msg);
				$this->SecurityService->setSystemRecord($securityServiceId, 2);
			}
		}
	}

	public function getAuditStatuses() {
		return getAuditStatuses();
	}

	public function findByClassifications($data = array()) {
		return $this->SecurityService->findByClassifications($data);
	}

	public function getClassifications() {
		return $this->SecurityService->getClassifications();
	}

	public function getThirdParties() {
		return $this->SecurityService->getThirdParties();
	}

	public function findByCompliancePackage($data = array()) {
		return $this->SecurityService->findByCompliancePackage($data);
	}

	public function findBySecurityService($data = array(), $filterParams = array()) {
		return $this->SecurityService->findByHabtm($data, $filterParams);
	}

	public function getSecurityServiceRelatedData($fieldData = array()) {
		return $this->SecurityService->getFilterRelatedData($fieldData);
	}

	public function getFormattedResult($result) {
		$statuses = getAuditStatuses();

		if (isset($statuses[$result])) {
			return $statuses[$result];
		}

		return false;
	}
}
