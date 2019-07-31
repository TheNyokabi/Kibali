<?php
App::uses('SectionBase', 'Model');

class SecurityIncidentStage extends SectionBase {
	public $displayField = 'name';

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description'
			)
		),
		'ObjectStatus.ObjectStatus'
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank'
		),
		'description' => array(
			'rule' => 'notBlank'
		)
	);

	public $hasAndBelongsToMany = [
		'SecurityIncident'
	];

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Security Incident Stages');

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
		];

		$this->fieldData = [
			'name' => [
				'label' => __('Name'),
				'editable' => true,
				'description' => __('Give a name to this stage. For example "Identification" or "Containment"'),
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Give a description to this stage'),
			],
		];

		parent::__construct($id, $table, $ds);
	}

	public function getObjectStatusConfig() {
        return [
            'lifecycle_incomplete' => [
                'trigger' => [
                    $this->SecurityIncident,
                ],
            ]
        ];
    }

	public function getStage($conditions = array()){
		$stage = $this->find('first', array(
			'conditions' => $conditions
		));

		return $stage;
	}

	public function getStagesList($conditions = array()){
		$stages = $this->find('list', array(
			'conditions' => $conditions
		));

		return $stages;
	}
}