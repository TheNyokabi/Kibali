<?php
App::uses('ClassRegistry', 'Utility');
App::uses('Hash', 'Utility');

class Group extends AppModel {
	const ADMIN_ID = ADMIN_GROUP_ID;

	public $name = 'Group';
	public $actsAs = array(
		'Acl' => array('type' => 'requester'),
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description'
			)
		)
	);

	public $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notBlank'
			),
			'unique' => array(
				'rule' => 'isUnique'
			)
		)
	);

	public $hasAndBelongsToMany = array(
		'User' => array(
			'with' => 'UsersGroup',
			'className' => 'User',
			'joinTable' => 'users_groups',
			'foreignKey' => 'group_id',
			'associationForeignKey' => 'user_id'
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Groups');
		
		parent::__construct($id, $table, $ds);

		$this->virtualFields['full_name_with_type'] = "CONCAT(`{$this->alias}`.`name`, ' ', '(" . __('Group') . ")')";
	}

	public function parentNode() {
		return null;
	}

	/**
	 * Reset ACL cache after a group has been saved.
	 */
	public function afterSave($created, $options = array()) {
		$ret = true;
		if ($created) {
			$CustomRolesGroup = ClassRegistry::init('CustomRoles.CustomRolesGroup');
			$ret &= $CustomRolesGroup->syncSingleObject($this->id);

			$Permission = ClassRegistry::init('AppPermission');

			// get aro<->aco links
			$link = $Permission->getAclLink($this, 'controllers');

			// if there is no link between this aro and 'controllers' aco, create it
			if (empty($link['link'])) {
				// $ret &= $Permission->allow($this, 'controllers');
			}

			// when permission setup is triggered for admin group, allow everything
			if ($this->id === self::ADMIN_ID) {
				$ret &= $Permission->allow($this, 'visualisation');
			}
			// other groups by default can create new objects (allow 'create' on model node)
			else {
				$ret &= $Permission->allow($this, 'models', 'create');
			}
		}

		$Setting = ClassRegistry::init('Setting');
		$Setting->deleteCache('acl');

		return $ret;
	}

	/**
	 * Restrict deletion if a Connector is still in use.
	 */
	public function beforeDelete($cascade = true) {
		$ret = true;
		if ($this->hasUsers($this->id)) {
			$ret = false;
			$this->deleteMessage = __('Group cannot be deleted because it contains one or more users.');
		}

		return $ret;
	}

	/**
	 * Checks if a Group contains any users.
	 */
	public function hasUsers($id) {
		$users = $this->User->find('all', array(
			'contain' => array(
				'Group'
			),
			'recursive' => -1
		));

		$ret = false;
		foreach ($users as $user) {
			$groups = Hash::extract($user, 'Group.{n}.id');
			
			if (in_array($id, $groups)) {
				$ret = true;
			}
		}

		return $ret;
	}
}
