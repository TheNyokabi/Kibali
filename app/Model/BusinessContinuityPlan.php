<?php
App::uses('SectionBase', 'Model');
App::uses('UserFields', 'UserFields.Lib');

class BusinessContinuityPlan extends SectionBase {
	public $displayField = 'title';
	
	public $mapping = array(
		'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'title', 'objective', 'audit_metric', 'audit_success_criteria', 'launch_criteria', 'security_service_type_id', 'opex', 'capex', 'resource_utilization', 'regular_review', 'awareness_recurrence'
			)
		),
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => [
				'LaunchInitiator' => [
					'mandatory' => false
				],
				'Sponsor' => [
					'mandatory' => false
				],
				'Owner' => [
					'mandatory' => false
				]
			]
		]
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'objective' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'audit_metric' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'audit_success_criteria' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'security_service_type_id' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		'opex' => array(
			'rule' => 'numeric',
			'required' => true
		),
		'capex' => array(
			'rule' => 'numeric',
			'required' => true
		),
		'resource_utilization' => array(
			'rule' => 'numeric',
			'required' => true
		)
	);

	public $belongsTo = array(
		'SecurityServiceType'
	);

	public $hasMany = array(
		'BusinessContinuityTask',
		'BusinessContinuityPlanAudit',
		'BusinessContinuityPlanAuditDate',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'BusinessContinuityPlan'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'BusinessContinuityPlan'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'BusinessContinuityPlan'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'BusinessContinuity'
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Business Continuity Plans');
		$this->_group = parent::SECTION_GROUP_CONTROL_CATALOGUE;

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
			'audits' => [
				'label' => __('Audits')
			]
		];

		$this->fieldData = [
			'title' => [
				'label' => __('Title'),
				'editable' => true,
				'description' => __('The name for this Continuity Plan')
			],
			'objective' => [
				'label' => __('Objective'),
				'editable' => true,
				'description' => __('Describe the plan objective, it should be something short and straightforward to understand')
			],
			'launch_criteria' => [
				'label' => __('Launch Criteria'),
				'editable' => true,
				'description' => __('OPTIONAL: Describe the criteria the plan initiator should use to trigger this continuity plan.')
			],
			'Owner' => $UserFields->getFieldDataEntityData($this, 'Owner', [
				'label' => __('Owner'), 
				'description' => __('The owner of the plan is usually the individual that is held responsible for the plan management.')
			]),
			'Sponsor' => $UserFields->getFieldDataEntityData($this, 'Sponsor', [
				'label' => __('Sponsor'), 
				'description' => __('Who is responsible for keeping this plan realitistic, communicated and applicable?')
			]),
			'LaunchInitiator' => $UserFields->getFieldDataEntityData($this, 'LaunchInitiator', [
				'label' => __('Launch Initiator'), 
				'description' => __('The Launch Initiator is the person who is authorized to launch or declare the need for the plan.')
			]),
			'opex' => [
				'label' => __('Cost (OPEX)'),
				'editable' => true,
				'description' => __('Describe the associated OPEX for of this Control')
			],
			'capex' => [
				'label' => __('Cost (CAPEX)'),
				'editable' => true,
				'description' => __('Describe the associated CAPEX for this Control')
			],
			'resource_utilization' => [
				'label' => __('Resource Utilization'),
				'editable' => true,
				'description' => __('The amount of days required to keep the plan operative. For example, 4 people need to work on the plan at least 5 days to ensure is audited, operational, Etc. That would make 20 days of effort (in terms of cost).')
			],
			'security_service_type_id' => [
				'label' => __('Status'),
				'editable' => true,
				'description' => __('The plan can be either in "Design" or "Production" phases. If the plan is set to "Design" it will not be shown on the rest of the system and audits will not be available')
			],
			'regular_review' => [
				'label' => __('Regular Review'),
				'editable' => false,
			],
			'awareness_recurrence' => [
				'label' => __('Awareness Recurrence'),
				'editable' => false,
			],
			'audits_all_done' => [
				'label' => __('Audits All Done'),
				'editable' => false,
			],
			'audits_last_missing' => [
				'label' => __('Audits Last missing'),
				'editable' => false,
			],
			'audits_last_passed' => [
				'label' => __('Audits Last passed'),
				'editable' => false,
			],
			'audits_improvements' => [
				'label' => __('Audits Improvements'),
				'editable' => false,
			],
			'ongoing_corrective_actions' => [
				'label' => __('Ongoing Corrective Actions'),
				'editable' => false,
			],
			'audit_success_criteria' => [
				'label' => __('Audit Methodology'),
				'editable' => true,
				'group' => 'audits',
				'description' => __('Define how this continiuty plan will be tested at regular point in time.)')
			],
			'audit_metric' => [
				'label' => __('Audit Success Metric Criteria'),
				'editable' => true,
				'group' => 'audits',
				'description' => __('What criteria will be used to determine if the plan worked or not.')
			],
			'BusinessContinuityTask' => [
				'label' => __('Business Continuity Task'),
				'editable' => false,
			],
			'BusinessContinuityPlanAudit' => [
				'label' => __('Business Continuity Plan Audit'),
				'editable' => false,
			],
			'BusinessContinuityPlanAuditDate' => [
				'label' => __('Business Continuity Plan Audit Date'),
				'editable' => false,
			],
			'BusinessContinuity' => [
				'label' => __('Business Continuity'),
				'editable' => false,
			],
		];

		parent::__construct($id, $table, $ds);

		$ongoingCorrectiveActions = $this->getStatusConfig('ongoingCorrectiveActions', 'title'/*, $migrateRecords*/);
		unset($ongoingCorrectiveActions['customValues']);
		unset($ongoingCorrectiveActions['toggles']);

		$this->mapping['statusManager'] = array(
			'auditsLastFailed' => $this->getStatusTemplate('auditsLastFailed', array(
				'migrateRecords' => array()
			)),
			'auditsLastMissing' => $this->getStatusTemplate('auditsLastMissing', array(
				'migrateRecords' => array()
			)),
			'status' => $this->getStatusConfig('SecurityServiceStatus', 'title'/*, $migrateRecords*/),
			'ongoingCorrectiveActions' => $ongoingCorrectiveActions
		);
	}

	public function getObjectStatusConfig() {
        return [
            'audits_last_passed' => [
            	'title' => __('Last audit failed'),
                'callback' => [$this, 'statusAuditsLastFailed'],
                'type' => 'danger'
            ],
            'audits_last_missing' => [
            	'title' => __('Last audit missing'),
            	'callback' => [$this, 'statusAuditsLastMissing'],
            ],
            'ongoing_corrective_actions' => [
            	'title' => __('Ongoing Corrective Actions'),
            	'callback' => [$this, '_statusOngoingCorrectiveActions'],
            	'type' => 'improvement'
            ],
        ];
    }

    public function statusAuditsLastFailed() {
    	$data = $this->BusinessContinuityPlanAudit->find('first', [
			'conditions' => [
				'BusinessContinuityPlanAudit.business_continuity_plan_id' => $this->id,
				'BusinessContinuityPlanAudit.planned_date <= NOW()'
			],
			'fields' => [
				'BusinessContinuityPlanAudit.id',
				'BusinessContinuityPlanAudit.result',
				'BusinessContinuityPlanAudit.planned_date',
			],
			'order' => ['BusinessContinuityPlanAudit.planned_date' => 'DESC'],
			'recursive' => -1
		]);

		return (empty($data) || (!empty($data) && in_array($data['BusinessContinuityPlanAudit']['result'], [1, null])));
    }

    public function statusAuditsLastMissing() {
    	$data = $this->BusinessContinuityPlanAudit->find('first', [
			'conditions' => [
				'BusinessContinuityPlanAudit.business_continuity_plan_id' => $this->id,
				'BusinessContinuityPlanAudit.planned_date < NOW()'
			],
			'fields' => [
				'BusinessContinuityPlanAudit.id',
				'BusinessContinuityPlanAudit.result',
				'BusinessContinuityPlanAudit.planned_date'
			],
			'order' => ['BusinessContinuityPlanAudit.planned_date' => 'DESC'],
			'recursive' => -1
		]);

		return (!empty($data) && $data['BusinessContinuityPlanAudit']['result'] === null);
    }

	public function beforeSave($options = array()) {
		$this->updateAuditMetricAndCriteria();

		$conds = isset($this->data['BusinessContinuityPlan']['security_service_type_id']);
		$conds = $conds && $this->data['BusinessContinuityPlan']['security_service_type_id'] == SECURITY_SERVICE_DESIGN;
		$conds &= isset($this->data['BusinessContinuityPlan']['id']);

		if ($conds) {
			$this->deleteProductionJoins();
		}

		return true;
	}

	public function afterSave($created, $options = array()) {
		if (!empty($this->id)) {
			// $this->BusinessContinuity->pushStatusRecords();
			// $ret = $this->BusinessContinuity->saveCustomStatuses($this->getBusinessPlansBusinessContinuities($this->id));
			// $this->BusinessContinuity->holdStatusRecords();

			// return $ret;
		}

		if (isset($this->data['BusinessContinuityPlanAuditDate'])) {
			$this->BusinessContinuityPlanAuditDate->deleteAll(array(
				'BusinessContinuityPlanAuditDate.business_continuity_plan_id' => $this->id
			));
			if (!empty($this->data['BusinessContinuityPlanAuditDate'])) {
				$this->saveAuditDateJoins($this->data['BusinessContinuityPlanAuditDate'], $this->id);
				$this->saveAuditsJoins($this->data['BusinessContinuityPlanAuditDate'], $this->id);
			}
		}

		return true;
	}

	public function deleteProductionJoins() {
		$this->BusinessContinuitiesBusinessContinuityPlan->deleteAll(array(
			'BusinessContinuitiesBusinessContinuityPlan.business_continuity_plan_id' => $this->data['BusinessContinuityPlan']['id']
		));
	}

	public function statusProcess($id, $column) {
		if ($column == 'audits_last_passed' || $column == 'audits_last_missing') {
			$statuses = $this->BusinessContinuityPlanAudit->getStatuses($id);
		}

		return $statuses[$column];
	}

	public function _statusOngoingCorrectiveActions() {
		$data = $this->BusinessContinuityPlanAudit->BusinessContinuityPlanAuditImprovement->find('count', [
			'conditions' => [
				'BusinessContinuityPlanAudit.id' => $this->id
			],
			'joins' => [
				[
					'table' => 'business_continuity_plan_audits',
					'alias' => 'BusinessContinuityPlanAudit',
					'type' => 'INNER',
					'conditions' => [
						'BusinessContinuityPlanAudit.id = BusinessContinuityPlanAuditImprovement.business_continuity_plan_audit_id'
					]
				],
			],
			'recursive' => -1
		]);

		return (boolean) $data;
	}

	/**
	 * @deprecated status, in favor of BusinessContinuityPlan::_statusOngoingCorrectiveActions()
	 */
	public function statusOngoingCorrectiveActions($id) {
		$auditIds = $this->BusinessContinuityPlanAudit->find('list', array(
			'conditions' => array(
				'BusinessContinuityPlanAudit.business_continuity_plan_id' => $id
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$ret = $this->BusinessContinuityPlanAudit->BusinessContinuityPlanAuditImprovement->find('count', array(
			'conditions' => array(
				'BusinessContinuityPlanAuditImprovement.business_continuity_plan_audit_id' => $auditIds
			),
			'recursive' => -1
		));

		if ($ret) {
			return 1;
		}

		return 0;
	}

	private function updateAuditMetricAndCriteria() {
		if (!empty($this->id)) {
			if (isset($this->data['BusinessContinuityPlan']['audit_metric']) && isset($this->data['BusinessContinuityPlan']['audit_success_criteria'])) {
				$data = $this->find('first', array(
					'conditions' => array(
						'id' => $this->id
					),
					'fields' => array('audit_metric', 'audit_success_criteria'),
					'recursive' => -1
				));

				$updateFields = array();
				if ($this->data['BusinessContinuityPlan']['audit_metric'] != $data['BusinessContinuityPlan']['audit_metric']) {
					$updateFields['BusinessContinuityPlanAudit.audit_metric_description'] = '"' . $this->data['BusinessContinuityPlan']['audit_metric'] . '"';
				}
				if ($this->data['BusinessContinuityPlan']['audit_success_criteria'] != $data['BusinessContinuityPlan']['audit_success_criteria']) {
					$updateFields['BusinessContinuityPlanAudit.audit_success_criteria'] = '"' . $this->data['BusinessContinuityPlan']['audit_success_criteria'] . '"';
				}

				if (!empty($updateFields)) {
					return $this->BusinessContinuityPlanAudit->updateAll($updateFields, array(
						'BusinessContinuityPlanAudit.planned_date >' => date('Y-m-d'),
						'BusinessContinuityPlanAudit.business_continuity_plan_id' => $this->id
					));
				}

			}
		}
	}

	public function getSecurityServiceTypes() {
		if (isset($this->data['BusinessContinuityPlan']['security_service_type_id'])) {
			$type = $this->SecurityServiceType->find('first', array(
				'conditions' => array(
					'SecurityServiceType.id' => $this->data['BusinessContinuityPlan']['security_service_type_id']
				),
				'fields' => array('name'),
				'recursive' => -1
			));

			return $type['SecurityServiceType']['name'];
		}

		return false;
	}

	public function getLastAuditFailedDate() {
		if (!empty($this->lastAuditFailed)) {
			return $this->lastAuditFailed;
		}

		return false;
	}

	public function getLastAuditMissingDate() {
		if (!empty($this->lastAuditMissing)) {
			return $this->lastAuditMissing;
		}

		return false;
	}

	/**
	 * Calculates and saves current audit statuses for given plan.
	 * @param  int $id Business continuity plan ID.
	 */
	public function saveAudits($id, $processType = null) {
		/*$audits = $this->BusinessContinuityPlanAudit->getStatuses($id);

		$saveData = $audits;

		$this->id = $id;
		return $this->save($saveData, array('validate' => false, 'callbacks' => 'before'));*/

		$ret = $this->triggerStatus('auditsLastFailed', $id, $processType);
		$ret &= $this->triggerStatus('auditsLastMissing', $id, $processType);
		return $ret;
	}

	public function saveAuditsJoins( $list, $bcm_id ) {
		// $user = $this->currentUser();
		$data = $this->data;
		foreach ( $list as $date ) {
			$tmp = array(
				'business_continuity_plan_id' => $bcm_id,
				'planned_date' =>  date('Y') . '-' . $date['month'] . '-' . $date['day'],
				'audit_metric_description' => $data['BusinessContinuityPlan']['audit_metric'],
				'audit_success_criteria' => $data['BusinessContinuityPlan']['audit_success_criteria'],
				// 'workflow_owner_id' => $user['id']
			);

			$exist = $this->BusinessContinuityPlanAudit->find( 'count', array(
				'conditions' => array(
					'BusinessContinuityPlanAudit.business_continuity_plan_id' => $bcm_id,
					'BusinessContinuityPlanAudit.planned_date' => date('Y') . '-' . $date['month'] . '-' . $date['day']
				),
				'recursive' => -1
			) );

			if ( ! $exist ) {
				$this->BusinessContinuityPlanAudit->create();
				$save = $this->BusinessContinuityPlanAudit->save($tmp, array(
					'validate' => false,
				));

				if (!$save) {
					return false;
				}
			}
		}

		return true;
	}

	private function saveAuditDateJoins( $list, $bcm_id ) {
		foreach ( $list as $date ) {
			$tmp = array(
				'business_continuity_plan_id' => $bcm_id,
				'day' => $date['day'],
				'month' => $date['month']
			);

			$this->BusinessContinuityPlanAuditDate->create();
			if ( ! $this->BusinessContinuityPlanAuditDate->save( $tmp ) ) {
				return false;
			}
		}

		return true;
	}

	private function getBusinessPlansBusinessContinuities($id) {
		$data = $this->BusinessContinuitiesBusinessContinuityPlan->find('list', array(
			'conditions' => array(
				'BusinessContinuitiesBusinessContinuityPlan.business_continuity_plan_id' => $id
			),
			'fields' => array('BusinessContinuitiesBusinessContinuityPlan.business_continuity_id'),
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
					'BusinessContinuityPlan.id' => $id
				),
				'fields' => array(
					'MIN(BusinessContinuityPlan.audits_last_passed) AS LastAuditPassed',
					'MAX(BusinessContinuityPlan.audits_last_missing) AS LastAuditMissing',
					'MAX(BusinessContinuityPlan.audits_improvements) AS AuditImprovements',
					'MIN(BusinessContinuityPlan.security_service_type_id) AS SecurityServiceTypeId',

				),
				'recursive' => -1
			));

			$data = $data[0][0];
		}
		else {
			$data = $this->find('list', array(
				'conditions' => array(
					'OR' => array(
						array(
							'BusinessContinuityPlan.id' => $id,
							'BusinessContinuityPlan.audits_all_done' => 0
						),
						array(
							'BusinessContinuityPlan.id' => $id,
							'BusinessContinuityPlan.audits_last_passed' => 0
						)
					)
				),
				'fields' => array('BusinessContinuityPlan.id', 'BusinessContinuityPlan.title'),
				'recursive' => 0
			));
		}

		return $data;
	}

}
