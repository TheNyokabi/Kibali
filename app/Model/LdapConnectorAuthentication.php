<?php
class LdapConnectorAuthentication extends AppModel {
	public $useTable = 'ldap_connector_authentication';

	public $actsAs = array(
		'Containable'
	);

	public $validate = array(
		'auth_users_id' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'allowEmpty' => false,
				'message' => 'LDAP Connector is required'
			)
		),
		'oauth_google_id' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'allowEmpty' => false,
				'message' => 'OAuth Google Connector is required'
			)
		),
		'auth_awareness_id' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'allowEmpty' => false,
				'message' => 'LDAP Connector is required'
			)
		),
		/*'auth_policies_id' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'allowEmpty' => false,
				'message' => 'LDAP Connector is required'
			)
		),*/
	);

	public $belongsTo = array(
		'AuthUsers' => array(
			'className' => 'LdapConnector',
			'foreignKey' => 'auth_users_id'
		),
		'OauthGoogle' => array(
			'className' => 'OauthConnector',
			'foreignKey' => 'oauth_google_id'
		),
		'AuthAwareness' => array(
			'className' => 'LdapConnector',
			'foreignKey' => 'auth_awareness_id'
		),
		'AuthPolicies' => array(
			'className' => 'LdapConnector',
			'foreignKey' => 'auth_policies_id'
		)
	);

	const GENERAL_AUTH_DEFAULT = 1;
	const GENERAL_AUTH_LDAP = 2;
	const GENERAL_AUTH_OAUTH_GOOGLE = 3;

	const VALUE_ENABLED = 1;
	const VALUE_DISABLED = 0;

	/**
	 * Generic method that returns LDAP auth information. Supports overwriting with local configuration.
	 *
	 * @todo possibility to define in local configuration, whole ldap auth.
	 * @todo use authconnector.
	 */
	public function getAuthData() {
		if (($data = Cache::read('ldap_auth_data', 'ldap')) === false) {
			$data = $this->find('first', array(
				'recursive' => 0
			));	
			
			$data = $this->attributesToLowercase($data);

			// Add OAuth data
			$data['OauthGoogle'] = $data['OauthGoogle'];

			Cache::write('ldap_auth_data', $data, 'ldap');
		}
		
		return $data;
	}

	public function afterSave($created, $options = array()) {
		Cache::clearGroup('ldap', 'ldap');
	}

	public function afterFind($results, $primary = false)
	{
		$results[0] = $this->initGeneralAuth($results[0]);
		return $results;
	}

	public function beforeValidate($options = array()) {
		$this->data = $this->initGeneralAuth($this->data);
		$this->handleValidation();

		return true;
	}

	private function initGeneralAuth($data)
	{
		reset($data);
		$key = key($data);

		$generalAuth = self::GENERAL_AUTH_DEFAULT;
		$authUsers = 0;
		$oauthGoogle = 0;
		if (isset($data[$key]['general_auth'])) {
			$generalAuth = $data[$key]['general_auth'];

			switch ($generalAuth) {
				case self::GENERAL_AUTH_LDAP:
					$authUsers = 1;
					$oauthGoogle = 0;
					break;
				case self::GENERAL_AUTH_OAUTH_GOOGLE:
					$authUsers = 0;
					$oauthGoogle = 1;
					break;
			}
		} elseif (isset($data[$key]['auth_users']) && isset($data[$key]['oauth_google'])) {
			if ($data[$key]['auth_users'] == 1) { // LDAP is selected
				$generalAuth = self::GENERAL_AUTH_LDAP;
				$authUsers = 1;
				$oauthGoogle = 0;
			} elseif ($data[$key]['oauth_google'] == 1) { // OAuth Google is selected
				$generalAuth = self::GENERAL_AUTH_OAUTH_GOOGLE;
				$authUsers = 0;
				$oauthGoogle = 1;
			} // If neither one condition is true: Default auth is selected
		} else {
			return $data;
		}

		$data[$key]['general_auth'] = $generalAuth;
		$data[$key]['auth_users'] = $authUsers;
		$data[$key]['oauth_google'] = $oauthGoogle;

		return $data;
	}

	private function handleValidation() {
		if (!$this->data['LdapConnectorAuthentication']['auth_users']) {
			$this->validator()->remove('auth_users_id');
		}

		if (!$this->data['LdapConnectorAuthentication']['oauth_google']) {
			$this->validator()->remove('oauth_google_id');
		}

		if (!$this->data['LdapConnectorAuthentication']['auth_awareness']) {
			$this->validator()->remove('auth_awareness_id');
		}

		if (!$this->data['LdapConnectorAuthentication']['auth_policies']) {
			$this->validator()->remove('auth_policies_id');
		}
	}

	public function attributesToLowercase($authentication) {
		$authentication['AuthUsers'] = $this->AuthUsers->attributesToLowercase($authentication['AuthUsers']);
		$authentication['AuthAwareness'] = $this->AuthAwareness->attributesToLowercase($authentication['AuthAwareness']);
		$authentication['AuthPolicies'] = $this->AuthPolicies->attributesToLowercase($authentication['AuthPolicies']);

		return $authentication;
	}

	/**
	 * Change authentication setting.
	 * @param  string $setting Setting field name.
	 * @param  int $value Setting value.
	 * @return boolean Success.
	 */
	public function changeValue($setting, $value) {
		$data = [
			'id' => 1,
			$setting => $value
		];

		return $this->save($data, ['fieldList' => [$setting], 'validate' => false]);
	}
}
