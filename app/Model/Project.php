<?php
/*
** Copyright (C) 2011-2015 www.eramba.org
** Author(s):	Esteban Ribicic <kisero@gmail.com>
**
** This program is free software; you can redistribute it and/or modify
** it under the terms of the GNU General Public License Version 2 as
** published by the Free Software Foundation.  You may not use, modify or
** distribute this program under any other version of the GNU General
** Public License.
**
** This program is distributed in the hope that it will be useful,
** but WITHOUT ANY WARRANTY; without even the implied warranty of
** MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
** GNU General Public License for more details.
**
** You should have received a copy of the GNU General Public License
** along with this progra
** Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
App::uses('SectionBase', 'Model');
App::uses('UserFields', 'UserFields.Lib');

class Project extends SectionBase {
	public $displayField = 'title';

	public $mapping = array(
		'titleColumn' => 'title',
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
				'title', 'goal', 'start', 'deadline', 'plan_budget', 'project_status_id'
			)
		),
		'Taggable',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'Visualisation.Visualisation',
		'ObjectStatus.ObjectStatus',
		'CustomFields.CustomFields',
		'UserFields.UserFields' => [
			'fields' => [
				'Owner' => [
					'mandatory' => false
				]
			]
		]
	);

	/*public $virtualFields = array(
		'completion' => 'COUNT(ProjectAchievement.id)'
	);*/

	public $validate = array(
		'title' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'start' => array(
			'rule' => 'date'
		),
		'deadline' => array(
			'rule' => 'date'
		),
		'plan_budget' => array(
			'rule' => 'numeric'
		)
	);

	public $belongsTo = array(
		'ProjectStatus'
	);

	public $hasMany = array(
		'ProjectAchievement',
		'ProjectExpense',
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'Project'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'Project'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'Project'
			)
		),
		'Tag' => array(
			'className' => 'Tag',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Tag.model' => 'Project'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'Risk',
		'ThirdPartyRisk',
		'BusinessContinuity' => array(
			'with' => 'BusinessContinuitiesProjects'
		),
		'SecurityService',
		'Goal',
		'SecurityPolicy',
		'ComplianceManagement',
		'DataAsset',
		'SecurityServiceAuditImprovement',
		'BusinessContinuityPlanAuditImprovement',
		'GoalAuditImprovement',
		'DataAsset'
	);

	/**
	 * To calculate the completion of the Project based on its Tasks completion
	 * 
	 * @var string
	 */
	protected $_ultimateCompletionQuery;

	public static function statuses($value = null) {
        $options = array(
            self::STATUS_PLANNED => __('Planned'),
            self::STATUS_ONGOING => __('Ongoing'),
            self::STATUS_COMPLETED => __('Completed'),
        );
        return parent::enum($value, $options);
    }

    const STATUS_PLANNED = PROJECT_STATUS_PLANNED;
	const STATUS_ONGOING = PROJECT_STATUS_ONGOING;
	const STATUS_COMPLETED = PROJECT_STATUS_COMPLETED;

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Projects');
		$this->_group = 'security-operations';

		$this->_ultimateCompletionQuery = 'SUM(completion/100) / COUNT(id)';

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
		);

		$this->fieldData = [
			'title' => [
				'label' => __('Title'),
				'editable' => true,
				'description' => __('Give the project a title, name or code so it\'s easily identified on the project list menu.')
			],
			'goal' => [
				'label' => __('Goal'),
				'editable' => true,
				'description' => __('Describe the project Goal, it\'s roadmap and deliverables.')
			],
			'start' => [
				'label' => __('Project Start Date'),
				'editable' => true,
				'description' => __('Insert the project kick-off date. The date format for this field is YYYY-MM-DD, the default is todays date.')
			],
			'deadline' => [
				'label' => __('Project Deadline'),
				'editable' => true,
				'description' => __('Insert the project deadline. The date format for this field is YYYY-MM-DD, the default is todays date.')
			],
			'plan_budget' => [
				'label' => __('Planned Budget'),
				'editable' => true,
				'description' => __('Document the planned and approved budget for this project.')
			],
			'project_status_id' => [
				'label' => __('Status'),
				'editable' => true,
				'description' => __('Projects are first planned, then they are initiated (ongoing) and finally finished (completed).')
			],
			'Owner' => $UserFields->getFieldDataEntityData($this, 'Owner', [
				'label' => __('Owner'), 
				'description' => __('Select the project owner. This is the person responsible for ensuring this project delivered as agreed within the timescales and budget.')
			]),
			'Tag' => array(
                'label' => __('Tags'),
				'editable' => true,
				'type' => 'tags',
				'description' => __('Apply tags for this Project.'),
				'empty' => __('Add a tag')
            ),
			'over_budget' => [
				'label' => __('Over Budget'),
				'editable' => false,
			],
			'expired' => [
				'label' => __('Expired'),
				'editable' => false,
			],
			'expired_tasks' => [
				'label' => __('Expired Tasks'),
				'editable' => false,
			],
			'ProjectAchievement' => [
				'label' => __('Project Achievements'),
				'editable' => false,
			],
			'ProjectExpense' => [
				'label' => __('Project Expenses'),
				'editable' => false,
			],
			'Risk' => [
				'label' => __('Asset Risks'),
				'editable' => false,
			],
			'ThirdPartyRisk' => [
				'label' => __('Third Party Risks'),
				'editable' => false,
			],
			'BusinessContinuity' => [
				'label' => __('Business Continuities'),
				'editable' => false,
			],
			'SecurityService' => [
				'label' => __('Security Services'),
				'editable' => false,
			],
			'Goal' => [
				'label' => __('Goals'),
				'editable' => false,
			],
			'SecurityPolicy' => [
				'label' => __('Security Policies'),
				'editable' => false,
			],
			'ComplianceManagement' => [
				'label' => __('Compliance Managements'),
				'editable' => false,
			],
			'DataAsset' => [
				'label' => __('Data Assets'),
				'editable' => false,
			],
			'SecurityServiceAuditImprovement' => [
				'label' => __('Security Service Audit Improvements'),
				'editable' => false,
			],
			'BusinessContinuityPlanAuditImprovement' => [
				'label' => __('Business Continuity Plan Audit Improvements'),
				'editable' => false,
			],
			'GoalAuditImprovement' => [
				'label' => __('Goal Audit Improvements'),
				'editable' => false,
			]
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
					'callback' => array($this, 'getFormattedCompletion')
				),
			),
			'customEmail' =>  true
		);

		$this->mapping['statusManager'] = array(
			'expiredTasks' => array(
				'column' => 'expired_tasks',
				'fn' => 'statusExpiredTasks'
			),
			'expired' => array(
				'column' => 'expired',
				'fn' => 'statusIsExpired',
				'migrateRecords' => array(
					'Risk',
					'ThirdPartyRisk',
					'BusinessContinuity',
					'SecurityService',
					'ComplianceManagement'
				),
				'toggles' => array(
					array(
						'value' => ITEM_STATUS_EXPIRED,
						'message' => __('The Project %s has expired %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Current%.deadline'
						)
					),
					array(
						'value' => ITEM_STATUS_NOT_EXPIRED,
						'message' => __('The Project %s is no longer expired %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Current%.deadline'
						)
					)
				)
			),
			'overBudget' => array(
				'column' => 'over_budget',
				'fn' => 'statusOverBudget',
				'migrateRecords' => array(),
				'toggles' => array(
					array(
						'value' => PROJECT_OVER_BUDGET,
						'message' => __('The sum of current expenses %s for the Project %s is above the planned budget %s'),
						'messageArgs' => array(
							0 => array(
								'type' => 'fn',
								'fn' => 'totalExpenses'
							),
							1 => '%Current%.title',
							2 => array(
								'type' => 'fn',
								'fn' => 'planBudget'
							)
						)
					),
					array(
						'value' => PROJECT_NOT_OVER_BUDGET,
						'message' => __('The sum of current expenses %s for the Project %s is under the planned budget %s'),
						'messageArgs' => array(
							0 => array(
								'type' => 'fn',
								'fn' => 'totalExpenses'
							),
							1 => '%Current%.title',
							2 => array(
								'type' => 'fn',
								'fn' => 'planBudget'
							)
						)
					)
				)
			)
		);

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'title' => array(
					'type' => 'text',
					'name' => __('Title'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.title',
						'field' => 'Project.id',
					)
				),
				'goal' => array(
					'type' => 'text',
					'name' => __('Goal'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.goal',
						'field' => 'Project.id',
					)
				),
				'start' => array(
					'type' => 'date',
					'name' => __('Project Start Date'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.start',
						'field' => 'Project.id',
					),
				),
				'deadline' => array(
					'type' => 'date',
					'name' => __('Project Deadline'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.deadline',
						'field' => 'Project.id',
					),
				),
				'owner_id' => $UserFields->getAdvancedFilterFieldData('Project', 'Owner', [
					'name' => __('Owner'),
				]),
				'ultimate_completion' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Completion'),
					'show_default' => false,
					'filter' => false,
					'field' => 'ultimateCompletionSubquery',
					'outputFilter' => array('Ux', 'outputPercentage'),
				),
				'plan_budget' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Planned Buget'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.plan_budget',
						'field' => 'Project.id',
					),
					'outputFilter' => array('Ux', 'outputCurrency'),
				),
				'tag_title' => array(
					'type' => 'multiple_select',
					'name' => __('Tags'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Tag.title',
						'field' => 'Project.id',
					),
					'data' => array(
						'method' => 'getTags',
					),
					'many' => true,
					'contain' => array(
						'Tag' => array(
							'title'
						)
					),
				),
				'project_status_id' => array(
					'type' => 'select',
					'name' => __('Project Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.project_status_id',
						'field' => 'Project.id',
					),
					'data' => array(
						'method' => 'getStatuses',
					),
					'contain' => array(
						'ProjectStatus' => array(
							'name'
						)
					),
				),
				'tasks' => array(
					'type' => 'text',
					'name' => __('Tasks'),
					'show_default' => true,
					'filter' => false,
					'field' => 'Project.id',
					'outputFilter' => array('Projects', 'outputTasks'),
				),
				'expenses' => array(
					'type' => 'text',
					'name' => __('Expenses'),
					'show_default' => true,
					'filter' => false,
					'field' => 'Project.id',
					'outputFilter' => array('Projects', 'outputExpenses'),
				),
			),
			__('Mitigation') => array(
				'data_asset_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Data Asset Flow'),
                    'show_default' => false,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAsset.id',
                        'field' => 'Project.id',
                    ),
                    'data' => array(
                        'method' => 'getDataAssets',
                    ),
                    'many' => true,
                    'contain' => array(
                        'DataAsset' => array(
                            'title'
                        )
                    ),
                ),
			),
			__('Status') => array(
				'expired' => array(
					'type' => 'select',
					'name' => __('Improvement Project Expired'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.expired',
						'field' => 'Project.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'over_budget' => array(
					'type' => 'select',
					'name' => __('Improvement Project over Budget'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.over_budget',
						'field' => 'Project.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'expired_tasks' => array(
					'type' => 'select',
					'name' => __('Improvement Project with Expired Tasks'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Project.expired_tasks',
						'field' => 'Project.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'expired_tasks_in_future' => array(
					'type' => 'object_status',
					'hidden' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'statusField' => 'expired_tasks_in_future',
						'field' => 'Project.id',
					),
				),
				'no_updates' => array(
					'type' => 'object_status',
					'hidden' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'statusField' => 'no_updates',
						'field' => 'Project.id',
					),
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Projects'),
			'pdf_file_name' => __('projects'),
			'csv_file_name' => __('projects'),
			'additional_actions' => array(
				'ProjectAchievement' => __('Tasks'),
				'ProjectExpense' => __('Expenses')
			),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
			'use_new_filters' => true,
			'add' => true,
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
                'title' => __('Improvement Project over Budget'),
                'callback' => [$this, '_statusOverBudget'],
                'trigger' => [
                	[
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.project_over_budget'
                    ],
                ]
            ],
            'expired_tasks' => [
            	'title' => __('Improvement Project with Expired Tasks'),
                'callback' => [$this, '_statusExpiredTasks'],
                'trigger' => [
                    $this->DataAsset,
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.project_expired_tasks'
                    ],
                ]
            ],
            'expired_tasks_in_future' => [
            	'title' => __('Expiring Tasks on the next two weeks'),
                'callback' => [$this, '_statusExpiredTasksInFuture'],
                'storageSelf' => false
            ],
            'expired' => [
            	'title' => __('Improvement Project Expired'),
                'callback' => [$this, 'statusExpired'],
                'trigger' => [
                	[
                        'model' => $this->SecurityService,
                        'trigger' => 'ObjectStatus.trigger.project_expired'
                    ],
                    [
                        'model' => $this->DataAsset,
                        'trigger' => 'ObjectStatus.trigger.project_expired'
                    ],
                    [
                        'model' => $this->Risk,
                        'trigger' => 'ObjectStatus.trigger.project_expired'
                    ],
                    [
                        'model' => $this->ThirdPartyRisk,
                        'trigger' => 'ObjectStatus.trigger.project_expired'
                    ],
                    [
                        'model' => $this->BusinessContinuity,
                        'trigger' => 'ObjectStatus.trigger.project_expired'
                    ],
                    [
                        'model' => $this->ComplianceManagement,
                        'trigger' => 'ObjectStatus.trigger.project_expired'
                    ],
                ]
            ],
            'no_updates' => [
            	'title' => __('No updates in the last two weeks'),
                'callback' => [$this, '_statusNoUpdates'],
                'storageSelf' => false
            ],
            'audits_improvements' => [
            	'title' => __('Security Services Being Fixed'),
            	'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'ongoing_corrective_actions' => [
            	'title' => __('Security Services Ongoing Corrective Actions'),
            	'trigger' => [
                    $this->SecurityService,
                ]
            ],
            'goal_ongoing_corrective_actions' => [
            	'trigger' => [
                    [
                        'model' => $this->Goal,
                        'trigger' => 'ObjectStatus.trigger.ongoing_corrective_actions'
                    ],
                ]
            ],
        ];
    }

    /**
     * Check if Project, Expenses or Achievements were modified in last two weeks.
     */
    public function _statusNoUpdates() {
    	$dataProject = $this->find('all', [
			'conditions' => [
				'Project.id' => $this->id,
			],
			'fields' => [
				'Project.id',
				'COUNT(Comment.id) AS comments_count',
				'COUNT(Attachment.id) AS attachments_count',
				'IF(DATE(Project.modified) > CURDATE() - INTERVAL 14 DAY, 1, 0) as item_modified',
			],
			'joins' => [
                [
                    'table' => 'comments',
                    'alias' => 'Comment',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Comment.foreign_key = Project.id',
                        'Comment.model' => 'Project',
                        'DATE(Comment.created) > CURDATE() - INTERVAL 14 DAY'
                    ]
                ],
                [
                    'table' => 'attachments',
                    'alias' => 'Attachment',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Attachment.foreign_key = Project.id',
                        'Attachment.model' => 'Project',
                        'DATE(Attachment.created) > CURDATE() - INTERVAL 14 DAY'
                    ]
                ],
            ],
            'group' => [
            	'Project.id'
            ],
			'recursive' => -1
		]);

		$dataAchievement = $this->ProjectAchievement->find('all', [
			'conditions' => [
				'ProjectAchievement.project_id' => $this->id,
			],
			'fields' => [
				'ProjectAchievement.id',
				'COUNT(Comment.id) AS comments_count',
				'COUNT(Attachment.id) AS attachments_count',
				'IF(DATE(ProjectAchievement.modified) > CURDATE() - INTERVAL 14 DAY, 1, 0) as item_modified',
			],
			'joins' => [
                [
                    'table' => 'comments',
                    'alias' => 'Comment',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Comment.foreign_key = ProjectAchievement.id',
                        'Comment.model' => 'ProjectAchievement',
                        'DATE(Comment.created) > CURDATE() - INTERVAL 14 DAY'
                    ]
                ],
                [
                    'table' => 'attachments',
                    'alias' => 'Attachment',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Attachment.foreign_key = ProjectAchievement.id',
                        'Attachment.model' => 'ProjectAchievement',
                        'DATE(Attachment.created) > CURDATE() - INTERVAL 14 DAY'
                    ]
                ],
            ],
            'group' => [
            	'ProjectAchievement.id'
            ],
			'recursive' => -1
		]);

		$dataExpense = $this->ProjectExpense->find('all', [
			'conditions' => [
				'ProjectExpense.project_id' => $this->id,
			],
			'fields' => [
				'ProjectExpense.id',
				'COUNT(Comment.id) AS comments_count',
				'COUNT(Attachment.id) AS attachments_count',
				'IF(DATE(ProjectExpense.modified) > CURDATE() - INTERVAL 14 DAY, 1, 0) as item_modified',
			],
			'joins' => [
                [
                    'table' => 'comments',
                    'alias' => 'Comment',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Comment.foreign_key = ProjectExpense.id',
                        'Comment.model' => 'ProjectExpense',
                        'DATE(Comment.created) > CURDATE() - INTERVAL 14 DAY'
                    ]
                ],
                [
                    'table' => 'attachments',
                    'alias' => 'Attachment',
                    'type' => 'LEFT',
                    'conditions' => [
                        'Attachment.foreign_key = ProjectExpense.id',
                        'Attachment.model' => 'ProjectExpense',
                        'DATE(Attachment.created) > CURDATE() - INTERVAL 14 DAY'
                    ]
                ],
            ],
            'group' => [
            	'ProjectExpense.id'
            ],
			'recursive' => -1
		]);

		$data = array_merge($dataProject, $dataAchievement, $dataExpense);

		foreach ($data as $item) {
			if (!empty($item[0]['comments_count']) || !empty($item[0]['attachments_count']) || !empty($item[0]['item_modified'])) {
				return false;
			}
		}

		return true;
	}

    public function _statusOverBudget() {
    	$data = $this->ProjectExpense->find('first', [
			'conditions' => [
				'ProjectExpense.project_id' => $this->id,
			],
			'fields' => [
				'ROUND(SUM(amount), 2) as expenses', 'Project.plan_budget'
			],
			'joins' => [
                [
                    'table' => 'projects',
                    'alias' => 'Project',
                    'type' => 'INNER',
                    'conditions' => [
                        'Project.id = ProjectExpense.project_id',
                    ]
                ],
            ],
            'having' => ['expenses > Project.plan_budget'],
			'recursive' => -1
		]);

		return (!empty($data));
	}

	public function _statusExpiredTasks() {
		$data = $this->ProjectAchievement->find('count', array(
			'conditions' => array(
				'ProjectAchievement.project_id' => $this->id,
				'DATE(ProjectAchievement.date) < NOW()',
				'ProjectAchievement.completion !=' => 100
			),
			'recursive' => -1
		));

		return (boolean) $data;
	}

	public function _statusExpiredTasksInFuture() {
		$data = $this->ProjectAchievement->find('count', array(
			'conditions' => array(
				'ProjectAchievement.project_id' => $this->id,
				'DATE(ProjectAchievement.date) >= CURDATE()',
				'DATE(ProjectAchievement.date) < CURDATE() + INTERVAL 14 DAY',
				'ProjectAchievement.completion !=' => 100
			),
			'recursive' => -1
		));

		return (boolean) $data;
	}

    public function statusExpired($conditions = null) {
        return parent::statusExpired([
        	'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED,
            'DATE(Project.deadline) < NOW()'
        ]);
    }

	public function afterSave($created, $options = array()) {
		$ret = true;
		if ($this->id) {
			$projectTitle = $this->field($this->mapping['titleColumn']);

			$serviceIds = $this->ProjectsSecurityService->find('list', array(
				'conditions' => array(
					'ProjectsSecurityService.project_id' => $this->id
				),
				'fields' => array('id', 'security_service_id'),
				'recursive' => -1
			));


			$Improvement = $this->ProjectsSecurityServiceAuditImprovement;
			$_ids = $Improvement->find('list', array(
				'conditions' => array(
					'ProjectsSecurityServiceAuditImprovement.project_id' => $this->id,
					// 'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED
				),
				'fields' => ['SecurityServiceAudit.security_service_id'],
				'joins' => $this->SecurityServiceAuditImprovement->getRelatedJoins(),
				'recursive' => -1
			));
			$serviceIds = am($serviceIds, $_ids);

			$goalIds = $this->GoalsProject->find('list', array(
				'conditions' => array(
					'GoalsProject.project_id' => $this->id
				),
				'fields' => array('id', 'goal_id'),
				'recursive' => -1
			));

			$this->triggerData = array(
				'mappedProjectsCompleted' => $projectTitle
			);

			$settings = array(
				'disableToggles' => array('mappedProjects', 'unmappedProjects'),
				'customToggles' => array('ProjectCompleted'),
				'customValues' => $this->triggerData
			);

			$ret &= $this->SecurityService->triggerStatus('ongoingCorrectiveActions', $serviceIds, null, $settings);
			$ret &= $this->Goal->triggerStatus('ongoingCorrectiveActions', $goalIds, null, $settings);

			// $ret &= $this->resaveNotifications($this->id);
		}

		return $ret;
	}

	public function resaveNotifications($id) {
		$ret = true;

		$this->bindNotifications();
		$ret &= $this->NotificationObject->NotificationSystem->saveCustomUsersByModel($this->alias, $id);

		$taskIds = $this->ProjectAchievement->find('list', array(
			'conditions' => array(
				'ProjectAchievement.project_id' => $id
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$this->ProjectAchievement->bindNotifications();
		$ret &= $this->ProjectAchievement->NotificationObject->NotificationSystem->saveCustomUsersByModel('ProjectAchievement', $taskIds);

		$expenseIds = $this->ProjectExpense->find('list', array(
			'conditions' => array(
				'ProjectExpense.project_id' => $id
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$this->ProjectExpense->bindNotifications();
		$ret &= $this->ProjectExpense->NotificationObject->NotificationSystem->saveCustomUsersByModel('ProjectExpense', $expenseIds);

		return $ret;
	}

	public function getUltimateCompletion($id) {
		$completion = $this->ProjectAchievement->find('first', array(
			'conditions' => array(
				'ProjectAchievement.project_id' => $id
			),
			'fields' => array(
				$this->_ultimateCompletionQuery . ' as completion'
			),
			'recursive' => -1
		));

		return (float) $completion[0]['completion'];
	}

	public function formatUltimateCompletion($value) {
		$percentage = CakeNumber::toPercentage($value, 0, array(
			'multiply' => true
		));

		return $percentage;
	}

	public function getFormattedCompletion($id) {
		$completion = $this->getUltimateCompletion($id);

		return $this->formatUltimateCompletion($completion);
	}

	/**
	 * @deprecated status, in favor of Project::statusExpired()
	 */
	public function statusIsExpired($id) {
		$today = date('Y-m-d', strtotime('now'));

		$isExpired = $this->find('count', array(
			'conditions' => array(
				'Project.id' => $id,
				'Project.project_status_id !=' => PROJECT_STATUS_COMPLETED,
				'DATE(Project.deadline) <' => $today
			),
			'recursive' => -1
		));

		return $isExpired;
	}

	/**
	 * @deprecated status, in favor of Project::_statusOverBudget()
	 */
	public function statusOverBudget($id) {
		$budgets = $this->getBudgetValues($id);

		if ($budgets['expense'] > $budgets['planBudget']) {
			return PROJECT_OVER_BUDGET;
		}

		return PROJECT_NOT_OVER_BUDGET;
	}

	public function totalExpenses($id) {
		$budgets = $this->getBudgetValues($id);
		return CakeNumber::currency($budgets['expense']);
	}

	public function planBudget($id) {
		$budgets = $this->getBudgetValues($id);
		return CakeNumber::currency($budgets['planBudget']);
	}

	/**
	 * @deprecated status, in favor of Project::_statusExpiredTasks()
	 */
	public function statusExpiredTasks($id) {
		$expiredTasks = $this->getExpiredTasks($id);

		if (!empty($expiredTasks)) {
			return PROJECT_EXPIRED_TASKS;
		}

		return PROJECT_NOT_EXPIRED_TASKS;
	}

	public function overBudgetMsgParams($id = null) {
		$budgets = $this->getBudgetValues($this->id);

		$planBudget = CakeNumber::currency($budgets['planBudget']);
		$expense = CakeNumber::currency($budgets['expense']);

		return array($expense, $planBudget);
	}

	public function saveOverBudget($id) {
		$budgets = $this->getBudgetValues($id);

		$over_budget = '0';
		if ($budgets['expense'] > $budgets['planBudget']) {
			$over_budget = '1';
		}

		$this->id = $id;
		$ret = $this->save(array('over_budget' => $over_budget), array(
			'validate' => false
		));

		return $ret;
	}

	public function getBudgetValues($id) {
		$expenses = $this->ProjectExpense->find('first', array(
			'conditions' => array(
				'ProjectExpense.project_id' => $id
			),
			'fields' => array('ROUND(SUM(amount), 2) as expenses', 'Project.plan_budget'),
			'recursive' => 0
		));

		$expense = $expenses[0]['expenses'];
		$planBudget = $expenses['Project']['plan_budget'];

		return array(
			'expense' => $expense,
			'planBudget' => $planBudget
		);
	}

	/* --- */

	public function expiredTasksMsgParams($id = null) {
		$expiredTasks = $this->getExpiredTasks($this->id);

		return implode(', ', $expiredTasks);
	}

	public function saveExpiredTasks($id) {
		$expiredTasks = $this->getExpiredTasks($id);

		$expired_tasks = '0';
		if (!empty($expiredTasks)) {
			$expired_tasks = '1';
		}

		$this->id = $id;
		$ret = $this->save(array('expired_tasks' => $expired_tasks), array('validate' => false, 'callbacks' => 'before'));

		return $ret;
	}

	public function getExpiredTasks($id) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$expiredTasks = $this->ProjectAchievement->find('list', array(
			'conditions' => array(
				'ProjectAchievement.project_id' => $id,
				'DATE(ProjectAchievement.date) <' => $today,
				'ProjectAchievement.completion !=' => 100
			),
			'fields' => array('id', 'description'),
			'recursive' => -1
		));

		return $expiredTasks;
	}

	public function getDataAssets() {
        return $this->DataAsset->getList();
    }

	/**
	 * @deprecated
	 */
	public function expiredTaskConditions($data = array()){
		$conditions = array();
		if($data['expired_tasks'] == 1){
			$conditions = array(
				'Project.expired_tasks >' => 0
			);
		}
		elseif($data['expired_tasks'] == 0){
			$conditions = array(
				'Project.expired_tasks' => 0
			);
		}

		return $conditions;
	}

	public function findByTag($data = array(), $filter) {
		$query = $this->Tag->find('list', array(
			'conditions' => array(
				'Tag.title' => $data[$filter['name']],
				'Tag.model' => 'Project'
			),
			'fields' => array(
				'Tag.foreign_key'
			)
		));

		return $query;
	}

	public function getStatuses() {
		$data = $this->ProjectStatus->find('list', array(
		));

		return $data;
	}

	public function ultimateCompletionSubquery() {
		$query = $this->ProjectAchievement->getQuery('all', array(
			'conditions' => array(
				'ProjectAchievement.project_id = Project.id'
			),
			'fields' => array(
				'ROUND(' . $this->_ultimateCompletionQuery . ' * 100, 0) as completion'
			),
			'contain' => array()
		));
		return $query;
	}

    public function getList($excludeCompleted = true) {
    	$conditions = [];

    	if ($excludeCompleted) {
    		$conditions = [
    			$this->alias . '.project_status_id !=' => self::STATUS_COMPLETED
    		];
    	}

        $data = $this->find('list', [
        	'conditions' => $conditions,
            'order' => [
                $this->alias . '.' . $this->displayField => 'ASC'
            ],
        ]);

        return $data;
    }

	/**
	 * Callback used by Status Assessment to calculate over budget field.
	 */
	/*public function queryOverBudget() {
		if ($this->id != null && !isset($this->data['Project']['over_budget'])) {
			$expenses = $this->ProjectExpense->find('first', array(
				'conditions' => array(
					'ProjectExpense.project_id' => $this->id
				),
				'fields' => array('SUM(amount) as expenses'),
				'recursive' => -1
			));

			$expense = $expenses[0]['expenses'];

			if (!isset($this->data['Project']['plan_budget'])) {
				$data = $this->find('first', array(
					'conditions' => array(
						'Project.id' => $this->id
					),
					'fields' => array('plan_budget'),
					'recursive' => -1
				));

				$planBudget = $data['Project']['plan_budget'];
			}
			else {
				$planBudget = $this->data['Project']['plan_budget'];
			}

			if ($expense > $planBudget) {
				$this->data['Project']['over_budget'] = '1';
			}
			else {
				$this->data['Project']['over_budget'] = '0';
			}

			$planBudget = CakeNumber::currency($planBudget);
			$expense = CakeNumber::currency($expense);
			return array($expense, $planBudget);
		}

		return true;
	}*/

	/**
	 * Check if a project is associated with one or more expired project tasks, then modify query.
	 */
	/*public function queryExpiredTasks() {
		if ($this->id != null && !isset($this->data['Project']['expired_tasks'])) {
			$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

			$expiredTasks = $this->ProjectAchievement->find('list', array(
				'conditions' => array(
					'ProjectAchievement.project_id' => $this->id,
					'ProjectAchievement.date <' => $today
				),
				'fields' => array('id', 'description'),
				'recursive' => -1
			));

			if (!empty($expiredTasks)) {
				$this->data['Project']['expired_tasks'] = '1';

				return array(implode(', ', $expiredTasks));
			}
			else {
				$this->data['Project']['expired_tasks'] = '0';
			}
		}

		return null;
	}*/

}
