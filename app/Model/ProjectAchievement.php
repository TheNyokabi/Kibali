<?php
App::uses('SectionBase', 'Model');
App::uses('InheritanceInterface', 'Model/Interface');
App::uses('UserFields', 'UserFields.Lib');

class ProjectAchievement extends SectionBase implements InheritanceInterface {
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
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'UserFields.UserFields' => [
			'fields' => ['TaskOwner']
		]
	);

	public $mapping = array(
		'indexController' => array(
			'basic' => 'projects',
			'advanced' => 'projectAchievements',
			'params' => array('project_id')
		),
		'titleColumn' => 'description',
		'logRecords' => true,
		'notificationSystem' => true,
		'workflow' => false

	);

	public $validate = array(
		'date' => array(
			'rule' => 'date',
			'required' => true
		),
		'completion' => array(
			'rule' => 'numeric',
			'required' => true
		),
		'task_order' => array(
			'rule' => 'numeric',
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
				'Comment.model' => 'ProjectAchievement'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'ProjectAchievement'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'ProjectAchievement'
			)
		),
		'NotificationObject' => array(
			'className' => 'NotificationObject',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'NotificationObject.model' => 'ProjectAchievement'
			)
		),
	);

	public $virtualFields = array(
		'task_duration' => 'DATEDIFF(ProjectAchievement.date, ProjectAchievement.created)',
		// 'completion_total' => 'SUM(DATEDIFF(ProjectAchievement.date, ProjectAchievement.created))'
	);
	
	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Project Tasks');
		$this->_group = 'security-operations';

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = [
			'TaskOwner' => $UserFields->getFieldDataEntityData($this, 'TaskOwner', [
				'label' => __('Task Owner'), 
				'description' => __('Typically the individual responsible to ensure the task is completed')
			]),
			'date' => [
				'label' => __('Task Deadline'),
				'editable' => true,
				'description' => __('The deadline of the task')
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				'description' => __('A brief description of what the task goal is')
			],
			'completion' => [
				'label' => __('How completed is this task?'),
				'editable' => true,
				'options' => 'getPercentageOptions',
				'description' => __('Percentage of completion of the task')
			],
			'task_order' => [
				'label' => __('Task Order'),
				'editable' => true,
				'description' => __('The task number dictates the order in which tasks must be executed')
			],
			'expired' => [
				'label' => __('Expired'),
				'editable' => false,
			],
			'project_id' => [
				'label' => __('Project'),
				'editable' => false,
			],
		];
		
		$this->notificationSystem = array(
			'macros' => array(
				'PROJECT_ID' => array(
					'field' => 'Project.id',
					'name' => __('Project ID')
				),
				'PROJECT_TITLE' => array(
					'field' => 'Project.title',
					'name' => __('Project Title')
				),
				'PROJECT_END' => array(
					'field' => 'Project.deadline',
					'name' => __('Project End')
				),
				'PROJECT_COMPLETION' => array(
					'type' => 'callback',
					'name' => __('Project Completion'),
					'callback' => array($this, 'getProjectFormattedCompletion')
				),
				'TASK_ID' => array(
					'field' => 'ProjectAchievement.id',
					'name' => __('Project Task ID')
				),
				'TASK_DESCRIPTION' => array(
					'field' => 'ProjectAchievement.description',
					'name' => __('Project Task Description')
				),
				'TASK_END' => array(
					'field' => 'ProjectAchievement.date',
					'name' => __('Project Task End')
				),
			),
			'customEmail' =>  true
		);

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
						'findField' => 'ProjectAchievement.project_id',
						'field' => 'ProjectAchievement.id',
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
				'task_owner_id' => $UserFields->getAdvancedFilterFieldData('ProjectAchievement', 'TaskOwner', [
					'name' => __('Task Owner'),
				]),
				'project_owner_id' => $UserFields->getForeignAdvancedFilterFieldData('ProjectAchievement', 'Project', 'Owner', [
					'name' => __('Project Owner')
				]),
				'date' => array(
					'type' => 'date',
					'name' => __('Deadline'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectAchievement.date',
						'field' => 'ProjectAchievement.id',
					),
				),
				'completion' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Completion'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectAchievement.completion',
						'field' => 'ProjectAchievement.id',
					),
					'data' => array(
						'minVal' => 1,
						'maxVal' => 100
					),
					'outputFilter' => array('Ux', 'outputPercentage'),
				),
				'task_order' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Order'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectAchievement.task_order',
						'field' => 'ProjectAchievement.id',
					),
					'data' => array(
						'minVal' => 1,
						'maxVal' => 20
					)
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'ProjectAchievement.description',
						'field' => 'ProjectAchievement.id',
					)
				),
			),
			__('Status') => [
				'project_status_id' => array(
					'type' => 'select',
					'name' => __('Project Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.project_status_id',
						'field' => 'ProjectAchievement.project_id',
					),
					'data' => [
						'method' => 'getProjectStatuses',
						'result_key' => true,
					],
					'field' => 'Project.project_status_id',
					'containable' => [
						'Project' => [
							'fields' => [
								'id', 'project_status_id'
							]
						]
					]
				),
			],
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Project Tasks'),
			'pdf_file_name' => __('project_tasks'),
			'csv_file_name' => __('project_tasks'),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
			'use_new_filters' => true
		);

		parent::__construct($id, $table, $ds);
	}

	public function parentModel() {
        return 'Project';
    }

    public function parentNode() {
    	return $this->visualisationParentNode('project_id');
    }

	public function getObjectStatusConfig() {
        return [
            'expired_tasks' => [
                'trigger' => [
                    $this->Project,
                ]
            ],
            'expired_tasks_in_future' => [
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

	public function getRecordTitle($id) {
		$title = parent::getRecordTitle($id);
		$textHelper = _getHelperInstance('Text');

		return $textHelper->truncate($title, 50);
	}

	public function beforeSave($options = array()) {
		// $this->logStatusToProject();

		return true;
	}

	public function afterSave($created, $options = array()) {
		// if (!empty($this->data['ProjectAchievement']['project_id'])) {
		// 	return $this->Project->saveExpiredTasks($this->data['ProjectAchievement']['project_id']);
		// }

		$ret = true;
		if (!empty($this->id)) {
			$projectId = $this->getProjectId($this->id);
			$ret &= $this->Project->triggerStatus('expiredTasks', $projectId);

			// return $this->Project->saveExpiredTasks($projectId);
		}

		return $ret;
	}

	public function beforeDelete($cascade = true) {
		$this->data['ProjectAchievement']['project_id'] = $this->getProjectId($this->id);
		return true;
	}

	public function afterDelete() {
		$ret = true;
		if (!empty($this->data['ProjectAchievement']['project_id'])) {
			$ret &= $this->Project->triggerStatus('expiredTasks', $this->data['ProjectAchievement']['project_id']);
			// return $this->Project->saveExpiredTasks($this->data['ProjectAchievement']['project_id']);
		}

		return $ret;
	}

	public function getProjectStatuses() {
		return $this->Project->getStatuses();
	}

	public function getProjectFormattedCompletion($id) {
		$this->id = $id;
		$projectId = $this->field('project_id');

		return $this->Project->getFormattedCompletion($projectId);
	}

	/**
	 * When a task is created or modified as expired, create a record for related project.
	 */
	private function logStatusToProject() {
		if (!empty($this->data['ProjectAchievement']['expired']) && $this->data['ProjectAchievement']['expired'] == 1) {
			if ($this->id == null) {
				$this->createProjectLog($this->data['ProjectAchievement']['project_id'], $this->data['ProjectAchievement']['description']);
			}
			else {
				$record = $this->find('first', array(
					'conditions' => array(
						'id' => $this->id
					),
					'fields' => array('expired', 'description', 'project_id'),
					'recursive' => -1
				));

				if ($record['ProjectAchievement']['expired'] != $this->data['ProjectAchievement']['expired']) {
					$this->createProjectLog($record['ProjectAchievement']['project_id'], $record['ProjectAchievement']['description']);
				}
			}
		}
	}

	private function createProjectLog($projectId, $description) {
		$this->Project->id = $projectId;
		$this->Project->addNoteToLog(__('One or more tasks for this project has expired: %s', $description));
		$this->Project->setSystemRecord($projectId, 2);
	}

	private function getProjectId($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'ProjectAchievement.id' => $id
			),
			'fields' => array('ProjectAchievement.project_id'),
			'recursive' => -1,
			'softDelete' => false
		));

		return $data['ProjectAchievement']['project_id'];
	}

	public function editSaveQuery() {
		$this->expiredStatusToQuery('expired', 'date');
	}
}
