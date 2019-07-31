<?php
/**
 * Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009-2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('ModelBehavior', 'Model');

/**
 * Taggable Behavior
 *
 * @package tags
 * @subpackage tags.models.behaviors
 */
class TaggableBehavior extends ModelBehavior {

/**
 * Settings array
 *
 * @var array
 */
	public $settings = array();

/**
 * Default settings
 *
 * separator              - separator used to enter a lot of tags, comma by default
 * field                  - the fieldname that contains the raw tags as string
 * tagAlias               - model alias for Tag model
 * tagClass               - class name of the table storing the tags
 * taggedAlias            - model alias for the HABTM join model
 * taggedClass            - class name of the HABTM association table between tags and models
 * foreignKey             - foreignKey used in the HABTM association
 * associationForeignKey  - associationForeignKey used in the HABTM association
 * cacheOccurrence        - cache the weight or occurence of a tag in the tags table
 * automaticTagging       - if set to true you don't need to use saveTags() manually
 * taggedCounter          - true to update the number of times a particular tag was used for a specific record
 * unsetInAfterFind       - unset 'Tag' results in afterFind
 * resetBinding           - reset the bindModel() calls, default is false
 * deleteTagsOnEmptyField - delete associated Tags if field is empty
 *
 * @var array
 */
	protected $_defaults = array(
		'separator' => ',',
		'field' => 'Tag',
		// 'tagAlias' => 'Tag',
		// 'tagClass' => 'Tags.Tag',
		// 'taggedAlias' => 'Tagged',
		// 'taggedClass' => 'Tags.Tagged',
		// 'foreignKey' => 'foreign_key',
		// 'associationForeignKey' => 'tag_id',
		// 'cacheOccurrence' => true,
		// 'automaticTagging' => true,
		// 'taggedCounter' => false,
		// 'unsetInAfterFind' => false,
		// 'resetBinding' => false,
		// 'deleteTagsOnEmptyField' => false,
	);

/**
 * Setup
 *
 * @param Model $model Model instance that behavior is attached to
 * @param array $config Configuration settings from model
 * @return void
 */
	public function setup(Model $model, $config = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->_defaults;
		}

		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], $config);
		// $this->settings[$model->alias]['withModel'] = $this->settings[$model->alias]['taggedClass'];
		$this->bindTagAssociations($model);
	}

/**
 * Bind tag assocations
 *
 * @param Model $model Model instance that behavior is attached to
 * @return void
 */
	public function bindTagAssociations(Model $model) {

		$model->bindModel(array(
			'hasMany' => array(
				'Tag' => array(
					'className' => 'Tag',
					'foreignKey' => 'foreign_key',
					'conditions' => array(
						'Tag.model' => $model->alias
					),
					'dependent' => ''
				)
			)
		), false);
	}


/**
 * Saves a string of tags
 *
 * @param Model $model Model instance that behavior is attached to
 * @param string $string comma separeted list of tags to be saved
 *     Tags can contain special tokens called `identifiers´ to namespace tags or classify them into catageories.
 *     A valid string is "foo, bar, cakephp:special". The token `cakephp´ will end up as the identifier or category for the tag `special´
 * @param mixed $foreignKey the identifier for the record to associate the tags with
 * @param bool $update True will remove tags that are not in the $string, false won't
 *     do this and just add new tags without removing existing tags associated to
 *     the current set foreign key
 * @return array
 */
	public function saveTags(Model $model, $string = null, $foreignKey = null, $update = true) {
		$tags = explode($this->settings[$model->alias]['separator'], $string);

		$source = array();
		if ($model->hasMethod('currentUser')) {
			$source = $model->currentUser();
		}

		$tmp = array();
		foreach ($tags as $title) {
			$tmp[] = array(
				'model' => $model->alias,
				'foreign_key' => $foreignKey,
				'title' => $title,
				'user_id' => $source['id']
			);

			// $model->Tag->create();
			// if (!$model->Tag->save($tmp)) {
			// 	return false;
			// }
		}

		$result = $model->Tag->saveMany($tmp, array(
			'validate' => false,
			'atomic' => false
		));

		// $result = $model->saveAll(array(
		// 	$model->alias => array('id' => $foreignKey),
		// 	'Tag' => $tmp
		// ), array('validate' => false, 'atomic' => false));

		return (bool) $result;
	}

	/*public function beforeSave(Model $model, $options = array()) {
		// return true;
		if (!isset($model->data[$model->alias][$this->settings[$model->alias]['field']])) {
			return true;
		}
		$field = $model->data[$model->alias][$this->settings[$model->alias]['field']];
		$hasTags = !empty($field);

		$ret = $this->deleteTags($model, $model->id);

		if ($hasTags) {
			$tags = explode($this->settings[$model->alias]['separator'], $field);

			$source = array();
			if ($model->hasMethod('currentUser')) {
				$source = $model->currentUser();
			}

			foreach ($tags as $tag) {
				$model->data['Tag'][] = array(
					'model' => $model->alias,
					'title' => $tag,
					'user_id' => $source['id']
				);
			}
		}
		return true;
// debug($model->data);exit;
		return $ret;
	}*/


	/*public function beforeSave(Model $model, $options = array()) {
		if (!isset($model->data[$model->alias][$this->settings[$model->alias]['field']])) {
			return true;
		}
		
		$this->deleteTags($model, $model->id);

		$field = $model->data[$model->alias][$this->settings[$model->alias]['field']];
		$hasTags = !empty($field);
		if ($hasTags) {
			$tags = explode($this->settings[$model->alias]['separator'], $field);

			$source = array();
			if ($model->hasMethod('currentUser')) {
				$source = $model->currentUser();
			}

			foreach ($tags as $tag) {
				$model->data['Tag'][] = array(
					'model' => $model->alias,
					'title' => $tag,
					'user_id' => $source['id']
				);
			}
			$model->data[$model->alias]['Tag'] = $model->data['Tag'];
		}

		// unset($model->data[$model->alias][$this->settings[$model->alias]['field']]);

		return true;
	}*/

	/**
	 * Get all available tags for a section.
	 * 
	 * @param  string $model      Model name.
	 * @param  string $foreignKey Foreign key.
	 * @param  bool   $indexedArr True to return indexed array of values.
	 */
	public function getTagged(Model $model, $foreignKey = null, $indexedArr = true) {
		$conds = array();
		if (!empty($model)) {
			$conds['model'] = $model->alias;
		}

		if (!empty($foreignKey)) {
			$conds['foreign_key'] = $foreignKey;
		}

		$data = $model->Tag->find('list', array(
			'conditions' => $conds,
			'order' => array('Tag.title' => 'ASC'),
			'fields' => array('Tag.id', 'Tag.title'),
			'group' => array('Tag.title'),
			'recursive' => -1
		));

		if ($indexedArr) {
			$data = array_values($data);
		}

		return $data;
	}

	public function afterSave(Model $model, $created, $options = array()) {
		if (!isset($model->data[$model->alias][$this->settings[$model->alias]['field']])) {
			return;
		}
		
		$this->deleteTags($model, $model->id);

		$field = $model->data[$model->alias][$this->settings[$model->alias]['field']];
		$hasTags = !empty($field);
		if ($hasTags) {
			$this->saveTags($model, $field, $model->id);
		}
	}

/**
 * Delete associated Tags if record has no tags and deleteTagsOnEmptyField is true
 *
 * @param Model $model Model instance that behavior is attached to
 * @param mixed $id Foreign key of the model, string for UUID or integer
 * @return void
 */
	public function deleteTags(Model $model, $id = null) {
		$options = array(
			'Tag.model' => $model->alias
		);

		if (!empty($id)) {
			$options['Tag.foreign_key'] = $id;
		}

		return $model->Tag->deleteAll($options, true, true);
	}

/**
 * afterFind Callback
 *
 * @param Model $model Model instance that behavior is attached to
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return array
 */
	/*public function afterFind(Model $model, $results, $primary = false) {
		extract($this->settings[$model->alias]);

		list($plugin, $class) = pluginSplit($tagClass);
		if ($model->name === $class) {
			return $results;
		}

		foreach ($results as $key => $row) {
			$row[$model->alias][$field] = '';
			if (isset($row[$tagAlias]) && !empty($row[$tagAlias])) {
				$row[$model->alias][$field] = $this->tagArrayToString($model, $row[$tagAlias]);
				if ($unsetInAfterFind == true) {
					unset($row[$tagAlias]);
				}
			}
			$results[$key] = $row;
		}
		return $results;
	}*/

}