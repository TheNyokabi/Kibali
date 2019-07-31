<?php
class AssetLabel extends AppModel {
	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description'
			)
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		)
	);

	public $hasMany = array(
		'Asset',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'AssetLabel'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'AssetLabel'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'AssetLabel'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Asset Labels');
		
		parent::__construct($id, $table, $ds);
	}
}
