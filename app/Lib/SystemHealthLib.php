<?php
App::uses('Queue', 'Model');
App::uses('ConnectionManager', 'Model');

class SystemHealthLib {
	const PHP_VERSION_REQUIRED = '7.0.0';
	const MYSQL_VERSION_REQUIRED = '5.6.5';
	const MARIADB_VERSION_REQUIRED = '10.1.0';
	const PHP_MEMORY_LIMIT = '2048M';
	const PHP_MAX_EXECUTION_TIME = '200';
	const PHP_UPLOAD_LIMIT = 8;
	const MYSQL_ALLOWED_PACKET = 128000000;
	const MYSQL_INNODB_LOCK_WAIT_TIMEOUT = self::PHP_MAX_EXECUTION_TIME;

	const SYSTEM_HEALTH_OK = 1;
	const SYSTEM_HEALTH_NOT_OK = 0;
	const SYSTEM_HEALTH_CRITICAL = 'critical';
	const SYSTEM_HEALTH_DESIRED = 'desired';

	/**
	 * Get the label for a system health check status.
	 * 
	 * @param  int|null     $status Status of a check
	 * @return string|array         Label for the status or the array of labels if status is NULL.
	 */
	public static function getSystemHealthStatuses($status = null) {
		$statuses = array(
			self::SYSTEM_HEALTH_NOT_OK => __('Not OK'),
			self::SYSTEM_HEALTH_OK => __('OK')
		);

		if ($status === null) {
			return $statuses;
		}

		return $statuses[$status];
	}

	/**
	 * Get the label for a system health check criticality.
	 * 
	 * @param  int|null     $type Criticality of a check
	 * @return string|array       Label for the criticalities or the array of labels value is NULL.
	 */
	public static function getSystemHealthCriticality($type = null) {
		$types = array(
			self::SYSTEM_HEALTH_DESIRED => __('Desired'),
			self::SYSTEM_HEALTH_CRITICAL => __('Critical')
		);

		if (empty($type)) {
			return $types;
		}

		return $types[$type];
	}

	/**
	 * Checks PHP version.
	 * @return boolean True when supports.
	 */
	public static function phpVersion() {
		if (!version_compare(PHP_VERSION, self::PHP_VERSION_REQUIRED, '>=')) {
			return false;
		}

		return true;
	}
	public static function phpVersion_value() {
		return PHP_VERSION;
	}

	public static function mysql($version = null) {
		$ret = true;

		if (!$version) {
			return false;
		}

		$version = strtolower($version);
		$isMariaDB = strpos($version, 'mariadb') !== false;

		if ($isMariaDB) {
			// i.e. 5.5.5-10.1.21-MariaDB or 10.1.21-MariaDB or 10.1.26-MariaDB-0+deb9u1
			$versionParts = explode('-', $version);
			$mariadbKey = array_search('mariadb', $versionParts);
			if ($mariadbKey !== 0) {
				$versionKey = $mariadbKey-1;
			}
			else {
				$versionKey = count($versionParts)-2;
			}

			$version = $versionParts[$versionKey];
			$ret &= version_compare($version, self::MARIADB_VERSION_REQUIRED, '>=');
		}
		else {
			$ret &= version_compare($version, self::MYSQL_VERSION_REQUIRED, '>=');
		}

		return (bool) $ret;
	}
	public static function mysql_value() {
		$ds = ConnectionManager::getDataSource('default');

		// check if mysql extension is loaded @see Mysql::enabled()
		if (!$ds->enabled()) {
			return false;
		}

		// first check the server version via PDO
		$version = $ds->getVersion();
		if (empty($version) || is_bool($version)) {
			// fallback for getting the version but from a global variable
			$version = $ds->query("SELECT @@version;");
			$version = $version[0][0]['@@version'];
		}

		if (!$version) {
			return false;
		}

		return $version;
	}

	public static function backups() {
		$ret = true;

		if (!defined('BACKUPS_ENABLED') || empty(BACKUPS_ENABLED)) {
			$ret = false;
		}

		return $ret;
	}

	public static function backups_value() {
		return (static::backups()) ? __('Enabled') : __('Disabled');
	}

	public static function openssl() {
		$ret = extension_loaded('openssl');
		$ret &= defined('OPENSSL_TLSEXT_SERVER_NAME') && OPENSSL_TLSEXT_SERVER_NAME;
		$ret &= defined('OPENSSL_VERSION_NUMBER') && OPENSSL_VERSION_NUMBER >= 0x009080bf;

		return $ret;
	}
	public static function openssl_value() {
		return OPENSSL_VERSION_TEXT;
	}

	public static function curl() {
		return extension_loaded('curl');
	}

	public static function ldap() {
		return extension_loaded('ldap') && function_exists('ldap_connect');
	}

	public static function mail() {
		return function_exists('mail');
	}

	public static function fileinfo() {
		return extension_loaded('fileinfo');
	}

	public static function mbstring() {
		return extension_loaded('mbstring');
	}

	public static function gd() {
		return extension_loaded('gd');
	}

	public static function exif() {
		return extension_loaded('exif');
	}

	public static function zlib() {
		return extension_loaded('zlib');
	}

	public static function maxExecutionTime($value) {
		return $value >= self::PHP_MAX_EXECUTION_TIME;
	}
	public static function maxExecutionTime_value() {
		return ini_get('max_execution_time');
	}

	public static function memoryLimit($value) {
		return ((int) $value) >= ((int) self::PHP_MEMORY_LIMIT);
	}
	public static function memoryLimit_value() {
		return ini_get('memory_limit');
	}

	public static function writeTmp() {
		return is_writable(TMP);
	}

	public static function writeCache() {
		return is_writable(CACHE);
	}
	public static function writeLogs() {
		return is_writable(LOGS);
	}
	public static function writeFiles() {
		return is_writable(WWW_ROOT . 'files' . DS);
	}
	public static function writeMedia() {
		return is_writable(WWW_ROOT . 'media' . DS);
	}
	public static function writeBackups() {
		return is_writable(WWW_ROOT . 'backups' . DS);
	}
	public static function writeQueue() {
		return is_writable(Queue::getDataPath());
	}

	public static function uploadLimits($value) {
		$postMaxSize = self::returnBytes($value);
		if (($postMaxSize/1024/1024) >= self::PHP_UPLOAD_LIMIT) {
			return true;
		}

		return false;
	}
	public static function uploadLimits_value() {
		return ini_get('post_max_size');
	}

	public static function pharDataClass() {
		return class_exists('PharData') && class_exists('Phar');
	}

	public static function zipArchiveClass() {
		return class_exists('ZipArchive');
	}

	public static function intl() {
		return extension_loaded('intl');
	}

	public static function simplexml() {
		return extension_loaded('simplexml');
	}

	public static function allow_url_fopen() {
		return ini_get('allow_url_fopen') == 1;
	}

	/**
	 * Convert post_max_size value to bytes.
	 */
	private static function returnBytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		$val = (int) $val;

		switch($last) {
			// El modificador 'G' está disponble desde PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}

}