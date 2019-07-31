<?php
App::uses('CustomFieldsAppModel', 'CustomFields.Model');

class CustomField extends CustomFieldsAppModel {
	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		// 'workflow' => true
	);
	
	public $actsAs = array(
		'Containable',
		'Sluggable' => array(
			'label' => 'name',
			'separator' => '_'
		),
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'custom_form_id'
			)
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'type' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'custom_form_id' => array(
			'rule' => 'notBlank',
			'required' => true
		),
	);

	public $belongsTo = array(
		'CustomForm' => array(
			'className' => 'CustomFields.CustomForm',
		),
	);

	public $hasMany = array(
		'CustomFieldOption' => array(
			'className' => 'CustomFields.CustomFieldOption',
		),
		'CustomFieldValue' => array(
			'className' => 'CustomFields.CustomFieldValue',
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'CustomField'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'CustomField'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'CustomField'
			)
		)
	);

	public function deleteStoredData($id) {
		return $this->CustomFieldValue->deleteAll(array(
			'CustomFieldValue.custom_field_id' => $id
		));
	}

	/**
	 * Compares request form data with existing data for changes.
	 */
	public function checkChanges() {
		$item = $this->find('first', array(
			'conditions' => array(
				'CustomField.id' => $this->data['CustomField']['id']
			),
			'fields' => array('type'),
			'recursive' => -1
		));

		$changes = array();
		if ($item['CustomField']['type'] != $this->data['CustomField']['type']) {
			$changes[] = 'type';
		}

		return $changes;
	}
}