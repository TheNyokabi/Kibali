<?php
class ComplianceAuditSettingsAuditee extends AppModel {
	public $actsAs = array(
		'Containable'
	);

	public $belongsTo = array(
		'Auditee' => array(
			'className' => 'User',
			'foreignKey' => 'auditee_id',
			// 'fields' => array('id', 'name', 'surname')
		),
		'ComplianceAuditSetting'
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ComplianceManagement'
			)
		)
	);
}
