<?php
App::uses('SectionBase', 'Model');

class OauthConnector extends SectionBase {

	public $displayField = 'name';

	const PROVIDER_GOOGLE = 'google';
	const STATUS_DISABLED = 0;
	const STATUS_ACTIVE = 1;

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'notificationSystem' => array('index'),
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'client_id', 'client_secret', 'provider', 'status'
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
		'client_id' => [
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Client ID is required'
		],
		'client_secret' => [
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'Client Secret is required'
		],
		'provider' => [
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'You have to choose a provider'
		]
	];

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'OauthConnector'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'OauthConnector'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'OauthConnector'
			)
		),
	);

	public $hasOne = array(
		'LdapAuthGoogleOauth' => array(
			'className' => 'LdapConnectorAuthentication',
			'foreignKey' => 'oauth_google_id'
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('OAuth Connectors');
		//$this->_group = 'organization';

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			]
		];

		$this->fieldData = [
			'name' => [
				'label' => __('Name'),
				'editable' => true,
				'description' => __(''),
			],
			'client_id' => [
				'label' => __('Client ID'),
				'editable' => true,
				'description' => __(''),
			],
			'client_secret' => [
				'label' => __('Client Secret'),
				'editable' => true,
				'description' => __(''),
			],
			'provider' => [
				'label' => __('Provider'),
				'editable' => true,
				'description' => __(''),
			]
		];

		parent::__construct($id, $table, $ds);
	}

	//
	// Possible providers
	public function providers($value = null) {
		$options = array(
			self::PROVIDER_GOOGLE => __('Google')
		);
		return parent::enum($value, $options);
	}

	// possible statuses
	public static function statuses($value = null) {
		$options = array(
			self::STATUS_DISABLED => __('Disabled'),
			self::STATUS_ACTIVE => __('Active')
		);
		return parent::enum($value, $options);
	}

	public function beforeValidate($options = array()) {
		
		// Default validation
		$this->addListValidation('status', array_keys(self::statuses()));
		$this->addListValidation('provider', array_keys($this->providers()));

		$this->handleProviderValidation();

		return true;
	}

	private function handleProviderValidation() {
		if ($this->data['OauthConnector']['provider'] == self::PROVIDER_GOOGLE) {
			//
			// Preparation: when another providers will be added
		}
	}

	public function getActiveOauthData()
	{
		$data = $this->find('first', array(
			'conditions' => array(
				'LdapAuthGoogleOauth.oauth_google' => 1,
				'OauthConnector.status' => self::STATUS_ACTIVE,
				"`LdapAuthGoogleOauth`.`oauth_google_id`=`OauthConnector`.`id`"
			)
		));

		if (!empty($data)) {
			return array(
				'clientId' => $data['OauthConnector']['client_id'],
				'clientSecret' => $data['OauthConnector']['client_secret']
			);
		} else {
			return false;
		}
	}

	/**
	 * Restrict deletion if a Connector is still in use.
	 */
	public function beforeDelete($cascade = true)
	{
		return $this->prepareDelete($this->id);
	}

	public function prepareDelete($id)
	{
		$ret = true;

		if ($this->inUse($id)) {
			$ret = false;
			$this->deleteMessage = __('OAuth Connector cannot be deleted because is in use.');
		}
		
		return $ret;
	}

	/**
	 * Checks if an OAuth Connector is used in some part of the system.
	 */
	private function inUse($id)
	{
		$data = $this->find('first', array(
			'fields' => array(
				'provider'
			),
			'conditions' => array(
				'OauthConnector.id' => $id
			)
		));

		if (empty($data)) {
			return false;
		}
		
		$provider = $data['OauthConnector']['provider'];
		$count = 0;
		if ($provider == self::PROVIDER_GOOGLE) {
			$count = $this->LdapAuthGoogleOauth->find('count', array(
				'conditions' => array(
					'LdapAuthGoogleOauth.oauth_google_id' => $id,
				)
			));
		}
		
		return $count;
	}
}
