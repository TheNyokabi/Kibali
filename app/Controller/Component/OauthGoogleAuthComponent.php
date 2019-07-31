<?php
App::uses('Component', 'Controller');
App::uses('ClassRegistry', 'Utility');
App::uses('OauthConnector', 'Model');

class OauthGoogleAuthComponent extends Component {

	public $components = array('Session');

	protected $gClient;
	protected $clientId = null;
	protected $clientSecret = null;
	protected $redirectUrl = "";
	protected $redirectUrls = array(
		'/login',
		'/portal/compliance-audits/login'
	);
	protected $scopes = array(
		"email", "openid"
	);

	protected $sessionKey = 'Oauth-eramba.Google';

	protected $OauthConnector;

	public function initialize(Controller $controller) {
		$this->controller = $controller;

		//
		// Initialize OauthConnector model and get Client ID and Client Secret from Active OAuth Google Connector
		$this->OauthConnector = ClassRegistry::init('OauthConnector');
		$data = $this->OauthConnector->getActiveOauthData();
		//

		$this->gClient = new Google_Client();
		$this->gClient->setApplicationName(__('Login to eramba'));

		if ($data !== false) {
			$this->clientId = $data['clientId'];
			$this->clientSecret = $data['clientSecret'];
			$this->gClient->setClientId($this->clientId);
			$this->gClient->setClientSecret($this->clientSecret);
		}

		$this->gClient->setRedirectUri($this->getRedirectUrl());
		$this->gClient->setAccessType("offline");
		$this->gClient->setScopes($this->scopes);
	}

	public function getRedirectUrl()
	{
		if ($this->redirectUrl == "") {
			$this->setRedirectUrl($this->redirectUrls[0], false);
		}
		return $this->redirectUrl;
	}

	public function getRedirectUrls($full = true)
	{
		$fullRedirectUrls = array();
		foreach ($this->redirectUrls as $ru) {
			$fullRedirectUrls[] = Router::fullBaseUrl() . $ru;
		}

		return $fullRedirectUrls;
	}

	public function setRedirectUrl($url, $cakeFormat = true)
	{
		if ($cakeFormat) {
			$url = Router::url($url);
		}

		if (!in_array($url, $this->redirectUrls)) {
			return false;
		}

		$this->redirectUrl = Router::fullBaseUrl() . $url;

		if (!empty($this->gClient)) {
			$this->gClient->setRedirectUri($this->redirectUrl);
		}

		return true;
	}

	public function getSanitizedAuthUrl()
	{
		return filter_var($this->gClient->createAuthUrl(), FILTER_SANITIZE_URL);
	}

	public function getGoogleClient()
	{
		return $this->gClient;
	}

	public function logout()
	{
		if ($this->Session->read($this->getTokenSessionKey())) {
			$this->gClient->revokeToken($this->Session->read($this->getTokenSessionKey()));

			$this->Session->delete($this->getSessionKey());
		}
	}

	public function getSessionKey()
	{
		return $this->sessionKey;
	}

	public function getTokenSessionKey()
	{
		return $this->sessionKey . '.token';
	}

	public function isOauthGoogleAllowed()
	{
		if (!empty($this->clientId)) {
			return true;
		} else {
			return false;
		}
	}
}
