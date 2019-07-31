<?php
App::uses('CustomFieldsAppModel', 'CustomFields.Model');
App::uses('CustomFieldsModule', 'CustomFields.Lib');

class CustomForm extends CustomFieldsAppModel {
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
				'name'
			)
		)
	);

	public $validate = array(
		'model' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		)
	);

	public $hasMany = array(
		'CustomField' => array(
			'className' => 'CustomFields.CustomField',
		),
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'CustomForm'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'CustomForm'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'CustomForm'
			)
		)
	);

	/**
	 * Get max fields count for a certain model name.
	 */
	public function getMaxFieldsCount($customFormId) {
		$model = $this->getModelName($customFormId);

		return CustomFieldsModule::getMaxFieldsCount($model);
	}

	/**
	 * Get the model name defined for a saved Custom Form.
	 * 
	 * @param  int $customFormId Custom Form ID
	 * @return string            Model name
	 */
	public function getModelName($customFormId) {
		$data = $this->find('first', [
			'conditions' => [
				$this->alias . '.' . $this->primaryKey => $customFormId
			],
			'fields' => [
				$this->alias . '.model'
			],
			'recursive' => -1
		]);

		if (empty($data)) {
			throw new NotFoundException(sprintf("Custom Form by the ID %s was not found.", $customFormId));
		}

		return $data[$this->alias]['model'];
	}

}