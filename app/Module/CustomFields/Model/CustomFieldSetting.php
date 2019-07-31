<?php
App::uses('CustomFieldsAppModel', 'CustomFields.Model');

class CustomFieldSetting extends CustomFieldsAppModel {
	public $mapping = array(
		'titleColumn' => 'model',
		'logRecords' => true,
		// 'workflow' => true
	);

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'status'
			)
		)
	);

	public $validate = array(
		'status' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Status is required'
			),
			'inList' => array(
				'rule' => array('inList', array('1', '0')),
				'message' => 'Please use provided field options'
			)
		)
	);

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['model'])) {
			unset($this->data[$this->alias]['model']);
		}

		return true;
	}

}