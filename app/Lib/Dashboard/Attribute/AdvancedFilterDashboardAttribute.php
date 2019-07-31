<?php
App::uses('BaseFilterDashboardAttribute', 'AdvancedFilters.Lib');

class AdvancedFilterDashboardAttribute extends BaseFilterDashboardAttribute {

	public function buildUrl(Model $Model, &$query, $attribute, $item = []) {
		return $query;
	}

	/**
	 * Convert advanced filter values list of params pulled from database into Dashboard-compatible format.
	 * 
	 * @param  array  $filterId Advanced Filter values array in a raw state.
	 * @return array            Converted array.
	 */
	public function buildParams(Model $Model, $filterId) {
		$AdvancedFilter = ClassRegistry::init('AdvancedFilter');
		$values = $AdvancedFilter->getFilterValues($filterId);
		$query = AdvancedFilter::buildValues($values);

		unset($query['advanced_filter']);
		unset($query['_limit']);

		$newParams = [];
		foreach ($query as $param => $value) {
			if (strpos($param, '__') === false) {
				$newParams[$param]['value'] = $value;
				continue;
			}

			if (strpos($param, '__comp_type') !== false) {
				$field = explode('__', $param);
				$newParams[$field[0]]['comparisonType'] = $value;	
			}
		}

		return $newParams;
	}

	public function buildQuery(Model $Model, $attribute) {
		$Model->Behaviors->load('AdvancedFilters.AdvancedFilters');

		$parameters = $this->buildParams($Model, $attribute);

		$conditions = $Model->filterCriteria($parameters);

		$query = [
			'conditions' => $conditions,
			'fields' => [$Model->escapeField($Model->primaryKey)]
		];

		return $query;
	}

	public function softDelete(Model $Model, $attribute) {
		return $this->softDelete;
	}

}
