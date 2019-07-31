<?php
App::uses('BaseRisk', 'Model');
App::uses('RiskClassification', 'Model');

class ThirdPartyRisk extends BaseRisk {
	public $displayField = 'title';
	public $scoreAssocModel = 'ThirdParty';

	public $mapping = array(
		'titleColumn' => 'title',
		'notificationSystem' => array('index'),
		'logRecords' => true,
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
				'title', 'shared_information', 'controlled', 'threats', 'vulnerabilities', 'residual_score', 'risk_score', 'residual_risk', 'review', 'risk_mitigation_strategy_id',
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
		'ThirdParty' => array(
			'rule' => array( 'multiple', array( 'min' => 1 ) )
		),
		'Asset' => array(
			'rule' => array( 'multiple', array( 'min' => 1 ) )
		),
		/*'Threat' => array(
			'rule' => array( 'multiple', array( 'min' => 1 ) )
		),
		'Vulnerability' => array(
			'rule' => array( 'multiple', array( 'min' => 1 ) )
		),*/
		'risk_mitigation_strategy_id' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		// 'SecurityService' => array(
		// 	'rule' => array( 'multiple', array( 'min' => 1 ) )
		// ),
		'residual_score' => array(
			'rule' => 'numeric',
			'required' => true
		),
		// 'RiskException' => array(
		// 	'rule' => array( 'multiple', array( 'min' => 1 ) )
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
				'Comment.model' => 'ThirdPartyRisk'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ThirdPartyRisk'
			)
		),
		'Review' => array(
			'className' => 'Review',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Review.model' => 'ThirdPartyRisk'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ThirdPartyRisk'
			)
		),
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Tag.model' => 'ThirdPartyRisk'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'ThirdParty',
		'Asset',
		'Threat',
		'Vulnerability',
		'SecurityService',
		'RiskException',
		'RiskClassification' => [
			'className' => 'RiskClassification',
			'with' => 'RiskClassificationsThirdPartyRisk',
			'joinTable' => 'risk_classifications_third_party_risks',
			'foreignKey' => 'third_party_risk_id',
			'associationForeignKey' => 'risk_classification_id',
			'conditions' => [
				'RiskClassificationsThirdPartyRisk.type' => RiskClassification::TYPE_ANALYSIS
			]
		],
		'RiskClassificationTreatment' => [
			'className' => 'RiskClassification',
			'with' => 'RiskClassificationsThirdPartyRisk',
			'joinTable' => 'risk_classifications_third_party_risks',
			'foreignKey' => 'third_party_risk_id',
			'associationForeignKey' => 'risk_classification_id',
			'conditions' => [
				'RiskClassificationsThirdPartyRisk.type' => RiskClassification::TYPE_TREATMENT
			]
		],
		'ComplianceManagement',
		'Project' => array(
			'with' => 'ProjectsThirdPartyRisk'
		),
		'SecurityPolicy' => [
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'third-party-risk',
			)
		],
		'SecurityPolicyTreatment' => array(
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'third-party-risk',
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
				'RisksSecurityPolicy.risk_type' => 'third-party-risk',
				'RisksSecurityPolicy.type' => RISKS_SECURITY_POLICIES_INCIDENT
			)
		),
		'SecurityIncident' => array(
			'with' => 'RisksSecurityIncident',
			'joinTable' => 'risks_security_incidents',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_incident_id',
			'conditions' => array(
				'RisksSecurityIncident.risk_type' => 'third-party-risk'
			)
		),
		'DataAsset' => array(
			'with' => 'DataAssetsRisk',
			'joinTable' => 'data_assets_risks',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'data_asset_id',
			'conditions' => array(
				'DataAssetsRisk.model' => 'ThirdPartyRisk'
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
				'RiskAppetiteThresholdsRisk.model' => 'ThirdPartyRisk',
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
				'RiskAppetiteThresholdsRisk.model' => 'ThirdPartyRisk',
			]
		],
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
        $this->label = __('Third Party Risk Management');
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
            'ThirdParty' => array(
                'label' => __('Applicable Third Parties'),
                'group' => 'analysis',
                'editable' => true
            ),
            'Asset' => array(
                'label' => __('Applicable Assets'),
                'group' => 'analysis',
                'editable' => true
            ),
            'shared_information' => array(
                'label' => __('Why is Information shared with these Third Parties'),
                'group' => 'analysis',
                'editable' => true
            ),
            'controlled' => array(
                'label' => __('How it will be Controlled?'),
                'group' => 'analysis',
                'editable' => true
            ),
        );

		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//

		$this->notificationSystem = array(
			'macros' => array(
				'RISK_ID' => array(
					'field' => 'ThirdPartyRisk.id',
					'name' => __('Risk ID')
				),
				'RISK_NAME' => array(
					'field' => 'ThirdPartyRisk.title',
					'name' => __('Risk Name')
				),
				'RISK_OWNER' => $UserFields->getNotificationSystemData('Owner', [
					'name' => __('Risk Owner')
				]),
				'RISK_STAKEHOLDER' => $UserFields->getNotificationSystemData('Stakeholder', [
					'name' => __('Risk Stakeholder')
				]),
				'RISK_SCORE' => array(
					'field' => 'ThirdPartyRisk.risk_score',
					'name' => __('Risk Score')
				),
				'RISK_RESIDUAL' => array(
					'field' => 'ThirdPartyRisk.residual_risk',
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

		$dvancedFilter = array(
        	__('Risk Analysis') => array(
        		'risk_classification_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id'
                    ),
                ),
                'asset_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id'
                    ),
                ),
                'asset_business_unit_id' => null,
                'third_party_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Applicable Third Parties'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'ThirdParty.id',
                        'field' => 'ThirdPartyRisk.id',
                    ),
                    'data' => array(
                        'method' => 'getThirdParties',
                    ),
                    'many' => true,
                    'contain' => array(
                        'ThirdParty' => array(
                            'name'
                        )
                    ),
                ),
                'threat_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    ),
                ),
                'vulnerability_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    ),
                ),
    		),
    		__('Risk Treatment') => array(
    			'risk_exception_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id'
                    ),
                ),
                'security_service_id' => array(
                    'filter' => array(
                        'findByModel' => 'SecurityService',
                        'field' => 'ThirdPartyRisk.id'
                    ),
                ),
                'project_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id'
                    ),
                ),
                'security_policy_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id'
                    ),
                ),
			),
			__('Related Status') => array(
                'project_expired' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'security_incident_ongoing_incident' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'risk_exception_expired' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'security_service_audits_last_passed' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'security_service_audits_last_missing' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'security_service_maintenances_last_missing' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'security_service_ongoing_corrective_actions' => array(
                   'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'security_service_security_service_type_id' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'security_service_control_with_issues' => array(
                   'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
                'asset_expired_reviews' => array(
                    'filter' => array(
                        'field' => 'ThirdPartyRisk.id',
                    )
                ),
            )
    	);

    	$this->mergeAdvancedFilterFields($dvancedFilter);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Third Party Risks'),
			'pdf_file_name' => __('third_party_risks'),
			'csv_file_name' => __('third_party_risks'),
            'additional_actions' => array(
                'ThirdPartyRiskReview' => array(
                    'label' => __('Reviews'),
                    'url' => array(
                        'controller' => 'reviews',
                        'action' => 'filterIndex',
                        'ThirdPartyRiskReview',
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

		// $this->filterArgs = array(
		// 	'user_id' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Owner')
		// 	),
		// 	'search' => array(
		// 		'type' => 'like',
		// 		'field' => array('ThirdPartyRisk.id', 'ThirdPartyRisk.title', 'ThirdPartyRisk.shared_information', 'ThirdPartyRisk.controlled'),
		// 		'_name' => __('Search')
		// 	),
		// 	'tp_id' => array(
		// 	  	'type' => 'subquery',
	 //            'method' => 'findByThirdParty',
	 //            'field' => 'ThirdPartyRisk.id',
	 //            '_name' => __('Third Party')
		// 	),
		// 	'third_party_id' => array(
		// 		'type' => 'value',
		// 		'field' => 'ThirdParty.id',
		// 		 '_name' => __('Third Party')
		// 	),
		// 	'third_party_search' => array(
		// 		'type' => 'like',
		// 		'field' => array('ThirdParty.id', 'ThirdParty.name'),
		// 		'_name' => __('Search')
		// 	),
		// 	'guardian_id' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Stakeholder')
		// 	),
		// 	'expired_reviews' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Expired Reviews')
		// 	),
		// 	'risk_above_appetite' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Risk Above Appetite')
		// 	),
		// 	/*'expired' => array(
		// 		'type' => 'value',
		// 		'_name' => 'Risk Review Expired'
		// 	),
		// 	'exceptions_issues' => array(
		// 		'type' => 'value',
		// 		'_name' => 'Exceptions with Issues'
		// 	),
		// 	'controls_issues' => array(
		// 		'type' => 'query',
		// 		'method' => 'controlsIssueConditions',
		// 		'_name' => 'Controls with Issues'
		// 	),
		// 	'residual_risk' => array(
		// 		'type' => 'query',
		// 		'method' => 'residualRiskConditions',
		// 		'_name' => 'Risk above appetite'
		// 	)*/
		// );
		
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
                        'trigger' => 'ObjectStatus.trigger.third_party_risk_expired_reviews'
                    ],
                ]
            ],
            'risk_above_appetite' => [
                'title' => __('Risk Above Appetite'),
                'callback' => [$this, '_statusRiskAboveAppetite'],
                'trigger' => [
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.third_party_risk_risk_above_appetite'
                    ],
                ]
            ],
        ];
    }

	public function statusExceptionsIssues() {
		$data = $this->RiskException->find('count', [
			'conditions' => [
				'RiskException.expired' => 1,
				'RiskExceptionsThirdPartyRisk.third_party_risk_id' => $this->id
			],
			'joins' => [
                [
                    'table' => 'risk_exceptions_third_party_risks',
                    'alias' => 'RiskExceptionsThirdPartyRisk',
                    'type' => 'INNER',
                    'conditions' => [
                        'RiskExceptionsThirdPartyRisk.risk_exception_id = RiskException.id',
                    ]
                ],
            ],
			'recursive' => -1
		]);

		return (boolean) $data;
    }

	public function beforeSave($options = array()) {
		$ret = true;

		if (isset($this->data['ThirdPartyRisk']['RiskClassificationTreatment']) && isset($this->data['ThirdPartyRisk']['Asset'])) {
			if ($this->getMethod() != 'magerit') {
				$this->data['ThirdPartyRisk']['RiskClassificationTreatment'] = array_filter($this->data['ThirdPartyRisk']['RiskClassificationTreatment']);
			}
		}

		$ret &= parent::beforeSave($options);

        $this->transformDataToHabtm(array('ThirdParty', 'Asset', 'Threat', 'Vulnerability', 'SecurityService', 
            'RiskException', 'Project', 'SecurityPolicyIncident', 'SecurityPolicyTreatment', 'RiskClassification', 'RiskClassificationTreatment'
        ));

        $this->setHabtmConditionsToData(array('SecurityPolicyIncident', 'SecurityPolicyTreatment'));

		if (isset($this->data['ThirdPartyRisk']['risk_score']) && is_numeric($this->data['ThirdPartyRisk']['risk_score'])) {
			$this->data['ThirdPartyRisk']['risk_score'] = CakeNumber::precision($this->data['ThirdPartyRisk']['risk_score'], 2);
			$math = $this->getCalculationMath();
			if (!is_null($math)) {
				// $this->data['ThirdPartyRisk']['risk_score_formula'] = $math;
			}
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

	public function getControlsWithIssues($riskId, $find = 'list') {
		$this->SecurityServicesThirdPartyRisk->bindModel(array(
			'belongsTo' => array('SecurityService')
		));

		$ids = $this->SecurityServicesThirdPartyRisk->find('list', array(
			'conditions' => array(
				'SecurityServicesThirdPartyRisk.third_party_risk_id' => $riskId
			),
			'fields' => array('SecurityService.id'),
			'recursive' => 0
		));

		$issues = $this->SecurityService->getIssues($ids, $find);

		return $issues;
	}

	public function controlsIssuesMsgParams() {
		if (isset($this->data['ThirdPartyRisk']['id'])) {
			$issues = $this->getControlsWithIssues($this->data['ThirdPartyRisk']['id']);
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

	public function getThirdParties() {
		$data = $this->ThirdParty->find('list', array(
            'order' => array('ThirdParty.name' => 'ASC'),
            'fields' => array('ThirdParty.id', 'ThirdParty.name'),
            'recursive' => -1
        ));
        return $data;
	}

	public function getExceptionWithIssues($riskId, $find = 'list') {
		$this->RiskExceptionsThirdPartyRisk->bindModel(array(
			'belongsTo' => array('RiskException')
		));

		$issues = $this->RiskExceptionsThirdPartyRisk->find($find, array(
			'conditions' => array(
				'RiskExceptionsThirdPartyRisk.third_party_risk_id' => $riskId,
				'RiskException.expired' => 1
			),
			'fields' => array('RiskException.id', 'RiskException.title'),
			'recursive' => 0
		));

		return $issues;
	}

	public function exceptionsIssuesMsgParams() {

		if (isset($this->data['ThirdPartyRisk']['id'])) {
			$issues = $this->getExceptionWithIssues($this->data['ThirdPartyRisk']['id']);
		}
		elseif (isset($this->id)) {
			$issues = $this->getExceptionWithIssues($this->id);
		}

		if (!empty($issues)) {
			return implode(', ', $issues);
		}
	}

	public function queryControlsIssues() {
		if ($this->id != null && !isset($this->data['ThirdPartyRisk']['controls_issues'])) {

			if (!isset($this->data['ThirdPartyRisk']['security_service_id'])) {
				$this->SecurityServicesThirdPartyRisk->bindModel(array(
					'belongsTo' => array('SecurityService')
				));

				$ids = $this->SecurityServicesThirdPartyRisk->find('list', array(
					'conditions' => array(
						'SecurityServicesThirdPartyRisk.third_party_risk_id' => $this->id
					),
					'fields' => array('SecurityService.id'),
					'recursive' => 0
				));

				$this->data['ThirdPartyRisk']['security_service_id'] = $ids;
			}

			$data = $this->SecurityService->find('list', array(
				'conditions' => array(
					'OR' => array(
						array(
							'SecurityService.id' => $this->data['ThirdPartyRisk']['security_service_id'],
							'SecurityService.audits_all_done' => 0
						),
						array(
							'SecurityService.id' => $this->data['ThirdPartyRisk']['security_service_id'],
							'SecurityService.audits_last_passed' => 0
						)
					)
				),
				'fields' => array('SecurityService.id', 'SecurityService.name'),
				'recursive' => 0
			));

			if (!empty($data)) {
				$this->data['ThirdPartyRisk']['controls_issues'] = '1';

				return array(implode(', ', $data));
			}
			else {
				$this->data['ThirdPartyRisk']['controls_issues'] = '0';
			}
		}

		return null;
	}

	/**
	 * Callback used by Status Assessment to calculate expired field based on query data before saving and insert it into the query.
	 */
	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'review');
	}

	/**
	 * Calculate Risk Score for a Risk from given classification values.
	 * @return int Risk Score.
	 */
	public function calculateRiskScore($classification_ids = array(), $tp_ids = array()) {
		$this->resetCalculationClass();
		
		return $this->calculateByMethod(array(
			'classification_ids' => $classification_ids,
			'tp_ids' => $tp_ids,
		));
	}

	/**
	 * Calculate Risk Score for this Risk from given classification values.
	 * @return int Risk Score.
	 */
	public function ErambaCalculation($classification_ids = array(), $tp_ids = array()) {
		//$classification_ids = $this->request->data['ThirdPartyRisk']['risk_classification_id'];
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

		//$tp_ids = $this->request->data['ThirdPartyRisk']['third_party_id'];
		$tps = $this->ThirdParty->find('all', array(
			'conditions' => array(
				'ThirdParty.id' => $tp_ids
			),
			'fields' => array( 'id' ),
			'contain' => array(
				'Legal' => array(
					'fields' => array( 'id', 'risk_magnifier' )
				)
			)
		));

		return array($classifications, $tps);

		/*$classification_sum = 0;
		foreach ( $classifications as $classification ) {
			$classification_sum += $classification['RiskClassification']['value'];
		}

		$tp_sum = 0;
		foreach ( $tps as $tp ) {
			foreach ($tp['Legal'] as $legal) {
				$tp_sum += $legal['risk_magnifier'];
			}
		}

		if ( $tp_sum ) {
			return $classification_sum * $tp_sum;
		}

		return $classification_sum;*/
	}

	/**
	 * Calculate risk score and save to database.
	 */
	public function calculateAndSaveRiskScoreById($Ids) {
		$risks = $this->find('all', array(
			'conditions' => array(
				'ThirdPartyRisk.id' => $Ids
			),
			'contain' => array(
				'ThirdParty' => array(
					'fields' => array('id')
				),
				'RiskClassification' => array(
					'fields' => array('id')
				)
			)
		));

		$ret = true;
		foreach ($risks as $risk) {
			$classificationIds = array();
			foreach ($risk['RiskClassification'] as $c) {
				$classificationIds[] = $c['id'];
			}

			$tpIds = array();
			foreach ($risk['ThirdParty'] as $tp) {
				$tpIds[] = $tp['id'];
			}

			$riskScore = $this->calculateRiskScore($classificationIds, $tpIds);
			if (!is_numeric($riskScore)) {
				return false;
			}
			$residualRisk = getResidualRisk($risk['ThirdPartyRisk']['residual_score'], $riskScore);

			$saveData = array(
				'risk_score' => $riskScore,
				'residual_risk' => $residualRisk
			);

			$this->id = $risk['ThirdPartyRisk']['id'];
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
				$ret &= $this->quickLogSave($risk['ThirdPartyRisk']['id'], 2, $msg);
			}
		}

		return $ret;
	}

	public function controlsIssueConditions($data = array()){
		$conditions = array();
		if($data['controls_issues'] == 1){
			$conditions = array(
				'ThirdPartyRisk.controls_issues >' => 0
			);
		}
		elseif($data['controls_issues'] == 0){
			$conditions = array(
				'ThirdPartyRisk.controls_issues' => 0
			);
		}

		return $conditions;
	}

	public function residualRiskConditions($data = array()){
		$conditions = array();
		if($data['residual_risk'] == 1){
			$conditions = array(
				'ThirdPartyRisk.residual_risk >' => RISK_APPETITE
			);
		}
		elseif($data['residual_risk'] == 0){
			$conditions = array(
				'ThirdPartyRisk.residual_risk <=' => RISK_APPETITE
			);
		}

		return $conditions;
	}

	public function findByThirdParty($data = array()) {
        $this->ThirdPartiesThirdPartyRisk->Behaviors->attach('Containable', array(
                'autoFields' => false
            )
        );
        $this->ThirdPartiesThirdPartyRisk->Behaviors->attach('Search.Searchable');

        $query = $this->ThirdPartiesThirdPartyRisk->getQuery('all', array(
            'conditions' => array(
                'ThirdPartiesThirdPartyRisk.third_party_id' => $data['tp_id']
            ),
            'fields' => array(
                'ThirdPartiesThirdPartyRisk.third_party_risk_id'
            )
        ));

        return $query;
    }

    public function getThirdPartyIds($riskIds = array()) {
		$thirdPartyIds = $this->ThirdPartiesThirdPartyRisk->find('list', array(
			'conditions' => array(
				'ThirdPartiesThirdPartyRisk.third_party_risk_id' => $riskIds
			),
			'fields' => array(
				'ThirdPartiesThirdPartyRisk.third_party_id'
			)
		));

		return array_values($thirdPartyIds);
	}
}
