<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Hash', 'Utility');

/**
 * Utils Plugin
 *
 * Utils Soft Delete Behavior
 *
 * @package utils
 * @subpackage utils.models.behaviors
 */
class SoftDeleteBehavior extends ModelBehavior {

/**
 * Default settings
 *
 * @var array $default
 */
	public $default = array(
		'deleted' => 'deleted_date',
		'atomic' => true,
	);

/**
 * Holds activity flags for models
 *
 * @var array $runtime
 */
	public $runtime = array();

/**
 * Holds atomic flag for model save operation
 *
 * @var bool $_atomic
 */
	protected $_atomic = true;

/**
 * Setup callback
 *
 * @param Model $model
 * @param array $settings
 */
	public function setup(Model $model, $settings = array()) {
		if (empty($settings)) {
			$settings = $this->default;
		} elseif (!is_array($settings)) {
			$settings = array($settings);
		}

		if (array_key_exists('atomic', $settings)) {
			$this->_atomic = $settings['atomic'];
			unset($settings['atomic']);
		}

		$error = __d('utils', 'SoftDeleteBehavior::setup(): model %s has no field!', $model->name);
		$fields = $this->_normalizeFields($model, $settings);
		foreach ($fields as $flag => $date) {
			if ($model->hasField($flag)) {
				if ($date && !$model->hasField($date)) {
					trigger_error($error . $date, E_USER_NOTICE);
					return;
				}
				continue;
			}
			trigger_error($error . $flag, E_USER_NOTICE);
			return;
		}

		$this->settings[$model->alias] = $fields;
		$this->softDelete($model, true);
	}

/**
 * Before find callback
 *
 * @param Model $model
 * @param array $query
 * @return array
 * @version e1.0.6.037 Added possibility to override SoftDeleteBehavior on/off configuration
 *          for single $query as in softDelete() method, using (bool) $query['softDelete'] = true/false.
 */
	public function beforeFind(Model $model, $query) {
		$runtime = $this->runtime[$model->alias];

		// temporary toggle using 'softDelete' key in the $query.
		if (isset($query['softDelete'])) {
			$runtime = $query['softDelete'];
		}

		if ($runtime) {
			if (!is_array($query['conditions'])) {
				$query['conditions'] = array();
			}
			$conditions = array_filter(array_keys($query['conditions']));

			$fields = $this->_normalizeFields($model);

			foreach ($fields as $flag => $date) {
				if (true === $runtime || $flag === $runtime) {
					if (!in_array($flag, $conditions) && !in_array($model->name . '.' . $flag, $conditions)) {
						$query['conditions'][$model->alias . '.' . $flag] = false;
					}

					if ($flag === $runtime) {
						break;
					}
				}
			}
			return $query;
		}
	}

/**
 * Check if a record exists for the given id
 *
 * @param Model $model
 * @param $id
 * @return mixed
 */
	public function existsAndNotDeleted(Model $model, $id) {
		if ($id === null) {
			$id = $model->getID();
		}
		if ($id === false) {
			return false;
		}
		$exists = $model->find('count', array(
			'contain' => array(),
			'recursive' => -1,
			'conditions' => array(
				$model->alias . '.' . $model->primaryKey => $id
			)
		));
		return ($exists ? true : false);
	}

/**
 * Before delete callback
 *
 * @param Model $model
 * @param bool $cascade
 * @return bool
 * @version e1.0.6.037 SoftDelete while deleting an object to trash,
 *          triggers once beforeDelete, no afterDelete but beforeSave and afterSave (for saving deleted flag column)
 */
	public function beforeDelete(Model $model, $cascade = true) {
		$runtime = $this->runtime[$model->alias];
		if ($runtime) {
			if ($model->beforeDelete($cascade)) {
				$this->delete($model, $model->id);
			}
			return false;
		}
		return true;
	}

/**
 * Mark record as deleted
 *
 * @param object $model
 * @param int $id
 * @return bool
 */
	public function delete($model, $id) {
		$runtime = $this->runtime[$model->alias];

		$data = array();
		$fields = $this->_normalizeFields($model);
		foreach ($fields as $flag => $date) {
			if (true === $runtime || $flag === $runtime) {
				$data[$flag] = true;
				if ($date) {
					$data[$date] = date('Y-m-d H:i:s');
				}
				if ($flag === $runtime) {
					break;
				}
			}
		}

		$record = $model->find('first', array(
			'fields' => $model->primaryKey,
			'conditions' => array($model->primaryKey => $id),
			'contain' => array(),
			'recursive' => -1
		));

		if (!empty($record)) {
			$model->set($model->primaryKey, $id);
			unset($model->data[$model->alias]['modified']);
			unset($model->data[$model->alias]['updated']);
			$result = $model->save(
				array($model->alias => $data),
				array('validate' => false, 'fieldList' => array_keys($data), 'atomic' => $this->_atomic)
			);
			if (!$result) {
				return false;
			}
		}

		return true;
	}

/**
 * Mark record as not deleted
 *
 * @param object $model
 * @param int $id
 * @return bool
 */
	public function undelete($model, $id) {
		$runtime = $this->runtime[$model->alias];
		$this->softDelete($model, false);

		$data = array();
		$fields = $this->_normalizeFields($model);
		foreach ($fields as $flag => $date) {
			if (true === $runtime || $flag === $runtime) {
				$data[$flag] = false;
				if ($date) {
					$data[$date] = null;
				}
				if ($flag === $runtime) {
					break;
				}
			}
		}

		$model->create();
		$model->set($model->primaryKey, $id);
		$result = $model->save(
			array($model->alias => $data),
			array('validate' => false, 'fieldList' => array_keys($data), 'atomic' => $this->_atomic)
		);
		$this->softDelete($model, $runtime);
		if ($result) {
			return true;
		}
		return false;
	}

/**
 * Enable/disable SoftDelete functionality
 *
 * Usage from model:
 * $this->softDelete(false); deactivate this behavior for model
 * $this->softDelete('field_two'); enabled only for this flag field
 * $this->softDelete(true); enable again for all flag fields
 * $config = $this->softDelete(null); for obtaining current setting
 *
 * @param object $model
 * @param mixed $active
 * @return mixed if $active is null, then current setting/null, or boolean if runtime setting for model was changed
 */
	public function softDelete($model, $active) {
		if (is_null($active)) {
			return isset($this->runtime[$model->alias]) ? $this->runtime[$model->alias] : null;
		}

		$result = !isset($this->runtime[$model->alias]) || $this->runtime[$model->alias] !== $active;
		$this->runtime[$model->alias] = $active;
		$this->_softDeleteAssociations($model, $active);
		return $result;
	}

/**
 * Returns number of outdated softdeleted records prepared for purge
 *
 * @param object $model
 * @param mixed $expiration anything parseable by strtotime(), by default '-90 days'
 * @return int
 */
	public function purgeDeletedCount($model, $expiration = '-90 days') {
		$runtime = $this->runtime[$model->alias];
		$this->softDelete($model, false);
		$result = $model->find('count', array(
				'conditions' => $this->_purgeDeletedConditions($model, $expiration),
				'recursive' => -1,
				'contain' => array()
			)
		);
		$this->runtime[$model->alias] = $runtime;
		return $result;
	}

/**
 * Purge table
 *
 * @param object $model
 * @param mixed $expiration anything parseable by strtotime(), by default '-90 days'
 * @return bool if there were some outdated records
 */
	public function purgeDeleted($model, $expiration = '-90 days') {
		$this->softDelete($model, false);
		$records = $model->find('all', array(
			'conditions' => $this->_purgeDeletedConditions($model, $expiration),
			'fields' => array($model->primaryKey),
			'recursive' => -1
		));
		if ($records) {
			foreach ($records as $record) {
				$model->delete($record[$model->alias][$model->primaryKey]);
			}
			return true;
		}
		return false;
	}

/**
 * Returns conditions for finding outdated records
 *
 * @param object $model
 * @param mixed $expiration anything parseable by strtotime(), by default '-90 days'
 * @return array
 */
	protected function _purgeDeletedConditions($model, $expiration = '-90 days') {
		$purgeDate = date('Y-m-d H:i:s', strtotime($expiration));
		$conditions = array();
		foreach ($this->settings[$model->alias] as $flag => $date) {
			$conditions[$model->alias . '.' . $flag] = true;
			if ($date) {
				$conditions[$model->alias . '.' . $date . ' <'] = $purgeDate;
			}
		}
		return $conditions;
	}

/**
 * Return normalized field array
 *
 * @param object $model
 * @param array $settings
 * @return array
 */
	protected function _normalizeFields($model, $settings = array()) {
		if (empty($settings)) {
			$settings = $this->settings[$model->alias];
		}
		$result = array();
		foreach ($settings as $flag => $date) {
			if (is_numeric($flag)) {
				$flag = $date;
				$date = false;
			}
			$result[$flag] = $date;
		}
		return $result;
	}

/**
 * Modifies conditions of hasOne and hasMany associations
 *
 * If multiple delete flags are configured for model, then $active = true doesn't
 * do anything - you have to alter conditions in association definition
 *
 * @param Model $model
 * @param mixed $active
 * @return void
 */
	protected function _softDeleteAssociations(Model $model, $active) {
		if (empty($model->belongsTo)) {
			return;
		}

		$fields = array_keys($this->_normalizeFields($model));
		$parentModels = Hash::extract($model->belongsTo, '{s}.className');
		$parentAliases = array_keys($model->belongsTo);

		$parentList = array_combine($parentAliases, $parentModels);
		foreach ($parentList as $parentAlias => $parentModel) {
			list($plugin, $modelClass) = pluginSplit($parentModel, true);
			App::uses($modelClass, $plugin . 'Model');
			if (!class_exists($modelClass)) {
				throw new MissingModelException(array('class' => $modelClass));
			}
			
			// overwrote object instances in some cases
			// $model->{$parentModel} = ClassRegistry::init(['class' => $parentModel, 'alias' => $parentAlias]);
			foreach (array('hasOne', 'hasMany') as $assocType) {
				if (empty($model->{$parentModel}->{$assocType})) {
					continue;
				}

				foreach ($model->{$parentModel}->{$assocType} as $assoc => $assocConfig) {
					$modelName = empty($assocConfig['className']) ? $assoc : @$assocConfig['className'];
					if ((!empty($model->plugin) && strstr($model->plugin . '.', $model->alias) === false ? $model->plugin . '.' : '') . $model->alias !== $modelName) {
						continue;
					}

					$conditions =& $model->{$parentModel}->{$assocType}[$assoc]['conditions'];
					if (!is_array($conditions)) {
						$model->{$parentModel}->{$assocType}[$assoc]['conditions'] = array();
					}

					$multiFields = 1 < count($fields);
					foreach ($fields as $field) {
						if ($active) {
							if (!isset($conditions[$field]) && !isset($conditions[$assoc . '.' . $field])) {
								if (is_string($active)) {
									if ($field == $active) {
										$conditions[$assoc . '.' . $field] = false;
									} elseif (isset($conditions[$assoc . '.' . $field])) {
										unset($conditions[$assoc . '.' . $field]);
									}
								} elseif (!$multiFields) {
									$conditions[$assoc . '.' . $field] = false;
								}
							}
						} elseif (isset($conditions[$assoc . '.' . $field])) {
							unset($conditions[$assoc . '.' . $field]);
						}
					}
				}
			}
		}
	}

/**
 * Soft delete all
 *
 * @param Model $model
 * @param array $conditions
 * @return void
 */
	public function softDeleteAll(Model $model, $conditions = array()) {
		$results = $model->find('all', array(
			'contain' => array(),
			'recursive' => -1,
			'conditions' => $conditions,
			'fields' => array(
				$model->alias . '.' . $model->primaryKey
			)
		));
		if (empty($results)) {
			return;
		}
		foreach ($results as $result) {
			$this->delete($model, $result[$model->alias][$model->primaryKey]);
		}
	}
}