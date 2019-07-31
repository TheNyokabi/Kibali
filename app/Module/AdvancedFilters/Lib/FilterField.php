<?php
App::uses('Hash', 'Utility');

class FilterField {

	/**
	 * Allowed keys for field filter preferences.
	 * 
	 * @var array
	 */
	public static $allowedPreferences = ['comparisonType'];

	protected $_field = null;

	protected $_defaults = [];

	protected $_config = [];

	protected $_model = null;

	protected $_dirty = false;

	public function __construct(Model $Model, $field, $config = []) {
		$this->_field = $field;
		$this->_config = array_merge($config, $this->_defaults);

		$this->_model = &$Model;
	}

	/**
	 * Get the model this field is attached to.
	 * 
	 * @return Model
	 */
	public function getModel() {
		return $this->_model;
	}
	
	/**
	 * Get or set configuration for this field. @see CrudComponent::config()
	 *
	 * @return mixed Depending on the arguments.
	 */
	public function config($key = null, $value = null) {
		if ($key === null && $value === null) {
			return $this->_config;
		}

		if ($value === null) {
			if (is_array($key)) {
				$this->_config = Hash::merge($this->_config, $key);
				return $this;
			}

			return Hash::get($this->_config, $key);
		}

		if (is_array($value)) {
			$value = array_merge((array)Hash::get($this->_config, $key), $value);
		}

		return $this;
	}

	/**
	 * Builds a query parameter out of existing config of this field,
	 * which goes to '?' => [..] and can be used in Router::url() method. 
	 * 
	 * @return array Query parameter
	 */
	public function buildQueryParams() {
		$query = [
			$this->getFieldName() . '__comp_type' => $this->config('comparisonType'),
			$this->getFieldName() => $this->config('value')
		];

		return $query;
	}

	/**
	 * Get the field name as usable in filters.
	 * 
	 * @return strin Field name.
	 */
	public function getFieldName() {
		return $this->_field;
	}

	/**
	 * Put a comparison type value from current configuration into Model's filterArgs,
	 * to make it possible to do filtering.
	 * 
	 * @param string $value Comaprison type.
	 */
	public function setComparisonType($value) {
		$this->getModel()->filterArgs[$this->_field]['comp_type'] = $value;
		$this->_makeDirty();
	}

	/**
	 * Gets the current comparison type as it is configured in this field.
	 * 
	 * @return string Comparison type.
	 */
	public function getComparisonType() {
		return $this->config('comparisonType');
	}

	/**
	 * Clears the comparison type setting for this field on the model.
	 */
	public function unsetComparisonType() {
		unset($this->getModel()->filterArgs[$this->_field]['comp_type']);
		$this->_makeDirty();
	}

	/**
	 * Set each available preference before filtering.
	 */
	public function setPreferences() {
		foreach (self::$allowedPreferences as $preference) {
			$value = $this->config($preference);
			if ($value !== null) {
				// method would be e.g 'setComparisonType'
				$fn = self::preferenceMethod($preference);

				$this->{$fn}($value);
			}
		}
	}

	/**
	 * Rollbacks each available preference after filtering to not affect the ones processed after.
	 * 
	 * @return void
	 */
	public function resetPreferences() {
		if ($this->isDirty()) {
			foreach (self::$allowedPreferences as $preference) {
				$fn = self::preferenceMethod($preference, false);
				$this->{$fn}();
			}
		}
	}

	// builds the method name for a given preference
	public static function preferenceMethod($preference, $set = true) {
		$method = sprintf('set%s', ucfirst($preference));
		if ($set !== true) {
			$method = 'un' . $method;
		}

		return $method;
	}

	/**
	 * Check if this instance of a field and it's preferences has been touched or altered in some way already.
	 * 
	 * @return boolean True if a change has been made, False otherwise
	 */
	public function isDirty() {
		return $this->_dirty === true;
	}

	/**
	 * Method sets this field instance as modified and not in original congfiguration.
	 * 
	 * @return void
	 */
	protected function _makeDirty() {
		$this->_dirty = true;
	}

}
