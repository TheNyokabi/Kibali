<?php
App::uses('ModelBehavior', 'Model');
App::uses('AppModel', 'Model');

class NotificationsSystemBehavior extends ModelBehavior {

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = array(
				'includeNotifications' => true
			);
		}
		$this->settings[$Model->alias] = array_merge(
		$this->settings[$Model->alias], (array) $settings);
	}

	public function beforeFind(Model $Model, $query) {
		if ($this->settings[$Model->alias]['includeNotifications']) {
			$this->bindNotifications($Model);

			// @todo rewrite
			// $conds = $Model->mapping['notificationSystem'] === true || (is_array($Model->mapping['notificationSystem']) && in_array(AppModel::$currentAction, $Model->mapping['notificationSystem']));
			
			// @todo rewrite			
			// if ($Model->name == AppModel::$modelClass && $conds) {
			// 	$query = AppModel::includeToQuery($query, $Model->name, 'NotificationObject');
			// }
		}

		return $query;
	}

	public function afterSave(Model $Model, $created, $options = array()) {
		$ret = true;

		if (!empty($created)) {
			$ret &= $this->attachAutomatedNotifications($Model);
		}

		return $ret;
	}

	private function attachAutomatedNotifications(Model $Model) {
		$Model->bindNotifications();
		return $Model->NotificationObject->NotificationSystem->associateForAutomated($Model->name, $Model->id);
	}

	/**
	 * Associate current model that uses notification feature with notification system objects.
	 */
	public function bindNotifications(Model $Model) {
		if (in_array('NotificationObject', $Model->getAssociated("hasMany"))) {
			return true;
		}

		$Model->bindModel(array(
			'hasMany' => array(
				'NotificationObject' => array(
					'foreignKey' => 'foreign_key',
					'conditions' => array(
						'NotificationObject.model' => $Model->name
					)
				)
			)
		));
	}

	/**
	 * Set whether notifications should be included or not.
	 */
	public function includeNotifications(Model $Model, $include = true) {
		if (!is_bool($include)) {
			$include = true;
		}
		
		$this->settings[$Model->alias]['includeNotifications'] = $include;
	}

}
