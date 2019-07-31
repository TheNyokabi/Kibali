<?php
App::uses('SectionBase', 'Model');
App::uses('Hash', 'Utility');
App::uses('Inflector', 'Utility');

class SystemLog extends SectionBase {
	public $displayField = 'id';
	
	public $actsAs = [
		'Search.Searchable',
	];

	public $belongsTo = [
		'User' => [
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => [
				'SystemLog.user_model' => 'User'
			]
		],
	];

	public $validate = [

	];

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('System Logs');

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
		];

		$this->fieldData = [
			'model' => [
				'label' => __('Model'),
				'editable' => false,
			],
			'foreign_key' => [
				'label' => __('Foreign Key'),
				'editable' => false,
			],
			'action' => [
				'label' => __('Action'),
				'editable' => false,
			],
			'result' => [
				'label' => __('Result'),
				'editable' => false,
			],
			'ip' => [
				'label' => __('Ip'),
				'editable' => false,
			],
			'uri' => [
				'label' => __('Uri'),
				'editable' => false,
			],
			'request_id' => [
				'label' => __('Request ID'),
				'editable' => false,
			],
		];

		$this->advancedFilter = [
			__('General') => [
				'id' => [
					'type' => 'text',
					'name' => __('ID'),
					'show_default' => true,
					'filter' => false
				],
				'action' => [
					'type' => 'select',
					'name' => __('Action'),
					'show_default' => true,
					'filter' => [
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SystemLog.action',
						'field' => 'SystemLog.id',
					],
					'data' => [
						'callable' => '',
						'result_key' => true
					],
				],
				'foreign_key' => [
					'type' => 'text',
					'name' => __('Subject ID'),
					'show_default' => true,
					'filter' => [
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SystemLog.foreign_key',
						'field' => 'SystemLog.id',
					],
				],
				'user' => [
					'type' => 'text',
					'name' => __('User'),
					'show_default' => true,
					'filter' => [
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'User.login',
						'field' => 'SystemLog.user_id',
					],
					'field' => 'User.login',
					'containable' => [
						'User' => [
							'fields' => ['login']
						]
					]
				],
				'message' => [
					'type' => 'text',
					'name' => __('Message'),
					'show_default' => true,
					'filter' => [
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SystemLog.message',
						'field' => 'SystemLog.id',
					],
				],
				'created' => [
					'type' => 'date',
					'name' => __('Date'),
					'show_default' => true,
					'filter' => [
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SystemLog.created',
						'field' => 'SystemLog.id',
					],
				],
			]
		];

		$this->advancedFilterSettings = [
			'pdf_title' => __('System Logs'),
			'pdf_file_name' => __('system_logs'),
			'csv_file_name' => __('system_logs'),
			'max_selection_size' => 10,
			'bulk_actions' => false,
			'history' => false,
			'trash' => false,
			'use_new_filters' => true,
			'actions' => false,
			'url' => [
				'plugin' => 'system_logs',
                'controller' => 'systemLogs',
                'action' => 'index',
                '?' => [
                    'advanced_filter' => 1
                ]
            ],
            'include_timestamps' => false
		];

		parent::__construct($id, $table, $ds);
	}

	public function adaptFilters($model) {
		$Model = ClassRegistry::init($model);

		$this->advancedFilter['General']['action']['data']['callable'] = [$Model, 'listSystemLogActions'];
		$this->advancedFilter['General']['foreign_key']['name'] = $Model->label . ' Id';

		$this->advancedFilterSettings['url'] = [
			'plugin' => 'system_logs',
            'controller' => 'systemLogs',
            'action' => 'index',
            $model,
            '?' => [
                'advanced_filter' => 1
            ]
		];

		$this->advancedFilterSettings['reset'] = [
			'plugin' => (!empty($Model->plugin)) ? Inflector::underscore($Model->plugin) : null,
            'controller' => controllerFromModel($Model->name),
            'action' => 'index',
		];
	}

}