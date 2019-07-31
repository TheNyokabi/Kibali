<?php
class ComplianceAuditFeedback extends AppModel {
	public $actsAs = array(
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name'
			)
		),
		'Containable'
	);

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true
		)
	);

	public $belongsTo = array(
		'ComplianceAuditFeedbackProfile' => array(
			'counterCache' => true
		)
	);

	public $hasAndBelongsToMany = array(
		'ComplianceAudit'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ComplianceAuditFeedback'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ComplianceAuditFeedback'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ComplianceAuditFeedback'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}

	public function afterDelete() {
		$data = $this->ComplianceAuditFeedbackProfile->find('list', array(
			'conditions' => array(
				'ComplianceAuditFeedbackProfile.compliance_audit_feedback_count' => 0
			),
			'fields' => array('id'),
			'recursive' => -1
		));

		$d = $this->ComplianceAuditFeedbackProfile->deleteAll(array(
			'ComplianceAuditFeedbackProfile.id' => $data
		));
	}

	/**
	 * Returns list of possible answers formatted with a profile.
	 */
	public function getList() {
		$this->virtualFields['full_title'] = 'CONCAT("[", ComplianceAuditFeedbackProfile.name, "] ", '.$this->alias.'.name)';
		$data = $this->find('all', array(
			'recursive' => 0
		));

		$options = array();
		foreach ($data as $item) {
			$options[$item['ComplianceAuditFeedback']['id']] = $item['ComplianceAuditFeedback']['full_title'];
		}

		return $options;
	}
}
