<?php
App::uses('CustomFieldsAppModel', 'CustomFields.Model');

class CustomFieldOption extends CustomFieldsAppModel {
	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'value'
			)
		)
	);

	public $validate = array(
		'value' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		)
	);

	public $belongsTo = array(
		'CustomField' => array(
			'className' => 'CustomFields.CustomField',
		),
	);

}