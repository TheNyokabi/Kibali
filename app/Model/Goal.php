<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');

class Goal extends SectionBase {
	public $displayField = 'name';
	
	public $mapping = array(
		'titleColumn' => 'name',
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
				'name', 'owner_id', 'description', 'status', 'audit_metric', 'audit_criteria'
			)
		),
		'CustomFields.CustomFields',
		'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Name is a required field'
		),
		'owner_id' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'status' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Status is required'
			),
			'inList' => array(
				'rule' => array('inList', array(
					GOAL_DRAFT,
					GOAL_DISCARDED,
					GOAL_CURRENT
				)),
				'message' => 'Please select one of the statuses'
			)
		)
	);

	public $belongsTo = array(
		'Owner' => array(
			'className' => 'User',
			'foreignKey' => 'owner_id',
			'fields' => array('id', 'login', 'name', 'surname')
		)
	);

	public $hasMany = array(
		'GoalAudit',
		'GoalAuditDate',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'Goal'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'Goal'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'Goal'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'SecurityService',
		'Risk',
		'ThirdPartyRisk',
		'BusinessContinuity',
		'Project',
		'SecurityPolicy',
		'ProgramIssue'
	);

	/*
     * static enum: Model::function()
     * @access static
     */
    public static function statuses($value = null) {
        $options = array(
            self::STATUS_DRAFT => __('Draft'),
			self::STATUS_DISCARDED => __('Discarded'),
			self::STATUS_CURRENT => __('Current')
        );
        return parent::enum($value, $options);
    }

    const STATUS_DRAFT = GOAL_DRAFT;
    const STATUS_DISCARDED = GOAL_DISCARDED;
    const STATUS_CURRENT = GOAL_CURRENT;

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Goals');
		$this->_group = 'program';

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			]
		];

		$this->fieldData = [
			'name' => [
				'label' => __('Name'),
				'editable' => true,
				'description' => __('The name of the goal'),
			],
			'owner_id' => [
				'label' => __('Owner'),
				'editable' => true,
				'description' => __('The individual accountable for planning, monitoring and ultimately acheving this goal'),
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('A brief description of the goal'),
			],
			'status' => [
				'label' => __('Status'),
				'editable' => true,
				'options' => [$this, 'statuses'],
				'description' => __('Select the current status of this goal'),
			],
			'audit_metric' => [
				'label' => __('Metric'),
				'editable' => true,
				'description' => __('What and how evidence will be used and collected to determine if the goal has been achieved'),
			],
			'audit_criteria' => [
				'label' => __('Success Criteria'),
				'editable' => true,
				'description' => __('How that metric will need to look in order to determine if it was achieved or not?'),
			],
			'ongoing_corrective_actions' => [
				'label' => __('Ongoing Corrective Actions'),
				'editable' => true,
				'description' => __('How that metric will need to look in order to determine if it was achieved or not?'),
			],
			'GoalAudit' => [
				'label' => __('Goal Audit'),
				'editable' => false,
			],
			'GoalAuditDate' => [
				'label' => __('Audit Calendar'),
				'editable' => false,
			],
			'SecurityService' => [
				'label' => __('Security Services'),
				'editable' => true,
				'description' => __('Select controls that are being (or have been) developed and put into production to support the completion of this goal'),
			],
			'Risk' => [
				'label' => __('Asset Risks'),
				'editable' => true,
				'description' => __('Select asset based risks that have emerged or have mitigated as part of this goal'),
			],
			'ThirdPartyRisk' => [
				'label' => __('Third Party Risks'),
				'editable' => true,
				'description' => __('Select third party based risks that have emerged or have mitigated as part of this goal'),
			],
			'BusinessContinuity' => [
				'label' => __('Business Continuities'),
				'editable' => true,
				'description' => __('Select business risks that have emerged or have mitigated as part of this goal'),
			],
			'Project' => [
				'label' => __('Projects'),
				'editable' => true,
				'description' => __('Select projects that have been createed to meet this goal'),
			],
			'SecurityPolicy' => [
				'label' => __('Security Policies'),
				'editable' => true,
				'options' => [$this, 'getSecurityPolicies'],
				'description' => __('Select Security Policies that have been developed and implemented as part of this goal'),
			],
			'ProgramIssue' => [
				'label' => __('Program Issues'),
				'editable' => true,
				'description' => __('Select any issues that will be mitigated with the achievement of this goal'),
			]
		];

		parent::__construct($id, $table, $ds);

		$migrateRecords = array(
			'Project'
			/*'Risk',
			'ThirdPartyRisk',
			'BusinessContinuity',*/
			// 'DataAsset',
			// 'ComplianceManagement'
		);
		
		$this->mapping['statusManager'] = array(
			'ongoingCorrectiveActions' => $this->getStatusConfig('ongoingCorrectiveActions', 'name', $migrateRecords)
		);

		$metricsLastMissing = $this->getStatusTemplate('auditsLastMissing', array(
			// 'migrateRecords' => $migrateRecords,
			'auditLabel' => __('Performance Review')
		));

		$metricsLastMissing['fn'] = array('metricsLastMissing');
		$metricsLastMissing['column'] = 'metrics_last_missing';

		$this->mapping['statusManager']['auditsLastMissing'] = $metricsLastMissing;
	}

	public function getObjectStatusConfig() {
        return [
            'ongoing_corrective_actions' => [
            	'title' => __('Ongoning Corrective Actions'),
                'callback' => [$this, '_statusOngoingCorrectiveActions'],
                'type' => 'improvement',
            ],
            'metrics_last_missing' => [
            	'title' => __('Last Performance Review missing'),
                'callback' => [$this, '_metricsLastMissing'],
            ]
        ];
    }

	public function beforeSave($options = array()) {
		$this->transformDataToHabtm(['SecurityService', 'Risk',	'ThirdPartyRisk', 'BusinessContinuity', 'Project', 'SecurityPolicy', 'ProgramIssue'
		]);

		return true;
	}

	public function afterSave($created, $options = array()) {
		if (isset($this->data['GoalAuditDate'])) {
			$this->GoalAuditDate->deleteAll(array(
				'GoalAuditDate.goal_id' => $this->id
			));
			if (!empty($this->data['GoalAuditDate'])) {
				$this->saveAuditsJoins($this->data['GoalAuditDate'], $this->id);
			}
		}

		return true;
	}

	public function _statusOngoingCorrectiveActions() {
		// check projects by status
		$ret = $this->Project->find('count', [
			'conditions' => [
				'GoalsProject.goal_id' => $this->id,
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
			],
			'joins' => [
                [
                    'table' => 'goals_projects',
                    'alias' => 'GoalsProject',
                    'type' => 'INNER',
                    'conditions' => [
                        'GoalsProject.project_id = Project.id'
                    ]
                ],
            ],
			'recursive' => -1
		]);

		// or also check audit improvements
		$ret = $ret || $this->GoalAudit->GoalAuditImprovement->find('count', [
			'conditions' => [
				'GoalAudit.goal_id' => $this->id,
			],
			'joins' => [
                [
                    'table' => 'goal_audits',
                    'alias' => 'GoalAudit',
                    'type' => 'INNER',
                    'conditions' => [
                        'GoalAudit.id = GoalAuditImprovement.goal_audit_id'
                    ]
                ],
            ],
			'recursive' => -1
		]);

    	return (boolean) $ret;
    }

	/**
	 * @deprecated status, in favor of Goal::_statusOngoingCorrectiveActions()
	 */
	public function statusOngoingCorrectiveActions($id) {
		$this->GoalsProject->bindModel(array(
			'belongsTo' => array('Project')
		));
		
		$ret = $this->GoalsProject->find('count', array(
			'conditions' => array(
				'GoalsProject.goal_id' => $id,
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
			),
			'recursive' => 0
		));

		$auditIds = $this->GoalAudit->find('list', array(
			'conditions' => array(
				'GoalAudit.goal_id' => $id
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$ret = $ret || $this->GoalAudit->GoalAuditImprovement->find('count', array(
			'conditions' => array(
				'GoalAuditImprovement.goal_audit_id' => $auditIds
			),
			'recursive' => -1
		));

		if ($ret) {
			return 1;
		}

		return 0;
	}

	public function _metricsLastMissing() {
		$data = $this->GoalAudit->find('first', [
			'conditions' => [
				'GoalAudit.goal_id' => $this->id,
				'GoalAudit.planned_date < DATE(NOW())'
			],
			'fields' => ['GoalAudit.id', 'GoalAudit.result', 'GoalAudit.planned_date'],
			'order' => ['GoalAudit.planned_date' => 'DESC'],
			'recursive' => -1
		]);

		return (!empty($data) && $data['GoalAudit']['result'] === null);
	}

	/**
	 * @deprecated status, in favor of Goal::_metricsLastMissing()
	 */
	public function metricsLastMissing($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$audit = $this->GoalAudit->find('first', array(
			'conditions' => array(
				'GoalAudit.goal_id' => $id,
				'GoalAudit.planned_date <' => $today
			),
			'fields' => array('GoalAudit.id', 'GoalAudit.result', 'GoalAudit.planned_date'),
			'order' => array('GoalAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$lastMissing = false;
		if (!empty($audit) && $audit['GoalAudit']['result'] == null) {
			$lastMissing = true;
		}

		if (!$lastMissing) {
			return 0;
		}

		return 1;
	}

	/*public function lastMissingMetric($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$audit = $this->GoalAudit->find('first', array(
			'conditions' => array(
				'GoalAudit.goal_id' => $id,
				'GoalAudit.planned_date <=' => $today,
				'GoalAudit.result' => null
			),
			'order' => array('GoalAudit.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			$this->lastMissingAuditId = $audit['GoalAudit']['id'];
			return $audit['GoalAudit']['planned_date'];
		}

		return false;
	}

	public function lastMissingMetricResult($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$audit = $this->GoalAudit->find('first', array(
			'conditions' => array(
				'GoalAudit.goal_id' => $id,
				'GoalAudit.planned_date <=' => $today,
				'GoalAudit.result' => array(1,0)
			),
			'order' => array('GoalAudit.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			if ($audit['GoalAudit']['result']) {
				return __('Pass');
			}

			return __('Fail');

		}

		return false;
	}*/

	public function saveAudits($id, $processType = null) {
		$ret = $this->triggerStatus('auditsLastMissing', $id, $processType);
		return $ret;
	}

	public function saveAuditsJoins($list, $goal_id) {
		$user = $this->currentUser();
		foreach ( $list as $date ) {
			$tmp = array(
				'goal_id' => $goal_id,
				'planned_date' =>  date('Y') . '-' . $date['month'] . '-' . $date['day'],
				'audit_metric_description' => $this->data['Goal']['audit_metric'],
				'audit_success_criteria' => $this->data['Goal']['audit_criteria'],
			);

			$exist = $this->GoalAudit->find( 'count', array(
				'conditions' => array(
					'GoalAudit.goal_id' => $goal_id,
					'GoalAudit.planned_date' => date('Y') . '-' . $date['month'] . '-' . $date['day']
				),
				'recursive' => -1
			) );

			if ( ! $exist ) {
				$this->GoalAudit->create();
				$save = $this->GoalAudit->save($tmp, array(
					'validate' => false,
					'forceRecheck' => true
				));

				if (!$save) {
					return false;
				}
			}
		}

		return true;
	}

	public function getSecurityPolicies() {
        return $this->SecurityPolicy->getListWithType();
    }

}
