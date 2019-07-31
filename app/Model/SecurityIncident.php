<?php
App::uses('SectionBase', 'Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('UserFields', 'UserFields.Lib');

class SecurityIncident extends SectionBase {
	public $displayField = 'title';

	public $mapping = array(
		'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false,
	);

	public $workflow = array(
		// 'pullWorkflowData' => array('SecurityIncidentStagesSecurityIncident')
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'description', 'reporter', 'victim', 'open_date', 'closure_date', 'type', 'security_incident_status_id', 'security_incident_classification_id', 'lifecycle_incomplete'
			)
		),
		'CustomFields.CustomFields',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => ['Owner']
		]
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		/*'security_service_id' => array(
			'rule' => array( 'multiple', array( 'min' => 1 ) )
		),*/
		'open_date' => array(
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
		)
	);

	public $belongsTo = array(
		'SecurityIncidentStatus',
		/*'ThirdParty' => array(
			'counterCache' => array(
				'security_incident_count' => true,
				'security_incident_open_count' => array(
					'SecurityIncident.security_incident_status_id' => SECURITY_INCIDENT_ONGOING
				)
			)
		),*/
		/*'Asset' => array(
			'counterCache' => array(
				'security_incident_open_count' => array(
					'SecurityIncident.security_incident_status_id' => SECURITY_INCIDENT_ONGOING
				)
			)
		),*/
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'SecurityIncident'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'SecurityIncident'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'SecurityIncident'
			)
		),
		'Classification' => array(
			'className' => 'SecurityIncidentClassification'
		),
		'SecurityIncidentStagesSecurityIncident'
	);

	public $hasAndBelongsToMany = array(
		'SecurityService' => array(
			'className' => 'SecurityService',
			'with' => 'SecurityIncidentsSecurityService'
		),
		'AssetRisk' => array(
			'className' => 'Risk',
			'with' => 'RisksSecurityIncident',
			'joinTable' => 'risks_security_incidents',
			'foreignKey' => 'security_incident_id',
			'associationForeignKey' => 'risk_id',
			'conditions' => array(
				'RisksSecurityIncident.risk_type' => 'asset-risk'
			)
		),
		'ThirdPartyRisk' => array(
			'className' => 'ThirdPartyRisk',
			'with' => 'RisksSecurityIncident',
			'joinTable' => 'risks_security_incidents',
			'foreignKey' => 'security_incident_id',
			'associationForeignKey' => 'risk_id',
			'conditions' => array(
				'RisksSecurityIncident.risk_type' => 'third-party-risk'
			)
		),
		'BusinessContinuity' => array(
			'className' => 'BusinessContinuity',
			'with' => 'RisksSecurityIncident',
			'joinTable' => 'risks_security_incidents',
			'foreignKey' => 'security_incident_id',
			'associationForeignKey' => 'risk_id',
			'conditions' => array(
				'RisksSecurityIncident.risk_type' => 'business-risk'
			)
		),
		'Asset',
		'ThirdParty',
		'SecurityIncidentStage'
	);

	public $findContain = array(
		'ThirdParty' => array(
			'fields' => array('name'),
			'Legal' => array(
				'fields' => array('name')
			)
		),
		'Asset' => array(
			'fields' => array('id', 'name', 'description'),
			'Legal' => array(
				'fields' => array('name')
			)
		),
		'Classification',
		'SecurityIncidentStatus' => array(
			'fields' => array('name')
		),
		'SecurityIncidentStage',
		'SecurityIncidentStagesSecurityIncident' => array(
			'Attachment',
			'Comment',
		),
		'AssetRisk' => array(
			'fields' => array('*'),
			'SecurityPolicy' => array(
				'fields' => array('id', 'index', 'use_attachments', 'url')
			)
		),
		'ThirdPartyRisk' => array(
			'fields' => array('*'),
			'SecurityPolicy' => array(
				'fields' => array('id', 'index', 'use_attachments', 'url')
			)
		),
		'BusinessContinuity' => array(
			'fields' => array('*'),
			'SecurityPolicy' => array(
				'fields' => array('id', 'index', 'use_attachments', 'url')
			)
		),
		'Comment',
		'Attachment',
		'CustomFieldValue'
	);

	/*
     * static enum: Model::function()
     * @access static
     */
    public static function types($value = null) {
        $options = array(
            self::TYPE_EVENT => __('Event'),
            self::TYPE_POSSIBLE_INCIDENT => __('Possible Incident'),
            self::TYPE_INCIDENT => __('Incident'),
        );
        return parent::enum($value, $options);
    }

    const TYPE_EVENT = 'event';
    const TYPE_POSSIBLE_INCIDENT = 'possible-incident';
    const TYPE_INCIDENT = 'incident';

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Security Incidents');
		$this->_group = 'security-operations';

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
			'risk-profile' => array(
				'label' => __('Risk Profile')
			),
			'incident-stakeholders' => array(
				'label' => __('Incident Stakeholders')
			),
			'incident-profile' => array(
				'label' => __('Incident Profile')
			)
		);

		$this->fieldData = [
			'title' => [
				'label' => __('Title'),
				'editable' => true,
				'description' => __('Give the Security Incident a title, name or code so it\'s easily identified on the menu.')
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('OPTIONAL: Describe the Security Incident in detail (when, what, where, why, whom, how). You will later update the incident using lifecycle stages or comments on the incident itself.')
			],
			'type' => [
				'label' => __('Type'),
				'editable' => true,
				'options' => [$this, 'types'],
				'description' => __('Incidents can be potential incidents or confirmed incidents. This usually is defined as the incident is investigated and confirmed.')
			],
			'open_date' => [
				'label' => __('Open Date'),
				'editable' => true,
				'description' => __('Set the date this incident was reported.')
			],
			'closure_date' => [
				'label' => __('Closure Date'),
				'editable' => true,
				'description' => __('OPTIONAL:

    If stages have not been defined (Sec. Operations / Sec. Incidents / Settings / Stages), this field is mandatory only if "Status" is "Closed"
    If stages are defined and you checkbox to "Automatically Close" then the closure date will be updated once all states are completed, you can leave this box empty')
			],
			'security_incident_status_id' => [
				'label' => __('Status'),
				'editable' => true,
				'description' => __('For the time the incident is being managed (investigated, communicated, etc.) the incident status should be open. Otherwise it could be closed.')
			],
			'auto_close_incident' => [
				'label' => __('Automatically Close Incident'),
				'editable' => true,
				'type' => 'toggle',
				'description' => __('When all items on the lifecycle are completed this incident will change to "Closed" status automatically.')
			],
			'Classification' => array(
                'label' => __('Tags'),
				'editable' => true,
				'options' => [$this, 'getClassifications'],
				'type' => 'tags',
				'description' => __('OPTIONAL: Tag this incident according to their characteristics. This can later be useful to apply filters and export data.'),
				'empty' => __('Add a tag')
            ),
			'AssetRisk' => [
				'label' => __('Related Asset Risks'),
				'editable' => true,
				'group' => 'risk-profile',
				'description' => __('OPTIONAL: If a Risk was previously documented (Risk Management / Asset Risk Management) describing a scenario where this incident could happen, select it in order to include further documentation on this incident (policies to be followed, controls used, assets affected, Etc).')
			],
			'ThirdPartyRisk' => [
				'label' => __('Related Third Party Risks'),
				'editable' => true,
				'group' => 'risk-profile',
				'description' => __('OPTIONAL: If a Risk was previously documented (Risk Management / Third Party Risk Management) describing a scenario where this incident could happen, select it in order to include further documentation on this incident (policies to be followed, controls used, assets affected, Etc).')
			],
			'BusinessContinuity' => [
				'label' => __('Related Business Risks'),
				'editable' => true,
				'group' => 'risk-profile',
				'description' => __('OPTIONAL: If a Risk was previously documented (Risk Management / Business Impact Analysis) describing a scenario where this incident could happen, select it in order to include further documentation on this incident (policies to be followed, controls used, assets affected, Etc).')
			],
			'Owner' => $UserFields->getFieldDataEntityData($this, 'Owner', [
				'label' => __('Owner'),
				'group' => 'incident-stakeholders',
				'description' => __('Is the individual in charge of managing the incident')
			]),
			'reporter' => [
				'label' => __('Reporter'),
				'editable' => true,
				'group' => 'incident-stakeholders',
				'description' => __('OPTIONAL: Is the individual that reported the incident, could be the same as the owner. If unknown at the time the incident was reported this field can be updated later.')
			],
			'victim' => [
				'label' => __('Victim'),
				'editable' => true,
				'group' => 'incident-stakeholders',
				'description' => __('OPTIONAL: Is the individual that has been affected by the incident. If unknown at the time the incident was reported this field can be updated later.')
			],
			'SecurityService' => [
				'label' => __('Affected Compensating Controls'),
				'editable' => true,
				'group' => 'incident-profile',
				'description' => __('OPTIONAL: Select one or more controls (from Control Catalogue / Security Services) that are involved on this incident. This fields might autocomplete as you select risks (on the previous tab).')
			],
			'Asset' => [
				'label' => __('Affected Asset'),
				'editable' => true,
				'group' => 'incident-profile',
				'description' => __('OPTIONAL: Select one or more assets (from Asset Managemnet / Asset Identification) that are involved on this incident. This fields might autocomplete as you select risks (on the previous tab).')
			],
			'ThirdParty' => [
				'label' => __('Affected Third Parties'),
				'editable' => true,
				'group' => 'incident-profile',
				'description' => __('OPTIONAL: Select one or more assets (from Organisation / Third Parties) that are involved on this incident. This fields might autocomplete as you select risks (on the previous tab).')
			],
			'expired' => [
				'label' => __('Expired'),
				'editable' => false,
			],
			'security_incident_classification_id' => [
				'label' => __('Security Incident Classification'),
				'editable' => false,
			],
			'lifecycle_incomplete' => [
				'label' => __('Lifecycle Incomplete'),
				'editable' => false,
			],
			'ongoing_incident' => [
				'label' => __('Ongoing Incident'),
				'editable' => false,
			],
			'SecurityIncidentStagesSecurityIncident' => [
				'label' => __('Security Incident Stages Security Incident'),
				'editable' => false,
			],
			'SecurityIncidentStage' => [
				'label' => __('Security Incident Stage'),
				'editable' => false,
			],
		];

		// $this->filterArgs = array(
		// 	'search' => array(
		// 		'type' => 'like',
		// 		'field' => array('SecurityIncident.title', 'SecurityIncident.description'),
		// 		'_name' => __('Search')
		// 	),
		// 	'asset_id' => array(
		// 		'type' => 'subquery',
		// 		'method' => 'findByAsset',
		// 		'field' => 'SecurityIncident.id',
		// 		'_name' => __('Asset')
		// 	),
		// 	'third_party_id' => array(
		// 		'type' => 'subquery',
		// 		'method' => 'findByThirdParty',
		// 		'field' => 'SecurityIncident.id',
		// 		'_name' => __('Third Party')
		// 	)
		// );

		$migrateRecords = array(
			'SecurityService',
			'ThirdParty',
			'AssetRisk',
			'ThirdPartyRisk',
			'BusinessContinuity',
			'Asset'
		);

		$this->mapping['statusManager'] = array(
			'status' => array(
				'column' => 'security_incident_status_id',
				'migrateRecords' => $migrateRecords,
				'toggles' => array(
					'open' => array(
						'value' => SECURITY_INCIDENT_ONGOING,
						'message' => __('The Security Incident %s has been opened'),
						'messageArgs' => array(
							0 => '%Current%.title'
						)
					),
					'close' => array(
						'value' => SECURITY_INCIDENT_CLOSED,
						'message' => __('The Security Incident %s has been closed'),
						'messageArgs' => array(
							0 => '%Current%.title'
						)
					)
				)
			),

			'lifecycleIncomplete' => array(
				'column' => 'lifecycle_incomplete',
				//'fn' => 'statusLifecycleIncomplete',
				'toggles' => array(
					array(
						'value' => 1,
						'message' => __('The lifecycle step %s for the Security Incident %s has been tagged as not completed'),
						'messageArgs' => array(
							0 => array(
								'type' => 'fn',
								'fn' => array('lastIncompleteStep'),
							),
							1 => '%Current%.title',
						)
					),
					array(
						'value' => 0,
						'message' => __('All lifecycle stages for the Security Incident %s have been completed'),
						'messageArgs' => array(
							0 => '%Current%.title'
						)
					)
				)
			),

			'ongoingIncident' => array(
				'column' => 'ongoing_incident',
				'fn' => 'statusOngoingIncident',
				'migrateRecords' => $migrateRecords,

				'toggles' => array(
					array(
						'value' => 1,
						'listeners' => array(
							'securityServiceAdd',
							'thirdPartyAdd',
							'assetRiskAdd',
							'thirdPartyRiskAdd',
							'businessContinuityAdd',
							'assetAdd'
						)
					),
					array(
						'value' => 0,
						'listeners' => array(
							'securityServiceRemove',
							'thirdPartyRemove',
							'assetRiskRemove',
							'thirdPartyRiskRemove',
							'businessContinuityRemove',
							'assetRemove'
						)
					),
				),

				'listeners' => array(
					'securityServiceAdd' => array(
						'type' => 'add',
						'field' => 'security_service_id',
						'model' => 'SecurityService',
						'message' => __('The Security Incident %s has been mapped to the Security Service %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),
					'securityServiceRemove' => array(
						'type' => 'remove',
						'field' => 'security_service_id',
						'model' => 'SecurityService',
						'message' => __('The Security Incident %s that has been mapped do the Security Service %s is no longer mapped'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),

					'thirdPartyAdd' => array(
						'type' => 'add',
						'field' => 'third_party_id',
						'model' => 'ThirdParty',
						'message' => __('The Security Incident %s has been mapped to the Third Party %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),
					'thirdPartyRemove' => array(
						'type' => 'remove',
						'field' => 'third_party_id',
						'model' => 'ThirdParty',
						'message' => __('The Security Incident %s that has been mapped do the Third Party %s is no longer mapped'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),

					'assetRiskAdd' => array(
						'type' => 'add',
						'field' => 'asset_risk_id',
						'model' => 'AssetRisk',
						'message' => __('The Security Incident %s has been mapped to the Risk %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),
					'assetRiskRemove' => array(
						'type' => 'remove',
						'field' => 'asset_risk_id',
						'model' => 'AssetRisk',
						'message' => __('The Security Incident %s that has been mapped do the Risk %s is no longer mapped'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),

					'thirdPartyRiskAdd' => array(
						'type' => 'add',
						'field' => 'third_party_risk_id',
						'model' => 'ThirdPartyRisk',
						'message' => __('The Security Incident %s has been mapped to the Third Party Risk %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),
					'thirdPartyRiskRemove' => array(
						'type' => 'remove',
						'field' => 'third_party_risk_id',
						'model' => 'ThirdPartyRisk',
						'message' => __('The Security Incident %s that has been mapped do the Third Party Risk %s is no longer mapped'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),

					'businessContinuityAdd' => array(
						'type' => 'add',
						'field' => 'business_risk_id',
						'model' => 'BusinessContinuity',
						'message' => __('The Security Incident %s has been mapped to the Business Continuity %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),
					'businessContinuityRemove' => array(
						'type' => 'remove',
						'field' => 'business_risk_id',
						'model' => 'BusinessContinuity',
						'message' => __('The Security Incident %s that has been mapped do the Business Continuity %s is no longer mapped'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),

					'assetAdd' => array(
						'type' => 'add',
						'field' => 'asset_id',
						'model' => 'Asset',
						'message' => __('The Security Incident %s has been mapped to the Asset %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),
					'assetRemove' => array(
						'type' => 'remove',
						'field' => 'asset_id',
						'model' => 'Asset',
						'message' => __('The Security Incident %s that has been mapped do the Asset %s is no longer mapped'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					)
				)
			)
		);

		/*$this->mapping['statusManager'] = array(
			'ongoing_incident' => array(
				'migrateRecords' => array('SecurityService'),
				'listeners' => array(
					array(
						'type' => 'add',
						'field' => 'security_service_id',
						'model' => 'SecurityService',
						'message' => __('The Security Incident %s has been mapped to the Security Service %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),
					array(
						'type' => 'remove',
						'field' => 'security_service_id',
						'model' => 'SecurityService',
						'message' => __('The Security Incident %s that has been mapped do the Security Service %s is no longer mapped'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),*/
				
					/*array(
						'field' => 'third_party_id',
						'model' => 'ThirdParty',
						'message' => __('The Security Incident %s has been mapped to the Third Party %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					),
					array(
						'field' => 'asset_risk_id',
						'model' => 'AssetRisk',
						'message' => __('The Security Incident %s has been mapped to the Risk %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),
					array(
						'field' => 'third_party_risk_id',
						'model' => 'ThirdPartyRisk',
						'message' => __('The Security Incident %s has been mapped to the Third Party Risk %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),
					array(
						'field' => 'business_risk_id',
						'model' => 'BusinessContinuity',
						'message' => __('The Security Incident %s has been mapped to the Business Impact Analysis %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.title'
						)
					),
					array(
						'field' => 'asset_id',
						'model' => 'Asset',
						'message' => __('The Security Incident %s has been mapped to the Asset %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Related%.name'
						)
					)*/
				/*)
			)
		);*/

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'title' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.title',
						'field' => 'SecurityIncident.id',
					)
				),
				'type' => array(
					'type' => 'select',
					'name' => __('Type'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.type',
						'field' => 'SecurityIncident.id',
					),
					'data' => array(
						'method' => 'getTypes',
						'result_key' => true,
						'empty' => __('All')
					),
				),
				'classification_id' => array(
					'type' => 'multiple_select',
					'name' => __('Tag'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Classification.name',
						'field' => 'SecurityIncident.id',
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
				'open_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Open Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.open_date',
						'field' => 'SecurityIncident.id',
					),
				),
				'closure_date' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Closed Date'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.closure_date',
						'field' => 'SecurityIncident.id',
					),
				),
				'security_incident_status_id' => array(
					'type' => 'select',
					'name' => __('Status'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.security_incident_status_id',
						'field' => 'SecurityIncident.id',
					),
					'data' => array(
						'method' => 'getStatuses',
						'result_key' => true,
						'empty' => __('All')
					),
				),
			),
			__('Risk Profile') => array(
				'asset_risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Asset Risks'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'AssetRisk.id',
						'field' => 'SecurityIncident.id',
					),
					'data' => array(
						'method' => 'getAssetRisks',
					),
					'many' => true,
					'contain' => array(
						'AssetRisk' => array(
							'title'
						)
					),
				),
				'third_party_risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Third Party Risks'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ThirdPartyRisk.id',
						'field' => 'SecurityIncident.id',
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
				'business_risk_id' => array(
					'type' => 'multiple_select',
					'name' => __('Business Risks'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'BusinessContinuity.id',
						'field' => 'SecurityIncident.id',
					),
					'data' => array(
						'method' => 'getBusinessRisks',
					),
					'many' => true,
					'contain' => array(
						'BusinessContinuity' => array(
							'title'
						)
					),
				),
			),
			__('Stakeholders') => array(
				'owner_id' => $UserFields->getAdvancedFilterFieldData('SecurityIncident', 'Owner', [
					'name' => __('Owner'),
					'show_default' => false
				]),
				'reporter' => array(
					'type' => 'text',
					'name' => __('Reporter'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.reporter',
						'field' => 'SecurityIncident.id',
					),
				),
				'victim' => array(
					'type' => 'text',
					'name' => __('Victim'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.victim',
						'field' => 'SecurityIncident.id',
					),
				),
			),
			__('Incident Profile') => array(
				'security_service_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compensating Controls'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityService.id',
						'field' => 'SecurityIncident.id',
					),
					'data' => array(
						'method' => 'getSecurityServices',
					),
					'many' => true,
					'contain' => array(
						'SecurityService' => array(
							'name'
						)
					),
				),
				'asset_id' => array(
					'type' => 'multiple_select',
					'name' => __('Compromised Assets'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Asset.id',
						'field' => 'SecurityIncident.id',
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
				'third_party_id' => array(
					'type' => 'multiple_select',
					'name' => __('Third Parties'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ThirdParty.id',
						'field' => 'SecurityIncident.id',
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
			),
			__('Status') => array(
				'ongoing_incident' => array(
					'type' => 'select',
					'name' => __('Ongoing Incident'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.ongoing_incident',
						'field' => 'SecurityIncident.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'result_key' => true,
						'empty' => __('All')
					),
				),
				'lifecycle_incomplete' => array(
					'type' => 'select',
					'name' => __('Lifecycle Incomplete'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.lifecycle_incomplete',
						'field' => 'SecurityIncident.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'result_key' => true,
						'empty' => __('All')
					),
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Security Incident'),
			'pdf_file_name' => __('security_incidents'),
			'csv_file_name' => __('security_incidents'),
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

	public function getObjectStatusConfig() {
        return [
            'expired' => [
            	'title' => __('Expired'),
                'callback' => [$this, 'statusExpired'],
            ],
            'ongoing_incident' => [
            	'title' => __('Ongoing Incident'),
                'callback' => [$this, '_statusOngoingIncident'],
                'trigger' => [
                    $this->Asset,
                    $this->SecurityService,
                    $this->AssetRisk,
                    $this->ThirdPartyRisk,
                    $this->BusinessContinuity,
                ]
            ],
            'lifecycle_incomplete' => [
            	'title' => __('Lifecycle Incomplete'),
                'callback' => [$this, '_statusLifecycleIncomplete'],
            ]
        ];
    }

    public function statusExpired($conditions = null) {
        return parent::statusExpired([
            'SecurityIncident.closure_date < NOW()'
        ]);
    }

    public function _statusOngoingIncident() {
    	return (boolean) $this->statusOngoingIncident($this->id);
	}

	public function _statusLifecycleIncomplete() {
		$data = $this->SecurityIncidentStagesSecurityIncident->find('count', [
			'conditions' => [
				'SecurityIncidentStagesSecurityIncident.security_incident_id' => $this->id,
				'SecurityIncidentStagesSecurityIncident.status' => 0
			],
			'recursive' => -1
		]);

		return (boolean) $data;
	}

	public function beforeValidate($options = array()) {
		$ret = true;

		$condAutoClose = $this->stagesExist() && !empty($this->data['SecurityIncident']['auto_close_incident']);
		$condClosed = !empty($this->data['SecurityIncident']['security_incident_status_id']) && $this->data['SecurityIncident']['security_incident_status_id'] != SECURITY_INCIDENT_CLOSED;

		if ($condAutoClose || $condClosed) {
			$this->validate['closure_date']['notEmpty']['required'] = false;
			$this->validate['closure_date']['notEmpty']['allowEmpty'] = true;
		}

		return $ret;
	}

	public function beforeSave($options = array()) {
		$this->transformDataToHabtm(['SecurityService', 'AssetRisk', 'ThirdPartyRisk', 'BusinessContinuity',
			'Asset', 'ThirdParty'
		]);

		$this->setHabtmConditionsToData(['AssetRisk', 'ThirdPartyRisk', 'BusinessContinuity']);

		return true;
	}

	public function afterSave($created, $options = array()) {
		$ret = true;
		if ($created) {
			$ret &= $this->joinStages($this->id);
		}

		if (isset($this->data['SecurityIncident']['Classification'])) {
			$this->Classification->deleteAll(['Classification.security_incident_id' => $this->id]);
			$this->joinClassifications($this->data['SecurityIncident']['Classification'], $this->id);
		}

		return $ret;
	}

	public function joinStages($incident_id){
		$stages = $this->SecurityIncidentStage->getStagesList();

		$data = array();
		if(!empty($stages )){
			foreach ($stages as $key => $name) {
				$data[] = array(
					'security_incident_stage_id' => $key,
					'security_incident_id' => $incident_id,
				);
			}

			if ($this->SecurityIncidentStagesSecurityIncident->saveAll($data)){
				return true;
			}
			return false;
		}
		else{
			return true;
		}
	}

	protected function getJoinHabtmSaveData($model, $list, $id) {
		$saveData = parent::getJoinHabtmSaveData($model, $list, $id);

		$assoc = $this->getAssociated($model);
		foreach ($saveData as $key => $data) {
			if (in_array($model, array('AssetRisk', 'ThirdPartyRisk', 'BusinessContinuity'))) {
				$saveData[$key]['risk_type'] = $assoc['conditions']['RisksSecurityIncident.risk_type'];
			}
		}

		return $saveData;
	}

	public function joinClassifications($labels, $id) {
		if (empty($labels)) {
			return true;
		}

		$labels = explode(',', $labels);

		foreach ($labels as $name) {
			$tmp = array(
				'security_incident_id' => $id,
				'name' => $name
			);

			$this->Classification->create();
			if (!$this->Classification->save($tmp)) {
				return false;
			}
		}

		return true;
	}

	public function getSecurityServices() {
		$data = $this->SecurityService->find('list', array(
			'order' => array('SecurityService.name' => 'ASC'),
			'fields' => array('SecurityService.id', 'SecurityService.name'),
			'recursive' => -1
		));
		return $data;
	}

	public function getAssets() {
		$data = $this->Asset->find('list', array(
			'order' => array('Asset.name' => 'ASC'),
			'fields' => array('Asset.id', 'Asset.name'),
			'recursive' => -1
		));
		return $data;
	}

	public function getThirdParties() {
		$data = $this->ThirdParty->find('list', array(
			'order' => array('ThirdParty.name' => 'ASC'),
			'fields' => array('ThirdParty.id', 'ThirdParty.name'),
			'recursive' => -1
		));
		return $data;
	}

	public function getBusinessRisks() {
		$data = $this->BusinessContinuity->find('list', array(
			'order' => array('BusinessContinuity.title' => 'ASC'),
			'fields' => array('BusinessContinuity.id', 'BusinessContinuity.title'),
			'recursive' => -1
		));
		return $data;
	}

	public function getThirdPartyRisks() {
		$data = $this->ThirdPartyRisk->find('list', array(
			'order' => array('ThirdPartyRisk.title' => 'ASC'),
			'fields' => array('ThirdPartyRisk.id', 'ThirdPartyRisk.title'),
			'recursive' => -1
		));
		return $data;
	}

	public function getAssetRisks() {
		$data = $this->AssetRisk->find('list', array(
			'order' => array('AssetRisk.title' => 'ASC'),
			'fields' => array('AssetRisk.id', 'AssetRisk.title'),
			'recursive' => -1
		));
		return $data;
	}

	public function getTypes() {
		return getSecurityIncidentTypes();
	}

	public function getClassifications() {
		$data = $this->Classification->find('list', array(
			'order' => array('Classification.name' => 'ASC'),
			'fields' => array('Classification.id', 'Classification.name'),
			'group' => array('Classification.name'),
			'recursive' => -1
		));
		$data = array_combine($data, $data);
		return $data;
	}

	public function getStatuses() {
		$data = $this->SecurityIncidentStatus->find('list', array(
			'order' => array('SecurityIncidentStatus.name' => 'ASC'),
			'fields' => array('SecurityIncidentStatus.id', 'SecurityIncidentStatus.name'),
			'recursive' => -1
		));
		return $data;
	}

	public function findByClassifications($data) {
		$this->Classification->Behaviors->attach('Containable', array('autoFields' => false));
		$this->Classification->Behaviors->attach('Search.Searchable');

		$query = $this->Classification->getQuery('all', array(
			'conditions' => array(
				'Classification.id ' => $data['classification_id']
			),
			'contain' => array(),
			'fields' => array(
				'Classification.security_incident_id'
			),
		));

		return $query;
	}

	public function statusOngoingIncident($id) {
		$incidents = $this->SecurityIncidentsSecurityService->find('count', array(
			'conditions' => array(
				'SecurityIncidentsSecurityService.security_incident_id' => $id
			)
		));

		$incidents = $incidents || $this->SecurityIncidentsThirdParty->find('count', array(
			'conditions' => array(
				'SecurityIncidentsThirdParty.security_incident_id' => $id
			)
		));

		$incidents = $incidents || $this->RisksSecurityIncident->find('count', array(
			'conditions' => array(
				'RisksSecurityIncident.security_incident_id' => $id,
				'RisksSecurityIncident.risk_type' => 'asset-risk'
			)
		));

		$incidents = $incidents || $this->RisksSecurityIncident->find('count', array(
			'conditions' => array(
				'RisksSecurityIncident.security_incident_id' => $id,
				'RisksSecurityIncident.risk_type' => 'third-party-risk'
			)
		));

		$incidents = $incidents || $this->RisksSecurityIncident->find('count', array(
			'conditions' => array(
				'RisksSecurityIncident.security_incident_id' => $id,
				'RisksSecurityIncident.risk_type' => 'business-risk'
			)
		));

		$incidents = $incidents || $this->AssetsSecurityIncident->find('count', array(
			'conditions' => array(
				'AssetsSecurityIncident.security_incident_id' => $id
			)
		));

		if ($incidents) {
			return 1;
		}

		return 0;
	}

	public function statusLifecycleIncomplete($id) {
		$conditions = array(
			'SecurityIncidentStagesSecurityIncident.security_incident_id' => $id,
			'SecurityIncidentStagesSecurityIncident.status' => 0
		);
		$lifecycle = $this->SecurityIncidentStagesSecurityIncident->getItem($conditions);

		if(!empty($lifecycle)){
			//is incomplete
			return 1;
		}
		
		//is complete
		return 0;
	}

	public function lastIncompleteStep($id) {
		$stage = $this->SecurityIncidentStagesSecurityIncident->find('first', array(
			'conditions' => array(
				'SecurityIncidentStagesSecurityIncident.security_incident_id' => $id,
				'SecurityIncidentStagesSecurityIncident.status' => 0
			),
			'order' => array('SecurityIncidentStagesSecurityIncident.modified' => 'DESC'),
			'recursive' => 0
		));
// debug($stage);
		if (!empty($stage)) {
			return $stage['SecurityIncidentStage']['name'];
		}

		return false;
	}

	public function getSecurityIncidentTypes() {
		if (isset($this->data['SecurityIncident']['type'])) {
			return getSecurityIncidentTypes($this->data['SecurityIncident']['type']);
		}

		return false;
	}

	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'closure_date');
	}

	public function expiredStatusToQuery($expiredField = 'expired', $dateField = 'date') {
		if (!isset($this->data['SecurityIncident']['expired']) && isset($this->data['SecurityIncident']['closure_date'])) {
			$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
			if ($this->data['SecurityIncident']['closure_date'] < $today && $this->data['SecurityIncident']['security_incident_status_id'] == SECURITY_INCIDENT_ONGOING) {
				$this->data['SecurityIncident']['expired'] = '1';
			}
			else {
				$this->data['SecurityIncident']['expired'] = '0';
			}
		}
	}

	public function getSecurityIncidentStatuses() {
		if (isset($this->data['SecurityIncident']['security_incident_status_id'])) {
			$status = $this->SecurityIncidentStatus->find('first', array(
				'conditions' => array(
					'SecurityIncidentStatus.id' => $this->data['SecurityIncident']['security_incident_status_id']
				),
				'fields' => array('name'),
				'recursive' => -1
			));

			return $status['SecurityIncidentStatus']['name'];
		}

		return false;
	}

	public function logExpirations($ids) {
		$this->logToModel('SecurityService', $ids);
	}

	public function logToModel($model, $ids = array()) {
		$assocId = $this->hasAndBelongsToMany[$model]['associationForeignKey'];

		$habtmModel = $this->hasAndBelongsToMany[$model]['with'];

		$this->{$habtmModel}->bindModel(array(
			'belongsTo' => array('SecurityIncident')
		));

		//security_incident_id
		$foreignKey = $this->hasAndBelongsToMany[$model]['foreignKey'];
		$data = $this->{$habtmModel}->find('all', array(
			'conditions' => array(
				$habtmModel . '.' . $foreignKey => $ids
			),
			'fields' => array($habtmModel . '.' . $assocId, 'SecurityIncident.title'),
			'recursive' => 0
		));

		foreach ($data as $item) {
			$msg = __('Security Incident "%s" expired', $item['SecurityIncident']['title']);

			$this->{$model}->id = $item[$habtmModel][$assocId];
			$this->{$model}->addNoteToLog($msg);
			$this->{$model}->setSystemRecord($item[$habtmModel][$assocId], 2);
		}

	}

	public function getSecurityIncidentsList($conditions = array()){
		$data = $this->find('list', array(
			'conditions' => $conditions
		));
		return $data;
	}

	public function getSecurityIncident($conditions = array()){
		$data = $this->find('first', array(
			'conditions' => $conditions
		));
		return $data;
	}

	public function updateLifecycleIncomplete($id, $inComplete){
		$incident = $this->getSecurityIncident(array('SecurityIncident.id' => $id));
		// debug($inComplete);
		// debug($incident['SecurityIncident']['lifecycle_incomplete']);
		if($incident['SecurityIncident']['lifecycle_incomplete'] != $inComplete){

			$ret = $this->triggerStatus('lifecycleIncomplete', $id, 'before');

			$ret &= $this->updateAll(
				array(
					'SecurityIncident.lifecycle_incomplete' => (int)$inComplete,
					'SecurityIncident.modified' => 'NOW()',
				),
				array('SecurityIncident.id' => $id)
			);

			$ret &= $this->triggerStatus('lifecycleIncomplete', $id, 'after');

			// Auto-close feature for incident is enabled
			if (!empty($incident['SecurityIncident']['auto_close_incident']) && empty($inComplete)) {
				$ret &= $this->triggerStatus('status', $id, 'before');

				$ret &= $this->updateAll(
					array(
						'SecurityIncident.security_incident_status_id' => SECURITY_INCIDENT_CLOSED,
						'SecurityIncident.closure_date' => 'NOW()',
						'SecurityIncident.modified' => 'NOW()',
					),
					array('SecurityIncident.id' => $id)
				);

				$ret &= $this->triggerStatus('status', $id, 'after');
			}

			if ($ret) {
				return true;
			}
			return false;
		}
	}

	public function findByThirdParty($data = array()) {
		$this->SecurityIncidentsThirdParty->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->SecurityIncidentsThirdParty->Behaviors->attach('Search.Searchable');

		$query = $this->SecurityIncidentsThirdParty->getQuery('all', array(
			'conditions' => array(
				'SecurityIncidentsThirdParty.third_party_id' => $data['third_party_id']
			),
			'fields' => array(
				'SecurityIncidentsThirdParty.security_incident_id'
			)
		));

		return $query;
	}

	public function findByAsset($data = array()) {
		$this->AssetsSecurityIncident->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->AssetsSecurityIncident->Behaviors->attach('Search.Searchable');

		$query = $this->AssetsSecurityIncident->getQuery('all', array(
			'conditions' => array(
				'AssetsSecurityIncident.asset_id' => $data['asset_id']
			),
			'fields' => array(
				'AssetsSecurityIncident.security_incident_id'
			)
		));

		return $query;
	}

	public function getRiskWithProcedures($riskIds, $model) {
		return $this->{$model}->getProcedures($riskIds);
	}

	public function stagesExist() {
		return ((boolean) $this->SecurityIncidentStage->find('count'));
	}

}
