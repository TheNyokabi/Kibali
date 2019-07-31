<?php
App::uses('Component', 'Controller');

class AdvancedFiltersExtentedComponent extends Component {

	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

	public function processFilterSettings($filterSetting) {
		$customFieldSet = $this->processCustomFields($filterSetting);

		if (!empty($customFieldSet)) {
			$filterSetting['advancedFilter'][__('Custom Fields')] = $customFieldSet;
		}

		return $filterSetting;
	}

	public function hasCustomFields() {
		//CustomFieldsMgt
		$ret = $this->controller->Components->loaded('CustomFieldsMgt');
		$ret &= $this->controller->Components->enabled('CustomFieldsMgt');

		return $ret;
	}

	private function getCustomFields() {
		if ($this->hasCustomFields()) {
			return $this->controller->CustomFieldsMgt->getData();
		}

		return false;
	}

	private function processCustomFields($filterSetting) {
		$customFields = $this->getCustomFields();
		$this->controller->set('CustomFieldsData', $customFields);

		if (empty($customFields) || !$customFields['available']) {
			return false;
		}

		$customFieldSet = array();
		foreach ($customFields['data'] as $customForm) {
			if (empty($customForm['CustomField'])) {
				continue;
			}

			foreach ($customForm['CustomField'] as $field) {
				$arg = getCustomFieldArg($field);

				$fieldData = array(
					'type' => $this->convertToFilterFieldType($field['type']),
					'name' => $field['name'],
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByCustomField',
						'customField' => $field,
						'field' => $filterSetting['model'] . '.' . $this->controller->{$filterSetting['model']}->primaryKey
					),
					'field' => $filterSetting['model'] . '.' . $this->controller->{$filterSetting['model']}->primaryKey
				);

				if ($field['type'] == CUSTOM_FIELD_TYPE_DATE) {
					$fieldData['comparison'] = true;

					$this->controller->{$filterSetting['model']}->filterArgs[$arg . '__comp_type'] = array();
					$this->controller->presetVars[] = array(
						'type' => 'value',
						'field' => $arg . '__comp_type'
					);
				}

				if ($field['type'] == CUSTOM_FIELD_TYPE_DROPDOWN) {
					$options = array();
					foreach ($field['CustomFieldOption'] as $option) {
						$options[$option['id']] = $option['value']; 
					}

					$fieldData['data']['options'] = $options;
				}

				$customFieldSet[$arg] = $fieldData;
				$this->controller->{$filterSetting['model']}->filterArgs[$arg] = $fieldData['filter'];
				$this->controller->{$filterSetting['model']}->filterArgs[$arg . '__show'] = array();

				$this->controller->presetVars[] = array(
					'type' => 'value',
					'field' => $arg
				);
				$this->controller->presetVars[] = array(
					'type' => 'value',
					'field' => $arg . '__show'
				);
			}
		}

		return $customFieldSet;
	}

	/**
	 * Convert Custom Field type into AdvancedFilter type for backwards compatibility.
	 */
	private function convertToFilterFieldType($type = null) {
		$types = array(
			CUSTOM_FIELD_TYPE_SHORT_TEXT => 'text',
			CUSTOM_FIELD_TYPE_TEXTAREA => 'text',
			CUSTOM_FIELD_TYPE_DATE => 'date',
			CUSTOM_FIELD_TYPE_DROPDOWN => 'select',
		);

		if (empty($type) || !isset($types[$type])) {
			return 'text';
		}

		return $types[$type];
	}

}