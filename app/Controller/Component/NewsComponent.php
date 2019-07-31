<?php
App::uses('Component', 'Controller');
App::uses('ErambaHttpSocket', 'Network/Http');

class NewsComponent extends Component {
	private $requestUrl;

	private $clientId;
	private $clientKey;

	private $error = false;
	private $errorMessage = '';

	private $outOfDatePeriod = 14; //in days

	public $components = array('ErambaHttp', 'AutoUpdate');

	public function initialize(Controller $controller) {
		$this->controller = $controller;

		$apiUrl = Configure::read('Eramba.SUPPORT_API_URL');
		$this->requestUrl = $apiUrl . '/api/news';

		$this->clientId = CLIENT_ID;
		$this->clientKey = $this->getClientKey();

		// $this->ErambaHttp->initialize($controller);
	}

	/**
	 * returns client ID
	 * 
	 * @return string
	 */
	private function getClientKey() {
		// $keyFile = new File(APP . DS . 'Vendor' . DS . 'other' . DS . 'CLIENT_KEY');
		// return trim($keyFile->read());
		
		return $this->AutoUpdate->getClientKey();
	}

	/**
	 * @return boolean
	 */
	public function hasError() {
		return $this->error;
	}

	/**
	 * @return string error message
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * @return string error message
	 */
	public function setError($message) {
		$this->error = true;
		$this->errorMessage = $message;

		Cache::write('server_response', array('success' => false, 'message' => $message), 'news');
	}

	private function request($url, $body = null) {
		$config = $this->ErambaHttp->config;
		$config['timeout'] = 15;
		$config['request']['header'] = array(
			'Accept' => 'application/json',
			'Content-Type' => 'application/json'
		);

		$http = new ErambaHttpSocket($config);
		$http->configAuth('Basic', $this->clientId, $this->clientKey);

		return $http->get($url, json_encode($body));
	}

	/**
	 * Check for news.
	 */
	public function check() {
		if (($response = Cache::read('server_response', 'news')) === false) {

			$request = $this->request($this->requestUrl);

			if (!$request || !$request->isOk()) {
				$this->setError(__('The system was not able to connect to our update servers. Please check your internet connection or proxy settings.'));
				return false;
			}

			$response = json_decode($request->body(), true);

			if (!$response['success']) {
				$this->setError($response['message']);
				return false;
			}

			if (!empty($response['response'])) {
				$this->setOutOfDateMessage($response['response']);
			}

			Cache::write('server_response', $response, 'news');
		}

		/*if (!$response['success']) {
			$this->setError($response['message']);
			return false;
		}*/

		return $response;
	}

	public function get() {
		$news = $this->check();

		$news = (!empty($news['response'])) ? $news['response'] : false;

		return $news;
	}

	public function markAsRead() {
		$news = $this->get();

		if (!empty($news[0]['id'])) {
			Cache::write('news_last_read_id', $news[0]['id'], 'infinite');
		}
	}

	public function getUnreadedCount() {
		$id = $this->getLastReadId();
		$news = $this->get();
		$count = 0;

		if (!empty($news)) {
			foreach ($news as $message) {
				if ($message['id'] > $id) {
					$count++;
				}
			}
		}

		return $count;
	}

	private function getLastReadId() {
		$id = 0; 

		if (($cacheId = Cache::read('news_last_read_id', 'infinite')) !== false) {
			$id = $cacheId;
		}

		return $id;
	}

	private function setOutOfDateMessage($news) {
		foreach ($news as $message) {
			if (strtotime($message['date']) < strtotime('-' . $this->outOfDatePeriod . ' days')) {
				Cache::write('news_last_read_id', $message['id'], 'infinite');
				return;
			}
		}
	}
}