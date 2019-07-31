<?php
App::uses('Component', 'Controller');
class CustomFieldsMgtComponent extends Component {
	public $components = array('Session');
	public $settings = array();

	public function __construct(ComponentCollection $collection, $settings = array()) {
		if (empty($this->settings)) {
			$this->settings = array(
				'model' => null
			);
		}

		$settings = array_merge($this->settings, (array)$settings);
		parent::__construct($collection, $settings);
	}

	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->controller->loadModel('CustomFields.CustomField');
		$this->controller->helpers[] = 'CustomFields.CustomFields';
	}

	public function startup(Controller $controller) {
		// $this->loadData($this->settings['model']);
	}

	public function saveFieldValues($foreignKey, $model = null) {
		if (empty($model)) {
			$model = $this->settings['model'];
		}

		$ret = true;
		$data = $this->controller->request->data;
		if (!empty($data['CustomFieldValue'])) {
			$this->controller->loadModel('CustomFields.CustomFieldValue');
			foreach ($data['CustomFieldValue'] as $customFieldId => $value) {
				$saveData = array(
					'model' => $model,
					'foreign_key' => $foreignKey,
					'custom_field_id' => $customFieldId
				);

				$ret &= $this->controller->CustomFieldValue->deleteAll($saveData);

				$saveData['value'] = $value['value'];

				$this->controller->CustomFieldValue->create();
				$this->controller->CustomFieldValue->set($saveData);
				$ret &= $this->controller->CustomFieldValue->save();
			}
		}

		return $ret;
	}

	/**
	 * Get all Custom Fields information.
	 */
	public function getData($model = null) {
		if (empty($model)) {
			$model = $this->settings['model'];
		}

		$this->controller->loadModel('CustomFields.CustomFieldSetting');
		$sectionSettings = $this->controller->CustomFieldSetting->find('first', array(
			'conditions' => array(
				'CustomFieldSetting.model' => $model
			)
		));

		$customFieldsEnabled = (!empty($sectionSettings['CustomFieldSetting']['status']) && ($sectionSettings['CustomFieldSetting']['status'] == CUSTOM_FIELD_SETTING_STATUS_ENABLED)) ? true : false;

		$customForms = false;
		$customFields = false;
		if ($customFieldsEnabled) {
			$this->controller->loadModel('CustomFields.CustomForm');
			$customForms = $this->controller->CustomForm->find('all', array(
				'conditions' => array(
					'CustomForm.model' => $model
				),
				'contain' => array(
					'CustomField' => array(
						'CustomFieldOption'
					)
				)
			));

			$customFields = $this->controller->CustomForm->CustomField->find('all', array(
				'conditions' => array(
					'CustomForm.model' => $model
				),
				'fields' => array('CustomField.*'),
				'contain' => array(
					'CustomForm',
					'CustomFieldOption'
				)
			));

			foreach ($customFields as &$field) {
				$field['CustomField']['CustomFieldOption'] = $field['CustomFieldOption'];
				unset($field['CustomFieldOption']);
			}
		}

		return array(
			'enabled' => $customFieldsEnabled,
			'customFieldsList' => $customFields,
			'data' => $customForms,
			'available' => $this->getAvailability($customFieldsEnabled, $customForms)
		);
	}

	/**
	 * Set custom fields which are available for current section into view.
	 */
	public function setData($model = null, $varPrefix = '') {
		if (empty($model)) {
			$model = $this->settings['model'];
		}

		$customFields = $this->getData($model);

		$this->controller->set($varPrefix . 'customFields_enabled', $customFields['enabled']);
		$this->controller->set($varPrefix . 'customFields_model', $model);
		$this->controller->set($varPrefix . 'customFields_data', $customFields['data']);

		// finally we set whether we display custom fields or not, views check it based on this variable
		$this->controller->set($varPrefix . 'customFields_available', $customFields['available']);

		return array(
			$varPrefix . 'customFields_enabled' => $customFields['enabled'],
			$varPrefix . 'customFields_model' => $model,
			$varPrefix . 'customFields_data' => $customFields['data'],
			$varPrefix . 'customFields_available' => $customFields['available'],
		);
	}

	private function getAvailability($customFieldsEnabled, $customForms) {
		return isset($customFieldsEnabled) && $customFieldsEnabled && !empty($customForms);
	}


}
