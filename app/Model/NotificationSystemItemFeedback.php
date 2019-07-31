<?php
App::uses('Hash', 'Utility');

class NotificationSystemItemFeedback extends AppModel {
	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'notification_system_item_log_id', 'notification_system_item_object_id', 'user_id', 'comment'
			)
		)
	);

	public $validate = array(
		'comment' => array(
			'rule' => 'notBlank',
			'required' => true,
			'message' => 'Feedback comment is required.'
		)
	);

	public $belongsTo = array(
		'NotificationSystemItemLog'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'NotificationSystemItemFeedback'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'NotificationSystemItemFeedback'
			)
		)
	);

	/**
	 * We validate if feedback was provided at least inside the Comment module.
	 */
	public function beforeValidate($options = array()) {
		$submittedComments = $this->getLogComments();

		// if no comments submitted by the current user, validation fails.
		if (empty($submittedComments)) {
			return false;
		}

		$comments = Hash::extract($submittedComments, '{n}.Comment.message');
		$this->data['NotificationSystemItemFeedback']['comment'] = implode(PHP_EOL, $comments);

		return true;
	}

	public function beforeSave($options = array()) {
		$ret = true;
		$ret &= $this->migrateFeedback();

		return $ret;
	}

	protected function getLogComments() {
		$logCommentAssoc = $this->NotificationSystemItemLog->getAssociated('Comment');

		$conds = $logCommentAssoc['conditions'];
		$conds['Comment.foreign_key'] = $this->data['NotificationSystemItemFeedback']['notification_system_item_log_id'];
		$conds['Comment.user_id'] = $this->data['NotificationSystemItemFeedback']['user_id'];

		return $this->NotificationSystemItemLog->Comment->find('all', array(
			'conditions' => $conds,
			'recursive' => -1
		));
	}

	protected function getLogAttachments() {
		$logAttachmentAssoc = $this->NotificationSystemItemLog->getAssociated('Attachment');

		$conds = $logAttachmentAssoc['conditions'];
		$conds['Attachment.foreign_key'] = $this->data['NotificationSystemItemFeedback']['notification_system_item_log_id'];
		$conds['Attachment.user_id'] = $this->data['NotificationSystemItemFeedback']['user_id'];

		return $this->NotificationSystemItemLog->Attachment->find('all', array(
			'conditions' => $conds,
			'recursive' => -1
		));
	}

	/**
	 * Migrate provided feedback for NotificationSystemItemLog to relevant sections.
	 * 
	 * @return boolean	True on success, false otherwise.
	 */
	protected function migrateFeedback() {
		$ret = true;

		$submittedComments = $this->getLogComments();
		$submittedAttachments = $this->getLogAttachments();

		$migrateTo = $this->NotificationSystemItemLog->NotificationObject->find('first', array(
			'conditions' => array(
				'NotificationObject.id' => $this->data['NotificationSystemItemFeedback']['notification_system_item_object_id']
			),
			'fields' => array('NotificationObject.model', 'NotificationObject.foreign_key'),
			'recursive' => -1
		));

		$model = $migrateTo['NotificationObject']['model'];
		$foreign_key = $migrateTo['NotificationObject']['foreign_key'];

		$submittedComments = Hash::insert($submittedComments, '{n}.Comment.model', $model);
		$submittedComments = Hash::insert($submittedComments, '{n}.Comment.foreign_key', $foreign_key);

		$submittedAttachments = Hash::insert($submittedAttachments, '{n}.Attachment.model', $model);
		$submittedAttachments = Hash::insert($submittedAttachments, '{n}.Attachment.foreign_key', $foreign_key);

		$ret &= $this->Comment->saveAll($submittedComments);
		$ret &= $this->Attachment->saveAll($submittedAttachments);
		
		return $ret;
	}
}
