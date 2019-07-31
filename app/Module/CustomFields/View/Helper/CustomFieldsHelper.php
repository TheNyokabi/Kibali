<?php
App::uses('AppHelper', 'View/Helper');
class CustomFieldsHelper extends AppHelper {
	public $helpers = array('Html', 'Eramba', 'AdvancedFilters', 'Community');
	public $settings = array();

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getGroupAlias($slug) {
		$alias = 'CustomFieldsGroup__' . $slug;

		return $alias;
	}

	public function getFieldTypeSlug($type) {
		return Inflector::slug(mb_strtolower(getCustomFieldTypes($type)));
	}

	public function getItemValue($item, $customField) {
		return getCustomFieldItemValue($item, $customField);
		/*$default = false;
		if (!empty($item['CustomFieldValue'])) {
			foreach ($item['CustomFieldValue'] as $v) {
				if ($v['custom_field_id'] == $customField['id']) {
					$default = $v['value'];
					break;
				}
			}
		}

		if ($customField['type'] == CUSTOM_FIELD_TYPE_DROPDOWN) {
			foreach ($customField['CustomFieldOption'] as $option) {
				if ($option['id'] == $default) {
					$default = $option['value'];
					break;
				}
			}
		}

		return $this->Eramba->getEmptyValue($default);*/
	}

	public function getIndexLink($model) {
		return $this->Community->getIndexLink(__('Customization'));
	}

	public function getTabs() {
		
	}

	public function advancedFilterLink($customFeldsData, $defaultFields = array('id'), $query) {
		if (!empty($customFeldsData[0]['CustomForm'])) {
			$customFeldsData = $customFeldsData[0];
		}

		$fields = array();
		foreach ($customFeldsData['CustomField'] as $item) {
			$fields[] = 'customField__' . $item['slug'];
		}

		$options = array(
			'defaults' => $defaultFields,
			'query' => $query
		);

		return $this->AdvancedFilters->showLink(__('Show Custom Fields'), $fields, $customFeldsData['CustomForm']['model'], $options);
	}
}