<?php
App::uses('SectionBase', 'Model');

class AssetClassification extends SectionBase {
	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'criteria'
			)
		)
	);

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'workflow' => false,
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'criteria' => array(
		),
		'value' => array(
			'numeric' => array(
				'rule' => 'numeric',
				'allowEmpty' => false,
				'message' => 'Please enter a number'
			)
		),
	);

	public $belongsTo = array(
		'AssetClassificationType' => array(
			'counterCache' => true
		)
	);

	public $hasAndBelongsToMany = array(
		'Asset'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'AssetClassification'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'AssetClassification'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'AssetClassification'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Asset Classifications');

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
		];

		$this->fieldData = [
			'asset_classification_type_id' => [
				'label' => __('Classification Type'),
				'editable' => true,
				'description' => __('Select from this drop down an existing classification type or create a new one with the field below.'),
			],
			'name' => [
				'label' => __('Classification Options'),
				'editable' => true,
				'description' => __('For each classification type, you will need to proivde options. Examples could be "High", "Low", etc.'),
			],
			'criteria' => [
				'label' => __('Criteria'),
				'editable' => true,
				'description' => __('OPTIONAL: define a criteria for this classification option'),
			],
			'value' => [
				'label' => __('Value'),
				'editable' => true,
				'description' => __('OPTIONAL: Certain risk calculation methods (Magerit, Allegro, Etc) require you to classify your assets in numerical values, for that reason you need to provide a value for this classification.'),
			],
		];

		parent::__construct($id, $table, $ds);
	}

	public function beforeSave($options = array()) {
		$this->transformDataToHabtm(array('Asset'));

		if (isset($this->data['AssetClassification']['value']) && is_numeric($this->data['AssetClassification']['value'])) {
			$this->data['AssetClassification']['value'] = CakeNumber::precision($this->data['AssetClassification']['value'], 2);
		}

		return true;
	}

	public function afterDelete() {
		$data = $this->AssetClassificationType->find('list', array(
			'conditions' => array(
				'AssetClassificationType.asset_classification_count' => 0
			),
			'fields' => array('id'),
			'recursive' => -1
		));

		$d = $this->AssetClassificationType->deleteAll(array(
			'AssetClassificationType.id' => $data
		));
	}

	public function getRelatedRisks($classificationId) {
		$data = $this->find('all', array(
			'conditions' => array(
				'AssetClassification.id' => $classificationId
			),
			'contain' => array(
				'Asset' => array(
					'fields' => array('id'),
					'Risk' => array(
						'fields' => array('id')
					)
				)
			)
		));

		$riskIds = array();
		foreach ($data as $ac) {
			foreach ($ac['Asset'] as $asset) {
				foreach ($asset['Risk'] as $risk) {
					$riskIds[] = $risk['id'];
				}
			}
		}

		return array(
			'riskIds' => array_unique($riskIds)
		);
	}

	public function isUsed($classificationId) {
		$related = $this->getRelatedRisks($classificationId);
		if (!empty($related['riskIds'])) {
			return true;
		}
		return false;
	}
}
