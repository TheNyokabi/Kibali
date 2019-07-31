<?php
App::uses('BaseRisk', 'Model');
App::uses('RiskClassification', 'Model');

class BusinessContinuity extends BaseRisk {
	public $displayField = 'title';
	public $scoreAssocModel = 'BusinessUnit';

	public $mapping = array(
		'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => true,
        'workflow' => false,
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
        'AuditLog.Auditable' => array(
            'ignore' => array(
                'risk_score',
                'residual_risk',
                'created',
                'modified',
                'SecurityPolicy',
                'SecurityPolicyIncident',
                'SecurityIncident',
            )
        ),
        'Utils.SoftDelete',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'impact', 'threats', 'vulnerabilities', 'residual_score', 'residual_risk', 'review', 'risk_mitigation_strategy_id',
                'description'
			)
		),
		'RiskManager',
		'RiskCalculationManager',
        'CustomFields.CustomFields',
        'Taggable',
        'ObjectStatus.ObjectStatus'
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'BusinessUnit' => array(
			'rule' => array('multiple', array('min' => 1))
		),
		/*'Threat' => array(
			'rule' => array('multiple', array('min' => 1))
		),
		'Vulnerability' => array(
			'rule' => array('multiple', array('min' => 1))
		),*/
		'risk_mitigation_strategy_id' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		/*'BusinessContinuityPlan' => array(
			'rule' => array('multiple', array('min' => 1))
		),*/
		'residual_score' => array(
			'rule' => 'numeric',
			'required' => true
		),
		// 'RiskException' => array(
		// 	'rule' => array('multiple', array('min' => 1))
		// ),
		// 'review' => array(
		// 	'rule' => 'date',
		// 	'required' => true
		// )
	);

	public $belongsTo = array(
		'RiskMitigationStrategy'
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'BusinessContinuity'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'BusinessContinuity'
			)
		),
		'Review' => array(
			'className' => 'Review',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Review.model' => 'BusinessContinuity'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'BusinessContinuity'
			)
		),
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Tag.model' => 'BusinessContinuity'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'BusinessUnit',
		'Process' => array(
			'with' => 'BusinessContinuitiesProcess',
			'foreignKey' => 'business_continuity_id'
		),
		'Threat',
		'Vulnerability',
		'SecurityService',
		'BusinessContinuityPlan',
		'RiskException',
		'RiskClassification' => [
			'className' => 'RiskClassification',
			'with' => 'BusinessContinuitiesRiskClassification',
			'joinTable' => 'business_continuities_risk_classifications',
			'foreignKey' => 'business_continuity_id',
			'associationForeignKey' => 'risk_classification_id',
			'conditions' => [
				'BusinessContinuitiesRiskClassification.type' => RiskClassification::TYPE_ANALYSIS
			]
		],
		'RiskClassificationTreatment' => [
			'className' => 'RiskClassification',
			'with' => 'BusinessContinuitiesRiskClassification',
			'joinTable' => 'business_continuities_risk_classifications',
			'foreignKey' => 'business_continuity_id',
			'associationForeignKey' => 'risk_classification_id',
			'conditions' => [
				'BusinessContinuitiesRiskClassification.type' => RiskClassification::TYPE_TREATMENT
			]
		],
		'ComplianceManagement',
		'Project' => array(
			'with' => 'BusinessContinuitiesProjects'
		),
		'SecurityPolicy' => [
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'business-risk',
			)
		],
		'SecurityPolicyTreatment' => array(
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'business-risk',
				'RisksSecurityPolicy.type' => RISKS_SECURITY_POLICIES_TREATMENT
			)
		),
		'SecurityPolicyIncident' => array(
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'business-risk',
				'RisksSecurityPolicy.type' => RISKS_SECURITY_POLICIES_INCIDENT
			)
		),
		'SecurityIncident' => array(
			'with' => 'RisksSecurityIncident',
			'joinTable' => 'risks_security_incidents',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_incident_id',
			'conditions' => array(
				'RisksSecurityIncident.risk_type' => 'business-risk'
			)
		),
		'DataAsset' => array(
			'with' => 'DataAssetsRisk',
			'joinTable' => 'data_assets_risks',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'data_asset_id',
			'conditions' => array(
				'DataAssetsRisk.model' => 'BusinessContinuity'
			)
		),
		'RiskAppetiteThresholdAnalysis' => [
			'className' => 'RiskAppetiteThreshold',
			'with' => 'RiskAppetiteThresholdsRisk',
			'joinTable' => 'risk_appetite_thresholds_risks',
			'foreignKey' => 'foreign_key',
			'associationForeignKey' => 'risk_appetite_threshold_id',
			'conditions' => [
				'RiskAppetiteThresholdsRisk.type' => RiskClassification::TYPE_ANALYSIS,
				'RiskAppetiteThresholdsRisk.model' => 'BusinessContinuity',
			]
		],
		'RiskAppetiteThresholdTreatment' => [
			'className' => 'RiskAppetiteThreshold',
			'with' => 'RiskAppetiteThresholdsRisk',
			'joinTable' => 'risk_appetite_thresholds_risks',
			'foreignKey' => 'foreign_key',
			'associationForeignKey' => 'risk_appetite_threshold_id',
			'conditions' => [
				'RiskAppetiteThresholdsRisk.type' => RiskClassification::TYPE_TREATMENT,
				'RiskAppetiteThresholdsRisk.model' => 'BusinessContinuity',
			]
		],
	);

	public function __construct($id = false, $table = null, $ds = null) {
        $this->label = __('Business Impact Analysis');
        $this->_group = parent::SECTION_GROUP_RISK_MGT;

        $this->fieldGroupData = array(
            'default' => array(
                'label' => __('General')
            ),
            'analysis' => array(
                'label' => __('Analysis')
            ),
            'treatment' => array(
                'label' => __('Treatment')
            ),
            'incident-containment' => array(
                'label' => __('Incident Containment')
            ),
        );

        $this->fieldData = array(
            'BusinessUnit' => array(
                'label' => __('Applicable Business Units'),
                'group' => 'analysis',
                'editable' => true
            ),
            'Process' => array(
                'label' => __('Processes'),
                'group' => 'analysis',
                'editable' => true
            ),
            'impact' => array(
                'label' => __('Business Impact'),
                'group' => 'analysis',
                'editable' => true
            ),
            'BusinessContinuityPlan' => array(
                'label' => __('Business Continuity Plans'),
                'group' => 'treatment',
                'editable' => true
            ),
            'plans_issues' => array(
                'label' => __('Plans Issues'),
                'type' => 'toggle',
                'hidden' => true
            ),
        );

        //
        // Init helper Lib for UserFields Module
        $UserFields = new UserFields();
        //

		$this->notificationSystem = array(
			'macros' => array(
				'RISK_ID' => array(
					'field' => 'BusinessContinuity.id',
					'name' => __('Risk ID')
				),
				'RISK_NAME' => array(
					'field' => 'BusinessContinuity.title',
					'name' => __('Risk Name')
				),
				'RISK_OWNER' => $UserFields->getNotificationSystemData('Owner', [
					'name' => __('Risk Owner')
				]),
				'RISK_STAKEHOLDER' => $UserFields->getNotificationSystemData('Stakeholder', [
					'name' => __('Risk Stakeholder')
				]),
				'RISK_SCORE' => array(
					'field' => 'BusinessContinuity.risk_score',
					'name' => __('Risk Score')
				),
				'RISK_RESIDUAL' => array(
					'field' => 'BusinessContinuity.residual_risk',
					'name' => __('Risk Residual Score')
				),
				'RISK_STRATEGY' => array(
					'field' => 'RiskMitigationStrategy.name',
					'name' => __('Risk Mitigation Strategy')
				),
			),
			'customEmail' =>  true
		);

		parent::__construct($id, $table, $ds);

		// $this->filterArgs = array(
		// 	'user_id' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Owner')
		// 	),
		// 	'search' => array(
		// 		'type' => 'like',
		// 		'_name' => __('Search'),
		// 		'field' => array('BusinessContinuity.id', 'BusinessContinuity.title', 'BusinessContinuity.impact', 'BusinessContinuity.threats',  'BusinessContinuity.vulnerabilities')
		// 	),
		// 	'bu_id' => array(
		// 	  	'type' => 'subquery',
	 //            'method' => 'findByBusinessUnit',
	 //            'field' => 'BusinessContinuity.id',
		// 		'_name' => __('Business Unit')
		// 	),
		// 	'business_unit_search' => array(
		// 		'type' => 'like',
		// 		'field' => array('BusinessUnit.id', 'BusinessUnit.name'),
		// 		'_name' => __('Search')
		// 	),
		// 	'guardian_id' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Stakeholder')
		// 	),
		// 	'expired' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Risk Review Expired')
		// 	),
		// 	'expired_reviews' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Expired Reviews')
		// 	),
		// 	'risk_above_appetite' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Risk Above Appetite')
		// 	),
		// 	/*'exceptions_issues' => array(
		// 		'type' => 'value',
		// 		'_name' => 'Exceptions with Issues'
		// 	),
		// 	'controls_issues' => array(
		// 		'type' => 'query',
		// 		'method' => 'controlsIssueConditions',
		// 		'_name' => 'Controls with Issues'
		// 	),
		// 	'plans_issues' => array(
		// 		'type' => 'query',
		// 		'method' => 'plansIssueConditions',
		// 		'_name' => 'Plans with Issues'
		// 	),
		// 	'residual_risk' => array(
		// 		'type' => 'query',
		// 		'method' => 'residualRiskConditions',
		// 		'_name' => 'Risk above appetite'
		// 	)*/
		// );

		$dvancedFilter = array(
        	__('Risk Analysis') => array(
        		'risk_classification_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id'
                    ),
                ),
                'asset_id' => null,
                'asset_business_unit_id' => null,
                'business_unit_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Applicable Business Units'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'BusinessUnit.id',
                        'field' => 'BusinessContinuity.id',
                    ),
                    'data' => array(
                        'method' => 'getBusinessUnits',
                    ),
                    'many' => true,
                    'contain' => array(
                        'BusinessUnit' => array(
                            'name'
                        )
                    ),
                ),
                'process_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Processes'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Process.id',
                        'field' => 'BusinessContinuity.id'
                    ),
                    'data' => array(
                        'method' => 'getProcesses',
                    ),
                    'many' => true,
                    'contain' => array(
                        'Process' => array(
                            'name'
                        )
                    ),
                ),
                'process_rpd' => array(
                    'type' => 'number',
                    'comparison' => true,
                    'name' => __('Revenue per Day'),
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Process.rpd',
                        'field' => 'BusinessContinuity.id',
                    ),
                    'many' => true,
                    'contain' => array(
                        'Process' => array(
                            'rpd'
                        )
                    ),
                ),
                'process_rto' => array(
                    'type' => 'number',
                    'comparison' => true,
                    'name' => __('Minimum RTO'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Process.rto',
                        'field' => 'BusinessContinuity.id',
                    ),
                    'many' => true,
                    'contain' => array(
                        'Process' => array(
                            'rto'
                        )
                    ),
                ),
                'process_rpo' => array(
                    'type' => 'number',
                    'comparison' => true,
                    'name' => __('Minimum MTO'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Process.rpo',
                        'field' => 'BusinessContinuity.id',
                    ),
                    'many' => true,
                    'contain' => array(
                        'Process' => array(
                            'rpo'
                        )
                    ),
                ),
                'threat_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    ),
                ),
                'vulnerability_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    ),
                ),
    		),
    		__('Risk Treatment') => array(
    			'risk_exception_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id'
                    ),
                ),
                'security_service_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id'
                    ),
                ),
                'project_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id'
                    ),
                ),
                'security_policy_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id'
                    ),
                ),
                'risk_score' => array(
                    'show_default' => false,
                ),
                'residual_risk' => array(
                    'show_default' => false,
                ),
                'business_continuity_plan_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Mitigating Business Continuity Plans'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'BusinessContinuityPlan.id',
                        'field' => 'BusinessContinuity.id'
                    ),
                    'data' => array(
                        'method' => 'getBusinessContinuityPlans',
                    ),
                    'many' => true,
                    'contain' => array(
                        'BusinessContinuityPlan' => array(
                            'title'
                        )
                    ),
                ),
			),
			__('Related Status') => array(
                'project_expired' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                // 'security_incident_ongoing_incident' => array(
                //     'filter' => array(
                //         'field' => 'ThirdPartyRisk.id',
                //     )
                // ),
                'risk_exception_expired' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                'security_service_audits_last_passed' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                'security_service_audits_last_missing' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                'security_service_maintenances_last_missing' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                'security_service_ongoing_corrective_actions' => array(
                   'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                'security_service_security_service_type_id' => array(
                    'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                'security_service_control_with_issues' => array(
                   'filter' => array(
                        'field' => 'BusinessContinuity.id',
                    )
                ),
                'asset_expired_reviews' => null,
            )
    	);

    	$this->mergeAdvancedFilterFields($dvancedFilter);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Business Risks'),
			'pdf_file_name' => __('business_risks'),
			'csv_file_name' => __('business_risks'),
            'additional_actions' => array(
                'BusinessContinuityReview' => array(
                    'label' => __('Reviews'),
                    'url' => array(
                        'controller' => 'reviews',
                        'action' => 'filterIndex',
                        'BusinessContinuityReview',
                        '?' => array(
                            'advanced_filter' => 1
                        )
                    )
                ),
            ),
            'history' => true,
            'bulk_actions' => true,
            'trash' => true,
            'view_item' => AppIndexCrudAction::VIEW_ITEM_QUERY,
            'use_new_filters' => true,
            'add' => true
		);

		$this->initAdvancedFilter();

		$this->mapping['statusManager'] = array(
			'expiredReviews' => $this->getStatusConfig('expiredReviews'),
			'riskAboveAppetite' => $this->getStatusConfig('riskAboveAppetite')
		);
	}

	public function getObjectStatusConfig() {
        return parent::getObjectStatusConfig() + [
            'expired_reviews' => [
                'title' => __('Risk Review Expired'),
                'callback' => [$this, '_statusExpiredReviews'],
                'trigger' => [
                    [
                        'model' => $this->DataAsset,
                        'trigger' => 'ObjectStatus.trigger.risks_with_missing_reviews'
                    ],
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.business_continuity_expired_reviews'
                    ],
                ]
            ],
            'risk_above_appetite' => [
                'title' => __('Risk Above Appetite'),
                'callback' => [$this, '_statusRiskAboveAppetite'],
                'trigger' => [
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.business_continuity_risk_above_appetite'
                    ],
                ]
            ],
        ];
    }

	public function statusExceptionsIssues() {
		$data = $this->RiskException->find('count', [
			'conditions' => [
				'RiskException.expired' => 1,
				'BusinessContinuitiesRiskException.business_continuity_id' => $this->id
			],
			'joins' => [
                [
                    'table' => 'business_continuities_risk_exceptions',
                    'alias' => 'BusinessContinuitiesRiskException',
                    'type' => 'INNER',
                    'conditions' => [
                        'BusinessContinuitiesRiskException.risk_exception_id = RiskException.id',
                    ]
                ],
            ],
			'recursive' => -1
		]);

		return (boolean) $data;
    }

	public function beforeSave($options = array()) {
		$ret = true;

		if (isset($this->data['BusinessContinuity']['RiskClassificationTreatment']) && isset($this->data['BusinessContinuity']['Asset'])) {
			if ($this->getMethod() != 'magerit') {
				$this->data['BusinessContinuity']['RiskClassificationTreatment'] = array_filter($this->data['BusinessContinuity']['RiskClassificationTreatment']);
			}
		}

		$ret &= parent::beforeSave($options);

        $this->transformDataToHabtm(array('BusinessUnit', 'Asset', 'Threat', 'Vulnerability', 'SecurityService', 
            'RiskException', 'Project', 'Process', 'SecurityPolicyTreatment', 'SecurityPolicyIncident', 'RiskClassification', 'RiskClassificationTreatment', 'BusinessContinuityPlan'
        ));

        $this->setHabtmConditionsToData(array('SecurityPolicyTreatment', 'SecurityPolicyIncident'));

		if (isset($this->data['BusinessContinuity']['risk_score']) && is_numeric($this->data['BusinessContinuity']['risk_score'])) {
			$this->data['BusinessContinuity']['risk_score'] = CakeNumber::precision($this->data['BusinessContinuity']['risk_score'], 2);
		}

		return $ret;
	}

	public function afterSave($created, $options = array()) {
        parent::afterSave($created, $options);

        if (!empty($this->id) && $this->exists()) {
        	$this->saveRiskScoreWrapper($this->id);
        }
	}

	/**
	 * Get relevant classification for this section.
	 */
	public function getFormClassifications()
	{
		return $this->getDefaultFormClassifications();
	}

	public function findByBusinessUnit($data = array()) {
        $this->BusinessContinuitiesBusinessUnit->Behaviors->attach('Containable', array(
                'autoFields' => false
            )
        );
        $this->BusinessContinuitiesBusinessUnit->Behaviors->attach('Search.Searchable');

        $query = $this->BusinessContinuitiesBusinessUnit->getQuery('all', array(
            'conditions' => array(
                'BusinessContinuitiesBusinessUnit.business_unit_id' => $data['bu_id']
            ),
            'fields' => array(
                'BusinessContinuitiesBusinessUnit.business_continuity_id'
            )
        ));

        return $query;
    }

	public function getControlsWithIssues($riskId, $find = 'list') {
		$this->BusinessContinuitiesSecurityService->bindModel(array(
			'belongsTo' => array('SecurityService')
		));

		$ids = $this->BusinessContinuitiesSecurityService->find('list', array(
			'conditions' => array(
				'BusinessContinuitiesSecurityService.business_continuity_id' => $riskId
			),
			'fields' => array('SecurityService.id'),
			'recursive' => 0
		));

		$issues = $this->SecurityService->getIssues($ids, $find);

		return $issues;
	}

	public function controlsIssuesMsgParams() {
		if (isset($this->data['BusinessContinuity']['id'])) {
			$issues = $this->getControlsWithIssues($this->data['BusinessContinuity']['id']);
		}
		elseif (isset($this->id)) {
			$issues = $this->getControlsWithIssues($this->id);
		}

		if (!empty($issues)) {
			return implode(', ', $issues);
		}
	}

    /**
     * @deprecated
     */
	public function saveExceptionIssues($riskId) {
		if (is_array($riskId)) {
			$ret = true;
			foreach ($riskId as $id) {
				$ret &= $this->saveExceptionIssues($id);
			}

			return $ret;
		}

		$issues = $this->getExceptionWithIssues($riskId);

		if (empty($issues)) {
			$hasIssues = '0';
		}
		else {
			$hasIssues = '1';
		}

		$saveData = array('exceptions_issues' => (string) (int) $hasIssues);

		$this->id = $riskId;
		return (bool) $this->save($saveData, array('validate' => false, 'callbacks' => 'before'));
	}

	public function getExceptionWithIssues($riskId, $find = 'list') {
		$this->BusinessContinuitiesRiskException->bindModel(array(
			'belongsTo' => array('RiskException')
		));

		$issues = $this->BusinessContinuitiesRiskException->find($find, array(
			'conditions' => array(
				'BusinessContinuitiesRiskException.business_continuity_id' => $riskId,
				'RiskException.expired' => 1
			),
			'fields' => array('RiskException.id', 'RiskException.title'),
			'recursive' => 0
		));

		return $issues;
	}

	public function exceptionsIssuesMsgParams() {

		if (isset($this->data['BusinessContinuity']['id'])) {
			$issues = $this->getExceptionWithIssues($this->data['BusinessContinuity']['id']);
		}
		elseif (isset($this->id)) {
			$issues = $this->getExceptionWithIssues($this->id);
		}

		if (!empty($issues)) {
			return implode(', ', $issues);
		}
	}

	public function queryControlsIssues() {
		if ($this->id != null && !isset($this->data['BusinessContinuity']['controls_issues'])) {

			if (!isset($this->data['BusinessContinuity']['business_continuity_plan_id'])) {
				$this->BusinessContinuitiesBusinessContinuityPlan->bindModel(array(
					'belongsTo' => array('BusinessContinuityPlan')
				));

				$ids = $this->BusinessContinuitiesBusinessContinuityPlan->find('list', array(
					'conditions' => array(
						'BusinessContinuitiesBusinessContinuityPlan.business_continuity_id' => $this->id
					),
					'fields' => array('BusinessContinuityPlan.id'),
					'recursive' => 0
				));

				$this->data['BusinessContinuity']['business_continuity_plan_id'] = $ids;
			}

			$data = $this->BusinessContinuityPlan->find('list', array(
				'conditions' => array(
					'OR' => array(
						array(
							'BusinessContinuityPlan.id' => $this->data['BusinessContinuity']['business_continuity_plan_id'],
							'BusinessContinuityPlan.audits_all_done' => 0
						),
						array(
							'BusinessContinuityPlan.id' => $this->data['BusinessContinuity']['business_continuity_plan_id'],
							'BusinessContinuityPlan.audits_last_passed' => 0
						)
					)
				),
				'fields' => array('BusinessContinuityPlan.id', 'BusinessContinuityPlan.title'),
				'recursive' => 0
			));

			if (!empty($data)) {
				$this->data['BusinessContinuity']['controls_issues'] = '1';

				return array(implode(', ', $data));
			}
			else {
				$this->data['BusinessContinuity']['controls_issues'] = '0';
			}
		}

		return null;
	}

	/**
	 * Append expired field to the query calculated from review date field.
	 */
	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'review');
	}

	/**
	 * Calculate Risk Score for a Risk from given classification values.
	 * @return int Risk Score.
	 */
	public function calculateRiskScore($classification_ids = array(), $bu_ids = array()) {
		$this->resetCalculationClass();
		
		return $this->calculateByMethod(array(
			'classification_ids' => $classification_ids,
			'bu_ids' => $bu_ids,
		));
	}

	/**
	 * Calculate Business Continuity Risk Score from given classification values.
	 * @return int Risk Score.
	 */
	public function ErambaCalculation($classification_ids = array(), $bu_ids = array()) {
		//$classification_ids = !empty($classification_ids) ? $classification_ids : $this->request->data['BusinessContinuity']['risk_classification_id'];
		if ( empty( $classification_ids ) ) {
			return 0;
		}

		$classifications = $this->RiskClassification->find('all', array(
			'conditions' => array(
				'RiskClassification.id' => $classification_ids
			),
			'fields' => array( 'id', 'value' ),
			'recursive' => -1
		));

		//$bu_ids = !empty($bu_ids) ? $bu_ids : $this->request->data['BusinessContinuity']['business_unit_id'];
		$business_units = $this->BusinessUnit->find('all', array(
			'conditions' => array(
				'BusinessUnit.id' => $bu_ids
			),
			'fields' => array( 'id' ),
			'contain' => array(
				'Legal' => array(
					'fields' => array( 'id', 'risk_magnifier' )
				)
			),
			'recursive' => 0
		));

		return array($classifications, $business_units);

		/*$classification_sum = 0;
		foreach ( $classifications as $classification ) {
			$classification_sum += $classification['RiskClassification']['value'];
		}

		$bu_sum = 0;
		foreach ( $business_units as $bu ) {
			foreach ($bu['Legal'] as $legal) {
				$bu_sum += $legal['risk_magnifier'];
			}
		}

		if ( $bu_sum ) {
			return $classification_sum * $bu_sum;
		}

		return $classification_sum;*/
	}

	/**
	 * Calculate risk score and save to database.
	 */
	public function calculateAndSaveRiskScoreById($Ids) {
		$bc = $this->find('all', array(
			'conditions' => array(
				'BusinessContinuity.id' => $Ids
			),
			'contain' => array(
				'BusinessUnit' => array(
					'fields' => array('id')
				),
				'RiskClassification' => array(
					'fields' => array('id')
				)
			)
		));

		$ret = true;
		foreach ($bc as $risk) {
			$classificationIds = array();
			foreach ($risk['RiskClassification'] as $c) {
				$classificationIds[] = $c['id'];
			}

			$buIds = array();
			foreach ($risk['BusinessUnit'] as $b) {
				$buIds[] = $b['id'];
			}

			$riskScore = $this->calculateRiskScore($classificationIds, $buIds);
			if (!is_numeric($riskScore)) {
				return false;
			}
			$residualRisk = getResidualRisk($risk['BusinessContinuity']['residual_score'], $riskScore);

			$saveData = array(
				'risk_score' => $riskScore,
				'residual_risk' => $residualRisk
			);

			$this->id = $risk['BusinessContinuity']['id'];
			$oldRiskScore = $this->field('risk_score');

			$ret &= (bool) $this->save($saveData, false);
			$ret &= $this->triggerStatus('riskAboveAppetite');

			if (!empty($this->logAfterRiskScoreChange)) {
				$msg = $this->logAfterRiskScoreChange['message'];
				$args = $this->logAfterRiskScoreChange['args'];
				$args[] = $oldRiskScore;
				$args[] = $riskScore;

				array_unshift($args, $msg);
				$msg = call_user_func_array('sprintf', $args);
				$ret &= $this->quickLogSave($risk['BusinessContinuity']['id'], 2, $msg);
			}
		}

		return $ret;
	}

	public function controlsIssueConditions($data = array()){
		$conditions = array();
		if($data['controls_issues'] == 1){
			$conditions = array(
				'BusinessContinuity.controls_issues >' => 0
			);
		}
		elseif($data['controls_issues'] == 0){
			$conditions = array(
				'BusinessContinuity.controls_issues' => 0
			);
		}

		return $conditions;
	}

	public function residualRiskConditions($data = array()){
		$conditions = array();
		if($data['residual_risk'] == 1){
			$conditions = array(
				'BusinessContinuity.residual_risk >' => RISK_APPETITE
			);
		}
		elseif($data['residual_risk'] == 0){
			$conditions = array(
				'BusinessContinuity.residual_risk <=' => RISK_APPETITE
			);
		}

		return $conditions;
	}

	public function getBusinessUnits() {
		$data = $this->BusinessUnit->find('list', array(
            'order' => array('BusinessUnit.name' => 'ASC'),
            'fields' => array('BusinessUnit.id', 'BusinessUnit.name'),
            'recursive' => -1
        ));
        return $data;
	}

	public function getProcesses() {
		$data = $this->Process->find('list', array(
            'order' => array('Process.name' => 'ASC'),
            'fields' => array('Process.id', 'Process.name'),
            'recursive' => -1
        ));
        return $data;
	}

	public function getBusinessContinuityPlans() {
		$data = $this->BusinessContinuityPlan->find('list', array(
            'order' => array('BusinessContinuityPlan.title' => 'ASC'),
            'fields' => array('BusinessContinuityPlan.id', 'BusinessContinuityPlan.title'),
            'recursive' => -1
        ));
        return $data;
	}
}
