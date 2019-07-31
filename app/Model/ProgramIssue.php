<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');

class ProgramIssue extends SectionBase {
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
				'name', 'issue_source', 'description', 'status'
			),
		),
		'CustomFields.CustomFields',
		'Visualisation.Visualisation',
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'issue_source' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Issue source must be selected'
			),
			'inList' => array(
				'rule' => array('inList', array(
					PROGRAM_ISSUE_INTERNAL,
					PROGRAM_ISSUE_EXTERNAL
				)),
				'message' => 'Please select one of the sources'
			)
		),
		'status' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Status must be selected'
			),
			'inList' => array(
				'rule' => array('inList', array(
					PROGRAM_ISSUE_DRAFT,
					PROGRAM_ISSUE_DISCARDED,
					PROGRAM_ISSUE_CURRENT
				)),
				'message' => 'Please select one of the statuses'
			)
		)
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ProgramIssue'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ProgramIssue'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ProgramIssue'
			)
		),
		'ProgramIssueType'
	);

	/*
     * static enum: Model::function()
     * @access static
     */
    public static function sources($value = null) {
        $options = array(
            self::SOURCE_INTERNAL => __('Internal'),
			self::SOURCE_EXTERNAL => __('External'),
        );
        return parent::enum($value, $options);
    }

    const SOURCE_INTERNAL = PROGRAM_ISSUE_INTERNAL;
    const SOURCE_EXTERNAL = PROGRAM_ISSUE_EXTERNAL;

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

    const STATUS_DRAFT = PROGRAM_ISSUE_DRAFT;
    const STATUS_DISCARDED = PROGRAM_ISSUE_DISCARDED;
    const STATUS_CURRENT = PROGRAM_ISSUE_CURRENT;

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Program Scopes');
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
				'description' => __('A brief title for this issue.'),
			],
			'issue_source' => [
				'label' => __('Issue Source'),
				'editable' => true,
				'options' => [$this, 'sources'],
				'description' => __('Issues fall in  two categories, those that happen in an internal or external context. Select one of the options and you will provided with categories for each.'),
			],
			'ProgramIssueType' => [
				'label' => __('Types'),
				'editable' => false,
				'description' => __(''),
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Provide a brief description of the issue.'),
			],
			'status' => [
				'label' => __('Status'),
				'editable' => true,
				'options' => [$this, 'statuses'],
				'description' => __('The status should be one of the following: "Draft" (the issue is being identified and documented), "Current" (the issue is active) or "Discarded" (the issue is no longer applicable).'),
			],
		];

		parent::__construct($id, $table, $ds);
	}

	public function afterSave($created, $options = array()) {
		if ($created === false) {
			$this->deleteIssueTypes($this->id);
		}

		$this->joinIssueTypes($this->id);
	}

	private function deleteIssueTypes($id) {
		return $this->ProgramIssueType->deleteAll(array(
			'ProgramIssueType.program_issue_id' => $id
		));
	}

	private function joinIssueTypes($id) {
		if (!is_array($this->data['ProgramIssue']['types'])) {
			return true;
		}

		foreach ($this->data['ProgramIssue']['types'] as $type) {
			$tmp = array(
				'program_issue_id' => $id,
				'type' => $type
			);

			$this->ProgramIssueType->create();
			if (!$this->ProgramIssueType->save($tmp)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Get a label for a changed Scope status.
	 */
	public function getProgramIssueStatuses() {
		if (isset($this->data['ProgramIssue']['status'])) {
			return getProgramIssueStatuses($this->data['ProgramIssue']['status']);
		}

		return false;
	}

	public function getInternalTypes() {
		return getInternalTypes();
	}

	public function getExternalTypes() {
		return getExternalTypes();
	}
}
