<?php
App::uses('Controller', 'Controller');
App::uses('Setting', 'Model');
App::uses('AdvancedFilter', 'Model');
App::uses('AdvancedFilterUserSetting', 'Model');
App::uses('ErambaCakeEmail', 'Network/Email');
App::uses('Hash', 'Utility');
App::uses('Inflector', 'Utility');
App::uses('CrudControllerTrait', 'Crud.Lib');

class AppController extends Controller {

	use CrudControllerTrait;

	public $uses = array('Setting', 'LdapConnectorAuthentication', 'Notification');
	public $components = array(
		'Auth' => array(
			'className' => 'AppAuth',
			'authorize' => array(
				'AppActions' => array('actionPath' => 'controllers/')
			)
		),
		'RequestHandler', 'Cookie', 'AppSession', 'Flash', 'DebugKit.Toolbar' => [
			'panels' => [
				'DebugKit.History' => false,
				'DebugKit.Variables' => false,
				'AppVariables'
			],
			'cache' => [
				'duration' => '+10 minutes'
			]
		], 'Acl', 'AppAcl', 'Menu', 'News', 'OauthGoogleAuth'
	);
	public $helpers = array('Html', 'Form', 'Video', 'Ux', 'FieldData.FieldData', 'Visualisation.Visualisation');
	protected $logged = null;

	public $title = null;
	public $subTitle = null;

	// compatibility after removing mappingcomponent
	private $recordsHandled = false;

	/**
	 * Extended method for constructing classes.
	 */
	public function constructClasses() {
		parent::constructClasses();

		// until all Session->setFlash() -related syntax is moved to Flash Component, we have to replace it,
		// because SessionComponent was causing issues on the latest CakePHP v2.10.0 in conjunction with FlashHelper
		$loaded = $this->Components->set('Session', $this->AppSession);
		$this->Session = $loaded['Session'];
		return true;
	}
	
	public function beforeFilter()
	{
		//
		// Initial core configuration for all controllers
		//
		
		$this->_setupSecurity();

		// Setup common authentication settings
		$this->_setupCommonAuthenticationSettings();

		if ($this->request->is('api') && !$this->request->is('ajax')) {
			echo "Api requests are not allowed in community version";
			exit;
		} else {
			$this->_setupAuthentication();
			$this->_setupDefaultCookies();
		}

		$this->_setupLanguages();
		$this->_afterSetup();

		// $this->Components->disable('Debugkit.Toolbar');
	}

	/**
	 * Settings for both authentication objects: Form and Basic
	 */
	protected function _setupCommonAuthenticationSettings()
	{
		$this->Auth->authenticate = array(
			AuthComponent::ALL => array(
				'userModel' => 'User',
				'fields' => array(
					'username' => 'login',
					'password' => 'password'
				),
				'passwordHasher' => 'Blowfish'
			)
		);
	}

	/**
	 * For external features with configurations, this allows to do a quick check,
	 * if current user has ACL access to settings section.
	 * 
	 * @return boolean  True with access, False otherwise with redirection as well.
	 */
	protected function checkSettingsAccess() {
		$aclCheck = [
			'plugin' => null,
			'controller' => 'settings',
			'action' => 'index'
		];

		if (!$this->AppAcl->check($aclCheck)) {
			$this->Auth->flash($this->Auth->authError);
			$this->Session->write('Auth.redirect', $this->request->here(false));
			$this->redirect($this->Auth->loginAction);
			return false;
		}

		return true;
	}

	/**
	 * Initializes security component with configuration for the whole app.
	 */
	protected function _setupSecurity() {
		//using blowfish algoritm
		Security::setHash('blowfish');

		$this->Security = $this->Components->load('Security');

		// default actions used in more than one place
		// @todo remove this and handle in more dry way
		$this->Security->unlockedActions = array(
			'getThreatsVulnerabilities',
			'calculateRiskScoreAjax',
			'auditCalendarFormEntry'
		);

		// for debugging we provide a possibility to toggle/disable security for the entire app.
		if (Configure::read('Eramba.DISABLE_SECURITY')) {
			// we unlock current request
			$this->Security->unlockedActions[] = $this->request->params['action'];
			$this->Security->validatePost = false;
			$this->Security->csrfCheck = false;

			return true;
		}

		$this->Security->validatePost = false;
		if ($this->request->is('api')) {
			$this->Security->csrfCheck = false;
		}
				
		if (Configure::check('Session.timeout')) {
			// Session timeout duration (core.php) should match with csrfExpires value
			$this->Security->csrfExpires = '+' . Configure::read('Session.timeout') . ' minutes';
		}
	}

	/**
	 * Setup authentication functionality for the app.
	 */
	protected function _setupAuthentication() {
		// no flash message error when user is not authorized to access action
		// @todo will be updated during new Ux updates.
		// $this->Auth->authError = __('Your session probably expired and you have been logged out of the application.');
		// $this->Auth->authError .= ' ' .__('It\'s also possible you are not authorized to access this location.');
		$this->Auth->authError = false;
		$this->Auth->flash['element'] = 'error';
		$this->Auth->flash['key'] = 'flash';

		if ($this->isAjax()) {
			// during ajax throw ForbiddenException rather than do redirect when user is unathorized
			$this->Auth->unauthorizedRedirect = false;
		}

		// default login redirect actions
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => null);
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login', 'admin' => false, 'plugin' => null);

		// login redirect action based on user's group
		if (isAdmin($this->Auth->user())) {
			$this->Auth->loginRedirect = [
				'plugin' => 'dashboard',
				'controller' => 'dashboard_kpis',
				'action' => 'admin'
			];
		}
		else {
			$this->Auth->loginRedirect = [
				'plugin' => 'dashboard',
				'controller' => 'dashboard_kpis',
				'action' => 'user'
			];
		}

		// dd($this->Auth->)

		// Default scope for logging in
		$scope = array(
			'User.status' => USER_ACTIVE
		);

		//
		// Check if user is Admin
		$isAdmin = false;
		if (isset($this->request->data['User']['login']) && $this->request->data['User']['login'] === 'admin') {
			$isAdmin = true;
		}
		//
		
		$ldapAuth = $this->LdapConnectorAuthentication->getAuthData();
		if (isset($this->request->data['User']['login'])) {
			if (!$isAdmin) {
				$authUsers = $ldapAuth['LdapConnectorAuthentication']['auth_users'];

				if ($authUsers) {
					$this->_initLdapAuth($ldapAuth['AuthUsers'], 'User', 'eramba');
				}
			}
		} else {
			/**
			 *
			 * 
			 * Temporary solution
			 *
			 * 
			 */
			if (!isset($ldapAuth['LdapConnectorAuthentication']['oauth_google'])) {
				$this->loadModel('Setting');
				$this->Setting->deleteCache(null);
				$ldapAuth = $this->LdapConnectorAuthentication->getAuthData();
			}
			///////////////////////////////////////////////////////////////////////
			
			$oauthGoogle = $ldapAuth['LdapConnectorAuthentication']['oauth_google'];
			if ($oauthGoogle) {
				// Only external users can be logged in through OAuth
				$scope['User.local_account'] = 0;

				// Initialize OAuth authentication
				$this->Auth->authenticate['OauthGoogle'] = array(
					'gClient' => $this->OauthGoogleAuth->getGoogleClient(),
					'userModel' => 'User',
					'sessionKey' => $this->OauthGoogleAuth->getSessionKey(),
					'tokenSessionKey' => $this->OauthGoogleAuth->getTokenSessionKey(),
					'fields' => array(
						'username' => 'email'
					),
					'scope' => $scope
				);

				// Set redirect URL
				$this->OauthGoogleAuth->setRedirectUrl($this->Auth->loginAction);
			}
		}

		//
		// Setup Form Authentication
		if (!$isAdmin) {
			$scope['User.local_account'] = 1;
		}
		$this->Auth->authenticate['Form'] = array(
			'scope' => $scope
		);
		//
	}

	/**
	 * Generally handles language configuration.
	 */
	protected function _setupLanguages() {
		if (!$this->Session->check('Config.language') && !empty($this->logged['language'])) {
			$this->Session->write('Config.language', $this->logged['language']);
		}

		if ($this->Session->check('Config.language')) {
			Configure::write('Config.language', $this->Session->read('Config.language'));
		}
	}

	/**
	 * Manages cookies setup with custom options.
	 */
	protected function _setupDefaultCookies() {
		$this->Cookie->name   = 'ErambaCookie';
		$this->Cookie->time   = '+2 weeks';
		$this->Cookie->domain = 'eramba.localhost';
		$this->Cookie->key	= 'k886fQz1O787u4r4q07DGvLkjTMP4VZ2pU1wA934Sxsm934mRa';
		
		if (HTTP_PROTOCOL == 'https://') {
			$this->Cookie->secure = true;
		}
		else {
			$this->Cookie->secure = false;
		}

		$this->Cookie->type('rijndael');
	}

	/**
	 * After controller setup process.
	 *
	 * @todo  remove this and improve it
	 */
	protected function _afterSetup() {

		$this->logged = $this->Auth->user();
		$this->currentModel = $this->modelClass;

		if (!$this->request->is('api'))
		{
			$this->set('logged', $this->logged);
			$this->set('currentModel', $this->currentModel);

			$this->ldapAuth = $this->LdapConnectorAuthentication->getAuthData();
			$this->set('ldapAuth', $this->ldapAuth);

			$this->_afterLoginCheck();

			if ($this->Auth->loggedIn()) {
				$this->_currentAuthExtras();
			}
			else {
				$this->set('menuItems', []);
			}

			$this->set('layout_headerPath', CORE_ELEMENT_PATH . 'header');
			$this->set('layout_toolbarPath', CORE_ELEMENT_PATH . 'toolbar');
			$this->set('layout_pageHeaderPath', CORE_ELEMENT_PATH . 'page_header');
		}

		// for auditable log behavior
		if (!empty($this->request->data) && empty($this->request->data[$this->Auth->userModel])) {
			$user['User']['id'] = $this->Auth->user('id');
			$this->request->data[$this->Auth->userModel] = $user;
		}
	}

	/**
	 * Handles only specifics required for current authenticated login portal.
	 * @todo  improve this
	 */
	protected function _currentAuthExtras() {
		// eramba app specifics
		$this->set('menuItems', $this->Menu->getMenu($this->logged['Groups'], Configure::read('Config.language')));
		if (isset($this->{$this->modelClass}->mapping)) {
			$this->set('notificationSystemEnabled', (bool) $this->{$this->modelClass}->mapping['notificationSystem']);
		}
		$this->_setNotifications();
		$this->_setNews();
	}

	protected function _setNotifications() {
		$data = $this->Notification->find('all', array(
			'conditions' => array(
				'Notification.user_id' => $this->logged['id'],
				'Notification.status' => 1
			),
			'order' => array('Notification.created' => 'DESC'),
			'recursive' => -1
		));

		$this->set('newNotifications', $data);
	}

	protected function _setNews() {
		$this->set('shortNews', $this->News->get());
		$this->set('unreadedNewsCount', $this->News->getUnreadedCount());
	}

	/**
	 * Modify session on the fly to reflect and display selected language.
	 */
	protected function changeLanguageSession($lang) {
		if (!langExists($lang)) {
			return false;
		}

		if ($this->logged) {
			$this->Session->write('Auth.User.language', $lang);
		}

		$this->Session->write('Config.language', $lang);

		return true;
	}

	/**
	 * Generic way to retrieve an LdapAuthenticate error message that could happen during user login.
	 * 
	 * @return mixed Message string or False if there are no more details about the error.
	 */
	protected function getLdapLoginError() {
		if (!isset($this->Auth->authenticate['LDAP'])) {
			return false;
		}
		
		$userModel = $this->Auth->authenticate['LDAP']['userModel'];
		$this->loadModel($userModel);
		$model = $this->{$userModel};

		if (isset($model->loginErrorMsg) && !empty($model->loginErrorMsg)) {
			return $model->loginErrorMsg;
		}

		return false;
	}

	public function _getLdapLoginError() {
		return $this->getLdapLoginError();
	}

	/**
	 * Initialize LDAP Authentication based on connector for any section/controller.
	 */
	protected function _initLdapAuth($connector, $userModel, $loginType) {
		$this->Auth->authenticate['LDAP'] = array(
			// the entire connector data
			'LdapConnector' => $connector,

			// other parameters, some of them will be removed in the future
			'ldap_url'		 => $connector['host'],
			'ldap_bind_dn'	 => $connector['ldap_bind_dn'],
			'ldap_bind_pw'	 => $connector['ldap_bind_pw'],
			'ldap_base_dn'	 => $connector['ldap_base_dn'],
			'ldap_filter'	 => $connector['ldap_auth_filter'],
			'ldap_attribute' => $connector['ldap_auth_attribute'],
			'ldap_memberof_attribute' => $connector['ldap_memberof_attribute'],
			'form_fields'	 => array('username' => 'login', 'password' => 'password'),
			'userModel' 	 => $userModel,
			'loginType' => $loginType
		);
	}

	// compatibility after removing mappingcomponent
	public function beforeRender() {
		// compatibility
		$this->handleSystemRecords();
	}

	// compatibility after removing mappingcomponent
	protected function handleSystemRecords($cron = false) {
		if ($this->recordsHandled) {
			return true;
		}

		$ret = true;

		App::uses('SystemLogBehavior', 'Model/Behavior');
		SystemLogBehavior::$isCron = $cron;
		foreach (SystemLogBehavior::$Models as $model) {
			//debug($model->name);
			$ret &= $model->handleSystemRecords();
		}
		SystemLogBehavior::$isCron = true;

		if ($ret) {
			$this->recordsHandled = true;
		}

		return $ret;
	}

	protected function forceSSL() {
		$this->redirect('https://' . env('SERVER_NAME') . $this->here);
		exit;
	}

	public function isAuthorized() {
		return true;
	}

	/**
	 * @deprecated Moved to bootstrap functions.
	 */
	protected function isSSL(){
		return isSSL();
	}

	/**
	 * Certain actions taking place right after a successful login. System health checking for example.
	 *
	 * @todo  process this using afterIdentify() callback ?
	 */
	protected function _afterLoginCheck() {
		if ($this->Session->check('UserLogged') && $this->Session->read('UserLogged')) {
			$this->Session->delete('UserLogged');
			$systemHealthComponent = $this->Components->load('SystemHealth');
			$systemHealthComponent->startup($this);

			$systemHealthData = $systemHealthComponent->checkCriticalStatuses();

			$autoUpdateComponent = $this->Components->load('AutoUpdate');
			$autoUpdateComponent->initialize($this);
			
			$this->set('userJustLogged', true);
			$this->set('systemHealthData', $systemHealthData);
			$this->set('autoUpdatePending', $autoUpdateComponent->hasPending());
		}
		else {
			$this->set('userJustLogged', false);
		}
	}
	
	/**
	 * @deprecated e1.0.6.021 Core email configuration is now built in ErambaCakeEmail::buildErambaConfig(), see SettingsController::testMailConnection().
	 */
	protected function initEmail($to, $subject, $template, $data = array(), $layout = 'default', $from = NO_REPLY_EMAIL, $type = 'html', $smtpSettings = array(), $instant = false) {

		$email = new ErambaCakeEmail('default');

		if (!is_array($to)) {
			$to = array_map('trim', explode(',', $to));
		}

		$email->to($to);
		$email->subject($subject);
		$email->template($template);
		$email->instant($instant);

		if (!empty($data)) {
			$email->viewVars($data);
		}

		return $email;
	}

	protected function getPageLimit() {
		//if the limit was changed
		if (isset($this->request->params['named']['limit']) && is_numeric($this->request->params['named']['limit'])) {
			$limit = $this->request->params['named']['limit'];
			Cache::write('page_limit_' . $this->logged['id'], $limit, 'infinite');
		}
		//if the cache is invalid
		elseif (($limit = Cache::read('page_limit_' . $this->logged['id'], 'infinite')) === false) {
			$limit = DEFAULT_PAGE_LIMIT;
		}

		$this->set('currPageLimit', $limit);

		return $limit;
	}

	/**
	 * Returns array of users with full names used in select inputs.
	 * @return array User list.
	 * @todo  move this away from appcontroller.
	 */
	protected function getUsersList($includeLogged = true) {
		$conds = array();
		if (!$includeLogged) {
			$conds['User.id !='] = $this->logged['id'];
		}

		$this->loadModel('User');
		$users_all = $this->User->find('all', array(
			'conditions' => $conds,
			'order' => array('User.name' => 'ASC'),
			'fields' => array('User.id', 'User.name', 'User.surname'),
			'recursive' => -1
		));

		$users = array();
		foreach ( $users_all as $user ) {
			$users[ $user['User']['id'] ] = $user['User']['name'] . ' ' . $user['User']['surname'];
		}

		return $users;
	}

	/**
	 * Returns search query in url.
	 */
	protected function getSearchQuery() {
		if ( isset( $this->request->query['search'] ) && $this->request->query['search'] != '' ) {
			$keyword = '%' . $this->request->query['search'] . '%';
			return $keyword;
		}

		return false;
	}

	/**
	 * Returns array of only Released security policies.
	 * @return array Security policy list.
	 * @todo  move from appcontroller
	 */
	protected function getSecurityPoliciesList() {
		$this->loadModel('SecurityPolicy');

		$data = $this->SecurityPolicy->getListWithType(['SecurityPolicy.status' => SECURITY_POLICY_RELEASED]);

		return $data;
	}

	/**
	 * @todo  move from appcontroller
	 */
	protected function getDayDiffFromToday( $date = false ) {
		if ( ! $date ) {
			return false;
		}

		$today = CakeTime::format( 'Y-m-d', CakeTime::fromString( 'now' ) );
		$datetime1 = new DateTime( $today );
		$datetime2 = new DateTime( $date );
		$interval = $datetime1->diff( $datetime2 );
		$diff = $interval->format( '%a' );

		return $diff;
	}

	/**
	 * Convert timestamp to javascript default date format - new Date().
	 * @todo  move away from appcontroller
	 */
	protected function toJsDateFormat( $timestamp = null ) {
		if ( ! $timestamp ) {
			$timestamp = CakeTime::fromString( 'now' );
		}

		return CakeTime::format( 'D M d Y H:i:s O', $timestamp );
	}

	/**
	 * Checks if date is expired.
	 * @todo  move from here
	 */
	protected function isExpired( $date = null, $status = null ) {
		$today = CakeTime::format( 'Y-m-d', CakeTime::fromString( 'now' ) );
		if ( $status !== null ) {
			if ( $date < $today && $status == 1 ) {
				return true;
			}
		} else {
			if ( $date < $today ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Calculate residual risk.
	 * @param  int $residual_score Residual Score.
	 * @param  int $risk_score     Risk Score.
	 * @return int                 Residual Risk.
	 * @todo  move away from appcontroller
	 */
	protected function getResidualRisk($residual_score, $risk_score) {
		return CakeNumber::precision(getResidualRisk($residual_score, $risk_score), 2);
	}

	/**
	 * Get index/custom page array url based on model, id and model settings.
	 *
	 * @param  string $model  Model name of the item.
	 * @param  int    $id     ID of the item.
	 * @param  string $action Use custom action page, otherwise list items on index based on Model's mapping settings.
	 * @return array          Redirect URL.
	 * @todo  remove
	 */
	protected function getIndexUrl($model, $id = null, $action = 'index') {
		$this->loadModel($model);
		if ($action == 'index') {
			if (!$this->{$model}->mapping['indexController']) {
				$controller = Inflector::variable(Inflector::tableize($model));
				$url = array('controller' => $controller, 'action' => 'index');
			}
			else {
				$map = $this->{$model}->mapping['indexController'];

				// if model has an array of custom settings for this mapping and ID provided.
				if (is_array($map) && $id) {
					$contain = false;
					if (isset($map['crawl'])) {
						$contain = $map['crawl'];
					}

					if ($this->{$model}->mapping['workflow'] !== false) {
						$this->{$model}->alterQueries(true);
					}

					$data = $this->{$model}->find('first', array(
						'conditions' => array(
							$model . '.id' => $id
						),
						'contain' => $contain
					));

					if ($this->{$model}->mapping['workflow'] !== false) {
						$this->{$model}->alterQueries();
					}

					$extraParams = array();
					if (!empty($map['params'])) {
						// parse params required for the Url based on model mapping.
						$extraParams = $this->getParams($data, $map['params']);
					}

					if ($action == 'index' && isset($map['action'])) {
						$action = $map['action'];
					}

					$urlBase = array('controller' => $map['advanced'], 'action' => $action);
					$url = am($urlBase, $extraParams);
				}
				elseif (is_array($map) && !$id) {
					$url = array('controller' => $map['basic'], 'action' => 'index');
				}
				else {
					$url = array('controller' => $map, 'action' => 'index', $id);
				}
			}
		}
		else {
			$map = $this->{$model}->mapping['indexController'];

			if (!$map) {
				$controller = Inflector::variable(Inflector::tableize($model));
				$url = array('controller' => $controller, 'action' => $action, $id);
			}
			else {
				if (is_array($map)) {
					if (isset($map['action'])) {
						$action = $map['action'];
					}

					$url = array('controller' => $map['advanced'], 'action' => $action, $id);
				}
				else {
					$url = array('controller' => $map, 'action' => $action, $id);
				}
			}
		}

		return $url;
	}

	private function getParams($arr, $params) {
		$values = array();
		foreach ($params as $param) {
			$values = $this->getParamsFinder($arr, $param, $values);
		}

		return $values;
	}

	private function getParamsFinder($arr, $param, $values) {
		$keys = array_keys($arr);
		if (in_array($param, $keys, true)) {
			$values[] = $arr[$param];
		}
		foreach ($keys as $key) {
			if (is_array($arr[$key])) {
				$values = $this->getParamsFinder($arr[$key], $param, $values);
			}
		}

		return $values;
	}

	/**
	 * Sends user to home after hitting unavailable request.
	 */
	protected function actionUnavailable($url = array('controller' => 'pages', 'action' => 'welcome')) {
		$this->Session->setFlash(__('Required action is not available.'), FLASH_ERROR);
		$this->redirect($url);
		exit;
	}

	protected function getScopesOptions() {
		$this->loadModel('Scope');
		$scope = $this->Scope->find('first', array(
			'recursive' => -1
		));

		$scopes = array();
		if (!empty($scope)) {
			if (!empty($scope['Scope']['ciso_role_id'])) {
				$scopes['ciso_role'] = __('CISO Role');
			}
			if (!empty($scope['Scope']['ciso_deputy_id'])) {
				$scopes['ciso_deputy'] = __('CISO Deputy');
			}
			if (!empty($scope['Scope']['board_representative_id'])) {
				$scopes['board_representative'] = __('Board Representative');
			}
			if (!empty($scope['Scope']['board_representative_deputy_id'])) {
				$scopes['board_representative_deputy'] = __('Board Representative Deputy');
			}
		}

		return $scopes;
	}

	/**
	 * Allows only ajax requests, otherwise it will exit the function.
	 */
	protected function allowOnlyAjax() {
		if (!$this->request->is('ajax')) {
			exit;
		}
	}

	protected function isAjax() {
		return $this->request->is('ajax');
	}

	/**
	 * @todo  deprecate this and remove this from acl and everywhere.
	 */
	public function cancelAction($model, $foreignKey = null) {
		$this->autoRender = false;

		if ($this->isAjax() && isset($this->request->query['redirectAjax'])) {
			$this->redirect($this->request->query['redirectAjax']);
		}


		// return true;
	}

	public function redirect($url, $status = null, $exit = true) {
		// compatibility
		$this->handleSystemRecords();

		parent::redirect($url, $status, $exit);
	}

	public function downloadAttachment($id) {
		$this->loadModel('Attachment');
		$attachment = $this->Attachment->getFile($id);

		// move this check into its component
		if (empty($attachment) || $attachment['Attachment']['model'] !== Inflector::singularize($this->name)) {
			throw new ForbiddenException(__('You are not allowed to download this attachment.'));
		}

		$AttachmentsMgt = $this->Components->load('AttachmentsMgt');
		$AttachmentsMgt->startup($this);

		return $AttachmentsMgt->download($id);
	}

/**
 * WARNING HOT FIX FUNCTION !!!! SHOULD BE REPLACED
 * 
 * Load plugin model hack to prevent $this->{$model} issue.
 */
	public function loadModel($modelClass = null, $id = null) {
		return parent::loadModel(ClassRegistry::mapModelName($modelClass), $id);
	}
}
