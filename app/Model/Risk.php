<?php
App::uses('BaseRisk', 'Model');
App::uses('RiskClassification', 'Model');
App::uses('CustomValidatorField', 'CustomValidator.Model');
App::uses('RiskCalculation', 'Model');

class Risk extends BaseRisk {
	public $displayField = 'title';
	public $scoreAssocModel = 'Asset';

	public $mapping = array(
		'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => true,
		'workflow' => false,
		/*'workflowValidate' => array(
			'Asset' => 'asset_id',
			'RiskException' => 'risk_exception_id'
		)*/
	);

	public $config = array(
		'actionList' => array(
			'notifications' => true
		)
	);

	public $actsAs = array(
		'Search.Searchable',
		'AuditLog.Auditable' => array(
            'ignore' => array(
            	// 'risk_score',
            	'risk_score_formula',
            	// 'residual_risk',
                'created',
                'modified',
                'SecurityPolicy',
                'SecurityPolicyIncident',
                'SecurityPolicyTreatment',
                'SecurityIncident',
            )
        ),
        'Utils.SoftDelete',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'threats', 'vulnerabilities', 'residual_score', 'risk_score', 'residual_risk', 'review', 'risk_mitigation_strategy_id',
				'description'
			)
		),
		'RiskManager',
		'RiskCalculationManager',
		'CustomFields.CustomFields',
		'Taggable',
		'ObjectStatus.ObjectStatus',
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'Asset' => array(
			'minCount' => array(
				'rule' => array('multiple', array('min' => 1)),
				'message' => 'You have to select at least one Asset'
			)
		),
		/*'Threat' => array(
			'rule' => array('multiple', array('min' => 1))
		),
		'Vulnerability' => array(
			'rule' => array('multiple', array('min' => 1))
		),*/
		// 'SecurityService' => array(
		// 	'rule' => array('multiple', array('min' => 1))
		// ),
		// 'RiskException' => array(
		// 	'minCount' => array(
		// 		'rule' => array('multiple', array('min' => 1)),
		// 		'message' => 'You have to select at least one Risk Exception',
		// 	)
		// ),
		'risk_mitigation_strategy_id' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		'residual_score' => array(
			'rule' => 'numeric',
			'required' => true
		),
		// 'review' => array(
		// 	'rule' => 'date',
		// 	'required' => true
		// ),
		'risk_score' => array(
			'notFalse' => array(
				'rule' => 'numeric',
				'message' => 'Risk score for this item does not meet required conditions'
			)
		)
	);

	public $belongsTo = array(
		'RiskMitigationStrategy'
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'Risk'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'Risk'
			)
		),
		'Review' => array(
			'className' => 'Review',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Review.model' => 'Risk'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'Risk'
			)
		),
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Tag.model' => 'Risk'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'Asset',
		'Threat',
		'Vulnerability',
		'SecurityService',
		'RiskException',
		'RiskClassification' => [
			'className' => 'RiskClassification',
			'with' => 'RiskClassificationsRisk',
			'joinTable' => 'risk_classifications_risks',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'risk_classification_id',
			'conditions' => [
				'RiskClassificationsRisk.type' => RiskClassification::TYPE_ANALYSIS
			]
		],
		'RiskClassificationTreatment' => [
			'className' => 'RiskClassification',
			'with' => 'RiskClassificationsRisk',
			'joinTable' => 'risk_classifications_risks',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'risk_classification_id',
			'conditions' => [
				'RiskClassificationsRisk.type' => RiskClassification::TYPE_TREATMENT
			]
		],
		'ComplianceManagement',
		'Project' => array(
			'with' => 'ProjectsRisk'
		),
		'SecurityPolicy' => [
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'asset-risk',
			)
		],
		'SecurityPolicyIncident' => array(
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'asset-risk',
				'RisksSecurityPolicy.type' => RISKS_SECURITY_POLICIES_INCIDENT
			)
		),
		'SecurityPolicyTreatment' => array(
			'className' => 'SecurityPolicy',
			'with' => 'RisksSecurityPolicy',
			'joinTable' => 'risks_security_policies',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_policy_id',
			'conditions' => array(
				'RisksSecurityPolicy.risk_type' => 'asset-risk',
				'RisksSecurityPolicy.type' => RISKS_SECURITY_POLICIES_TREATMENT
			)
		),
		'SecurityIncident' => array(
			'with' => 'RisksSecurityIncident',
			'joinTable' => 'risks_security_incidents',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'security_incident_id',
			'conditions' => array(
				'RisksSecurityIncident.risk_type' => 'asset-risk'
			)
		),
		'DataAsset' => array(
			'with' => 'DataAssetsRisk',
			'joinTable' => 'data_assets_risks',
			'foreignKey' => 'risk_id',
			'associationForeignKey' => 'data_asset_id',
			'conditions' => array(
				'DataAssetsRisk.model' => 'Risk'
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
				'RiskAppetiteThresholdsRisk.model' => 'Risk',
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
				'RiskAppetiteThresholdsRisk.model' => 'Risk',
			]
		],
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Asset Risk Management');
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
            'Asset' => array(
                'label' => __('Applicable Assets'),
                'group' => 'analysis',
                'editable' => true,
                'description' => __( 'Select one or more applicable assets for this risk. For example, if the risk is web-hacks all your web-applications in scope could be included in this field' )
            ),
        	'risk_score' => array(
				'label' => __('Risk Score'),
				'editable' => false,
				'description' => __('The score calculated for this risk after it\'s assets (their liabilities) and the risk classification has been factored.'),
			),
		);

		//
        // Init helper Lib for UserFields Module
        $UserFields = new UserFields();
        //

		$this->notificationSystem = array(
			'macros' => array(
				'RISK_ID' => array(
					'field' => 'Risk.id',
					'name' => __('Risk ID')
				),
				'RISK_NAME' => array(
					'field' => 'Risk.title',
					'name' => __('Risk Name')
				),
				'RISK_OWNER' => $UserFields->getNotificationSystemData('Owner', [
					'name' => __('Risk Owner')
				]),
				'RISK_STAKEHOLDER' => $UserFields->getNotificationSystemData('Stakeholder', [
					'name' => __('Risk Stakeholder')
				]),
				'RISK_SCORE' => array(
					'field' => 'Risk.risk_score',
					'name' => __('Risk Score')
				),
				'RISK_RESIDUAL' => array(
					'field' => 'Risk.residual_risk',
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

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Risks'),
			'pdf_file_name' => __('risks'),
			'csv_file_name' => __('risks'),
			'additional_actions' => array(
				'RiskReview' => array(
					'label' => __('Reviews'),
					'url' => array(
						'controller' => 'reviews',
						'action' => 'filterIndex',
						'RiskReview',
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
                        'trigger' => 'ObjectStatus.trigger.risk_expired_reviews'
                    ],
                ]
            ],
            'risk_above_appetite' => [
                'title' => __('Risk Above Appetite'),
                'callback' => [$this, '_statusRiskAboveAppetite'],
                'trigger' => [
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.risk_risk_above_appetite'
                    ],
                ]
            ],
        ];
    }

    public function statusExceptionsIssues() {
		$data = $this->RiskException->find('count', [
			'conditions' => [
				'RiskException.expired' => 1,
				'RiskExceptionsRisk.risk_id' => $this->id
			],
			'joins' => [
                [
                    'table' => 'risk_exceptions_risks',
                    'alias' => 'RiskExceptionsRisk',
                    'type' => 'INNER',
                    'conditions' => [
                        'RiskExceptionsRisk.risk_exception_id = RiskException.id',
                    ]
                ],
            ],
			'recursive' => -1
		]);

		return (boolean) $data;
    }

	public function beforeValidate($options = array()) {
		$ret = parent::beforeValidate($options);

		// additionally do a magerit calculation method validation if applicable
		$this->_validateMageritClassification($options);

		return $ret;
	}

	/**
	 * Determine if magerit calculation/manipulation is possible based on correctness of Asset's data.
	 * 
	 * @return boolean True if possible, False otherwise
	 */
	public function isMageritPossible($assetIds = [])
	{
		$Asset = $this->Asset;
		$AssetClassification = $Asset->AssetClassification;

		$invalidAssetClassifications = $AssetClassification->find('count', [
			'conditions' => [
				'AssetClassification.value IS NULL'
			],
			'recursive' => -1
		]);

		$assetClassificationTypes = $AssetClassification->AssetClassificationType->find('count', [
			'recursive' => -1
		]);

		$conds = null;
		if (!empty($assetIds)) {
			$conds = [
				'AssetClassificationsAsset.asset_id' => $assetIds
			];
		}

		$invalidAssets = $Asset->AssetClassificationsAsset->find('count', [
			'conditions' => $conds,
			'group' => [
				'AssetClassificationsAsset.asset_id'
			],
			'having' => [
				'COUNT(AssetClassificationsAsset.asset_id) !=' => (int) $assetClassificationTypes
			],
			'recursive' => -1
		]);

		$ret = true;

		// no asset classifications with a null value
		// possibility to have a null value was a bug in previous versions, now corrected with validation
		$ret &= (int) $invalidAssetClassifications === 0;

		// assets with invalid number of classifications attached
		$ret &= (int) $invalidAssets === 0;

		return (bool) $ret;
	}

	/**
	 * Validate magerit calculation method.
	 *
	 * @return boolean True on success, False otherwise
	 */
	protected function _validateMageritClassification($options = []) {
		if (isset($this->data['Risk']['Asset']) && isset($this->data['Risk']['RiskClassification'])) {
			$calculationMethod = $this->Behaviors->RiskCalculationManager->getMethod($this);

			$conds = $calculationMethod == RiskCalculation::METHOD_MAGERIT;
			$conds = $conds && !$this->isMageritPossible($this->data['Risk']['Asset']);
			if ($conds) {
				$this->invalidate('RiskClassification', __('Magerit calculation is not possible to process at the moment'));
				return false;
			}

			$assetCount = $this->Asset->AssetClassification->AssetClassificationType->find('count');

			$countClassifications = 0;
			if (isset($this->data['Risk']['RiskClassification'])) {
				$countClassifications = count($this->data['Risk']['RiskClassification']);
			}

			$conds = $calculationMethod == RiskCalculation::METHOD_MAGERIT;
			$conds &= $countClassifications != $assetCount+1;
			if ($conds) {
				$this->invalidate('RiskClassification', __('You have to select all Risk Classifications'));
				return false;
			}
		}

		return true;
	}

	public function beforeSave($options = array()) {
		$ret = true;

		if (isset($this->data['Risk']['RiskClassification']) && isset($this->data['Risk']['Asset'])) {
			if ($this->getMethod() != RiskCalculation::METHOD_MAGERIT) {
				$this->data['Risk']['RiskClassification'] = $this->fixClassificationIds();
			}
		}

		$conds = isset($this->data['Risk']['RiskClassificationTreatment']);
		$conds = $conds && is_array($this->data['Risk']['RiskClassificationTreatment']);
		$conds &= isset($this->data['Risk']['Asset']);
		if ($conds) {
			if ($this->getMethod() != RiskCalculation::METHOD_MAGERIT) {
				$this->data['Risk']['RiskClassificationTreatment'] = array_filter($this->data['Risk']['RiskClassificationTreatment']);
			}
		}

		$ret &= parent::beforeSave($options);

		$this->transformDataToHabtm(array('Asset', 'Threat', 'Vulnerability', 'SecurityService',
			'RiskException', 'Project', 'SecurityPolicyIncident', 'SecurityPolicyTreatment', 'RiskClassification', 'RiskClassificationTreatment'
		));

		$this->setHabtmConditionsToData(array('SecurityPolicyIncident', 'SecurityPolicyTreatment'));

		// @todo upgrade to dry for all risks
		if (isset($this->data['Risk']['risk_score']) && is_numeric($this->data['Risk']['risk_score'])) {
			
			$this->data['Risk']['risk_score'] = CakeNumber::precision($this->data['Risk']['risk_score'], 2);
			$math = $this->getCalculationMath();
			if (!is_null($math)) {
				// $this->data['Risk']['risk_score_formula'] = $math;
			}
		}
		
		return $ret;
	}

	public function afterSave($created, $options = array()) {
        parent::afterSave($created, $options);

        if (!empty($this->id) && $this->exists()) {
        	$this->saveRiskScoreWrapper($this->id);
        }

        return true;
	}

	/**
	 * Get relevant classification for this section.
	 */
	public function getFormClassifications()
	{
		$calculationMethod = $this->getMethod();
		$calculationValues = $this->getClassificationTypeValues($this->getSectionValues());
		if ($calculationMethod == RiskCalculation::METHOD_MAGERIT) {
			$classificationTypeUsed = $calculationValues[0];
			$classificationTypeData = $this->RiskClassification->RiskClassificationType->find('first', array(
				'conditions' => array(
					'RiskClassificationType.id' => $classificationTypeUsed
				),
				'recursive' => 1
			));

			$assetClassificationTypeCount = $this->Asset->AssetClassification->AssetClassificationType->find('count');
			
			$classifications = array();
			for ($i=0; $i < $assetClassificationTypeCount; $i++) { 
				$classifications[] = $classificationTypeData;
			}
		}
		else {
			$classifications = $this->getDefaultFormClassifications();
		}

		return $classifications;
	}

	/**
	 * @deprecated replaced by CustomValidator
	 */
	protected function invalidateDependencies() {
		if (!isset($this->data['Risk']['risk_mitigation_strategy_id'])) {
			return;
		}

		if ($this->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_ACCEPT) {
			if (empty($this->data['Risk']['RiskException'])) {
				$this->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->validate['SecurityService']);
		}

		if ($this->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_AVOID) {
			if (empty($this->data['Risk']['RiskException'])) {
				$this->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->validate['SecurityService']);
		}

		if ($this->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_MITIGATE) {
			if (empty($this->data['Risk']['SecurityService'])) {
				$this->invalidate('SecurityService', __('This field cannot be left blank'));
			}
			unset($this->validate['RiskException']);
		}

		if ($this->data['Risk']['risk_mitigation_strategy_id'] == RISK_MITIGATION_TRANSFER) {
			if (empty($this->data['Risk']['RiskException'])) {
				$this->invalidate('RiskException', __('This field cannot be left blank'));
			}
			unset($this->validate['SecurityService']);
		}
	}

	/**
	 * Calculate residual risk.
	 * @param  int $residual_score Residual Score.
	 * @param  int $risk_score     Risk Score.
	 * @return int                 Residual Risk.
	 * @todo  move away from appcontroller
	 */
	protected function getResidualRisk($residual_score, $risk_score) {
		return CakeNumber::precision(getResidualRisk($residual_score, $risk_score), 2);
	}

	protected function fixClassificationIds() {
		$tmp = array();
		if (!empty($this->data['Risk']['RiskClassification'])) {
			foreach ( $this->data['Risk']['RiskClassification'] as $classification_id ) {
				if ( $classification_id ) {
					$tmp[] = $classification_id;
				}
			}
		}

		return $tmp;
	}

	/**
	 * @deprecated in favor of AppModel::findByHabtm()
	 */
	public function findByExceptions($data = array()) {
		$this->RiskExceptionsRisk->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->RiskExceptionsRisk->Behaviors->attach('Search.Searchable');

		$query = $this->RiskExceptionsRisk->getQuery('all', array(
			'conditions' => array(
				'RiskExceptionsRisk.risk_exception_id' => $data['risk_exception_id']
			),
			'fields' => array(
				'RiskExceptionsRisk.risk_id'
			)
		));

		return $query;
	}

	/**
	 * @deprecated in favor of AppModel::findByHabtm()
	 */
	public function findByServices($data = array()) {
		$this->RisksSecurityService->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->RisksSecurityService->Behaviors->attach('Search.Searchable');

		$query = $this->RisksSecurityService->getQuery('all', array(
			'conditions' => array(
				'RisksSecurityService.security_service_id' => $data['security_service_id']
			),
			'fields' => array(
				'RisksSecurityService.risk_id'
			)
		));

		return $query;
	}

	public function getStatusManagerConfig($config) {
		return $this->mapping['statusManager'][$config];
	}

	public function getControlsWithIssues($riskId, $find = 'list') {
		$this->RisksSecurityService->bindModel(array(
			'belongsTo' => array('SecurityService')
		));

		$ids = $this->RisksSecurityService->find('list', array(
			'conditions' => array(
				'RisksSecurityService.risk_id' => $riskId
			),
			'fields' => array('SecurityService.id'),
			'recursive' => 0
		));

		$issues = $this->SecurityService->getIssues($ids, $find);

		return $issues;
	}

	public function controlsIssuesMsgParams() {
		if (isset($this->data['Risk']['id'])) {
			$issues = $this->getControlsWithIssues($this->data['Risk']['id']);
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
		$this->RiskExceptionsRisk->bindModel(array(
			'belongsTo' => array('RiskException')
		));

		$issues = $this->RiskExceptionsRisk->find($find, array(
			'conditions' => array(
				'RiskExceptionsRisk.risk_id' => $riskId,
				'RiskException.expired' => 1
			),
			'fields' => array('RiskException.id', 'RiskException.title'),
			'recursive' => 0
		));

		return $issues;
	}

	public function exceptionsIssuesMsgParams() {
		if (isset($this->data['Risk']['id'])) {
			$issues = $this->getExceptionWithIssues($this->data['Risk']['id']);
		}
		elseif (isset($this->id)) {
			$issues = $this->getExceptionWithIssues($this->id);
		}

		if (!empty($issues)) {
			return implode(', ', $issues);
		}
	}

	/**
	 * Append expired field to the query calculated from review date field.
	 */
	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'review');
	}

	/**
	 * @deprecated in favor of AppModel::findByHabtm()
	 */
	public function findByAsset($data = array()) {
		$this->AssetsRisk->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->AssetsRisk->Behaviors->attach('Search.Searchable');

		$query = $this->AssetsRisk->getQuery('all', array(
			'conditions' => array(
				'AssetsRisk.asset_id' => $data['asset_id']
			),
			'fields' => array(
				'AssetsRisk.risk_id'
			)
		));

		return $query;
	}

	public function getAssetIds($riskIds = array()) {
		$assetIds = $this->AssetsRisk->find('list', array(
			'conditions' => array(
				'AssetsRisk.risk_id' => $riskIds
			),
			'fields' => array(
				'AssetsRisk.asset_id'
			)
		));

		return array_values($assetIds);
	}

	/**
	 * Calculate Risk Score for a Risk from given classification values.
	 * @return int Risk Score.
	 */
	public function calculateRiskScore($classification_ids = array(), $asset_ids = array()) {
		$this->resetCalculationClass();

		$calculation = $this->calculateByMethod(array(
			'classification_ids' => $classification_ids,
			'asset_ids' => $asset_ids,
		));

		return $calculation;
	}

	public function ErambaCalculation($classification_ids = array(), $asset_ids = array()) {
		//$classification_ids = !empty($classification_ids) ? $classification_ids : $this->request->data['Risk']['risk_classification_id'];
// debug($classification_ids);
		if ( empty( $classification_ids ) ) {
			return 0;
		}
// debug($classification_ids);exit;
		$classifications = $this->RiskClassification->find('all', array(
			'conditions' => array(
				'RiskClassification.id' => $classification_ids
			),
			'fields' => array( 'id', 'value' ),
			'recursive' => -1
		));

		//$asset_ids = !empty($asset_ids) ? $asset_ids : $this->request->data['Risk']['asset_id'];
		$assets = $this->Asset->find('all', array(
			'conditions' => array(
				'Asset.id' => $asset_ids
			),
			'fields' => array( 'id' ),
			'contain' => array(
				'Legal' => array(
					'fields' => array( 'id', 'risk_magnifier' )
				)
			),
			'recursive' => 0
		));
// debug(array($classifications, $assets));exit;
		return array($classifications, $assets);

		return $this->ErambaCalcFormula(array($classifications, $assets));

		/*$classification_sum = 0;
		foreach ( $classifications as $classification ) {
			$classification_sum += $classification['RiskClassification']['value'];
		}

		$asset_sum = 0;
		foreach ( $assets as $asset ) {
			foreach ($asset['Legal'] as $legal) {
				$asset_sum += $legal['risk_magnifier'];
			}
		}

		if ( $asset_sum ) {
			return $classification_sum * $asset_sum;
		}

		return $classification_sum;*/
	}

	/**
	 * Calculate risk score and save to database.
	 */
	public function calculateAndSaveRiskScoreById($Ids) {
		$risks = $this->find('all', array(
			'conditions' => array(
				'Risk.id' => $Ids
			),
			'contain' => array(
				'Asset' => array(
					'fields' => array('id')
				),
				'RiskClassification' => array(
					'fields' => array('id')
				)
			)
		));
		// debug($risks);exit;

		$ret = true;
		foreach ($risks as $risk) {
			$classificationIds = array();
			foreach ($risk['RiskClassification'] as $c) {
				$classificationIds[] = $c['id'];
			}

			$assetIds = array();
			foreach ($risk['Asset'] as $a) {
				$assetIds[] = $a['id'];
			}

			$riskScore = $this->calculateRiskScore($classificationIds, $assetIds);
			if (!is_numeric($riskScore)) {
				return false;
			}

			$residualRisk = getResidualRisk($risk['Risk']['residual_score'], $riskScore);

			$saveData = array(
				'risk_score' => $riskScore,
				'residual_risk' => $residualRisk
			);

			$this->id = $risk['Risk']['id'];
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
				
				$ret &= $this->quickLogSave($risk['Risk']['id'], 2, $msg);
			}
		}

		return $ret;
	}

	public function controlsIssueConditions($data = array()){
		$conditions = array();
		if($data['controls_issues'] == 1){
			$conditions = array(
				'Risk.controls_issues >' => 0
			);
		}
		elseif($data['controls_issues'] == 0){
			$conditions = array(
				'Risk.controls_issues' => 0
			);
		}

		return $conditions;
	}

	public function residualRiskConditions($data = array()){
		$conditions = array();
		if($data['residual_risk'] == 1){
			$conditions = array(
				'Risk.residual_risk >' => RISK_APPETITE
			);
		}
		elseif($data['residual_risk'] == 0){
			$conditions = array(
				'Risk.residual_risk <=' => RISK_APPETITE
			);
		}

		return $conditions;
	}
}