<?php
App::uses('SectionBase', 'Model');

class ProjectExpense extends SectionBase {
	public $displayField = 'description';
	
	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'description'
			)
		),
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'ObjectStatus.ObjectStatus',
		'Visualisation.Visualisation',
	);

	public $mapping = array(
		'indexController' => array(
			'basic' => 'projects',
			'advanced' => 'projectExpenses',
			'params' => array('project_id')
		),
		'titleColumn' => 'description',
		'logRecords' => true,
		'notificationSystem' => true,
		'workflow' => false
	);

	public $validate = array(
		'amount' => array(
			'rule' => 'numeric',
			'required' => true
		),
		'date' => array(
			'rule' => 'date',
			'required' => true
		)
	);

	public $belongsTo = array(
		'Project'
	);

	public $hasMany = array(
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'ProjectExpense'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ProjectExpense'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ProjectExpense'
			)
		),
		'NotificationObject' => array(
			'className' => 'NotificationObject',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'NotificationObject.model' => 'ProjectExpense'
			)
		),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Project Expenses');
		$this->_group = 'security-operations';

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = [
			'amount' => [
				'label' => __('Expense Amount'),
				'editable' => true,
				'description' => __('The amount of money involved in this expense')
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('A brief description of what was purchased')
			],
			'date' => [
				'label' => __('Expense Date'),
				'editable' => true,
				'description' => __('The day the expense was executed')
			],
			'project_id' => [
				'label' => __('Project'),
				'editable' => false,
			],
		];
		
		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'project_id' => array(
					'type' => 'multiple_select',
					'name' => __('Project'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectExpense.project_id',
						'field' => 'ProjectExpense.id',
					),
					'data' => array(
						'method' => 'getProjects',
					),
					'contain' => array(
						'Project' => array(
							'title'
						)
					),
				),
				'amount' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Amount'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectExpense.amount',
						'field' => 'ProjectExpense.id',
					),
					'outputFilter' => array('Ux', 'outputCurrency'),
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectExpense.description',
						'field' => 'ProjectExpense.id',
					)
				),
				'date' => array(
					'type' => 'date',
					'name' => __('Date'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectExpense.date',
						'field' => 'ProjectExpense.id',
					),
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Project Expeneses'),
			'pdf_file_name' => __('project_expeneses'),
			'csv_file_name' => __('project_expeneses'),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
			'use_new_filters' => true
		);

		parent::__construct($id, $table, $ds);
	}

	public function getObjectStatusConfig() {
        return [
            'over_budget' => [
                'trigger' => [
                    $this->Project,
                ]
            ],
            'no_updates' => [
                'trigger' => [
                    $this->Project,
                ]
            ],
        ];
    }

    public function parentModel() {
        return 'Project';
    }

    public function parentNode() {
    	return $this->visualisationParentNode('project_id');
    }

	public function getRecordTitle($id) {
		$title = parent::getRecordTitle($id);
		$textHelper = _getHelperInstance('Text');

		return $textHelper->truncate($title, 50);
	}

	public function afterSave($created, $options = array()) {
		$ret = true;
		if (!empty($this->id)) {
			$projectId = $this->getProjectId($this->id);
			$ret &= $this->Project->triggerStatus('overBudget', $projectId);
		}

		return $ret;
	}

	public function beforeDelete($cascade = true) {
		$this->data['ProjectExpense']['project_id'] = $this->getProjectId($this->id);
		return true;
	}

	public function afterDelete() {
		$ret = true;
		if (!empty($this->data['ProjectExpense']['project_id'])) {
			$ret &= $this->Project->triggerStatus('overBudget', $this->data['ProjectExpense']['project_id']);
		}

		return $ret;
	}

	private function getProjectId($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'ProjectExpense.id' => $id
			),
			'fields' => array('ProjectExpense.project_id'),
			'recursive' => -1
		));

		return $data['ProjectExpense']['project_id'];
	}
}
