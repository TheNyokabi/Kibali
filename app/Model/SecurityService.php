<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');
App::uses('AppIndexCrudAction', 'Controller/Crud/Action');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');
App::uses('Hash', 'Utility');
App::uses('UserFields', 'UserFields.Lib');

class SecurityService extends SectionBase {
	public $displayField = 'name';

	public $statusConfig;

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'AuditLog.Auditable' => array(
			'ignore' => array(
				'security_incident_open_count',
				'created',
				'modified',
			)
		),
		'Utils.SoftDelete',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'objective', 'documentation_url', 'security_service_type_id', 'service_classification_id', 'audit_metric_description', 'audit_success_criteria', 'maintenance_metric_description', 'opex', 'capex', 'resource_utilization'
			)
		),
		'CustomFields.CustomFields',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => [
				'ServiceOwner' => [
					'mandatory' => false
				],
				'Collaborator' => [
					'mandatory' => false
				],
				'AuditOwner' => [
					'customRolesInit' => false
				],
				'AuditEvidenceOwner' => [
					'customRolesInit' => false
				],
				'MaintenanceOwner' => [
					'customRolesInit' => false
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
		'documentation_url' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => true
			),
			'urlCustom' => array(
				'rule' => 'urlCustom',
				'allowEmpty' => true,
				'message' => 'Please enter a valid URL'
			)
		),
		'audit_metric_description' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		'audit_success_criteria' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		'maintenance_metric_description' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field is required'
			)
		),
		'opex' => [
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field is required'
			),
			'number' => [
				'rule' => 'numeric',
				'message' => 'This field has to be a number'
			]
		],
		'capex' => [
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field is required'
			),
			'number' => [
				'rule' => 'numeric',
				'message' => 'This field has to be a number'
			]
		],
		'resource_utilization' => [
			'notBlank' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field is required'
			),
			'number' => [
				'rule' => 'numeric',
				'message' => 'This field has to be a number'
			]
		]
	);

	public $belongsTo = array(
		'SecurityServiceType',
		'ServiceClassification'
	);

	public $hasMany = array(
		'SecurityServiceAudit',
		'SecurityServiceAuditDate',
		'SecurityServiceMaintenance',
		'SecurityServiceMaintenanceDate',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'SecurityService'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'SecurityService'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'SecurityService'
			)
		),
		'Classification' => array(
			'className' => 'SecurityServiceClassification'
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'SecurityService'
			)
		),
		'Issue' => array(
			'className' => 'Issue',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Issue.model' => 'SecurityService'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'ServiceContract',
		'SecurityPolicy',
		'Risk',
		'ThirdPartyRisk',
		'BusinessContinuity',
		'SecurityIncident' => array(
			'className' => 'SecurityIncident',
			'with' => 'SecurityIncidentsSecurityService'
		),
		'DataAsset' => array(
			'with' => 'DataAssetsSecurityService'
		),
		'ComplianceManagement',
		'Projects' => array(
			'className' => 'Project',
			'with' => 'ProjectsSecurityServices'
		),
		'Project'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Security Services');
        $this->_group = parent::SECTION_GROUP_CONTROL_CATALOGUE;

		$this->fieldGroupData = array(
            'default' => array(
                'label' => __('General')
            ),
            'audits' => array(
                'label' => __('Audits')
            ),
            'maintenances' => array(
                'label' => __('Maintenances')
            ),
            'status' => array(
                'label' => __('Status')
            ),
        );

        $this->fieldData = array(
            'name' => array(
                'label' => __('Name'),
                'editable' => true
            ),
            'objective' => array(
                'label' => __('Objective'),
                'editable' => true
            ),
            'documentation_url' => array(
                'label' => __('Documentation URL'),
                'editable' => true
            ),
            'security_service_type_id' => array(
                'label' => __('Status'),
                'editable' => true
            ),
            'service_classification_id' => array(
                'label' => __('Classification'),
                'editable' => false,
                'hidden' => true,
            ),
            'Classification' => array(
                'label' => __('Tags'),
                'editable' => true,
                'type' => 'tags',
                'options' => [$this, 'getClassifications'],
            ),
            'ServiceOwner' => $UserFields->getFieldDataEntityData($this, 'ServiceOwner', [
				'label' => __('Service Owner'), 
				'description' => __('You can use this field in any way it fits best your organisation, for example the person responsable or accountable of ensuring this control is correctly operated. Remember you can setup notifications that point to this role to remind them of their responsabilities.')
			]),
			'Collaborator' => $UserFields->getFieldDataEntityData($this, 'Collaborator', [
				'label' => __('Collaborator'), 
				'description' => __('OPTIONAL: You can use this field in any way it fits best your organisation, for example to indicate the people that operates this control in a daily bases.')
			]),
        	'opex' => array(
            	'label' => __('Cost (OPEX)'),
                'editable' => true
        	),
        	'capex' => array(
            	'label' => __('Cost (CAPEX)'),
                'editable' => true
        	),
        	'resource_utilization' => array(
            	'label' => __('Resource Utilization'),
                'editable' => true
        	),
        	'ServiceContract' => array(
        		'label' => __('Support Contracts'),
                'editable' => true
    		),
    		'SecurityPolicy' => array(
        		'label' => __('Security Policy Items'),
                'editable' => true
    		),
    		'AuditOwner' => $UserFields->getFieldDataEntityData($this, 'AuditOwner', [
				'label' => __('Audit Owner'), 
				'description' => __('This role is typically used to record and notify the individual that lead the audit (testing) process. Remember you send send reports of coming and pending audits to this role.')
			]),
			'AuditEvidenceOwner' => $UserFields->getFieldDataEntityData($this, 'AuditEvidenceOwner', [
				'label' => __('Audit Evidence Owner'), 
				'description' => __('This role is typically used to record and notify the individual that must provide evidence. Remember you can send regular notifications requesting evidence to this role.')
			]),
    		'audit_metric_description' => array(
        		'label' => __('Audit Metric Description'),
        		'group' => 'audits',
                'editable' => true
    		),
    		'audit_success_criteria' => array(
        		'label' => __('Audit Success Criteria'),
        		'group' => 'audits',
                'editable' => true
    		),
    		'MaintenanceOwner' => $UserFields->getFieldDataEntityData($this, 'MaintenanceOwner', [
				'label' => __('Maintenance Owner'), 
				'description' => __('This role is typically used to record and notify the individual that must carry on with this maintenance. You can setup notifications asociated with each deadline and reports of pending and completed tasks.')
			]),
    		'maintenance_metric_description' => array(
        		'label' => __('Maintenance Task'),
        		'group' => 'maintenances',
                'editable' => true
    		),
    		'audits_all_done' => array(
    			'label' => __('Audits all done'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'audits_last_missing' => array(
    			'label' => __('Last audit missing'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'audits_last_passed' => array(
    			'label' => __('Last audit failed'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'audits_improvements' => array(
    			'label' => __('Audits Improvements'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'audits_status' => array(
    			'label' => __('Audits status'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'maintenances_all_done' => array(
				'label' => __('Maintances all done'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'maintenances_last_missing' => array(
    			'label' => __('Last maintance missing'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'maintenances_last_passed' => array(
    			'label' => __('Last maintance failed'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'ongoing_security_incident' => array(
    			'label' => __('Ongoing Security Incident'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'control_with_issues' => array(
    			'label' => __('Control with Issues'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'ongoing_corrective_actions' => array(
    			'label' => __('Ongoing Corrective Actions'),
    			'group' => 'status',
				'type' => 'toggle',
				'hidden' => true,
			),
			'SecurityServiceAudit' => array(
    			'label' => __('Security Service Audit'),
				'hidden' => true,
			),
			'SecurityServiceAuditDate' => array(
    			'label' => __('Security Service Audit Date'),
				'hidden' => true,
			),
			'SecurityServiceMaintenance' => array(
    			'label' => __('Security Service Maintenance'),
				'hidden' => true,
			),
			'SecurityServiceMaintenanceDate' => array(
    			'label' => __('Security Service Maintenance Date'),
				'hidden' => true,
			),
			'Issue' => array(
    			'label' => __('Issues'),
				'hidden' => true,
			),
			'Risk' => array(
    			'label' => __('Risks'),
				'hidden' => true,
			),
			'ThirdPartyRisk' => array(
    			'label' => __('Third Party Risks'),
				'hidden' => true,
			),
			'BusinessContinuity' => array(
    			'label' => __('Business Continuities'),
				'hidden' => true,
			),
			'SecurityIncident' => array(
    			'label' => __('Business Continuities'),
				'hidden' => true,
			),
			'DataAsset' => array(
    			'label' => __('Data Asset'),
				'hidden' => true,
			),
			'ComplianceManagement' => array(
    			'label' => __('Compliance Managements'),
				'hidden' => true,
			),
			'Project' => array(
				'label' => __('Projects'),
				'editable' => true,
				'options' => array($this, 'getProjects'),
			)
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
				'SECSERV_OWNER' => $UserFields->getNotificationSystemData('ServiceOwner', [
					'name' => __('Security Service Owner')
				]),
				'SECSERV_COLLABORATOR' => $UserFields->getNotificationSystemData('Collaborator', [
					'name' => __('Security Service Collaborators')
				]),
				'SECSERV_AUDITMETRIC' => array(
					'field' => 'SecurityService.audit_metric_description',
					'name' => __('Security Service Metric Description')
				),
				'SECSERV_AUDITCRITERIA' => array(
					'field' => 'SecurityService.audit_success_criteria',
					'name' => __('Security Service Success Criteria')
				),
			),
			'customEmail' =>  true
		);

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'show_default' => true,
					'filter' => false
				),
				'name' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.name',
						'field' => 'SecurityService.id',
					)
				),
				'objective' => array(
					'type' => 'text',
					'name' => __('Objective'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.objective',
						'field' => 'SecurityService.id',
					)
				),
				'documentation_url' => array(
					'type' => 'text',
					'name' => __('Documentation URL'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.documentation_url',
						'field' => 'SecurityService.id',
					)
				),
				'security_service_type_id' => array(
					'type' => 'multiple_select',
					'name' => __('Status'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceType.id',
						'field' => 'SecurityService.security_service_type_id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'SecurityServiceType',
					),
					'contain' => array(
						'SecurityServiceType' => array(
							'name'
						)
					),
				),
				'service_owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityService', 'ServiceOwner', [
					'name' => __('Service Owner'),
				]),
				'collaborator_id' => $UserFields->getAdvancedFilterFieldData('SecurityService', 'Collaborator', [
					'name' => __('Collaborator'),
				]),
				'classifications' => array(
					'type' => 'multiple_select',
					'name' => __('Tags'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Classification.name',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getClassifications',
					),
					'many' => true,
					'contain' => array(
						'Classification' => array(
							'name'
						)
					),
				),
				'project_id' => array(
					'type' => 'multiple_select',
					'name' => __('Projects'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.id',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getProjects',
					),
					'many' => true,
					'contain' => array(
						'Project' => array(
							'title'
						)
					),
				),
				'security_policy_id' => array(
					'type' => 'multiple_select',
					'name' => __('Security Policy Items'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityPolicy.id',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'SecurityPolicy',
					),
					'many' => true,
					'contain' => array(
						'SecurityPolicy' => array(
							'index'
						)
					),
				),
				'service_contract_id' => array(
					'type' => 'multiple_select',
					'name' => __('Support Contracts'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ServiceContract.id',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'ServiceContract',
					),
					'many' => true,
					'contain' => array(
						'ServiceContract' => array(
							'name'
						)
					),
				),
			),
			__('Cost') => array(
				'opex' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Opex'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.opex',
						'field' => 'SecurityService.id',
					),
				),
				'capex' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Capex'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.capex',
						'field' => 'SecurityService.id',
					),
				),
				'resource_utilization' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Resource Utilization'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.resource_utilization',
						'field' => 'SecurityService.id',
					),
				),
			),
			__('Audit') => array(
				'security_service_audit_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Service Audit Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceAudit.planned_date',
						'field' => 'SecurityService.id',
					),
					'many' => true,
					'contain' => array(
						'SecurityServiceAudit' => array(
							'planned_date'
						)
					)
				),
				'audit_metric_description' => array(
					'type' => 'text',
					'name' => __('Audit Methodology'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.audit_metric_description',
						'field' => 'SecurityService.id',
					),
				),
				'audit_success_criteria' => array(
					'type' => 'text',
					'name' => __('Audit Success Criteria'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.audit_success_criteria',
						'field' => 'SecurityService.id',
					),
				),
				'audit_owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityService', 'AuditOwner', [
					'name' => __('Audit Owner'),
					'show_default' => false
				]),
				'audit_evidence_owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityService', 'AuditEvidenceOwner', [
					'name' => __('Audit Evidence Owner'),
					'show_default' => false
				])
			),
			__('Maintenance') => array(
				'security_service_maintance_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Maintance Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.planned_date',
						'field' => 'SecurityService.id',
					),
					'many' => true,
					'contain' => array(
						'SecurityServiceMaintenance' => array(
							'planned_date'
						)
					)
				),
				'maintenance_task' => array(
					'type' => 'text',
					'name' => __('Maintenance Task'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityServiceMaintenance.task',
						'field' => 'SecurityService.id',
					),
					'many' => true,
					'contain' => array(
						'SecurityServiceMaintenance' => array(
							'task'
						)
					),
				),
				'maintenance_owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityService', 'MaintenanceOwner', [
					'name' => __('Maintenance Owner'),
					'show_default' => false
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
						'field' => 'SecurityService.id',
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
						'field' => 'SecurityService.id',
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
						'field' => 'SecurityService.id',
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
				'compliance_package_item_description' => array(
					'type' => 'text',
					'name' => __('Item Description'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.description',
						'field' => 'SecurityService.id',
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
								'findField' => 'CompliancePackageItem.description',
								'field' => 'CompliancePackageItem.id',
							],
						]
					),
					'many' => true,
					'field' => 'ComplianceManagement.{n}.CompliancePackageItem.description',
					'containable' => array(
						'ComplianceManagement' => array(
							'fields' => array('id', 'compliance_package_item_id'),
							//contain data in ComplianceManagement::attachCompliancePackageData()
						)
					)
				),
				'risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Asset Risks'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Risk.id',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'Risk',
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
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ThirdPartyRisk.id',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'ThirdPartyRisk',
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
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'BusinessContinuity.id',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'BusinessContinuity',
					),
					'many' => true,
					'contain' => array(
						'BusinessContinuity' => array(
							'title'
						)
					),
				),
				'data_asset_id' => array(
					'type' => 'multiple_select',
					'name' => __('Data Asset Flows'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'DataAsset.id',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getFilterRelatedData',
						'findByModel' => 'DataAsset',
					),
					'many' => true,
					'contain' => array(
						'DataAsset' => array(
							'title'
						)
					),
				),
			),
			__('Status') => array(
				'audits_last_passed' => array(
					'type' => 'select',
					'name' => __('Last audit failed'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.audits_last_passed',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOptionInverted',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'audits_last_missing' => array(
					'type' => 'select',
					'name' => __('Last audit missing'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.audits_last_missing',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'maintenances_last_missing' => array(
					'type' => 'select',
					'name' => __('Last maintenance missing'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.maintenances_last_missing',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'ongoing_corrective_actions' => array(
					'type' => 'select',
					'name' => __('Ongoing Corrective Actions'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.ongoing_corrective_actions',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'control_with_issues' => array(
					'type' => 'select',
					'name' => __('Control with Issues'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.control_with_issues',
						'field' => 'SecurityService.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
			),
			__('Related Status') => array(
				'security_incident_ongoing_incident' => array(
					'type' => 'select',
					'name' => __('Ongoing Incident'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'SecurityService.id',
						'status' => array(
							'model' => 'SecurityIncident',
							'field' => 'ongoing_incident'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
					'containable' => array(
						'SecurityIncident' => array(
							'fields' => array('ongoing_incident', 'security_incident_status_id')
						)
					)
				),
				'project_expired' => array(
					'type' => 'select',
					'name' => __('Improvement Project Expired'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'SecurityService.id',
						'status' => array(
							'model' => 'Project',
							'field' => 'expired'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
					'containable' => array(
						'Project' => array(
							'fields' => array('expired')
						)
					)
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Security Services'),
			'pdf_file_name' => __('security_services'),
			'csv_file_name' => __('security_services'),
			'additional_actions' => array(
				'SecurityServiceAudit' => __('Audits'),
				'SecurityServiceMaintenance' => __('Maintenances'),
			),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
            'view_item' => AppIndexCrudAction::VIEW_ITEM_QUERY,
            'use_new_filters' => true,
            'add' => true
		);

		parent::__construct($id, $table, $ds);

		$this->statusConfig = array(
			'audits_last_missing' => array(
				'name' => __('Last audit missing'),
				'value' => 1,
				'type' => 'dangeren',
				// 'column' => 'audits_last_missing'
			)
		);

		$migrateStatusRecords = array(
			'Risk',
			'ThirdPartyRisk',
			'BusinessContinuity',
			'DataAsset',
			'ComplianceManagement'
		);

		$this->mapping['statusManager'] = array(
			'auditsLastFailed' => $this->getStatusTemplate('auditsLastFailed', array(
				'migrateRecords' => $migrateStatusRecords
			)),
			'auditsLastMissing' => $this->getStatusTemplate('auditsLastMissing', array(
				'migrateRecords' => $migrateStatusRecords
			)),
			'maintenancesLastMissing' => array(
				'column' => 'maintenances_last_missing',
				'fn' => array('statusProcess', 'maintenances_last_missing'),
				'migrateRecords' => $migrateStatusRecords,
				'customValues' => array(
					'before' => array(
						'lastMissingMaintenance' => array('lastMissingMaintenance')
					)
				),
				'toggles' => array(
					'missing' => array(
						'value' => 1,
						'message' => __('The Security Service %s has a missing Maintenance %s'),
						'messageArgs' => array(
							0 => '%Current%.name',
							1 => array(
								'type' => 'fn',
								'fn' => array('lastMissingMaintenance'),
							)
						)
					),
					'notMissing' => array(
						'value' => 0,
						'message' => __('The Maintenance planned for the date %s on the Security Service %s has been set to %s'),
						'messageArgs' => array(
							0 => array(
								'type' => 'custom',
								'lastMissingMaintenance',
							),
							1 => '%Current%.name',
							2 => array(
								'type' => 'fn',
								'fn' => array('lastMissingMaintenanceResult')
							)
						)
					)
				),
				'custom' => array(
					'toggles' => array(
						'MaintenanceDelete' => array(
							'value' => 0,
							'message' => __('The Maintenance for the date %s that has been missing on the Security Service %s has been deleted'),
							'messageArgs' => array(
								0 => array(
									'type' => 'custom',
									'missingMaintenanceDateBeforeDelete'
								),
								1 => '%Current%.name'
							)
						),
					)
				)
			),
			'ongoingCorrectiveActions' => $this->getStatusConfig('ongoingCorrectiveActions', 'name', $migrateStatusRecords),
			'status' => $this->getStatusConfig('SecurityServiceStatus', 'name', $migrateStatusRecords),
			'issues' => array(
				'column' => 'control_with_issues',
				'fn' => 'statusHasIssues',
				'migrateRecords' => $migrateStatusRecords,
				'toggles' => array(
					array(
						'value' => 1,
						'message' => __('The Security Service %s has been tagged as having Issues'),
						'messageArgs' => array(
							0 => '%Current%.name'
						)
					),
					array(
						'value' => 0,
						'message' => __('The Security Service %s has been tagged as no longer having Issues'),
						'messageArgs' => array(
							0 => '%Current%.name'
						)
					)
				)
			),
		);

		$this->importArgs = array(
			'SecurityService.name' => array(
				'name' => __('Name'),
				'headerTooltip' => __('This field is mandatory')
			),
			'SecurityService.objective' => array(
				'name' => __('Objective'),
				'headerTooltip' => __('This field is mandatory')
			),
			'SecurityService.documentation_url' => array(
				'name' => __('Documentation URL'),
				'headerTooltip' => __('Optional, you can leave this field blank')
			),
			'SecurityService.security_service_type_id' => array(
				'name' => __('Status'),
				'model' => 'SecurityServiceType',
				'headerTooltip' => __(
					'Mandatory, can be one of the following numbers: %s',
					ImportToolModule::formatList(
						$this->SecurityServiceType->find('list', ['recursive' => -1])
					)
				)
			),
			'SecurityService.Project' => array(
				'name' => __('Projects'),
				'model' => 'Project',
				'headerTooltip' => __('Optional and accepts multiple IDs separated by "|". You need to enter the ID of a project, you can find them at Security Operations / Project Management')
			),
			'SecurityService.Classification' => array(
				'name' => __('Tags'),
				'model' => 'Classification',
				'callback' => array(
					'beforeImport' => array($this, 'convertClassificationsImport')
				),
				'headerTooltip' => __('Optional and accepts multiple values separated by "|". For example "Critical|SOX|PCI"')
			),
			'SecurityService.ServiceOwner' => $UserFields->getImportArgsFieldData('ServiceOwner', [
				'name' => __('Service Owner')
			], true),
			'SecurityService.Collaborator' => $UserFields->getImportArgsFieldData('Collaborator', [
				'name' => __('Collaborator')
			], true),
			'SecurityService.opex' => array(
				'name' => __('OPEX'),
				'headerTooltip' => __('Mandatory, it requires a numerical value')
			),
			'SecurityService.capex' => array(
				'name' => __('CAPEX'),
				'headerTooltip' => __('Mandatory, it requires a numerical value')
			),
			'SecurityService.resource_utilization' => array(
				'name' => __('Resource Utilization'),
				'headerTooltip' => __('Mandatory, it requires a numerical value')
			),
			'SecurityService.SecurityPolicy' => array(
				'name' => __('Security Policies'),
				'model' => 'SecurityPolicy',
				'headerTooltip' => __('Optional and accepts multiple IDs separated by "|". For example "1|2|3". You can get the ID of a policy from Control Catalogue / Policy Management')
			),
			'SecurityService.AuditOwner' => $UserFields->getImportArgsFieldData('AuditOwner', [
				'name' => __('Audit Owner'),
				'headerTooltip' => __('This role is typically used to record the individual that lead the audit (testing) process.')
			]),
			'SecurityService.AuditEvidenceOwner' => $UserFields->getImportArgsFieldData('AuditEvidenceOwner', [
				'name' => __('Audit Evidence Owner'),
				'headerToolTip' => __('This role is typically used to record and notify the individual that must provide evidence. Remember you can send regular notifications requesting evidence to this role.')
			]),
			'SecurityService.audit_metric_description' => array(
				'name' => __('Audit Metric'),
				'headerTooltip' => __('Mandatory, you need to insert some text or NA if you are not interested in this feature')
			),
			'SecurityService.audit_success_criteria' => array(
				'name' => __('Audit Criteria'),
				'headerTooltip' => __('Mandatory, you need to insert some text or NA if you are not interested in this feature')
			),
			'SecurityServiceAuditDate' => array(
				'name' => __('Audit Date'),
				'model' => 'SecurityServiceAuditDate',
				'callback' => array(
					'beforeImport' => array($this, 'convertAuditDateImport'),
					'beforeExport' => array($this, 'convertAuditDateExport')
				),
				'headerTooltip' => __('Optional, you can insert one date with the format DD-MM. Bare in mind the delimiter is a "-"')
			),
			'SecurityService.MaintenanceOwner' => $UserFields->getImportArgsFieldData('MaintenanceOwner', [
				'name' => __('Maintenance Owner'),
				'headerToolTip' => __('Who executed the task?')
			]),
			'SecurityService.maintenance_metric_description' => array(
				'name' => __('Maintenance Task'),
				'headerTooltip' => __('Mandatory - you can set NA if you wont want to use this feature')
			),
			'SecurityServiceMaintenanceDate' => array(
				'name' => __('Maintenance Date'),
				'model' => 'SecurityServiceMaintenanceDate',
				'callback' => array(
					'beforeImport' => array($this, 'convertAuditDateImport'),
					'beforeExport' => array($this, 'convertMaintenanceDateExport')
				),
				'headerHint' => 'Day-Month',
				'headerTooltip' => __('Optional, you can insert one date with the format DD-MM. Bare in mind the delimiter is a "-"')
			),
		);
	}

	public function getObjectStatusConfig() {
        return [
        	'audits_all_done' => [
                'title' => __('Audits all done'),
                'callback' => [$this, 'statusAuditsAllDone'],
            ],
            'audits_not_all_done' => [//issue
                'title' => __('Audits not all done'),
                'callback' => [$this, 'statusAuditsNotAllDone'],
            ],
            'audits_last_passed' => [
                'title' => __('Last audit failed'),
                'callback' => [$this, 'statusAuditsLastPassed'],
                'type' => 'danger',
                'trigger' => [
                    $this->Risk,
                    $this->ThirdPartyRisk,
                    $this->BusinessContinuity,
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.security_service_audits_last_passed'
                    ],
                ]
            ],
            'audits_last_not_passed' => [
                'title' => __('Last audit not failed'),
                'callback' => [$this, 'statusAuditsLastNotPassed'],
                'type' => 'danger',
                'trigger' => [
                    [
                        'model' => $this->DataAsset,
                        'trigger' => 'ObjectStatus.trigger.controls_with_failed_audits'
                    ],
                ],
                'storageSelf' => false,
            ],
            'audits_last_missing' => [//issue
                'title' => __('Last audit missing'),
                'callback' => [$this, 'statusAuditsLastMissing'],
                'trigger' => [
                    [
                        'model' => $this->DataAsset,
                        'trigger' => 'ObjectStatus.trigger.controls_with_missing_audits'
                    ],
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.security_service_audits_last_missing'
                    ],
                    $this->Risk,
                    $this->ThirdPartyRisk,
                    $this->BusinessContinuity,
                ]
            ],
            'audits_improvements' => [
            	'title' => __('Being fixed'),
                'callback' => [$this, 'statusAuditsImprovements'],
            ],
            'maintenances_all_done' => [
            	'title' => __('Maintenances all done'),
                'callback' => [$this, 'statusMaintenancesAllDone'],
            ],
            'maintenances_not_all_done' => [//issue
            	'title' => __('Maintenances not all done'),
                'callback' => [$this, 'statusMaintenancesNotAllDone'],
            ],
            'maintenances_last_passed' => [
            	'title' => __('Last maintenance failed'),
                'callback' => [$this, 'statusMaintenancesLastPassed'],
                'type' => 'danger'
            ],
            'maintenances_last_missing' => [//issue
                'title' => __('Last maintenance missing'),
                'callback' => [$this, 'statusMaintenancesLastMissing'],
                'trigger' => [
                    $this->Risk,
                    $this->ThirdPartyRisk,
                    $this->BusinessContinuity,
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.security_service_maintenances_last_missing'
                    ],
                ]
            ],
            'ongoing_corrective_actions' => [//issue
                'title' => __('Ongoing Corrective Actions'),
                'callback' => [$this, '_statusOngoingCorrectiveActions'],
                'type' => 'improvement',
                'trigger' => [
                    $this->Risk,
                    $this->ThirdPartyRisk,
                    $this->BusinessContinuity,
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.security_service_ongoing_corrective_actions'
                    ],
                ]
            ],
            'control_with_issues' => [
                'title' => __('Control with Issues'),
                'callback' => [$this, 'statusControlWithIssues'],
                'type' => 'danger',
                'trigger' => [
                    [
                        'model' => $this->DataAsset,
                        'trigger' => 'ObjectStatus.trigger.controls_with_issues'
                    ],
                    $this->Risk,
                    $this->ThirdPartyRisk,
                    $this->BusinessContinuity,
                    [
                        'model' => $this->Risk,
                        'trigger' => 'ObjectStatus.trigger.controls_issues'
                    ],
                    [
                        'model' => $this->ThirdPartyRisk,
                        'trigger' => 'ObjectStatus.trigger.controls_issues'
                    ],
                    [
                        'model' => $this->BusinessContinuity,
                        'trigger' => 'ObjectStatus.trigger.controls_issues'
                    ],
                    [
                    	'model' => $this->ComplianceManagement,
                    	'trigger' => 'security_service_control_with_issues'
                    ],
                ]
            ],
            'control_in_design' => [
                'title' => __('Control in Design'),
                'callback' => [$this, 'statusControlInDesign'],
                'storageSelf' => false,
                'trigger' => [
                    $this->Risk,
                    $this->ThirdPartyRisk,
                    $this->BusinessContinuity,
                ]
            ],
            'ongoing_incident' => [
            	'title' => __('Ongoing Incident'),
                'inherited' => [
                	'SecurityIncident' => 'ongoing_incident'
            	],
            	'storageSelf' => false
            ],
            'project_expired' => [
            	'title' => __('Project Expired'),
                'inherited' => [
                	'Project' => 'expired'
            	],
            	'storageSelf' => false
            ],
        ];
    }

    public function statusAuditsAllDone() {
    	$data = $this->SecurityServiceAudit->find('count', [
			'conditions' => [
				'SecurityServiceAudit.security_service_id' => $this->id,
				'SecurityServiceAudit.result IS NULL',
				'SecurityServiceAudit.planned_date < DATE(NOW())'
			],
			'recursive' => -1
		]);

    	return empty($data);
    }

    public function statusAuditsNotAllDone() {
    	return !$this->statusAuditsAllDone();
    }

    public function statusAuditsLastPassed() {
		$data = $this->SecurityServiceAudit->find('first', [
			'conditions' => [
				'SecurityServiceAudit.security_service_id' => $this->id,
				'SecurityServiceAudit.planned_date < DATE(NOW())',
			],
			'fields' => [
				'SecurityServiceAudit.result'
			],
			'order' => [
				'SecurityServiceAudit.planned_date' => 'DESC'
			],
			'recursive' => -1
		]);

		if (empty($data) || in_array($data['SecurityServiceAudit']['result'], [AUDIT_PASSED, null])) {
			return true;
		}

    	return false;
    }

    public function statusAuditsLastNotPassed() {
    	return !$this->statusAuditsLastPassed();
    }

    public function statusAuditsLastMissing() {
		$data = $this->SecurityServiceAudit->find('first', [
			'conditions' => [
				'SecurityServiceAudit.security_service_id' => $this->id,
				'SecurityServiceAudit.planned_date < DATE(NOW())',
			],
			'fields' => [
				'SecurityServiceAudit.result'
			],
			'order' => [
				'SecurityServiceAudit.planned_date' => 'DESC'
			],
			'recursive' => -1
		]);

    	return (!empty($data) && $data['SecurityServiceAudit']['result'] === null);
    }

    public function statusAuditsImprovements() {
    	$data = $this->ProjectsSecurityServices->find('count', [
			'conditions' => [
				'ProjectsSecurityServices.security_service_id' => $this->id
			],
			'recursive' => -1
		]);

		if (!empty($data)) {
			return true;
		}

		$data = $this->SecurityServiceAudit->find('count', [
			'conditions' => [
				'SecurityServiceAudit.security_service_id' => $this->id,
				'SecurityServiceAudit.planned_date <= DATE(NOW())',
				'SecurityServiceAudit.result IS NOT NULL'
			],
			'fields' => '*',
			'order' => [
				'SecurityServiceAudit.planned_date' => 'DESC'
			],
			'joins' => [
                [
                    'table' => 'security_service_audit_improvements',
                    'alias' => 'SecurityServiceAuditImprovement',
                    'type' => 'INNER',
                    'conditions' => [
                        'SecurityServiceAuditImprovement.security_service_audit_id = SecurityServiceAudit.id',
                    ]
                ],
            ],
			'recursive' => -1
		]);

		if (!empty($data)) {
			return true;
		}

    	return false;
    }

    public function statusMaintenancesAllDone() {
    	$data = $this->SecurityServiceMaintenance->find('count', [
			'conditions' => [
				'SecurityServiceMaintenance.security_service_id' => $this->id,
				'SecurityServiceMaintenance.result IS NULL',
				'SecurityServiceMaintenance.planned_date < DATE(NOW())'
			],
			'recursive' => -1
		]);

    	return empty($data);
    }

    public function statusMaintenancesNotAllDone() {
    	return !$this->statusMaintenancesAllDone();
    }

    public function statusMaintenancesLastPassed() {
    	$data = $this->SecurityServiceMaintenance->find('first', [
			'conditions' => [
				'SecurityServiceMaintenance.security_service_id' => $this->id,
				'SecurityServiceMaintenance.planned_date < DATE(NOW())',
			],
			'fields' => [
				'SecurityServiceMaintenance.result'
			],
			'order' => [
				'SecurityServiceMaintenance.planned_date' => 'DESC'
			],
			'recursive' => -1
		]);

		if (empty($data) || in_array($data['SecurityServiceMaintenance']['result'], [AUDIT_PASSED, null])) {
			return true;
		}

    	return false;
    }

    public function statusMaintenancesLastMissing() {
    	$data = $this->SecurityServiceMaintenance->find('first', [
			'conditions' => [
				'SecurityServiceMaintenance.security_service_id' => $this->id,
				'SecurityServiceMaintenance.planned_date < DATE(NOW())',
			],
			'fields' => [
				'SecurityServiceMaintenance.result'
			],
			'order' => [
				'SecurityServiceMaintenance.planned_date' => 'DESC'
			],
			'recursive' => -1
		]);

    	return (!empty($data) && $data['SecurityServiceMaintenance']['result'] === null);
    }

    public function _statusOngoingCorrectiveActions() {
		// check projects by status
		$data = $this->Project->find('all', [
			'conditions' => [
				'ProjectsSecurityService.security_service_id' => $this->id,
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
			],
			'joins' => [
                [
                    'table' => 'projects_security_services',
                    'alias' => 'ProjectsSecurityService',
                    'type' => 'INNER',
                    'conditions' => [
                        'ProjectsSecurityService.project_id = Project.id'
                    ]
                ],
            ],
			'recursive' => -1
		]);

		if (!empty($data)) {
			return true;
		}

		// or also check projects associated to audit improvements by status
		$Improvement = $this->SecurityServiceAudit->SecurityServiceAuditImprovement;
		$data = $Improvement->ProjectsSecurityServiceAuditImprovement->find('count', [
			'conditions' => [
				'SecurityServiceAudit.security_service_id' => $this->id,
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
			],
			'joins' => $Improvement->getRelatedJoins(),
			'recursive' => -1
		]);

		if (!empty($data)) {
			return true;
		}

    	return false;
    }

    public function statusControlWithIssues() {
    	$data = $this->Issue->find('count', [
			'conditions' => [
				'Issue.model' => 'SecurityService',
				'Issue.foreign_key' => $this->id,
				'Issue.status' => ISSUE_OPEN
			],
			'recursive' => -1
		]);

    	return (boolean) $data;
    }

    public function statusControlInDesign() {
    	$data = $this->find('count', [
			'conditions' => [
				'SecurityService.id' => $this->id,
				'SecurityService.security_service_type_id' => SECURITY_SERVICE_DESIGN,
			],
			'recursive' => -1
		]);

    	return (boolean) $data;
    }

	public function beforeValidate($options = array()) {
		$ret = true;

		// audits and mantenances date validation
		$dateFields = array('SecurityServiceAuditDate', 'SecurityServiceMaintenanceDate');
		foreach ($dateFields as $field) {
			if (!empty($this->data[$field])) {
				foreach ($this->data[$field] as $key => $date) {
					$formattedDate = sprintf('%s-%s-%s', $date['day'], $date['month'], date('Y'));
					$ret &= $valid = Validation::date($formattedDate, array('dmy'));

					if (empty($valid)) {
						$this->invalidate($field . '_' . $key, __('This date is not valid.'));
						$this->invalidate($field, __('One or more dates are not valid.'));
					}
				}
			}
		}
		
		if (isset($this->data['SecurityService']['SecurityPolicy'])) {
			$this->invalidateRelatedNotExist('SecurityPolicy', 'SecurityPolicy', $this->data['SecurityService']['SecurityPolicy']);
		}

		if (isset($this->data['SecurityService']['Project'])) {
			$this->invalidateRelatedNotExist('Project', 'Project', $this->data['SecurityService']['Project']);
		}

		if (isset($this->data['SecurityService']['security_service_type_id'])) {
			$this->invalidateRelatedNotExist('SecurityServiceType', 'security_service_type_id', $this->data['SecurityService']['security_service_type_id']);
		}

		return $ret;
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['SecurityService']['security_service_type_id'])
			&& $this->data['SecurityService']['security_service_type_id'] == SECURITY_SERVICE_DESIGN
		) {
			$this->setEmptyPoductionJoins();
		}
				
		// transforms the data array to save the HABTM relation
    	$this->transformDataToHabtm(array('ServiceContract', 'SecurityPolicy', 'Project', 'SecurityIncident', 'DataAsset', 'ComplianceManagement'
		));

		//$this->disableDesignValidation();
		$this->updateAuditMaintenanceMetricAndCriteriaFields();

		// if (!empty($this->id)) {
		// 	$this->beforeItemSave($this->id);
		// }

		return true;
	}

	public function afterSave($created, $options = array()) {
		$ret = true;
		if (!empty($this->id)) {
			// $this->Risk->pushStatusRecords();
			// $ret = $this->Risk->saveCustomStatuses($this->getSecurityServiceRisks($this->id));
			// $this->Risk->holdStatusRecords();

			// $this->ThirdPartyRisk->pushStatusRecords();
			// $ret &= $this->ThirdPartyRisk->saveCustomStatuses($this->getSecurityServiceTpRisks($this->id));
			// $this->ThirdPartyRisk->holdStatusRecords();

			// $this->BusinessContinuity->pushStatusRecords();
			// $ret &= $this->BusinessContinuity->saveCustomStatuses($this->getSecurityServiceBusinessRisks($this->id));
			// $this->BusinessContinuity->holdStatusRecords();
			
			if (isset($this->data['SecurityService']['Classification'])) {
				$this->Classification->deleteAll(array(
					'Classification.security_service_id' => $this->id
				));

				if (!empty($this->data['SecurityService']['Classification'])) {
					$this->saveClassifications($this->data['SecurityService']['Classification'], $this->id);
				}
			}

			if (isset($this->data['SecurityServiceAuditDate'])) {
				// $this->saveAuditDates($this->data['SecurityServiceAuditDate'], $this->id);
				$this->SecurityServiceAuditDate->deleteAll(array(
					'SecurityServiceAuditDate.security_service_id' => $this->id
				));
				if (!empty($this->data['SecurityServiceAuditDate'])) {
					$this->saveAuditsJoins($this->data['SecurityServiceAuditDate'], $this->id);
				
				}
			}
			
			if (isset( $this->data['SecurityServiceMaintenanceDate'])) {
				// 	$this->saveMaintenanceDates($this->data['SecurityService']['maintenance_calendar'], $this->id);
				$this->SecurityServiceMaintenanceDate->deleteAll(array(
					'SecurityServiceMaintenanceDate.security_service_id' => $this->id
				));
				if (!empty($this->data['SecurityServiceMaintenanceDate'])) {
					$this->saveMaintenancesJoins($this->data['SecurityServiceMaintenanceDate'], $this->id);
				}
			}

			$this->resaveNotifications($this->id);

            // when deleted, move to trash also it's associated audits and maintenances
	        if (!$this->exists($this->id)) {
	        	$ret &= $this->_relatedObjectsRemoval('SecurityServiceAudit', $this->id);
	        	$ret &= $this->_relatedObjectsRemoval('SecurityServiceMaintenance', $this->id);
	        }
		}

		//$ret &= $this->logMappedProjects();

		
		// $ret &= $this->resaveNotifications($this->id);

		// if (!empty($this->id)) {
		// 	$this->afterItemSave($this->id);
		// }

		return $ret;
	}

	protected function _relatedObjectsRemoval($relatedModel, $id) {
		$related = $this->{$relatedModel}->find('list', [
    		'conditions' => [
    			'security_service_id' => $this->id
    		],
    		'recursive' => -1
    	]);

		$ret = true;
    	foreach (array_keys($related) as $id) {
    		$ret &= $this->{$relatedModel}->delete($id);
    	}

    	return $ret;
	}

	/**
	 * If a control status is set to design - delete all joins.
	 */
	public function setEmptyPoductionJoins() {
		$this->data['SecurityService']['SecurityIncident'] = array();
		$this->data['SecurityService']['DataAsset'] = array();
		$this->data['SecurityService']['ComplianceManagement'] = array();
	}

	/**
	 * @deprecated
	 */
	public function saveJoins($data = null) {
		$this->data = $data;

		$ret = true;

		$ret &= $this->joinHabtm('ServiceContract', 'service_contract_id');
		$ret &= $this->joinHabtm('SecurityPolicy', 'security_policy_id');
		$ret &= $this->joinHabtm('Project', 'project_id');
		//$ret &= $this->joinHabtm('Collaborators', 'collaborator_id');

		if (!empty($this->data['SecurityService']['Classification'])) {
			$ret &= $this->saveClassifications($this->data['SecurityService']['Classification'], $this->id);
		}

		// if ( isset( $this->data['SecurityService']['audit_calendar'] ) && ! empty( $this->data['SecurityService']['audit_calendar'] ) ) {
		// 	$ret &= $this->saveAuditDates( $this->data['SecurityService']['audit_calendar'], $this->id );
		// 	$ret &= $this->saveAuditsJoins( $this->data['SecurityService']['audit_calendar'], $this->id );

		// 	//temporarily reassign data because it gets lost during audit joins
		// 	$this->data = $data;
		// }

		// if ( isset( $this->data['SecurityService']['maintenance_calendar'] ) && ! empty( $this->data['SecurityService']['maintenance_calendar'] ) ) {
		// 	$ret &= $this->saveMaintenanceDates( $this->data['SecurityService']['maintenance_calendar'], $this->id );
		// 	$ret &= $this->saveMaintenancesJoins( $this->data['SecurityService']['maintenance_calendar'], $this->id );

		// 	//temporarily reassign data because it gets lost during audit joins
		// 	$this->data = $data;
		// }

		$this->data = false;
		
		return $ret;
	}

	/**
	 * @deprecated
	 * 
	 * delete all hasMany data
	 * 
	 * @param  int $id security_service_id
	 * @return boolean 
	 */
	public function deleteJoins($id) {
		// $ret = $this->SecurityServicesServiceContract->deleteAll( array(
		// 	'SecurityServicesServiceContract.security_service_id' => $id
		// ) );
		// $ret &= $this->SecurityPoliciesSecurityService->deleteAll( array(
		// 	'SecurityPoliciesSecurityService.security_service_id' => $id
		// ) );
		// $ret = $this->SecurityServiceAuditDate->deleteAll( array(
		// 	'SecurityServiceAuditDate.security_service_id' => $id
		// ) );
		// $ret &= $this->SecurityServiceMaintenanceDate->deleteAll( array(
		// 	'SecurityServiceMaintenanceDate.security_service_id' => $id
		// ) );
		// $ret &= $this->ProjectsSecurityServices->deleteAll(array(
		// 	'ProjectsSecurityServices.security_service_id' => $id
		// ) );
		// $ret &= $this->SecurityServicesUser->deleteAll(array(
		// 	'SecurityServicesUser.security_service_id' => $id
		// ) );
		$ret = $this->Classification->deleteAll(array(
			'Classification.security_service_id' => $id
		) );


		return $ret;
	}

	public function convertClassificationsImport($value) {
		if (is_array($value)) {
			return implode(',', $value);
		}

		return false;
	}

	// conversion of audit dates for import tool export
	public function convertAuditDateExport($item) {
		return $this->_convertDatesImportTool($item, 'SecurityServiceAuditDate');
	}

	// conversion of maintenance dates for import tool export
	public function convertMaintenanceDateExport($item) {
		return $this->_convertDatesImportTool($item, 'SecurityServiceMaintenanceDate');
	}

	/**
	 * Generic method that makes a conversion of Audit or Maintenance dates for import tool export.
	 */
	protected function _convertDatesImportTool($item, $model) {
		if (!in_array($model, ['SecurityServiceAuditDate', 'SecurityServiceMaintenanceDate'])) {
			trigger_error('Wrong model for conversion entered');
		}

		if (!empty($item[$model])) {
			$dates = [];
			foreach ($item[$model] as $date) {
				$dates[] = $date['day'] . '-' . $date['month'];
			}

			return ImportToolModule::buildValues($dates);
		}

		return false;
	}

	// convert dates for import
	public function convertAuditDateImport($dates) {
		if (!empty($dates)) {
			$data = [];
			foreach ($dates as $date) {
				$exploded = explode('-', $date);
				$data[] = [
					'day' => isset($exploded[0]) ? $exploded[0] : false,
					'month' => isset($exploded[1]) ? $exploded[1] : false
				];
			}

			return $data;
		}

		return false;
	}

	/**
	 * save Classification associated with SecurityService
	 * 
	 * @param  string $labels comma-separated classifications
	 * @param  int $id security_service_id
	 * @return boolean $result
	 */
	private function saveClassifications($labels, $id) {
		if (empty($labels)) {
			return true;
		}

		$labels = explode(',', $labels);

		$data = array();

		foreach ($labels as $name) {
			$data[] = array(
				'security_service_id' => $id,
				'name' => $name
			);
		}

		$result = $this->Classification->saveAll($data, array(
			'validate' => false,
			'atomic' => false
		));

		return (bool) $result;
	}

	/**
	 * save hasMany SecurityServiceAuditDate associated with SecurityService
	 * 
	 * @param  array $list list of dates
	 * @param  int $service_id
	 * @return boolean $result
	 */
	private function saveAuditDates($list, $service_id) {
		$data = array();

		foreach ($list as $date) {
			$data[] = array(
				'security_service_id' => $service_id,
				'day' => $date['day'],
				'month' => $date['month']
			);
		}

		$result = $this->SecurityServiceAuditDate->saveAll($data, array(
			'validate' => false,
			'atomic' => false
		));

		return (bool) $result;
	}

	/**
	 * save SecurityServiceAudit data related to SecurityService
	 * 
	 * @param  array $list list of dates
	 * @param  int $service_id
	 * @return boolean $result
	 */
	public function saveAuditsJoins($list, $service_id) {
		$user = $this->currentUser();
		$data = array();
		
		foreach ($list as $date) {
			$dataItem = array(
				'security_service_id' => $service_id,
				'planned_date' =>  date('Y') . '-' . $date['month'] . '-' . $date['day'],
				'AuditOwner' => $this->getStoredOldModelData('AuditOwner'),
				'AuditEvidenceOwner' => $this->getStoredOldModelData('AuditEvidenceOwner'),
				'audit_metric_description' => $this->data['SecurityService']['audit_metric_description'],
				'audit_success_criteria' => $this->data['SecurityService']['audit_success_criteria'],
			);

			$secServAuditData = $this->SecurityServiceAudit->find('all', array(
				'fields' => array(
					'SecurityServiceAudit.id',
					'SecurityServiceAudit.planned_date'
				),
				'conditions' => array(
					'SecurityServiceAudit.security_service_id' => $service_id,
					'SecurityServiceAudit.planned_date' => date('Y') . '-' . $date['month'] . '-' . $date['day']
				),
				'recursive' => -1
			));

			if (empty($secServAuditData)) {
				$data[] = $dataItem;
			} /*elseif (!empty($dataItem['user_id'])) {
				foreach ($secServAuditData as $ssad) {
					if (empty($ssad['SecurityServiceAudit']['planned_date']) || 
					strtotime($ssad['SecurityServiceAudit']['planned_date']) >= strtotime(date('Y-m-d', time()))) {
						$data[] = array(
							'id' => $ssad['SecurityServiceAudit']['id'],
							'user_id' => $dataItem['user_id']
						);
					}
				}
			}*/
		}

		if (empty($data)) {
			return true;
		}

		$result = $this->SecurityServiceAudit->saveAll($data, array(
			'validate' => false,
			'atomic' => false,
		));

		return (bool) $result;
	}

	/**
	 * save hasMany SecurityServiceMaintenanceDate associated to SecurityService
	 * 
	 * @param  array $list list of dates
	 * @param  int $service_id
	 * @return boolean $result
	 */
	private function saveMaintenanceDates( $list, $service_id ) {
		$data = array();

		foreach ($list as $date) {
			$data[] = array(
				'security_service_id' => $service_id,
				'day' => $date['day'],
				'month' => $date['month']
			);
		}

		$result = $this->SecurityServiceMaintenanceDate->saveAll($data, array(
			'validate' => false,
			'atomic' => false
		));

		return (bool) $result;
	}

	/**
	 * save SecurityServiceMaintenance data related to SecurityService
	 * 
	 * @param  array $list list of dates
	 * @param  int $service_id
	 * @return boolean $result
	 */
	public function saveMaintenancesJoins($list, $service_id)
	{
		$data = array();

		foreach ($list as $date) {
			$dataItem = array(
				'security_service_id' => $service_id,
				'planned_date' =>  date('Y') . '-' . $date['month'] . '-' . $date['day'],
				'MaintenanceOwner' => $this->getStoredOldModelData('MaintenanceOwner'),
				'task' => $this->data['SecurityService']['maintenance_metric_description']
			);

			$secServMtncData = $this->SecurityServiceMaintenance->find('all', array(
				'fields' => array(
					'SecurityServiceMaintenance.id',
					'SecurityServiceMaintenance.planned_date'
				),
				'conditions' => array(
					'SecurityServiceMaintenance.security_service_id' => $service_id,
					'SecurityServiceMaintenance.planned_date' => date('Y') . '-' . $date['month'] . '-' . $date['day']
				),
				'recursive' => -1
			));

			if (empty($secServMtncData)) {
				$data[] = $dataItem;
			} /*elseif (!empty($dataItem['user_id'])) {
				foreach ($secServMtncData as $ssmd) {
					if (empty($ssmd['SecurityServiceMaintenance']['planned_date']) || 
					strtotime($ssmd['SecurityServiceMaintenance']['planned_date']) >= strtotime(date('Y-m-d', time()))) {
						$data[] = array(
							'id' => $ssmd['SecurityServiceMaintenance']['id'],
							'user_id' => $dataItem['user_id']
						);
					}
				}
			}*/
		}

		if (empty($data)) {
			return true;
		}

		$result = $this->SecurityServiceMaintenance->saveAll($data, array(
			'validate' => false,
			'atomic' => false,
		));

		return true;
	}

	public function resaveNotifications($id) {
		$ret = true;

		$this->bindNotifications();
		$ret &= $this->NotificationObject->NotificationSystem->saveCustomUsersByModel($this->alias, $id);

		$auditIds = $this->SecurityServiceAudit->find('list', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$this->SecurityServiceAudit->bindNotifications();
		$ret &= $this->SecurityServiceAudit->NotificationObject->NotificationSystem->saveCustomUsersByModel('SecurityServiceAudit', $auditIds);

		return $ret;
	}

	public function statusProcess($id, $column) {
		if ($column == 'audits_last_passed' || $column == 'audits_last_missing') {
			$statuses = $this->SecurityServiceAudit->getStatuses($id);
		}

		if ($column == 'maintenances_last_missing') {
			$statuses = $this->SecurityServiceMaintenance->getStatuses($id);
		}

		return $statuses[$column];
	}

	/**
	 * @deprecated status, in favor of SecurityService::_statusOngoingCorrectiveActions()
	 */
	public function statusOngoingCorrectiveActions($id) {
		$this->ProjectsSecurityService->bindModel(array(
			'belongsTo' => array('Project')
		));

		// check projects by status
		$ret = $this->ProjectsSecurityService->find('count', array(
			'conditions' => array(
				'ProjectsSecurityService.security_service_id' => $id,
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
			),
			'recursive' => 0
		));

		$auditIds = $this->SecurityServiceAudit->find('list', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$Improvement = $this->SecurityServiceAudit->SecurityServiceAuditImprovement;

		// or also check projects associated to audit improvements by status
		$ret = $ret || $Improvement->ProjectsSecurityServiceAuditImprovement->find('count', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id,
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
			),
			'joins' => $Improvement->getRelatedJoins(),
			'recursive' => -1
		));

		// if at least 1 record was found that means it should show ongoing corrective actions status
		if ($ret) {
			return 1;
		}

		return 0;
	}

	/**
	 * @deprecated status, in favor of SecurityService::statusControlWithIssues()
	 */
	public function statusHasIssues($id) {
		$count = $this->Issue->find('count', array(
			'conditions' => array(
				'Issue.model' => 'SecurityService',
				'Issue.foreign_key' => $id,
				'Issue.status' => ISSUE_OPEN
			),
			'recursive' => -1
		));

		if ($count) {
			return 1;
		}

		return 0;
	}

	/*public function mappedProjects($id) {
		$data = $this->ProjectsSecurityServices->find('list', array(
			'conditions' => array(
				'ProjectsSecurityServices.security_service_id' => $id
			),
			'fields' => array('id', 'project_id'),
			'recursive' => -1
		));

		if (empty($data)) {
			return false;
		}

		$projects = $this->Projects->find('list', array(
			'conditions' => array(
				'Projects.id' => $data
			),
			'fields' => array('id', 'title'),
			'recursive' => -1
		));

		return implode(', ', $projects);
	}*/

	/*public function lastAuditDate($id, $result = array(1, null), $field = 'planned_date') {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$audit = $this->SecurityServiceAudit->find('first', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id,
				'SecurityServiceAudit.planned_date <=' => $today,
				'SecurityServiceAudit.result' => $result
			),
			'fields' => array('SecurityServiceAudit.id', 'SecurityServiceAudit.result', 'SecurityServiceAudit.planned_date'),
			'order' => array('SecurityServiceAudit.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			return $audit['SecurityServiceAudit'][$field];
		}

		return false;
	}*/

	/*public function lastMissingAudit($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$audit = $this->SecurityServiceAudit->find('first', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id,
				'SecurityServiceAudit.planned_date <=' => $today,
				'SecurityServiceAudit.result' => null
			),
			'order' => array('SecurityServiceAudit.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			$this->lastMissingAuditId = $audit['SecurityServiceAudit']['id'];
			return $audit['SecurityServiceAudit']['planned_date'];
		}

		return false;
	}

	public function lastMissingAuditResult($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$audit = $this->SecurityServiceAudit->find('first', array(
			'conditions' => array(
				'SecurityServiceAudit.security_service_id' => $id,
				'SecurityServiceAudit.planned_date <=' => $today,
				'SecurityServiceAudit.result' => array(1,0)
			),
			'order' => array('SecurityServiceAudit.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			if ($audit['SecurityServiceAudit']['result']) {
				return __('Pass');
			}

			return __('Fail');

		}

		return false;
	}*/

	public function lastMissingMaintenance($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$Maintenance = $this->SecurityServiceMaintenance->find('first', array(
			'conditions' => array(
				'SecurityServiceMaintenance.security_service_id' => $id,
				'SecurityServiceMaintenance.planned_date <=' => $today,
				'SecurityServiceMaintenance.result' => null
			),
			'order' => array('SecurityServiceMaintenance.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($Maintenance)) {
			return $Maintenance['SecurityServiceMaintenance']['planned_date'];
		}

		return false;
	}

	public function lastMissingMaintenanceResult($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$Maintenance = $this->SecurityServiceMaintenance->find('first', array(
			'conditions' => array(
				'SecurityServiceMaintenance.security_service_id' => $id,
				'SecurityServiceMaintenance.planned_date <=' => $today,
				'SecurityServiceMaintenance.result' => array(1,0)
			),
			'order' => array(/*'SecurityServiceMaintenance.planned_date' => 'DESC', */'SecurityServiceMaintenance.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($Maintenance)) {
			if ($Maintenance['SecurityServiceMaintenance']['result']) {
				return __('Pass');
			}

			return __('Fail');

		}

		return false;
	}

	public function checkLastAuditStatus($id, $param) {
		if (empty($id) || empty($param)) {
			return null;
		}

		$audits = $this->SecurityServiceAudit->getStatuses($id);
		return $audits[$param];
	}

	private function updateAuditMaintenanceMetricAndCriteriaFields() {
		if (!empty($this->id)) {
			if (isset($this->data['SecurityService']['audit_metric_description']) && isset($this->data['SecurityService']['audit_success_criteria']) && isset($this->data['SecurityService']['maintenance_metric_description'])) {
				$data = $this->find('first', array(
					'conditions' => array(
						'id' => $this->id
					),
					'fields' => array(
						'audit_metric_description',
						'audit_success_criteria',
						'maintenance_metric_description'
					),
					'recursive' => -1
				));

				$updateFields = array();
				if ($this->data['SecurityService']['audit_metric_description'] != $data['SecurityService']['audit_metric_description']) {
					$updateFields['SecurityServiceAudit.audit_metric_description'] = '"' . addslashes($this->data['SecurityService']['audit_metric_description']) . '"';
				}
				if ($this->data['SecurityService']['audit_success_criteria'] != $data['SecurityService']['audit_success_criteria']) {
					$updateFields['SecurityServiceAudit.audit_success_criteria'] = '"' . addslashes($this->data['SecurityService']['audit_success_criteria']) . '"';
				}

				if (!empty($updateFields)) {
					return $this->SecurityServiceAudit->updateAll($updateFields, array(
						'SecurityServiceAudit.planned_date >' => date('Y-m-d'),
						'SecurityServiceAudit.security_service_id' => $this->id
					));
				}

				$updateFields = array();
				if ($this->data['SecurityService']['maintenance_metric_description'] != $data['SecurityService']['maintenance_metric_description']) {
					$updateFields['SecurityServiceMaintenance.task'] = '"' . addslashes($this->data['SecurityService']['maintenance_metric_description']) . '"';
				}

				if (!empty($updateFields)) {
					return $this->SecurityServiceMaintenance->updateAll($updateFields, array(
						'SecurityServiceMaintenance.planned_date >' => date('Y-m-d'),
						'SecurityServiceMaintenance.security_service_id' => $this->id
					));
				}
			}
		}
	}

	private function disableDesignValidation() {
		$disableValidation = false;
		if (isset($this->data['SecurityService']['security_service_type_id'])) {
			if ($this->data['SecurityService']['security_service_type_id'] == SECURITY_SERVICE_DESIGN) {
				$disableValidation = true;
			}
		}
		elseif ($this->id != null) {
			$data = $this->find('count', array(
				'conditions' => array(
					'id' => $this->id,
					'security_service_type_id' => SECURITY_SERVICE_DESIGN
				),
				'recursive' => -1
			));

			if (!empty($data)) {
				$disableValidation = true;
			}
		}

		if ($disableValidation) {
			$this->validator()->remove('audit_metric_description');
			$this->validator()->remove('audit_success_criteria');
			$this->validator()->remove('maintenance_metric_description');
		}
	}

	public function getSecurityServiceTypes() {
		if (isset($this->data['SecurityService']['security_service_type_id'])) {
			$type = $this->SecurityServiceType->find('first', array(
				'conditions' => array(
					'SecurityServiceType.id' => $this->data['SecurityService']['security_service_type_id']
				),
				'fields' => array('name'),
				'recursive' => -1
			));

			return $type['SecurityServiceType']['name'];
		}

		return false;
	}

	/**
	 * @deprecated
	 */
	public function getLastAuditFailedDate() {
		if (!empty($this->lastAuditFailed)) {
			return $this->lastAuditFailed;
		}

		return false;
	}

	/**
	 * @deprecated
	 */
	public function getLastAuditMissingDate() {
		if (!empty($this->lastAuditMissing)) {
			return $this->lastAuditMissing;
		}

		return false;
	}

	/**
	 * @deprecated
	 */
	public function getLastMaintenanceMissingDate() {
		if (!empty($this->lastMaintenanceMissing)) {
			return $this->lastMaintenanceMissing;
		}

		return false;
	}

	/**
	 * Saves audits and maintenance fields for a security service.
	 * @param  int $id Security Service ID.
	 */
	public function saveAuditsMaintenances($id) {
		// $audits = $this->SecurityServiceAudit->getStatuses($id);
		// $maintenances = $this->SecurityServiceMaintenance->getStatuses($id);

		// $saveData = array_merge($audits, $maintenances);

		// $this->id = $id;
		// $ret = $this->save($saveData, array('validate' => false, 'callbacks' => 'before'));


		// return $ret;

		return true;
	}

	/**
	 * Saves audits fields for a security service.
	 * @param  int $id Security Service ID.
	 */
	public function saveAudits($id, $processType = null) {
		// $saveData = $this->SecurityServiceAudit->getStatuses($id);

		// $this->id = $id;
		// return $this->save($saveData, array('validate' => false, 'afterItemSave' => true));

		$ret = $this->triggerStatus('auditsLastFailed', $id, $processType);
		$ret &= $this->triggerStatus('auditsLastMissing', $id, $processType);
		return $ret;
	}

	/**
	 * Saves audits fields for a security service.
	 * @param  int $id Security Service ID.
	 */
	public function saveMaintenances($id, $processType = null) {
		$ret = true;

		$ret &= $this->triggerStatus('maintenancesLastMissing', $id, $processType);
		return $ret;

		// $saveData = $this->SecurityServiceMaintenance->getStatuses($id);

		// $this->id = $id;
		// return $this->save($saveData, array('validate' => false, 'callbacks' => 'before'));
	}

	private function getSecurityServiceRisks($id) {
		$data = $this->RisksSecurityService->find('list', array(
			'conditions' => array(
				'RisksSecurityService.security_service_id' => $id
			),
			'fields' => array('RisksSecurityService.risk_id'),
			'recursive' => -1
		));

		return $data;
	}

	private function getSecurityServiceTpRisks($id) {
		$data = $this->SecurityServicesThirdPartyRisk->find('list', array(
			'conditions' => array(
				'SecurityServicesThirdPartyRisk.security_service_id' => $id
			),
			'fields' => array('SecurityServicesThirdPartyRisk.third_party_risk_id'),
			'recursive' => -1
		));

		return $data;
	}

	private function getSecurityServiceBusinessRisks($id) {
		$data = $this->BusinessContinuitiesSecurityService->find('list', array(
			'conditions' => array(
				'BusinessContinuitiesSecurityService.security_service_id' => $id
			),
			'fields' => array('BusinessContinuitiesSecurityService.business_continuity_id'),
			'recursive' => -1
		));

		return $data;
	}

	public function getIssues($id = array(), $find = 'list') {
		if (empty($id)) {
			return false;
		}

		if ($find == 'all') {
			$data = $this->find($find, array(
				'conditions' => array(
					'SecurityService.id' => $id
				),
				'fields' => array(
					'MIN(SecurityService.audits_last_passed) AS LastAuditPassed',
					'MAX(SecurityService.audits_last_missing) AS LastAuditMissing',
					'MAX(SecurityService.maintenances_last_missing) AS LastMaintenanceMissing',
					'MAX(SecurityService.audits_improvements) AS AuditImprovements',
					'SUM(SecurityService.security_incident_open_count) AS OngoingSecurityIncident',
					'MIN(SecurityService.security_service_type_id) AS SecurityServiceTypeId',

				),
				'recursive' => -1
			));

			$data = $data[0][0];
		}
		else {
			$data = $this->find($find, array(
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
		}

		return $data;
	}

	/**
	 * Get associated projects.
	 */
	public function getAssignedProjects($id, $type = 'count') {
		$data = $this->ProjectsSecurityServices->find($type, array(
			'conditions' => array(
				'ProjectsSecurityServices.security_service_id' => $id
			),
			'recursive' => -1
		));

		return $data;
	}

	public function getProjects() {
		return $this->Project->getList();
	}

	/**
	 * @deprecated
	 */
	public function auditsLastPassedConditions($data = array()){
		$conditions = array();
		if($data['audits_last_failed'] == 1){
			$conditions = array(
				'SecurityService.audits_last_passed' => 0
			);
		}
		elseif($data['audits_last_failed'] == 0){
			$conditions = array(
				'SecurityService.audits_last_passed' => 1
			);
		}

		return $conditions;
	}

	/**
	 * @deprecated
	 */
	public function securityIncidentOpenConditions($data = array()){
		$conditions = array();
		if($data['security_incident_open_count'] == 1){
			$conditions = array(
				'SecurityService.security_incident_open_count >' => 0
			);
		}
		elseif($data['security_incident_open_count'] == 0){
			$conditions = array(
				'SecurityService.security_incident_open_count' => 0
			);
		}

		return $conditions;
	}

	/**
	 * @deprecated
	 */
	public function designConditions($data = array()){
		$conditions = array();
		if($data['design'] == 1){
			$conditions = array(
				'SecurityServiceType.id' => SECURITY_SERVICE_DESIGN
			);
		}
		elseif($data['design'] == 0){
			$conditions = array(
				'SecurityServiceType.id !=' => SECURITY_SERVICE_DESIGN
			);
		}

		return $conditions;
	}

	public function getClassifications() {
		$tags = $this->Classification->find('list', array(
			'order' => array('Classification.name' => 'ASC'),
			'fields' => array('Classification.name', 'Classification.name'),
			'group' => array('Classification.name'),
			'recursive' => -1
		));

		return $tags;
	}

	public function findByClassifications($data = array(), $filterParams = array()) {
		// $this->Classification->Behaviors->attach('Containable', array(
		// 		'autoFields' => false
		// 	)
		// );
		// $this->Classification->Behaviors->attach('Search.Searchable');
		
		// $query = $this->Classification->getQuery('all', array(
		// 	'conditions' => array(
		// 		'Classification.name' => $data['classifications'],
		// 	),
		// 	'fields' => array(
		// 		'Classification.security_service_id'
		// 	),
		// 	'group' => array(
		// 		'Classification.security_service_id HAVING COUNT(Classification.security_service_id) = ' . count($data['classifications'])
		// 	),
		// 	'recursive' => -1
		// ));

		// return $query;
		// debug($query);exit;

		$this->Classification->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->Classification->Behaviors->attach('Search.Searchable');

		$query = $this->Classification->getQuery('all', array(
			'conditions' => array(
				'Classification.name' => $data['classifications']
			),
			// 'group' => array('Classification.name'),
			'fields' => array(
				'Classification.security_service_id'
			)
		));
		// debug($query);exit;

		return $query;
	}

	public function findByAuditDate($data = array(), $filter) {
		$this->SecurityServiceAudit->Behaviors->attach('Containable', array('autoFields' => false));
		$this->SecurityServiceAudit->Behaviors->attach('Search.Searchable');

		$query = $this->SecurityServiceAudit->getQuery('all', array(
			'conditions' => array(
				'SecurityServiceAudit.planned_date ' .  getComparisonTypes()[$filter['comp_type']] => $data['security_service_audit_date']
			),
			'fields' => array(
				'SecurityServiceAudit.security_service_id'
			),
			'contain' => array()
		));

		return $query;
	}

	public function findByMaintanceDate($data = array(), $filter) {
		$this->SecurityServiceMaintenance->Behaviors->attach('Containable', array('autoFields' => false));
		$this->SecurityServiceMaintenance->Behaviors->attach('Search.Searchable');

		$query = $this->SecurityServiceMaintenance->getQuery('all', array(
			'conditions' => array(
				'SecurityServiceMaintenance.planned_date ' .  getComparisonTypes()[$filter['comp_type']] => $data['security_service_maintance_date']
			),
			'fields' => array(
				'SecurityServiceMaintenance.security_service_id'
			),
			'contain' => array()
		));

		return $query;
	}

	public function findByCompliancePackage($data = array(), $filterParams = array()) {
		$this->ComplianceManagementsSecurityService->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->ComplianceManagementsSecurityService->Behaviors->attach('Search.Searchable');

		$value = $data[$filterParams['name']];

		$joins = array(
			array(
				'table' => 'compliance_managements',
				'alias' => 'ComplianceManagement',
				'type' => 'LEFT',
				'conditions' => array(
					'ComplianceManagementsSecurityService.compliance_management_id = ComplianceManagement.id'
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

		$query = $this->ComplianceManagementsSecurityService->getQuery('all', array(
			'conditions' => $conditions,
			'joins' => $joins,
			'fields' => array(
				'ComplianceManagementsSecurityService.security_service_id'
			),
			// 'group' => 'ThirdParty.id'
		));

		return $query;
	}

	public function findByMaintenanceTask($data = array()) {
		$this->SecurityServiceMaintenance->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->SecurityServiceMaintenance->Behaviors->attach('Search.Searchable');

		$query = $this->SecurityServiceMaintenance->getQuery('all', array(
			'conditions' => array(
				'SecurityServiceMaintenance.task LIKE' => '%' . $data['maintenance_task'] . '%'
			),
			'fields' => array(
				'SecurityServiceMaintenance.security_service_id'
			),
			'recursive' => -1
		));

		return $query;
	}

	public function getThirdParties() {
		return $this->ComplianceManagement->getThirdParties();
	}

	public function getSecurityPolicyIds($securityServiceIds = array()) {
		$securityPolicyIds = $this->SecurityPoliciesSecurityService->find('list', array(
			'conditions' => array(
				'SecurityPoliciesSecurityService.security_service_id' => $securityServiceIds
			),
			'fields' => array(
				'SecurityPoliciesSecurityService.security_policy_id'
			)
		));

		return array_values($securityPolicyIds);
	}
}
