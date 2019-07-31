<?php
App::uses('AppHelper', 'View/Helper');
App::uses('DashboardKpi', 'Dashboard.Model');
App::uses('Dashboard', 'Dashboard.Lib');

class DashboardKpiHelper extends AppHelper {
	public $helpers = ['Html', 'Ux', 'AdvancedFilters', 'Eramba'];

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->DashboardKpi = ClassRegistry::init('Dashboard.DashboardKpi');
	}

	/**
	 * Renders a box with KPIs.
	 */
	public function widget($section, $model, $categories) {
		$widget = $this->_View->element('Dashboard.' . $section, [
			'model' => $model,
			'categories' => $categories
		]);

		return $this->Html->div('widget widget-dashboard box', $widget, [
			'escape' => false
		]);
	}

	/**
	 * Shows the KPI value on the dashboard, otherwise if value is missing, it shows only a tooltip with information.
	 */
	public function getKpiValue($item) {
		$value = $item['DashboardKpi']['value'];

		if ($value !== null) {
			$ret = $value;
		}
		else {
			$ret = $this->noValueTooltip();
		}

		return $ret;
	}

	/**
	 * In case there is no value for a KPI, show only an informational tooltip.
	 * 
	 * @return string
	 */
	public function noValueTooltip() {
		return $this->Eramba->getTruncatedTooltip('', [
			'content' => __('Value will be recalculated during the next hourly CRON run')
		]);
	}

	public function getKpiLink($model, $item) {
		$Model = ClassRegistry::init($model);
		$value = $item['DashboardKpi']['value'];
		$valueIcon = $value .  ' &nbsp;' . $this->Ux->getIcon('external-link') . '';

		// handler for saved filters as KPIs
		if (isset($item['attributes']['AdvancedFilter'])) {
			$link = $this->AdvancedFilters->getFilterRedirectUrl($item['attributes']['AdvancedFilter']);

			return $this->Html->link($value, $link);
		}

		// special handler for awareness program KPIs
		if (isset($item['attributes']['AwarenessProgram'])) {
			$link = $this->AdvancedFilters->getItemFilteredLink(
				$valueIcon,
				'AwarenessProgramUser',
				$item['attributes']['AwarenessProgram'],
				[
					'key' => 'awareness_program_id',
					'param' => $item['attributes']['AwarenessProgramUserModel']
				]
			);

			return $link;
		}

		$query = [];
		$findOn = $Model;
		foreach ($item['attributes'] as $attributeClass => $attribute) {
			$AttributeInstance = $this->DashboardKpi->instance()->attributeInstance($attributeClass);

			// $filterField = $AttributeInstance->mapFilterField($Model, $attribute);
			$query = $AttributeInstance->buildUrl($Model, $query, $attribute, $item);
		}

		$element = $this->AdvancedFilters->getItemFilteredLink($valueIcon, $findOn->alias, null, [
			'controller' => $findOn->getMappedController(),
			'query' => $query
		]);
			
		return $element;
	}

	/**
	 * Helper method that shows attributes of a KPI.
	 * 
	 * @param  string   Model class name.
	 * @return mixed    Array of nicely formatted attributes or boolean false if nothing found.
	 */
	public function getModelAttributeList($model) {
		$attributeList = $this->_View->get('attributeList');
		if ($attributeList !== null && isset($attributeList[$model])) {
			return $attributeList[$model];
		}

		return false;
	}

	public function displayModelLabel($model) {
		return ClassRegistry::init($model)->label();
	}

	// KPIs searched for in array of KPIs by its attributes
	public function searchKpiByAttributes(&$items, $attributes) {
		// debug($attributes);
		// dd($items);
		foreach ($items as $key => $item) {
			$found = true;
			foreach ($attributes as $attr => $value) {
				if (!isset($item['attributes'][$attr]) || $item['attributes'][$attr] != $value) {
					// debug($value);
					// dd($item);
					$found = false;
					break;
				}

			}

			if ($found) {
				$return = $item;
				// unset($items[$key]);

				return $return;
			}
		}
		// debug($items);
// dd($attributes);
		return false;
	}

}