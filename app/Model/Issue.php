<?php
class Issue extends AppModel {
	protected $issueParentModel = false;

	public $mapping = array(
		'titleColumn' => false,
		'logRecords' => true,
		'workflow' => false
	);

	public $workflow = array(
		// 'pullWorkflowData' => array('SecurityServiceIssue')
	);

	public $actsAs = array(
		'Containable'
	);

	public $belongsTo = array(
		'User'
	);

	public $validate = array(
		'date_start' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Please enter a date'
			),
			'date' => array(
				'rule' => 'date',
				'message' => 'Enter a valid date'
			)
		),
		'date_end' => array(
			'date' => array(
				'required' => true,
				'allowEmpty' => true,
				'rule' => 'date',
				'message' => 'Enter a valid date'
			)
		)
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'Issue'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'Issue'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'Issue'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Issues');

		parent::__construct($id, $table, $ds);

		if (!empty($this->issueParentModel)) {
			$this->bindModel(array(
				'belongsTo' => array(
					$this->issueParentModel => array(
						'foreignKey' => 'foreign_key'
					)
				)
			));
		}
	}

	public function beforeDelete($cascade = true) {
		$ret = true;
		if (!empty($this->id)) {
			// $this->bindIssueModel();
			$data = $this->getIssue();

			$this->parentModel = $data[$this->alias]['model'];
			$this->parentId = $data[$this->alias]['foreign_key'];
		}

		return $ret;
	}

	public function afterDelete() {
		$ret = true;
		if (isset($this->parentId) && isset($this->parentModel)) {
			if ($this->bindIssueModel()) {
				if ($this->{$this->issueParentModel}->statusConfigExist('issues')) {
					$ret &= $this->{$this->issueParentModel}->triggerStatus('issues', $this->parentId);
				}
			}
		}

		return $ret;
	}

	public function triggerAssociatedObjectStatus($id) {
		if (is_array($id) && !empty($id)) {
			$data = $id;
		}
		else {
			$data = $this->find('first', [
	            'conditions' => [
	                "{$this->alias}.id" => $id,
	            ],
	            'recursive' => -1
	        ]);
		}

        if (empty($data)) {
            return;
        }

        $AssocModel = ClassRegistry::init($data[$this->alias]['model']);
        if ($AssocModel->Behaviors->enabled('ObjectStatus.ObjectStatus')) {
            $AssocModel->triggerObjectStatus('control_with_issues', $data[$this->alias]['foreign_key']);
        }
    }

	public function afterSaveTrigger($model, $foreign_key) {
		$ret = true;
		$this->bindModel(array(
			'belongsTo' => array(
				$model => array(
					'foreignKey' => 'foreign_key'
				)
			)
		));
	
		if ($this->{$model}->statusConfigExist('issues')) {
			$ret &= $this->{$model}->triggerStatus('issues', $foreign_key);
		}

		return $ret;
	}

	protected function bindIssueModel() {
		// debug($this->issueParentModel);
		if (!empty($this->issueParentModel)) {
			$this->bindModel(array(
				'belongsTo' => array(
					$this->issueParentModel => array(
						'foreignKey' => 'foreign_key'
					)
				)
			));

			return true;
		}

		return false;
	}

	protected function getIssue() {
		$issue = $this->find('first', array(
			'conditions' => array(
				'id' => $this->id
			),
			'recursive' => -1
		));

		return $issue;
	}

	protected function getMapping() {
	  return $this->mapping;
	}
}