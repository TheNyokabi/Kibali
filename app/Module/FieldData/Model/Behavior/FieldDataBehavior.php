<?php
App::uses('ModelBehavior', 'Model');
App::uses('Hash', 'Utility');
App::uses('FieldDataEntity', 'FieldData.Model/FieldData');
App::uses('FieldGroupEntity', 'FieldData.Model/FieldData');
App::uses('FieldDataCollection', 'FieldData.Model/FieldData');
App::uses('DebugMemory', 'DebugKit.Lib');

/**
 * FieldDataBehavior
 */
class FieldDataBehavior extends ModelBehavior {

	protected $_runtime = [];

	public $debug = ['totalMemory' => 0];

	/**
	 * Holds lists of existing database tables groupped by config name.
	 * 
	 * @var array
	 */
	protected $_listSources = [];

	/**
	 * Default config
	 *
	 * @var array
	 */
	protected $_defaults = array(
		'enabled' => true
	);

	public $settings = [];

	/**
	 * Setup
	 *
	 * @param Model $Model
	 * @param array $settings
	 * @throws RuntimeException
	 * @return void
	 */
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = Hash::merge($this->_defaults, $settings);
		}

		if (Configure::read('debug')) {
			$this->debug[$Model->alias]['memory'] = 0;
			$_memory = DebugMemory::getCurrent();
		}

		foreach ($Model->fieldGroupData as $key => $config) {
			// this is a handler of a case when the same model is run through this setup() callback more than once
			// @todo this should not happen but not critical at the moment
			if ($Model->fieldGroupData[$key] instanceof FieldGroupEntity) {
				continue;
			}
			
			$config['__key'] = $key;
			$Model->fieldGroupData[$key] = new FieldGroupEntity($config);
		}

		$this->_initConfig($Model);

		if (Configure::read('debug')) {
			$_memory = DebugMemory::getCurrent() - $_memory;
			$this->debug[$Model->alias]['memory'] += $_memory;
			$this->debug['totalMemory'] += $_memory;
		}
	}

	/**
	 * Get field data entities.
	 * 
	 * @param  string|null $field String to get specified entity, null to get array of all entities.
	 * @return FieldDataEntity|array
	 * @todo
	 */
	public function getFieldCollection(Model $Model) {
		if (!$this->_runtime[$Model->alias] instanceof FieldDataCollection) {
			trigger_error(sprintf('Field Collection class for model %s is not configured properly', $Model->alias));
		}

		return $this->_runtime[$Model->alias];
	}

	// checks if there is some collection of fields on the $Model
	public function hasFieldCollection(Model $Model) {
		return $this->_runtime[$Model->alias] instanceof FieldDataCollection;
	}

	/**
	 * Get field data entities.
	 * 
	 * @param  string|null $field String to get specified entity, null to get array of all entities.
	 * @return FieldDataEntity|array
	 * @todo
	 */
	public function getFieldDataEntity(Model $Model, $field = null) {
		$collection = $this->getFieldCollection($Model);
		if ($collection->has($field)) {
			return $collection->get($field);
		}

		return $collection;
	}

	/**
	 * Look if given field has a FieldDataEntity class configured.
	 * 
	 * @param  string  $field Field name.
	 * @return boolean
	 */
	public function hasFieldDataEntity(Model $Model, $field) {
		return $this->getFieldCollection($Model)->has($field);
	}

	/**
	 * Initialize FieldData classes.
	 */
	protected function _initConfig(Model $Model) {
		$config = $Model->fieldData;

		// setup depends on some provided config and table in datasource for schema() parameters.
		if (empty($config) || !$this->tableExists($Model)) {
			return false;
		}

		$defaults = $this->getConfigDefaults($Model);
		$runtimeConfig = Hash::merge($defaults, $config);

		$this->settings[$Model->alias]['config'] = $runtimeConfig;

		$Collection = new FieldDataCollection([], $Model);
		foreach ($runtimeConfig as $field => $params) {
			$Collection->add($field, $params);
		}

		$this->_runtime[$Model->alias] = $Collection;
	}

	/**
	 * Checks if a table really exists in database without throwing a missing table error.
	 * 
	 * @param  Model  $Model
	 * @return boolean       True if exists, False otherwise.
	 */
	public function tableExists(Model $Model) {
		return in_array(
			$Model->tablePrefix . $Model->useTable,
			$this->listSources($Model)
		);
	}

	/**
	 * Cached method returns array of tables for a specified database config.
	 * 
	 * @param  Model  $Model
	 * @return array         List of tables.
	 */
	public function listSources(Model $Model) {
		$dbConfig = $Model->useDbConfig;
		if (!isset($this->_listSources[$dbConfig])) {
			$this->_listSources[$dbConfig] = ConnectionManager::getDataSource($dbConfig)->listSources();
		}

		return $this->_listSources[$dbConfig];
	}

	/**
	 * Triggers an event about a change of property value after saving.
	 */
	public function afterAuditProperty(Model $Model, $_m, $propertyName, $oldValue, $newValue) {
		if ($this->getFieldCollection($Model)->has($propertyName)) {
			return $this->getFieldCollection($Model)->get($propertyName)->trigger('afterChange', [$oldValue, $newValue]);
		}
	}

	/* --- */

	// Get a list of default configurations for possibly existing columns.
	public function getConfigDefaults(Model $Model) {
		$fieldData = array(
			$Model->primaryKey => array(
				'label' => __('ID'),
				'type' => 'primary'
			),
			'created' => array(
				'label' => __('Created')
			),
			'modified' => array(
				'label' => __('Modified')
			),
			'deleted' => array(
				'label' => __('Deleted'),
				'type' => 'toggle'
			),
			'deleted_date' => array(
				'label' => __('Deleted Date')
			)
		);

		foreach ($fieldData as $field => $config) {
			if (!$Model->hasField($field)) {
				unset($fieldData[$field]);
			}
		}

		return $fieldData;
	}

	protected function _logIssues(Model $Model) {
		// Log missing FieldDataEntities definitions for model only in case this model uses related functionality
		if ($this->Behaviors->loaded('AuditLog.Auditable')) {
			$_definedFieldData = array_keys($this->fieldData);

			$_modelColumnTypes = array_keys($this->getColumnTypes());
			$_habtm = $this->getAssociated('hasAndBelongsToMany');
			$_hasMany = $this->getAssociated('hasMany');
			$_hasOne = $this->getAssociated('hasOne');
			$_assoc = am($_habtm, $_hasMany, $_hasOne, $_modelColumnTypes);

			$whiteList = array(
				'workflow_status',
				'workflow_owner_id',
				'CustomFieldValue',
				'WorkflowAcknowledgement',
				'SystemRecord',
				'Attachment',
				'Comment'
			);
			$diff = array_diff($_assoc, $_definedFieldData, $whiteList);

			if (!empty($diff)) {
				CakeLog::write(
					'fieldData',
					sprintf('Model %s is missing some FieldDataEntities definitions:', $this->alias) . "\n" . Debugger::exportVar($diff)
				);
			}
		}
	}

}
