<?php
App::uses('AppController', 'Controller');

/**
 * @package       Api.Controller
 */
class ApiAppController extends AppController {
	/**
	 * Array of fields whitelisted for saving.
	 * @var array
	 */
	protected $fieldList = array();

	/**
	 * We setup core functionality for APIs based on the application defaults.
	 */
	public function beforeFilter() {
		$this->setAuthSettings();
		$this->setupSecurity();

		$this->Components->disable(array('Menu', 'News', 'AdvancedFilters'));
		// $this->disableModelConfigs($this->modelClass);
		$this->{$this->modelClass}->Behaviors->disable(array('SystemLog', 'WorkflowManager', 'NotificationsSystem'));
	}

	protected function disableModelConfigs($model) {
		if (is_array($model)) {
			foreach ($model as $m) {
				$this->disableModelConfigs($m);
			}

			return true;
		}

		$this->{$this->modelClass}->{$model}->Behaviors->disable(array('SystemLog', 'WorkflowManager', 'NotificationsSystem'));
	}

	/**
	 * Setup user authentication but using "Basic Authentication" which happens automatically during a request.
	 * Only successfully authenticated users having ACL permissions allowed are able to use APIs.
	 */
	private function setAuthSettings() {
		$authUserName = env('PHP_AUTH_USER');
		AuthComponent::$sessionKey = false;
		$this->Auth->unauthorizedRedirect = false;

		/*$this->Auth->authorize = array(
			'Api.ApiActions' => array('actionPath' => 'controllers/')
		);*/

		$this->loadModel('LdapConnectorAuthentication');
		$data = $this->LdapConnectorAuthentication->find('first', array(
			'recursive' => 0
		));

		$data = $this->LdapConnectorAuthentication->attributesToLowercase($data);

		$this->ldapAuth = $data;

		$authUsers = $data['LdapConnectorAuthentication']['auth_users'];
		if ($authUsers && (!empty($authUserName) && $authUserName != 'admin')) {
			$this->initLdapAuthentication($data['AuthUsers']);

			$scope = array(
				'User.status' => USER_ACTIVE,
				'User.local_account' => 1
			);
		}
		else {
			// default scope for logging in
			$scope = array(
				'User.status' => USER_ACTIVE
			);

			// in case LDAP is disabled, we cant allow LDAP users to login
			if (empty($authUsers)) {
				$scope['User.local_account'] = 1;
			}
		}

		// explicitly allow only user accounts that are configured to use the API
		$scope['User.api_allow'] = 1;

		// unset($this->Auth->authenticate['Blowfish']);

		$this->Auth->authenticate['Basic'] = array(
			'fields' => array('username' => 'login'),
			'scope' => $scope,
			'passwordHasher' => 'Blowfish'
		);

		$this->Auth->startup($this);
		$this->logged = $this->Auth->user();
	}

	/**
	 * API requires SSL connection on all api request. SSL is not required while in debug mode. 
	 */
	protected function setupSecurity() {
		if (!Configure::read('debug')) {
			$this->Security = $this->Components->load('Security');
			$this->Security->validatePost = false;
			$this->Security->csrfCheck = false;
			
			$this->Security->requireSecure();
		}
	}

	/**
	 * Temporary set default workflow values while adding a new item.
	 */
	protected function setWorkflowDefaults($data = array()) {
		if (empty($data['workflow_owner_id'])) {
			$data['workflow_owner_id'] = $this->getDefaultUser();
		}

		if (empty($data['workflow_status'])) {
			$data['workflow_status'] = WORKFLOW_APPROVED;
		}

		return $data;
	}

	/**
	 * Get IF for the current user.
	 */
	protected function getDefaultUser() {
		return $this->Auth->user('id');
	}

	/**
	 * Get data params with a single wrapper method. 
	 */
	protected function getDefaultRequestData() {
		// $input = $this->request->input('json_decode', true);
		return $this->request->data;
	}

}