<?php
class LdapConnector extends AppModel {
	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description', 'host', 'domain', 'port', 'type', 'status'
			)
		)
	);

	public $validate = [
		'name' => [
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Name is required'
		],
		'host' => [
			'notBlank' => [
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Host is required'
			],
			'url' => [
				'rule' => 'urlCustom',
				'message' => 'Please enter a valid URL'
			]
		],
		'domain' => [
			'notBlank' => [
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Domain is required'
			],
			'url' => [
				'rule' => 'url',
				'message' => 'Please enter a valid domain'
			]
		],
		'port' => [
			'notEmpty' => [
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Port is required'
			],
			'numeric' => [
				'rule' => 'numeric',
				'message' => 'Port must be numeric'
			],
			'range' => [
				'rule' => ['range', -1, 65536],
				'message' => 'Port must be a number within 0 - 65535'
			],
		],
		'ldap_bind_dn' => [
			'notEmpty' => [
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'LDAP username is required'
			]
		],
		'ldap_bind_pw' => [
			'notEmpty' => [
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'LDAP password is required'
			]
		],
		'ldap_base_dn' => [
			'notEmpty' => [
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'LDAP Base DN is required'
			]
		]
	];

	public $hasMany = array(
		'AwarenessProgram',
		'SecurityPolicy',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'LdapConnector'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'LdapConnector'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'LdapConnector'
			)
		),
	);

	public $hasOne = array(
		'LdapAuthUsers' => array(
			'className' => 'LdapConnectorAuthentication',
			'foreignKey' => 'auth_users_id'
		),
		'LdapAuthAwareness' => array(
			'className' => 'LdapConnectorAuthentication',
			'foreignKey' => 'auth_awareness_id'
		),
		'LdapAuthPolicies' => array(
			'className' => 'LdapConnectorAuthentication',
			'foreignKey' => 'auth_policies_id'
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Ldap Connectors');
		
		parent::__construct($id, $table, $ds);
	}

	// possible statuses
	public static function statuses($value = null) {
		$options = array(
			self::STATUS_DISABLED => __('Disabled'),
			self::STATUS_ACTIVE => __('Active')
		);
		return parent::enum($value, $options);
	}
	const STATUS_DISABLED = 0;
	const STATUS_ACTIVE = 1;

	// possible types
	public static function types($value = null) {
		$options = array(
			self::TYPE_AUTHENTICATOR => __('Authenticator'),
			self::TYPE_GROUP => __('Group')
		);
		return parent::enum($value, $options);
	}
	const TYPE_AUTHENTICATOR = 'authenticator';
	const TYPE_GROUP = 'group';

	public function beforeValidate($options = array()) {
		// default validation
		$this->addListValidation('status', array_keys(self::statuses()));
		$this->addListValidation('type', array_keys(self::types()));

		$this->handleTypeValidation();

		return true;
	}

	/**
	 * Restrict deletion if a Connector is still in use.
	 */
	public function beforeDelete($cascade = true) {
		$ret = true;

		if ($this->inUse($this->id)) {
			$ret = false;
			$this->deleteMessage = __('Ldap Connector cannot be deleted because is in use.');
		}

		return $ret;
	}

	public function afterSave($created, $options = array()) {
		if (!$created) {
			Cache::clearGroup('ldap', 'ldap');
		}
	}

	private function handleTypeValidation() {
		if ($this->data['LdapConnector']['type'] == LDAP_CONNECTOR_TYPE_GROUP) {
			$this->validator()->add('ldap_grouplist_filter', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP filter is required')
			));

			$this->validator()->add('ldap_grouplist_name', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP attribute is required')
			));

			$this->validator()->add('ldap_groupmemberlist_filter', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP filter is required')
			));

			// group type validation
			$this->validator()->add('ldap_group_fetch_email_type', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('Email address configuration is required')
			));
			
			$this->addListValidation('ldap_group_fetch_email_type', array_keys(getLdapConnectorEmailFetchTypes()));

			$this->validator()->add('ldap_group_account_attribute', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP attribute is required')
			));

			if ($this->data['LdapConnector']['ldap_group_fetch_email_type'] == LDAP_CONNECTOR_EMAIL_FETCH_EMAIL_ATTRIBUTE) {
				$this->validator()->add('ldap_group_email_attribute', 'notEmpty', array(
					'rule' => 'notBlank',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('This LDAP attribute is required')
				));
			}

			if ($this->data['LdapConnector']['ldap_group_fetch_email_type'] == LDAP_CONNECTOR_EMAIL_FETCH_ACCOUNT_DOMAIN) {
				$this->validator()->add('ldap_group_mail_domain', 'notEmpty', array(
					'rule' => 'notBlank',
					'required' => true,
					'allowEmpty' => false,
					'message' => __('This field is required')
				));
			}
		}
		
		elseif ($this->data['LdapConnector']['type'] == LDAP_CONNECTOR_TYPE_AUTHENTICATOR) {
			$this->validator()->add('ldap_auth_filter', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP filter is required')
			));

			$this->validator()->add('ldap_auth_attribute', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP attribute is required')
			));

			$this->validator()->add('ldap_name_attribute', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP attribute is required')
			));

			$this->validator()->add('ldap_email_attribute', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP attribute is required')
			));

			$this->validator()->add('ldap_memberof_attribute', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This LDAP attribute is required')
			));
		}
	}

	public function attributesToLowercase($connector) {
		$keys = array_keys($connector);

		if (count($keys) == 1) {
			$c = $connector[$keys[0]];
		}
		else {
			$c = $connector;
		}

		$c['ldap_auth_attribute'] = $this->toLower($c['ldap_auth_attribute']);
		$c['ldap_name_attribute'] = $this->toLower($c['ldap_name_attribute']);
		$c['ldap_email_attribute'] = $this->toLower($c['ldap_email_attribute']);
		$c['ldap_memberof_attribute'] = $this->toLower($c['ldap_memberof_attribute']);
		$c['ldap_grouplist_name'] = $this->toLower($c['ldap_grouplist_name']);
		// $c['ldap_groupmemberlist_name'] = $this->toLower($c['ldap_groupmemberlist_name']);

		if (count($keys) == 1) {
			$connector[$keys[0]] = $c;
		}
		else {
			$connector = $c;
		}

		return $connector;
	}

	private function toLower($str) {
		if ($str === null) {
			return $str;
		}

		return strtolower($str);
	}

	/**
	 * Checks if an Ldap Connector is used in some part of the system.
	 */
	public function inUse($id) {
		$count = $this->AwarenessProgram->find('count', array(
			'conditions' => array(
				'AwarenessProgram.ldap_connector_id' => $id
			)
		));

		$count = $count || $this->SecurityPolicy->find('count', array(
			'conditions' => array(
				'SecurityPolicy.ldap_connector_id' => $id
			)
		));

		$count = $count || $this->LdapAuthUsers->find('count', array(
			'conditions' => array(
				'OR' => array(
					'LdapAuthUsers.auth_users_id' => $id,
					'LdapAuthUsers.auth_awareness_id' => $id,
					'LdapAuthUsers.auth_policies_id' => $id
				)
			)
		));
		
		return $count;
	}

}
