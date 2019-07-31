<?php
App::uses('ModelBehavior', 'Model/Behavior');
class CustomFieldsBehavior extends ModelBehavior {

	/**
	 * Settings array
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Default settings
	 *
	 * field - the fieldname that contains CustomFieldValue data
	 *
	 * @var array
	 */
	protected $_defaults = array(
		'field' => 'CustomFieldValue',
	);

	public function setup(Model $Model, $config = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->_defaults;
		}

		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $config);
		$this->bindCustomFieldValues($Model);
	}
	public function bindCustomFieldValues($Model) {
		if ($Model->getAssociated('CustomFieldValue') === null) {
			$Model->bindModel(array(
				'hasMany' => array(
					'CustomFieldValue' => array(
						'className' => 'CustomFields.CustomFieldValue',
						'foreignKey' => 'foreign_key',
						'conditions' => array(
							'CustomFieldValue.model' => $Model->alias
						),
						'fields' => array('id', 'model', 'foreign_key', 'value', 'custom_field_id')
					)
				)
			), false);
		}
	}

	public function unbindCustomFieldValues($Model) {
		if ($Model->getAssociated('CustomFieldValue') !== null) {
			$Model->unbindModel(array(
				'hasMany' => array(
					'CustomFieldValue'
				)
			), false);
		}
	}

	public function beforeFind(Model $Model, $query = array()) {
		// $Model->bindCustomFieldValues();
		return true;
	}
	/**
	 * Custom fields general validation rules for different field types.
	 */
	public function beforeValidate(Model $Model, $options = array()) {
		// $Model->bindCustomFieldValues();

		$invalid = false;
		if (!empty($Model->data['CustomFieldValue'])) {
			$Model->CustomFieldValue->bindModel(array(
				'belongsTo' => array(
					'CustomField' => array(
						'className' => 'CustomFields.CustomField'
					)
				)
			));
			foreach ($Model->data['CustomFieldValue'] as $customFieldId => $customFieldValue) {
				$field = $Model->CustomFieldValue->CustomField->find('first', array(
					'conditions' => array(
						'CustomField.id' => $customFieldId
					),
					'contain' => array(
						'CustomFieldOption'
					)
				));
				// custom validation for a date field
				if ($field['CustomField']['type'] == CUSTOM_FIELD_TYPE_DATE && !empty($customFieldValue['value']) && !Validation::date($customFieldValue['value'])) {
					$this->_invalidateCustomField($Model, $customFieldId, __('Please enter a correct date format'));
					$invalid = true;
				}
				// custom validation for dropdown to check if selected option really exists
				if ($field['CustomField']['type'] == CUSTOM_FIELD_TYPE_DROPDOWN && !empty($customFieldValue['value'])) {
					$availableOptions = array();
					foreach ($field['CustomFieldOption'] as $option) {
						$availableOptions[] = $option['id'];
					}
					if (!in_array($customFieldValue['value'], $availableOptions)) {
						$this->_invalidateCustomField($Model, $customFieldId, __('Please select one from the list of available options'));
						$invalid = true;
					}
				}
			}
			$Model->CustomFieldValue->unbindModel(array(
				'belongsTo' => array('CustomField')
			));
		}
		if (empty($invalid)) {
			return true;
		}
		return false;
	}

	private function _invalidateCustomField($Model, $customFieldId, $message) {
		$Model->invalidate('customFieldValue_' . $customFieldId, $message);
	}

	public function afterValidate(Model $model) {
		$this->unbindCustomFieldValues($model);
		return true;
	}

	public function afterSave(Model $model, $created, $options = array()) {
		if (!isset($model->data[$this->settings[$model->alias]['field']])) {
			return;
		}

		$data = $this->_getData($model);
		if (!empty($data)) {
			$this->bindCustomFieldValues($model);
			$this->_deleteCustomFields($model, $data);
			$this->_saveCustomFields($model, $data);
		}
	}

	/**
	 * saves CustomFieldValue data related to input $model or $data
	 * 
	 * @param  Model $model data
	 * @param  array|false $data
	 * @return boolean
	 */
	protected function _saveCustomFields($model, $data = false) {
		if ($data === false) {
			$data = $this->_getData($model);
		}

		if (empty($data)) {
			return true;
		}
		$saveData = array();
		foreach ($data as $customFieldId => $value) {
			$saveData[] = array(
				'model' => $model->alias,
				'foreign_key' => $model->id,
				'custom_field_id' => $customFieldId,
				'value' => $value['value'],
			);
		}

		$result = $model->CustomFieldValue->saveAll($saveData, array(
			'validate' => false,
			'atomic' => false
		));

		return (bool) $result;
	}

	/**
	 * deletes CustomFieldValue data related to input $model or $data
	 * 
	 * @param  Model $model data
	 * @param  array|false $data
	 * @return boolean
	 */
	protected function _deleteCustomFields($model, $data = false) {
		if ($data === false) {
			$data = $this->_getData($model);
		}

		if (empty($data)) {
			return true;
		}
		$deleteData = array();
		foreach ($data as $customFieldId => $value) {
			$deleteData[] = array(
				'model' => $model->alias,
				'foreign_key' => $model->id,
				'custom_field_id' => $customFieldId
			);
		}

		$result = $model->CustomFieldValue->deleteAll(array('OR' => $deleteData));
		return (bool) $result;
	}

	/**
	 * extracts and returns CustomFieldValue data from model data
	 * 
	 * @param  Model $model data
	 * @return array 
	 */
	protected function _getData($model) {
		$field = $this->settings[$model->alias]['field'];
		return (!empty($model->data[$field])) ? $model->data[$field] : array();
	}
}
