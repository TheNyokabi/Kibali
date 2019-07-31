<?php
App::uses('Hash', 'Utility');

class User extends AppModel {
	public $displayField = 'full_name';

	public $mapping = array(
		'titleColumn' => 'email',
		'logRecords' => true
	);

	public $name = 'User';
	public $actsAs = array(
		'Acl' => array('type' => 'requester'),
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'surname', 'email', 'login', 'language', 'status', 'local_account'
			)
		)
	);

	/*public $virtualFields = array(
		'full_name' => 'CONCAT(User.name, " ", User.surname)',
	);*/

	public $validate = array(
		'pass' => array (
			'between' => array(
				'rule' => array('between', 8, 30),
				'message' => 'Passwords must be between 8 and 30 characters long.'
			),
			'alphaNumericCustomized' => array(
				'rule' => 'alphaNumericCustomized',
				'message' => 'Password must be only alphanumeric characters and optionally, include one of the following: "!@#$%^&()]["'
			),
			'compare' => array(
				'rule' => array('compare_password', 'pass2'),
				'message' => 'Password and verify password must be same.'
			)
		),
		'email' => array(
			'email' => array(
				'rule' => 'email',
				'required' => true
			),
			'notEmpty' => array(
				'rule' => 'notBlank'
			),
			'unique' => array(
				'rule' => 'isUnique',
				'message' => 'Email is already used'
			)
		),
		'login' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'msg' => 'sadasd'
			),
			'unique' => array(
				'rule' => 'isUnique',
				// 'message' => 'Login is already used'
			)
		),
		'name' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		'Group' => array(
			'multiple' => array(
				'rule' => array('multiple', array('min' => 1)),
				'required' => true,
				'message' => 'At least one group has to be assigned to the user'
			)
		)
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'User'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'User'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'User'
			)
		),
		'UserBan'
	);

	public $hasAndBelongsToMany = array(
		'Group' => array(
			'with' => 'UsersGroup',
			'className' => 'Group',
			'joinTable' => 'users_groups',
			'foreignKey' => 'user_id',
			'associationForeignKey' => 'group_id'
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Users');
		
		$this->fieldData = array(
			'Group' => array(
				'label' => __('Groups'),
				'editable' => true,
				'description' => __('Select groups (System / Settings / Groups) for this user. Groups have access controls defined (System / Settings / Access Lists) that limit the places where a user can access on the system.'),
			)
		);

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'name' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'like',
						'field' => array('User.name'),
					)
				),
				'surname' => array(
					'type' => 'text',
					'name' => __('Surname'),
					'show_default' => true,
					'filter' => array(
						'type' => 'like',
						'field' => array('User.surname'),
					)
				),
				'email' => array(
					'type' => 'text',
					'name' => __('Email'),
					'show_default' => false,
					'filter' => array(
						'type' => 'like',
						'field' => array('User.email'),
					)
				),
				'login' => array(
					'type' => 'text',
					'name' => __('Login'),
					'show_default' => true,
					'filter' => array(
						'type' => 'like',
						'field' => array('User.login'),
					)
				),
				'local_account' => array(
					'type' => 'select',
					'name' => __('Local Account'),
					'show_default' => false,
					'filter' => array(
						'type' => 'value',
					),
					'data' => array(
						'method' => 'getStatusFilterOption',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'group_id' => array(
					'type' => 'multiple_select',
					'name' => __('Group'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Group.id',
						'field' => 'User.id',
					),
					'data' => array(
						'method' => 'getGroups',
					),
					'many' => true,
					'contain' => array(
						'Group' => array(
							'name'
						)
					),
				),
				'language' => array(
					'type' => 'multiple_select',
					'name' => __('Language'),
					'show_default' => false,
					'filter' => array(
						'type' => 'value',
					),
					'data' => array(
						'method' => 'getLangs',
						'result_key' => true,
					),
				),
				'status' => array(
					'type' => 'select',
					'name' => __('Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'value',
					),
					'data' => array(
						'method' => 'getStatuses',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Users'),
			'pdf_file_name' => __('users'),
			'csv_file_name' => __('users'),
		);

		parent::__construct($id, $table, $ds);

		$this->virtualFields['full_name'] = 'CONCAT('.$this->alias.'.name, " ", '.$this->alias.'.surname)';
		$this->virtualFields['full_name_with_type'] = "CONCAT(`{$this->alias}`.`name`, ' ', `{$this->alias}`.`surname`, ' ', '(" . __('User') . ")')";
		$this->order = [$this->escapeField('name') . ' ASC', $this->escapeField('surname') . ' ASC'];
	}

	/**
	 * Alphanumeric validation that can optionally include following characters: !@#$%^&()][
	 */
	public function alphaNumericCustomized($check) {
        // $data array is passed using the form field name as the key
        // have to extract the value to make the function generic
        $value = array_values($check);
        $value = $value[0];
        
        $validate = preg_match('/[A-Za-z]/', $value);
        $validate &= preg_match('/[0-9]/', $value);
        $validate &= preg_match('|^[0-9a-zA-Z!@#\$%\^&\(\)\]\[]*$|', $value);

        return $validate;
    }

	public function beforeDelete($cascade = true) {
		$ret = true;

		$ret &= $this->makeItemOwnersAdmin();

		return $ret;
	}

	/**
	 * we check local account value before validation.
	 */
	public function beforeValidate($options = array()) {
		if (!isset($this->data['User']['id']) && !empty($this->id)) {
			$this->data['User']['local_account'] = $this->field('local_account');
		}

		//handle empty status field
		if (!isset($this->data['User']['status'])) {
			$this->data['User']['status'] = USER_ACTIVE;
		}

		//
		// If LDAP or OAuth authentication is enabled for users, disable password validation
		$auth = ClassRegistry::init('LdapConnectorAuthentication');
		$count = $auth->find('count', array(
			'conditions' => array(
				'OR' => array(
					'LdapConnectorAuthentication.auth_users' => '1',
					'LdapConnectorAuthentication.oauth_google' => '1'
				)
			),
			'recursive' => -1
		));
		
		if ($count && isset($this->data['User']['local_account']) && empty($this->data['User']['local_account'])) {
			$this->validator()->remove('pass');
		}
		//
	}

	public function beforeSave($options = array())
	{
		// Transforms the data array to save the HABTM relation
    	$this->transformDataToHabtm(array('Group'));

    	return true;
	}

	/**
	 * After save callback.
	 */
	public function afterSave($created, $options = array()) {
		$ret = true;

		// for a new user we also create a custom role related row that will act as ACL node.
		if ($created) {
			$CustomRolesUser = ClassRegistry::init('CustomRoles.CustomRolesUser');
			$ret &= $CustomRolesUser->syncSingleObject($this->id);
		}

		return $ret;
	}

	public function getLangs() {
		return availableLangs();
	}

	public function getStatuses() {
		return array(
			USER_ACTIVE => __('Active'),
			USER_NOTACTIVE => __('Inactive')
		);
	}


	public function getGroups() {
		$data = $this->Group->find('list', array(
			'order' => array('Group.name' => 'ASC'),
			'fields' => array('Group.id', 'Group.name'),
			'recursive' => -1
		));

		return $data;
	}

	/**
	 * Change all owner fields that belongs to the current user $this->id, to Admin.
	 */
	private function makeItemOwnersAdmin() {
		$ownersData = array(
			'Risk' => array('user_id', 'guardian_id'),
			'ThirdPartyRisk' => array('user_id', 'guardian_id'),
			'BusinessContinuity' => array('user_id', 'guardian_id'),
			'SecurityIncident' => array('user_id'),
			'SecurityService' => 'user_id',
			'SecurityServiceAudit' => 'user_id',
			'SecurityServiceAuditImprovement' => array('user_id'),
			'SecurityServiceMaintenance' => 'user_id',
			'Goal' => 'owner_id',
			'GoalAudit' => 'user_id',
			'GoalAuditImprovement' => array('user_id'),
			'Asset' => 'asset_owner_id',
			'BusinessContinuityPlan' => array('owner_id', 'launch_responsible_id', 'sponsor_id'),
			'BusinessContinuityPlanAudit' => 'user_id',
			'BusinessContinuityPlanAuditImprovement' => array('user_id'),
			'BusinessContinuityTask' => array('awareness_role'),
			'Project' => 'user_id',
			'ProjectAchievement' => 'user_id',
			'Review' => 'user_id',
			'Issue' => 'user_id',
			'Attachment' => 'user_id',
			'ComplianceAudit' => 'auditor_id',
			'NotificationSystemItemFeedback' => 'user_id',
			'RiskExceptions' => array('author_id'),
			'Scope' => array('ciso_role_id', 'ciso_deputy_id', 'board_representative_id', 'board_representative_deputy_id'),
			'SecurityPolicy' => array('author_id'),
			'SystemRecord' => array('user_id'),
			'ComplianceAuditAuditeeFeedback' => array('user_id'),
			'AdvancedFilterUserSetting' => array('user_id')
			
		);
		$ret = true;

		foreach ($ownersData as $model => $field) {
			$ret = $this->makeFieldAdmin($model, $field);
		}

		return $ret;
	}

	public function makeFieldAdmin($model, $field) {
		$ret = true;
		if (is_array($field)) {
			foreach ($field as $f) {
				$ret &= $this->makeFieldAdmin($model, $f);
			}

			return $ret;
		}

		$tmpClass = ClassRegistry::init($model);
		if (!$tmpClass->schema($field)) {
			return true;
		} 

		return $tmpClass->updateAll(array(
			$model . '.' . $field => ADMIN_ID
		), array(
			$model . '.' . $field => $this->id
		));
	}

	public function bindNode($user) {
        return array('model' => 'Group', 'foreign_key' => $user['User']['Groups']);
    }

	public function parentNode() {
		if (!$this->id && empty($this->data)) {
			return null;
		}

		$groups = array();
		if (isset($this->data['Group']['Group']) && is_array($this->data['Group']['Group'])) {
			$groups = $this->data['Group']['Group'];
		}
		else {
			$groups = $this->find('first', array(
				'conditions' => array(
					$this->alias . '.id' => $this->id
				),
				'contain' => array(
					'Group' => array(
						'fields' => array(
							'Group.id'
						)
					)
				)
			));

			$groups = Hash::extract($groups, 'Group.{n}.id');
		}
		if (empty($groups)) {
			return null;
		}
		else {
			return array('Group' => array('id' => $groups));
		}
	}

	public function compare_password($pass1 = null, $pass2 = null) {

		foreach ($pass1 as $key => $value) {
			if ($value != $this->data[$this->name][$pass2]) {
				return false;
			}
			else continue;
		}
		return true;
	}

	/**
	 * Checks if a user is associated in a restricted table relation.
	 * @deprecated
	 */
	public function hasRestrictAssoc($userId) {
		$securityIncident = ClassRegistry::init('SecurityIncident');
		$count = $securityIncident->find('count', array(
			'conditions' => array(
				'user_id' => $userId
			),
			'recursive' => -1
		));
		if ($count) {
			return true;
		}

		$controlAudit = ClassRegistry::init('SecurityServiceAudit');
		$count = $controlAudit->find('count', array(
			'conditions' => array(
				'user_id' => $userId
			),
			'recursive' => -1
		));
		if ($count) {
			return true;
		}

		$tpRisk = ClassRegistry::init('ThirdPartyRisk');
		$count = $tpRisk->find('count', array(
			'conditions' => array(
				'user_id' => $userId
			),
			'recursive' => -1
		));
		if ($count) {
			return true;
		}

		$businessContinuity = ClassRegistry::init('BusinessContinuity');
		$count = $businessContinuity->find('count', array(
			'conditions' => array(
				'user_id' => $userId
			),
			'recursive' => -1
		));
		if ($count) {
			return true;
		}

		$goalAudit = ClassRegistry::init('GoalAudit');
		$count = $goalAudit->find('count', array(
			'conditions' => array(
				'user_id' => $userId
			),
			'recursive' => -1
		));
		if ($count) {
			return true;
		}

		$risk = ClassRegistry::init('Risk');
		$count = $risk->find('count', array(
			'conditions' => array(
				'user_id' => $userId
			),
			'recursive' => -1
		));
		if ($count) {
			return true;
		}

		return false;
	}

	public function saveBlockedField($id, $blocked) {
		$this->id = $id;

		// $this->pushStatusRecords();
		$ret = $this->saveField('blocked', $blocked, array('validate' => false, 'callbacks' => 'before'));
		// $this->holdStatusRecords();

		return $ret;
	}

	public function checkBlockedStatus($id) {
		$this->bindModel( array(
			'hasMany' => array(
				'UserBan'
			)
		) );
		$this->Behaviors->attach('Containable');

		$fromTime = CakeTime::format( 'Y-m-d H:i:s', CakeTime::fromString( '-' . BRUTEFORCE_SECONDS_AGO . ' seconds' ) );
		$user = $this->find( 'first', array(
			'fields' => array('id', 'blocked'),
			'conditions' => array(
				'User.id' => $id
			),
			'contain' => array(
				'UserBan' => array(
					'conditions' => array(
						'UserBan.until >' => CakeTime::format( 'Y-m-d H:i:s', CakeTime::fromString( 'now' ) )
					)
				)
			),
		) );

		if ( empty( $user ) ) {
			return true;
		}

		if (empty($user['UserBan']) && $user['User']['blocked']) {
			return (boolean) $this->saveBlockedField($user['User']['id'], '0');
		}

		return true;
	}

	public function checkAllBlockedStatuses() {
		$data = $this->find('list', array(
			'fields' => array('id')
		));

		$ret = true;
		foreach ($data as $id) {
			$ret &= $this->checkBlockedStatus($id);
		}

		return $ret;
	}

	/**
	 * Unblock user that has a brute force block.
	 */
	public function unblockBan($userId) {
		$ret = true;

		$ret &= $this->UserBan->deleteAll(array(
			'UserBan.user_id' => $userId,
			'UserBan.until >' => '"' . CakeTime::format('Y-m-d H:i:s', CakeTime::fromString('now')) . '"'
		));

		$ret &= $this->saveBlockedField($userId, '0');

		return $ret;
	}

	/**
	 * Returns array of user emails as a list.
	 */
	public function getEmails($ids = []) {
		if (!is_array($ids)) {
			$ids = array($ids);
		}

		return $this->find('list', [
			'conditions' => [
				$this->alias . '.id' => $ids
			],
			'fields' => [
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.email'
			],
			'recursive' => -1
		]);
	}
}
