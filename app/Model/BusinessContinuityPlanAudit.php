<?php
App::uses('AppAudit', 'Model');
App::uses('InheritanceInterface', 'Model/Interface');

class BusinessContinuityPlanAudit extends AppAudit implements InheritanceInterface {
	protected $auditParentModel = 'BusinessContinuityPlan';
	public $displayField = 'planned_date';
	
	public $mapping = array(
		'indexController' => array(
			'basic' => 'businessContinuityPlanAudits',
			'advanced' => 'businessContinuityPlanAudits',
			'params' => array('business_continuity_plan_id')
		),
		'titleColumn' => false,
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
				'audit_metric_description', 'audit_success_criteria', 'result_description'
			)
		),
		'AuditLog.Auditable',
		'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
	);

	public $validate = array(
	);

	public $belongsTo = array(
		'BusinessContinuityPlan',
		'User'
	);

	public $hasMany = array(
		// 'Asset',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'BusinessContinuityPlanAudit'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'BusinessContinuityPlanAudit'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'BusinessContinuityPlanAudit'
			)
		)
	);

	public $hasOne = array(
		'BusinessContinuityPlanAuditImprovement'
	);

	/*
     * static enum: Model::function()
     * @access static
     */
    public static function results($value = null) {
        $options = array(
            self::RESULT_FAILED => __('Fail'),
            self::RESULT_SUCCESS => __('Pass'),
        );
        return parent::enum($value, $options);
    }

    const RESULT_FAILED = 0;
    const RESULT_SUCCESS = 1;

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Business Continuity Plan Audits');
		$this->_group = parent::SECTION_GROUP_CONTROL_CATALOGUE;

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
		];

		$this->fieldData = [
			'business_continuity_plan_id' => [
				'label' => __('Business Continuity Plan'),
				'editable' => false,
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
			'result_description' => [
				'label' => __('Audit Conclusion'),
				'editable' => true,
				'description' => __('Describe what evidence was avilable, the accuracy and integrity of the metrics taken and if the metrics are within the expected threasholds or not.')
			],
			'result' => [
				'label' => __('Audit Result'),
				'editable' => true,
				'options' => [$this, 'results'],
				'description' => __('After evluating the audit evidence, success criteria, etc you are able to conclude with the audit result. Pass or Fail are the available options.')
			],
			'user_id' => [
				'label' => __('Audit Owner'),
				'editable' => true,
				'description' => __('Register the person who has worked on this audit (the auditor name)')
			],
			'start_date' => [
				'label' => __('Audit Start Date'),
				'editable' => true,
				'description' => __('Register the date at which this audit started.')
			],
			'end_date' => [
				'label' => __('Audit End Date'),
				'editable' => true,
				'description' => __('Register the date at which this audit ended.')
			],
			'planned_date' => [
				'label' => __('Planned Date'),
				'editable' => false,
			],
			'BusinessContinuityPlanAuditImprovement' => [
				'label' => __('Business Continuity Plan Audit Improvements'),
				'editable' => false,
			]
		];

		parent::__construct($id, $table, $ds);
	}

	public function getObjectStatusConfig() {
        return [
            'audits_last_passed' => [
            	'trigger' => [
            		$this->BusinessContinuityPlan
            	]
            ],
            'audits_last_missing' => [
            	'trigger' => [
            		$this->BusinessContinuityPlan
            	]
            ],
            'ongoing_corrective_actions' => [
            	'trigger' => [
            		$this->BusinessContinuityPlan
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

	public function parentModel() {
		return 'BusinessContinuityPlan';
	}

	public function parentNode() {
        return $this->visualisationParentNode('business_continuity_plan_id');
    }

	public function getRecordTitle($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'BusinessContinuityPlanAudit.id' => $id
			),
			'fields' => array(
				'BusinessContinuityPlanAudit.planned_date',
				'BusinessContinuityPlan.title'
			),
			'recursive' => 0
		));
		
		return sprintf('%s (%s)', $data['BusinessContinuityPlanAudit']['planned_date'], $data['BusinessContinuityPlan']['title']);
	}

	private function logStatusToPlan() {
		$record = $this->find('first', array(
			'conditions' => array(
				'id' => $this->id
			),
			'fields' => array('result'),
			'recursive' => -1
		));

		if ($record['BusinessContinuityPlanAudit']['result'] != $this->data['BusinessContinuityPlanAudit']['result']) {
			$statuses = getAuditStatuses();
			$this->BusinessContinuityPlan->addNoteToLog(__('Audit status changed to %s', $statuses[$this->data['BusinessContinuityPlanAudit']['result']]));
			$this->BusinessContinuityPlan->setSystemRecord($this->data['BusinessContinuityPlanAudit']['business_continuity_plan_id'], 2);
		}
	}

	/**
	 * Get audits completion statuses.
	 * @param  int $id   Security Service ID.
	 */
	public function getStatuses($id) {
		$audits = $this->find('count', array(
			'conditions' => array(
				'BusinessContinuityPlanAudit.business_continuity_plan_id' => $id,
				'BusinessContinuityPlanAudit.result' => null,
				'BusinessContinuityPlanAudit.planned_date <' => date('Y-m-d', strtotime('now'))
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
				'BusinessContinuityPlanAudit.business_continuity_plan_id' => $id,
				'BusinessContinuityPlanAudit.planned_date <=' => $today
			),
			'fields' => array(
				'BusinessContinuityPlanAudit.id',
				'BusinessContinuityPlanAudit.result',
				'BusinessContinuityPlanAudit.planned_date',
				'BusinessContinuityPlanAuditImprovement.id'
			),
			'order' => array('BusinessContinuityPlanAudit.planned_date' => 'DESC'),
			'contain' => array(
				'BusinessContinuityPlanAuditImprovement'
			)
		));

		$last_passed = false;
		if (empty($audit) ||
			(!empty($audit) && in_array($audit['BusinessContinuityPlanAudit']['result'], array(1, null)))) {
			$last_passed = true;
		}
		elseif (!empty($audit)) {
			$this->BusinessContinuityPlan->lastAuditFailed = $audit['BusinessContinuityPlanAudit']['planned_date'];
		}

		$improvements = false;
		$audit = $this->find('first', array(
			'conditions' => array(
				'BusinessContinuityPlanAudit.business_continuity_plan_id' => $id,
				'BusinessContinuityPlanAudit.planned_date <=' => $today,
				'BusinessContinuityPlanAudit.result' => array(1, 0)
			),
			'fields' => array(
				'BusinessContinuityPlanAudit.id',
				'BusinessContinuityPlanAudit.result',
				'BusinessContinuityPlanAuditImprovement.id'
			),
			'order' => array('BusinessContinuityPlanAudit.planned_date' => 'DESC'),
			'contain' => array(
				'BusinessContinuityPlanAuditImprovement'
			)
		));

		if (isset($audit['BusinessContinuityPlanAuditImprovement']['id']) && $audit['BusinessContinuityPlanAuditImprovement']['id'] != null) {
			$improvements = true;
		}

		$audit = $this->find('first', array(
			'conditions' => array(
				'BusinessContinuityPlanAudit.business_continuity_plan_id' => $id,
				'BusinessContinuityPlanAudit.planned_date <' => $today
			),
			'fields' => array(
				'BusinessContinuityPlanAudit.id',
				'BusinessContinuityPlanAudit.result',
				'BusinessContinuityPlanAudit.planned_date'
			),
			'order' => array('BusinessContinuityPlanAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		$lastMissing = false;
		if (!empty($audit) && $audit['BusinessContinuityPlanAudit']['result'] == null) {
			$this->BusinessContinuityPlan->lastAuditMissing = $audit['BusinessContinuityPlanAudit']['planned_date'];
			$lastMissing = true;
		}
		
		$arr = array(
			'audits_all_done' => (string) (int) $all_done,
			'audits_last_missing' => (string) (int) $lastMissing,
			'audits_last_passed' => (string) (int) $last_passed,
			'audits_improvements' =>(int) $improvements
		);

		return $arr;
	}

	public function logMissingAudits() {
		$yesterday = CakeTime::format('Y-m-d', CakeTime::fromString('-1 day'));

		$audits = $this->find('all', array(
			'conditions' => array(
				'BusinessContinuityPlanAudit.planned_date' => $yesterday
			),
			'fields' => array(
				'BusinessContinuityPlanAudit.id',
				'BusinessContinuityPlanAudit.result',
				'BusinessContinuityPlanAudit.planned_date',
				'BusinessContinuityPlanAudit.business_continuity_plan_id'
			),
			'order' => array('BusinessContinuityPlanAudit.planned_date' => 'DESC'),
			'recursive' => -1
		));

		foreach ($audits as $item) {
			$msg = __('Last audit missing (%s)', $item['BusinessContinuityPlanAudit']['planned_date']);

			if ($item['BusinessContinuityPlanAudit']['result'] == null) {
				$bcpId = $item['BusinessContinuityPlanAudit']['business_continuity_plan_id'];

				$this->BusinessContinuityPlan->id = $bcpId;
				$this->BusinessContinuityPlan->addNoteToLog($msg);
				$this->BusinessContinuityPlan->setSystemRecord($bcpId, 2);
			}
		}
	}
}
