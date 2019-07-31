<?php
App::uses('AppAudit', 'Model');
App::uses('InheritanceInterface', 'Model/Interface');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');
App::uses('UserFields', 'UserFields.Lib');

// App::uses('AppModel', 'Model');
class SecurityServiceAudit extends AppAudit implements InheritanceInterface {
	public $displayField = 'planned_date';

	protected $auditParentModel = 'SecurityService';

	public $mapping = array(
		'indexController' => array(
			'basic' => 'securityServiceAudits',
			'advanced' => 'securityServiceAudits',
			'params' => array('security_service_id')
		),
		'titleColumn' => 'planned_date',
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
				'audit_metric_description', 'audit_success_criteria', 'result_description' 
			)
		),
		'CustomFields.CustomFields',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => [
				'AuditOwner',
				'AuditEvidenceOwner'
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
				'Attachment.model' => 'SecurityServiceAudit'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'SecurityServiceAudit'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'SecurityServiceAudit'
			)
		)
	);

	public $hasOne = array(
		'SecurityServiceAuditImprovement'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Security Service Audits');
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
			'audit_metric_description' => array(
				'label' => __('Audit Methodology'),
				'editable' => true,
				'description' => __('The text above is copied from what the control has defined as Audit Methodology. <br>Any modification to the content of this field in this form will be highlighted once you save the audit.')
			),
			'audit_success_criteria' => array(
				'label' => __('Audit Success Criteria'),
				'editable' => true,
				'description' => __('The text above is copied from what the control has defined as Audit Criteria. <br>Any modification to the content of this field in this form will be highlighted once you save the audit.')
			),
			'result' => array(
				'label' => __('Audit Result'),
				'options' => array($this, 'getAuditStatuses'),
				'editable' => true,
				'description' => __('Pass: the security service will be tagged as "Ok" and in "Green" colour<br><br>Fail: The audit will be tagged as "Last Audit Failed" and in "Red" colour. You will be able to assign "Projects" and "Incidents" to this audit as corrective measures.')
			),
			'result_description' => array(
				'label' => __('Audit Conclusion'),
				'editable' => true,
				'description' => __('Describe the conclusion of the audit in detail, what was found, Etc. You can attach your audit conclusion, evidence, Etc to this audit by using the right bar on the "Attachments" tab.')
			),
			'AuditOwner' => $UserFields->getFieldDataEntityData($this, 'AuditOwner', [
				'label' => __('Audit Owner'), 
				'description' => __('This role is typically used to record the individual that lead the audit (testing) process.')
			]),
			'AuditEvidenceOwner' => $UserFields->getFieldDataEntityData($this, 'AuditEvidenceOwner', [
				'label' => __('Audit Evidence Owner'), 
				'description' => __('This role is typically used to record and notify the individual that must provide evidence. Remember you can send regular notifications requesting evidence to this role.')
			]),
			'planned_date' => array(
				'label' => __('Planned Start'),
				'editable' => true,
				'hidden' => true
			),
			'start_date' => array(
				'label' => __('Audit Start Date'),
				'editable' => true,
				'description' => __('Set the date when the audit begun, this might be different from the date it was supposed to start. <br><br> The difference in between the planned and actual start date of an audit is an important metric that can be used to highlight resource deficiences. You may use audit filters to analyse this information in detail.')
			),
			'end_date' => array(
				'label' => __('Audit End Date'),
				'editable' => true,
				'description' => __('Set the date when the audit was completed.<br><br> Recording the day the audit (test) was completed is an important record as it helps to understand how long audits take in total (counting "dead or waiting" times). You may use audit filters to analyse this information in detail.<br><br> If you want to record the "Actual" time an audit has taken, you may use "Custom Fields" and create a spefic field to record this value.')
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
				'SECSERV_OWNER' => $UserFields->getNotificationSystemData('SecurityService.ServiceOwner', [
					'name' => __('Security Service Owner')
				]),
				'SECSERV_AUDITMETRIC' => array(
					'field' => 'SecurityServiceAudit.audit_metric_description',
					'name' => __('Security Service Audit Metric Description')
				),
				'SECSERV_AUDITCRITERIA' => array(
					'field' => 'SecurityServiceAudit.audit_success_criteria',
					'name' => __('Security Service Audit Success Criteria')
				),
				'SECSERV_AUDITRESULT' => array(
					'type' => 'callback',
					'field' => 'SecurityServiceAudit.result',
					'name' => __('Security Service Audit Result'),
					'callback' => array($this, 'getFormattedResult')
				),
				'SECSERV_AUDITCONCLUSION' => array(
					'field' => 'SecurityServiceAudit.result_description',
					'name' => __('Security Service Audit Conclusion')
				),
				'SECSERV_AUDITDATE' => array(
					'field' => 'SecurityServiceAudit.planned_date',
					'name' => __('Security Service Audit Date')
				),
				'SECSERV_AUDITOWNER' => $UserFields->getNotificationSystemData('AuditOwner', [
					'name' => __('Security Service Audit Owner')
				]),
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
						'field' => 'SecurityServiceAudit.security_service_id',
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
				'classifications' => array(
					'type' => 'multiple_select',
					'name' => __('Classification'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByClassifications',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'data' => array(
						'method' => 'getClassifications',
					),
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'Classification' => array(
								'name'
							)
						)
					),
					'field' => 'SecurityService.Classification.{n}.name'
				),
				'audit_metric_description' => array(
					'type' => 'text',
					'name' => __('Audit Methodology'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.audit_metric_description',
						'field' => 'SecurityServiceAudit.id',
					)
				),
				'audit_success_criteria' => array(
					'type' => 'text',
					'name' => __('Audit Success Criteria'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.audit_success_criteria',
						'field' => 'SecurityServiceAudit.id',
					)
				),
				'planned_date' => array(
					'type' => 'date',
					'comparison' => true,
					'show_default' => true,
					'name' => __('Planned Start'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.planned_date',
						'field' => 'SecurityServiceAudit.id',
					),
				),
				'start_date' => array(
					'type' => 'date',
					'comparison' => true,
					'show_default' => true,
					'name' => __('Audit Start Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.start_date',
						'field' => 'SecurityServiceAudit.id',
					),
				),
				'end_date' => array(
					'type' => 'date',
					'comparison' => true,
					'show_default' => true,
					'name' => __('Audit End Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.end_date',
						'field' => 'SecurityServiceAudit.id',
					),
				),
				'result' => array(
					'type' => 'select',
					'name' => __('Audit Result'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.result',
						'field' => 'SecurityServiceAudit.id',
					),
					'data' => array(
						'method' => 'getAuditStatusesAll',
						'empty' => __('All'),
						// 'result_key' => true,
					),
					'outputFilter' => array('SecurityServiceAudits', 'outputResult')
				),
				'result_description' => array(
					'type' => 'text',
					'name' => __('Audit Conclusion'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.result_description',
						'field' => 'SecurityServiceAudit.id',
					)
				),
				'audit_owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityServiceAudit', 'AuditOwner', [
					'name' => __('Audit Owner')
				]),
				'audit_evidence_owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityServiceAudit', 'AuditEvidenceOwner', [
					'name' => __('Audit Evidence Owner')
				])
			),
			__('Mitigation') => array(
				'third_party_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compliance Package Name'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.third_party_id',
						'field' => 'SecurityServiceAudit.security_service_id',
						'path' => [
							'ComplianceManagementsSecurityService' => [
								'findField' => 'ComplianceManagementsSecurityService.compliance_management_id',
								'field' => 'ComplianceManagementsSecurityService.security_service_id',
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
						'field' => 'SecurityServiceAudit.security_service_id'
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
						'field' => 'SecurityServiceAudit.security_service_id'
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
						'field' => 'SecurityServiceAudit.security_service_id'
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
						'field' => 'SecurityServiceAudit.security_service_id'
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
			),
			__('Improvements') => array(
				'project_id' => array(
					'type' => 'multiple_select',
					'name' => __('Projects'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityServiceComplex',
						'findField' => 'Project.id',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'data' => array(
						'method' => 'getProjects',
					),
					'field' => 'SecurityService.Project.{n}.title',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'fields' => array('id'),
							'Project' => array(
								'fields' => array('id', 'title')
							)
						)
					)
				),
				'project_deadline' => array(
					'type' => 'date',
					'name' => __('Project Planned Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityServiceComplex',
						'findField' => 'Project.deadline',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'field' => 'SecurityService.Project.{n}.deadline',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'fields' => array('id'),
							'Project' => array(
								'fields' => array('id', 'deadline')
							)
						)
					)
				),
				'project_owner_id' => $UserFields->getForeignAdvancedFilterFieldData('SecurityServiceAudit', 'Project', 'Owner', [
					'name' => __('Project Owner'),
					'show_default' => false
				], 'SecurityService'),
				'project_project_status_id' => array(
					'type' => 'multiple_select',
					'name' => __('Project Status'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityServiceComplex',
						'findField' => 'Project.project_status_id',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'data' => array(
						'method' => 'getProjectStatuses',
						'result_key' => true
					),
					'field' => 'SecurityService.Project.{n}.project_status_id',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'fields' => array('id'),
							'Project' => array(
								'fields' => array('id', 'project_status_id')
							)
						)
					)
				),
				'security_incident_id' => array(
					'type' => 'multiple_select',
					'name' => __('Security Incidents'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityServiceComplex',
						'findField' => 'SecurityIncident.id',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'data' => array(
						'method' => 'getSecurityIncident',
					),
					'field' => 'SecurityService.SecurityIncident.{n}.title',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'fields' => array('id'),
							'SecurityIncident' => array(
								'fields' => array('id', 'title')
							)
						)
					)
				),
				'security_incident_owner_id' => $UserFields->getForeignAdvancedFilterFieldData('SecurityServiceAudit', 'SecurityIncident', 'Owner', [
					'name' => __('Security Incident Owner'),
					'show_default' => false
				], 'SecurityService'),
				'security_incident_open_date' => array(
					'type' => 'date',
					'name' => __('Security Incident Open Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityServiceComplex',
						'findField' => 'SecurityIncident.open_date',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'field' => 'SecurityService.SecurityIncident.{n}.open_date',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'fields' => array('id'),
							'SecurityIncident' => array(
								'fields' => array('id', 'open_date')
							)
						)
					)
				),
				'security_incident_closure_date' => array(
					'type' => 'date',
					'name' => __('Security Incident Closure Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityServiceComplex',
						'findField' => 'SecurityIncident.closure_date',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'field' => 'SecurityService.SecurityIncident.{n}.closure_date',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'fields' => array('id'),
							'SecurityIncident' => array(
								'fields' => array('id', 'closure_date')
							)
						)
					)
				),
				'security_incident_status_id' => array(
					'type' => 'multiple_select',
					'name' => __('Security Incident Status'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findBySecurityServiceComplex',
						'findField' => 'SecurityIncident.security_incident_status_id',
						'field' => 'SecurityServiceAudit.security_service_id',
					),
					'data' => array(
						'method' => 'getSecurityIncidentStatuses',
						'result_key' => true
					),
					'field' => 'SecurityService.SecurityIncident.{n}.security_incident_status_id',
					'many' => true,
					'containable' => array(
						'SecurityService' => array(
							'fields' => array('id'),
							'SecurityIncident' => array(
								'fields' => array('id', 'security_incident_status_id')
							)
						)
					)
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Security Service Audits'),
			'pdf_file_name' => __('security_service_audits'),
			'csv_file_name' => __('security_service_audits'),
			'view_item' => array(
				'ajax_action' => array(
					'controller' => 'securityServiceAudits',
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
        	'audits_all_done' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'audits_not_all_done' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'audits_last_passed' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'audits_last_not_passed' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'audits_last_missing' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'audits_improvements' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'ongoing_corrective_actions' => [
                'trigger' => [
                    $this->SecurityService,
                ]
            ],
        ];
    }

    public function beforeSave($options = array()) {
		parent::beforeSave($options);

		$ret = true;

		return $ret;
	}

	public function afterSave($created, $options = array()) {
		parent::afterSave($created, $options = array());

		$ret = true;
		
		if ($created && !empty($this->id) && isset($this->data['SecurityServiceAudit']['result'])) {
			$ret &= $this->createAuditDate($this->id);

			$this->triggerObjectStatus();
		}
		
		return $ret;
	}

	public function setCreateValidation() {
		$this->validate = array_merge($this->validate, $this->createValidate);
	}

	/**
     * Get the parent model name, required for InheritanceInterface class.
     */
    public function parentModel() {
        return $this->auditParentModel;
    }

    public function parentNode() {
    	return $this->visualisationParentNode('security_service_id');
    }

    public function createAuditDate($auditId) {
    	$audit = $this->find('first', [
    		'conditions' => [
    			'SecurityServiceAudit.id' => $auditId
			],
			'recursive' => -1
		]);

		if (empty($audit)) {
			return false;
		}

		$date = $audit['SecurityServiceAudit']['planned_date'];
		$data = [
			'security_service_id' => $audit['SecurityServiceAudit']['security_service_id'],
			'day' => date('d', strtotime($audit['SecurityServiceAudit']['planned_date'])),
			'month' => date('m', strtotime($audit['SecurityServiceAudit']['planned_date'])),
		];

		$this->SecurityService->SecurityServiceAuditDate->create();
		return $this->SecurityService->SecurityServiceAuditDate->save($data);
	}

	public function checkEndDate($endDate, $startDate) {
		if (!isset($this->data[$this->name][$startDate])) {
			return true;
		}

		return $this->data[$this->name][$startDate] <= $endDate['end_date'];
	}

	public function getRecordTitle($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'SecurityServiceAudit.id' => $id
			),
			'fields' => array(
				'SecurityServiceAudit.planned_date',
				'SecurityService.name'
			),
			'recursive' => 0
		));
		
		return sprintf('%s (%s)', $data['SecurityServiceAudit']['planned_date'], $data['SecurityService']['name']);
	}

	public function getFormattedResult($result) {
		$statuses = getAuditStatuses();

		if (isset($statuses[$result])) {
			return $statuses[$result];
		}

		return false;
	}

	private function logStatusToService() {
		$record = $this->find('first', array(
			'conditions' => array(
				'id' => $this->id
			),
			'fields' => array('result'),
			'recursive' => -1
		));
		//debug($this->data);

		if ($record['SecurityServiceAudit']['result'] != $this->data['SecurityServiceAudit']['result']) {
			$statuses = getAuditStatuses();
			$this->SecurityService->addNoteToLog(__('Audit status changed to %s', $statuses[$this->data['SecurityServiceAudit']['result']]));
			$this->SecurityService->setSystemRecord($this->data['SecurityServiceAudit']['security_service_id'], 2);
		}
	}

	/**
	 * Get audits completion statuses.
	 * @param  int $id   Security Service ID.
	 */
	public function getStatuses($id) {
		$audits = $this->find('count', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id,
				'SecurityServiceAudit.result' => null,
				'SecurityServiceAudit.planned_date <' => date('Y-m-d', strtotime('now'))
			),
			'recursive' => -1
		));

		$all_done = false;
		if (empty($audits)) {
			$all_done = true;
		}

		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$audit = $this->find('first', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id,
				'SecurityServiceAudit.planned_date <=' => $today
			),
			'fields' => array('SecurityServiceAudit.id', 'SecurityServiceAudit.result', 'SecurityServiceAudit.planned_date'),
			'order' => array('SecurityServiceAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$last_passed = false;
		if (empty($audit) ||
			(!empty($audit) && in_array($audit['SecurityServiceAudit']['result'], array(1, null)))) {
			$last_passed = true;
		}
		elseif (!empty($audit)) {
			$this->SecurityService->lastAuditFailed = $audit['SecurityServiceAudit']['planned_date'];
		}

		$improvements = false;
		$hasProjects = $this->SecurityService->getAssignedProjects($id);
		if ($hasProjects) {
			$improvements = true;
		}
		else {
			$audit = $this->find('first', array(
				'conditions' => array(
					'SecurityServiceAudit.security_service_id' => $id,
					'SecurityServiceAudit.planned_date <=' => $today,
					'SecurityServiceAudit.result' => array(1, 0)
				),
				'fields' => array('SecurityServiceAudit.id', 'SecurityServiceAudit.result', 'SecurityServiceAuditImprovement.id'),
				'order' => array('SecurityServiceAudit.planned_date' => 'DESC'),
				'contain' => array(
					'SecurityServiceAuditImprovement'
				)
			));

			if (isset($audit['SecurityServiceAuditImprovement']['id']) && $audit['SecurityServiceAuditImprovement']['id'] != null) {
				$improvements = true;
			}
		}

		$audit = $this->find('first', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id,
				'SecurityServiceAudit.planned_date <' => $today
			),
			'fields' => array('SecurityServiceAudit.id', 'SecurityServiceAudit.result', 'SecurityServiceAudit.planned_date'),
			'order' => array('SecurityServiceAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$lastMissing = false;
		if (!empty($audit) && $audit['SecurityServiceAudit']['result'] == null) {
			$this->SecurityService->lastAuditMissing = $audit['SecurityServiceAudit']['planned_date'];
			$lastMissing = true;
		}

		$arr = array(
			'audits_all_done' => (string) (int) $all_done,
			'audits_last_missing' => (string) (int) $lastMissing,
			'audits_last_passed' => (string) (int) $last_passed,
			'audits_improvements' => (string) (int) $improvements,
			'audits_status' => $this->auditStatus($id)
		);

		return $arr;
	}

	private function auditStatus($id = null) {
		$data = $this->SecurityService->find('first', array(
			'conditions' => array(
				'SecurityService.id' => $id
			),
			'fields' => array('id', 'security_service_type_id'),
			'recursive' => -1
		));

		if (empty($data)) {
			return 0;
		}

		if ($data['SecurityService']['security_service_type_id'] == SECURITY_SERVICE_RETIRED) {
			return 2;
		}

		if ($data['SecurityService']['security_service_type_id'] != SECURITY_SERVICE_PRODUCTION) {
			return 1;
		}

		return 0;
	}

	public function logMissingAudits() {
		$yesterday = CakeTime::format('Y-m-d', CakeTime::fromString('-1 day'));

		$audits = $this->find('all', array(
			'conditions' => array(
				'SecurityServiceAudit.planned_date' => $yesterday
			),
			'fields' => array(
				'SecurityServiceAudit.id',
				'SecurityServiceAudit.result',
				'SecurityServiceAudit.planned_date',
				'SecurityServiceAudit.security_service_id'
			),
			'order' => array('SecurityServiceAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		foreach ($audits as $item) {
			$msg = __('Last audit missing (%s)', $item['SecurityServiceAudit']['planned_date']);

			if ($item['SecurityServiceAudit']['result'] == null) {
				$securityServiceId = $item['SecurityServiceAudit']['security_service_id'];

				$this->SecurityService->id = $securityServiceId;
				$this->SecurityService->addNoteToLog($msg);
				$this->SecurityService->setSystemRecord($securityServiceId, 2);
			}
		}
	}

	public function getAuditStatuses() {
		return getAuditStatuses();
	}

	public function getAuditStatusesAll() {
		$statuses = am(getAuditStatuses(), [
			AbstractQuery::NULL_VALUE => __('Incomplete')
		]);

		return $statuses;
	}

	public function getProjects() {
		return $this->SecurityService->getProjects();
	}

	public function getProjectStatuses() {
		return $this->SecurityService->Project->getStatuses();
	}

	public function getSecurityIncident() {
		return $this->SecurityService->SecurityIncident->find('list', array(
			'fields' => array('SecurityIncident.id', 'SecurityIncident.title'),
			'order' => array('SecurityIncident.title' => 'ASC')
		));
	}

	public function getSecurityIncidentStatuses() {
		return $this->SecurityService->SecurityIncident->getStatuses();
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

	public function findBySecurityService($data = array(), $filterParams = array()) {
		return $this->SecurityService->findByHabtm($data, $filterParams);
	}

	public function findBySecurityServiceComplex($data = array(), $filterParams = array()) {
		return $this->SecurityService->findComplexType($data, $filterParams);
	}

	public function getSecurityServiceRelatedData($fieldData = array()) {
		return $this->SecurityService->getFilterRelatedData($fieldData);
	}
}
