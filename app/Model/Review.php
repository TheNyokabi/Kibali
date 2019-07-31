<?php
App::uses('SectionBase', 'Model');
App::uses('BulkAction', 'BulkActions.Model');
App::uses('InheritanceInterface', 'Model/Interface');

class Review extends SectionBase implements InheritanceInterface {
    public $controllerName = 'Reviews';
    public $mapController = 'reviews';
    public $displayField = 'planned_date';

    const STATUS_COMPLETE = REVIEW_COMPLETE;
    const STATUS_NOT_COMPLETE = REVIEW_NOT_COMPLETE;
    
    protected $_relatedModel = null;

	public $mapping = array(
		'titleColumn' => false,
		'logRecords' => true,
        'notificationSystem' => true,
		'workflow' => false
	);

	public $workflow = array(
		// 'pullWorkflowData' => array('RiskReview', 'ThirdPartyRiskReview', 'BusinessContinuityReview', 'AssetReview', 'SecurityPolicyReview')
	);

    public $actsAs = array(
        'Search.Searchable',
        'AuditLog.Auditable',
        'Utils.SoftDelete',
        'Visualisation.Visualisation'
    );


	public $belongsTo = array(
		'User'
	);

	public $validate = array(
		'planned_date' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Please enter a date'
		),
		'actual_date' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'required' => true,
                'allowEmpty' => true,
                'on' => 'create'
            ),
            'updateEmpty' => array(
                'rule' => 'notBlank',
                'required' => 'update',
                'on' => 'update',
                'allowEmpty' => false,
                'message' => 'This field cannot be empty'
            ),
            'date' => array(
                'rule' => 'date',
                'message' => 'Enter a valid date'
            ),
            'past' => array(
                'rule' => 'validatePastDate',
                'message' => 'Choose a date in the present or past'
            )
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
        $this->label = __('Reviews');

        $this->fieldGroupData = array(
            'default' => array(
                'label' => __('General')
            ),
        );

        $this->fieldData = (!empty($this->fieldData)) ? $this->fieldData : array();

        $this->fieldData = am(array(
            'model' => array(
                'label' => __('Related Model'),
                'editable' => false,
                'hidden' => true
            ),
            'foreign_key' => array(
                'label' => __('Related Item ID'),
                'editable' => false,
                'hidden' => true
            ),
            'planned_date' => array(
                'label' => __('Planned date'),
                'editable' => false,
            ),
            'actual_date' => array(
                'label' => __('Actual date'),
                'editable' => true,
            ),
            'user_id' => array(
                'label' => __('Reviewer'),
                'editable' => false,
            ),
            'description' => array(
                'label' => __('Description'),
                'editable' => true,
            ),
            'completed' => array(
                'label' => __('Completed'),
                'type' => 'toggle',
                'editable' => true,
                // 'hidden' => true
            ),
            'version' => array(
                'label' => __('Version'),
                'editable' => false,
            ),
        ), $this->fieldData);

		parent::__construct($id, $table, $ds);
     
        $this->advancedFilter = array(
            __('General') => array(
                'parent' => array(
                ),
                'planned_date' => array(
                    'type' => 'date',
                    'comparison' => true,
                    'name' => __('Planned Date'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.planned_date',
                        'field' => $this->alias . '.id'
                    ),
                ),
                'actual_date' => array(
                    'type' => 'date',
                    'comparison' => true,
                    'name' => __('Actual Date'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.actual_date',
                        'field' => $this->alias . '.id'
                    ),
                ),
                'user_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Reviewer'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.user_id',
                        'field' => $this->alias . '.id'
                    ),
                    'data' => array(
                        'method' => 'getUsers',
                    ),
                    'contain' => array(
                        'User' => array(
                            'name', 'surname'
                        )
                    ),
                ),
                'description' => array(
                    'type' => 'text',
                    'name' => __('Description'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.description',
                        'field' => $this->alias . '.id'
                    ),
                ),
                'completed' => array(
                    'type' => 'multiple_select',
                    'name' => __('Completed'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->alias . '.completed',
                        'field' => $this->alias . '.id'
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true,
                    ),
                ),
            ),
        );

        $this->reviewFilterSettings = array(
            'bulk_actions' => array(
                BulkAction::TYPE_DELETE
            )
        );

        $this->bindModel(array(
            'hasMany' => array(
                'Attachment' => array(
                    'className' => 'Attachment',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'Attachment.model' => $this->alias
                    )
                ),
                'Comment' => array(
                    'className' => 'Comment',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'Comment.model' => $this->alias
                    )
                ),
                'SystemRecord' => array(
                    'className' => 'SystemRecord',
                    'foreignKey' => 'foreign_key',
                    'conditions' => array(
                        'SystemRecord.model' => $this->alias
                    )
                )
            )
        ), false);

        if ($this->_relatedModel !== null) {
            $this->bindModel(array(
                'belongsTo' => array(
                    $this->_relatedModel => array(
                        'foreignKey' => 'foreign_key',
                        'conditions' => array(
                            $this->alias . '.model' => $this->_relatedModel
                        )
                    )
                )
            ), false);

            $this->_group = $this->{$this->parentModel()}->_group;
        }
	}

    /**
     * Get index url params for current model.
     * 
     * @return array Url params.
     */
    public function getSectionIndexUrl($params = []) {
        return parent::getSectionIndexUrl([
            'controller' => 'reviews',
            'action' => 'filterIndex',
            $this->alias,
            '?' => [
                'advanced_filter' => 1
            ]
        ]);
    }

    // public function bindNode($item) {
    //     debug($item);exit;
    //     return array('model' => 'Group', 'foreign_key' => $user['User']['group_id']);
    // }

    /**
     * Get the parent model name, required for InheritanceInterface class.
     */
    public function parentModel() {
        return $this->_relatedModel;
    }

    public function parentNode() {
        return $this->visualisationParentNode('foreign_key');
    }

    // risk review field into field data collection for bulk editing while keeping the original logic
    protected function addRiskReviewField() {
        // review field
        // $Review = $this->{$this->parentModel()}->getFieldDataEntity('review');
        // if (!$this->hasFieldDataEntity('review')) {
        //     $this->getFieldCollection()->add('review', $Review)->toggleEditable(true);
        // }
    }

    public function beforeValidate($options = array()) {
        $ret = true;

        $ret &= parent::beforeValidate($options);

        // here its possible to update a related model object's data if the data are provided while saving a review
        // all is automatically associated
        if (isset($this->data[$this->_relatedModel])) {
            $relatedData = $this->data[$this->_relatedModel];

            if (isset($this->data[$this->alias]['foreign_key'])) {
                $foreignKey = $this->data[$this->alias]['foreign_key'];
            }
            else {
                $fk = $this->visualisationParentNode('foreign_key');
                $foreignKey = $fk[$this->_relatedModel]['id'];
            }

            $relatedModel = $this->{$this->_relatedModel};

            $relatedData['id'] = $foreignKey;
            $relatedModel->id = $foreignKey;
            $relatedModel->set($relatedData);

            $relatedOptions = [
                'fieldList' => array_keys($relatedData)
            ];

            $validates = $relatedModel->validates($relatedOptions);
            if (!$validates) {
                $this->invalidate($this->_relatedModel, true);
            }
            
            // $prevValidation = $relatedModel->validationErrors;
            // $ret &= $relatedModel->save(null, $relatedOptions);
            // $relatedModel->validationErrors = $prevValidation;
        }

        return $ret;
    }

    public function beforeFind($query) {
        if ($this->_relatedModel !== null) {
            $query['conditions'][$this->alias . '.model'] = $this->_relatedModel;
        }
        return $query;
    }

	public function beforeSave($options = array()) {
		$user = $this->currentUser();

        $ret = true;

		$ret &= $this->triggerStatuses('before');

		return $ret;
	}

	public function afterSave($created, $options = array()) {
		$ret = true;

        $data = $this->{$this->_relatedModel}->data;
        $exists = $this->{$this->_relatedModel}->exists($this->{$this->_relatedModel}->id);
        if (!empty($data) && $exists) {
            // trigger a save of the related object for statuses udpate?
            $relatedOptions = [
                'fieldList' => array_keys($data[$this->_relatedModel]),
                'validate' => false
            ];

            $ret &= $this->{$this->_relatedModel}->save($data, $relatedOptions);
        }

		$ret &= $this->triggerStatuses('after');

		return $ret;
	}

    public function triggerAssociatedObjectStatus($id) {
        $data = $this->find('first', [
            'conditions' => [
                'Review.id' => $id,
                'Review.deleted' => [true, false]
            ],
            'recursive' => -1
        ]);

        if (empty($data)) {
            return;
        }

        $AssocModel = ClassRegistry::init($data['Review']['model']);
        if ($AssocModel->Behaviors->enabled('ObjectStatus.ObjectStatus')) {
            $AssocModel->triggerObjectStatus('expired_reviews', $data['Review']['foreign_key']);
        }
    }

	public function beforeDelete($cascade = true) {
		$ret = true;
		$ret &= $this->triggerStatuses('before');

		return $ret;
	}

	public function afterDelete() {
		$ret = true;
		$ret &= $this->triggerStatuses('after', $this->triggeredItem);

		return $ret;
	}

	/**
	 * Trigger statuses reload in related items.
	 */
	private function triggerStatuses($processType, $item = false) {
		$ret = true;
		if (!empty($this->id)) {
            if ($item !== false) {
                $foreignKey = $item;
            }
            else {
                $fk = $this->visualisationParentNode('foreign_key');
                $foreignKey = $fk[$this->_relatedModel]['id'];
            }

            $this->triggeredItem = $foreignKey;

            $RelatedModel = $this->{$this->_relatedModel};

			$hasTrigger = $RelatedModel->Behaviors->enabled('StatusManager');
            $hasTrigger = $hasTrigger && $RelatedModel->mapping['statusManager']['expiredReviews'];

            // check for a trigger existence and more importantly if object exists and is available for modifications
            // as SoftDelete makes objects fallback into status where they exists in DB and Model->id is set but then
            // saving process evaluates them new object and tries to create it which always failed with fatal error
			if ($hasTrigger && $RelatedModel->exists($RelatedModel->id)) {
				$ret &= $RelatedModel->triggerStatus('expiredReviews', $foreignKey, $processType);
			}
		}

		return $ret;
	}

	protected function getMapping() {
	  return $this->mapping;
	}

    // same as associateReview() method but wrapper for auto-created completed today's review for a created related object.
    public function associateAutoCreatedReview($description = '') {
        $today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

        return $this->associateReview($today, [
            'actual_date' => $today,
            'completed' => self::STATUS_COMPLETE,
            'description' => $description
        ], false);
    }

    /**
     * The same as addReview() method but with model and foreign_key value automatically assigned
     * from $this->_relatedModel instace, assuming its having Model->id value during save operation for example.
     *  
     * @return boolean                True on success, False otherwise.
     */
    public function associateReview($plannedDate, $data = [], $skipDuplicate = true) {
        $Model = $this->{$this->_relatedModel};

        return $this->addReview(
            $Model->id,
            $plannedDate,
            $data,
            $skipDuplicate
        );
    }

    /**
     * Wrapper method that creates a new review record based on a Review model alias where its being saved,
     * for example SecurityPolicyReview->addReview() creates a review for SecurityPolicy model.
     *
     * note: migratin security policy new reviews into this should be tested properly because of its different save.
     *
     * @param  boolean $skipDuplicate Checks if record with the same planned_date already exists.
     *                                Skip saveing additional one if it does.
     */
    public function addReview($foreignKey, $plannedDate, $data = [], $skipDuplicate = true) {
        $user = $this->currentUser();

        $saveData = array(
            'model' => $this->_relatedModel,
            'foreign_key' => $foreignKey,
            'user_id' => $user['id'],
            'planned_date' => $plannedDate,
            'actual_date' => null,
            'completed' => self::STATUS_NOT_COMPLETE,
            'version' => null,
            // 'workflow_status' => WORKFLOW_APPROVED
        );

        if (is_array($data) && !empty($data)) {
            $saveData = am($saveData, $data);
        }

        if ($skipDuplicate === true && $this->reviewDuplicate($saveData['foreign_key'], $saveData['planned_date'])) {
            return true;
        }

        // actual date is wrongly validated as required when created
        $fieldList = array_keys($saveData);
        // unset($fieldList['actual_date']);
        $this->create();

        // this is here to handle correctly submission on bulks and /add forms
        // $this->id = $foreignKey;
        $this->set($saveData);
        
        $ret = $this->save($saveData, [
            'fieldList' => $fieldList
        ]);
        return $ret;
    }

    // check for a duplicated review entry by required planned_date field
    public function reviewDuplicate($foreignKey, $plannedDate) {
        return (bool) $this->find('count', [
            'conditions' => [
                $this->alias . '.foreign_key' => $foreignKey,
                $this->alias . '.planned_date' => $plannedDate
            ],
            'recursive' => -1
        ]);
    }

    /**
     * Builds a correct conventional Review model name out of a parent's object model name.
     * 
     * @param  string $model Model name.
     * @return string        Review model name.
     */
    public static function buildModelName($parentModel) {
        return $parentModel . 'Review';
    }

    /**
     * returns related model
     */
    public function getRelatedModel() {
        return $this->_relatedModel;
    }

    /**
     * Get last completed review.
     */
    public function getLastCompletedReview($foreignKey) {
        $data = $this->find('first', [
            'conditions' => [
                "{$this->alias}.foreign_key" => $foreignKey,
                "{$this->alias}.completed" => REVIEW_COMPLETE,
                "{$this->alias}.version IS NOT NULL",
                "{$this->alias}.actual_date IS NOT NULL"
            ],
            'order' => [
                "{$this->alias}.actual_date" => 'DESC',
                "{$this->alias}.version" => 'DESC'
            ],
            'recursive' => -1
        ]);

        return $data;
    }
}
