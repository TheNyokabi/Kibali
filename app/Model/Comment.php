<?php
App::uses('SidebarWidgetTrait', 'Model/Trait');

class Comment extends AppModel {
	use SidebarWidgetTrait;

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'message', 'model', 'foreign_key', 'user_id'
			)
		)
	);

	public $validate = array(
		'message' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Please enter a comment'
		)
	);

	public $belongsTo = array(
		'User'
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Comments');
		
		parent::__construct($id, $table, $ds);
	}

	public function beforeFind($query) {
		// we explicitly set only current user's comments for NotificationSystemItemLog
		if (isset($query['conditions']['Comment.model']) && $query['conditions']['Comment.model'] == 'NotificationSystemItemLog') {
			$currentUser = $this->currentUser();
			$query['conditions']['Comment.user_id'] = $currentUser['id'];
		}

		$query = $this->widgetBeforeFind($query);
		
		return $query;
	}

	public function afterSave($created, $options = array()) {
		if ($created) {
			// clear the index widget cache when added
			Cache::clearGroup('widget_data', 'widget_data');
		}

		//Project ObjectStatus trigger
		$this->triggerProjectObjectStatus($this->id);

		$this->logComment($this->id);
	}

	public function logComment($id) {
		$models = [
			'VendorAssessmentFeedback' => 'VendorAssessments.VendorAssessmentFeedback'
		];

		$data = $this->find('first', [
			'conditions' => [
				"{$this->alias}.id" => $id
			],
			'recursive' => -1
		]);

		if (empty($data) || !isset($models[$data['Comment']['model']])) {
			return false;
		}

		$Model = ClassRegistry::init($models[$data['Comment']['model']]);

		return $Model->logComment($data);
	}

	/**
	 * Triggers dependent Project statuses.
	 */
	public function triggerProjectObjectStatus($id) {
		$data = $this->find('first', [
			'conditions' => [
				'Comment.id' => $id
			],
			'recursive' => -1
		]);

		$triggerModels = [
			'Project', 'ProjectAchievement', 'ProjectExpense'
		];

		if (empty($data) || !in_array($data['Comment']['model'], $triggerModels)) {
			return false;
		}

		$Model = ClassRegistry::init($data['Comment']['model']);

		return $Model->triggerObjectStatus('no_updates', $data['Comment']['foreign_key']);
	}

	/**
	 * Retrieve comments associated with an item.
	 */
	public function getByItem($model, $foreign_key) {
		return $this->find('all', array(
			'conditions' => array(
				'Comment.model' => $model,
				'Comment.foreign_key' => $foreign_key
			),
			'order' => array('Comment.created' => 'DESC'),
			'recursive' => 0
		));
	}
	
}
