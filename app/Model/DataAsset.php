<?php
App::uses('SectionBase', 'Model');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');
App::uses('DataAssetSettingsUser', 'Model');
App::uses('Country', 'Model');
App::uses('DataAssetSettingsThirdParty', 'Model');
App::uses('DataAssetGdprDataType', 'Model');
App::uses('DataAssetGdprCollectionMethod', 'Model');
App::uses('DataAssetGdprLawfulBase', 'Model');
App::uses('DataAssetGdprThirdPartyCountry', 'Model');
App::uses('DataAssetGdprArchivingDriver', 'Model');
App::uses('InheritanceInterface', 'Model/Interface');
App::uses('UserFields', 'UserFields.Lib');

class DataAsset extends SectionBase implements InheritanceInterface {
	public $displayField = 'title';

	public $actsAs = [
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => [
			'config' => 'Strict',
			'fields' => [
				'description', 'data_asset_status_id', 'asset_id'
			]
		],
        'CustomFields.CustomFields',
        'AuditLog.Auditable',
        'Utils.SoftDelete',
        'ObjectStatus.ObjectStatus',
        'Visualisation.Visualisation',
        'UserFields.UserFields'
	];

	public $mapping = [
		'titleColumn' => 'description',
		'logRecords' => true,
		'workflow' => false
	];

	public $workflow = [
	];

	public $validate = [
		'data_asset_status_id' => [
			'rule' => 'notBlank',
			'required' => true
		],
		'title' => [
			'rule' => 'notBlank',
			'required' => true
		],
	];

	public $gdprValidate = [
        'BusinessUnit' => [
            'minCount' => [
                'rule' => ['multiple', ['min' => 1]],
                'message' => 'You have to select at least one option',
                'required' => true
            ]
        ],
	];

	public $belongsTo = [
		'DataAssetStatus',
		'DataAssetInstance'
	];

    public $hasOne = [
        'DataAssetGdpr'
    ];

	public $hasMany = [
		'Attachment' => [
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => [
				'Attachment.model' => 'DataAsset'
			]
		],
		'Comment' => [
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => [
				'Comment.model' => 'DataAsset'
			]
		],
		'SystemRecord' => [
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => [
				'SystemRecord.model' => 'DataAsset'
			]
		]
	];

	public $hasAndBelongsToMany = [
		'SecurityService',
		'BusinessUnit',
		'ThirdParty',
		'Project' => [
			'with' => 'DataAssetsProject'
		],
		'SecurityPolicy',
		'Risk' => [
            'className' => 'Risk',
            'with' => 'DataAssetsRisk',
            'joinTable' => 'data_assets_risks',
            'foreignKey' => 'data_asset_id',
            'associationForeignKey' => 'risk_id',
            'conditions' => [
                'DataAssetsRisk.model' => 'Risk'
            ]
        ],
        'ThirdPartyRisk' => [
            'className' => 'ThirdPartyRisk',
            'with' => 'DataAssetsRisk',
            'joinTable' => 'data_assets_risks',
            'foreignKey' => 'data_asset_id',
            'associationForeignKey' => 'risk_id',
            'conditions' => [
                'DataAssetsRisk.model' => 'ThirdPartyRisk'
            ]
        ],
        'BusinessContinuity' => [
            'className' => 'BusinessContinuity',
            'with' => 'DataAssetsRisk',
            'joinTable' => 'data_assets_risks',
            'foreignKey' => 'data_asset_id',
            'associationForeignKey' => 'risk_id',
            'conditions' => [
                'DataAssetsRisk.model' => 'BusinessContinuity'
            ]
        ],
	];

    /*
     * static enum: Model::function()
     * @access static
     */
    public static function statuses($value = null) {
        $options = [
            self::STATUS_COLLECTED => __('Collected'),
            self::STATUS_MODIFIED => __('Modified'),
            self::STATUS_STORED => __('Stored'),
            self::STATUS_TRANSIT => __('Transit'),
            self::STATUS_DELETED => __('Deleted'),
        ];
        return parent::enum($value, $options);
    }

    const STATUS_COLLECTED = 1;
    const STATUS_MODIFIED = 2;
    const STATUS_STORED = 3;
    const STATUS_TRANSIT = 4;
    const STATUS_DELETED = 5;

	/*
     * static enum: Model::function()
     * @access static
     */
    public static function statusesInfo($value = null) {
        $options = [
        	self::STATUS_COLLECTED => __('When information is created, for example a receptionist takes note of a customer patient data for an appointment or a system receives credit card information to process a payment.'),
            self::STATUS_MODIFIED => __('When existing records are modified, for example a medical appointment is updated or contact information is updated over the phone.'),
            self::STATUS_STORED => __('Data is stored in paper or digital format.'),
            self::STATUS_TRANSIT => __('Data is sent over networks, voice (telephone).'),
            self::STATUS_DELETED => __('When data is deleted, for example when malfunction hard drives are destroyed, a system deletes with SQL commands, a share drive file is "Trashed".'),
        ];
        return parent::enum($value, $options);
    }

	public function __construct($id = false, $table = null, $ds = null)
    {
        //
        // Init helper Lib for UserFields Module
        $UserFields = new UserFields();
        //
        
		$this->label = __('Data Assets');
        $this->_group = parent::SECTION_GROUP_ASSET_MGT;

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
			'risk-management' => [
				'label' => __('Risk Management')
			],
			'mitigating-controls' => [
				'label' => __('Mitigating Controls')
			]
		];

		$this->fieldData = [
			'title' => [
				'label' => __('Title'),
				'editable' => true,
				'description' => __('Set a title that describes this flow and stage. For example "Callcenter receives customer information".'),
			],
			'description' => [
				'label' => __('Flow Description'),
				'editable' => true,
				'description' => __('OPTIONAL: Describe the stage, the context of the situation, Etc.'),
			],
			'data_asset_status_id' => [
				'label' => __('Stage'),
				'editable' => true,
                'options' => [$this, 'statuses'],
				'description' => __('Choose the stage being analysed.'),
			],
			'order' => [
				'label' => __('Order'),
				'editable' => false,
				'type' => 'select',
				'description' => __('Select from the dropbox the "place" in which this stage happens.'),
			],
			'data_asset_instance_id' => [
				'label' => __('Data Asset Instance'),
				'editable' => false,
			],
			'SecurityService' => [
				'label' => __('Compensating controls'),
				'editable' => true,
				'group' => 'mitigating-controls',
				'description' => __('Choose one or more controls from Control Catalogue / Security Services module used to protect this asset in this particular stage.'),
			],
			'BusinessUnit' => [
				'label' => __('Business Units'),
				'editable' => true,
				'description' => __('Select one or more Business Units (Organisation / Business Units) involved on this stage.'),
			],
			'ThirdParty' => [
				'label' => __('Third Parties'),
				'editable' => true,
				'description' => __('OPTIONAL: Select one ore more Third Parties (Organisation / Third Parties) with whom this information is being shared. Sharing data with Third Parties is an indication that further risk analysis must be involved.'),
			],
			'Project' => [
				'label' => __('Improvement Projects'),
				'editable' => true,
				'group' => 'mitigating-controls',
				'description' => __('Choose one or more projects from Security Operations / Project Management that describe the improvements planned for this stage.'),
			],
			'Risk' => [
				'label' => __('Asset Risks'),
				'editable' => true,
				'group' => 'risk-management',
				'description' => __('Select one or more Risks from the Risk Management / Asset Risk Management module that are related to this stage. All controls and policies used in this Risk will be pre-selected on the next tab "Mitigation Controls".'),
			],
			'ThirdPartyRisk' => [
				'label' => __('Third Party Risks'),
				'editable' => true,
				'group' => 'risk-management',
				'description' => __('Select one or more Risks from the Risk Management / Third Party Risk Management module that are related to this stage.'),
			],
			'BusinessContinuity' => [
				'label' => __('Business Continuities'),
				'editable' => true,
				'group' => 'risk-management',
				'description' => __('Select one or more Risks from the Risk Management / Business Impact Analysis module that are related to this stage.'),
			],
			'SecurityPolicy' => [
				'label' => __('Security Policies'),
				'editable' => true,
				'options' => [$this, 'getSecurityPolicies'],
				'group' => 'mitigating-controls',
				'description' => __('Select one or more documents (Control Catalogue / Security Policies) that describe related policies, procedures, templates and standards for this stage.'),
			],
		];

        $this->advancedFilter = [
            __('Asset') => [
                'asset_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Asset Name'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.asset_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getAssets',
                        'result_key' => true
                    ],
                    'contain' => [
                        'DataAssetInstance' => [
                            'asset_id'
                        ]
                    ]
                ],
                'asset_owner_id' => [
                    'type' => 'multiple_select',
                    'name' => __('BU Owner'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Asset.asset_owner_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetInstance' => [
                                'findField' => 'DataAssetInstance.asset_id',
                                'field' => 'DataAssetInstance.id',
                            ],
                            'Asset' => [
                                'findField' => 'Asset.asset_owner_id',
                                'field' => 'Asset.id',
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getBusinessUnits',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.Asset.asset_owner_id',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'asset_id'],
                            'Asset' => [
                                'fields' => ['id', 'asset_owner_id'],
                            ]
                        ]
                    ]
                ],
                'das_data_owner_id' => $UserFields->getForeignAdvancedFilterFieldData('DataAsset', 'DataAssetSetting', 'DataOwner', [
                    'name' => __('Data Owner'),
                    'show_default' => true,
                    'filter' => [
                        'method' => 'findComplexType',
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                ], 'DataAssetInstance'),
                /*
                'owner_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Owner'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetSettingsUser.user_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.id',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                            'DataAssetSettingsUser' => [
                                'findField' => 'DataAssetSettingsUser.user_id',
                                'field' => 'DataAssetSettingsUser.data_asset_setting_id',
                                'conditions' => [
                                    'DataAssetSettingsUser.type' => DataAssetSettingsUser::TYPE_OWNER
                                ]
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getUsers',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetInstance.DataAssetSetting.Owner.{n}.id',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'asset_id'],
                            'DataAssetSetting' => [
                                'fields' => ['id'],
                                'Owner' => [
                                    'fields' => ['id']
                                ]
                            ]
                        ]
                    ]
                ],
                */
            ],
            __('GDPR') => [
                'gdpr_enabled' => [
                    'type' => 'select',
                    'name' => __('GDPR Enabled'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetSetting.gdpr_enabled',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.gdpr_enabled',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                        ],
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.DataAssetSetting.gdpr_enabled',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id'],
                            'DataAssetSetting' => [
                                'fields' => ['id', 'gdpr_enabled'],
                            ]
                        ]
                    ]
                ],
                'driver_for_compliance' => [
                    'type' => 'text',
                    'name' => __('Driver for Compliance'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetSetting.driver_for_compliance',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.driver_for_compliance',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                        ],
                    ],
                    'field' => 'DataAssetInstance.DataAssetSetting.driver_for_compliance',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id'],
                            'DataAssetSetting' => [
                                'fields' => ['id', 'driver_for_compliance'],
                            ]
                        ]
                    ]
                ],
                'dpo' => [
                    'type' => 'multiple_select',
                    'name' => __('DPO Role'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetSettingsUser.user_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.id',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                            'DataAssetSettingsUser' => [
                                'findField' => 'DataAssetSettingsUser.user_id',
                                'field' => 'DataAssetSettingsUser.data_asset_setting_id',
                                'conditions' => [
                                    'DataAssetSettingsUser.type' => DataAssetSettingsUser::TYPE_DPO
                                ]
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getUsers',
                        'result_key' => true
                    ],
                    'many' => true,
                    'outputFilter' => array('DataAssetSettings', 'outputDpo'),
                    'field' => 'all',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id'],
                            'DataAssetSetting' => [
                                'fields' => ['id', 'dpo_empty'],
                                'Dpo' => [
                                    'fields' => ['id', 'full_name'],
                                ]
                            ]
                        ]
                    ]
                ],
                'processor' => [
                    'type' => 'multiple_select',
                    'name' => __('Processor Role'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetSettingsThirdParty.third_party_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.id',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                            'DataAssetSettingsThirdParty' => [
                                'findField' => 'DataAssetSettingsThirdParty.third_party_id',
                                'field' => 'DataAssetSettingsThirdParty.data_asset_setting_id',
                                'conditions' => [
                                    'DataAssetSettingsThirdParty.type' => DataAssetSettingsThirdParty::TYPE_PROCESSOR
                                ]
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getThirdParties',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetInstance.DataAssetSetting.Processor.{n}.id',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id'],
                            'DataAssetSetting' => [
                                'fields' => ['id'],
                                'Processor' => [
                                    'fields' => ['id'],
                                ]
                            ]
                        ]
                    ]
                ],
                'controller' => [
                    'type' => 'multiple_select',
                    'name' => __('Controller Role'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetSettingsThirdParty.third_party_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.id',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                            'DataAssetSettingsThirdParty' => [
                                'findField' => 'DataAssetSettingsThirdParty.third_party_id',
                                'field' => 'DataAssetSettingsThirdParty.data_asset_setting_id',
                                'conditions' => [
                                    'DataAssetSettingsThirdParty.type' => DataAssetSettingsThirdParty::TYPE_CONTROLLER
                                ]
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getThirdParties',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetInstance.DataAssetSetting.Controller.{n}.id',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id'],
                            'DataAssetSetting' => [
                                'fields' => ['id'],
                                'Controller' => [
                                    'fields' => ['id'],
                                ]
                            ]
                        ]
                    ]
                ],
                'controller_representative' => [
                    'type' => 'multiple_select',
                    'name' => __('Controller Representative'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetSettingsUser.user_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.id',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                            'DataAssetSettingsUser' => [
                                'findField' => 'DataAssetSettingsUser.user_id',
                                'field' => 'DataAssetSettingsUser.data_asset_setting_id',
                                'conditions' => [
                                    'DataAssetSettingsUser.type' => DataAssetSettingsUser::TYPE_CONTROLLER_REPRESENTATIVE
                                ]
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getUsers',
                        'result_key' => true
                    ],
                    'many' => true,
                    'outputFilter' => array('DataAssetSettings', 'outputControllerRepresentative'),
                    'field' => 'all',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id'],
                            'DataAssetSetting' => [
                                'fields' => ['id', 'controller_representative_empty'],
                                'ControllerRepresentative' => [
                                    'fields' => ['id', 'full_name'],
                                ]
                            ]
                        ]
                    ],
                ],
                'supervisory_authority' => [
                    'type' => 'multiple_select',
                    'name' => __('Supervisory Authority'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Country.country_id',
                        'field' => 'DataAsset.data_asset_instance_id',
                        'path' => [
                            'DataAssetSetting' => [
                                'findField' => 'DataAssetSetting.id',
                                'field' => 'DataAssetSetting.data_asset_instance_id',
                            ],
                            'Country' => [
                                'findField' => 'Country.country_id',
                                'field' => 'Country.foreign_key',
                                'conditions' => [
                                    'Country.model' => 'DataAssetSetting',
                                    'Country.type' => Country::TYPE_DATA_ASSET_SETTING_SUPERVISORY_AUTHORITY,
                                ]
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getEuropeCountries',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetInstance.DataAssetSetting.SupervisoryAuthority.{n}.country_id',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id'],
                            'DataAssetSetting' => [
                                'fields' => ['id'],
                                'SupervisoryAuthority' => [
                                    'fields' => ['id', 'country_id'],
                                ]
                            ]
                        ]
                    ]
                ],
            ],
            __('General') => [
                'id' => [
                    'type' => 'text',
                    'name' => __('ID'),
                    'show_default' => true,
                    'filter' => false
                ],
                'data_asset_status_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Stage Name'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAsset.data_asset_status_id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'statuses',
                        'result_key' => true
                    ],
                ],
                'title' => [
                    'type' => 'text',
                    'name' => __('Title'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAsset.title',
                        'field' => 'DataAsset.id',
                    ],
                ],
                'description' => [
                    'type' => 'text',
                    'name' => __('Flow Description'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAsset.description',
                        'field' => 'DataAsset.id',
                    ],
                ],
                'order' => [
                    'type' => 'number',
                    'name' => __('Order'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAsset.order',
                        'field' => 'DataAsset.id',
                    ],
                ],
                'business_unit_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Involved Business Units'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'BusinessUnit.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getBusinessUnits',
                    ],
                    'many' => true,
                    'field' => 'BusinessUnit.{n}.name',
                    'containable' => [
                        'BusinessUnit' => [
                            'fields' => ['id', 'name'],
                        ]
                    ]
                ],
                'third_party_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Involved Third Parties'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'ThirdParty.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getThirdParties',
                    ],
                    'many' => true,
                    'field' => 'ThirdParty.{n}.name',
                    'containable' => [
                        'ThirdParty' => [
                            'fields' => ['id', 'name'],
                        ]
                    ]
                ],
            ],
            __('Risk Management') => [
                'risk_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Asset Based Risks'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Risk.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getRisks',
                    ],
                    'many' => true,
                    'field' => 'Risk.{n}.title',
                    'containable' => [
                        'Risk' => [
                            'fields' => ['id', 'title'],
                        ]
                    ]
                ],
                'third_party_risk_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Third Party Risks'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'ThirdPartyRisk.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getThirdPartyRisks',
                    ],
                    'many' => true,
                    'field' => 'ThirdPartyRisk.{n}.title',
                    'containable' => [
                        'ThirdPartyRisk' => [
                            'fields' => ['id', 'title'],
                        ]
                    ]
                ],
                'business_continuity_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Business Risks'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'BusinessContinuity.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getBusinessContinuities',
                    ],
                    'many' => true,
                    'field' => 'BusinessContinuity.{n}.title',
                    'containable' => [
                        'BusinessContinuity' => [
                            'fields' => ['id', 'title'],
                        ]
                    ]
                ],
            ],
            __('Mitigating Controls') => [
                'security_service_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Security Services'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'SecurityService.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getSecurityServices',
                    ],
                    'many' => true,
                    'field' => 'SecurityService.{n}.name',
                    'containable' => [
                        'SecurityService' => [
                            'fields' => ['id', 'name'],
                        ]
                    ]
                ],
                'security_policy_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Policies'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'SecurityPolicy.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getSecurityPolicies',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'SecurityPolicy.{n}.id',
                    'containable' => [
                        'SecurityPolicy' => [
                            'fields' => ['id', 'index'],
                        ]
                    ]
                ],
                'project_id' => [
                    'type' => 'multiple_select',
                    'name' => __('Projects'),
                    'show_default' => true,
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Project.id',
                        'field' => 'DataAsset.id',
                    ],
                    'data' => [
                        'method' => 'getProjects',
                    ],
                    'many' => true,
                    'field' => 'Project.{n}.title',
                    'containable' => [
                        'Project' => [
                            'fields' => ['id', 'title'],
                        ]
                    ]
                ],
            ],
            __('Status') => [
                'asset_missing_review' => [
                    'type' => 'select',
                    'name' => __('Asset Missing Review'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.asset_missing_review',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.asset_missing_review',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'asset_missing_review'],
                        ]
                    ]
                ],
                'controls_with_issues' => [
                    'type' => 'select',
                    'name' => __('Controls with Issues'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.controls_with_issues',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.controls_with_issues',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'controls_with_issues'],
                        ]
                    ]
                ],
                'controls_with_failed_audits' => [
                    'type' => 'select',
                    'name' => __('Controls with failed Audits'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.controls_with_failed_audits',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.controls_with_failed_audits',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'controls_with_failed_audits'],
                        ]
                    ]
                ],
                'controls_with_missing_audits' => [
                    'type' => 'select',
                    'name' => __('Controls with missing Audits'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.controls_with_missing_audits',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.controls_with_missing_audits',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'controls_with_missing_audits'],
                        ]
                    ]
                ],
                'policies_with_missing_reviews' => [
                    'type' => 'select',
                    'name' => __('Policies with Missing Reviews'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.policies_with_missing_reviews',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.policies_with_missing_reviews',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'policies_with_missing_reviews'],
                        ]
                    ]
                ],
                'risks_with_missing_reviews' => [
                    'type' => 'select',
                    'name' => __('Risks with Missing Reviews'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.risks_with_missing_reviews',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.risks_with_missing_reviews',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'risks_with_missing_reviews'],
                        ]
                    ]
                ],
                'project_expired' => [
                    'type' => 'select',
                    'name' => __('Project Expired'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.project_expired',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.project_expired',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'project_expired'],
                        ]
                    ]
                ],
                'expired_tasks' => [
                    'type' => 'select',
                    'name' => __('Expired Tasks'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.expired_tasks',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.expired_tasks',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'expired_tasks'],
                        ]
                    ]
                ],
                'incomplete_analysis' => [
                    'type' => 'select',
                    'name' => __('Incomplete Analysis'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.incomplete_analysis',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.incomplete_analysis',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'incomplete_analysis'],
                        ]
                    ]
                ],
                'incomplete_gdpr_analysis' => [
                    'type' => 'select',
                    'name' => __('Incomplete GDPR Analysis'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetInstance.incomplete_gdpr_analysis',
                        'field' => 'DataAsset.data_asset_instance_id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetInstance.incomplete_gdpr_analysis',
                    'containable' => [
                        'DataAssetInstance' => [
                            'fields' => ['id', 'incomplete_gdpr_analysis'],
                        ]
                    ]
                ],
            ],
            __('Collected') => [
                'data_asset_gdpr_data_type' => [
                    'type' => 'multiple_select',
                    'name' => __('Type of Data'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdprDataType.data_type',
                        'field' => 'DataAsset.id',
                        'path' => [
                            'DataAssetGdpr' => [
                                'findField' => 'DataAssetGdpr.id',
                                'field' => 'DataAssetGdpr.data_asset_id',
                            ],
                            'DataAssetGdprDataType' => [
                                'findField' => 'DataAssetGdprDataType.data_type',
                                'field' => 'DataAssetGdprDataType.data_asset_gdpr_id',
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'dataTypes',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetGdpr.DataAssetGdprDataType.{n}.data_type',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['id'],
                            'DataAssetGdprDataType' => [
                                'fields' => ['data_type'],
                            ]
                        ]
                    ]
                ],
                'purpose' => [
                    'type' => 'text',
                    'name' => __('Purpose'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.purpose',
                        'field' => 'DataAsset.id',
                    ],
                    'field' => 'DataAssetGdpr.purpose',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['purpose'],
                        ]
                    ]
                ],
                'right_to_be_informed' => [
                    'type' => 'text',
                    'name' => __('Right To be Informed'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.right_to_be_informed',
                        'field' => 'DataAsset.id',
                    ],
                    'field' => 'DataAssetGdpr.right_to_be_informed',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['right_to_be_informed'],
                        ]
                    ]
                ],
                'data_subject' => [
                    'type' => 'text',
                    'name' => __('Data Subject'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.data_subject',
                        'field' => 'DataAsset.id',
                    ],
                    'field' => 'DataAssetGdpr.data_subject',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['data_subject'],
                        ]
                    ]
                ],
                'data_asset_gdpr_collection_method' => [
                    'type' => 'multiple_select',
                    'name' => __('Collection Method'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdprCollectionMethod.collection_method',
                        'field' => 'DataAsset.id',
                        'path' => [
                            'DataAssetGdpr' => [
                                'findField' => 'DataAssetGdpr.id',
                                'field' => 'DataAssetGdpr.data_asset_id',
                            ],
                            'DataAssetGdprCollectionMethod' => [
                                'findField' => 'DataAssetGdprCollectionMethod.collection_method',
                                'field' => 'DataAssetGdprCollectionMethod.data_asset_gdpr_id',
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'collectionMethods',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetGdpr.DataAssetGdprCollectionMethod.{n}.collection_method',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['id'],
                            'DataAssetGdprCollectionMethod' => [
                                'fields' => ['collection_method'],
                            ]
                        ]
                    ]
                ],
                'volume' => [
                    'type' => 'text',
                    'name' => __('Volume'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.volume',
                        'field' => 'DataAsset.id',
                    ],
                    'field' => 'DataAssetGdpr.volume',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['volume'],
                        ]
                    ]
                ],
                'recived_data' => [
                    'type' => 'text',
                    'name' => __('Recived Data'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.recived_data',
                        'field' => 'DataAsset.id',
                    ],
                    'field' => 'DataAssetGdpr.recived_data',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['recived_data'],
                        ]
                    ]
                ],
                'data_asset_gdpr_lawful_base' => [
                    'type' => 'multiple_select',
                    'name' => __('Lawful Base'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdprLawfulBase.lawful_base',
                        'field' => 'DataAsset.id',
                        'path' => [
                            'DataAssetGdpr' => [
                                'findField' => 'DataAssetGdpr.id',
                                'field' => 'DataAssetGdpr.data_asset_id',
                            ],
                            'DataAssetGdprLawfulBase' => [
                                'findField' => 'DataAssetGdprLawfulBase.lawful_base',
                                'field' => 'DataAssetGdprLawfulBase.data_asset_gdpr_id',
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'lawfulBases',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetGdpr.DataAssetGdprLawfulBase.{n}.lawful_base',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['id'],
                            'DataAssetGdprLawfulBase' => [
                                'fields' => ['lawful_base'],
                            ]
                        ]
                    ]
                ],
                'contracts' => [
                    'type' => 'text',
                    'name' => __('Applicable Contracts, Code of Conducts and Privacy Notes'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.contracts',
                        'field' => 'DataAsset.id',
                    ],
                    'field' => 'DataAssetGdpr.contracts',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['contracts'],
                        ]
                    ]
                ],
            ],
            __('Modified') => [
                'stakeholders' => [
                    'type' => 'text',
                    'name' => __('Stakeholders'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.stakeholders',
                        'field' => 'DataAsset.id',
                    ],
                    'field' => 'DataAssetGdpr.stakeholders',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['stakeholders'],
                        ]
                    ]
                ],
                'accuracy' => [
                    'type' => 'text',
                    'name' => __('Right to Restrict'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.accuracy',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.accuracy',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['accuracy'],
                        ]
                    ]
                ],
                'right_to_access' => [
                    'type' => 'text',
                    'name' => __('Right to Access'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.right_to_access',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.right_to_access',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['right_to_access'],
                        ]
                    ]
                ],
                'right_to_rectification' => [
                    'type' => 'text',
                    'name' => __('Right to Rectification'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.right_to_rectification',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.right_to_rectification',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['right_to_rectification'],
                        ]
                    ]
                ],
                'right_to_decision' => [
                    'type' => 'text',
                    'name' => __('Right to Decision'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.right_to_decision',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.right_to_decision',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['right_to_decision'],
                        ]
                    ]
                ],
                'right_to_object' => [
                    'type' => 'text',
                    'name' => __('Right to Object'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.right_to_object',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.right_to_object',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['right_to_object'],
                        ]
                    ]
                ],
            ],
            __('Stored') => [
                'retention' => [
                    'type' => 'text',
                    'name' => __('Retention'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.retention',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.retention',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['retention'],
                        ]
                    ]
                ],
                'encryption' => [
                    'type' => 'text',
                    'name' => __('Encryption'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.encryption',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.encryption',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['encryption'],
                        ]
                    ]
                ],
                'right_to_erasure' => [
                    'type' => 'text',
                    'name' => __('Right to Erasure'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.right_to_erasure',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.right_to_erasure',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['right_to_erasure'],
                        ]
                    ]
                ],
                'data_asset_gdpr_archiving_driver' => [
                    'type' => 'multiple_select',
                    'name' => __('Archiving Drivers'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdprArchivingDriver.archiving_driver',
                        'field' => 'DataAsset.id',
                        'path' => [
                            'DataAssetGdpr' => [
                                'findField' => 'DataAssetGdpr.id',
                                'field' => 'DataAssetGdpr.data_asset_id',
                            ],
                            'DataAssetGdprArchivingDriver' => [
                                'findField' => 'DataAssetGdprArchivingDriver.archiving_driver',
                                'field' => 'DataAssetGdprArchivingDriver.data_asset_gdpr_id',
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'archivingDrivers',
                        'result_key' => true
                    ],
                    'many' => true,
                    'outputFilter' => array('DataAssetGdpr', 'outputArchivingDriver'),
                    'field' => 'all',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['id', 'archiving_driver_empty'],
                            'DataAssetGdprArchivingDriver'
                        ]
                    ]
                ],
            ],
            __('Transit') => [
                'origin' => [
                    'type' => 'text',
                    'name' => __('Origin'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.origin',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.origin',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['origin'],
                        ]
                    ]
                ],
                'destination' => [
                    'type' => 'text',
                    'name' => __('Destination'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.destination',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.destination',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['destination'],
                        ]
                    ]
                ],
                'transfer_outside_eea' => [
                    'type' => 'select',
                    'name' => __('Data Transfers outside the EEA'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.transfer_outside_eea',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'data' => [
                        'method' => 'getStatusFilterOption',
                        'result_key' => true
                    ],
                    'field' => 'DataAssetGdpr.transfer_outside_eea',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['transfer_outside_eea'],
                        ]
                    ]
                ],
                'third_party_involved' => [
                    'type' => 'multiple_select',
                    'name' => __('Third Party Countries Involved'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'Country.country_id',
                        'field' => 'DataAsset.id',
                        'path' => [
                            'DataAssetGdpr' => [
                                'findField' => 'DataAssetGdpr.id',
                                'field' => 'DataAssetGdpr.data_asset_id',
                            ],
                            'Country' => [
                                'findField' => 'Country.country_id',
                                'field' => 'Country.foreign_key',
                                'conditions' => [
                                    'Country.type' => Country::TYPE_DATA_ASSET_GDPR_THIRD_PARTY_INVOLVED
                                ]
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'getCountries',
                    ],
                    'outputFilter' => array('DataAssetGdpr', 'outputThirdPartyInvolved'),
                    'field' => 'all',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['id', 'third_party_involved_all'],
                            'ThirdPartyInvolved' => [
                                'fields' => ['id', 'country_id'],
                            ]
                        ]
                    ]
                ],
                'data_asset_gdpr_third_party_country' => [
                    'type' => 'multiple_select',
                    'name' => __('Lawful Base for Transfers outside EEA'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdprThirdPartyCountry.third_party_country',
                        'field' => 'DataAsset.id',
                        'path' => [
                            'DataAssetGdpr' => [
                                'findField' => 'DataAssetGdpr.id',
                                'field' => 'DataAssetGdpr.data_asset_id',
                            ],
                            'DataAssetGdprThirdPartyCountry' => [
                                'findField' => 'DataAssetGdprThirdPartyCountry.third_party_country',
                                'field' => 'DataAssetGdprThirdPartyCountry.data_asset_gdpr_id',
                            ],
                        ],
                        'comparisonTypes' => [
                            AbstractQuery::COMPARISON_IN,
                            AbstractQuery::COMPARISON_NOT_IN,
                            AbstractQuery::COMPARISON_IS_NULL,
                        ]
                    ],
                    'data' => [
                        'method' => 'thirdPartyCountries',
                        'result_key' => true
                    ],
                    'many' => true,
                    'field' => 'DataAssetGdpr.DataAssetGdprThirdPartyCountry.{n}.third_party_country',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['id'],
                            'DataAssetGdprThirdPartyCountry' => [
                                'fields' => ['third_party_country'],
                            ]
                        ]
                    ]
                ],
                'security' => [
                    'type' => 'text',
                    'name' => __('Security'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.security',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.security',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['security'],
                        ]
                    ]
                ],
                'right_to_portability' => [
                    'type' => 'text',
                    'name' => __('Right to Data Portability'),
                    'filter' => [
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'DataAssetGdpr.right_to_portability',
                        'field' => 'DataAssetGdpr.id',
                    ],
                    'field' => 'DataAssetGdpr.right_to_portability',
                    'containable' => [
                        'DataAssetGdpr' => [
                            'fields' => ['right_to_portability'],
                        ]
                    ]
                ],
            ],
        ];

        $this->advancedFilterSettings = [
            'pdf_title' => __('Data Asset Flows'),
            'pdf_file_name' => __('data_asset_flows'),
            'csv_file_name' => __('data_asset_flows'),
            'max_selection_size' => 20,
            'bulk_actions' => true,
            'url' => [
                'controller' => 'dataAssets',
                'action' => 'index',
                '?' => [
                    'advanced_filter' => 1
                ]
            ],
            'reset' => [
                'controller' => 'dataAssetInstances',
                'action' => 'index',
            ],
            'history' => true,
            'trash' => [
                'controller' => 'dataAssets',
                'action' => 'trash',
                '?' => [
                    'advanced_filter' => 1
                ]
            ],
            'use_new_filters' => true,
            'scrollable_tabs' => true
        ];

		parent::__construct($id, $table, $ds);
	}

    public function getObjectStatusConfig() {
        return [
            'asset_missing_review' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'controls_with_issues' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'controls_with_failed_audits' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'controls_with_missing_audits' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'policies_with_missing_reviews' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'risks_with_missing_reviews' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'project_expired' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'expired_tasks' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'incomplete_analysis' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
            'incomplete_gdpr_analysis' => [
                'trigger' => [
                    $this->DataAssetInstance
                ]
            ],
        ];
    }

    public function parentModel() {
        return 'DataAssetInstance';
    }

    public function parentNode() {
        return $this->visualisationParentNode('data_asset_instance_id');
    }

	public function enableGdprValidation() {
		$this->validate = am($this->validate, $this->gdprValidate);
	}

    public function beforeValidate($options = array()) {
        $ret = true;

        $gdprEnabled = (isset($this->data['DataAsset']['data_asset_instance_id']) && $this->isGdprEnabled($this->data['DataAsset']['data_asset_instance_id'])) ? true : false;

        // if ($gdprEnabled) {
        //     $ret &= $this->validateMultipleFields(['Risk', 'ThirdPartyRisk', 'BusinessContinuity'], __('Please choose at least one Asset Risk, Third Party Risk or Business Continuity.'), true);
        // }
        
        return true;
    }

    public function beforeSave($options = []) {
        $this->transformDataToHabtm(['SecurityService', 'BusinessUnit', 'ThirdParty', 'Project',
            'Risk', 'ThirdPartyRisk', 'BusinessContinuity', 'SecurityPolicy'
        ]);

        $this->setHabtmConditionsToData(['Risk', 'ThirdPartyRisk', 'BusinessContinuity']);

        return true;
    }

	public function afterSave($created, $options = []) {
		if (!empty($this->id)) {
            $dataAssetInstanceId = $this->field('data_asset_instance_id', [
                'DataAsset.id' => $this->id
            ]);

			$this->updateOrder($this->id, $dataAssetInstanceId);
		}
    }

    private function updateOrder($id, $dataAssetInstanceId = null) {
        if ($dataAssetInstanceId === null) {
            $dataAssetInstanceId = $this->field('data_asset_instance_id', [
                'DataAsset.id' => $id
            ]);
        }

    	$dataAssets = $this->find('all', [
    		'conditions' => [
    			'DataAsset.data_asset_instance_id' => $dataAssetInstanceId 
    		],
    		'order' => [
    			'DataAsset.order' => 'ASC',
    			'DataAsset.modified' => 'DESC'
    		],
    		'recursive' => -1
    	]);

    	foreach ($dataAssets as $key => $item) {
    		$this->updateAll(['order' => $key], [
    			'DataAsset.id' => $item['DataAsset']['id']
			]);
    	}
    }

    public function getAssets() {
        return $this->DataAssetInstance->Asset->getList();
    }

    public function getBusinessUnits() {
        return $this->BusinessUnit->getList();
    }

    public function getThirdParties() {
        return $this->ThirdParty->getList();
    }

    public function getRisks() {
        return $this->Risk->getList();
    }

    public function getThirdPartyRisks() {
        return $this->ThirdPartyRisk->getList();
    }

    public function getBusinessContinuities() {
        return $this->BusinessContinuity->getList();
    }

    public function getSecurityServices() {
        return $this->SecurityService->getList();
    }

    public function getSecurityPolicies() {
        return $this->SecurityPolicy->getListWithType();
    }

    public function getProjects() {
        return $this->Project->getList();
    }

    public function getEuropeCountries() {
        return Country::europeCountries();
    }

    public function getCountries() {
        return Country::countries();
    }

    public static function dataTypes($value = null) {
        return DataAssetGdprDataType::dataTypes($value);
    }

    public static function collectionMethods($value = null) {
        return DataAssetGdprCollectionMethod::collectionMethods($value);
    }

    public static function lawfulBases($value = null) {
        return DataAssetGdprLawfulBase::lawfulBases($value);
    }

    public static function thirdPartyCountries($value = null) {
        return DataAssetGdprThirdPartyCountry::thirdPartyCountries($value);
    }

    public static function archivingDrivers($value = null) {
        return DataAssetGdprArchivingDriver::archivingDrivers($value);
    }

    public function getUsers() {
        $User = ClassRegistry::init('User');

        $User->virtualFields['full_name'] = 'CONCAT(User.name, " ", User.surname)';
        $users = $User->find('list', [
            'conditions' => [],
            'fields' => ['User.id', 'User.full_name'],
        ]);

        return $users;
    }

    public function isGdprEnabled($dataAssetInstanceId) {
        $result = $this->DataAssetInstance->DataAssetSetting->field('gdpr_enabled', [
            'DataAssetSetting.data_asset_instance_id' => $dataAssetInstanceId
        ]);

        return !empty($result) ? true : false;
    }
}
