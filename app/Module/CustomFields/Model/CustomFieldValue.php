<?php
App::uses('CustomFieldsAppModel', 'CustomFields.Model');

class CustomFieldValue extends CustomFieldsAppModel {
	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'value', 'custom_field_id', 'model', 'foreign_key'
			)
		)
	);

	// this is bound manually in CustomFieldsBehavior::beforeValidate()
	/*public $belongsTo = array(
		'CustomField'
	);*/
}