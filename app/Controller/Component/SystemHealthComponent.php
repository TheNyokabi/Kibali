<?php
App::uses('Component', 'Controller');
App::uses('SystemHealthLib', 'Lib');
App::uses('CakeTime', 'Utility');
App::uses('Cron', 'Model');

class SystemHealthComponent extends Component {
	public $components = array('Session');
	private $checkStatuses = array();

	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->controller->loadModel('Cron');
	}

	public function startup(Controller $controller) {
		$this->controller = $controller;
	}

	public function getStatuses() {
		return $this->checkStatuses;
	}

	public function loadChecks() {
		$this->controller->loadModel('Cron');
		$this->checkStatuses = array(
			array(
				'groupName' => __('PHP Libraries and Extensions'),
				'checks' => array(
					array(
						'name' => __('PHP 7'),
						'new' => true,
						'fn' => array('SystemHealthLib', 'phpVersion'),
						'description' => __('eramba needs PHP versions above %s', SystemHealthLib::PHP_VERSION_REQUIRED),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('MySQL'),
						'fn' => array('SystemHealthLib', 'mysql'),
						'description' => __(
							'We need at least MySQL %s / MariaDB %s, installed with PHP',
							SystemHealthLib::MYSQL_VERSION_REQUIRED,
							SystemHealthLib::MARIADB_VERSION_REQUIRED
						),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('OpenSSL'),
						'fn' => array('SystemHealthLib', 'openssl'),
						'description' => __('We need SSL in order to encrpyt SMTP, LDAP and API connections. SNI support and OpenSSL 0.9.8k or greater is required'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('CURL'),
						'fn' => array('SystemHealthLib', 'curl'),
						'description' => __('We need Curl libraries to manage file uploads. Altough this module in linux is called different depending on the distribution and version, you could try: php-pear-Net-Curl.noarch or php-curl.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('LDAP'),
						'fn' => array('SystemHealthLib', 'ldap'),
						'description' => __('We need LDAP libraries in PHP to manage accounts and groups. Altough this module in linux is called different depending on the distribution and version, you could try: php-ldap'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Mail'),
						'fn' => array('SystemHealthLib', 'mail'),
						'description' => __('We use mail libraries to connect to SMTP servers.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Fileinfo'),
						'fn' => array('SystemHealthLib', 'fileinfo'),
						'description' => __('We need fileinfo libraries as part of PHP'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Multibyte String'),
						'fn' => array('SystemHealthLib', 'mbstring'),
						'description' => __('We need multibyte strings as part of PHP.Altough this module in linux is called different depending on the distribution and version, you could try: php-mbstring'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('GD'),
						'fn' => array('SystemHealthLib', 'gd'),
						'description' => __('We use GD libraries to manage the system Logo. Altough this module in linux is called different depending on the distribution and version, you could try: php-gd'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Exif'),
						'fn' => array('SystemHealthLib', 'exif'),
						'description' => __('We need exif as part of PHP to manage images. Altough this module in linux is called different depending on the distribution and version, you could try: php-exif'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Zlib'),
						'fn' => array('SystemHealthLib', 'zlib'),
						'description' => __('We need Zlib to handle file compression. Altough this module in linux is called different depending on the distribution and version, you could try: php7.0-zip or php-pclzip or php-pecl'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Phar'),
						'fn' => array('SystemHealthLib', 'pharDataClass'),
						'description' => __('PharData as Phar extensions for archive files accessing. This is part of PHP.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('ZipArchive'),
						'fn' => array('SystemHealthLib', 'zipArchiveClass'),
						'description' => __('ZipArchive class for bundling a backup of the system. If you have installed all ZIP files (check above) this should be OK.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Intl'),
						'new' => true,
						'fn' => array('SystemHealthLib', 'intl'),
						'description' => __('Altough this module in linux is called different depending on the distribution and version, you could try: php-intl'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('SimpleXML'),
						'new' => true,
						'fn' => array('SystemHealthLib', 'simplexml'),
						'description' => __('Altough this module in linux is called different depending on the distribution and version, you could try: php-xml'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
				)
			),
			array(
				'groupName' => __('Server Settings'),
				'checks' => array(
					array(
						'name' => __('Max Execution Time'),
						'new' => true,
						'fn' => array('SystemHealthLib', 'maxExecutionTime'),
						'description' => __('We require setting max execution time settings to be equal or more than %s seconds. You can find this setting on your php.ini (under /etc/) file under the setting: max_execution_time', SystemHealthLib::PHP_MAX_EXECUTION_TIME),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Memory Limit'),
						'new' => true,
						'fn' => array('SystemHealthLib', 'memoryLimit'),
						'description' => __(
							'We require setting memory limitations settings to be equal or more than %s (MG). You can find this setting on your php.ini (under /etc/) file under the setting: memory_limit',
							SystemHealthLib::PHP_MEMORY_LIMIT
						),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Access Wrappers'),
						'new' => true,
						'fn' => array('SystemHealthLib', 'allow_url_fopen'),
						'description' => __('We require a special URL management setting under php to be enabled. You can find this setting on your php.ini (under /etc/) file under the setting: allow_url_fopen'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					)
				)
			),
			array(
				'groupName' => __('Crons'),
				'checks' => array(
					'cron-hourly' => array(
						'name' => __('Hourly'),
						'fn' => [$this, 'cronsHourly'],
						'description' => __('You need to make sure crons are being called every hour. The cron should call the following URL: http(s)://yourdomain/cron/hourly/KEY where KEY is defined under System / Settings / Security Key. You can validate if the cron are running correctly or not at System / Settings / Cron <br><br>For example: @hourly /usr/bin/wget --no-check-certificate -O /dev/null https://mydomain/cron/hourly/rqltLFkcEc (where my Security Key is: rqltLFkcEc)'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					'cron-daily' => array(
						'name' => __('Daily'),
						'fn' => [$this, 'cronsDaily'],
						'description' => __('You need to make sure crons are being called every day. The cron should call the following URL: http(s)://yourdomain/cron/daily/KEY where KEY is defined under System / Settings / Security Key. You can validate if the cron are running correctly or not at System / Settings / Cron. The cron must run succesfully at least once in the last 30 hours to set status as OK. <br><br>For example: @daily /usr/bin/wget --no-check-certificate -O /dev/null https://mydomain/cron/daily/rqltLFkcEc (where my Security Key is: rqltLFkcEc)

	<br><br>NOTE: If you are an enterprise customer you need your license to be valid'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Yearly'),
						'fn' => [$this, 'cronsYearly'],
						'description' => __('You need to make sure crons are being called every year. The cron should call the following URL: http(s)://yourdomain/cron/yearly/KEY where KEY is defined under System / Settings / Security Key. You can validate if the cron are running correctly or not at System / Settings / Cron. <br><br>For example: @yearly /usr/bin/wget --no-check-certificate -O /dev/null https://mydomain/cron/yearly/rqltLFkcEc (where my Security Key is: rqltLFkcEc)<br><br>NOTE: if you are doing a fresh install, call this cron URL with your browser.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Correct CRON URL'),
						'new' => true,
						'fn' => [$this, 'checkCronUrl'],
						'description' => __('Any of the three mandatory crons (hourly, daily, yearly) can not use localhost as an endpoint, for example the following example is incorrect:<br><br> @hourly /usr/bin/wget --no-check-certificate -O /dev/null https://<b>localhost</b>/cron/daily/rqltLFkcEc'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					)
				)
			),
			array(
				'groupName' => __('Write Permissions'),
				'checks' => array(
					array(
						'name' => __('Temporary folder'),
						'fn' => array('SystemHealthLib', 'writeTmp'),
						'description' => __('The system should be allowed to write and read on the directory eramba_v2/app/tmp/*. Altough every distribution works different the quickest way to solve this is by using the command "chown" and assign all this files the user/group apache is using.<br><br>For example, in ubuntu while at the eramba_v2 directory: chown www-data:www-data app/tmp/ -R<br><br>NOTE: some systems enforce selinux, which can override this permissions.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('User Attachments & Awareness Media folder'),
						'fn' => array('SystemHealthLib', 'writeFiles'),
						'description' => __('The system should be allowed to write and read on the directory eramba_v2/app/webroot/files/*. Altough every distribution works different the quickest way to solve this is by using the command "chown" and assign all this files the user/group apache is using.<br><br>For example, in ubuntu while at the eramba_v2 directory: chown www-data:www-data app/webroot/files/ -R<br><br>NOTE: some systems enforce selinux, which can override this permissions.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Policy HTML Editor Media folder'),
						'fn' => array('SystemHealthLib', 'writeMedia'),
						'description' => __('The system should be allowed to write and read on the directory eramba_v2/app/webroot/media/*. Altough every distribution works different the quickest way to solve this is by using the command "chown" and assign all this files the user/group apache is using.<br><br>For example, in ubuntu while at the eramba_v2 directory: chown www-data:www-data app/webroot/media/ -R<br><br>NOTE: some systems enforce selinux, which can override this permissions.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Database Backups folder'),
						'fn' => array('SystemHealthLib', 'writeBackups'),
						'description' => __('The system should be allowed to write and read on the directory eramba_v2/app/webroot/backups/*. Altough every distribution works different the quickest way to solve this is by using the command "chown" and assign all this files the user/group apache is using.<br><br>For example, in ubuntu while at the eramba_v2 directory: chown www-data:www-data app/webroot/backups/ -R<br><br>NOTE: some systems enforce selinux, which can override this permissions.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('Mail Queue folder'),
						'fn' => array('SystemHealthLib', 'writeQueue'),
						'description' => __('The system should be allowed to write and read on the directory eramba_v2/app/Vendor/queue/. Altough every distribution works different the quickest way to solve this is by using the command "chown" and assign all this files the user/group apache is using.<br><br>For example, in ubuntu while at the eramba_v2 directory: chown www-data:www-data app/Vendor/queue/ -R<br><br>NOTE: some systems enforce selinux, which can override this permissions.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
				)
			),
			array(
				'groupName' => __('Other'),
				'checks' => array(
					'default-password' => array(
						'name' => __('Default Password'),
						'fn' => [$this, 'password'],
						'description' => __('You must change the admin password at System / Settings / User Management'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
					array(
						'name' => __('File Attachment Limits'),
						'fn' => array('SystemHealthLib', 'uploadLimits'),
						'description' => __('In order to upload large files you need to set equal or more than %sM on the two settings: post_max_size and upload_max_filesize. You can find this setting on your php.ini (under /etc/)', SystemHealthLib::PHP_UPLOAD_LIMIT),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL

					),
					array(
						'name' => __('MySQL Strict'),
						'fn' => [$this, 'sqlStrict'],
						'description' => __('You need to disable strict mode in MySQL or MariaDB. You need to edit MySQL / MariaDB configuration files (this changes from distribution to distribution: /etc/mysql/mysql.conf.d/mysqld.cnf in Ubuntu, /etc/my.cnf in Red Hat) and under the section [mysqld] add: sql_mode="". You will need to restart the engine after the configuration change is done.<br><br>We recommend looking at the specifics of your linux distribution to ensure the change is done correctly.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL

					),
					array(
						'name' => __('MySQL Allowed Packet'),
						'fn' => [$this, 'sqlPacket'],
						'description' => __('You need to set cache limits in MySQL or MariaDB. You need to edit MySQL / MariaDB configuration files (this changes from distribution to distribution: /etc/mysql/mysql.conf.d/mysqld.cnf in Ubuntu, /etc/my.cnf in Red Hat) and under the section [mysqld] add: max_allowed_packet="%s". You will need to restart the engine after the configuration change is done.<br><br>We recommend looking at the specifics of your linux distribution to ensure the change is done correctly.', SystemHealthLib::MYSQL_ALLOWED_PACKET),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL

					),
					'innodb-lock-timeout' => array(
						'name' => __('MySQL InnoDB Lock Timeout'),
						'fn' => [$this, 'sqlLockTimeout'],
						'description' => __('You need to set cache limits in MySQL or MariaDB. You need to edit MySQL / MariaDB configuration files (this changes from distribution to distribution: /etc/mysql/mysql.conf.d/mysqld.cnf in Ubuntu, /etc/my.cnf in Red Hat) and under the section [innodb] add: innodb_lock_wait_timeout="%s". You will need to restart the engine after the configuration change is done.<br><br>We recommend looking at the specifics of your linux distribution to ensure the change is done correctly.', SystemHealthLib::MYSQL_INNODB_LOCK_WAIT_TIMEOUT),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL

					),
					array(
						'name' => __('Backups Enabled'),
						'new' => false,
						'fn' => array('SystemHealthLib', 'backups'),
						'description' => __('You are required to have daily backups enabled on the system, at System / Settings / Backup Configuration you can enable this feature.'),
						'criticality' => SystemHealthLib::SYSTEM_HEALTH_CRITICAL
					),
				)
			),
		);
	}

	/**
	 * Internal method to get final value of a health check status.
	 * 
	 * @param  array $callable  Callable method
	 * @return bool             Result status true if passed or false otherwise
	 */
	protected function _processCheck($callable) {
		$args = $this->_getValue($callable);
		$status = $this->initCheckFunction($callable, [$args]);

		return (bool) $status;
	}

	/**
	 * Get value for health check in the current application instance.
	 */
	protected function _getValue($callable) {
		$fnForValue = $callable;
		$fnForValue[1] = $fnForValue[1] . '_value';

		return $this->initCheckFunction($fnForValue);
	}

	/**
	 * Get Table data.
	 * 
	 * @return array Table Data.
	 */
	public function getData() {
		$this->loadChecks();
		
		$data = $this->checkStatuses;
		foreach ($data as $groupKey => $group) {
			foreach ($group['checks'] as $checkKey => $check) {
				$callable = $check['fn'];

				$data[$groupKey]['checks'][$checkKey]['status'] = $this->_processCheck($callable);
				$value = $this->_getValue($callable);
				$data[$groupKey]['checks'][$checkKey]['value'] = ($value === false) ? '-' : $value;

				unset($data[$groupKey]['checks'][$checkKey]['fn']);
			}
		}

		return $data;
	}

	/**
	 * Get boolean value of all critical statuses.
	 */
	public function checkCriticalStatuses($options = []) {
		$options = array_merge([
			'skip' => [] // array of key slugs for system checks to skip
		], $options);

		$this->loadChecks();

		$ret = true;
		foreach ($this->checkStatuses as $group) {
			foreach ($group['checks'] as $key => $check) {
				$doCheck = $check['criticality'] == SystemHealthLib::SYSTEM_HEALTH_CRITICAL;
				$doCheck &= !in_array($key, $options['skip']);

				if ($doCheck) {
					$callable = $check['fn'];

					$ret &= $this->_processCheck($callable);
				}
			}
		}

		return (bool) $ret;
	}

	public function initCheckFunction($fn, $args = []) {
		if (is_callable($fn)) {
			return call_user_func_array($fn, $args);
		}

		return false;
	}

	/**
	 * Check for a strict sql mode. If a single mode contains string "STRICT" or is missing string "NO_ENGINE_SUBSTITUTION", check fails.
	 */
	private function sqlStrict($value) {
		$sqlModesString = $value;

		if ($sqlModesString !== false) {
			$sqlMode = explode(',', $sqlModesString);

			//check for a "STRICT" occurence
			$strictExists = strpos($sqlModesString, 'STRICT');
			if ($strictExists !== false) {
				return false;
			}

			if (in_array('NO_ENGINE_SUBSTITUTION', $sqlMode)) {
				return true;
			}
			elseif (empty($sqlMode[0])) {
				return true;
			}
		}

		return false;
	}
	public function sqlStrict_value() {
		$this->controller->loadModel('Setting');
		$sqlMode = $this->controller->Setting->query("SELECT @@sql_mode;");
		if (isset($sqlMode[0][0]['@@sql_mode'])) {
			return $sqlMode[0][0]['@@sql_mode'];
		}

		return false;
	}

	private function sqlPacket($value) {
		$maxAllowedPacket = $value;

		if ($maxAllowedPacket !== false && $maxAllowedPacket >= SystemHealthLib::MYSQL_ALLOWED_PACKET) {
			return true;
		}

		return false;
	}
	public function sqlPacket_value() {
		$this->controller->loadModel('Setting');
		$select = $this->controller->Setting->query("SELECT @@max_allowed_packet;");

		if (isset($select[0][0]['@@max_allowed_packet'])) {
			return $select[0][0]['@@max_allowed_packet'];
		}

		return false;
	}

	private function sqlLockTimeout($value) {
		$lockTimeout = $value;

		if ($lockTimeout !== false && $lockTimeout >= SystemHealthLib::MYSQL_INNODB_LOCK_WAIT_TIMEOUT) {
			return true;
		}

		return false;
	}
	public function sqlLockTimeout_value() {
		$this->controller->loadModel('Setting');
		$select = $this->controller->Setting->query("SELECT @@innodb_lock_wait_timeout;");

		if (isset($select[0][0]['@@innodb_lock_wait_timeout'])) {
			return $select[0][0]['@@innodb_lock_wait_timeout'];
		}

		return false;
	}

	/*private function crons() {
		$this->controller->loadModel('Cron');
		
		$ret = true;
		$ret &= $this->cronsDaily();
		$ret &= $this->cronsYearly();

		return $ret;
	}*/

	public function cronsHourly() {
		// $hoursAgo = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString('-2 hours'));
		$hoursAgoTolerance = CakeTime::format(CakeTime::fromString('-2 hours'), '%Y-%m-%d %H:%M:%S');
		
		$data = $this->controller->Cron->find('count', array(
			'conditions' => array(
				'Cron.type' => Cron::TYPE_HOURLY,
				'Cron.status' => Cron::STATUS_SUCCESS,
				'Cron.created >' => $hoursAgoTolerance
			),
			'order' => array('Cron.created' => 'DESC')
		));

		if ($data < 1) {
			return false;
		}

		return true;
	}

	/**
	 * Check that a Daily CRON was successfully processed in the last 30 hours.
	 * 
	 * @return boolean True on success.
	 */
	private function cronsDaily() {
		// $today = CakeTime::format(strtotime('now'), '%Y-%m-%d');
		// $yesterday = CakeTime::format(strtotime('-1 day'), '%Y-%m-%d');
		$hoursAgoTolerance = CakeTime::format(CakeTime::fromString('-30 hours'), '%Y-%m-%d %H:%M:%S');

		$data = $this->controller->Cron->find('count', array(
			'conditions' => array(
				'Cron.type' => Cron::TYPE_DAILY,
				'Cron.status' => Cron::STATUS_SUCCESS,
				'Cron.created >=' => $hoursAgoTolerance
			),
			'order' => array('Cron.created' => 'DESC'),
			'limit' => 1
		));

		if ($data < 1) {
			return false;
		}

		return true;
	}

	private function cronsYearly() {
		$data = $this->controller->Cron->find('count', array(
			'conditions' => array(
				'Cron.type' => Cron::TYPE_YEARLY,
				'YEAR(Cron.created)' => date('Y'),
				'Cron.status' => Cron::STATUS_SUCCESS
			)
		));

		if (!empty($data)) {
			return true;
		}

		return false;
	}

	private function checkCronUrl()
	{
		$data = $this->controller->Cron->find('first', array(
			'fields' => array(
				'url'
			),
			'order' => array(
				'id' => 'DESC'
			)
		));

		$conds = empty($data);
		$conds = $conds || $data['Cron']['url'] === null;
		$conds = $conds || $data['Cron']['url'] === Router::fullBaseUrl();

		// if there is not enough data or url is actually matching what is should then its okay
		if ($conds) {
			return true;
		}

		return false;
	}

	private function password() {
		$this->controller->loadModel('User');
		$count = $this->controller->User->find('count', array(
			'conditions' => array(
				'User.id' => ADMIN_ID,
				'User.password' => '$2a$10$WhVO3Jj4nFhCj6bToUOztun/oceKY6rT2db2bu430dW5/lU0w9KJ.'
			),
			'recursive' => -1
		));

		return (boolean) !$count;
	}

}
