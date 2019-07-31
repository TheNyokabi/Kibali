<?php
class AssetMediaType extends AppModel {
	public $name = 'AssetMediaType';
	public $actsAs = array(
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name'
			)
		)
	);

	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notBlank'
			),
			'unique' => array(
				'rule' => 'isUnique'
			)
		),
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'AssetMediaType'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'AssetMediaType'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'AssetMediaType'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'Threat',
		'Vulnerability'
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Asset Media Types');
		
		parent::__construct($id, $table, $ds);
	}

	public function getThreatsVulnerabilities($typeIds) {
		if (empty($typeIds)) {
			return array('threats' => array(), 'vulnerabilities' => array());
		}

		$typeIds = array_unique($typeIds);

		$threats = $this->AssetMediaTypesThreat->find('list', array(
			'conditions' => array(
				'AssetMediaTypesThreat.asset_media_type_id' => $typeIds
			),
			'fields' => array('threat_id'),
			'recursive' => -1
		));

		$vulnerabilities = $this->AssetMediaTypesVulnerability->find('list', array(
			'conditions' => array(
				'AssetMediaTypesVulnerability.asset_media_type_id' => $typeIds
			),
			'fields' => array('vulnerability_id'),
			'recursive' => -1
		));

		return array(
			'threats' => array_values($threats),
			'vulnerabilities' => array_values($vulnerabilities)
		);
	}
}
