<?php
App::uses('SectionBase', 'Model');

class Improvement extends SectionBase {
	protected $auditModel = false;
	protected $auditParentModel = false;

	public $actsAs = array(
		'Containable',
		'EventManager.EventManager',
	);

	public $validate = array(
		'Project' => array(
			'rule' => array( 'multiple', array( 'min' => 1 ) )
		)
	);

	public $belongsTo = array(
		'User'
	);

	public $hasAndBelongsToMany = array(
		'Project',
		'SecurityIncident'
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Improvements');

		parent::__construct($id, $table, $ds);
	}

	public function beforeDelete($cascade = true) {
		if (!empty($this->id)) {
			$audit = $this->getAudit($this->id);
			$this->planId = $audit[$this->auditModel][$this->{$this->auditModel}->belongsTo[$this->auditParentModel]['foreignKey']];
		}

		return true;
	}

	public function afterDelete() {
		if (isset($this->planId)) {
			$ret = $this->getAuditParent()->triggerStatus('ongoingCorrectiveActions', $this->planId);

			return $ret;
		}
		
		return true;
	}

	public function beforeSave($options = array()) {
		$this->transformDataToHabtm(array('Project', 'SecurityIncident'));

		if (isset($this->id)) {
			$audit = $this->getAudit($this->id);
			$settings = array(
				'disableToggles' => array('mappedProjects', 'unmappedProjects'),
				'customToggles' => array('ProjectMappedToAudit')
			);

			$ret = $this->getAuditParent()->triggerStatus('ongoingCorrectiveActions', $audit[$this->auditModel][$this->{$this->auditModel}->belongsTo[$this->auditParentModel]['foreignKey']], 'before', $settings);
			return $ret;
		}


		return true;
	}

	public function afterSave($created, $options = array()) {
		$audit = $this->getAudit($this->id);
		$projects = $this->Project->find('list', array(
			'conditions' => array(
				'id' => $this->data['Project']['Project']
			),
			'fields' => array('id', 'title'),
			'recursive' => -1
		));

		$this->triggerData = array(
			'mappedProjectsAudit' => implode(', ', $projects),
			'mappedProjectsAuditDate' => $audit[$this->auditModel]['planned_date']
		);

		$this->afterItemSaveTrigger($this->id);

		return true;
	}

	public function afterItemSaveTrigger($id) {
		if (isset($id)) {
			$audit = $this->getAudit($id);
			
			$settings = array(
				'disableToggles' => array('mappedProjects', 'unmappedProjects'),
				'customToggles' => array('ProjectMappedToAudit'),
				'customValues' => $this->triggerData
			);

			$ret = $this->getAuditParent()->triggerStatus('ongoingCorrectiveActions', $audit[$this->auditModel][$this->{$this->auditModel}->belongsTo[$this->auditParentModel]['foreignKey']], 'after', $settings);

			return $ret;
		}

		return true;
	}

	private function getAudit($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'id' => $id
			),
			'fields' => array($this->belongsTo[$this->auditModel]['foreignKey']),
			'recursive' => -1
		));

		$audit = $this->{$this->auditModel}->find('first', array(
			'conditions' => array(
				'id' => $data[$this->alias][$this->belongsTo[$this->auditModel]['foreignKey']]
			),
			'recursive' => -1
		));

		return $audit;
	}

	protected function getAuditParent() {
		return $this->{$this->auditModel}->{$this->auditParentModel};
	}

	public function getProjects() {
		$data = $this->Project->find('list', [
			'conditions' => [
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
			],
			'order' => ['Project.title' => 'ASC']
		]);

		return $data;
	}

}
