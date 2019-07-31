<?php
App::uses('ModelBehavior', 'Model');
App::uses('Hash', 'Utility');
App::uses('ObjectStatus', 'ObjectStatus.Model');

/**
 * ObjectStatusBehavior
 */
class ObjectStatusBehavior extends ModelBehavior {

/**
 * Default config
 *
 * @var array
 */
    protected $_defaults = [
        'modelClass' => 'ObjectStatus.ObjectStatus'
    ];

    public $settings = [];

    const STORAGE_SELF = 'self';
    const STORAGE_SHARED = 'shared';

/**
 * Setup
 *
 * @param Model $Model
 * @param array $settings
 * @throws RuntimeException
 * @return void
 */
    public function setup(Model $Model, $settings = []) {
        if (!isset($this->settings[$Model->alias])) {
            $this->settings[$Model->alias] = Hash::merge($this->_defaults, $settings);
        }

        $this->_loadFieldsSettings($Model);

        $this->_bindEvents($Model);
    }

/**
 * Normalized formating of fields config.
 *
 * @param Model $Model
 * @param array $config
 * @return array
 */
    protected function _normalizeFieldsSettings(Model $Model, $config) {
        $defaultFieldConfig = [
            'title' => __(''),
            'type' => 'warning',
            'callback' => false,
            'inherited' => false,
            'on' => false,
            'trigger' => [],
            'storageSelf' => true,
            'storageShared' => true,
        ];

        $normalConfig = [];

        $config = Hash::normalize($config);

        foreach ($config as $field => $settings) {
            $normalConfig[$field] = $settings;

            //field name
            if (!isset($normalConfig[$field]['field'])) {
                $normalConfig[$field]['field'] = $field;
            }

            //default event listener
            $normalConfig[$field]['on'][] = [
                'model' => $Model,
                'trigger' => $this->getTriggerName($field),
            ];

            $normalConfig[$field] = Hash::normalize($normalConfig[$field]);
            $normalConfig[$field] = Hash::merge($defaultFieldConfig, $normalConfig[$field]);
        }

        return $normalConfig;
    }

    public function bindObjectStatus($Model) {
        if ($Model->getAssociated('ObjectStatus') === null) {
            $Model->bindModel([
                'hasMany' => [
                    'ObjectStatus' => [
                        'className' => 'ObjectStatus.ObjectStatus',
                        'foreignKey' => 'foreign_key',
                        'conditions' => [
                            'ObjectStatus.model' => $Model->alias
                        ],
                        // 'fields' => array('id', 'model', 'foreign_key', 'value', 'custom_field_id')
                    ]
                ]
            ], false);
        }
    }

/**
 * Binds all defined trigger events.
 * 
 * @param Model $Model
 * @return void
 */
    protected function _bindEvents(Model $Model) {
        $fields = $this->_field($Model);

        foreach ($fields as $field) {
            if (!empty($field['on'])) {
                foreach ($field['on'] as $event) {
                    $EventModel = $this->_getModel($event['model']);
                    $EventModel->on($event['trigger'], [$Model, 'triggerObjectStatusDispatcher']);
                }
            }
        }
    }

/**
 * Return default trigger name for input field name.
 * 
 * @param  string $fieldName field name
 * @return string Trigger name.
 */
    public function getTriggerName($fieldName) {
        return "ObjectStatus.trigger.{$fieldName}";
    }

/**
 * Default trigger event callback for propagating trigger event.
 * 
 * @param Model $Model
 * @param CakeEvent $event
 * @return void
 */
    public function triggerObjectStatusDispatcher(Model $Model, CakeEvent $event) {
        $eventName = explode('.', $event->name());
        $fieldName = end($eventName);

        $field = $this->_field($Model, $fieldName);

        $joins = $this->_joinModels($Model, $event->subject->model->alias);
        $conditions = $this->_getAdditionalConditions($Model, $event->subject->model->alias, false);

        //find ids
        $ids = $Model->find('list', [
            'conditions' => [
                "{$event->subject->model->alias}.id" => $event->subject->id
            ] + $conditions,
            'fields' => [
                "{$Model->alias}.id"
            ],
            'joins' => $joins,
            'recursive' => -1
        ]);

        $this->triggerObjectStatus($Model, $fieldName, $ids);
    }

/**
 * Load status fields settings from model.
 * 
 * @param Model $Model
 * @return void
 */
    protected function _loadFieldsSettings(Model $Model) {
        if (!$Model->hasMethod('getObjectStatusConfig')) {
            return trigger_error('ObjectStatus: Model %s is missing object status configuration when loading it up.', $Model->alias);
        }

        $fieldsConfig = $Model->getObjectStatusConfig();
        $this->settings[$Model->alias]['fields'] = $this->_normalizeFieldsSettings($Model, $fieldsConfig);
    }

/**
 * Get list of fields that are managed by this feature.
 * Note: Settings may contain 'callback' parameter having recursively defined other 'callback' params in other models.
 *       Debugging or working with plain $settings should be avoided.
 *       
 * @param  Model  $Model Model.
 * @return array         Simple array of fields.
 */
    public function getObjectStatusFields(Model $Model) {
        return array_keys($this->getModelSettings($Model, true));
    }

    public function getModelSettings(Model $Model, $labels = false) {
        $list = [];

        $fields = array_keys($this->settings[$Model->alias]['fields']);
        foreach ($fields as $field) {
            $list[] = $field;
        }

        if ($labels === true) {
            $list = Hash::normalize($list);
            foreach (array_keys($list) as $field) {
                $f = $this->_field($Model, $field);
                if ($f['inherited']) {
                    unset($list[$field]);
                    continue;
                }

                $list[$field] = $f['title'];
            }
        }

        return $list;
    }

    /**
     * Alias to expose ObjectStatusBehavior::_field() method.
     */
    public function field(Model $Model, $fieldName = null) {
        return $this->_field($Model, $fieldName);
    }

/**
 * Returns status field/fields config.
 * 
 * @param Model $Model
 * @param string $fieldName field name
 * @return array field config
 */
    protected function _field(Model $Model, $fieldName = null) {
        if ($fieldName !== null) {
            return $this->settings[$Model->alias]['fields'][$fieldName];
        }
        return $this->settings[$Model->alias]['fields'];
    }

/**
 * ObjectStatus delete action.
 * 
 * @param Model $Model
 * @param array|string $statuses list of status fields to delete
 * @param mixed $ids list of item ids to trigger status
 * @return boolean success
 */
    public function deleteObjectStatus(Model $Model, $statuses = null, $ids = null) {
        return $this->_execute('_deleteField', $Model, $statuses, $ids);
    }

/**
 * ObjectStatus save action.
 * 
 * @param Model $Model
 * @param array|string $statuses list of status fields to save
 * @param mixed $ids list of item ids to trigger status
 * @return boolean success
 */
    public function triggerObjectStatus(Model $Model, $statuses = null, $ids = null) {
        return $this->_execute('_processField', $Model, $statuses, $ids);
    }

/**
 * Common execution handler which iterates all input data ($statuses, $ids) and starts action.
 * 
 * @param Model $Model
 * @param array|string $statuses list of status fields
 * @param mixed $ids list of item ids to trigger status
 * @return boolean success
 */
    protected function _execute($action, Model $Model, $statuses = null, $ids = null) {
        $fields = $this->_field($Model);
        $ret = true;

        if (is_string($statuses)) {
            $statuses = [$statuses];
        }

        $itemIds = [];
        if ($ids === null) {
            $itemIds = [$Model->id];
        }
        elseif (is_array($ids)) {
            $itemIds = $ids;
        }
        elseif (!empty($ids)) {
            $itemIds = [$ids];
        }

        foreach ($fields as $fieldName => $field) {
            if ($statuses !== null && !in_array($fieldName, $statuses)) {
                continue;
            }

            foreach ($itemIds as $id) {
                // $Model->create();
                $Model->id = $id;

                $ret &= $this->$action($Model, $fieldName);
            }
        }

        return $ret;
    }

/**
 * Compute status value, save, run triggers.
 *
 * @param Model $Model
 * @param string $fieldName field name
 * @return boolean success
 */
    protected function _processField(Model $Model, $fieldName) {
        $field = $this->_field($Model, $fieldName);
        $ret = true;

        //compute status
        if (!empty($field['callback']) || !empty($field['inherited'])) {
            $status = $this->computeStatus($Model, $fieldName);
            $ret &= $this->_save($Model, $fieldName, $status);
        }

        //execute all defined triggers
        $this->executeFieldTriggers($Model, $fieldName);

        return $ret;
    }

/**
 * Delete status, run triggers.
 *
 * @param Model $Model
 * @param string $fieldName field name
 * @return boolean success
 */
    protected function _deleteField(Model $Model, $fieldName) {
        $field = $this->_field($Model, $fieldName);

        //delete status
        $ret = $this->_delete($Model, $fieldName);

        //execute all defined triggers
        $this->executeFieldTriggers($Model, $fieldName);

        return $ret;
    }

/**
 * Run all defined triggers of field.
 * 
 * @param  Model  $Model
 * @param  string $fieldName field name
 * @return void
 */
    public function executeFieldTriggers(Model $Model, $fieldName) {
        $field = $this->_field($Model, $fieldName);

        foreach ($field['trigger'] as $trigger) {
            $TriggerModel = (is_a($trigger, 'Model')) ? $trigger : $this->_getModel($trigger['model']);
            $triggerName = (is_a($trigger, 'Model')) ? $this->getTriggerName($fieldName) : $trigger['trigger'];

            $TriggerModel->trigger($triggerName, [
                'id' => $Model->id,
                'model' => $Model
            ]);
        }
    }

/**
 * Call status callback to compute status value.
 * 
 * @param Model $Model 
 * @param string $fieldName field name
 * @return int
 */
    public function computeStatus(Model $Model, $fieldName) {
        $field = $this->_field($Model, $fieldName);

        if ($field['inherited']) {
            return (int) $this->getInheritedStatus($Model, $fieldName);
        }

        return (int) call_user_func($field['callback']);
    }

/**
 * Finds inherited status and returns its value.
 * 
 * @param  Model $Model
 * @param  string $fieldName field name
 * @return boolean Status value.
 */
    public function getInheritedStatus(Model $Model, $fieldName) {
        $field = $this->_field($Model, $fieldName);

        $ObjectStatus = ClassRegistry::init($this->settings[$Model->alias]['modelClass']);

        foreach ($field['inherited'] as $modelPath => $inheritField) {
            $models = explode('.', $modelPath);

            $WorkingModel = $Model;
            $joins = [];
            $conditions = [];

            //construct joins
            foreach ($models as $modelName) {
                $joins = array_merge($joins, $this->_joinModels($WorkingModel, $modelName));
                $conditions = array_merge($conditions, $this->_getAdditionalConditions($WorkingModel, $modelName));
                $WorkingModel = ClassRegistry::init($modelName);
            }

            //join ObjectStatus
            $joins[] = $this->_getJoin($ObjectStatus->tableName(), $ObjectStatus->alias, [
                "{$ObjectStatus->alias}.model" => $WorkingModel->alias,
                "{$ObjectStatus->alias}.foreign_key = {$WorkingModel->alias}.id",
                "{$ObjectStatus->alias}.name" => $inheritField,
                "{$ObjectStatus->alias}.status" => 1,
            ]);

            //find status
            $data = $Model->find('count', [
                'conditions' => [
                    "{$Model->alias}.id" => $Model->id
                ] + $conditions,
                'joins' => $joins,
                'recursive' => -1
            ]);

            if ($data) {
                return true;
            }
        }

        return false;
    }

/**
 * Alias for _getAdditionalConditions method to expose this to other classes.
 *
 * @param  Model $Model
 * @param  string $assoc Associated model name.
 * @return array Conditions.
 */
    public function getAdditionalConditions(Model $Model, $assoc) {
        return $this->_getAdditionalConditions($Model, $assoc);
    }

/**
 * Get soft delete conditions if behavior is loaded and join conditions if exists in association.
 * 
 * @param  Model $Model
 * @param  string $assoc Associated model name.
 * @param  boolean $softDelete Include softDelete conditions.
 * @return array Conditions.
 */
    protected function _getAdditionalConditions(Model $Model, $assoc, $softDelete = true) {
        $conditions = [];

        if ($softDelete && $Model->{$assoc}->Behaviors->loaded('SoftDelete')) {
            $conditions[] = "{$Model->{$assoc}->alias}.deleted = 0";
        }

        $assocData = $Model->getAssociated($assoc);

        if (!empty($assocData['conditions'])) {
            $conditions = array_merge($conditions, $assocData['conditions']);
        }

        //unset soft delete conditions from associated conditions data
        if (!$softDelete) {
            unset($conditions["{$assoc}.deleted"]);
        }

        return $conditions;
    }

    /**
     * Alias for _joinModels method to expose this to other classes.
     */
    public function joinModels(Model $Model, $assoc, $type = 'INNER') {
        return $this->_joinModels($Model, $assoc, $type);
    }

/**
 * Build join between $Model and associated model.
 * 
 * @param  Model $Model
 * @param  string $assoc Associated model name.
 * @param  string $type Join type.
 * @return array Join.
 */
    protected function _joinModels(Model $Model, $assoc, $type = 'INNER') {
        $joins = [];

        if (empty($Model->{$assoc})) {
            return $joins;
        }

        $AssocModel = $Model->{$assoc};
        $assocData = $Model->getAssociated($assoc);

        $joins[] = $this->_getJoin(
            $AssocModel->useTable,
            $assoc,
            [
                $this->_getJoinConditions($Model, $assoc)[0]
            ],
            $type
        );

        if ($assocData['association'] == 'hasAndBelongsToMany') {
            $joins[] = $this->_getJoin(
                $AssocModel->{$assocData['with']}->useTable,
                $assocData['with'],
                [
                    $this->_getJoinConditions($Model, $assoc)[1]
                ],
                $type
        );

            $joins = array_reverse($joins);
        }

        return $joins;
    }

/**
 * Build structured join array ready for query.
 * 
 * @param  string $table Table name.
 * @param  string $alias Model alias.
 * @param  array $conditions Join conditions.
 * @param  string $type Join type.
 * @return array Join.
 */
    protected function _getJoin($table, $alias, $conditions, $type = 'INNER') {
        return [
            'table' => $table,
            'alias' => $alias,
            'type' => $type,
            'conditions' => $conditions
        ];
    }

/**
 * Returns join conditons.
 * 
 * @param  Model $Model
 * @param  string $assoc Associated model name.
 * @return array Join conditions.
 */
    protected function _getJoinConditions(Model $Model, $assoc) {
        $conditions = [];

        if (empty($Model->{$assoc})) {
            return $conditions;
        }

        $AssocModel = $Model->{$assoc};
        $assocData = $Model->getAssociated($assoc);

        if ($assocData['association'] == 'belongsTo') {
            $conditions = [
                "{$Model->alias}.{$assocData['foreignKey']} = {$assoc}.id",
            ];
        }
        elseif ($assocData['association'] == 'hasOne' || $assocData['association'] == 'hasMany') {
            $conditions = [
                "{$Model->alias}.id = {$assoc}.{$assocData['foreignKey']}"
            ];
        }
        elseif ($assocData['association'] == 'hasAndBelongsToMany') {
            $conditions = [
                "{$assoc}.id = {$assocData['with']}.{$assocData['associationForeignKey']}",
                "{$assocData['with']}.{$assocData['foreignKey']} = {$Model->alias}.id",
            ];
        }

        return $conditions;
    }

/**
 * Save status value.
 *
 * @param Model $Model
 * @param string $fieldName field name
 * @param array $status status value
 * @return boolean success
 */
    protected function _save(Model $Model, $fieldName, $status) {
        $field = $this->_field($Model, $fieldName);
        $ret = true;

        if (!empty($Model->id)) {
            if ($field['storageSelf']) {
                $ret &= $this->_saveSelf($Model, $fieldName, $status);
            }

            if ($field['storageShared']) {
                $ret &= $this->_saveShared($Model, $fieldName, $status);
            }
        }

        return $ret;
    }

/**
 * Save status value to model(self) table.
 *
 * @param Model $Model
 * @param string $fieldName field name
 * @param array $status status value
 * @return boolean success
 */
    protected function _saveSelf(Model $Model, $fieldName, $status) {
        $field = $this->_field($Model, $fieldName);

        return $Model->save([$field['field'] => $status], [
            'validate' => false,
            'fieldList' => [$field['field']],
            'callbacks' => false
        ]);
    }

/**
 * Save status value to shared storage table.
 *
 * @param Model $Model
 * @param string $fieldName field name
 * @param array $status status value
 * @return boolean success
 */
    protected function _saveShared(Model $Model, $fieldName, $status) {
        $field = $this->_field($Model, $fieldName);

        $ObjectStatus = ClassRegistry::init($this->settings[$Model->alias]['modelClass']);
        
        return $ObjectStatus->saveStatus($field['field'], $status, $Model->alias, $Model->id);
    }

/**
 * Delete status.
 *
 * @param Model $Model
 * @param string $fieldName field name
 * @return boolean success
 */
    protected function _delete(Model $Model, $fieldName) {
        $field = $this->_field($Model, $fieldName);
        $ret = true;

        if (!empty($Model->id)) {
            $ret &= $this->_deleteShared($Model, $fieldName);
        }

        return $ret;
    }

/**
 * Delete status value from shared storage table.
 *
 * @param Model $Model
 * @param string $fieldName field name
 * @return boolean success
 */
    protected function _deleteShared(Model $Model, $fieldName) {
        $field = $this->_field($Model, $fieldName);

        $ObjectStatus = ClassRegistry::init($this->settings[$Model->alias]['modelClass']);
        
        return $ObjectStatus->deleteStatus($field['field'], $Model->alias, $Model->id);
    }

/**
 * Return model instance.
 * 
 * @param  mixed $model Model instance or definition for first param of ClassRegistry::init().
 * @return Model Model instance.
 */
    protected function _getModel($model) {
        if (is_a($model, 'Model')) {
            return $model;
        }

        return ClassRegistry::init($model);
    }

}

