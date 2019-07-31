<?php
App::uses('AppController', 'Controller');
App::uses('Hash', 'Utility');

class UsersController extends AppController {
	public $name = 'Users';
	public $uses = array('User', 'Setting');
	public $components = array('OauthGoogleAuth', 'Ticketmaster', 'LdapConnectorsMgt', 'AdvancedFilters', 'Paginator', 'Ajax' => array(
			'actions' => array('add', 'edit', 'delete')
	));

	public function beforeFilter() {
		if ($this->params['action'] == 'changeLanguage') {
			$this->Components->disable('Acl');
			$this->Components->disable('Auth');
		}

		$this->Auth->allow('resetpassword', 'useticket', 'logout', 'changeLanguage');

		parent::beforeFilter();

		// $this->Security->unlockedActions = array('chooseLdapUser');
		// $this->Security->requireAuth('login');

		$this->set('isUserAdmin', $this->isUserAdmin());
	}

	public function index() {
		$this->set( 'title_for_layout', __('User Accounts') );
		$this->set( 'subtitle_for_layout', __( 'Manage your system user accounts. Account must exist for each individual that wants to use eramba indistinctly if LDAP is used or not.' ) );

		if ($this->AdvancedFilters->filter('User')) {
			return;
		}

		$this->paginate = array(
			'conditions' => array(

			),
			'fields' => array(
				'User.id', 'User.login', 'User.name', 'User.surname', 'User.email',
				'User.blocked',' User.status'
			),
			'contain' => array(
				'Group' => array(
					'fields' => array(
						'id', 'name'
					)
				)
			),
			'order' => array('User.id' => 'ASC'),
			'limit' => $this->getPageLimit(),
			'recursive' => -1
		);

		$data = $this->paginate('User');

		$this->set('data', $data);
	}

	public function add() {
		$this->set( 'title_for_layout', __('Create a User Account') );
		$this->set( 'subtitle_for_layout', __( 'Create system users and assign them the appropiate Group Roles' ) );
		$this->set($this->User->getFieldDataEntity()->getViewOptions());
		$this->initData();
		$this->setLdapUsers();

		if (!empty($this->request->data)) {
			if (empty($this->ldapAuth['LdapConnectorAuthentication']['oauth_google']) || empty($this->ldapAuth['LdapConnectorAuthentication']['auth_users']) || !empty($this->request->data['User']['local_account'])) {
				$this->_doPasswordValidation();
			}

			unset($this->request->data['User']['id']);

			$this->User->set($this->request->data);

			if ($this->User->validates()) {
				if ($this->User->save()) {
					$this->Ajax->success();
					$this->Session->setFlash(__('User was successfully added.'), FLASH_OK);
				}
				else {
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			}
		}
		$this->request->data['User']['pass'] = $this->request->data['User']['pass2'] = '';
	}

	/**
	 * just checks if ldap connection can be made.
	 *
	 * @deprecated
	 */
	private function setLdapUsers() {
		if (empty($this->ldapAuth['LdapConnectorAuthentication']['auth_users'])) {
			return false;
		}
		
		$connector = $this->ldapAuth['AuthUsers'];

		$LdapConnector = $this->LdapConnectorsMgt->getConnector($connector);
		$ldapConnection = $LdapConnector->connect();
		$LdapConnector->unbind();

		// $ldapConnection = $this->LdapConnectorsMgt->ldapConnect($connector['host'], $connector['port'], $connector['ldap_bind_dn'], $connector['ldap_bind_pw']);
		$this->set('ldapConnection', $ldapConnection);
		
		/*if ($ldapConnection !== true) {
			return false;
		}
		
		$_users = $this->LdapConnectorsMgt->getConnectorUsers($connector);

		$users = array();
		foreach ($_users as $key => $value) {
			$users[$value] = $value;
		}

		$this->set('ldapUsers', $users);*/
	}

	/**
	 * Search all existing users using LdapConnector.
	 */
	public function searchLdapUsers() {
		if (empty($this->ldapAuth['LdapConnectorAuthentication']['auth_users'])) {
			return false;
		}
		
		$connector = $this->ldapAuth['AuthUsers'];

		$LdapConnector = $this->LdapConnectorsMgt->getConnector($connector);
		$ldapConnection = $LdapConnector->connect();

		// $ldapConnection = $this->LdapConnectorsMgt->ldapConnect($connector['host'], $connector['port'], $connector['ldap_bind_dn'], $connector['ldap_bind_pw']);
		$this->set('ldapConnection', $ldapConnection);
		
		if ($ldapConnection !== true) {
			echo json_encode(array('success' => false, 'message' => __('LDAP connection has failed.')));
			exit;
		}
		
		$_users = $LdapConnector->searchUsers($this->request->query['q']);
		// $_users = $this->LdapConnectorsMgt->searchUsers($connector, $this->request->query['q']);

		$users = array();
		foreach ($_users as $key => $value) {
			$users[] = array(
				'id' => $value,
				'text' => $value
			);
		}

		echo json_encode($users);
		exit;
	}

	public function edit($id = null) {
		$id = (int) $id;
		$this->initData();
		$this->set('id', $id);
		$this->set('edit', true);
		$this->set( 'title_for_layout', __('Edit User Accounts') );
		$this->set( 'subtitle_for_layout', __( 'Edit system users and assign them the appropiate Group Roles' ) );
		$this->set($this->User->getFieldDataEntity()->getViewOptions());

		if (!empty($this->request->data)) {
			$id = (int) $this->request->data['User']['id'];
		}

		if (!$this->isUserAdmin() && $id != $this->logged['id']) {
			throw new ForbiddenException(__('You are not allowed to edit this user.'));
		}

		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $id,
			),
			'contain' => array(
				'Group'
			),
			'recursive' => -1
		));

		if (empty($user)) {
			throw new NotFoundException();
		}

		$this->Ajax->processEdit($id);

		if (!empty($this->request->data)) {
			//validate pass
			//$this->validateUserPwd($user, false);

			if (!empty($this->request->data['User']['pass'])) {
				$this->_doPasswordValidation();
			}

			if (empty($this->User->validationErrors)) {
				$this->User->create();

				unset($this->request->data['User']['pass']);
				$this->User->set($this->request->data);

				if ($this->User->validates()) {
					if ($this->isUserAdmin()) {
						$ret = $this->User->save();
					}
					else {
						$ret = $this->User->save(null, true, array('password', 'language'));
					}

					if ($ret) {
						$this->Ajax->success();
						$this->Session->setFlash(__('User was successfully edited.'), FLASH_OK);
					}
					else {
						$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
					}
				}
			}
		}
		else {
			$this->request->data = $user;
		}
		$this->request->data['User']['old_pass'] = $this->request->data['User']['pass'] = $this->request->data['User']['pass2'] = '';

		$this->render('add');
	}

	private function validateUserPwd($user, $checkOldPwd = true) {
		if ((!$checkOldPwd || $this->request->data['User']['old_pass'] != '') && $this->request->data['User']['pass'] != '' && $this->request->data['User']['pass2'] != '') {
			//old password must match
			if ($user['User']['password'] == Security::hash($this->request->data['User']['old_pass'], 'blowfish', $user['User']['password'])) {
				
				$this->_doPasswordValidation();
			}
			else {
				$this->User->invalidate('old_pass', __('Old password is wrong.'));
			}
		}
		else {
			unset($this->User->validate['pass']);
		}
	}

	/**
	 * Do a single validation of a password request and assign the hashed password to the request if valid.
	 * 
	 * @return boolean True on success, False on failure.
	 */
	private function _doPasswordValidation() {
		$this->User->set($this->request->data);
		//valid new password
		if ($this->User->validates(array('fieldList' => array('pass')))) {
			$newPassHash = "";
			if (isset($this->request->data['User']['pass'])) {
				//save new password
				$newPassHash = Security::hash($this->request->data['User']['pass']);
			}

			$this->request->data['User']['password'] = $newPassHash;
			return true;
		}

		return false;
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('User'));
		$this->set('subtitle_for_layout', __('Delete a User.'));
		$id = (int) $id;

		$data = $this->User->find('first', array(
			'conditions' => array(
				'User.id' => $id,
				'User.id !=' => $this->logged['id'],
			),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('data', $data);

		if ($data['User']['id'] == ADMIN_ID || !$this->isUserAdmin()) {
			// $this->Session->setFlash(__('Admin user cannot be deleted.'), FLASH_WARNING);
			throw new ForbiddenException(__('You are not allowed to delete this user.'));
			// $this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		else {
			if ($this->request->is('post') || $this->request->is('put')) {
				/*if ($this->User->hasRestrictAssoc($id)) {
					$this->Session->setFlash(__('User is associated with special records and therefore cannot be deleted.'), FLASH_ERROR);
				}
				else{
	
				}*/

				$dataSource = $this->User->getDataSource();
				$dataSource->begin();

				$ret = $this->User->delete($id);
				if ($ret) {
					$dataSource->commit();
					$this->Session->setFlash(__('User was successfully deleted.'), FLASH_OK);
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
				}
				
				$this->Ajax->success();
			}
			else {
				$this->request->data = $data;
			}
		}
	}

	public function profile() {
		$this->set('title_for_layout', __('My profile'));
		$this->set( 'subtitle_for_layout', __( 'Edit your profile' ) );
		$this->initData();
		$id = $this->logged['id'];

		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.id'	  => $id,
				'User.status' => USER_ACTIVE
			),
			'contain' => array(
				'Group'
			),
			'recursive' => -1
		));

		if (empty($user)) {
			throw new NotFoundException();
			return;
		}

		$this->set('user', $user);

		if (!empty($this->request->data)) {
			if ($user['User']['local_account']) {
				//change of the password check
				$this->validateUserPwd($user);
			}

			//if password entered is valid
			if (empty($this->User->validationErrors)) {
				$this->User->create();
				$this->User->id = $id;

				//group cannot be changed
				$this->request->data['User']['Group'] = Hash::extract($user, 'Group.{n}.id');

				$this->User->set($this->request->data);

				if ($this->User->validates()) {
					if ($this->isUserAdmin()) {
						$ret = $this->User->save(null, true, array('name', 'surname', 'password', 'email'/*, 'language'*/));
					}
					else {
						$ret = $this->User->save(null, true, array('password'/*, 'language'*/));
					}

					if ($ret) {
						//$this->changeLanguageSession($this->request->data['User']['language']);

						$this->Session->setFlash(__('Profile was successfully updated.'), FLASH_OK);
						$this->redirect(array('controller' => 'users', 'action' => 'profile'));
					}
					else {
						$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
					}
				}
			}
			$this->request->data['User']['old_pass'] = $this->request->data['User']['pass'] = $this->request->data['User']['pass2'] = '';
		}
		else {
			$this->request->data = $user;
		}
	}

	/**
	  * Method send email with link for password change.
	  */
	public function resetpassword() {
		$this->set('title_for_layout', __('Did you forget your password?'));
		$this->layout = 'login';

		if (!empty($this->request->data)) {
			$this->request->data['User']['email'] = Purifier::clean($this->request->data['User']['email'], 'Strict');
			
			//find user
			$user = $this->User->find('first', array (
				'conditions' => array(
					'User.email'  => $this->request->data['User']['email'],
					'User.status' => USER_ACTIVE
				),
				'fields' => array('User.email'),
				'recursive' => -1
			));

			if (empty($user)) {
				//throw new NotFoundException();
				$this->Session->setFlash(__('Account with email address you entered does not exist. Please try again.'), FLASH_ERROR);
				return false;
			}

			$this->loadModel('Ticket');

			//create hash
			$ticketHash = $this->Ticketmaster->createHash();

			//data for email
			$emailData = array(
				'token' => $ticketHash,
				'emailTitle' => __('Reset your password')
			);
			$emailResult = false;

			//data for ticket
			$data = array();
			$data['Ticket']['hash'] = $ticketHash;
			$data['Ticket']['data'] = $user['User']['email'];
			$data['Ticket']['expires'] = $this->Ticketmaster->getExpirationDate();

			//save ticket
			if (($ticketResult = $this->Ticket->save($data))) {
				//send the email with ticket
				$email = $this->initEmail($user['User']['email'], __('Reset Your Password'), 'reset_password', $emailData);
				$emailResult = $email->send();
			}

			if ($ticketResult && $emailResult) {
				$this->Session->setFlash(__('We have sent to your email address the link to change your password. Please check your email.'), FLASH_OK);

				$this->request->data = null;
			}
			else {
				if (!$emailResult) {
					$this->Session->setFlash(__('Error occured while trying to send the email. Check your email configurations and try again.'), FLASH_ERROR);
				}
				else {
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			}

		}
	}

	/**
	  * Check ticket and use it if valid
	  *
	  * @param string $hash
	  */
	public function useticket($hash = null) {
		$this->set('title_for_layout', __('Password change'));
		$this->layout = 'login';
		$this->loadModel('Ticket');

		if ($this->request->is('post')) {
			$this->_doPasswordValidation();

			$this->User->set($this->request->data);
			if ($this->User->validates(array('fieldList' => array('pass')))) {

				//najdeme ticket pre dany hash
				$ticket = $this->Ticketmaster->checkTicket($this->request->data['User']['hash']);

				//ak sme nasli ticket
				if (!empty($ticket)) {
					//najdeme usera s danym emailom
					$user = $this->User->find('first', array(
						'conditions' => array(
							'User.email'  => $ticket['Ticket']['data'],
							'User.status' => USER_ACTIVE
						),
						'recursive' => -1
					));

					//ak taky user existuje
					if (!empty($user)) {

						$this->User->id = $user['User']['id'];
						// $ret = (bool) $this->User->saveField('password', Security::hash($this->request->data['User']['pass']));
						$ret = $this->User->save(null, true, array('password'));

						//ulozime uzivatela s novym heslom
						if ($ret) {
							//oznavime ticket ako pouzity, aby sa uz nedal pouzit znovu
							$this->Ticketmaster->useTicket($this->request->data['User']['hash']);

							$this->Session->setFlash(__('Your password was successfully changed. Now you can login again.'), FLASH_OK);
							$this->redirect(array('controller' => 'users', 'action' => 'login'));
						}
						else {
							$this->Session->setFlash(__('Error happened while processing your request. Please try again.'), FLASH_ERROR);
						}
					}
					else {
						$this->Session->setFlash(__('Requested ticket is invalid. Please contact the support center.'), FLASH_ERROR);
						$this->redirect(array('controller' => 'users', 'action' => 'login'));
					}
				}
				else{
					$this->Session->setFlash(__('Requested ticket is invalid. Please try the password recovery process again.'), FLASH_ERROR);
					$this->redirect(array('controller' => 'users', 'action' => 'login'));
				}
			}
			$this->request->data['User']['pass'] = $this->request->data['User']['pass2'] = '';
		}
		else {
		//skontrolujeme ci ticket existuje
			$ticket = $this->Ticketmaster->checkTicket($hash);

			if (!empty($ticket)) {
				//najdeme uzivatela s danym emailom
				$user = $this->User->find('first', array(
					'conditions' => array(
						'User.email'  => $ticket['Ticket']['data'],
						'User.status' => USER_ACTIVE
					),
					'fields' => array('User.email'),
					'recursive' => -1
				));

				if (!empty($user)) {
					$this->request->data['User']['hash'] = $hash;
				}
				else {
					$this->Session->setFlash(__('Requested ticket is invalid. Please try the password recovery process again.'), FLASH_ERROR);
					$this->redirect(array('controller' => 'users', 'action' => 'login'));
				}
			}
			else {
				$this->Session->setFlash(__('Requested ticket is invalid. Please try the password recovery process again.'), FLASH_ERROR);
				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
		}
	}

	public function login() {
		$this->set('title_for_layout', __('Login'));
		$this->layout = 'login';

		if ($this->logged != null) {
			$this->redirect($this->Auth->loginRedirect);
		}

		if ($this->request->is('post') || isset($this->request->query['authuser'])) {
			// $this->request->data['User']['login'] = Purifier::clean($this->request->data['User']['login'], 'Strict');
			// $this->request->data['User']['password'] = Purifier::clean($this->request->data['User']['password'], 'Strict');
			
			if ($this->request->is('post')) {
				$this->checkBruteForce();
			}

			if ($this->Auth->login()) {
				$this->logLogin(true);
				$userId = $this->Auth->user('id');

				$dataSource = $this->Setting->getDataSource();
				$dataSource->begin();
				
				if ($this->sendLoginRequest()) {
					$dataSource->commit();
				}
				else {
					$dataSource->rollback();

					$this->Session->setFlash(__('Internet connection is not available. Please try again.'), FLASH_ERROR);
					$this->logout();
					exit;
				}

				$this->Session->write('UserLogged', true);

				// if default language could not be saved automatically after successful login
				if (!$this->saveChosenLanguage()) {
					// $this->Session->setFlash(__('Problem occured while saving default language to your account. Plaease, try again in your Profile page.'), FLASH_ERROR);
				}
				
				return $this->redirect($this->Auth->redirect());
			}
			else {
				$this->logLogin(false);

				// we put different warning for ldap login issue
				$login = $this->getLoginFormData('login');
				$notLocalUser = $this->User->find('count', array(
					'conditions' => array(
						'User.login' => $login,
						'User.local_account' => 0
					),
					'recursive' => -1
				));

				$ldapErr = $this->getLdapLoginError();

				// if dealing with user account that is NOT created locally while non-local authentication is disabled
				if (!empty($notLocalUser) && empty($this->ldapAuth['LdapConnectorAuthentication']['auth_users']) && empty($this->ldapAuth['LdapConnectorAuthentication']['oauth_google'])) {
					$errorMsg = __('Your account is configured to be authenticated using LDAP or OAuth which is disabled at the moment.');
				}
				// trying to login a non local user when remote server configuration fails
				elseif (!empty($notLocalUser) && !empty($ldapErr)) {
					$errorMsg = $ldapErr;
				}
				elseif (!empty($notLocalUser) && !empty($this->ldapAuth['LdapConnectorAuthentication']['oauth_google'])) {
					$errorMsg = __('Your account is configured to be authenticated using OAuth. Only way how you can login is via "Sign in with Google" button.');
				}
				// other cases of failure
				else {
					$errorMsg = __('The system was unable to log you in. Check that your username and password are typed correctly.');
				}

				$this->Session->setFlash($errorMsg, FLASH_ERROR);
			}
		}

		$this->set("OauthGoogleAllowed", $this->OauthGoogleAuth->isOauthGoogleAllowed());
		$this->set("OauthGoogleAuthUrl", $this->OauthGoogleAuth->getSanitizedAuthUrl());
	}

	private function getLoginFormData($which)
	{
		$data = null;
		$loginFormDataKeys = array('login', 'password', 'language');
		if (in_array($which, $loginFormDataKeys)) {
			$data = isset($this->request->data['User'][$which]) ? $this->request->data['User'][$which] : null;
		}

		return $data;
	}

	/**
	 * Saves language chosen on the login page.
	 *
	 * @return bool
	 */
	private function saveChosenLanguage() {
		$ret = true;

		$lang = $this->getLoginFormData('language');
		$login = $this->getLoginFormData('login');
		if (langExists($lang)) {
			$user = $this->User->find('first', array(
				'conditions' => array(
					'User.login' => $login
				),
				'fields' => array('id', 'language'),
				'recursive' => -1
			));

			if ($user['User']['language'] != $lang) {
				$this->User->id = $user['User']['id'];
				$ret &= $this->User->saveField('language', $lang);
				$ret &= $this->changeLanguageSession($lang);
			}
		}

		return $ret;
	}

	/**
	 * Sends a request about a user login with app ID information.
	 */
	private function sendLoginRequest() {
		$clientId = CLIENT_ID;

		if (empty($clientId)) {
			$clientId = $this->getClientId();
			if (empty($clientId)) {
				return false;
			}

			if (STATS_REQUEST && !empty($clientId)) {
				$this->ErambaHttp = $this->Components->load('ErambaHttp');
				return $this->ErambaHttp->registerClientID($clientId);
			}
		}

		return true;
	}

	/**
	 * Creates and returns client ID.
	 */
	private function getClientId() {
		$clientId = CLIENT_ID;

		if (empty($clientId)) {
			$clientId = Security::hash(microtime(true) . Configure::read('Eramba.version') . mt_rand(1, 999), 'sha1');
			if (!$this->Setting->updateVariable('CLIENT_ID', $clientId)) {
				return false;
			}
		}

		return $clientId;
	}

	private function checkBruteForce() {
		$login = $this->request->data['User']['login'];

		$this->User->bindModel( array(
			'hasMany' => array(
				'SystemRecord' => array(
					'foreignKey' => 'foreign_key',
					'conditions' => array(
						'SystemRecord.model' => 'User'
					)
				),
				'UserBan'
			)
		) );
		$this->User->Behaviors->attach('Containable');

		$fromTime = CakeTime::format( 'Y-m-d H:i:s', CakeTime::fromString( '-' . BRUTEFORCE_SECONDS_AGO . ' seconds' ) );
		$user = $this->User->find( 'first', array(
			'fields' => array('id', 'blocked'),
			'conditions' => array(
				'User.login' => $login
			),
			'contain' => array(
				'SystemRecord' => array(
					'conditions' => array(
						'SystemRecord.created >' => $fromTime,
						'SystemRecord.type' => 5
					)
				),
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

		if ( ! empty( $user['UserBan'] ) ) {
			$this->Session->setFlash(__('You are not allowed to login until ' . $user['UserBan'][0]['until']), FLASH_ERROR);
			$this->redirect( array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => null) );
			exit;
		}

		if ( count( $user['SystemRecord'] ) >= BRUTEFORCE_WRONG_LOGINS ) {
			$this->loadModel( 'UserBan' );
			$until = CakeTime::format( 'Y-m-d H:i:s', CakeTime::fromString( '+' . BRUTEFORCE_BAN_FOR_MINUTES . ' minutes' ) );
			$this->UserBan->set( array(
				'user_id' => $user['User']['id'],
				'until' => $until
			) );
			$this->UserBan->save();

			$this->User->saveBlockedField($user['User']['id'], '1');

			$this->Session->setFlash(__('You are not allowed to login because you tried it too many times.'), FLASH_ERROR);
			$this->redirect( array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => null) );
			exit;
		}

		if ($user['User']['blocked']) {
			$this->User->saveBlockedField($user['User']['id'], '0');
		}
	}

	private function bruteForceRedirect() {
		$this->Session->setFlash(__('You are not allowed to login because you tried it too many times.'), FLASH_ERROR);
		$this->redirect( array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => null) );
		exit;
	}

	/**
	 * Record this login attempt into system records.
	 */
	private function logLogin($success = true) {
		$login = $this->getLoginFormData('login');
		$user = $this->User->find( 'first', array(
			'fields' => array( 'id' ),
			'conditions' => array(
				'User.login' => $login
			),
			'recursive' => -1
		) );

		if (!empty($user)) {
			$this->User->Behaviors->load('SystemLog');
			($this->User->setSystemRecord($user['User']['id'], ($success ? 4 : 5)));
		}
	}

	public function logout() {
		$this->OauthGoogleAuth->logout();
		$this->redirect($this->Auth->logout());
	}

	private function initData() {
		$groups = $this->User->Group->find('list', array(
			'order' => array('Group.name' => 'ASC'),
			'recursive' => -1
		));

		$this->set('groups', $groups);
	}

	/**
	 * Load LDAP user data while adding new user.
	 */
	public function chooseLdapUser($ldapUser) {
		$this->allowOnlyAjax();

		if (empty($this->ldapAuth['LdapConnectorAuthentication']['auth_users'])) {
			return false;
		}
		
		$connector = $this->ldapAuth['AuthUsers'];
		$connector['_ldap_auth_filter_username_value'] = $ldapUser;

		$ldapConnection = $this->LdapConnectorsMgt->ldapConnect($connector['host'], $connector['port'], $connector['ldap_bind_dn'], $connector['ldap_bind_pw']);
		$this->set('ldapConnection', $ldapConnection);
		
		if ($ldapConnection !== true) {
			return false;
		}
		
		$user = array_values($this->LdapConnectorsMgt->getData($connector));
		
		$this->add();

		if (isset($user[0]) && !empty($user[0])) {
			if (!empty($connector['ldap_auth_attribute']) && isset($user[0][$connector['ldap_auth_attribute']])) {
				$this->request->data['User']['login'] = $user[0][$connector['ldap_auth_attribute']];
			}

			if (!empty($connector['ldap_name_attribute']) && isset($user[0][$connector['ldap_name_attribute']])) {
				$ldapName = trim($user[0][$connector['ldap_name_attribute']]);

				// split the ldap name in case there is a blank space and fill in surname field as well
				if (strpos($ldapName, ' ') !== false) {
					$explode = explode(' ', $ldapName, 2);
					$ldapName = $explode[0];
					$this->request->data['User']['surname'] = $explode[1];

				}
				$this->request->data['User']['name'] = $ldapName;
			}

			if (!empty($connector['ldap_email_attribute']) && isset($user[0][$connector['ldap_email_attribute']])) {
				$this->request->data['User']['email'] = $user[0][$connector['ldap_email_attribute']];
			}
			elseif (isset($connector['domain']) && !empty($connector['domain']) && !empty($this->request->data['User']['login'])) {
				$this->request->data['User']['email'] = $this->request->data['User']['login'].'@'.$connector['domain'];
			}
			
		}

		$this->request->data['User']['ldap_user'] = $ldapUser;
		$this->render('add_form');
	}

	/**
	 * Wrapper function to cahnge language.
	 */
	public function changeLanguage($lang) {
		$availableLangs = availableLangs();

		// if language exists save it in the session
		if (langExists($lang)) {
			$this->changeLanguageSession($lang);
		}

		return $this->redirect($this->referer());
	}

	/**
	 * Unblock user that has a brute force ban.
	 */
	public function unblock($userId) {
		$userId = Purifier::clean($userId, 'Strict');

		$user = $this->User->find('count', array(
			'conditions' => array(
				'User.id' => $userId
			),
			'recursive' => -1
		) );

		if (!empty($user)) {
			$dataSource = $this->User->getDataSource();
			$dataSource->begin();

			$ret = $this->User->unblockBan($userId);
			if ($ret) {
				$dataSource->commit();
				$this->Session->setFlash(__('User was successfully unlocked.'), FLASH_OK);
			}
			else {
				$dataSource->rollback();
				$this->Session->setFlash(__('Error occured while trying to unlock the user. Please try again.'), FLASH_ERROR);
			}
		}
		else {
			$this->Session->setFlash(__('User not found. Please try again.'), FLASH_ERROR);
		}

		$this->redirect(array('controller' => 'users', 'action' => 'index', 'admin' => false, 'plugin' => null));
	}

	/**
	 * Find out if a given user is ADMIN or if he has assigned an ADMIN group
	 * @return boolean
	 */
	protected function isUserAdmin()
	{
		if ($this->logged === null) {
			return false;
		}

		return $this->logged['id'] == ADMIN_ID || (isset($this->logged['Groups']) && is_array($this->logged['Groups']) && in_array(ADMIN_GROUP_ID, $this->logged['Groups']));
	}

	/**
	 * Checks conflicts in selected groups and renders informational box if there is any problem
	 * 
	 * @return void
	 */
	public function checkConflicts() {
		$groups = isset($this->request->query['groups']) ? $this->request->query['groups'] : [];
		$Acl = $this->AppAcl->Acl->adapter();
		$aroIDs = $Acl->Aro->find('list', [
			'conditions' => [
				'Aro.foreign_key' => $groups
			],
			'fields' => [
				'Aro.id'
			],
			'recursive' => -1
		]);
		$conflicts = $Acl->conflicts($aroIDs);
		
		$this->set(compact('conflicts'));
		return $this->render('../Elements/users/group_conflicts');
	}
}
