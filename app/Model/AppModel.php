<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Model', 'Model');
App::uses('AdvancedFiltersQuery', 'Lib/AdvancedFilters');
App::uses('Validation', 'Utility');
App::uses('AppValidation', 'Utility');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	public function implementedEvents() {
		return parent::implementedEvents() + [
			'Model.afterAuditProperty' => array('callable' => 'afterAuditProperty', 'passParams' => true)
		];
	}

	public $mapping = null;

	public $actsAs = [
        'Containable'
    ];
	
	/**
	 * Section (model) label name used throughout the application.
	 * 
	 * @var mixed String for a model label name, false for $name.
	 */
	public $label = false;

	/**
	 * Group label for this section.
	 * 
	 * @var null|string
	 */
	protected $_group = null;

	/*
	 * static enum
	 * @access static
	 */
	 public static function sectionGroups($value = null) {
		$options = array(
			self::SECTION_GROUP_DEBUG => __('Debug'),
			self::SECTION_GROUP_ASSET_MGT => __('Asset Management'),
			self::SECTION_GROUP_RISK_MGT => __('Risk Management'),
			self::SECTION_GROUP_COMPLIANCE_MGT => __('Compliance Management'),
			self::SECTION_GROUP_CONTROL_CATALOGUE => __('Control Catalogue'),
			self::SECTION_GROUP_ORGANIZATION => __('Organization'),
			self::SECTION_GROUP_SECURITY_OPERATIONS => __('Security Operations'),
			self::SECTION_GROUP_PROGRAM => __('Program'),
		);
		return self::enum($value, $options);
	}
	const SECTION_GROUP_DEBUG = 'debug';
	const SECTION_GROUP_ASSET_MGT = 'asset';
	const SECTION_GROUP_RISK_MGT = 'risk';
	const SECTION_GROUP_COMPLIANCE_MGT = 'compliance';
	const SECTION_GROUP_CONTROL_CATALOGUE = 'control';
	const SECTION_GROUP_ORGANIZATION = 'organization';
	const SECTION_GROUP_SECURITY_OPERATIONS = 'security-operations';
	const SECTION_GROUP_PROGRAM = 'program';


	/**
	 * Group label for this section.
	 * 
	 * @return string
	 */
	public function group() {
		return self::sectionGroups($this->_group);
	}

	/**
	 * Groupped breadcumbs label for this section.
	 * 
	 * @return string
	 */
	public function groupLabel() {
		return sprintf('%s / %s', $this->group(), $this->label(['displayParents' => true]));
	}

	/**
	 * Get section (model) label name.
	 */
	public function label($options = array()) {
		$options = am(array(
			'singular' => false,
			'displayParents' => false
		), $options);

		$label = $this->_getLabel();
		
		if (!empty($options['singular'])) {
			$label = Inflector::singularize($label);
		}

		if ($options['displayParents'] === true && $this instanceof InheritanceInterface) {
			return sprintf('%s / %s', $this->{$this->parentModel()}->label($options), $label);
		}

		return $label;
	}

	/**
	 * Internal method to get a default label for this model.
	 * 
	 * @return string Label.
	 */
	protected function _getLabel() {
		if (!empty($this->label)) {
			$label = $this->label;
		}
		else {
			$modelName = get_class($this);

			// in case something went wrong
			if ($modelName == 'AppModel') {
				return getEmptyValue(false);
			}

			$label = Inflector::humanize(Inflector::tableize($modelName));
		}

		return $label;
	}

	/**
	 * Map a controller name to a model for features that requires it.
	 * 
	 * @var null|string
	 */
	public $mapController = null;

	/**
	 * Get a mapped controller name the current model.
	 * 
	 * @return string Controller name.
	 */
	public function getMappedController() {
		if ($this->mapController !== null) {
			return $this->mapController;
		}

		return controllerFromModel($this->alias);
	}

	/**
	 * Get index url params for current model.
	 * 
	 * @return array Url params.
	 */
	public function getSectionIndexUrl($params = []) {
		$defaultParams = [
			'plugin' => null,
			'controller' => $this->getMappedController(),
			'action' => 'index'
		];

		$url = am($defaultParams, $params);

		return $url;
	}

	/**
	 * Data for fields for a current model.
	 * Format:
	 * 'field_name' => array(
	 * 		'label' => __('My Field')
	 * )
	 * 
	 * @var array
	 */
	public $fieldData = array();

	// default group
	public $fieldGroupData = array('default' => array('label' => 'General'));

	/**
	 * Get Field Data property.
	 * 
	 * @param  string $field    Name of a field.
	 * @param  string $dataType Type of the data to get. Possible options are: 'label'.
	 * @param  array  $options  TBD.
	 * 
	 * @deprecated in favour of FieldDataEntity AppModel::getFieldDataEntity()
	 */
	public function fieldData($field, $dataType, $options = array()) {
		$options = am(array(
			'humanizeLabel' => true
		), $options);

		// fallback for @deprecated notice
		if ($this->hasFieldDataEntity($field)) {
			return $this->getFieldDataEntity($field)->getLabel();
		}

		if ($dataType == 'label' && !empty($options['humanizeLabel'])) {
			return Inflector::humanize($field);
		}

		return false;
	}

	/**
	 * Alias for AppModel::getFieldDataEntity()
	 * @deprecated
	 */
	public function getFieldData($key = null) {
		return $this->getFieldDataEntity($key);
	}

	public function getAuditIgnoredFields() {
		$ignore = isset($this->actsAs['AuditLog.Auditable']['ignore']) ? $this->actsAs['AuditLog.Auditable']['ignore'] : array();

		$ignore = am(array('workflow_status', 'workflow_owner_id'), $ignore);

		return $ignore;
	}

	/**
	 * static enums
	 * @access static
	 */
	public static function enum($value, $options, $default = '') {
		if ($value !== null && (is_string($value) || is_numeric($value))) {
			if (array_key_exists($value, $options)) {
				return $options[$value];
			}
			return $default;
		}
		return $options;
	}

	/**
	 * Get the current user
	 *
	 * Necessary for logging the "owner" of a change set,
	 * when using the AuditLog behavior.
	 *
	 * @return mixed|null User record. or null if no user is logged in.
	 */
	public function currentUser($value = null) {
		App::uses('AuthComponent', 'Controller/Component');
		if ($value !== null) {
			return AuthComponent::user($value);
		}
		
		return array(
			'id' => AuthComponent::user('id'),
			'description' => AuthComponent::user('full_name'),
		);
	}

	// soft delete handler
	public function exists($id = null) {
		if ($this->Behaviors->loaded('SoftDelete')) {
			return $this->existsAndNotDeleted($id);
		} else {
			return parent::exists($id);
		}
	}

	// soft delete handler
	public function delete($id = null, $cascade = true) {
		$result = parent::delete($id, $cascade);

		if ($result === false && $this->Behaviors->enabled('SoftDelete')) {
			$this->afterDelete();
			return (bool)$this->field($this->alias . '.deleted', array($this->alias . '.deleted' => 1));
		}
		return $result;
	}

	/**
	 * Public API method to build a joins query based on app's general rules.
	 * 
	 * @param  mixed $assocModel  Model name as string for which to generate this $query,
	 *                            or array of Model names to merge the resulting $query array.
	 * @return array              Query params.
	 */
	public function buildJoinsQuery($assocModel, $type = 'INNER') {
		App::uses('ObjectStatusBehavior', 'ObjectStatus.Model/Behavior');
		$ObjectStatusBehavior = new ObjectStatusBehavior();

		if (!is_array($assocModel)) {
			$assocModel = (array) $assocModel;
		}
		$assocModel = array_unique($assocModel);
		
		$joins = $conditions = [];
		foreach ($assocModel as $modelName) {
			$joins = array_merge($joins, $ObjectStatusBehavior->joinModels($this, $modelName, $type));
	        $conditions = array_merge($conditions, $ObjectStatusBehavior->getAdditionalConditions($this, $modelName));
	    }

		return [
			'conditions' => $conditions,
			'joins' => $joins
		];
	}

	public $_configDefaults = array(
		'titleColumn' => false,
		'indexController' => false,
		'logRecords' => false,
		'notificationSystem' => false,
		'statusReview' => false,
		'statusManager' => true
	);

	public $notificationSystem = array(
		/*
			formatted like 'MACRO' => array(
				'field' => 'Model.field',
				'name' => 'Field Name'
			)
		*/
		'macros' => array(),
		'customEmail' => false
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		// backwards compatibility for removed obsolete classes
		$this->setupMapping();

		if (!empty($this->fieldData)) {
			$this->Behaviors->load('FieldData.FieldData');
		}

		$this->initAdvancedFilter();
	}

	/**
	 * Compatibility reasons for deprecated mapping behavior, will be removed
	 * BEGIN.
	 */
	public static $logged_id, $modelClass, $currentAction;
	protected function setupMapping() {
		// AppSetting is loaded in bootstrap and needs to be excluded here
		if ($this->alias == 'AppSetting') {
			return;
		}

		if (!empty($this->mapping)) {
			$this->mapping = array_merge($this->_configDefaults, $this->mapping);
		}
		else {
			$this->mapping = $this->_configDefaults;
		}

		if ($this->mapping['logRecords']) {
			$this->Behaviors->load('SystemLog', array(
				'priority' => 9
			));
		}
		if ($this->mapping['notificationSystem']) {
			$this->Behaviors->load('NotificationsSystem', array(
				'priority' => 9
			));
		}
		if ($this->mapping['statusManager']) {
			$this->Behaviors->load('StatusManager', array(
				'priority' => 8
			));
		}
	}

	public function alterQueries($options = array()) {
		if (is_bool($options) && $options) {
			$options = array(
				'notificationSystem' => false
			);
		}
		else {
			$options = array_merge(
				array(
					'notificationSystem' => true
				),
				(array) $options
			);
		}

		if ($this->Behaviors->loaded('NotificationsSystem')) {
			$this->includeNotifications($options['notificationSystem']);
		}
	}

	public function resetQueries() {
		$this->alterQueries();
	}

	public static function includeToQuery($query, $modelName, $bindingModel) {
		if (!isset($query['contain'])) {
			if (!isset($query['recursive'])) {
				$query['recursive'] = 1;
			}
			else {
				if ($query['recursive'] === -1) {
					// ErrorHandler::handleError(E_NOTICE, '<strong>' . $modelName . '</strong> - recursive option in find query is set to -1! ' . $bindingModel . ' association requires at least recursive at 1.', __FILE__, __LINE__);
				}

				if ($query['recursive'] < 1) {
					$query['recursive'] = 1;
				}
			}
		}
		else {
			$c = is_array($query['contain']) ? $query['contain'] : array();
			if (!in_array($bindingModel, $c) || !isset($query['contain'][$bindingModel])) {
				$query['contain'][$bindingModel] = array();
			}
		}

		return $query;
	}
	/**
	 * Compatibility reasons for deprecated mapping behavior, will be removed
	 * END.
	 */

	protected function initAdvancedFilter() {
		if (empty($this->advancedFilter)) {
			return;
		}

		$this->advancedFilterOtherTab();

		$this->buildFilterArgs();
		$this->addOrderFields();

		$settingsDefault = array(
			'max_selection_size' => 15
		);

		$this->advancedFilterSettings = am($settingsDefault, $this->advancedFilterSettings);
	}

	private function advancedFilterOtherTab() {
		$allowTimeStamps = true;
		if (isset($this->advancedFilterSettings['include_timestamps']) && $this->advancedFilterSettings['include_timestamps'] === false) {
			$allowTimeStamps = false;
		}

		if ($allowTimeStamps && $this->schema('created') !== null) {
			$this->advancedFilter[__('Other')]['created'] = [
				'type' => 'date',
				'name' => __('Created on'),
				'show_default' => false,
				'filter' => [
					'type' => 'subquery',
					'method' => 'findComplexType',
					'findField' => "{$this->alias}.created",
					'field' => "{$this->alias}.id",
				],
			];
		}

		if ($allowTimeStamps && $this->schema('modified') !== null) {
			$this->advancedFilter[__('Other')]['modified'] = [
				'type' => 'date',
				'name' => __('Last Updated'),
				'show_default' => false,
				'filter' => [
					'type' => 'subquery',
					'method' => 'findComplexType',
					'findField' => "{$this->alias}.modified",
					'field' => "{$this->alias}.id",
				],
			];
		}

		if (!empty($this->getAssociated()['Comment'])) {
			$this->advancedFilter[__('Other')]['comment_message'] = array(
				'type' => 'text',
				'name' => __('Comment'),
				'show_default' => false,
				'filter' => array(
					'type' => 'subquery',
					'method' => 'findByComment',
					'field' => $this->alias . '.id',
				),
				'many' => true,
				'field' => 'Comment',
				'containable' => array(
					'Comment' => array(
						'fields' => array('message', 'created'),
						'User' => array(
							'fields' => array('full_name'),
						)
					)
				),
				// 'contain' => array(
				// 	'Comment' => array(
				// 		'message', 'created',
				// 	)
				// ),
				'outputFilter' => array('Comments', 'outputList')
			);
		}
		
		if (!empty($this->getAssociated()['Attachment'])) {
			$this->advancedFilter[__('Other')]['attachment_filename'] = array(
				'type' => 'text',
				'name' => __('Attachment filename'),
				'show_default' => false,
				'filter' => array(
					'type' => 'subquery',
					'method' => 'findByAttachment',
					'conditionFiled' => 'Attachment.filename',
					'field' => $this->alias . '.id',
				),
				'many' => true,
				'contain' => array(
					'Attachment' => array(
						'filename'
					)
				)
			);

			/*$this->advancedFilter[__('Other')]['attachment_description'] = array(
				'type' => 'text',
				'name' => __('Attachment description'),
				'show_default' => false,
				'filter' => array(
					'type' => 'subquery',
					'method' => 'findByAttachment',
					'conditionFiled' => 'Attachment.description',
					'field' => $this->alias . '.id',
				),
				'many' => true,
				'contain' => array(
					'Attachment' => array(
						'description'
					)
				)
			);*/
		}
	}

	protected function mergeAdvancedFilterFields($advancedFilter) {
		$this->advancedFilter = array_replace_recursive($this->advancedFilter, $advancedFilter);
		foreach ($this->advancedFilter as $pageKey => $page) {
			foreach ($page as $itemKey => $item) {
				if ($this->advancedFilter[$pageKey][$itemKey] === null) {
					unset($this->advancedFilter[$pageKey][$itemKey]);
				}
			}
		}
		$this->advancedFilter = array_filter($this->advancedFilter, function($var){return !is_null($var);});
	}

	/**
	 * returns array of models used in additional_actions fiter setting
	 * 
	 * @return array
	 */
	public function getAdvancedFilterAdditionalModels() {
		$additionalModels = array();
		
		if (!empty($this->advancedFilterSettings['additional_actions'])) {
			$additionalModels = array_keys($this->advancedFilterSettings['additional_actions']);
		}

		return $additionalModels;
	}

	private function buildFilterArgs() {
		$filterArgs = array();

		$filterArgs['id'] = array(
			'type' => 'value',
			'field' => array($this->alias . '.id'),
			'_name' => __('ID')
		);

		if ($this->schema('deleted') !== null) {
			$filterArgs['deleted'] = array(
				'type' => 'subquery',
				'method' => 'findComplexType',
				'field' => $this->alias . '.id',
				'findField' => $this->alias . '.deleted',
				'_config' => [
					'type' => 'select'
				]
			);
			$filterArgs['deleted_date'] = array(
				'type' => 'subquery',
				'method' => 'findComplexType',
				'field' => $this->alias . '.id',
				'findField' => $this->alias . '.deleted_date',
				'_config' => [
					'type' => 'date'
				]
			);
		}
		
		foreach ($this->advancedFilter as $fieldSet) {
			foreach ($fieldSet as $field => $data) {
				$filterArgs[$field . '__show'] = array();

				if (!empty($data['filter'])) {
					$filterArgs[$field] = $data['filter'];
					$filterArgs[$field]['_name'] = (!empty($data['name'])) ? $data['name'] : '';
					// if (!empty($data['comparison'])) {
						$filterArgs[$field . '__comp_type'] = array();
					// }
					
					if ($data['type'] == 'date') {
						$filterArgs[$field . '__use_calendar'] = array();
					}

					if ($this->getFilterNoneConds($field, $data)) {
						$filterArgs[$field . '__none'] = array();
					}
				}

				$tmpData = $data;
				unset($tmpData['filter']);
				$filterArgs[$field]['_config'] = $tmpData;
			}
		}

		if (!isset($filterArgs['created'])) {
			$filterArgs['created'] = array(
				'type' => 'subquery',
	            'method' => 'findComplexType',
				'field' => $this->alias . '.id',
				'findField' => $this->alias . '.created',
				'_config' => [
					'type' => 'date'
				]
			);
		}

		if (!empty($filterArgs)) {
			$filterArgs['_limit'] = array();
			$this->filterArgs = $filterArgs;
		}
	}

	private function addOrderFields() {
		foreach ($this->advancedFilter as $cat => $fieldSet) {
			foreach ($fieldSet as $field => $data) {
				if ((!empty($data['contain']) || !empty($data['containable'])) && empty($data['many'])) {
					if (!empty($data['contain'])) {
						reset($data['contain']);
						$model = key($data['contain']);
						if (!empty($this->getAssociated($model)['association']) 
							&& ($this->getAssociated($model)['association'] == 'belongsTo' || $this->getAssociated($model)['association'] == 'hasOne')
						) {
							$this->advancedFilter[$cat][$field]['order'] = $model . '.' . $data['contain'][$model][0];
						}
					}
				}
			}
		}

		if (!empty($filterArgs)) {
			$filterArgs['_limit'] = array();
			$this->filterArgs = $filterArgs;
		}
	}

	public function getFilterNoneConds($field, $data, $condValueType = true, $condSubqueryType = true) {
		$schema = $this->schema($field);

		$conds = ($data['type'] == 'multiple_select');

		$valueConds = false;
		if ($condValueType) {
			$valueConds = $this->getFilterNoneValueConds($field, $data);
		}

		$subqueryConds = false;
		if ($condSubqueryType) {
			$subqueryConds = $this->getFilterNoneSubqueryConds($field, $data);
		}
		$conds &= $subqueryConds || $valueConds;
		
		return $conds;
	}

	public function getFilterNoneSubqueryConds($field, $data) {
		return $data['filter']['type'] == 'subquery' && $data['filter']['method'] == 'findByHabtm';
	}

	public function getFilterNoneValueConds($field, $data) {
		$schema = $this->schema($field);

		return $data['filter']['type'] == 'value' && (isset($schema) && (!empty($schema['null'])));
	}

	/**
	 * @deprecated for use of original data convention.
	 */
	public function beforeItemSave($id) {
		$passId = false;
		if (!empty($this->id)) {
			$passId = $this->id;
		}

		$this->id = $id;

		$ret = true;
		if ($this->Behaviors->loaded('StatusManager')) {
			$ret &= $this->processStatusManagement('before');
		}

		$this->id = $passId;

		return $ret;
	}

	/**
	 * @deprecated for use of original data convention.
	 */
	public function afterItemSave($id) {
		//todo
		//if ($this->Behaviors->loaded('StatusManager')) {
			// $this->migrateSystemRecords();
		//}

		$passId = false;
		if (!empty($this->id)) {
			$passId = $this->id;
		}

		$this->id = $id;

		$ret = true;
		if ($this->Behaviors->loaded('StatusManager')) {
			$ret &= $this->processStatusManagement('after');
		}

		$this->id = $passId;

		return $ret;
	}

	/**
	 * @deprecated for use of original data convention.
	 */
	public function itemSave($data = null, $options = array()) {
		$options = am(array(
			'autocommit' => false,
			'fieldList' => array()
		), $options);

		$saveDefaults = array(
			'validate' => true, 'fieldList' => $options['fieldList'],
			'callbacks' => true, 'counterCache' => true,
			'atomic' => true
		);

		$this->set($data);
		$data = $this->data;
		// debug($data);exit;
		
		if (!empty($options['autocommit'])) {
			$dataSource = $this->getDataSource();
			$dataSource->begin();
		}

		// $event = new CakeEvent('Model.beforeSave', $this, array($saveDefaults));
		// $this->getEventManager()->dispatch($event);

		$ret = true;
		// $created = true;
		if (!empty($this->id)) {
			// $created = false;
			$ret &= $this->beforeItemSave($this->id);
			$ret &= $this->deleteJoins($this->id);
		}

		// $saveDefaults['callbacks'] = false;
		// $ret &= $saveData = $this->save(null, $saveDefaults);
		$ret &= $saveData = $this->save(null, true, $options['fieldList']);

		if (empty($saveData)) {
			if (!empty($options['autocommit'])) {
				$dataSource->rollback();
			}
			
			return false;
		}

		$ret &= $this->saveJoins($data);

		$ret &= $this->afterItemSave($this->id);

		// $event = new CakeEvent('Model.afterSave', $this, array($created, $saveDefaults));
		// $this->getEventManager()->dispatch($event);

		if (!empty($options['autocommit'])) {
			if ($ret) {
				$dataSource->commit();
			}
			else {
				$dataSource->rollback();
			}
		}

		return $ret;
	}

	/**
	 * @deprecated for use of original data convention.
	 */
	public function saveJoins($data = null) {
		return true;
	}

	/**
	 * @deprecated for use of original data convention.
	 */
	public function deleteJoins($id) {
		return true;
	}

	/**
	 * Wrapper function to save joined data using habtm join model.
	 *
	 * @param string $model Name of the model to join list of data with.
	 * @param mixed $list Array data of list of IDs to join with current item being saved or string field key to get from $this->data or NULL to search $this->data automatically.
	 * @param int $id Current item ID.
	 */
	public function joinHabtm($model, $list = null, $id = null, $skipCheck = false) {
		$assoc = $this->getAssociated($model);
		$with = $assoc['with'];
		$assocForeignKey = $assoc['associationForeignKey'];
		
		$list = $this->getJoinHabtmListData($model, $list);

		if (empty($list)) {
			return true;
		}

		$id = $this->getJoinHabtmId($id);

		$saveData = $this->getJoinHabtmSaveData($model, $list, $id);

		foreach ($saveData as $data) {
			if ($skipCheck === false) {
				// check if the related item we want to join exists, otherwise we would get foreign key constraint error
				$count = $this->{$model}->find('count', array(
					'conditions' => array(
						$model . '.' . $this->{$model}->primaryKey => $data[$assocForeignKey]
					),
					'recursive' => -1
				));

				if (empty($count)) {
					return false;
				}
			}

			$this->{$with}->create();
			if (!$this->{$with}->save($data)) {
				return false;
			}

		}

		return true;
	}
	protected function getJoinHabtmSaveData($model, $list, $id) {
		$assoc = $this->getAssociated($model);
		$assocForeignKey = $assoc['associationForeignKey'];

		$saveData = array();
		foreach ($list as $joinId) {
			$saveData[] = array(
				$assoc['foreignKey'] => $id,
				$assocForeignKey => $joinId
			);
		}

		return $saveData;
	}

	protected function getJoinHabtmListData($model, $list = null) {
		if (!empty($list) && is_array($list)) {
			return $list;
		}
		
		$assoc = $this->getAssociated($model);
		$assocForeignKey = $assoc['associationForeignKey'];

		//if field key is specified
		if (!empty($list) && is_string($list) && isset($this->data[$this->alias][$list])) {
			return $this->data[$this->alias][$list];
		}

		// search it automatically
		if ($list === null && isset($this->data[$this->alias][$assocForeignKey])) {
			return $this->data[$this->alias][$assocForeignKey];
		}

		return false;
	}

	protected function getJoinHabtmId($id = null) {
		if ($id === null) {
			$id = $this->id;
		}

		return $id;
	}

	/**
	 * Checks if related objects actually exists in database - used mainly for Import Tool feature.
	 * 
	 * @return boolean True if all exists or the check passed, False otherwise.
	 */
	public function checkRelatedExists($model, $list, $additionalConds = []) {
		$conds = is_array($list) && empty($list);
		$conds = $conds || (!is_array($list) && in_array($list, ['', null, false], true));
		if ($conds) {
			return true;
		}

		$conds = $this->_getRelatedExistsConds($model, $list, $additionalConds);
		$count = $this->{$model}->find('count', array(
			'conditions' => $conds,
			'recursive' => -1
		));

		return count($list) == $count;
	}

	/**
	 * Get conditions array parameter for checking existence of related objects.
	 * 
	 * @param  string $model           Model name.
	 * @param  array  $list            Array of provided IDs.
	 * @param  array  $additionalConds Additional conditions to consider.
	 * @return array                   Conditions.
	 */
	protected function _getRelatedExistsConds($model, $list, $additionalConds) {
		if (!is_array($list)) {
			$list = array($list);
		}

		$conds = [
			$model . '.' . $this->{$model}->primaryKey => $list
		];

		if (!empty($additionalConds)) {
			$conds = am($conds, $additionalConds);
		}

		return $conds;
	}

	/**
	 * Makes a field invalid for validation of related objects,
	 * letting user know what exactly is invalid in the list of data.
	 * 
	 * @param  string $model           Model name.
	 * @param  string $fieldName       Field name to invalidate with the proper message.
	 * @param  array  $list            List of items (foreign keys).
	 * @param  array  $additionalConds Additional conditions.
	 * @return void
	 */
	public function invalidateRelatedNotExist($model, $fieldName, $list, $additionalConds = []) {
		if (!$this->checkRelatedExists($model, $list, $additionalConds)) {
			$data = $this->{$model}->find('list', array(
				'conditions' => $this->_getRelatedExistsConds($model, $list, $additionalConds),
				'fields' => [
					$this->{$model}->escapeField($this->{$model}->primaryKey)
				],
				'recursive' => -1
			));

			// belongsTo relation has its own message
			if (!is_array($list)) {
				$message = __("Object which you are trying to use does not exist or is in a wrong format.\n\rThat is: <strong>%s</strong>", $list);
			}
			// hasAndBelongsToMany has also its own message
			else {
				$foreignKeys = array_keys($data);
				$nonExistent = array_diff($list, $foreignKeys);
				$message = __(
					"Some items you are trying to use does not exist.\n\rThese are: <strong>%s</strong>",
					implode(', ', $nonExistent)
				);
			}

			$this->invalidate($fieldName, $message);
		}
	}

	/**
	 * General method to fetch record's title.
	 */
	public function getRecordTitle($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.' . $this->primaryKey => $id
			),
			'fields' => array(
				$this->alias . '.' . $this->displayField
			),
			'recursive' => -1
		));

		$value = "";
		if (isset($data[$this->alias][$this->displayField])) {
			$value = $data[$this->alias][$this->displayField];
		}

		return getEmptyValue($value);
	}

	/**
	 * Wrapper function for getting error message given by the current model during a request or default one.
	 */
	public function getErrorMessage() {
		$msg = __('Error while deleting the data. Please try it again.');
		if (!empty($this->deleteMessage)) {
			$msg = $this->deleteMessage;
		}

		return $msg;
	}

	public function statusExpired($conditions = null) {
		$data = $this->find('count', [
			'conditions' => [
				$this->alias . '.id' => $this->id,
			] + $conditions,
			'recursive' => -1
		]);

		return (boolean) $data;
	}

	public function _statusExpiredReviews() {
		$data = $this->Review->find('count', [
			'conditions' => [
				'Review.foreign_key' => $this->id,
				'Review.model' => $this->alias,
				'Review.completed' => REVIEW_NOT_COMPLETE,
				'DATE(Review.planned_date) < NOW()'
			],
			'recursive' => -1
		]);

		return (boolean) $data;
	}

	/**
	 * @deprecated status, in favor of AppModel::_statusExpiredReviews()
	 */
	public function statusExpiredReviews($id = null, $model = null) {
		$id = ($id === null) ? $this->id : $id;
		$model = ($model === null) ? $this->alias : $model;

		$expiredReviews = $this->getExpiredReviews($id, $model);

		if (!empty($expiredReviews)) {
			return RISK_EXPIRED_REVIEWS;
		}

		return RISK_NOT_EXPIRED_REVIEWS;
	}

	public function getExpiredReviews($id, $model) {
		$today = date('Y-m-d', strtotime('now'));

		$expiredReviews = $this->Review->find('list', array(
			'conditions' => array(
				'Review.foreign_key' => $id,
				'Review.model' => $model,
				'Review.completed' => REVIEW_NOT_COMPLETE,
				'DATE(Review.planned_date) <' => $today
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		return $expiredReviews;
	}

	public function lastMissingReview($id, $model) {
		$today = date('Y-m-d', strtotime('now'));

		$expiredReviews = $this->Review->find('first', array(
			'conditions' => array(
				'Review.foreign_key' => $id,
				'Review.model' => $model,
				'Review.completed' => REVIEW_NOT_COMPLETE,
				'DATE(Review.planned_date) <' => $today
			),
			'fields' => array('id', 'planned_date'),
			'order' => array('Review.planned_date' => 'DESC'),
			'recursive' => -1
		));

		if (isset($expiredReviews['Review']['planned_date'])) {
			return $expiredReviews['Review']['planned_date'];
		}

		return false;
	}

	/**
	 * @deprecated status, in favor of BaseRisk::_statusRiskAboveAppetite()
	 */
	public function statusRiskAboveAppetite($id, $dbValue = false) {
		$isAboveAppetite = $this->find('first', array(
			'conditions' => array(
				$this->alias . '.id' => $id,
				$this->alias . '.residual_risk >' => $this->getRiskAppetite($id, $dbValue)
			),
			'recursive' => -1
		));

		if ($isAboveAppetite) {
			return RISK_ABOVE_APPETITE;
		}

		return RISK_NOT_ABOVE_APPETITE;
	}

	public function getRiskAppetite($id = null, $dbValue = false) {
		if ($dbValue == true) {
			$setting = ClassRegistry::init('Setting');
			$value = $setting->getVariable('RISK_APPETITE');
			return $value;
		}

		return RISK_APPETITE;
	}

	protected function getStatusMigration() {
		$arr = array();

		if (!empty($this->mapping['statusManager']) && is_array($this->mapping['statusManager'])) {
			foreach ($this->mapping['statusManager'] as $configName => $config) {
				$arr[$configName] = $config['migrateRecords'];
			}
		}

		return $arr;
	}

	protected function getStatusTemplate($configName, $options = array()) {
		App::uses('StatusTemplatesLib', 'Lib');

		return StatusTemplatesLib::getTemplate($this, $configName, $options);
	}

	protected function getStatusConfig($configName, $titleCol = 'title', $migrateRecords = array()) {
		$niceModel = parseModelNiceName($this->alias);

		$configs = array(
			'expiredReviews' => array(
				'column' => 'expired_reviews',
				'fn' => array('statusExpiredReviews', $this->name),
				'customValues' => array(
					'before' => array(
						'customValueLastMissingReview' => array('lastMissingReview', $this->name)
					)
				),
				'toggles' => array(
					array(
						'value' => RISK_EXPIRED_REVIEWS,
						'message' => __('The %s %s has a missing Review %s'),
						'messageArgs' => array(
							0 => array(
								'type' => 'value',
								$niceModel
							),
							1 => '%Current%.' . $titleCol,
							2 => array(
								'type' => 'fn',
								'fn' => array('lastMissingReview', $this->name),
							)
						)
					),
					array(
						'value' => RISK_NOT_EXPIRED_REVIEWS,
						'message' => __('The %s %s that has been with a missing Review %s has no longer missing reviews'),
						'messageArgs' => array(
							0 => array(
								'type' => 'value',
								$niceModel
							),
							1 => '%Current%.' . $titleCol,
							2 => array(
								'type' => 'custom',
								'customValueLastMissingReview'
							)
						)
					)
				)
			),
			'riskAboveAppetite' => array(
				'column' => 'risk_above_appetite',
				'fn' => 'statusRiskAboveAppetite',
				'customValues' => array(
					'before' => array(
						'originalRiskAppetite' => array('getRiskAppetite')
					),
					'after' => array(
						'dbRiskAppetite' => array('getRiskAppetite', true)
					)
				),
				'toggles' => array(
					'default' => array(
						'value' => RISK_ABOVE_APPETITE,
						'message' => __('The Risk %s Residual Score %s is higher than the Risk Appetite %s'),
						'messageArgs' => array(
							0 => '%Current%.title',
							1 => '%Current%.residual_risk',
							2 => array(
								'type' => 'value',
								ClassRegistry::init('Setting')->getVariable('RISK_APPETITE')
							)
						)
					)
				),
				'custom' => array(
					'toggles' => array(
						'RiskClassification' => array(
							'value' => RISK_ABOVE_APPETITE,
							'message' => __('The values of Risk Classification were updated and the Residual Score value %s for the Risk %s is above the Risk Appetite %s'),
							'messageArgs' => array(
								0 => '%Current%.residual_risk',
								1 => '%Current%.title',
								2 => array(
									'type' => 'value',
									ClassRegistry::init('Setting')->getVariable('RISK_APPETITE')
								)
							)
						),
						'Setting' => array(
							'value' => RISK_ABOVE_APPETITE,
							'message' => __('The Risk Appetite original value %s has been updated to a new value %s and the Risk %s Residual Score value %s is above'),
							'messageArgs' => array(
								0 => array(
									'type' => 'custom',
									'originalRiskAppetite'
								),
								//defined as custom value in settings controller
								1 => array(
									'type' => 'custom',
									'dbRiskAppetite'
								),
								2 => '%Current%.title',
								3 => '%Current%.residual_risk'
							)
						)
					)
				)
			),
			'SecurityServiceStatus' => array(
				'column' => 'security_service_type_id',
				'migrateRecords' => $migrateRecords,
				'toggles' => array(
					array(
						'value' => SECURITY_SERVICE_DESIGN,
						'message' => __('The %s %s has been tagged as being in Design'),
						'messageArgs' => array(
							0 => array(
								'type' => 'value',
								$niceModel
							),
							1 => '%Current%.' . $titleCol
						)
					),
					array(
						'value' => SECURITY_SERVICE_PRODUCTION,
						'message' => __('The %s %s has been tagged as being in Production'),
						'messageArgs' => array(
							0 => array(
								'type' => 'value',
								$niceModel
							),
							1 => '%Current%.' . $titleCol
						)
					)
				),
			),
			'ongoingCorrectiveActions' => array(
				'column' => 'ongoing_corrective_actions',
				'migrateRecords' => $migrateRecords,
				'fn' => 'statusOngoingCorrectiveActions',
				'customValues' => array(
					'before' => array(
						'mappedProjectsBefore' => array('mappedProjects')
					),
					'after' => array(
						'mappedProjectsAfter' => array('mappedProjects')
					),
				),

				'toggles' => array(
					'mappedProjects' => array(
						'value' => SECURITY_SERVICE_ONGOING_CORRECTIVE_ACTIONS,
						'message' => __('The Project %s has been mapped to the %s %s'),
						'messageArgs' => array(
							0 => array(
								'type' => 'custom',
								'mappedProjectsAfter'
							),
							1 => array(
								'type' => 'value',
								$niceModel
							),
							2 => '%Current%.' . $titleCol
						)
					),
					'unmappedProjects' => array(
						'value' => SECURITY_SERVICE_NOT_ONGOING_CORRECTIVE_ACTIONS,
						'message' => __('The Project %s that has been mapped to the %s %s has been removed'),
						'messageArgs' => array(
							0 => array(
								'type' => 'custom',
								'mappedProjectsBefore'
							),
							1 => array(
								'type' => 'value',
								$niceModel
							),
							2 => '%Current%.' . $titleCol
						)
					),
				),
				'custom' => array(
					'toggles' => array(
						'ProjectCompleted' => array(
							'value' => SECURITY_SERVICE_NOT_ONGOING_CORRECTIVE_ACTIONS,
							'message' => __('The Project %s that has been mapped to the %s %s has been tagged as Completed'),
							'messageArgs' => array(
								0 => array(
									'type' => 'custom',
									'mappedProjectsCompleted'
								),
								1 => array(
									'type' => 'value',
									$niceModel
								),
								2 => '%Current%.' . $titleCol
							)
						),
						'ProjectMappedToAudit' => array(
							'value' => SECURITY_SERVICE_ONGOING_CORRECTIVE_ACTIONS,
							'message' => __('The Project %s has been mapped to the failed audit %s of %s %s'),
							'messageArgs' => array(
								0 => array(
									'type' => 'custom',
									'mappedProjectsAudit'
								),
								1 => array(
									'type' => 'custom',
									'mappedProjectsAuditDate'
								),
								2 => array(
									'type' => 'value',
									$niceModel
								),
								3 => '%Current%.' . $titleCol
							)
						),
					)
				)
			),
		);

		return $configs[$configName];
	}

	public function mappedProjects($id) {
		$projectAssoc = $this->getAssociated('Project');

		if (empty($projectAssoc)) {
			return false;
		}

		$data = $this->{$projectAssoc['with']}->find('list', array(
			'conditions' => array(
				$projectAssoc['with'] . '.' . $projectAssoc['foreignKey'] => $id
			),
			'fields' => array('id', $projectAssoc['associationForeignKey']),
			'recursive' => -1
		));

		if (empty($data)) {
			return false;
		}

		$projects = $this->Project->find('list', array(
			'conditions' => array(
				'Project.id' => $data
			),
			'fields' => array('id', 'title'),
			'recursive' => -1
		));

		return implode(', ', $projects);
	}

	public function lastAuditDate($id, $model, $result = array(1, null), $field = 'planned_date') {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
		$model = $model . 'Audit';
		$foreignKey = $this->hasMany[$model]['foreignKey'];

		$audit = $this->{$model}->find('first', array(
			'conditions' => array(
				$model . '.' . $foreignKey => $id,
				$model . '.planned_date <=' => $today,
				$model . '.result' => $result
			),
			'fields' => array($model . '.id', $model . '.result', $model . '.planned_date'),
			'order' => array($model . '.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			return $audit[$model][$field];
		}

		return false;
	}

	public function lastMissingAudit($id, $model) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
		$model = $model . 'Audit';
		$foreignKey = $this->hasMany[$model]['foreignKey'];

		$audit = $this->{$model}->find('first', array(
			'conditions' => array(
				$model . '.' . $foreignKey => $id,
				$model . '.planned_date <=' => $today,
				$model . '.result' => null
			),
			'order' => array($model . '.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			$this->lastMissingAuditId = $audit[$model]['id'];
			return $audit[$model]['planned_date'];
		}

		return false;
	}

	public function lastMissingAuditResult($id, $model) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
		$model = $model . 'Audit';
		$foreignKey = $this->hasMany[$model]['foreignKey'];

		$audit = $this->{$model}->find('first', array(
			'conditions' => array(
				$model . '.' . $foreignKey => $id,
				$model . '.planned_date <=' => $today,
				$model . '.result' => array(1,0)
			),
			'order' => array(/*$model . '.planned_date' => 'DESC', */$model . '.modified' => 'DESC'),
			'recursive' => -1
		));

		if (!empty($audit)) {
			if ($audit[$model]['result']) {
				return __('Pass');
			}

			return __('Fail');

		}

		return false;
	}

	public function getStatusFilterOption() {
		return getStatusFilterOption();
	}

	public function getStatusFilterOptionInverted() {
		$arr = getStatusFilterOption();

		$k = array_keys($arr);
		$v = array_values($arr);

		$rv = array_reverse($v);

		$options = array_combine($k, $rv);
		return $options;
	}

	public function setComparisonType($data = array(), $filter) {
		$query = array();
		$field = (!empty($filter['field'])) ? $filter['field'] : $this->alias . '.' . $filter['name'];
		if ($filter['_config']['type'] == 'date') {
			$field = 'DATE(' . $field . ')';
		}
		$query[$field . ' ' . getComparisonTypes()[$filter['comp_type']]] = $data[$filter['name']];
		return $query;
	}

	/**
	 * Filter wrapper method for inherited statuses.
	 * example:
		...
		'filter' => array(
			'type' => 'subquery',
			'method' => 'findByInheritedStatus',
			'field' => 'Risk.id',
			'status' => array(

				// model where to search the status
				'model' => 'SecurityService',

				// column for the search
				'field' => 'ongoing_corrective_actions',

				// 1. option is to make the provided searched value negative (for custom uses)
				// 'negativeValue' => true,

				// 2. option is to make the searched value custom
				// 'customValue' => SECURITY_SERVICE_DESIGN
			)
		)
		...
	 */
	public function findByInheritedStatus($data = array(), $filterParams = array()) {
		$inherit = $filterParams['status'];
		// debug($data);
		// debug($filterParams);

		$assoc = $this->getAssociated($inherit['model']);
		// debug($assoc);exit;

		if (empty($assoc)) {
			AppError('Filter status model association does not exist!');
			return false;
		}

		if ($assoc['association'] != 'hasAndBelongsToMany') {
			AppError('Filter for inherited statuses does not support any other association than HABTM');
			return false;
		}

		$model = $inherit['model'];
		$with = $assoc['with'];
		$findValue = $data[$filterParams['name']];

		if (!empty($inherit['negativeValue'])) {
			$findValue = !$findValue;
		}

		if (!empty($inherit['customValue'])) {
			$findValue = $inherit['customValue'];
		}

		$comparisonOperator = false;
		if (!empty($inherit['comparisonOperator'])) {
			$comparisonOperator = $inherit['comparisonOperator'];
		}

		$this->{$with}->bindModel(array(
			'belongsTo' => array(
				$model
			)
		));
		$this->{$with}->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->{$with}->Behaviors->attach('Search.Searchable');

		$conditionField = $model . '.' . $inherit['field'];
		if (!empty($comparisonOperator)) {
			$conditionField .= ' ' . $comparisonOperator;
		}

		$query = $this->{$with}->getQuery('all', array(
			'conditions' => array(
				$conditionField => $findValue
			),
			'fields' => array(
				$with . '.' . $assoc['foreignKey']
			)
		));

		return $query;
	}

	public function findByHabtm($data = array(), $filterParams = array()) {
		$findByModel = $filterParams['findByModel'];

		$assoc = $this->getAssociated($findByModel);

		if (empty($assoc)) {
			AppError('Filter status model association does not exist!');
			return false;
		}

		if ($assoc['association'] != 'hasAndBelongsToMany') {
			AppError('Filter for inherited statuses does not support any other association than HABTM');
			return false;
		}

		$with = $assoc['with'];

		$this->{$with}->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->{$with}->Behaviors->attach('Search.Searchable');

		$value = $data[$assoc['associationForeignKey']];
		if (!is_array($value) && $value === ADVANCED_FILTER_MULTISELECT_NONE) {

			$queryChild = $this->{$with}->getQuery('all', array(
				'fields' => array(
					$with . '.' . $assoc['foreignKey']
				)
			));
			// debug($queryChild);
			$query = $this->getQuery('all', array(
				'conditions' => array(
					$this->alias . '.' . $this->primaryKey . ' NOT IN (' . $queryChild . ')'
				),
				'fields' => array(
					$this->alias . '.' . $this->primaryKey
				),
				'group' => array($this->alias . '.' . $this->primaryKey),
				'recursive' => -1
			));
		}
		else {
			$foreignKeyParam = $with . '.' . $assoc['foreignKey'];
			$assocKeyParam = $with . '.' . $assoc['associationForeignKey'];

			$havingCount = (!empty($filterParams['orCondition'])) ? 1 : count($value);

			$queryChild = $this->{$with}->getQuery('all', array(
				'fields' => array(
					$foreignKeyParam
				),
				'group' => $foreignKeyParam . ' HAVING COUNT(' . $foreignKeyParam . ')  >= ' . $havingCount
			));

			$conditions = array(
				$assocKeyParam => $value,
				$foreignKeyParam . ' IN (' . $queryChild . ')'
			);
			if (!empty($assoc['conditions'])) {
				$conditions = am($assoc['conditions'], $conditions);
			}
			if (!empty($filterParams['conditions'])) {
				$conditions = am($filterParams['conditions'], $conditions);
			}

			$query = $this->{$with}->getQuery('all', array(
				'conditions' => $conditions,
				'fields' => array(
					$foreignKeyParam
				),
				'group' => $foreignKeyParam
			));
		}

		return $query;
	}

	public function findByHabtmComparison($data = array(), $filter = array()) {
		$model = $filter['findByModel'];
		$assoc = $this->getAssociated($model);

		if (empty($assoc)) {
			AppError('Filter status model association does not exist!');
			return false;
		}

		if ($assoc['association'] != 'hasAndBelongsToMany') {
			AppError('Filter for inherited statuses does not support any other association than HABTM');
			return false;
		}

		$with = $assoc['with'];

		$this->{$with}->Behaviors->attach('Containable', array('autoFields' => false));
		$this->{$with}->Behaviors->attach('Search.Searchable');

		$query = $this->{$with}->getQuery('all', array(
			'conditions' => array(
				$filter['comparisonField'] . ' ' . getComparisonTypes()[$filter['comp_type']] => $data[$filter['name']]
			),
			'joins' => array(
				array(
					'table' => $this->{$model}->table,
					'alias' => $model,
					'type' => 'INNER',
				)
			),
			'fields' => array(
				$with . '.' . $assoc['foreignKey']
			)
		));

		return $query;
	}

	public function findByCustomField($data = array(), $filterParams = array()) {
		$alias = 'CustomFieldValue';
		$this->bindCustomFieldValues();
		$this->{$alias}->Behaviors->attach('Containable', array(
				'autoFields' => false
			)
		);
		$this->{$alias}->Behaviors->attach('Search.Searchable');

		$assoc = $this->getAssociated($alias);

		$arg = getCustomFieldArg($filterParams['customField']);
		$value = trim($data[$arg]);

		// default column condition extracted from associacion data
		$conds = $assoc['conditions'];

		// additional conditions
		$conds[$alias . '.custom_field_id'] = $filterParams['customField']['id'];
		
		if ($filterParams['customField']['type'] == CUSTOM_FIELD_TYPE_DATE) {
			$conds[$alias . '.value ' . getComparisonTypes()[$filterParams['comp_type']]] =  $value;
		}

		if ($filterParams['customField']['type'] == CUSTOM_FIELD_TYPE_DROPDOWN) {
			$conds[$alias . '.value'] = $value;
		}
		else {
			$conds[$alias . '.value LIKE'] = '%' . $value . '%';
		}

		$query = $this->{$alias}->getQuery('all', array(
			'conditions' => $conds,
			'fields' => array(
				$alias . '.' . $assoc['foreignKey']
			),
			'recursive' => -1
		));

		return $query;
	}

	public function findByComment($data = array(), $filter) {
		$this->Comment->Behaviors->attach('Containable', array('autoFields' => false));
		$this->Comment->Behaviors->attach('Search.Searchable');

		$query = $this->Comment->getQuery('all', array(
			'conditions' => array(
				'Comment.message LIKE' => '%' .  $data[$filter['name']] . '%',
				'Comment.model' => $this->alias
			),
			'fields' => array(
				'Comment.foreign_key'
			),
			'recursive' => -1
		));

		return $query;
	}

	public function findByAttachment($data = array(), $filter) {
		$this->Attachment->Behaviors->attach('Containable', array('autoFields' => false));
		$this->Attachment->Behaviors->attach('Search.Searchable');

		$query = $this->Attachment->getQuery('all', array(
			'conditions' => array(
				$filter['conditionFiled'] . ' LIKE' => '%' .  $data[$filter['name']] . '%',
				'Attachment.model' => $this->alias
			),
			'fields' => array(
				'Attachment.foreign_key'
			),
			'recursive' => -1
		));

		return $query;
	}

	public function getFilterRelatedData($fieldData = array()) {
		if (empty($fieldData['data']['findByModel'])) {
			AppError('findByModel parameter for data key is required.');
			return false;
		}

		$findByModel = $fieldData['data']['findByModel'];

		$assoc = $this->getAssociated($findByModel);
		// if ($assoc['association'] != 'belongsTo') {
		// 	AppError('Only belongsTo association is supported for this method');
		// 	return false;
		// }

		// debug($assoc);
		// debug($fieldData);exit;

		$list = $this->{$findByModel}->find('list', array(
			// 'fields' => array('ComplianceTreatmentStrategy.id', 'ComplianceTreatmentStrategy.name'),
			// 'order' => array('ComplianceTreatmentStrategy.name' => 'ASC'),
			'recursive' => -1
		));
		
		return $list;
	}

	public function getUsers()
	{
		$User = ClassRegistry::init('User');
		$User->virtualFields['full_name'] = 'CONCAT(User.name, " ", User.surname)';
		$users = $User->find('list', array(
			'conditions' => array(),
			'fields' => array('User.id', 'User.full_name'),
		));

		return $users;
	}

	public function getProjects() {
		$projects = $this->Project->find('list', array(
			'fields' => array('Project.id', 'Project.title')
		));

		return $projects;
	}

	protected function _findSelf($state, $query, $results = array()) {
		if ($state === 'before') {
			// debug($query['habtm']);exit;
			$habtm = $query['habtm'];
			if (is_string($habtm)) {
				$habtm = $this->getAssociated($habtm);
			}

			$habtm2 = $habtm;
			$habtm2['foreignKey'] = $habtm2['associationForeignKey'];
			$habtm2['associationForeignKey'] = $habtm['foreignKey'];

			$this->bindModel(array(
				'hasAndBelongsToMany' => array(
					'AssociatedNode1' => $habtm,
					'AssociatedNode2' => $habtm2
				)
			));
			// debug($habtm);debug($habtm2);

			$query['recursive'] = -1;
			
			if (!empty($query['habtmAssoc'])) {
				$query['contain']['AssociatedNode1'] = $query['habtmAssoc'];
				$query['contain']['AssociatedNode2'] = $query['habtmAssoc'];//debug($query);exit;
			}
			else {
				$query['contain'][] = 'AssociatedNode1';
				$query['contain'][] = 'AssociatedNode2';
			}

			return $query;
		}

		foreach($results as &$result) {
			if(isset($result['AssociatedNode1']) || isset($result['AssociatedNode2'])) {
				if (!empty($result['AssociatedNode1'])) {
					//debug($result);exit;
				}
				$associated_nodes = array();

				if(isset($result['AssociatedNode1'])) {
					foreach($result['AssociatedNode1'] as $associated_node) {
						$associated_nodes[] = $associated_node;
					}
				}

				if(isset($result['AssociatedNode2'])) {
					foreach($result['AssociatedNode2'] as $associated_node) {
						$associated_nodes[] = $associated_node;
					}
				}

				unset($result['AssociatedNode1']);
				unset($result['AssociatedNode2']);

				$result['SelfJoin'] = $associated_nodes;
			}
		}

		unset($result);

		return $results;
	}

	/**
	 * Get all HABTM related data and format it to optimize db queries for huge indexes.
	 */
	public function getAllHabtmData($joinModel, $query = array()) {
		$join = $this->getAssociated($joinModel);
		$assocForeignKey = $join['associationForeignKey'];
		$foreignKey = $join['foreignKey'];

		$this->{$joinModel}->bindModel(array(
			'hasMany' => array(
				$join['with']
			)
		));

		$query['contain'][$join['with']] = array('fields' => array($foreignKey));

		$data = $this->{$joinModel}->find('all', array(
			//'fields' => array('RiskClassification.name', 'RiskClassification.value', 'RiskClassification.criteria'),
			'contain' => $query['contain']
		));
		$formattedData = $joinIds = array();
		foreach ($data as $item) {
			$formattedData[$item[$joinModel]['id']] = $item;

			foreach ($item[$join['with']] as $assocData) {
				if (!isset($joinIds[$assocData[$foreignKey]])) {
					$joinIds[$assocData[$foreignKey]] = array();
				}

				$joinIds[$assocData[$foreignKey]][] = $assocData[$assocForeignKey];
			}
		}

		return array(
			'formattedData' => $formattedData,
			'joinIds' => $joinIds
		);
	}

	/**
	 * Adds a list validation for a field.
	 */
	protected function addListValidation($field, $list = array(), $message = null) {
		if (empty($message)) {
			$message = __('Selected option is not valid');
		}

		return $this->validator()->add($field, 'inList', array(
			'rule' => array('inList', $list),
			'message' => $message
		));
	}

	/**
	 * Custom validation method that checks and compare array_keys() from a callback method with the user input.
	 * Example:
	 * 'step_type' => array(
			'notBlank' => [
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'This field is required'
			],
			'callable' => [
				'rule' => ['callbackValidation', ['WorkflowStagesNextStage', 'stepTypes']],
				'message' => 'Incorrect step type'
			]
		)
	 */
	public function callbackValidation($checkValue, $callback) {
		if (!is_callable($callback)) {
			trigger_error('Validation callback is not a callable type');
		}

        $value = array_values($checkValue);
        $value = $value[0];

        $list = array_keys(call_user_func($callback));

        return Validation::inList($value, $list);
    }

	public function findByTags($data = array(), $filterParams = array()) {
		$this->Tag->Behaviors->attach('Containable', array('autoFields' => false));

		$this->Tag->Behaviors->attach('Search.Searchable');

		$query = $this->Tag->getQuery('all', array(
			'conditions' => array(
				'Tag.title' => $data[$filterParams['name']],
				'model' => $filterParams['model']
			),
			'fields' => array(
				'Tag.foreign_key'
			)
		));

		return $query;
	}

	public function getTags($data = array()) {
		$model = (!empty($data['filter']['model'])) ? $data['filter']['model'] : $this->alias;
		$tags = $this->Tag->find('list', array(
			'conditions' => array(
				'Tag.model' => $model,
			),
			'order' => array('Tag.title' => 'ASC'),
			'fields' => array('Tag.title'),
			'group' => array('Tag.title'),
			'recursive' => -1
		));

		$tags = array_combine($tags, $tags);
		return $tags;
	}

	/**
	 * Transforms the data array to save the HABTM relation.
	 *
	 * @param mixed null|array $keys Null to process all defined HABTM relations; or array of association keys to process.
	 */
	public function transformDataToHabtm($keys = null) {
		foreach (array_keys($this->hasAndBelongsToMany) as $model){
			if (is_array($keys) && !in_array($model, $keys)) {
				continue;
			}

			if(isset($this->data[$this->name][$model])){
				$this->data[$model][$model] = $this->data[$this->name][$model];
				unset($this->data[$this->name][$model]);
			}
		}
	}

	/**
	 * Storing the values for previous data that will be restored after successfull save because can cause conflicts
	 * on some other saves from other models.
	 * 
	 * @var array
	 */
	protected $_storedHabtmOriginalData = [];

	/**
	 * sets join conditions to data as join table extra fields
	 * 
	 * @param mixed null|array $keys Null to process all defined HABTM relations; or array of association keys to process.
	 */
	public function setHabtmConditionsToData($keys = null) {
		$this->restoreHabtmConditionalData();

		foreach (array_keys($this->hasAndBelongsToMany) as $model){
			if (is_array($keys) && !in_array($model, $keys)) {
				continue;
			}

			$data = array();
			if (isset($this->data[$model][$model])) {
				$data = $this->data[$model][$model];
			}
			elseif (isset($this->data[$model])) {
				$data = $this->data[$model];
			}

			if (!empty($data) && !empty($this->hasAndBelongsToMany[$model]['conditions'])) {
				$this->_storedHabtmOriginalData[$model] = $this->data[$model];
				
				$dataItems = array();
				foreach ((array) $data as $assocForeignKey) {
					$item = array();
					$item[$this->hasAndBelongsToMany[$model]['associationForeignKey']] = $assocForeignKey;
					foreach ($this->hasAndBelongsToMany[$model]['conditions'] as $field => $value) {
						$field = explode('.', $field);
						$item[end($field)] = $value;
					}
					$dataItems[] = $item;
				}
				
				if (!empty($this->data[$model][$model])) {
					$this->data[$model][$model] = $dataItems;
				}
				else {
					$this->data[$model] = $dataItems;
				}
			}
		}
	}

	/**
	 * When using conditional HABTM save, it might be the case of a additional save(s) on afterSave callback
	 * from within a Behavior for example, which then crashes the model.
	 */
	public function restoreHabtmConditionalData() {
		foreach ($this->_storedHabtmOriginalData as $model => $values) {
			$this->data[$model] = $values;
		}
	}
	
	public function findComplexType($data = array(), $filterParams = array()) {
		$queryBuilder = new AdvancedFiltersQuery();
		$query = $queryBuilder->get($this->alias, $filterParams, $data);

		return $query;
	}

	/**
     * default list finder
     * 
     * @return array
     */
    public function getList() {
        $data = $this->find('list', [
            'order' => [
                $this->alias . '.' . $this->displayField => 'ASC'
            ]
        ]);

        return $data;
    }

    /**
	 * Validates if there is at least one value selected within multiple HABTM fields.
	 * 
	 * @param  array  $checkModels HABTM Model aliases to check in $this->data
	 * @param  string $errorMessage error message
	 * @param  boolean $invalidateAll invalidate all check models
	 * @return bool                True if one or more values are selected, False if no items selected.
	 */
	public function validateMultipleFields($checkModels = [], $errorMessage, $invalidateAll = false) {
		$ret = false;
		foreach ($checkModels as $check) {
			if (!isset($this->data[$this->alias][$check])) {
				continue;
			}

			$val = (array) $this->data[$this->alias][$check];
			$val = array_filter($val);
			
			$ret = $ret || count($val);
		}

		if (!$ret) {
			$invalidateModels = ($invalidateAll) ? $checkModels : [reset($checkModels)];
			foreach ($invalidateModels as $model) {
				$this->invalidate($model , $errorMessage);
			}
		}

		return $ret;
	}

	/**
	 * Custom URL rule to allow non qualified domains.
	 */
	public function urlCustom($check) {
        $value = array_values($check);
        $value = $value[0];
        
        return AppValidation::url($value);
    }

    public function modelFullName() {
    	return (!empty($this->plugin)) ? "{$this->plugin}.{$this->name}" : $this->name;
    }

    public function modelFullAlias() {
    	return (!empty($this->plugin)) ? "{$this->plugin}.{$this->alias}" : $this->alias;
    }
}
