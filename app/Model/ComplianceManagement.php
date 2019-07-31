<?php
App::uses('SectionBase', 'Model');
App::uses('BulkAction', 'BulkActions.Model');

class ComplianceManagement extends SectionBase {
	public $mapping = array(
		'indexController' => 'complianceManagements',
		/*'indexController' => array(
			'basic' => 'complianceManagements',
			'advanced' => 'complianceManagements',
			'action' => 'analyze',
			'params' => array('third_party_id'),
			'crawl' => array(
				'CompliancePackageItem' => array(
					'CompliancePackage' => array(
						'fields' => array('third_party_id')
					)
				)
			)
		),*/
		'titleColumn' => false,
		'logRecords' => true,
		'workflow' => false,
	);

	public $config = array(
		'actionList' => array(
			'trash' => false,
			'notifications' => false
		)
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
				'description', 'compliance_treatment_strategy_id', 'efficacy', 'owner_id', 'legal_id', 'compliance_exception_id', 'compliance_package_item_id'
			)
		),
		'CustomFields.CustomFields',
		'ObjectStatus.ObjectStatus',
	);

	public $validate = array(
		'efficacy' => array(
			'rule' => 'notBlank',
			'required' => true
		)
	);

	public $hasAndBelongsToMany = array(
		'SecurityService',
		'SecurityPolicy',
		'Risk',
		'ThirdPartyRisk',
		'BusinessContinuity',
		'Project' => array(
			'with' => 'ComplianceManagementsProject'
		),
		'ComplianceAnalysisFinding',
		'ComplianceException',
		'Asset'
	);

	public $belongsTo = array(
		'ComplianceTreatmentStrategy',
		// 'ComplianceException',
		'CompliancePackageItem',
		'Legal',
		'Owner' => array(
			'className' => 'User',
			// 'fields' => array('id', 'name', 'surname')
		)
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ComplianceManagement'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ComplianceManagement'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ComplianceManagement'
			)
		),
	);

	public $packageContain = array(
		'CompliancePackageItem' => array(
			'ComplianceManagement' => array(
				'SecurityService' => array(
					/*'conditions' => array(
						'OR' => array(
							'audits_all_done' => 0,
							'audits_last_missing' => 1,
							'audits_last_passed' => 0,
							'maintenances_all_done' => 0,
							'maintenances_last_missing' => 1,
							'maintenances_last_passed' => 0
						)
					),*/
					'SecurityServiceType'
				),
				'SecurityPolicy',
				'ComplianceException' => array(
					// 'conditions' => array(
					// 	'expired' => 1
					// )
				),
				'Project'
			)
		)
	);

	public $thirdPartyJoins = array(
		array(
			'table' => 'compliance_packages',
			'alias' => 'CompliancePackage',
			'type' => 'INNER',
			'conditions' => array(
				'CompliancePackageItem.compliance_package_id = CompliancePackage.id'
			)
		),
		array(
			'table' => 'third_parties',
			'alias' => 'ThirdParty',
			'type' => 'INNER',
			'conditions' => array(
				'ThirdParty.id = CompliancePackage.third_party_id'
			)
		),
	);

	/**
	 * @deprecated
	 */
	public function compliancePackageSearch($data = array(), $filter) {
		$third_party = $data[$filter['name']];

		$searchData = $this->find('all', array(
			'conditions' => array(
				'ThirdParty.name LIKE' => '%' . trim($third_party) . '%'
			),
			'fields' => array('ComplianceManagement.id'),
			'recursive' => -1,
			'joins' => array(
				array(
					'table' => 'compliance_package_items',
					'alias' => 'SubCompliancePackageItem',
					'type' => 'LEFT',
					'conditions' => array(
						'SubCompliancePackageItem.id = ComplianceManagement.compliance_package_item_id'
					)
				),
				array(
					'table' => 'compliance_packages',
					'alias' => 'CompliancePackage',
					'type' => 'LEFT',
					'conditions' => array(
						'SubCompliancePackageItem.compliance_package_id = CompliancePackage.id'
					)
				),
				array(
					'table' => 'third_parties',
					'alias' => 'ThirdParty',
					'type' => 'LEFT',
					'conditions' => array(
						'ThirdParty.id = CompliancePackage.third_party_id'
					)
				),
			)
		));
		
		$ids = Hash::extract($searchData, '{n}.ComplianceManagement.id');

		$conds = array();
		$conds['ComplianceManagement.id'] = $ids;
		
		return $conds;
	}

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Compliance Managements');

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
			'mitigation-options' => array(
				'label' => __('Mitigation Options')
			),
			'findings' => array(
				'label' => __('Findings')
			),
			'compliance-drivers' => array(
				'label' => __('Compliance Drivers')
			),
			'asset' => array(
				'label' => __('Asset')
			),
		);

		$this->fieldData = array(
			'compliance_package_item_id' => array(
				'label' => __('Compliance Package Item'),
				'editable' => false,
				'hidden' => true,
			),
			'efficacy' => array(
				'label' => __('Compliance Efficacy'),
				'options' => 'getPercentageOptions',
				'editable' => true,
				'description' => __('OPTIONAL: In your subjective appreciation, the controls and policies selected on the next fields mitigate the requirement to which extent? If in doubt, select %100.')
			),
			'owner_id' => array(
				'label' => __('Owner'),
				'editable' => true,
				'description' => __('OPTIONAL: select one individual that is most related to this particular requirement. If in doubt, simply select "Admin".')
			),
			'compliance_treatment_strategy_id' => array(
				'label' => __('Current Compliance Status'),
				'editable' => true,
				'description' => __('Select your desired compliance goal:<br>Compliant: you wish to be compliant.<br>Not Applicable: this item is not applicable to the scope of this program.<br>Not Compliant: your organisation has no interest in being compliant with this requirement.')
			),
			'description' => array(
				'label' => __('Description'),
				'editable' => true,
			),
			'Project' => array(
				'label' => __('Mitigation Projects'),
				'editable' => true,
				'options' => array($this, 'getProjectsNotCompleted'),
				'description' => __('OPTIONAL: If you havent got controls and policies that meet this requirement, you can select a project that addresses this issue (Projects are defined in Security Operations / Project Management).')
			),
			'SecurityService' => array(
				'label' => __('Mitigation Controls'),
				'group' => 'mitigation-options',
				'editable' => true,
				'description' => __('Select one or more controls (from Control Catalogue / Security Services) used to mitigate this compliance requirement. If you havent got controls you can still select mitigation policies or alternatively, set this requirement as "Not Compliant" and create a "Mitigation Project"')
			),
			'SecurityPolicy' => array(
				'label' => __('Mitigating Security Policies'),
				'group' => 'mitigation-options',
				'editable' => true,
				'description' => __('Select one or more policies (from Control Catalogue / Security Policies) that mitigate this compliance requirement (they can replace security controls when none is applicable).')
			),
			'ComplianceException' => array(
				'label' => __('Compliance Exception'),
				'group' => 'mitigation-options',
				'editable' => true,
				'description' => __('If the compliance status (from the first tab) is "Not Aplicable" or "Not Compliant" you might want to set a Compliance Exception to state that in a formal record. This is an optional record.')
			),
			'ComplianceAnalysisFinding' => array(
				'label' => __('Compliance Findings'),
				'group' => 'findings',
				'editable' => true,
				'description' => __('OPTIONAL: Select or create one or more Compliance Findings (from Compliance Management / Compliance Finginds) for this compliance requirements. This is typically used when your auditors have identified that your mitigation for this control is innefective and you want to keep track of such incompliance until remediation.')
			),
			'Risk' => array(
				'label' => __('Asset Risks'),
				'group' => 'compliance-drivers',
				'editable' => true,
				'description' => __('OPTIONAL: Certain standards (such as ISO 27001) require you to describe the drivers for meeting their controls. You can use Risks (from Risk Management / Asset Risk Management) as drivers.')
			),
			'ThirdPartyRisk' => array(
				'label' => __('Third Party Risks'),
				'group' => 'compliance-drivers',
				'editable' => true,
				'description' => __('OPTIONAL: Certain standards (such as ISO 27001) require you to describe the drivers for meeting their controls. You can use Risks (from Risk Management / Third Party Risk Management) as drivers.')
			),
			'BusinessContinuity' => array(
				'label' => __('Business Risks'),
				'group' => 'compliance-drivers',
				'editable' => true,
				'description' => __('OPTIONAL: Certain standards (such as ISO 27001) require you to describe the drivers for meeting their controls. You can use Risks (from Risk Management / Business Impact Analysis) as drivers.')
			),
			'legal_id' => array(
				'label' => __('Liabilities'),
				'group' => 'compliance-drivers',
				'editable' => true,
				'description' => __('OPTIONAL: If there are liabilities (from Organisation / Legal Constrains) that require you to meet this particular requirement select them here.')
			),
			'Asset' => array(
				'label' => __('Assets'),
				'group' => 'asset',
				'editable' => true,
				'description' => __('OPTIONAL: Select one or more assets (from Asset Managemnet / Asset Identification) that are related to this compliance requirement.')
			),
		);

		$this->mapping['statusManager'] = array(
			'compliance_treatment_strategy_id' => array(
				'column' => 'compliance_treatment_strategy_id',
				// 'migrateRecords' => $migrateRecords,
				'toggles' => array(
					'compliant' => array(
						'value' => COMPLIANCE_TREATMENT_COMPLIANT,
						'message' => __('Treatment Strategy has been set to Compliant')
					),
					'not_applicable' => array(
						'value' => COMPLIANCE_TREATMENT_NOT_APPLICABLE,
						'message' => __('Treatment Strategy has been set to Not Applicable')
					),
					'not_compliant' => array(
						'value' => COMPLIANCE_TREATMENT_NOT_COMPLIANT,
						'message' => __('Treatment Strategy has been set to Not Compliant')
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
				'third_party' => array(
					'type' => 'multiple_select',
					'name' => __('Compliance Package Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackage.third_party_id',
						'field' => 'ComplianceManagement.id',

						'path' => [
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
					),
					'field' => 'CompliancePackageItem.CompliancePackage.ThirdParty.name',
					'data' => array(
						'method' => 'getThirdParties',
					),
					'containable' => array(
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
				),
				'item_id' => array(
					'type' => 'text',
					'name' => __('Item ID'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.item_id',
						'field' => 'ComplianceManagement.compliance_package_item_id',
					),
					'contain' => array(
						'CompliancePackageItem' => array(
							'item_id'
						)
					),
				),
				'item_name' => array(
					'type' => 'text',
					'name' => __('Item Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.name',
						'field' => 'ComplianceManagement.compliance_package_item_id',
					),
					'contain' => array(
						'CompliancePackageItem' => array(
							'name'
						)
					),
				),
				'item_description' => array(
					'type' => 'text',
					'name' => __('Item Description'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'CompliancePackageItem.description',
						'field' => 'ComplianceManagement.compliance_package_item_id',
					),
					'contain' => array(
						'CompliancePackageItem' => array(
							'description'
						)
					),
				),
				'efficacy' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Compliance Efficacy'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceManagement.efficacy',
						'field' => 'ComplianceManagement.id',
					),
				),
				'project_id' => array(
					'type' => 'multiple_select',
					'name' => __('Mitigation Projects'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.id',
						'field' => 'ComplianceManagement.id',
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
				'owner_id' => array(
					'type' => 'multiple_select',
					'name' => __('Owner'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceManagement.owner_id',
						'field' => 'ComplianceManagement.id',
					),
					'data' => array(
						'method' => 'getOwners',
					),
					'contain' => array(
						'Owner' => array(
							'name', 'surname'
						)
					),
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'filter' => array(
						'type' => 'like',
						'field' => array('ComplianceManagement.description'),
					)
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
						'field' => 'ComplianceManagement.id',
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
				'compliance_treatment_strategy_id' => array(
					'type' => 'multiple_select',
					'name' => __('Strategy'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceManagement.compliance_treatment_strategy_id',
						'field' => 'ComplianceManagement.id',
					),
					'data' => array(
						'method' => 'getStrategies'
					),
					'contain' => array(
						'ComplianceTreatmentStrategy' => array(
							'name'
						)
					),
				),
				'security_service_id' => array(
					'type' => 'multiple_select',
					'name' => __('Mitigation Controls'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.id',
						'field' => 'ComplianceManagement.id',
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
				'security_policy_id' => array(
					'type' => 'multiple_select',
					'name' => __('Mitigating Security Policies'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityPolicy.id',
						'field' => 'ComplianceManagement.id',
					),
					'data' => array(
						'method' => 'getPolicies',
					),
					'many' => true,
					'contain' => array(
						'SecurityPolicy' => array(
							'index'
						)
					),
				),
				'compliance_exception_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compliance Exception'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceException.id',
						'field' => 'ComplianceManagement.id',
					),
					'data' => array(
						'method' => 'getExceptions',
					),
					'many' => true,
					'field' => 'ComplianceException.{n}.title',
					'containable' => array(
						'ComplianceException' => array(
							'fields' => ['title']
						)
					),
				),
				'risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Asset Risks'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Risk.id',
						'field' => 'ComplianceManagement.id',
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
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ThirdPartyRisk.id',
						'field' => 'ComplianceManagement.id',
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
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'BusinessContinuity.id',
						'field' => 'ComplianceManagement.id',
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
				'legal_id' => array(
					'type' => 'multiple_select',
					'name' => __('Liabilities'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceManagement.legal_id',
						'field' => 'ComplianceManagement.id',
					),
					'data' => array(
						'method' => 'getLegals',
					),
					'contain' => array(
						'Legal' => array(
							'name'
						)
					),
				),
			),
			__('Compliance Findings') => array(
				'compliance_analysis_finding_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compliance Findings'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ComplianceAnalysisFinding.id',
						'field' => 'ComplianceManagement.id',
					),
					'data' => array(
						'method' => 'getAnalysisFindings',
					),
					'many' => true,
					'contain' => array(
						'ComplianceAnalysisFinding' => array(
							'title'
						)
					),
				),
			),
			__('Control') => array(
				'security_service_audits_last_passed' => array(
					'type' => 'select',
					'name' => __('Last audit failed'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
						'status' => array(
							'model' => 'SecurityService',
							'field' => 'audits_last_passed'
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
						'field' => 'ComplianceManagement.id',
						'status' => array(
							'model' => 'SecurityService',
							'field' => 'audits_last_missing'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'security_service_maintenances_last_missing' => array(
					'type' => 'select',
					'name' => __('Last maintenance missing'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
						'status' => array(
							'model' => 'SecurityService',
							'field' => 'maintenances_last_missing'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'security_service_ongoing_corrective_actions' => array(
					'type' => 'select',
					'name' => __('Ongoing Corrective Actions'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
						'status' => array(
							'model' => 'SecurityService',
							'field' => 'ongoing_corrective_actions'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'security_service_security_service_type_id' => array(
					'type' => 'select',
					'name' => __('Control in Design'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
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
					),
				),
				'security_service_control_with_issues' => array(
					'type' => 'select',
					'name' => __('Control with Issues'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
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
			),
			__('Policy') => array(
				'security_policy_expired_reviews' => array(
					'type' => 'select',
					'name' => __('Missing Reviews'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
						'status' => array(
							'model' => 'SecurityPolicy',
							'field' => 'expired_reviews'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
			),
			__('Project') => array(
				'project_expired' => array(
					'type' => 'select',
					'name' => __('Improvement Project Expired'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
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
				'project_over_budget' => array(
					'type' => 'select',
					'name' => __('Improvement Project over Budget'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
						'status' => array(
							'model' => 'Project',
							'field' => 'over_budget'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'project_expired_tasks' => array(
					'type' => 'select',
					'name' => __('Project Task Expired'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByInheritedStatus',
						'field' => 'ComplianceManagement.id',
						'status' => array(
							'model' => 'Project',
							'field' => 'expired_tasks',
							// 'comparisonOperator' => '>'
						)
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
			),
		);
		
		$this->advancedFilterSettings = array(
			'pdf_title' => __('Compliance Analysis'),
			'pdf_file_name' => __('compliance_analysis'),
			'csv_file_name' => __('compliance_analysis'),
			'view_item' => array(
				'ajax_action' => array(
					'controller' => 'complianceManagements',
					'action' => 'analyze'
				)
			),
			'history' => true,
			'bulk_actions' => array(
				BulkAction::TYPE_EDIT
			),
			'use_new_filters' => true
		);

		parent::__construct($id, $table, $ds);
	}

	public function getObjectStatusConfig() {
        return [
        	'project_over_budget' => [
            	'title' => __('Improvement Project over Budget'),
                'inherited' => [
                	'Project' => 'over_budget'
            	],
            	'storageSelf' => false
            ],
            'project_expired' => [
            	'title' => __('Improvement Project Expired'),
                'inherited' => [
                	'Project' => 'expired'
            	],
            	'storageSelf' => false
            ],
            'project_expired_task' => [
            	'title' => __('Improvement Project with Expired Tasks'),
                'inherited' => [
                	'Project' => 'expired_task'
            	],
            	'storageSelf' => false
            ],
            'security_policy_expired_reviews' => [
            	'title' => __('Missing Review'),
                'inherited' => [
                	'SecurityPolicy' => 'expired_reviews'
            	],
            	'storageSelf' => false
            ],
            'compliance_exception_expired' => [
            	'title' => __('Expired'),
                'inherited' => [
                	'ComplianceException' => 'expired'
            	],
            	'storageSelf' => false
            ],
            'security_service_audits_last_passed' => [
            	'title' => __('Last audit failed'),
                'inherited' => [
                	'SecurityService' => 'audits_last_passed'
            	],
            	'type' => 'danger',
            	'storageSelf' => false
            ],
            'security_service_audits_last_missing' => [
            	'title' => __('Last audit missing'),
                'inherited' => [
                	'SecurityService' => 'audits_last_missing'
            	],
            	'storageSelf' => false
            ],
            'security_service_maintenances_last_missing' => [
            	'title' => __('Last maintenance missing'),
                'inherited' => [
                	'SecurityService' => 'maintenances_last_missing'
            	],
            	'storageSelf' => false
            ],
            'security_service_ongoing_corrective_actions' => [
            	'title' => __('Ongoing Corrective Actions'),
                'inherited' => [
                	'SecurityService' => 'ongoing_corrective_actions'
            	],
            	'type' => 'improvement',
            	'storageSelf' => false
            ],
            'security_service_control_with_issues' => [
            	'title' => __('Control with Issues'),
                'inherited' => [
                	'SecurityService' => 'control_with_issues'
            	],
            	'type' => 'danger',
            	'storageSelf' => false
            ],
            'risk_expired_reviews' => [
            	'title' => __('Risk Review Expired'),
                'inherited' => [
                	'Risk' => 'expired_reviews'
            	],
            	'storageSelf' => false
            ],
            'risk_risk_above_appetite' => [
            	'title' => __('Risk Above Appetite'),
                'inherited' => [
                	'Risk' => 'risk_above_appetite'
            	],
            	'storageSelf' => false
            ],
            'third_party_risk_expired_reviews' => [
            	'title' => __('Risk Review Expired'),
                'inherited' => [
                	'ThirdPartyRisk' => 'expired_reviews'
            	],
            	'storageSelf' => false
            ],
            'third_party_risk_risk_above_appetite' => [
            	'title' => __('Risk Above Appetite'),
                'inherited' => [
                	'ThirdPartyRisk' => 'risk_above_appetite'
            	],
            	'storageSelf' => false
            ],
            'business_continuity_expired_reviews' => [
            	'title' => __('Risk Review Expired'),
                'inherited' => [
                	'BusinessContinuity' => 'expired_reviews'
            	],
            	'storageSelf' => false
            ],
            'business_continuity_risk_above_appetite' => [
            	'title' => __('Risk Above Appetite'),
                'inherited' => [
                	'BusinessContinuity' => 'risk_above_appetite'
            	],
            	'storageSelf' => false
            ],
        ];
    }

	public function beforeSave($options = array()) {
        $this->transformDataToHabtm(array('ComplianceAnalysisFinding','SecurityService', 'SecurityPolicy', 'Risk', 
        	'ThirdPartyRisk', 'BusinessContinuity', 'Project', 'ComplianceException', 'Asset'
    	));

        return true;
    }

	public function afterFind($results, $primary = false) {
		if ($primary) {
			foreach ($results as $key => &$item) {
				if (isset($item['ComplianceTreatmentStrategy']['name']) && $item['ComplianceTreatmentStrategy']['name'] === null) {
					$item['ComplianceTreatmentStrategy']['name'] = __('Undefined');
				}
			}
		}

		return $results;
	}

	/**
	 * Create a record having default basic values possible.
	 */
	public function addItem($compliancePackageItemId) {
		if (is_array($compliancePackageItemId)) {
			$ret = true;
			foreach ($compliancePackageItemId as $id) {
				$ret &= $this->addItem($id);
			}

			return $ret;
		}

		$user = $this->currentUser();
		$data = [
			'compliance_package_item_id' => $compliancePackageItemId,
			'compliance_treatment_strategy_id' => null,//COMPLIANCE_TREATMENT_COMPLIANT,
			'efficacy' => '0',
			// 'workflow_status' => WORKFLOW_APPROVED,
			// 'workflow_owner_id' => $user['id']
		];

		$this->create();
		$this->set($data);
		
		return $this->save();
	}

	/**
	 * @deprecated in favor of AppModel::findByHabtm()
	 */
	public function findByProjects($data = array()) {
		$this->ComplianceManagementsProject->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->ComplianceManagementsProject->Behaviors->attach('Search.Searchable');

		$query = $this->ComplianceManagementsProject->getQuery('all', array(
			'conditions' => array(
				'ComplianceManagementsProject.project_id' => $data['project_id']
			),
			'fields' => array(
				'ComplianceManagementsProject.compliance_management_id'
			)
		));

		return $query;
	}

	public function getThirdParties() {
		$data = $this->CompliancePackageItem->CompliancePackage->ThirdParty->find('all', array(
			'conditions' => array(
			),
			'fields' => array(
				'ThirdParty.id',
				'ThirdParty.name',
				'ThirdParty.description'
			),
			'contain' => array(
				'CompliancePackage' => array(
					'CompliancePackageItem'
				)
			),
			'order' => array( 'ThirdParty.id' => 'ASC' ),

		));
		$data = $this->filterComplianceData($data);

		$list = array();
		foreach ($data as $item) {
			$list[$item['ThirdParty']['id']] = $item['ThirdParty']['name'];
		}

		return $list;
	}

	public function getOwners() {
		$this->Owner->virtualFields['full_name'] = 'CONCAT(Owner.name, " ", Owner.surname)';
		$owners = $this->Owner->find('list', array(
			'conditions' => array(),
			'fields' => array('Owner.id', 'Owner.full_name'),
		));

		return $owners;
	}

	public function getStrategies() {
		$strategies = $this->ComplianceTreatmentStrategy->find('list', array(
			'fields' => array('ComplianceTreatmentStrategy.id', 'ComplianceTreatmentStrategy.name'),
			'order' => array('ComplianceTreatmentStrategy.name' => 'ASC'),
			'recursive' => -1
		));
		
		return $strategies;
	}

	public function getExceptions() {
		$exceptions = $this->ComplianceException->find('list', array(
			'fields' => array('ComplianceException.id', 'ComplianceException.title'),
			'order' => array('ComplianceException.title' => 'ASC'),
			'recursive' => -1
		));

		return $exceptions;
	}

	public function getLegals() {
		$legals = $this->Legal->find('list', array(
			'fields' => array('Legal.id', 'Legal.name'),
			'order' => array('Legal.name' => 'ASC'),
			'recursive' => -1
		));

		return $legals;
	}

	public function getControlFilterStatusOptions() {
		$arr = array(
			'audits_last_missing' => 'Audits Last missing text',
			'maintenances_last_missing' => 'maintenance missing text from model'
		);

		return $arr;
	}

	public function getServices() {
		$services = $this->SecurityService->find('list', array(
			'fields' => array('SecurityService.id', 'SecurityService.name')
		));

		return $services;
	}

	public function getPolicies() {
		return $this->SecurityPolicy->getListWithType();
	}

	public function getRisks() {
		$risks = $this->Risk->find('list', array(
			'fields' => array('Risk.id', 'Risk.title'),
			'order' => array('Risk.title' => 'ASC'),
			'recursive' => -1
		));

		return $risks;
	}

	public function getProjectsNotCompleted() {
		return $this->Project->getList();
	}

	public function getThirdPartyRisks() {
		$risks = $this->ThirdPartyRisk->find('list', array(
			'fields' => array('ThirdPartyRisk.id', 'ThirdPartyRisk.title'),
			'order' => array('ThirdPartyRisk.title' => 'ASC'),
			'recursive' => -1
		));

		return $risks;
	}

	public function getBusinessContinuities() {
		$risks = $this->BusinessContinuity->find('list', array(
			'fields' => array('BusinessContinuity.id', 'BusinessContinuity.title'),
			'order' => array('BusinessContinuity.title' => 'ASC'),
			'recursive' => -1
		));

		return $risks;
	}

	public function getAssets() {
		return $this->Asset->getList();
	}

	public function getAnalysisFindings() {
		return $this->ComplianceAnalysisFinding->getList();
	}

	public function filterComplianceData($data) {
		return filterComplianceData($data);
	}

	/**
	 * Get commonly needed Compliance data through to Third Party name.
	 * 
	 * @param  array  $ids ComplianceManagement IDs.
	 */
	public function getCommonComplianceData($ids = array()) {
		$data = $this->find('all', array(
			'conditions' => array(
				'ComplianceManagement.id' => $ids
			),
			'fields' => array(
				'ComplianceManagement.id',
				'ComplianceManagement.compliance_package_item_id',
				'CompliancePackageItem.*',
				'CompliancePackage.name',
				'CompliancePackage.third_party_id',
				'ThirdParty.name'
			),
			'joins' => $this->thirdPartyJoins,
			'recursive' => 0
		));

		// fill array keys with the item's ID
		$data = array_combine(Hash::extract($data, '{n}.ComplianceManagement.id'), array_values($data));
		
		return $data;
	}

	// sync missing compliance management rows in the table
	public function syncObjects() {
		$data = $this->CompliancePackageItem->find('list', [
			'fields' => ['id'],
			'recursive' => -1
		]);

		return $this->ComplianceAnalysisFinding->complianceIntegrityCheck($data);
	}

	/**
	 * Attach Package data on ComplaineManagements.
	 */
	public function attachCompliancePackageData(&$data, $singleItem = false) {
		if (empty($data)) {
			return;
		}

		$data = ($singleItem) ? [$data] : $data;

		$CompliancePackageItem = ClassRegistry::init('CompliancePackageItem');
		$compliancePackageItemIds = Hash::extract($data, '{n}.ComplianceManagement.{n}.compliance_package_item_id');

		if (empty($compliancePackageItemIds)) {
			return;
		}

		$compliancePackageItemData = $CompliancePackageItem->find('all', [
			'conditions' => [
				'CompliancePackageItem.id' => $compliancePackageItemIds
			],
			'recursive' => -1
		]);
		$compliancePackageItemData = Hash::combine($compliancePackageItemData, '{n}.CompliancePackageItem.id', '{n}');

		$CompliancePackage = ClassRegistry::init('CompliancePackage');
		$compliancePackageIds = Hash::extract($compliancePackageItemData, '{n}.CompliancePackageItem.compliance_package_id');
		$compliancePackageData = $CompliancePackage->find('all', [
			'conditions' => [
				'CompliancePackage.id' => $compliancePackageIds
			],
			'recursive' => -1
		]);
		$compliancePackageData = Hash::combine($compliancePackageData, '{n}.CompliancePackage.id', '{n}');

		$ThirdParty = ClassRegistry::init('ThirdParty');
		$thirdPartyIds = Hash::extract($compliancePackageData, '{n}.CompliancePackage.third_party_id');
		$thirdPartyData = $ThirdParty->find('all', [
			'conditions' => [
				'ThirdParty.id' => $thirdPartyIds
			],
			'recursive' => -1
		]);
		$thirdPartyData = Hash::combine($thirdPartyData, '{n}.ThirdParty.id', '{n}');

		foreach ($compliancePackageData as $key => $item) {
			if (!empty($item['CompliancePackage']['third_party_id'])) {
				$compliancePackageData[$key]['CompliancePackage']['ThirdParty'] = $thirdPartyData[$item['CompliancePackage']['third_party_id']]['ThirdParty'];
			}
		}

		foreach ($compliancePackageItemData as $key => $item) {
			if (!empty($item['CompliancePackageItem']['compliance_package_id'])) {
				$compliancePackageItemData[$key]['CompliancePackageItem']['CompliancePackage'] = $compliancePackageData[$item['CompliancePackageItem']['compliance_package_id']]['CompliancePackage'];
			}
		}

		foreach ($data as $key => $item) {
			foreach ($item['ComplianceManagement'] as $subKey => $subItem) {
				if (!empty($subItem['compliance_package_item_id'])) {
					$data[$key]['ComplianceManagement'][$subKey]['CompliancePackageItem'] = $compliancePackageItemData[$subItem['compliance_package_item_id']]['CompliancePackageItem'];
				}
			}
		}

		$data = ($singleItem) ? $data[0] : $data;
	}

}
