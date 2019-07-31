<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');

class TeamRole extends SectionBase {
	public $displayField = 'role';
	
	public $mapping = array(
		'titleColumn' => 'role',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false,
	);

	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'user_id', 'role', 'status', 'responsibilities', 'competences'
			)
		),
		'CustomFields.CustomFields',
	);

	public $validate = array(
		'user_id' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'role' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Role is a required field'
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
					TEAM_ROLE_ACTIVE,
					TEAM_ROLE_DISCARDED
				)),
				'message' => 'Please select one of the statuses'
			)
		)
	);

	public $belongsTo = array(
		'User'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'TeamRole'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'TeamRole'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'TeamRole'
			)
		)
	);

	/*
     * static enum: Model::function()
     * @access static
     */
    public static function statuses($value = null) {
        $options = array(
            self::STATUS_ACTIVE => __('Active'),
			self::STATUS_DISCARDED => __('Inactive'),
        );
        return parent::enum($value, $options);
    }

    const STATUS_ACTIVE = TEAM_ROLE_ACTIVE;
    const STATUS_DISCARDED = TEAM_ROLE_DISCARDED;

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Team Roles');

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			]
		];

		$this->fieldData = [
			'user_id' => [
				'label' => __('Name'),
				'editable' => true,
				'description' => __('Select a user that is a team member'),
			],
			'role' => [
				'label' => __('Role'),
				'editable' => true,
				'description' => __('Describe the role this team member has within the program'),
			],
			'responsibilities' => [
				'label' => __('Responsibilities'),
				'editable' => true,
				'description' => __('Describe the responsibilities of this team member or role'),
			],
			'competences' => [
				'label' => __('Competences'),
				'editable' => true,
				'description' => __('Describe the competences (skills, etc) that this team member has or plans to acquire'),
			],
			'status' => [
				'label' => __('Status'),
				'editable' => true,
				'options' => [$this, 'statuses'],
				'description' => __('Select the status for this team member as Active or Inactive (no longer part of the program)'),
			],
		];

		parent::__construct($id, $table, $ds);
	}

	/**
	 * Get a label for a changed Scope status.
	 */
	public function getTeamRoleStatuses() {
		if (isset($this->data['TeamRole']['status'])) {
			return getTeamRoleStatuses($this->data['TeamRole']['status']);
		}

		return false;
	}
}
