<?php
App::uses('SectionBase', 'Model');
App::uses('AppIndexCrudAction', 'Controller/Crud/Action');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');
App::uses('RiskClassification', 'Model');
App::uses('CustomValidatorField', 'CustomValidator.Model');
App::uses('UserFields', 'UserFields.Lib');
App::uses('RiskAppetite', 'Model');
App::uses('RiskAppetitesHelper', 'View/Helper');
App::uses('RiskCalculation', 'Model');

class BaseRisk extends SectionBase {
    public $actsAs = [
        'EventManager.EventManager',
        'Visualisation.Visualisation',
        'UserFields.UserFields' => [
            'fields' => ['Owner', 'Stakeholder']
        ],
        'ReviewsPlanner.Reviews' => [
            'dateColumn' => 'review' 
        ],
        'CustomValidator.CustomValidator'
    ];

    public function __construct($id = false, $table = null, $ds = null)
    {
        //
        // Init helper Lib for UserFields Module
        $UserFields = new UserFields();
        //
        
        $this->fieldData = am($this->fieldData, array(
            'title' => array(
                'label' => __('Title'),
                'editable' => true,
                'description' => __('Give this risk a descriptive title')
            ),
            'description' => array(
                'label' => __('Description'),
                'editable' => true,
                'description' => __('Describe this risk scenario, context, triggers, Etc.')
            ),
            'Owner' => $UserFields->getFieldDataEntityData($this, 'Owner', [
                'label' => __('Owner'), 
                'description' => __('You can use this field in any way it fits best your organisation, for example:<br><br>

                                    - In some cases this role relates to the GRC individual responsible to ensure the Risk is well documented and approved  (this is typically our recommendation).<br> 
                                    - In some other organisations this role belongs to the individual that brings this organisation to the risk by performing a certain business function.<br><br> 

                                    This role will be available when you create notifications under the field Custom Roles.')
            ]),
            'Stakeholder' => $UserFields->getFieldDataEntityData($this, 'Stakeholder', [
                'label' => __('Stakeholder'), 
                'description' => __('Risk collaborators are those that generate risks by doing something or taking a specific decision. For example, all finance risks should have the finance team as collaborators.')
            ]),
            'Tag' => array(
                'type' => 'tags',
                'label' => __('Tags'),
                'editable' => true,
                'description' => __('OPTIONAL: Use tags to classify or tag your risk, examples are "Risk beign treated", "High Risk", "Financial Risk", Etc.'),
                'empty' => __('Add a tag')
            ),
            'review' => array(
                'label' => __('Review'),
                'editable' => false,
                'description' => __('eramba assumes you will review risks at regular point in time. The date you set in this field will be the one used to trigger notifications (to the owner, collabortor or anyone you define).')
            ),
            'Threat' => array(
                'label' => __('Threat Tags'),
                'group' => 'analysis',
                'editable' => true,
                'description' => __('Select all applicable threats.')
            ),
            'threats' => array(
                'label' => __('Threat Description'),
                'group' => 'analysis',
                'editable' => false,
                'description' => __('Describe the context of the threats if you wish.')
            ),
            'Vulnerability' => array(
                'label' => __('Vulnerabilities Tags'),
                'group' => 'analysis',
                'editable' => true,
                'description' => __('Select all applicable vulnerabilities.')
            ),
            'vulnerabilities' => array(
                'label' => __('Vulnerabilities Description'),
                'group' => 'analysis',
                'editable' => false,
                'description' => __('Describe the context of the vulnerabilities if you wish.')
            ),
            'RiskClassification' => array(
                'type' => 'select',
                'label' => __('Risk Classification'),
                'group' => 'analysis',
                'options' => array($this, 'getClassifications'),
                'editable' => false,
                'empty' => __('Choose Classification'),
                'renderHelper' => 'RiskClassificationField',
                'Extensions' => [
                    'RiskClassification'
                ]
            ),
            'risk_mitigation_strategy_id' => array(
                'label' => __('Risk Treatment'),
                'group' => 'treatment',
                'editable' => true,
                'description' => __('Select a treatment strategy for this risk - your treatment options (controls, policies, etc) have been set at Settings / Risk Treatment Options.')
            ),
            'SecurityService' => array(
                'label' => __('Compensating controls'),
                'group' => 'treatment',
                'editable' => true,
                'description' => __('Select one or more controls defined at Control Catalogue / Security Services.')
            ),
            'RiskException' => array(
                'label' => __('Applicable Risk Exceptions'),
                'group' => 'treatment',
                'editable' => true,
                'description' => __('Select one or more Risk Exceptions (defined at Risk Management / Risk Exceptions).')
            ),
            'Project' => array(
                'label' => __('Treatment Projects'),
                'group' => 'treatment',
                'editable' => true,
                'options' => array($this, 'getProjectsNotCompleted'),
                'description' => __('Select one ore more projects (defined at Security Operations / Project Management).')
            ),
            'SecurityPolicyIncident' => array(
                'label' => __('Risk Response Documents'),
                'group' => 'treatment',
                'options' => array($this, 'getSecurityPolicies'),
                'editable' => true,
                'description' => __('OPTIONAL: Select one or more documents (defined in Control Catalogue / Security Policies) that will be used when recording incidents on the Incident module located at Security Operations / Incident Management')
            ),
            'SecurityPolicyTreatment' => array(
                'label' => __('Treatment Documents'),
                'group' => 'treatment',
                'options' => array($this, 'getSecurityPolicies'),
                'editable' => true,
                'description' => __('Select one or more documents defined at Control Catalogue / Security Policies.')
            ),
            'SecurityPolicyIncident' => array(
                'label' => __('Risk Response Documents'),
                'group' => 'treatment',
                'options' => array($this, 'getSecurityPolicies'),
                'editable' => true,
                'description' => __('OPTIONAL: Select one or more documents (defined in Control Catalogue / Security Policies) that will be used when recording incidents on the Incident module located at Security Operations / Incident Management')
            ),
            'residual_score' => array(
                'label' => __('Residual Score'),
                'group' => 'treatment',
                'editable' => true,
                'options' => 'getReversePercentageOptions',
                'description' => __('Select the percentage of Risk Reduction that was achieved by applying Security Controls. If the risk score for this risk is 100 points and you select %100, the residual for this risk will be 100 points. If you choose %30, the residual will be 30.')
            ),
            'expired' => array(
                'label' => __('Expired'),
                'type' => 'toggle',
                'hidden' => true
            ),
            'exceptions_issues' => array(
                'label' => __('Exception Issues'),
                'type' => 'toggle',
                'hidden' => true
            ),
            'controls_issues' => array(
                'label' => __('Controls Issues'),
                'type' => 'toggle',
                'hidden' => true
            ),
            'control_in_design' => array(
                'label' => __('Controls in Design'),
                'type' => 'toggle',
                'hidden' => true
            ),
            'expired_reviews' => array(
                'label' => __('Expired Reviews'),
                'type' => 'toggle',
                'hidden' => true
            ),
            'risk_above_appetite' => array(
                'label' => __('Risk above appetite'),
                'type' => 'toggle',
                'hidden' => true
            ),
        ));

        parent::__construct($id, $table, $ds);

        $calculationMethod = $this->getMethod();
        $calculationValues = $this->getClassificationTypeValues($this->getSectionValues());
        // if ($calculationMethod == 'eramba') {
        //     $this->getFieldDataEntity('RiskClassification')->toggleEditable(true);
        // }

        $this->advancedFilter = array(
            __('Basic Risk') => array(
                'id' => array(
                    'type' => 'text',
                    'name' => __('ID'),
                    'show_default' => true,
                    'filter' => false
                ),
                'title' => array(
                    'type' => 'text',
                    'name' => __('Title'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.title',
                        'field' => $this->alias . '.id',
                    )
                ),
                'description' => array(
                    'type' => 'text',
                    'name' => __('Description'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.description',
                        'field' => $this->alias . '.id',
                    ),
                ),
                'tag_title' => array(
                    'type' => 'multiple_select',
                    'name' => __('Tags'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByTags',
                        'field' => $this->alias . '.id',
                        'model' => $this->alias
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
                'stakeholder_id' => $UserFields->getAdvancedFilterFieldData($this->alias, 'Stakeholder', [
                    'name' => __('Stakeholder'),
                ]),
                'owner_id' => $UserFields->getAdvancedFilterFieldData($this->alias, 'Owner', [
                    'name' => __('Owner'),
                ]),
                'expired_reviews' => array(
                    'type' => 'select',
                    'name' => __('Expired Reviews'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.expired_reviews',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getExpiredReviewStatus',
                        'empty' => __('All'),
                        'result_key' => true,
                    ),
                ),
                'risk_above_appetite' => array(
                    'type' => 'select',
                    'name' => __('Risk above appetite'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.risk_above_appetite',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getExpiredReviewStatus',
                        'empty' => __('All'),
                        'result_key' => true,
                    ),
                ),
                'review' => array(
                    'type' => 'date',
                    'comparison' => true,
                    'name' => __('Review'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.review',
                        'field' => $this->alias . '.id',
                    ),
                ),
            ),
            __('Risk Analysis') => array(
                'risk_classification_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Classifications'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'RiskClassification.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getClassifications',
                        'result_key' => true
                    ),
                    'many' => true,
                    'contain' => array(
                        'RiskClassification' => array(
                            'id'
                        )
                    ),
                ),
                'analysis_risk_appetite_threshold_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Analysis Risk Appetite'),
                    'show_default' => false,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'RiskAppetiteThresholdAnalysis.title',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getThresholds',
                    ),
                    'many' => true,
                    'field' => 'RiskAppetiteThresholdAnalysis.{n}.title',
                    'containable' => array(
                        'RiskAppetiteThresholdAnalysis' => array(
                            'fields' => array('id', 'title'),
                        )
                    )
                ),
                'asset_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Applicable Assets'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Asset.id',
                        'field' => $this->alias . '.id',
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
                'asset_business_unit_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Business Units'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'BusinessUnit.id',
                        'field' => $this->alias . '.id',
                        'path' => [
                            'AssetsRisk' => [
                                'findField' => 'AssetsRisk.asset_id',
                                'field' => 'AssetsRisk.risk_id',
                            ],
                            'AssetsBusinessUnit' => [
                                'findField' => 'AssetsBusinessUnit.business_unit_id',
                                'field' => 'AssetsBusinessUnit.asset_id',
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ),
                    'data' => array(
                        'method' => 'getBusinessUnits',
                    ),
                    'many' => true,
                    'field' => 'Asset.{n}.BusinessUnit.{n}.name',
                    'containable' => array(
                        'Asset' => array(
                            'fields' => array('id'),
                            'BusinessUnit' => array(
                                'fields' => array('name'),
                            )
                        )
                    )
                ),
                'threat_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Threats Tags'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Threat.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getThreats',
                    ),
                    'many' => true,
                    'contain' => array(
                        'Threat' => array(
                            'name'
                        )
                    ),
                ),
                'threats' => array(
                    'type' => 'text',
                    'name' => __('Threat Description'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.threats',
                        'field' => $this->alias . '.id',
                    ),
                ),
                'vulnerability_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Vulnerabilities Tags'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Vulnerability.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getVulnerabilities',
                    ),
                    'many' => true,
                    'contain' => array(
                        'Vulnerability' => array(
                            'name'
                        )
                    ),
                ),
                'vulnerabilities' => array(
                    'type' => 'text',
                    'name' => __('Vulnerabilities Description'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.vulnerabilities',
                        'field' => $this->alias . '.id',
                    ),
                ),
                'data_asset_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Data Asset Flow'),
                    'show_default' => false,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAsset.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getDataAssets',
                    ),
                    'many' => true,
                    'contain' => array(
                        'DataAsset' => array(
                            'title'
                        )
                    ),
                ),
            ),
            __('Risk Treatment') => array(
                'treatment_risk_classification_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Treatment Classifications'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'RiskClassificationTreatment.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getClassifications',
                        'result_key' => true
                    ),
                    'many' => true,
                    'contain' => array(
                        'RiskClassificationTreatment' => array(
                            'id'
                        )
                    ),
                ),
                'treatment_risk_appetite_threshold_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Treatment Risk Appetite'),
                    'show_default' => false,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'RiskAppetiteThresholdTreatment.title',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getThresholds',
                    ),
                    'many' => true,
                    'field' => 'RiskAppetiteThresholdTreatment.{n}.title',
                    'containable' => array(
                        'RiskAppetiteThresholdTreatment' => array(
                            'fields' => array('id', 'title'),
                        )
                    )
                ),
                'risk_mitigation_strategy_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Risk Treatment'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.risk_mitigation_strategy_id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getRiskStrategies',
                    ),
                    'contain' => array(
                        'RiskMitigationStrategy' => array(
                            'name'
                        )
                    ),
                ),
                'risk_exception_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Applicable Risk Exceptions'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'RiskException.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getException',
                    ),
                    'many' => true,
                    'contain' => array(
                        'RiskException' => array(
                            'title'
                        )
                    ),
                ),
                'security_service_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Mitigating controls'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'SecurityService.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getServices',
                    ),
                    'many' => true,
                    'contain' => array(
                        'SecurityService' => array(
                            'name'
                        )
                    ),
                ),
                'project_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Treatment Projects'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Project.id',
                        'field' => $this->alias . '.id',
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
                    'name' => __('Risk Response Policies'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'SecurityPolicy.id',
                        'field' => $this->alias . '.id',
                    ),
                    'data' => array(
                        'method' => 'getSecurityPolicies',
                        'result_key' => true
                    ),
                    'many' => true,
                    'contain' => array(
                        'SecurityPolicyIncident' => array(
                            'id'
                        )
                    ),
                ),
                'risk_score' => array(
                    'type' => 'number',
                    'comparison' => true,
                    'name' => __('Risk Score'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.risk_score',
                        'field' => $this->alias . '.id',
                    ),
                ),
                'residual_risk' => array(
                    'type' => 'number',
                    'show_default' => true,
                    'comparison' => true,
                    'name' => __('Residual Score'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.residual_risk',
                        'field' => $this->alias . '.id',
                    ),
                ),
            ),
            __('Related Status') => array(
                'project_expired' => array(
                    'type' => 'select',
                    'name' => __('Improvement Project Expired'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
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
                ),
                'security_incident_ongoing_incident' => array(
                    'type' => 'select',
                    'name' => __('Ongoing Incident'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
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
                            'fields' => array('security_incident_status_id')
                        )
                    )
                ),
                'risk_exception_expired' => array(
                    'type' => 'select',
                    'name' => __('Exception Expired'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'RiskException',
                            'field' => 'expired'
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    )
                ),
                'security_service_audits_last_passed' => array(
                    'type' => 'select',
                    'name' => __('Last audit failed'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'SecurityService',
                            'field' => 'audits_last_passed',
                            // 'negative' => true
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOptionInverted',
                        'empty' => __('All'),
                        'result_key' => true,
                    ),
                ),
                'security_service_audits_last_missing' => array(
                    'type' => 'select',
                    'name' => __('Last audit missing'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'SecurityService',
                            'field' => 'audits_last_missing'
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    )
                ),
                'security_service_maintenances_last_missing' => array(
                    'type' => 'select',
                    'name' => __('Last maintenance missing'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'SecurityService',
                            'field' => 'maintenances_last_missing'
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    )
                ),
                'security_service_ongoing_corrective_actions' => array(
                    'type' => 'select',
                    'name' => __('Ongoing Corrective Actions'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'SecurityService',
                            'field' => 'ongoing_corrective_actions'
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    )
                ),
                //design
                'security_service_security_service_type_id' => array(
                    'type' => 'select',
                    'name' => __('Control in Design'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'SecurityService',
                            'field' => 'security_service_type_id',
                            'customValue' => SECURITY_SERVICE_DESIGN
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    )
                ),
                'security_service_control_with_issues' => array(
                    'type' => 'select',
                    'name' => __('Control with Issues'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'SecurityService',
                            'field' => 'control_with_issues'
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    )
                ),
                'asset_expired_reviews' => array(
                    'type' => 'select',
                    'name' => __('Missing Asset Review'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findByInheritedStatus',
                        'field' => $this->alias . '.id',
                        'status' => array(
                            'model' => 'Asset',
                            'field' => 'expired_reviews'
                        )
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    )
                ),
            ),
        );

        if ($this->getRiskAppetiteType() == RiskClassification::TYPE_TREATMENT) {
            unset($this->advancedFilter['Basic Risk']['risk_above_appetite']);
        }
        else {
            unset($this->advancedFilter['Risk Analysis']['analysis_risk_appetite_threshold_id']);
            unset($this->advancedFilter['Risk Treatment']['treatment_risk_appetite_threshold_id']);
        }
    }

    public function getObjectStatusConfig() {
        return [
            'expired' => [
                'title' => __('Risk Review Expired'),
                'callback' => [$this, 'statusExpired'],
                'type' => 'danger'
            ],
            'exceptions_issues' => [
                'title' => __('Exceptions Issues'),
                'callback' => [$this, 'statusExceptionsIssues'],
                'type' => 'danger'
            ],
            'controls_issues' => [
                'title' => __('Control with Issues'),
                'inherited' => [
                    'SecurityService' => 'control_with_issues'
                ],
            ],
            'control_in_design' => [
                'title' => __('Control In Design'),
                'inherited' => [
                    'SecurityService' => 'control_in_design'
                ],
            ],
            'project_expired' => [
                'title' => __('Project Expired'),
                'inherited' => [
                    'Project' => 'expired'
                ],
                'storageSelf' => false,
            ],
            'ongoing_incident' => [
                'title' => __('Ongoing Incident'),
                'inherited' => [
                    'SecurityIncident' => 'ongoing_incident'
                ],
                'storageSelf' => false,
            ],
            'risk_exception_expired' => [
                'title' => __('Exception Expired'),
                'inherited' => [
                    'RiskException' => 'expired'
                ],
                'storageSelf' => false,
            ],
            'audits_last_passed' => [
                'title' => __('Last audit failed'),
                'inherited' => [
                    'SecurityService' => 'audits_last_passed'
                ],
                'type' => 'danger',
                'storageSelf' => false,
            ],
            'audits_last_missing' => [
                'title' => __('Last audit missing'),
                'inherited' => [
                    'SecurityService' => 'audits_last_missing'
                ],
                'storageSelf' => false,
            ],
            'maintenances_last_missing' => [
                'title' => __('Last maintenance missing'),
                'inherited' => [
                    'SecurityService' => 'maintenances_last_missing'
                ],
                'storageSelf' => false,
            ],
            'ongoing_corrective_actions' => [
                'title' => __('Ongoing Corrective Actions'),
                'inherited' => [
                    'SecurityService' => 'ongoing_corrective_actions'
                ],
                'storageSelf' => false,
            ],
            'control_with_issues' => [
                'title' => __('Control with Issues'),
                'inherited' => [
                    'SecurityService' => 'control_with_issues'
                ],
                'storageSelf' => false,
            ],
        ];
    }

    /*
     * static enum: Model::function()
     * @access static
     */
    public static function mitigationStrategies($value = null) {
        $options = array(
            self::RISK_MITIGATION_ACCEPT => __('Accept'),
            self::RISK_MITIGATION_AVOID => __('Avoid'),
            self::RISK_MITIGATION_MITIGATE => __('Mitigate'),
            self::RISK_MITIGATION_TRANSFER => __('Transfer')
        );
        return parent::enum($value, $options);
    }

    const RISK_MITIGATION_ACCEPT = RISK_MITIGATION_ACCEPT;
    const RISK_MITIGATION_AVOID = RISK_MITIGATION_AVOID;
    const RISK_MITIGATION_MITIGATE = RISK_MITIGATION_MITIGATE;
    const RISK_MITIGATION_TRANSFER = RISK_MITIGATION_TRANSFER;

    public function statusExpired($conditions = null) {
        return parent::statusExpired([
            $this->alias . '.review < NOW()'
        ]);
    }

    public function _statusRiskAboveAppetite($id = null, $dbValue = false) {
        $id = ($id === null) ? $this->id : $id;
        
        $data = $this->find('count', [
            'conditions' => [
                $this->alias . '.id' => $id,
                $this->alias . '.residual_risk >' => ClassRegistry::init('Setting')->getVariable('RISK_APPETITE')
            ],
            'recursive' => -1
        ]);

        return (boolean) $data;
    }

    public function getRiskAppetiteType() {
        $RiskAppetite = ClassRegistry::init('RiskAppetite');

        return $RiskAppetite->getCurrentType();
    }

    public function getCustomValidatorConfig() {
        $defaultFieldsConfig = [
            'SecurityService' => CustomValidatorField::OPTIONAL_VALUE,
            'SecurityPolicyTreatment' => CustomValidatorField::OPTIONAL_VALUE,
            'RiskException' => 'minCount',
            'Project' => CustomValidatorField::OPTIONAL_VALUE
        ];

        return [
            'mitigation_strategy_accept' => [
                'title' => __('Risk Treatment Validation - %s', self::mitigationStrategies(self::RISK_MITIGATION_ACCEPT)), 
                'conditions' => [
                    'risk_mitigation_strategy_id' => self::RISK_MITIGATION_ACCEPT
                ],
                'fields' => $defaultFieldsConfig,
            ],
            'mitigation_strategy_avoid' => [
                'title' => __('Risk Treatment Validation - %s', self::mitigationStrategies(self::RISK_MITIGATION_AVOID)), 
                'conditions' => [
                    'risk_mitigation_strategy_id' => self::RISK_MITIGATION_AVOID
                ],
                'fields' => $defaultFieldsConfig,
            ],
            'mitigation_strategy_mitigate' => [
                'title' => __('Risk Treatment Validation - %s', self::mitigationStrategies(self::RISK_MITIGATION_MITIGATE)), 
                'conditions' => [
                    'risk_mitigation_strategy_id' => self::RISK_MITIGATION_MITIGATE
                ],
                'fields' => [
                    'SecurityService' => 'minCount',
                    'SecurityPolicyTreatment' => CustomValidatorField::OPTIONAL_VALUE,
                    'RiskException' => CustomValidatorField::OPTIONAL_VALUE,
                    'Project' => CustomValidatorField::OPTIONAL_VALUE
                ],
            ],
            'mitigation_strategy_transfer' => [
                'title' => __('Risk Treatment Validation - %s', self::mitigationStrategies(self::RISK_MITIGATION_TRANSFER)), 
                'conditions' => [
                    'risk_mitigation_strategy_id' => self::RISK_MITIGATION_TRANSFER
                ],
                'fields' => $defaultFieldsConfig,
            ],
        ];
    }

    /**
     * OverSave RiskClassification join data. 
     * NOTE: We need to save duplicit HABTM records and its not supported by Cake.
     */
    public function saveRiskClassifications($data, $model = 'RiskClassification') {
        if (!isset($data[$model])) {
            return true;
        }

        $ret = true;

        $assoc = $this->getAssociated($model);
        $WithModel = $this->{$assoc['with']};

        // prepare general conditions for the association
        $conds = Hash::expand($assoc['conditions']);
        $conds = reset($conds);

        //delete all existing records
        $conds[$assoc['foreignKey']] = $this->id;
        $ret &= $WithModel->deleteAll($conds);

        if (isset($data[$model][$model]) && is_array($data[$model][$model])) {
            //save new records
            foreach ($data[$model][$model] as $item) {
                $WithModel->create();
                $saveConds = $conds;
                $saveConds[$assoc['associationForeignKey']] = $item;
                $ret &= $WithModel->save($saveConds);
            }
        }

        return $ret;
    }

    public function beforeValidate($options = array()) {
        $ret = parent::beforeValidate($options);

        $calculationMethod = $this->Behaviors->RiskCalculationManager->getMethod($this);
        $appetiteMethod = ClassRegistry::init('RiskAppetite')->getCurrentType();

        $validateAnalysis = $calculationMethod != RiskCalculation::METHOD_MAGERIT;
        if ($validateAnalysis) {
            // validate classifications generally equally for each risk section
            $this->_validateClassification('RiskClassification');
        }

        $validateTreatment = $appetiteMethod === RiskAppetite::TYPE_THRESHOLD;
        $validateTreatment &= $calculationMethod != RiskCalculation::METHOD_MAGERIT;
        if ($validateTreatment) {
            $this->_validateClassification('RiskClassificationTreatment');
        }

        return $ret;
    }

    protected function _validateClassification($type) {
        if (!isset($this->data[$this->alias][$type])) {
            return true;
        }

        $countData = 0;
        if (!empty($this->data[$this->alias][$type])) {
            $countData = count(array_filter($this->data[$this->alias][$type]));
        }

        $classifications = $this->getFormClassifications();
        $countClassifications = count($classifications);

        if ($countData !== $countClassifications) {
            $this->invalidate($type, __('You have to select all Risk Classifications to meet required conditions'));
        }
    }

    public function afterSave($created, $options = array()) {
        $ret = true;

        //OverSave RiskClassification join data. We need to save duplicit HABTM records and its not supported by Cake. 
        $this->saveRiskClassifications($this->data, 'RiskClassification');

        //OverSave RiskClassification join data. We need to save duplicit HABTM records and its not supported by Cake. 
        $this->saveRiskClassifications($this->data, 'RiskClassificationTreatment');

        parent::afterSave($created, $options);

        return $ret;
    }

    public function saveRiskScoreWrapper($id) {
        $this->afterSaveRiskScore($id, $this->scoreAssocModel);

        $riskAppetiteMethod = ClassRegistry::init('RiskAppetite')->getCurrentType();

        if ($riskAppetiteMethod == RiskAppetite::TYPE_THRESHOLD) {
            $RiskThresholdRisks = ClassRegistry::init('RiskAppetiteThresholdsRisk');

            $threshold = $this->riskThreshold($id, RiskClassification::TYPE_ANALYSIS);
            $RiskThresholdRisks->saveItem($this, $id, $threshold['RiskAppetiteThreshold']['id'], RiskClassification::TYPE_ANALYSIS);

            $threshold = $this->riskThreshold($id, RiskClassification::TYPE_TREATMENT);
            $RiskThresholdRisks->saveItem($this, $id, $threshold['RiskAppetiteThreshold']['id'], RiskClassification::TYPE_TREATMENT);
        }
    }

    // used in after save for recalculation
    public function afterSaveRiskScore($id, $assocModel) {
        $risk = $this->find('first', array(
            'conditions' => array(
                "{$this->alias}.id" => $id
            ),
            'contain' => array(
                $assocModel => array(
                    'fields' => array('id')
                ),
                'RiskClassification' => array(
                    'fields' => array('id')
                ),
                'RiskClassificationTreatment' => [
                    'fields' => ['id']
                ]
            )
        ));

        $classificationIds = array();
        foreach ($risk['RiskClassification'] as $c) {
            $classificationIds[] = $c['id'];
        }

        $assocIds = array();
        foreach ($risk[$assocModel] as $a) {
            $assocIds[] = $a['id'];
        }

        $riskScore = $this->calculateRiskScore($classificationIds, $assocIds);
        $residualScore = isset($this->data[$this->alias]['residual_score']) ? $this->data[$this->alias]['residual_score'] : $risk[$this->alias]['residual_score'];

        $saveData = [];
        if (is_numeric($riskScore)) {
            $math = $this->getCalculationMath();
            if (!is_null($math)) {
                $saveData['risk_score_formula'] = $math;
            }

            $riskAppetiteMethod = ClassRegistry::init('RiskAppetite')->getCurrentType();
            if ($riskAppetiteMethod == RiskClassification::TYPE_ANALYSIS) {
                $residualRisk = getResidualRisk($residualScore, $riskScore);
            }

            if ($riskAppetiteMethod == RiskClassification::TYPE_TREATMENT) {
                $treatmentIds = array();
                foreach ($risk['RiskClassificationTreatment'] as $c) {
                    $treatmentIds[] = $c['id'];
                }

                $residualRisk = $this->calculateRiskScore($treatmentIds, $assocIds);
                $math = $this->getCalculationMath();
                 if (!is_null($math)) {
                    $saveData['residual_risk_formula'] = $math;
                }
            }

            $saveData['id'] = $id;
            $saveData['risk_score'] = $riskScore;
            $saveData['residual_risk'] = $residualRisk;

            $this->create();
            $this->id = $saveData['id'];
            $this->set($saveData);
            $ret = $this->save($saveData, ['validate' => false, 'fieldList' => array_keys($saveData), 'callbacks' => 'before']);
        }
    }

    public function getDataAssets() {
        return $this->DataAsset->getList();
    }

    public function getAssets() {
        $assets = $this->Asset->find('list', array(
            'fields' => array('Asset.name'),
        ));

        return $assets;
    }

    public function getThreats() {
        $data = $this->Threat->find('list', array(
            'fields' => array('Threat.name'),
            'order' => array('Threat.name' => 'ASC')
        ));

        return $data;
    }

    public function getVulnerabilities() {
        $data = $this->Vulnerability->find('list', array(
            'fields' => array('Vulnerability.name'),
            'order' => array('Vulnerability.name' => 'ASC')
        ));

        return $data;
    }

    public function getException() {
        $exceptions = $this->RiskException->find('list', array(
            'order' => array('RiskException.title' => 'ASC'),
            'fields' => array('RiskException.id', 'RiskException.title'),
            'recursive' => -1
        ));
        return $exceptions;
    }

    public function getRiskStrategies() {
        $strategies = $this->RiskMitigationStrategy->find('list', array(
            'fields' => array('RiskMitigationStrategy.id', 'RiskMitigationStrategy.name')
        ));

        return $strategies;
    }

    public function getServices() {
        $services = $this->SecurityService->find('list', array(
            'fields' => array('SecurityService.id', 'SecurityService.name')
        ));

        return $services;
    }

    public function getProjects() {
        return $this->Project->getList(false);
    }

    public function getProjectsNotCompleted() {
        return $this->Project->getList();
    }

    public function getSecurityPolicies() {
        return $this->SecurityPolicy->getListWithType();
    }

    public function getBusinessUnits() {
        $data = $this->Asset->BusinessUnit->find('list', array(
            'fields' => array('BusinessUnit.id', 'BusinessUnit.name'),
            'order' => array('BusinessUnit.name' => 'ASC')
        ));

        return $data;
    }

    /**
     * Get data to set in the controller which relates to ajax request to calculate risk score.
     * This is mainly used by risk classifications while adding/editing an object.
     *    
     * @param  array  $classificationIds Risk Classification IDs
     * @param  array  $relatedItemIds    Parent object IDs which primarily relates to the current model
     * @return array                     Array of data
     */
    public function getRiskCalculationData($classificationIds, $relatedItemIds)
    {
        $calculationMethod = $this->Behaviors->RiskCalculationManager->getMethod($this);

        // risk score configuration
        $riskScore = $this->calculateRiskScore($classificationIds, $relatedItemIds);
        $riskCalculationMath = $this->getCalculationMath();
        $otherData = $this->getOtherData();
        $classificationCriteria = $this->RiskClassification->getRiskCriteria($classificationIds);

        // if magerit it calculation method, we take the highest classification out of one type
        // and then the other classification type as 2 classifications to check threshold from
        if ($calculationMethod == RiskCalculation::METHOD_MAGERIT) {
            $mageritSecondPartClassification = array_pop($classificationIds);

            $maxValue = $this->RiskClassification->find('first', [
                'conditions' => [
                    'RiskClassification.id' => $classificationIds
                ],
                'fields' => [
                    'MAX(RiskClassification.value) as max'
                ],
                'recursive' => -1
            ]);

            if ($maxValue[0]['max'] !== null) {
                $max = $this->RiskClassification->find('first', [
                    'conditions' => [
                        'RiskClassification.value' => $maxValue[0]['max'],
                        'RiskClassification.id' => $classificationIds
                    ],
                    'fields' => [
                        'RiskClassification.id'
                    ],
                    'recursive' => -1
                ]);
                
                $mageritFirstPartClassification = $max['RiskClassification']['id'];

                $classificationIds = [$mageritFirstPartClassification, $mageritSecondPartClassification];
            }
        }

        $appetiteThreshold = $this->RiskClassification->getRiskAppetiteThreshold($classificationIds);

        $setData = [
            'riskScore' => $riskScore,
            'riskAppetite' => Configure::read('Eramba.Settings.RISK_APPETITE'),
            'riskCalculationMath' => $riskCalculationMath,
            'otherData' => $otherData,
            'classificationCriteria' => $classificationCriteria,
            'riskAppetiteThreshold' => [
                'data' => $appetiteThreshold,
            ]
        ];

        return $setData;
    }

    public function getClassifications() {
        $classificationsRaw = $this->RiskClassification->find('all', array(
            'fields' => array('RiskClassification.id', 'RiskClassification.name'),
            'contain' => array(
                'RiskClassificationType' => array(
                    'fields' => array('RiskClassificationType.name')
                )
            )
        ));

        $classifications = array();
        
        foreach ($classificationsRaw as $item) {
            $classifications[$item['RiskClassification']['id']] = '[' . $item['RiskClassificationType']['name'] . '] ' . $item['RiskClassification']['name'];
        }

        return $classifications;
    }

    public function getExpiredReviewStatus() {
        $status = array(
            RISK_EXPIRED_REVIEWS => __('Yes'),
            RISK_NOT_EXPIRED_REVIEWS => __('No')
        );

        return $status;
    }

    public function getResidualScoreOptions() {
        $multiplier = 10;

        $percentages = array();
        for ( $i = 0; $i <= $multiplier; $i++ ) {
            $val = $i * 10;
            $percentages[$val] = CakeNumber::toPercentage($val, 0);
        }

        return array_reverse($percentages, true);
    }

    /**
     * Get the risk threshold data for a specific risk and specific classification type.
     */
    public function riskThreshold($riskId, $type = RiskClassification::TYPE_ANALYSIS) {
        $classificationIds = $this->getRelatedClassifications($riskId, $type);
        $threshold = ClassRegistry::init('RiskAppetiteThreshold')->getThreshold($classificationIds);

        return $threshold;
    }

    /**
     * Get classification IDs associated to a specific Risk.
     */
    public function getRelatedClassifications($riskId, $type = RiskClassification::TYPE_ANALYSIS) {
        $assoc = $this->getAssociated('RiskClassification');
        $with = $assoc['with'];
        $foreignKey = $assoc['foreignKey'];

        return $this->{$with}->find('list', [
            'conditions' => [
                $foreignKey => $riskId,
                'type' => $type
            ],
            'fields' => [
                'risk_classification_id'
            ],
            'recursive' => -1
        ]);
    }

    /**
     * Get the default form classifications that are supposed to be used for form rendering
     * and validation.
     */
    public function getDefaultFormClassifications()
    {
        $calculationValues = $this->getClassificationTypeValues($this->getSectionValues());
        $classifications = $this->RiskClassification->RiskClassificationType->find('all', array(
            'conditions' => array(
                'RiskClassificationType.id' => $calculationValues
            ),
            'order' => array('RiskClassificationType.name' => 'ASC'),
            'recursive' => 1
        ));

        return $classifications;
    }

    public function getThresholds() {
        $RiskAppetiteThreshold = ClassRegistry::init('RiskAppetiteThreshold');

        return $RiskAppetiteThreshold->getList();
    }
}