<?php
class NotificationObject extends AppModel {
	public $useTable = 'notification_system_items_objects';
	public $recursive = 0;

	public $actsAs = array(
		'Containable'
	);

	public $validate = array(
		'notification_system_item_id' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Notification is required.'
			),
			'naturalNumber' => array(
				'rule' => 'naturalNumber',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Field value is invalid.'
			),
		),
		'model' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'foreign_key' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => true,
				'message' => 'This field is required.'
			),
			'naturalNumber' => array(
				'rule' => 'naturalNumber',
				'required' => true,
				'allowEmpty' => true,
				'message' => 'Field value is invalid.'
			),
		)
	);

	public $belongsTo = array(
		'NotificationSystem' => array(
			'foreignKey' => 'notification_system_item_id'
		)
	);

	public $hasMany = array(
		'NotificationLog' => array(
			'className' => 'NotificationSystemItemLog',
			'foreignKey' => 'notification_system_item_object_id'
		),
		'NotificationCustomUser' => array(
			'className' => 'NotificationSystemItemCustomUser',
			'foreignKey' => 'notification_system_item_object_id'
		)
	);

	public function afterSave($created, $options = array()) {
		$ret = true;

		// this was buggy - moved to associate()
		$ret &= $this->resaveNotifications($this->id);

		if ($created) {
			// clear the index widget cache when added
			$ret &= Cache::clearGroup('widget_data', 'widget_data');
		}

		return $ret;
	}

	// we resave custom roles to make sure the data stored is correct.
	public function resaveNotifications($id) {
		$ret = true;

		$tmpObject = $this->find('first', array(
			'conditions' => array(
				'id' => $id
			),
			'recursive' => -1
		));

		$model = $tmpObject['NotificationObject']['model'];
		$foreignKey = $tmpObject['NotificationObject']['foreign_key'];

		$ret &= $this->NotificationSystem->saveCustomUsersByModel($model, $foreignKey);

		return $ret;
	}

	public function getOptions($model = null) {
		$this->bindObjectModel($model);

		$fields = array('id');
		if (is_string($this->Object->mapping['titleColumn'])) {
			$fields[] = $this->Object->mapping['titleColumn'];
		}

		return $this->Object->find('list', array(
			'fields' => $fields,
			'order' => array('Object.id' => 'ASC'),
			'recursive' => -1
		));
	}

	private function bindObjectModel($model) {
		$this->bindModel(array(
			'belongsTo' => array(
				'Object' => array(
					'className' => $model,
					'foreignKey' => 'foreign_key'
				)
			)
		));
	}

	/**
	 * Checks if a notification-object association exists.
	 *
	 * @return array Of the item data if exists, empty array otherwise.
	 */
	public function isAssociated($notificationId, $model, $foreign_key) {
		$conds = array(
			'NotificationObject.notification_system_item_id' => $notificationId,
			'NotificationObject.model' => $model,
			'NotificationObject.foreign_key' => $foreign_key
		);

		return $this->find('first', array(
			'conditions' => $conds,
			'fields' => array('*'),
			'recursive' => -1
		));
	}

	/**
	 * Associates an object to a notification.
	 *
	 * @return boolean True on successful save or when the association already exists.
	 */
	public function associate($notificationId, $model, $foreign_key, $overwrite = true) {
		$exists = $this->isAssociated($notificationId, $model, $foreign_key);

		if (!empty($exists)) {
			return true;
		}
		
		$saveData = array(
			'notification_system_item_id' => $notificationId,
			'model' => $model,
			'foreign_key' => $foreign_key
		);

		//temporary check
		$exists = $this->find('count', array(
			'conditions' => $saveData,
			'recursive' => -1
		));

		if ($exists) {
			return true;
		}

		$this->create();
		$ret = $this->save($saveData);
		// $ret &= $this->NotificationSystem->saveCustomUsersByModel($model, $foreign_key);
		
		if ($ret && !empty($foreign_key)) {
			$this->NotificationSystem->id = $notificationId;
			$notificationName = $this->NotificationSystem->field('name');

			$Class = ClassRegistry::init($model);
			$ret &= $Class->quickLogSave($foreign_key, 2, __('Notification %s has been associated with this item', $notificationName));
		}

		return $ret;
	}

	/**
	 * Deletes association table row between object and notification.
	 */
	public function remove($notificationId, $model, $foreign_key) {
		$exists = $this->isAssociated($notificationId, $model, $foreign_key);

		$ret = true;
		if (!empty($exists)) {
			$ret &= $this->delete($exists['NotificationObject']['id']);
			if ($ret && !empty($foreign_key)) {
				$this->NotificationSystem->id = $notificationId;
				$notificationName = $this->NotificationSystem->field('name');

				$Class = ClassRegistry::init($model);
				$ret &= $Class->quickLogSave($foreign_key, 2, __('Notification %s has been removed from this item', $notificationName));
			}
		}

		return $ret;
	}

	/**
	 * Change status to enabled on an existing notification object and store a system record.
	 * 
	 * @param  mixed $id NotificationObject ID.
	 * @return bool    Success if true.
	 */
	public function enableObject($id) {
		if (is_array($id)) {
			$ret = true;
			foreach ($id as $val) {
				$ret &= $this->enableObject($val);
			}

			return $ret;
		}

		$this->id = $id;
		$this->set(array(
			'id' => $id,
			'status' => NOTIFICATION_OBJECT_ENABLED
		));
		$ret = $this->save(null, array('validate' => false));

		if (!$ret) {
			return false;
		}

		$tmpObject = $this->find('first', array(
			'conditions' => array(
				'id' => $id
			),
			'recursive' => -1
		));

		$notification = $this->NotificationSystem->find('first', array(
			'conditions' => array(
				'id' => $tmpObject['NotificationObject']['notification_system_item_id']
			),
			'fields' => array('id', 'name'),
			'recursive' => -1
		));

		$model = $tmpObject['NotificationObject']['model'];
		$foreignKey = $tmpObject['NotificationObject']['foreign_key'];

		$Class = ClassRegistry::init($model);

		$ret &= $Class->quickLogSave($foreignKey, 2, __('Notification %s has been enabled for this item', $notification['NotificationSystem']['name']));

		return $ret;
	}

	/**
	 * Change status to disabled on an existing notification object and store a system record.
	 * 
	 * @param  int $id NotificationObject ID.
	 * @return bool    Success if true.
	 */
	public function disableObject($id) {
		$this->id = $id;
		$this->set(array(
			'id' => $id,
			'status' => NOTIFICATION_OBJECT_DISABLED
		));
		$ret = $this->save(null, array('validate' => false));

		if (!$ret) {
			return false;
		}

		$tmpObject = $this->find('first', array(
			'conditions' => array(
				'id' => $id
			),
			'recursive' => -1
		));

		$notification = $this->NotificationSystem->find('first', array(
			'conditions' => array(
				'id' => $tmpObject['NotificationObject']['notification_system_item_id']
			),
			'fields' => array('id', 'name'),
			'recursive' => -1
		));

		$model = $tmpObject['NotificationObject']['model'];
		$foreignKey = $tmpObject['NotificationObject']['foreign_key'];
		$Class = ClassRegistry::init($model);

		$ret &= $Class->quickLogSave($foreignKey, 2, __('Notification %s has been disabled for this item', $notification['NotificationSystem']['name']));

		return $ret;
	}

	public function enableAll($notificationId) {
		$items = $this->find('list', array(
			'conditions' => array(
				'notification_system_item_id' => $notificationId
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		return $this->enableObject($items);

		/*return $this->updateAll(array(
			'status' => "'" . NOTIFICATION_OBJECT_ENABLED . "'"
		), array(
			'notification_system_item_id' => $notificationId
		));*/
	}

	public function disableAll($notificationId) {
		return $this->updateAll(array(
			'status' => "'" . NOTIFICATION_OBJECT_DISABLED . "'"
		), array(
			'notification_system_item_id' => $notificationId
		));
	}

	/**
	 * Associate all given items in a model to a certain notification.
	 */
	public function associateAll($model, $listIds, $notificationId) {
		$ret = true;
		if (!empty($listIds)) {
			foreach ($listIds as $foreignKey) {
				$ret &= $this->associate($notificationId, $model, $foreignKey);
			}
		}

		return $ret;
	}

	/**
	 * @deprecated
	 */
	public function enableForAll($notificationId, $model) {
		$this->bindObjectModel($model);

		$allIds = $this->Object->find('list', array(
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		// debug($allIds);
	}

	/**
	 * Retrieve notifications associated with an item.
	 */
	public function getByItem($model, $foreign_key) {
		return $this->find('all', array(
			'conditions' => array(
				'NotificationObject.model' => $model,
				'NotificationObject.foreign_key' => $foreign_key
			),
			'fields' => array(
				'NotificationObject.*',
				'NotificationSystem.*',
			),
			'order' => array('NotificationObject.created' => 'DESC'),
			// 'limit' => 10,
			'recursive' => 0
		));
	}
}
