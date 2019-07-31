<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');
App::uses('AppIndexCrudAction', 'Controller/Crud/Action');

class Asset extends SectionBase {
	public $displayField = 'name';
	
	public $findMethods = array('self' =>  true);

	private $reviewAfterSave = false;

	public $workflow = array(
		// 'additionalField' => array(
		// 	'owner' => array(
		// 		'type' => 'single',
		// 		'model' => 'Asset',
		// 		'column' => 'asset_owner_id',
		// 		'label' => 'Owner',
		// 		'pullObjectData' => 'BusinessUnit'
		// 	)
		// )
	);

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Search.Searchable',
		'AuditLog.Auditable' => array(
			'ignore' => array(
				'security_incident_open_count',
				'created',
				'modified',
				'Risk',
				'ThirdPartyRisk',
				'SecurityIncident'
			)
		),
		'Utils.SoftDelete',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description', 'asset_label_id', 'asset_media_type_id', 'asset_owner_id', 'asset_guardian_id', 'asset_user_id', 'review'
			)
		),
		'CustomFields.CustomFields',
		'Visualisation.Visualisation',
		'ReviewsPlanner.Reviews' => [
			'dateColumn' => 'review' 
		],
		'CustomRoles.CustomRoles',
		'ObjectStatus.ObjectStatus'
		// 'CustomRoles.CustomRoles' => [
		// 	'roles' => [
		// 		CustomRolesRole::ROLE_OWNER => ['asset_owner_id']
		// 	]
		// ]
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'asset_owner_id' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field is required'
			),
			'notEveryone' => array(
				'rule' => array('comparison', '!=', 1),
				'message' => 'Choose specific Business Units only, Everyone (ID 1) is not allowed'
			)
		),
		'asset_guardian_id' => array(
			'notEveryone' => array(
				'rule' => array('comparison', '!=', 1),
				'message' => 'Choose specific Business Units only, Everyone (ID 1) is not allowed'
			)
		),
		'asset_media_type_id' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		'review' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field is required'
			),
			'date' => array(
				'rule' => 'date',
				'required' => true,
				'message' => 'This date has incorrect format'
			)
		),
		'BusinessUnit' => array(
			'rule' => array( 'multiple', array( 'min' => 1 ))
		)
	);

	public $belongsTo = array(
		'AssetMediaType',
		'AssetLabel',
		'AssetOwner' => array(
			'className' => 'BusinessUnit',
			'foreignKey' => 'asset_owner_id'
		),
		'AssetGuardian' => array(
			'className' => 'BusinessUnit',
			'foreignKey' => 'asset_guardian_id'
		),
		'AssetUser' => array(
			'className' => 'BusinessUnit',
			'foreignKey' => 'asset_user_id'
		),
		/*'AssetMainContainer' => array(
			'className' => 'Asset',
			'foreignKey' => 'asset_id'
		)*/
	);

	public $hasOne = array(
		'DataAssetInstance'
	);

	public $hasMany = array(
		'Review' => array(
			'className' => 'Review',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Review.model' => 'Asset'
			)
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'Asset'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'Asset'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'Asset'
			)
		),
		// 'WorkflowAcknowledgement' => array(
		// 	'className' => 'WorkflowAcknowledgement',
		// 	'foreignKey' => 'foreign_key',
		// 	'conditions' => array(
		// 		'WorkflowAcknowledgement.model' => 'Asset'
		// 	)
		// )
	);

	public $hasAndBelongsToMany = array(
		'BusinessUnit',
		'Legal',
		'AssetClassification',
		'Risk',
		'ThirdPartyRisk',
		'SecurityIncident',
		'RelatedAssets' => array(
			'className' => 'Asset',
			'with' => 'AssetsRelated',
			'joinTable' => 'assets_related',
			'foreignKey' => 'asset_id',
			'associationForeignKey' => 'asset_related_id'
		),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Asset Identification');
		$this->_group = parent::SECTION_GROUP_ASSET_MGT;

		$this->fieldGroupData = array(
			'default' => array(
				'label' => __('General')
			),
			'asset-owner' => array(
				'label' => __('Asset Owner')
			),
			'asset-classification' => array(
				'label' => __('Asset Classification')
			)
		);

		$this->fieldData = array(
			'BusinessUnit' => array(
				'label' => __('Related Business Units'),
				// 'options' => array($this, 'getBusinessUnits'),
				'editable' => true,
				'description' => __( 'One or more Business Units that are mostly associated with this asset, this could be the BU that use or build the asset.' ),
			),
			'name' => array(
				'label' => __('Name'),
				'editable' => true,
				'description' => __('Give a name to the asset you have identified.')
			),
			'description' => array(
				'label' => __('Description'),
				'editable' => true,
				'description' => __('Give a brief description of what the asset is.')
			),
			'asset_label_id' => array(
				'label' => __('Label'),
				// 'options' => array($this, 'getLabels'),
				'editable' => true,
				'description' => __('Labels refer to the type of asset being created, typical examples are Confidential, Private, Etc. Labels are defined at Asset Management/Settings/Labels'),
				'empty' => __('Choose one'),
			),
			'asset_media_type_id' => array(
				'label' => __('Type'),
				// 'options' => array($this, 'getMediaTypes'),
				'editable' => true,
				'description' => __('Based on the type of asset eramba will suggest you possible threats and vulnerabilities (at the time you perform Risk Management). If you are also interested in Data Asset Flows (Asset Management / Data Flows) you need to select "Data Asset"')
			),
			'RelatedAssets' => array(
				'label' => __('Assets'),
				// 'options' => array($this, 'getAssets'),
				'editable' => true,
				'description' => __('OPTIONAL: Many times assets relate to other assets in the scope of a Risk. A datacenter holds systems and those systems hold applications that manage key data. By selecting in here one or more related asssets you set those relationships that be later taken in consideration when performing Risk Management (you would only select "Datacenter" as the input asset  for a risk and not every server or application). This is only applicable if using "Magerit" type of risk calculation.')
			),
			'Legal' => array(
				'label' => __('Liabilities'),
				// 'options' => array($this, 'getLegals'),
				'editable' => true,
				'description' => __('OPTIONAL: One or more liabilities that are asociated with this asset. This is a rather important field as liabilites associated to this asset will increase or decrease risks scores asociated with it.')
			),
			'review' => array(
				'label' => __('New Review Date'),
				'editable' => false,
				'description' =>  __('Select a date when this asset should be reviewed. With the objective of ensuring the data on the system is relevant, eramba will warn you when a review is due or when a review is missing.')
			),
			'asset_owner_id' => array(
				'label' => __('Owner'),
				'group' => 'asset-owner',
				// 'options' => array($this, 'getOwners'),
				'editable' => true,
				'description' => __('Select from the list of business units, which one owns the asset, this will not affect the way the asset is used under risk management and is purely a way for you to track ownership of an asset.'),
				'empty' => __('Select an Owner')
			),
			'asset_guardian_id' => array(
				'label' => __('Guardian'),
				'group' => 'asset-owner',
				// 'options' => array($this, 'getOwners'),
				'editable' => true,
				'description' => __('OPTIONAL: Select from the list of business units, which one is in charge of maintaining the asset. This will not affect the way the asset is used under risk management and is purely a way for you to track ownership of an asset.'),
				'empty' => __('Select a Guardian')
			),
			'asset_user_id' => array(
				'label' => __('User'),
				'group' => 'asset-owner',
				// 'options' => array($this, 'getOwners'),
				'editable' => true,
				'description' => __('OPTIONAL: Select from the list of business unit, which one is using the asset. You can optionally choose "Everyone". This will not affect the way the asset is used under risk management and is purely a way for you to track ownership of an asset.'),
				'empty' => __('Everyone')
			),
			'AssetClassification' => array(
				'type' => 'select',
				'label' => __('Classification'),
				'group' => 'asset-classification',
				'options' => array($this, 'getClassifications'),
				'editable' => true,
				'empty' => __('Choose Classification'),
                'renderHelper' => 'AssetClassificationField',
                'Extensions' => [
                    'AssetClassification'
                ]
			),
			'expired_reviews' => array(
				'label' => __('Expired Reviews'),
				'toggle' => true,
				'hidden' => true
			)
		);

		$this->notificationSystem = array(
			'macros' => array(
				'ASSET_ID' => array(
					'field' => 'Asset.id',
					'name' => __('Asset ID')
				),
				'ASSET_NAME' => array(
					'field' => 'Asset.name',
					'name' => __('Asset Name')
				),
				'ASSET_DESCRIPTION' => array(
					'field' => 'Asset.description',
					'name' => __('Asset Description')
				),
				'ASSET_REVIEW_DATE' => array(
					'field' => 'Asset.review',
					'name' => __('Asset Review')
				)
			),
			'customEmail' =>  true
		);

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'show_default' => true,
					'filter' => false
				),
				'business_unit_id' => array(
					'type' => 'multiple_select',
					'name' => __('Related Business Units'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'BusinessUnit.id',
						'field' => 'Asset.id',
					),
					'data' => array(
						'method' => 'getBusinessUnits',
					),
					'many' => true,
					'contain' => array(
						'BusinessUnit' => array(
							'name'
						)
					),
				),
				'name' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Asset.name',
						'field' => 'Asset.id',
					)
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Asset.description',
						'field' => 'Asset.id',
					)
				),
				'asset_label_id' => array(//error
					'type' => 'select',
					'name' => __('Label'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'AssetLabel.id',
						'field' => 'Asset.asset_label_id',
					),
					'data' => array(
						'method' => 'getLabels',
					),
					'contain' => array(
						'AssetLabel' => array(
							'name'
						)
					),
				),
				'asset_media_type_id' => array(//error
					'type' => 'select',
					'name' => __('Type'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'AssetMediaType.id',
						'field' => 'Asset.asset_media_type_id',
					),
					'data' => array(
						'method' => 'getMediaTypes',
					),
					'contain' => array(
						'AssetMediaType' => array(
							'name'
						)
					),
				),
				'legal_id' => array(
					'type' => 'multiple_select',
					'name' => __('Liabilities'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Legal.id',
						'field' => 'Asset.id',
					),
					'data' => array(
						'method' => 'getLegals',
					),
					'many' => true,
					'contain' => array(
						'Legal' => array(
							'name'
						)
					),
				),
				'review' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Review'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Asset.review',
						'field' => 'Asset.id',
					),
				),
			),
			__('Owner') => array(
				'asset_owner_id' => array(
					'type' => 'multiple_select',
					'name' => __('Owner'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'AssetOwner.id',
						'field' => 'Asset.asset_owner_id',
					),
					'data' => array(
						'method' => 'getOwners',
					),
					'contain' => array(
						'AssetOwner' => array(
							'name'
						)
					),
				),
				'asset_guardian_id' => array(
					'type' => 'multiple_select',
					'name' => __('Guardian'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'AssetGuardian.id',
						'field' => 'Asset.asset_guardian_id',
					),
					'data' => array(
						'method' => 'getOwners',
					),
					'contain' => array(
						'AssetGuardian' => array(
							'name'
						)
					),
				),
				'asset_user_id' => array(
					'type' => 'multiple_select',
					'name' => __('User'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'AssetUser.id',
						'field' => 'Asset.asset_user_id',
					),
					'data' => array(
						'method' => 'getOwners',
					),
					'contain' => array(
						'AssetUser' => array(
							'name'
						)
					),
				),
			),
			__('Asset Classification') => array(
				'asset_classification_id' => array(
					'type' => 'multiple_select',
					'name' => __('Classification'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'AssetClassification.id',
						'field' => 'Asset.id',
					),
					'data' => array(
						'method' => 'getClassifications',
						'result_key' => true
					),
					'many' => true,
					'contain' => array(
						'AssetClassification' => array(
							'id'
						)
					),
				),
			),
			__('Status') => array(
				'expired_reviews' => array(
					'type' => 'select',
					'name' => __('Expired Reviews'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Asset.expired_reviews',
						'field' => 'Asset.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
			),
			__('Related Status') => array(
				'security_incident_ongoing_incident' => array(
					'type' => 'select',
					'name' => __('Ongoing Incident'),
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'SecurityIncident.ongoing_incident',
						'field' => 'Asset.id',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
					'field' => 'SecurityIncident.{n}.ongoing_incident',
					'containable' => array(
						'SecurityIncident' => array(
							'fields' => array('ongoing_incident', 'security_incident_status_id')
						)
					)
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Assets'),
			'pdf_file_name' => __('assets'),
			'csv_file_name' => __('assets'),
			'max_selection_size' => 10,
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
            'view_item' => AppIndexCrudAction::VIEW_ITEM_QUERY,
			'additional_actions' => array(
				'AssetReview' => array(
					'label' => __('Reviews'),
					'url' => array(
						'controller' => 'reviews',
						'action' => 'filterIndex',
						'AssetReview',
						'?' => array(
							'advanced_filter' => 1
						)
					)
				),
			),
			'use_new_filters' => true,
			'add' => true,
		);

		parent::__construct($id, $table, $ds);

		// $this->filterArgs = array(
		// 	'search' => array(
		// 		'type' => 'like',
		// 		'field' => array('Asset.name', 'Asset.description'),
		// 		'_name' => __('Search')
		// 	),
		// 	'asset_owner_id' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Owner')
		// 	),
		// 	'asset_guardian_id' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Guardian')
		// 	),
		// 	'asset_user_id' => array(
		// 		'type' => 'value',
		// 		'_name' => __('User')
		// 	),
		// 	'expired_reviews' => array(
		// 		'type' => 'value',
		// 		'_name' => __('Missing Asset Review')
		// 	)
		// );

		$expiredReviews = $this->getStatusConfig('expiredReviews', 'name');
		$expiredReviews['migrateRecords'] = array('Risk');

		$this->mapping['statusManager'] = array(
			'expiredReviews' => $expiredReviews
		);

		$this->importArgs = array(
			'Asset.BusinessUnit' => array(
				'name' => __('Related Business Units'),
				'model' => 'BusinessUnit',
				'headerTooltip' => __('This field is mandatory, you need to input one or more Business Units IDs. You can get the ID of a business unit from Organisation / Business Units - eramba will check if those IDs exist and if they dont, block the import - If you want to include more than one, remember to split them with "|". For example 1|2|3 would include three business units.')
			),
			'Asset.name' => array(
				'name' => __('Name'),
				'headerTooltip' => __('This field is mandatory, simply put the asset name. For example: Corporate Laptops.')
			),
			'Asset.description' => array(
				'name' => __('Description'),
				'headerTooltip' => __('This field is not mandatory, you can leave it blank if you want.')
			),
			'Asset.asset_label_id' => array(
				'name' => __('Label'),
				'model' => 'AssetLabel',
				'headerTooltip' => __('This field is not mandatory, you can leave it blank. Alternatively you can include the Asset Label ID which you can get from Asset Management / Asset Identification / Settings / Labels.')
			),
			'Asset.asset_media_type_id' => array(
				'name' => __('Type'),
				'model' => 'AssetMediaType',
				'headerTooltip' => __(
					'This field is mandatory, you need to set the type of asset by inserting one of the following IDs: %s',
					ImportToolModule::formatList($this->getMediaTypes())
				)
			),
			'Asset.Legal' => array(
				'name' => __('Liabilities'),
				'model' => 'Legal',
				'headerTooltip' => __('This is an optional field, you can leave it blank if you want to, alternatively you need to insert the ID of one or more liabilities. You can get the ID for each existing liability at Organisation  / Legal Constrains. Eramba will check each liability exists! If you want to include more than one, remember to split them with a "|" character. For example: 1|2.')
			),
			'Asset.review' => array(
				'name' => __('Review'),
				'headerTooltip' => __('This is a mandatory field, it must have the format YYYY-MM-DD - bare In ind the "-" delimiter.')
			),
			'Asset.asset_owner_id' => array(
				'name' => __('Owner'),
				'model' => 'AssetOwner',
				'headerTooltip' => __('This field is mandatory, you need to input one Business Units ID. You can get the ID of a business unit from Organisation / Business Units - eramba will check if those IDs exist and if they dont, block the import.')
			),
			'Asset.asset_guardian_id' => array(
				'name' => __('Guardian'),
				'model' => 'AssetGuardian',
				'headerTooltip' => __('This field is optional, you may leave it blank. You can get the ID of a business unit from Organisation / Business Units - eramba will check if those IDs exist and if they dont, block the import.')
			),
			'Asset.asset_user_id' => array(
				'name' => __('User'),
				'model' => 'AssetUser',
				'headerTooltip' => __('This field is optional, you may leave it blank. You can get the ID of a business unit from Organisation / Business Units - eramba will check if those IDs exist and if they dont, block the import. This field can be also set as "Everyone" with the value: 1')
			)
		);
	}

	public function getObjectStatusConfig() {
        return [
            'expired_reviews' => [
            	'title' => __('Missing Review'),
                'callback' => [$this, '_statusExpiredReviews'],
                'trigger' => [
                	[
                        'model' => $this->DataAssetInstance,
                        'trigger' => 'ObjectStatus.trigger.asset_missing_review'
                    ],
                ]
            ],
            'ongoing_incident' => [
            	'title' => __('Ongoing Incident'),
                'inherited' => [
                	'SecurityIncident' => 'ongoing_incident'
            	],
            	'storageSelf' => false
            ],
        ];
    }

	public function notEveryone($check) {
        $value = array_values($check);
        $value = $value[0];
        
       	if (in_array(BU_EVERYONE, $value)) {
       		return false;
       	}

        return true;
    }

	public function beforeValidate($options = array()) {
		if (isset($this->data['Asset']['BusinessUnit'])) {
			$this->invalidateRelatedNotExist('BusinessUnit', 'BusinessUnit', $this->data['Asset']['BusinessUnit']);
		}

		if (isset($this->data['Asset']['Legal'])) {
			$this->invalidateRelatedNotExist('Legal', 'Legal', $this->data['Asset']['Legal']);
		}

		if (isset($this->data['Asset']['asset_media_type_id'])) {
			$this->invalidateRelatedNotExist('AssetMediaType', 'asset_media_type_id', $this->data['Asset']['asset_media_type_id']);
		}

		if (isset($this->data['Asset']['asset_label_id'])) {
			$this->invalidateRelatedNotExist('AssetLabel', 'asset_label_id', $this->data['Asset']['asset_label_id']);
		}

		if (isset($this->data['Asset']['asset_owner_id'])) {
			$this->invalidateRelatedNotExist('AssetOwner', 'asset_owner_id', $this->data['Asset']['asset_owner_id']);
		}

		if (isset($this->data['Asset']['asset_guardian_id'])) {
			$this->invalidateRelatedNotExist('AssetGuardian', 'asset_guardian_id', $this->data['Asset']['asset_guardian_id']);
		}

		if (isset($this->data['Asset']['asset_user_id'])) {
			$this->invalidateRelatedNotExist('AssetUser', 'asset_user_id', $this->data['Asset']['asset_user_id'], [
				'AssetUser._hidden' => 1
			]);
		}
	}

	public function printMediaTypes(&$item, $key) {
		$item = sprintf("\n%s: %s", $key, $item);
	}

	public function beforeSave($options = array()) {
		// $ret = $this->createReview();

		$this->transformDataToHabtm(array('Legal', 'BusinessUnit', 'RelatedAssets', 'AssetClassification'));

		// if (!empty($this->id)) {
		// 	$this->beforeItemSave($this->id);
		// }

		return true;
	}

	public function afterSave($created, $options = array()) {
		if ($this->reviewAfterSave && !empty($this->data['Asset']['review'])) {
			$this->saveReviewRecord($this->data['Asset']['review']);
		}

		if ($created === false) {
			$this->setRisksData();
			$this->updateRiskScores();
		}

		$this->createDataAssetInstance($this->id);

		// if (!empty($this->id)) {
		// 	$this->afterItemSave($this->id);
		// }
	}

	/**
	 * Create new DataAssetInstance for input $assetId.
	 * 
	 * @param  int $assetId
	 * @return boolean Success.
	 */
	public function createDataAssetInstance($assetId) {
		$ret = true;

		$asset = $this->find('count', [
			'conditions' => [
				'Asset.id' => $assetId,
				'Asset.asset_media_type_id' => ASSET_MEDIA_TYPE_DATA
			],
			'recursive' => -1
		]);

		if (empty($asset)) {
			$this->DataAssetInstance->deleteAll(['asset_id' => $assetId]);
			return $ret;
		}

		$instance = $this->DataAssetInstance->find('count', [
			'conditions' => [
				'DataAssetInstance.asset_id' => $assetId
			],
			'recursive' => -1
		]);

		if (empty($instance)) {
			$this->DataAssetInstance->create();
			$ret &= $this->DataAssetInstance->save([
				'asset_id' => $assetId
			]);
		}

		return $ret;
	}

	public function saveJoins($data = null) {
		$this->data = $data;

		$ret = true;

		$ret &= $this->joinHabtm('BusinessUnit', 'business_unit_id');
		$ret &= $this->joinHabtm('AssetClassification', 'asset_classification_id');
		$ret &= $this->joinHabtm('Legal', 'legal_id');
		$ret &= $this->joinHabtm('RelatedAssets', 'related_id');

		$this->data = false;
		
		return $ret;
	}

	public function deleteJoins($id) {
		$ret = $this->AssetsBusinessUnit->deleteAll( array(
			'AssetsBusinessUnit.asset_id' => $id
		) );
		$ret &= $this->AssetClassificationsAsset->deleteAll( array(
			'AssetClassificationsAsset.asset_id' => $id
		) );
		$ret &= $this->AssetsLegal->deleteAll( array(
			'AssetsLegal.asset_id' => $id
		) );
		$ret &= $this->AssetsRelated->deleteAll( array(
			'AssetsRelated.asset_id' => $id
		) );

		return $ret;
	}

	// private function createReview() {
	// 	if (!isset($this->data['Asset']['review'])) {
	// 		return true;
	// 	}

	// 	if (!empty($this->id)) {
	// 		$data = $this->find('first', array(
	// 			'conditions' => array(
	// 				'Asset.id' => $this->id
	// 			),
	// 			'fields' => array('Asset.review'),
	// 			'recursive' => -1
	// 		));

	// 		if ($data['Asset']['review'] == $this->data['Asset']['review']) {
	// 			return true;
	// 		}

	// 		return $this->saveReviewRecord($this->data['Asset']['review']);
	// 	}

	// 	$this->reviewAfterSave = true;
	// 	return true;
	// }

	/**
	 * Save an actual review item.
	 *
	 * @param  string $review Date.
	 */
	// private function saveReviewRecord($review) {
	// 	$user = $this->currentUser();
	// 	$saveData = array(
	// 		'model' => 'Asset',
	// 		'foreign_key' => $this->id,
	// 		'planned_date' => $review,
	// 		'user_id' => $user['id'],
	// 	);

	// 	$this->Review->set($saveData);
	// 	return $this->Review->save(null, false);
	// }

	public function beforeDelete($cascade = true) {
		$ret = $this->deleteUselessRisk();
		$this->setRisksData();

		return $ret;
	}

	public function afterDelete() {
		$this->updateRiskScores();
	}

	private function deleteUselessRisk() {
		$data = $this->AssetsRisk->find('all', array(
			'conditions' => array(
				'AssetsRisk.asset_id' => $this->id
			)
		));

		$ret = true;
		foreach ($data as $risk) {
			$count = $this->AssetsRisk->find('count', array(
				'conditions' => array(
					'AssetsRisk.risk_id' => $risk['AssetsRisk']['risk_id']
				)
			));

			if ($count == 1) {
				$ret &= $this->Risk->delete($risk['AssetsRisk']['risk_id']);
			}
		}

		return $ret;
	}

	public function setRisksData() {
		$data = $this->find('all', array(
			'conditions' => array(
				'Asset.id' => $this->id
			),
			'contain' => array(
				'Risk' => array(
					'fields' => array('id')
				),
				'ThirdPartyRisk' => array(
					'fields' => array('id')
				)
			)
		));

		$this->RiskIds = $this->TpRiskIds = array();
		foreach ($data as $asset) {
			foreach ($asset['Risk'] as $risk) {
				$this->RiskIds[] = $risk['id'];
			}

			foreach ($asset['ThirdPartyRisk'] as $risk) {
				$this->TpRiskIds[] = $risk['id'];
			}
		}
	}

	public function getAssets() {
		$data = $this->find('list', array(
			'conditions' => array(
				'workflow_status' => WORKFLOW_APPROVED
			),
			'order' => array('name' => 'ASC'),
			'recursive' => -1
		));

		return $data;
	}

	public function getBusinessUnits() {
		$data = $this->BusinessUnit->find('list', array(
			'order' => array('BusinessUnit.name' => 'ASC'),
			'fields' => array('BusinessUnit.id', 'BusinessUnit.name'),
			'recursive' => -1
		));

		return $data;
	}

	public function getDataAssets() {
		$data = $this->DataAsset->find('list', array(
			'order' => array('DataAsset.description' => 'ASC'),
			'fields' => array('DataAsset.id', 'DataAsset.description'),
			'recursive' => -1
		));

		return $data;
	}

	public function getLabels() {
		$data = $this->AssetLabel->find('list', array(
			'order' => array('AssetLabel.name' => 'ASC'),
			'fields' => array('AssetLabel.id', 'AssetLabel.name'),
			'recursive' => -1
		));

		return $data;
	}

	public function getMediaTypes() {
		$data = $this->AssetMediaType->find('list', array(
			'order' => array('AssetMediaType.name' => 'ASC'),
			'fields' => array('AssetMediaType.id', 'AssetMediaType.name'),
			'recursive' => -1
		));

		return $data;
	}

	public function getLegals() {
		$data = $this->Legal->find('list', array(
			'order' => array('Legal.name' => 'ASC'),
			'fields' => array('Legal.id', 'Legal.name'),
			'recursive' => -1
		));

		return $data;
	}

	public function getOwners()	{
		$data = $this->AssetOwner->find('list', array(
			'order' => array('AssetOwner.name' => 'ASC'),
			'fields' => array('AssetOwner.id', 'AssetOwner.name'),
			'recursive' => -1
		));

		return $data;
	}

	public function getReviews() {
		$data = $this->Review->find('list', array(
			'conditions' => array(
				'Review.model' => 'Asset'
			),
			'order' => array('Review.planned_date' => 'ASC'),
			'fields' => array('Review.id', 'Review.planned_date'),
			'recursive' => -1
		));
		return $data;
	}

	public function getClassifications() {
		$dataRaw = $this->AssetClassification->find('all', array(
			'order' => array('AssetClassification.name' => 'ASC'),
			'fields' => array('AssetClassification.id', 'AssetClassification.name', 'AssetClassificationType.name'),
			'contain' => array(
				'AssetClassificationType'
			),
		));

		$data = array();
		foreach ($dataRaw as $item) {
			$data[$item['AssetClassification']['id']] = '[' . $item['AssetClassificationType']['name'] . '] - ' . $item['AssetClassification']['name'];
		}

		return $data;
	}

	public function findByReviews($data = array()) {
		$this->Review->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->Review->Behaviors->attach('Search.Searchable');

		$query = $this->Review->getQuery('all', array(
			'conditions' => array(
				'Review.id' => $data['review_id']
			),
			'fields' => array(
				'Review.foreign_key'
			),
			'recursive' => -1
		));

		return $query;
	}

	public function findByBusinessUnits() {
		$this->AssetsBusinessUnit->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->AssetsBusinessUnit->Behaviors->attach('Search.Searchable');

		$query = $this->AssetsBusinessUnit->getQuery('all', array(
			'conditions' => array(
				'AssetsBusinessUnit.business_unit_id' => $data['business_units']
			),
			'fields' => array(
				'AssetsBusinessUnit.asset_id'
			),
			'recursive' => -1
		));

		return $query;
	}

	public function updateRiskScores() {
		$this->Risk->calculateAndSaveRiskScoreById($this->RiskIds);
		$this->ThirdPartyRisk->calculateAndSaveRiskScoreById($this->TpRiskIds);
	}

	public function getThreatsVulnerabilities($assetIds) {
		$typeIds = $this->find('list', array(
			'conditions' => array(
				'Asset.id' => $assetIds
			),
			'fields' => array('asset_media_type_id'),
			'recursive' => -1
		));

		return $this->AssetMediaType->getThreatsVulnerabilities($typeIds);
	}

	public function getAssetClassificationsData() {
		$this->AssetClassification->bindModel(array(
			'hasMany' => array(
				'AssetClassificationsAsset'
			)
		));

		$data = $this->AssetClassification->find('all', array(
			'fields' => array('AssetClassification.name', 'AssetClassification.value', 'AssetClassification.criteria'),
			'contain' => array(
				'AssetClassificationType' => array(
					'fields' => array('id', 'name')
				),
				'AssetClassificationsAsset' => array(
					'fields' => array('asset_id')
				)
			)
		));

		$formattedData = $joinAssets = array();
		foreach ($data as $classification) {
			$formattedData[$classification['AssetClassification']['id']] = $classification;

			foreach ($classification['AssetClassificationsAsset'] as $join) {
				if (!isset($joinAssets[$join['asset_id']])) {
					$joinAssets[$join['asset_id']] = array();
				}

				$joinAssets[$join['asset_id']][] = $join['asset_classification_id'];
			}
		}

		return array(
			'formattedData' => $formattedData,
			'joinAssets' => $joinAssets
		);
	}

	/**
	 * Add missing DataAssetInstance records for all assets.
	 *
	 * @return  boolean Success.
	 */
	public function addMissingInstances() {
		$Instance = ClassRegistry::init('DataAssetInstance');

		$assets = $this->find('all', ['recursive' => -1]);

		$ret = true;

		foreach ($assets as $asset) {
			$ret &= $this->createDataAssetInstance($asset['Asset']['id']);
		}

		return $ret;
	}
}
