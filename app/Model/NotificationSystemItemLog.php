<?php
class NotificationSystemItemLog extends AppModel {
	public $actsAs = array(
		'Containable'
	);

	public $belongsTo = array(
		'NotificationObject' => array(
			'counterCache' => 'log_count',
			'foreignKey' => 'notification_system_item_object_id'
		)
	);

	public $hasMany = array(
		'NotificationSystemItemFeedback',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'NotificationSystemItemLog'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'NotificationSystemItemLog'
			)
		)
	);

	public function getCommentConditions() {
		$currentUser = $this->currentUser();
		$assoc = $this->getAssociated('Comment');

		$conds = $assoc['conditions'];
		$conds['Comment.user_id'] = $currentUser['id'];

		return $conds;
	}

	public function getAttachmentConditions() {
		$currentUser = $this->currentUser();
		$assoc = $this->getAssociated('Attachment');

		$conds = $assoc['conditions'];
		$conds['Attachment.user_id'] = $currentUser['id'];

		return $conds;
	}
	
	public function getRecordTitle($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'NotificationSystemItemLog.id' => $id
			),
			'fields' => array(
				'NotificationObject.model',
				'NotificationObject.foreign_key',
			),
			'recursive' => 0
		));

		$model = $data['NotificationObject']['model'];

		$objectTitle = getItemTitle($model, $data['NotificationObject']['foreign_key']);
		$modelLabel = getModelLabel($model, array('singular' => true));

		return sprintf('Feedback (%s - %s)', $modelLabel, $objectTitle);
	}
}
