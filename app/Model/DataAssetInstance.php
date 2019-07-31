<?php
App::uses('SectionBase', 'Model');
App::uses('DataAsset', 'Model');
App::uses('DataAssetSetting', 'Model');
App::uses('Hash', 'Utility');
App::uses('InheritanceInterface', 'Model/Interface');

class DataAssetInstance extends SectionBase implements InheritanceInterface {
    public $displayField = null;

    public $actsAs = [
        'Containable',
        'Search.Searchable',
        'ObjectStatus.ObjectStatus',
        'Visualisation.Visualisation',
    ];

    public $validate = [
    ];

    public $belongsTo = [
        'Asset'
    ];

    public $hasOne = [
        'DataAssetSetting'
    ];

    public $hasMany = [
        'DataAsset',
        'Attachment' => [
            'className' => 'Attachment',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'Attachment.model' => 'DataAssetSetting'
            ]
        ],
        'Comment' => [
            'className' => 'Comment',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'Comment.model' => 'DataAssetSetting'
            ]
        ],
        'SystemRecord' => [
            'className' => 'SystemRecord',
            'foreignKey' => 'foreign_key',
            'conditions' => [
                'SystemRecord.model' => 'DataAssetSetting'
            ]
        ],
    ];

    public $hasAndBelongsToMany = [
    ];

    /*
     * static enum: Model::function()
     * @access static
     */
    public static function analysisStatuses($value = null) {
        $options = [
            self::ANALYSIS_STATUS_LOCKED => __('Locked'),
            self::ANALYSIS_STATUS_UNLOCKED => __('Unlocked'),
        ];
        return parent::enum($value, $options);
    }

    const ANALYSIS_STATUS_LOCKED = 0;
    const ANALYSIS_STATUS_UNLOCKED = 1;

    public function __construct($id = false, $table = null, $ds = null) {
        $this->label = __('Data Asset Instances');
        $this->_group = parent::SECTION_GROUP_ASSET_MGT;

        $this->fieldGroupData = [
            'default' => [
                'label' => __('General')
            ],
        ];

        $this->fieldData = [
            'asset_id' => [
                'label' => __('Asset'),
                'editable' => false,
            ],
            'analysis_unlocked' => [
                'label' => __('Analysis Unlocked'),
                'editable' => false,
                'options' => [$this, 'analysisStatuses']
            ],
        ];

        $this->filterArgs = array(
            'id' => array(
                'type' => 'value',
                '_name' => __('ID')
            ),
        );

        parent::__construct($id, $table, $ds);
    }

    public function parentModel() {
        return 'Asset';
    }

    public function parentNode() {
        return $this->visualisationParentNode('asset_id');
    }

    public function getItem($id) {
        $data = $this->find('first', [
            'conditions' => [
                $this->alias . '.id' => $id
            ],
            'recursive' => 1
        ]);

        if (empty($data)) {
            throw new NotFoundException();
        }

        return $data;
    }

    public function unlockAnalysis($id) {
        $ret = $this->updateAll(['analysis_unlocked' => self::ANALYSIS_STATUS_UNLOCKED], [
            'DataAssetInstance.id' => $id
        ]);

        return $ret;
    }

    public function getObjectStatusConfig() {
        return [
            'asset_missing_review' => [//inherited
                'title' => __('Asset Missing Review'),
                'inherited' => [
                    'Asset' => 'expired_reviews'
                ],
            ],
            'controls_with_issues' => [//inherited
                'title' => __('Controls with Issues'),
                'inherited' => [
                    'DataAsset.SecurityService' => 'control_with_issues'
                ],
                'type' => 'danger',
            ],
            'controls_with_failed_audits' => [//inherited
                'title' => __('Controls with failed Audits'),
                'inherited' => [
                    'DataAsset.SecurityService' => 'audits_last_not_passed'
                ],
                'type' => 'danger'
            ],
            'controls_with_missing_audits' => [//inherited
                'title' => __('Controls with missing Audits'),
                'inherited' => [
                    'DataAsset.SecurityService' => 'audits_last_missing'
                ],
            ],
            'policies_with_missing_reviews' => [//inherited
                'title' => __('Policies with Missing Reviews'),
                'inherited' => [
                    'DataAsset.SecurityPolicy' => 'expired_reviews'
                ],
            ],
            'risks_with_missing_reviews' => [//inherited
                'title' => __('Risks with Missing Reviews'),
                'inherited' => [
                    'DataAsset.Risk' => 'expired_reviews',
                    'DataAsset.ThirdPartyRisk' => 'expired_reviews',
                    'DataAsset.BusinessContinuity' => 'expired_reviews',
                ],
            ],
            'project_expired' => [//inherited
                'title' => __('Project Expired'),
                'inherited' => [
                    'DataAsset.Project' => 'expired'
                ],
            ],
            'expired_tasks' => [//inherited
                'title' => __('Expired Tasks'),
                'inherited' => [
                    'DataAsset.Project' => 'expired_tasks'
                ],
            ],
            'incomplete_analysis' => [
                'title' => __('Incomplete Analysis'),
                'callback' => [$this, 'statusIncompleteAnalysis'],
            ],
            'incomplete_gdpr_analysis' => [
                'title' => __('Incomplete GDPR Analysis'),
                'callback' => [$this, 'statusIncompleteGdprAnalysis'],
            ],
        ];
    }

    public function statusIncompleteAnalysis() {
        $data = $this->find('count', [
            'conditions' => [
                'DataAssetInstance.id' => $this->id
            ],
            'joins' => [
                [
                    'table' => 'data_asset_settings',
                    'alias' => 'DataAssetSetting',
                    'type' => 'INNER',
                    'conditions' => [
                        'DataAssetSetting.data_asset_instance_id = DataAssetInstance.id',
                        'DataAssetSetting.gdpr_enabled' => DataAssetSetting::GDPR_DISABLED,
                    ]
                ],
                [
                    'table' => 'data_assets',
                    'alias' => 'DataAsset',
                    'type' => 'LEFT',
                    'conditions' => [
                        'DataAsset.deleted = 0',
                        'DataAsset.data_asset_instance_id = DataAssetInstance.id',
                    ]
                ],
            ],
            'group' => [
                'DataAsset.data_asset_status_id'
            ],
            'recursive' => -1
        ]);

        return (boolean) (is_numeric($data) && $data != count(DataAsset::statuses()));
    }

    public function statusIncompleteGdprAnalysis() {
        $data = $this->find('count', [
            'conditions' => [
                'DataAssetInstance.id' => $this->id
            ],
            'joins' => [
                [
                    'table' => 'data_asset_settings',
                    'alias' => 'DataAssetSetting',
                    'type' => 'INNER',
                    'conditions' => [
                        'DataAssetSetting.data_asset_instance_id = DataAssetInstance.id',
                        'DataAssetSetting.gdpr_enabled' => DataAssetSetting::GDPR_ENABLED,
                    ]
                ],
                [
                    'table' => 'data_assets',
                    'alias' => 'DataAsset',
                    'type' => 'LEFT',
                    'conditions' => [
                        'DataAsset.deleted = 0',
                        'DataAsset.data_asset_instance_id = DataAssetInstance.id',
                    ]
                ],
            ],
            'group' => [
                'DataAsset.data_asset_status_id'
            ],
            'recursive' => -1
        ]);

        return (boolean) (is_numeric($data) && $data != count(DataAsset::statuses()));
    }

}
