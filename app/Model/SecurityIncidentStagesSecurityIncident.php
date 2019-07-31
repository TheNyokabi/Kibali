<?php
class SecurityIncidentStagesSecurityIncident extends AppModel {
	public $mapping = array(
		'logRecords' => true,
		// 'indexController' => 'securityIncidents',
		'indexController' => array(
			'action' => 'index',
			'advanced' => 'securityIncidents',
			'basic' => 'securityIncidents'
		),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable'
	);

	public $belongsTo = array(
		'SecurityIncidentStage',
		'SecurityIncident'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'SecurityIncidentStagesSecurityIncident'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'SecurityIncidentStagesSecurityIncident'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'SecurityIncidentStagesSecurityIncident'
			)
		)
	);

	public function getItem($conditions){
		$item = $this->find('first', array(
			'conditions' => $conditions
		));

		return $item;
	}
}
