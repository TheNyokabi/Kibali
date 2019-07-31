<?php
App::uses('ModelBehavior', 'Model');
/*
 * Example:
 *

$settings = array(
	'disableToggles' => array('default'),
	'customToggles' => array('RiskClassification')
);

$this->mapping['statusManager'] = array(

	// unique name for this config
	'ongoingIncident' => array(

		// column name for this config
		'column' => 'ongoing_incident'

		// method in the used model class that returns current correct value for the given column (status), set null to pull info from db column directly
		'fn' => 'statusOngoingIncident',

		// array of models where to migrate all system records created for this column related to the current used item
		'migrateRecords' => array('SecurityService'),

		// store custom values and use later, for example when deleting an item and need its value
		'customValues' => array(
			'before' => array(
				'customValueLastMissingReview' => array('lastMissingReview', 'Risk')
			)
		),

		'toggles' => array(
			array(
				'value' => RISK_NOT_EXPIRED_REVIEWS,
				'message' => __('The Risk %s that has been with a missing Review %s has no longer missing reviews'),
				'messageArgs' => array(
					0 => '%Current%.title',
					1 => array(
						'type' => 'custom',
						'customValueLastMissingReview'
					),
					1 => array(
						'type' => 'value',
						'My value'
					)
				)
			)
		),

		// listen to changes for a given field and save related logs
		'listeners' => array(
			array(
				'field' => 'security_service_id',
				'model' => 'SecurityService',
				'message' => __('The Security Incident %s has been mapped to the Security Service %s'),
				'messageArgs' => array(
					0 => '%Current%.title',
					1 => '%Related%.name'
				)
			)
		),

		'custom' => array(
			'toggles' => array(
				...
			)
		)
	),

	'status' => array(
		'column' => 'status',
		'migrateRecords' => array(),

		// if null, pull value directly from database column for usage
		'fn' => null,

		// toggle a system record when a value for the current column changes to the one defined in a certain toggle
		'toggles' => array(
			array(
				'value' => SECURITY_POLICY_DRAFT,
				'message' => __('The Security Policy %s has been tagged as being in Draft'),
				'messageArgs' => array(
					0 => '%Current%.index',
					1 => array(
						'type' => 'fn',
						'fn' => 'totalExpenses'
					)
				)
			),
			array(
				'value' => SECURITY_POLICY_RELEASED,
				'message' => __('The Security Policy %s has been tagged as being Published'),
				'messageArgs' => array(
					0 => '%Current%.index'
				)
			)
		)
	)
);
*/


/**
 * Class assumes that $Model->data and $Model->id values are present.
 */
class StatusManagerBehavior extends ModelBehavior {
	const PROCESS_TYPE_BEFORE = 'before';
	const PROCESS_TYPE_AFTER = 'after';
	const MODEL_CURRENT = '%Current%';
	const MODEL_RELATED = '%Related%';
	const LISTENER_ADD = 'add';
	const LISTENER_REMOVE = 'remove';

/**
 * Runtime configuration for this behavior
 *
 * @var array
 */
	public $runtime = array();

	private $runtimeDefaults = array(
		'columnValue' => null
	);

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array(
				'dataBeforeCheck' => array(),
				'migrateSystemRecords' => array()
			);
		}
		$this->settings[$Model->alias] = array_merge(
		$this->settings[$Model->alias], (array) $settings);

		if (!isset($this->runtime[$Model->alias])) {
			/*$this->runtime[$Model->alias] = array(
				'config' => array(),

			);*/
		}
	}

	public function statusConfigExist(Model $Model, $configName) {
		return !empty($Model->mapping['statusManager'][$configName]);
	}

	/**
	 * Returns a config array for a given config name.
	 */
	public function getConfig(Model $Model, $configName = null, $param = null) {
		if (empty($configName)) {
			return false;
		}

		if (empty($this->runtime[$Model->alias]['config'][$configName])) {
			$this->cacheConfig($Model, $configName);
		}

		if (!empty($param)) {
			return $this->runtime[$Model->alias]['config'][$configName][$param];
		}

		return $this->runtime[$Model->alias]['config'][$configName];
	}

	private function cacheConfig(Model $Model, $configName) {
		$config = $Model->mapping['statusManager'][$configName];
		$this->normalizeConfig($config);

		$this->runtime[$Model->alias]['config'][$configName] = $config;
	}

	private function getRuntime(Model $Model, $configName) {
		/*if (empty($configName)) {
			return false;
		}*/

		if (isset($this->runtime[$Model->alias][$Model->id][$configName])) {
			return $this->runtime[$Model->alias][$Model->id][$configName];
		}

		return null;
	}

	/**
	 * Triggers a status check.
	 * 
	 * @param mixed $id  If null trigger all, otherwise trigger one ID or array of IDs.
	 */
	public function triggerStatus(Model $Model, $configName, $id = null, $processType = null, $settings = array()) {
		$ret = true;

		if ($id === null || is_array($id)) {

			// Trigger all items if ID is null
			if ($id === null) {
				$data = $Model->find('list', array(
					'fields' => array('id', 'id'),
					'recursive' => -1
				));
			}

			// trigger array of IDs
			elseif (is_array($id)) {
				$data = $id;
			}

			if (!empty($data)) {
				foreach ($data as $itemId) {
					$ret &= $Model->triggerStatus($configName, $itemId, $processType, $settings);
				}
			}

			return $ret;
		}
// debug($id);
		$Model->id = $id;
		if (empty($processType) || $processType == self::PROCESS_TYPE_BEFORE) {
			$ret &= $this->processStatusItem($Model, self::PROCESS_TYPE_BEFORE, $configName, $settings);
		}

		if (empty($processType) || $processType == self::PROCESS_TYPE_AFTER) {
			$ret &= $this->processStatusItem($Model, self::PROCESS_TYPE_AFTER, $configName, $settings);
		}
// debug($this->runtime);
		return $ret;
	}

	public function processStatusManagement(Model $Model, $processType = self::PROCESS_TYPE_AFTER) {
		if (!isset($Model->mapping['statusManager']) || empty($Model->mapping['statusManager']) || !is_array($Model->mapping['statusManager'])) {
			return true;
		}

		$ret = true;
		$configs = array_keys($Model->mapping['statusManager']);
		foreach ($configs as $configName) {
			$ret &= $this->processStatusItem($Model, $processType, $configName);
		}
		
		// debug($this->runtime);

		if ($processType == self::PROCESS_TYPE_AFTER) {
			// $ret &= $this->migrateSystemRecords($Model);
		}

		return $ret;
	}

	/**
	 * Process a single status item and migrate created system records.
	 */
	private function processStatusItem(Model $Model, $processType, $configName, $settings = array()) {
		if (!$this->statusConfigExist($Model, $configName)) {
			return false;
		}

		$ret = true;

		$this->cacheConfig($Model, $configName);

		if (!empty($settings)) {
			$this->processSettings($Model, $configName, $settings);
		}

		$ret &= $this->processCustomValues($Model, $processType, $configName);

		$ret &= $this->processItemValue($Model, $processType, $configName);
		$ret &= $this->processToggles($Model, $processType, $configName);
		// $ret &= $this->processListeners($Model, $processType, $configName);
// debug($this->runtime[$Model->alias]);
		if ($processType == self::PROCESS_TYPE_AFTER) {
			$ret &= $this->migrateSystemRecords($Model, $configName);
		}
// debug($this->runtime[$Model->alias]);exit;
		$this->cacheConfig($Model, $configName);

		return $ret;
	}

	private function processSettings(Model $Model, $configName, $settings) {
		$settings = array_merge(
			array(
				'fn' => null,
				'disableToggles' => array(),
				'customToggles' => array(),
				'customValues' => array()
			),
			(array) $settings
		);

		if (!empty($settings['disableToggles'])) {
			foreach ($settings['disableToggles'] as $toggle) {
				unset($this->runtime[$Model->alias]['config'][$configName]['toggles'][$toggle]);
			}
		}

		if (!empty($settings['customToggles'])) {
			foreach ($settings['customToggles'] as $toggle) {
				$customToggle = $this->runtime[$Model->alias]['config'][$configName]['custom']['toggles'][$toggle];
				$this->runtime[$Model->alias]['config'][$configName]['toggles'][] = $customToggle;
			}
		}
		
		if (!empty($settings['customValues'])) {
			foreach ($settings['customValues'] as $customValue => $value) {
				$this->runtime[$Model->alias][$Model->id][$configName]['customValues'][$customValue] = $value;
			}
		}

		if (!empty($settings['fn'])) {
			$this->runtime[$Model->alias]['config'][$configName]['fn'] = $settings['fn'];
		}
	}

	/**
	 * Processes and saves custom values for later usage, e.g before deleting data.
	 */
	private function processCustomValues(Model $Model, $processType, $configName) {
		$ret = true;

		$customValues = $this->getConfig($Model, $configName, 'customValues');
		if (!empty($customValues[$processType])) {
			foreach ($customValues[$processType] as $customValue => $fn) {
				$value = $this->getResult($Model, $fn);
				$this->runtime[$Model->alias][$Model->id][$configName]['customValues'][$customValue] = $value;
			}
		}

		return $ret;
	}

	/**
	 * Processes given column value.
	 */
	private function processItemValue(Model $Model, $processType, $configName) {
		$ret = true;

		$column = $this->getConfig($Model, $configName, 'column');

		if ($processType == self::PROCESS_TYPE_BEFORE) {
			$value = $Model->field($column);
		}

		if ($processType == self::PROCESS_TYPE_AFTER) {
			$fn = $this->getConfig($Model, $configName, 'fn');

			// if a custom function for value retrieval is defined, call it, compare to the previous value and save if needed
			if (!empty($fn)) {
				// $value = $Model->{$fn}($Model->id);
				$value = $this->getResult($Model, $fn);

				$runtime = $this->getRuntime($Model, $configName);

				$previousValue = $this->runtimeDefaults['columnValue'];
				if (isset($runtime['columnValue'][self::PROCESS_TYPE_BEFORE])) {
					$previousValue = $runtime['columnValue'][self::PROCESS_TYPE_BEFORE];
				}

				// $previousValue = $this->runtime[$Model->alias][$Model->id][$configName]['columnValue'][self::PROCESS_TYPE_BEFORE];
				if ($value != $previousValue) {
					$_tmpData = $Model->data;

					// if Auditable behavior is enabled means this model is already migrated to a new logic
					if ($Model->Behaviors->loaded('AuditLog.Auditable')) {
						$opts = array(
							'validate' => false,
							'fieldList' => array($column),
							'callbacks' => false
						);
					}
					else {
						$opts = false;
					}

					// this triggers an empty update system log
					if (!method_exists($Model, 'getObjectStatusConfig')) {
						$ret &= $Model->saveField($column, $value, $opts);
					}

					$Model->data = $_tmpData;
					unset($_tmpData);
				}
			}
			// otherwise load a current value from the database
			else {
				$value = $Model->field($column);
			}
		}

		// save the value of the item for all processing types
		$this->runtime[$Model->alias][$Model->id][$configName]['columnValue'][$processType] = $value;

		return $ret;
	}

	/**
	 * Process and call a callback function for a column value, if defined.
	 *
	 * @param mixed $param Array to pass one or more parameters to a called function including item ID, string to call a function only with item ID.
	 */
	private function getResult(Model $Model, $param) {
		if (is_array($param)) {
			$fn = $param[0];

			if (!method_exists($Model, $fn)) {
				appError("METHOD NOT EXIST?!", E_WARNING);
				exit;
				return null;
			}

			// we shift the function name and leave only arguments
			array_shift($param);

			// prepend ID as a first argument
			array_unshift($param, $Model->id);

			return call_user_func_array(array($Model, $fn), $param);
		}

		return $Model->{$param}($Model->id);
	}

	/**
	 * Process a toggles for a config.
	 */
	private function processToggles(Model $Model, $processType, $configName) {
		$toggles = $this->getConfig($Model, $configName, 'toggles');

		if (empty($toggles)) {
			return true;
		}

		$ret = true;
		foreach ($toggles as $toggle) {
			$ret &= $this->processToggle($Model, $toggle, $processType, $configName);
		}

		return $ret;
	}

	/**
	 * Process a single toggle.
	 */
	private function processToggle(Model $Model, $toggle, $processType, $configName) {
		$this->normalizeToggle($toggle);

		if (empty($toggle['listeners']) && empty($toggle['message'])) {
			appError('Wrong toggle params');
			return false;
		}

		$ret = true;

		$column = $this->getConfig($Model, $configName, 'column');

		if ($processType == self::PROCESS_TYPE_BEFORE) {
			if (!empty($toggle['listeners'])) {
				// load before process for defined listeners to use in after process if toggle triggered
				$ret &= $this->processListeners($Model, $processType, $configName, $toggle['listeners']);
			}
		}

		if ($processType == self::PROCESS_TYPE_AFTER) {
			$runtime = $this->getRuntime($Model, $configName);

			$previousValue = $this->runtimeDefaults['columnValue'];
			if (isset($runtime['columnValue'][self::PROCESS_TYPE_BEFORE])) {
				$previousValue = $runtime['columnValue'][self::PROCESS_TYPE_BEFORE];
			}

			// $previousValue = $this->runtime[$Model->alias][$Model->id][$configName]['columnValue'][self::PROCESS_TYPE_BEFORE];
			$currentValue = $this->runtime[$Model->alias][$Model->id][$configName]['columnValue'][self::PROCESS_TYPE_AFTER];

			if ($previousValue != $currentValue && $currentValue == $toggle['value']) {

				// use listeners inside of a toggle if defined
				if (!empty($toggle['listeners'])) {
					$ret &= $this->processListeners($Model, $processType, $configName, $toggle['listeners']);
				}
				else {
					$message = $this->getMessage3($Model, $configName, $toggle['message'], $toggle['messageArgs']);
					// debug($toggle['message']);
					// debug($message);

					// $ret &= $Model->quickLogSave($Model->id, 2, $message);

					$ret &= $this->pushMessage($Model, $message, $configName);
					// $this->pushMessage2($Model, $message, $configName, $listener['model']);

					$ret &= $this->storeStatusChange($Model, $configName, $currentValue);
				}
			}
		}

		return $ret;
	}

	/**
	 * Process a group of listeners for a given model.
	 */
	private function processListeners(Model $Model, $processType, $configName, $listenerKeys = array()) {
		$listeners = $this->getConfig($Model, $configName, 'listeners');

		if (empty($listeners)) {
			return true;
		}

		$ret = true;
		foreach ($listeners as $listenerKey => $listener) {
			if (!in_array($listenerKey, $listenerKeys)) {
				continue;
			}

			$ret &= $this->processListener($Model, $listener, $processType, $configName);
		}

		return $ret;
	}

	/**
	 * Process a single listener for a given model.
	 */
	private function processListener(Model $Model, $listener, $processType, $configName) {
		$this->normalizeListener($listener);

		if (empty($listener['type']) || empty($listener['field']) || empty($listener['model'])) {
			appError('Wrong listener params');
			return false;
		}

		$ret = true;

		// get related data IDs for the current model, needed in all operations
		$relatedData = $this->getRelatedModelItems($Model, $listener['model']);

		// store data about a listener in all processings for later usage
		// $this->runtime[$Model->alias]['listeners'][$processType][$listener['model']] = $relatedData;
		$this->runtime[$Model->alias][$Model->id][$configName]['listeners'][$processType][$listener['model']] = $relatedData;

		// process listener before saving operation
		if ($processType == self::PROCESS_TYPE_BEFORE) {
			//?
		}

		// process listener after saving operation
		if ($processType == self::PROCESS_TYPE_AFTER) {

			if (isset($this->runtime[$Model->alias][$Model->id][$configName]['listeners'][self::PROCESS_TYPE_BEFORE][$listener['model']])) {
				$previousData = $this->runtime[$Model->alias][$Model->id][$configName]['listeners'][self::PROCESS_TYPE_BEFORE][$listener['model']];

				if ($listener['type'] == self::LISTENER_ADD) {
					// compare related added data for the listener
					$comparison = $this->compareData($Model, $listener['model'], $relatedData, $previousData);
				}

				if ($listener['type'] == self::LISTENER_REMOVE) {
					// compare related removed data for the listener
					$comparison = $this->compareData($Model, $listener['model'], $previousData, $relatedData);
				}

				if (!empty($comparison)) {
					// $column = $this->getConfig($Model, $configName, 'column');
						
					// save system records about what happened in this listener
					foreach ($comparison as $relatedId) {
						$Model->{$listener['model']}->id = $relatedId;

						$message = $this->getMessage2($Model, $listener);

						// store the message for later migration to related items
						// $this->settings[$Model->alias]['migrateSystemRecords'][$Model->id][$column][] = $message;		
						// $this->runtime[$Model->alias]['migrateRecords'][$Model->id][$configName][] = $message;

						// $ret &= $Model->quickLogSave($Model->id, 2, $message);
						$ret &= $this->pushMessage($Model, $message, $configName, $listener['model']);
						// $this->pushMessage2($Model, $message, $configName, $listener['model']);
					}
				}
			}
			
		}

		return $ret;
	}

	/**
	 * Save message and prepare for migration to other sections.
	 */
	private function pushMessage(Model $Model, $message, $configName, $relatedModel = null) {
		if (empty($message)) {
			return true;
		}

		if (!empty($relatedModel)) {
			$r = $Model->{$relatedModel};
			$this->runtime[$Model->alias]['migrateRecords'][$Model->id][$configName]['assoc'][$r->alias][$r->id][] = $message;
		}
		else {
			$this->runtime[$Model->alias]['migrateRecords'][$Model->id][$configName]['default'][] = $message;
		}
		
		return $Model->quickLogSave($Model->id, 2, $message);
	}

	private function pushMessage2(Model $Model, $message, $configName, $relatedModel) {
		$r = $Model->{$relatedModel};
		$this->runtime[$Model->alias]['migrateRecords2'][$Model->id][$configName][$r->alias][$r->id][] = $message;
		// return $Model->quickLogSave($Model->id, 2, $message);
	}

	private function getMessage3(Model $Model, $configName, $message, $messageArgs, $assocModel = null) {
		$parsedArgs = $this->getMessageArgs3($Model, $configName, $messageArgs, $assocModel);

		if ($parsedArgs === false) {
			return false;
		}

		array_unshift($parsedArgs, $message);
		$message = call_user_func_array('sprintf', $parsedArgs);

		return $message;
	}

	private function getMessageArgs3(Model $Model, $configName, $messageArgs, $assocModel = null) {
		$parsedArgs = array();
		foreach ($messageArgs as $index => $arg) {
			$parse = $this->getMessageArgument3($Model, $configName, $arg, $assocModel);
			if ($parse === false) {
				// appError($Model->alias . '->' . $Model->id . ' - status - ' . $configName . ' - message argument (' . $index . ') does not have a value.');
				return false;
			}

			$parsedArgs[] = $parse;
		}

		return $parsedArgs;
	}

	private function getMessageArgument3(Model $Model, $configName, $arg, $assocModel = null) {
		if (is_array($arg)) {
			if ($arg['type'] == 'fn') {
				if (isset($arg['fn'])) {
					return $this->getResult($Model, $arg['fn']);
				}
			}

			if ($arg['type'] == 'custom') {
				$runtime = $this->getRuntime($Model, $configName);

				if (isset($runtime['customValues'][$arg[0]])) {
					return $runtime['customValues'][$arg[0]];
				}
			}

			if ($arg['type'] == 'value') {
				return $arg[0];
			}

			return false;
		}

		$field = explode('.', $arg);
		if ($field[0] == self::MODEL_CURRENT) {
			$val = $Model->field($field[1]);

			return $val;
		}

		if (!empty($assocModel) && $field[0] == self::MODEL_RELATED) {
			$val = $Model->{$assocModel}->field($field[1]);
			
			return $val;
		}

		return false;
	}

	private function getMessage2(Model $Model, $listener) {
		/*if (empty($listener['messageArgs'])) {
			return false;
		}*/

		$parsedArgs = array();
		foreach ($listener['messageArgs'] as $arg) {
			$field = explode('.', $arg);

			if ($field[0] == self::MODEL_CURRENT) {
				$parsedArgs[] = $Model->field($field[1]);
			}

			if ($field[0] == self::MODEL_RELATED) {
				$parsedArgs[] = $Model->{$listener['model']}->field($field[1]);
			}
		}

		array_unshift($parsedArgs, $listener['message']);
		$message = call_user_func_array('sprintf', $parsedArgs);

		if (empty($message)) {
			return false;
		}

		return $message;
	}

	

	public function compareData(Model $Model, $assocModel, $data, $compare) {
		$relations = $Model->getAssociated();

		if ($relations[$assocModel] == 'hasAndBelongsToMany') {
			if (empty($data)) {
				return false;
			}

			$diff = array_diff((array) $data, $compare);
			if (empty($diff)) {
				return false;
			}
			else {
				return $diff;
			}
		}
	}

	public function prepareChecks(Model $Model) {
		if (!isset($Model->mapping['statusManager']) || empty($Model->mapping['statusManager'])) {
			return true;
		}

		$findColumns = array();
		foreach ($Model->mapping['statusManager'] as $column => $config) {
			if (!isset($Model->data[$Model->alias][$column])) {
				continue;
			}

			$findColumns[] = $column;
		}

		if (!empty($findColumns)) {
			$data = $Model->find('first', array(
				'conditions' => array(
					$Model->alias . '.id' => $Model->id
				),
				'recursive' => -1
			));

			$this->settings[$Model->alias]['dataBeforeCheck'] = $data[$Model->alias];
			// debug($this->settings[$Model->alias]['dataBeforeCheck']);
		}
	}

	/**
	 * Method assumes $Model->data contains desired values to check.
	 */
	public function manageChecks(Model $Model) {
		if (!isset($Model->mapping['statusManager']) || empty($Model->mapping['statusManager'])) {
			return true;
		}

		foreach ($Model->mapping['statusManager'] as $column => $config) {
			if (!isset($config['checks']) || empty($config['checks'])) {
				continue;
			}

			if (!isset($this->settings[$Model->alias]['dataBeforeCheck'][$column])) {
				continue;
			}

			$checks = $config['checks'];

			foreach ($checks as $checkItem) {
				$fn = $checkItem['fn'];
				$result = $this->getResult($Model, $fn);

				// if the condition to trigger is met
				if ($result == $checkItem['value']) {

					// if the value is changed from previous one
					if ($this->settings[$Model->alias]['dataBeforeCheck'][$column] != $result) {
						// get the relevant system message
						$message = $this->getMessage($Model, $checkItem);

						// store the message for later migration to related items
						$this->settings[$Model->alias]['migrateSystemRecords'][$Model->id][$column][] = $message;

						$ret = $Model->quickLogSave($Model->id, 2, $message);
						$ret &= $this->storeStatusChange($Model, $column, $result);
					}
				}
			}

		}
		// exit;

		return true;
	}

	/**
	 * Method stores a change log about what happend to given item.
	 */
	private function storeStatusChange(Model $Model, $configName, $value) {
		if (!isset($Model->hasMany['StatusTrigger'])) {
			$Model->bindModel(array(
				'hasMany' => array(
					'StatusTrigger' => array(
						'foreignKey' => 'foreign_key',
						'conditions' => array(
							'StatusTrigger.model' => $Model->name
						)
					)
				)
			));
		}

		$column = $this->getConfig($Model, $configName, 'column');

		$saveData = array(
			'model' => $Model->alias,
			'foreign_key' => $Model->id,
			'config_name' => $configName,
			'column_name' => $column,
			'value' => $value
		);

		$Model->StatusTrigger->create();
		$Model->StatusTrigger->set($saveData);
		return $Model->StatusTrigger->save();
	}

	/**
	 * Get a message with included related arguments.
	 */
	private function getMessage(Model $Model, $checkItem) {
		$args = $this->getMessageArgs($Model, $checkItem['messageArgs']);
		array_unshift($args, $checkItem['message']);
		$message = call_user_func_array('sprintf', $args);

		if (empty($message)) {
			return false;
		}

		return $message;
	}

	private function getMessageArgs(Model $Model, $params) {
		if (empty($params)) {
			return array();
		}

		$args = array();
		foreach ($params as $param) {
			if (is_array($param)) {
				$assocKey = array_keys($param);
				$assocModel = $Model->{$assocKey[0]};
				$column = $param[$assocKey[0]];

				$data = $assocModel->find('first', array(
					'conditions' => array(
						'id' => $assocModel->id
					),
					'fields' => array($column),
					'recursive' => -1
				));

				$args[] = $data[$assocKey[0]][$column];
			}
			else {
				$data = $Model->find('first', array(
					'conditions' => array(
						'id' => $Model->id
					),
					'fields' => array($param),
					'recursive' => -1
				));

				$args[] = $data[$Model->alias][$param];
			}
		}

		return $args;
	}

	private function migrateSystemRecords(Model $Model, $configName = null) {
		$ret = true;

		/**if (!empty($this->runtime[$Model->alias]['migrateRecords2'])) {
			$records = $this->runtime[$Model->alias]['migrateRecords2'];

			foreach ($records as $id => $columnData) {
				foreach ($columnData as $cName => $relatedModel) {
					foreach ($relatedModel as $relatedId => $messages) {
						if (!empty($configName) && $configName != $cName) {
							continue;
						}

						if (empty($Model->mapping['statusManager'][$cName]['migrateRecords'])) {
							continue;
						}

						$ret &= $Model->{$relatedModel}->quickLogSave($relatedId, 2, $messages);
					}

				}
			}

			if ($ret) {
				// set migrated records as done 
				$this->runtime[$Model->alias]['migrateRecords2'] = array();
			}
		}*/



			if (!empty($this->runtime[$Model->alias]['migrateRecords'])) {
				$records = $this->runtime[$Model->alias]['migrateRecords'];
// debug($this->runtime[$Model->alias]);
// return true;

				foreach ($records as $id => $columnData) {

					// foreach ($columnData['default'] as $)

					foreach ($columnData as $cName => $data) {
						if (!empty($configName) && $configName != $cName) {
							continue;
						}

						if (empty($Model->mapping['statusManager'][$cName]['migrateRecords'])) {
							continue;
						}	

						if (!empty($data['default'])) {
							$migrateTo = $Model->mapping['statusManager'][$cName]['migrateRecords'];
							foreach ($migrateTo as $migrateModel) {
								$relatedIds = $this->getRelatedModelItems($Model, $migrateModel);

								if (!empty($relatedIds)) {
									foreach ($relatedIds as $relatedId) {
										$ret &= $Model->{$migrateModel}->quickLogSave($relatedId, 2, $data['default']);
									}
								}
							}
						}

						if (!empty($data['assoc'])) {
							foreach ($data['assoc'] as $relatedModel => $relatedData) {
								foreach ($relatedData as $relatedId => $messages) {
										
									$ret &= $Model->{$relatedModel}->quickLogSave($relatedId, 2, $messages);
								}
							}
						}

					}
				}

				if ($ret) {
					// set migrated records as done 
					$this->runtime[$Model->alias]['migrateRecords'] = array();
				}
			}

		return $ret;
	}


	/**
	 * Get array of item IDs from associated model related to the current item.
	 * 
	 * @param  string $assocModel    Associated model from where we find related item IDs.
	 * @return array                 Array of item IDs. False if none found.
	 */
	private function getRelatedModelItems(Model $Model, $assocModel) {
		$relations = $Model->getAssociated();

		if ($relations[$assocModel] == 'hasAndBelongsToMany') {
			$with = $Model->hasAndBelongsToMany[$assocModel]['with'];
			$foreignKey = $Model->hasAndBelongsToMany[$assocModel]['foreignKey'];
			$assocForeignKey = $Model->hasAndBelongsToMany[$assocModel]['associationForeignKey'];

			$conds = $Model->hasAndBelongsToMany[$assocModel]['conditions'];
			if (empty($conds)) {
				$conds = array();
			}

			$conds = array_merge($conds, array(
				$with . '.' . $foreignKey => $Model->id
			));

			$data = $Model->{$with}->find('list', array(
				'conditions' => $conds,
				'fields' => array($with . '.' . $assocForeignKey),
				'recursive' => -1
			));

			return $data;
		}

		/*if ($relations[$assocModel] == 'belongsTo') {
			$foreignKey = $Model->belongsTo[$assocModel]['foreignKey'];
			if (isset($Model->data[$Model->name][$foreignKey])) {
				return (array) $Model->data[$Model->name][$foreignKey];
			}
		}*/

		return false;
	}

	/**
	 * Normalize config for a single config item.
	 */
	private function normalizeConfig(&$config) {
		$config = array_merge(
			array(
				'column' => null,
				'fn' => null,
				'migrateRecords' => array(),
				'customValues' => array(),
				'toggles' => array(),
				'listeners' => array(),
				'custom' => array(
					'toggles' => array()
				),

				'checks' => array()
			),
			(array) $config
		);

		return $config;
	}

	/**
	 * Normalize config for a single listener item.
	 */
	private function normalizeListener(&$listener) {
		$listener = array_merge(
			array(
				'type' => null,
				'field' => null,
				'model' => null,
				'message' => null,
				'messageArgs' => array(),
				'conditions' => array()
			),
			(array) $listener
		);

		return $listener;
	}

	/**
	 * Normalize config for a single toggle item.
	 */
	private function normalizeToggle(&$toggle) {
		$toggle = array_merge(
			array(
				'value' => null,
				'message' => null,
				'messageArgs' => array(),

				'listener' => null
			),
			(array) $toggle
		);

		return $toggle;
	}
}