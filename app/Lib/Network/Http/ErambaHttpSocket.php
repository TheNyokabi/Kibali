<?php
App::uses('HttpSocket', 'Network/Http');

class ErambaHttpSocket extends HttpSocket {
	public $blockingRequest = true;
	protected static $_proxyConfig = null;

	public function __construct($config = array()) {
		parent::__construct($config);

		if (self::_getProxyConfig('USE_PROXY')) {
			$this->setSocketProxy();
		}
	}

	public static function setProxyConfig($proxy = array()) {
		self::$_proxyConfig = $proxy;
	}

	protected static function _getProxyConfig($key) {
		return self::$_proxyConfig[$key];
	}

	/**
	 * Set proxy settings for a socket if setup in settings.
	 */
	private function setSocketProxy() {
		$host = self::_getProxyConfig('PROXY_HOST');
		$port = self::_getProxyConfig('PROXY_PORT');
		
		$method = null;
		$user = null;
		$pass = null;

		if (self::_getProxyConfig('USE_PROXY_AUTH')) {
			$method = 'Basic';
			$user = self::_getProxyConfig('PROXY_AUTH_USER');
			$pass = self::_getProxyConfig('PROXY_AUTH_PASS');
		}

		$this->configProxy($host, $port, $method, $user, $pass);
		$this->config['request']['request_fulluri'] = true;

		// $this->config['ssl_cafile'] = CAKE . 'Config' . DS . 'cacert.pem';
		// $this->configAuth($method, $user, $pass);
	}

	/**
	 * Extended CakeSocket::connect() method to implement stream_set_blocking() funcationality.
	 */
	public function connect() {
		parent::connect();

		if ($this->connected && !$this->blockingRequest) {
			stream_set_blocking($this->connection, 0);
		}
		return $this->connected;
	}

	public function request($request = array()) {
		try {
			return parent::request($request);
		}
		catch (Exception $e) {
			return false;
		}

		return false;
	}

	public function getProxyData() {
		return $this->_proxy;
	}
}