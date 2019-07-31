<?php
App::uses('AppAudit', 'Model');
class GoalAudit extends AppAudit {
	public $displayField = 'planned_date';

	protected $auditParentModel = 'Goal';

	public $mapping = array(
		'indexController' => array(
			'basic' => 'goalAudits',
			'advanced' => 'goalAudits',
			'params' => array('goal_id')
		),
		'titleColumn' => false,
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'audit_metric_description', 'audit_success_criteria', 'result_description'
			)
		),
		'ObjectStatus.ObjectStatus',
	);

	public $validate = array(

	);

	public $belongsTo = array(
		'Goal',
		'User'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'GoalAudit'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'GoalAudit'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'GoalAudit'
			)
		)
	);

	public $hasOne = array(
		'GoalAuditImprovement'
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Goal Audits');

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = [
			'goal_id' => [
				'label' => __('Goal'),
				'editable' => false,
				'hidden' => true,
				'description' => __(''),
			],
			'audit_metric_description' => [
				'label' => __('Audit Metric'),
				'editable' => true,
				'description' => __('At the time of creating the Security Service, a metric was defined in order to be able to measure the level of efficacy of the control. This should be utilized as the base for this audit review.')
			],
			'audit_success_criteria' => [
				'label' => __('Metric Success Criteria'),
				'editable' => true,
				'description' => __('At the time of creating the Security Service, a success criteria was defined in order to evaluate if the metric results are within acceptable threasholds (audit pass) or not (audit not pass).')
			],
			'result' => [
				'label' => __('Audit Result'),
				'options' => [$this, 'results'],
				'editable' => true,
				'description' => __('After evluating the audit evidence, success criteria, etc you are able to conclude with the audit result. Pass or Fail are the available options.'),
			],
			'result_description' => [
				'label' => __('Audit Conclusion'),
				'editable' => true,
				'description' => __('Describe what evidence was avilable, the accuracy and integrity of the metrics taken and if the metrics are within the expected threasholds or not.')
			],
			'user_id' => [
				'label' => __('Audit Owner'),
				'editable' => true,
				'description' => __('Register the person who has worked on this audit (the auditor name)')
			],
			'planned_date' => [
				'label' => __('Planned Date'),
				'editable' => false,
				'hidden' => true,
				'description' => __('')
			],
			'start_date' => [
				'label' => __('Audit Start Date:'),
				'editable' => true,
				'description' => __('Register the date at which this audit started.')
			],
			'end_date' => [
				'label' => __('Audit End Date:'),
				'editable' => true,
				'description' => __('Register the date at which this audit ended.')
			],
		];
		
		parent::__construct($id, $table, $ds);
	}

	public function getObjectStatusConfig() {
        return [
            'metrics_last_missing' => [
                'trigger' => [
                    $this->Goal
                ]
            ],
            'ongoing_corrective_actions' => [
                'trigger' => [
                    $this->Goal
                ]
            ],
        ];
    }

    public function afterSave($created, $options = array()) {
    	parent::afterSave($created, $options);

		if ($created) {
			$this->triggerObjectStatus();
		}
	}

	public function getRecordTitle($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'GoalAudit.id' => $id
			),
			'fields' => array(
				'GoalAudit.planned_date',
				'Goal.name'
			),
			'recursive' => 0
		));
		
		return sprintf('%s (%s)', $data['GoalAudit']['planned_date'], $data['Goal']['name']);
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

		if ($record['GoalAudit']['result'] != $this->data['GoalAudit']['result']) {
			$statuses = getAuditStatuses();
			$this->Goal->addNoteToLog(__('Audit status changed to %s', $statuses[$this->data['GoalAudit']['result']]));
			$this->Goal->setSystemRecord($this->data['GoalAudit']['security_service_id'], 2);
		}
	}

	/**
	 * Get audits completion statuses.
	 * @param  int $id   Security Service ID.
	 */
	public function getStatuses($id) {
		$audits = $this->find('count', array(
			'conditions' => array(
				'GoalAudit.security_service_id' => $id,
				'GoalAudit.result' => null,
				'GoalAudit.planned_date <' => date('Y-m-d', strtotime('now'))
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
				'GoalAudit.security_service_id' => $id,
				'GoalAudit.planned_date <=' => $today
			),
			'fields' => array('GoalAudit.id', 'GoalAudit.result', 'GoalAudit.planned_date'),
			'order' => array('GoalAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$last_passed = false;
		if (empty($audit) ||
			(!empty($audit) && in_array($audit['GoalAudit']['result'], array(1, null)))) {
			$last_passed = true;
		}
		elseif (!empty($audit)) {
			$this->Goal->lastAuditFailed = $audit['GoalAudit']['planned_date'];
		}

		$improvements = false;
		$hasProjects = $this->Goal->getAssignedProjects($id);
		if ($hasProjects) {
			$improvements = true;
		}
		else {
			$audit = $this->find('first', array(
				'conditions' => array(
					'GoalAudit.security_service_id' => $id,
					'GoalAudit.planned_date <=' => $today,
					'GoalAudit.result' => array(1, 0)
				),
				'fields' => array('GoalAudit.id', 'GoalAudit.result', 'GoalAuditImprovement.id'),
				'order' => array('GoalAudit.planned_date' => 'DESC'),
				'contain' => array(
					'GoalAuditImprovement'
				)
			));

			if (isset($audit['GoalAuditImprovement']['id']) && $audit['GoalAuditImprovement']['id'] != null) {
				$improvements = true;
			}
		}

		$audit = $this->find('first', array(
			'conditions' => array(
				'GoalAudit.security_service_id' => $id,
				'GoalAudit.planned_date <' => $today
			),
			'fields' => array('GoalAudit.id', 'GoalAudit.result', 'GoalAudit.planned_date'),
			'order' => array('GoalAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$lastMissing = false;
		if (!empty($audit) && $audit['GoalAudit']['result'] == null) {
			$this->Goal->lastAuditMissing = $audit['GoalAudit']['planned_date'];
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
		$data = $this->Goal->find('first', array(
			'conditions' => array(
				'Goal.id' => $id
			),
			'fields' => array('id', 'security_service_type_id'),
			'recursive' => -1
		));

		if ($data['Goal']['security_service_type_id'] == SECURITY_SERVICE_RETIRED) {
			return 2;
		}

		if ($data['Goal']['security_service_type_id'] != SECURITY_SERVICE_PRODUCTION) {
			return 1;
		}

		return 0;
	}

	public function logMissingAudits() {
		$yesterday = CakeTime::format('Y-m-d', CakeTime::fromString('-1 day'));

		$audits = $this->find('all', array(
			'conditions' => array(
				'GoalAudit.planned_date' => $yesterday
			),
			'fields' => array(
				'GoalAudit.id',
				'GoalAudit.result',
				'GoalAudit.planned_date',
				'GoalAudit.security_service_id'
			),
			'order' => array('GoalAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		foreach ($audits as $item) {
			$msg = __('Last audit missing (%s)', $item['GoalAudit']['planned_date']);

			if ($item['GoalAudit']['result'] == null) {
				$GoalId = $item['GoalAudit']['security_service_id'];

				$this->Goal->id = $GoalId;
				$this->Goal->addNoteToLog($msg);
				$this->Goal->setSystemRecord($GoalId, 2);
			}
		}
	}
}
