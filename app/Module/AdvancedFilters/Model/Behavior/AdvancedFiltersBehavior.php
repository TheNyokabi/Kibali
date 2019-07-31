<?php
App::uses('ModelBehavior', 'Model');
App::uses('Hash', 'Utility');
App::uses('FilterField', 'AdvancedFilters.Lib');

/**
 * AdvancedFiltersBehavior
 */
class AdvancedFiltersBehavior extends ModelBehavior {

	/**
	 * Default config
	 *
	 * @var array
	 */
	protected $_defaults = array(
		'enabled' => true,
		'config' => []
	);

	public $settings = [];

	protected $_runtime = [];

	/**
	 * Setup
	 *
	 * @param Model $Model
	 * @param array $settings
	 * @return void
	 */
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = Hash::merge($this->_defaults, $settings);
			$this->_runtime[$Model->alias] = [];
		}

		$this->_parseConfig($Model);
	}

	protected function _parseConfig(Model $Model) {
		// boilerplate for config to develop dependencies
		$configTemplate = Hash::normalize(array_keys(array_filter($Model->filterArgs)));

		// store array of possible field names only for further use
		$this->settings[$Model->alias]['config'] = $configTemplate;
	}

	public function filterField(Model $Model, $field) {
		$cond = array_key_exists($field, $this->settings[$Model->alias]['config']);
		$cond = $cond && !isset($this->_runtime[$Model->alias][$field]);
		if ($cond) {
			$this->_runtime[$Model->alias][$field] = new FilterField($Model, $field, []);
		}

		return $this->_runtime[$Model->alias][$field];
	}

	// reset $filterArgs to the default state ready for next find operation
	// removes comp_type from $filterArgs,... etc
	public function resetFilterArgs(Model $Model) {
		foreach ($this->_runtime[$Model->alias] as $Field) {
			$Field->resetPreferences();
		}
	}

	/**
	 * Execute a complex finder query using filters.
	 * 
	 * @param  Model  $Model  Model.
	 * @param  string $type   Finder query type.
	 * @param  array  $config Configuration for the filtering, used to build final conditions for the query.
	 * @return mixed          Results.
	 */
	public function filter(Model $Model, $type = 'all', $params = []) {
		$conditions = $this->_buildConditions($Model, $params);

		$data = $Model->find($type, [
			'conditions' => $conditions,
			'recursive' => -1
		]);

		return $data;
	}

	/**
	 * Builds and returns the $conditions for query based on provided $params.
	 * 
	 * @param  Model  $Model  Model object.
	 * @param  array  $params Filter parameters.
	 * @return array          Conditions.
	 */
	public function filterCriteria(Model $Model, $params = []) {
		return $this->_buildConditions($Model, $params);

	}

	// before query executes we parse the required parameters and converts then into a usable $conditions
	protected function _buildConditions(Model $Model, $params) {
		$config = $this->_buildConfig($Model, $params);
		$conditions = $Model->parseCriteria($config);

		// reset fields to the original state right after filtering.
		$this->resetFilterArgs($Model);

		return $conditions;
	}

	// triggered just before filtering begins
	protected function _buildConfig(Model $Model, $params) {
		unset($params['_limit']);
		$config = array_combine(array_keys($params), Hash::extract($params, '{s}.value'));
		$fieldConfig = Hash::remove($params, '{s}.value');

		$this->_setFieldConfig($Model, $fieldConfig);

		// initialize fields configuration just before executing a filter
		$this->_prepareFields($Model);

		foreach ($config as $key => &$value) {
			if (is_bool($value)) {
				$value = (int) $value;
				$value = (string) $value;
			}
		}

		return $config;
	}

	/**
	 * Set and configure cusotm field options prior to executing complex filtering.
	 * 
	 * @param array $options Array of options:
	 * 
	 * 'fieldName1' => [
	 * 		'option1' => 'value1',
	 * 		'option2' => 'value2',
	 * 		...
	 * ]
	 */
	protected function _setFieldConfig(Model $Model, $config = []) {
		foreach ($config as $field => $fieldOptions) {
			$Model->filterField($field)->config($fieldOptions);
		}
	}

	/**
	 * Before filter process, this should be run for each field.
	 */
	protected function _prepareFields(Model $Model) {
		foreach ($this->_runtime[$Model->alias] as $Field) {
			$Field->setPreferences();
		}
	}

}
