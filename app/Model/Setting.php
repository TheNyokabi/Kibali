<?php
App::uses('PhinxApp', 'Lib');
App::uses('CacheCleaner', 'Lib/Cache');
App::uses('VisualisationShell', 'Visualisation.Console/Command');
App::uses('DataAssetShell', 'Console/Command');
App::uses('ObjectStatusShell', 'ObjectStatus.Console/Command');

class Setting extends AppModel {
	const USE_SSL_NO_ENCRYPTION = 0;
	const USE_SSL_SSL = 1;
	const USE_SSL_TLS = 2;

	public $name = 'Setting';

	public $notes = array();

	public $groupTitles = array();

	public $groupHelpText = array();

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'active', 'name', 'variable', 'value', 'default_value', 'values', 'type', 'setting_group_slug', 'setting_type', 'order'
			)
		),
		'Uploader.Attachment' => array(
			'logo_file' => array(
				'nameCallback' => 'formatName',
				'dbColumn' => 'value',
				'transforms' => array(
					'logo' => array(
						'method' => 'resize',
						'nameCallback' => 'transformName',
						'overwrite' => true,
						//'width' => MAX_SMALL_IMAGE_WIDTH,
						'height' => 58,
						'expand' => false,
						'aspect' => true,
						'mode' => 'width'
					)
				),
			)
		),
		'Uploader.FileValidation' => array(
			'logo_file' => array(
				'extension' => array('gif', 'jpg', 'png', 'jpeg'),
				'type' => 'image',
				'required' => true
			)
		)
	);

	public $belongsTo = array(
		'SettingGroup' => array(
			'foreignKey' => 'setting_group_slug'
		)
	);

	public $validate = array(
		'QUEUE_TRANSPORT_LIMIT' => [
			'naturalNumber' => [
				'rule' => 'naturalNumber',
				'required' => false,
				'message' => 'Only natural number in range of 10 - 500 are allowed.'
			],
			'number' => [
				'rule' => array('range', 10, 500),
				'required' => false,
				'message' => 'Only natural numbers in range of 10 - 500 are allowed.'
			],
		]
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Settings');

		parent::__construct($id, $table, $ds);

		$this->notes = array(
			'SMTP_USE' => __('Type: Select the type of mail configuration you want to use to send notifications. Mail will use the operating system internal mailing capabilities while SMTP allows the use of any type of mail system (Exchange, Google, Etc).'),
			'USE_SSL' => __('Choose the encryption type you would like to use when sending emails.'),
			'SMTP_HOST' => __('SMTP host: The server to which eramba will connect to send emails. For example "mail.google.com"'),
			'SMTP_USER' => __('SMTP user: The username to use in order to authenticate against the previously defined SMTP server. If left empty, the system will not authenticate the connection.'),
			'SMTP_PWD' => __('SMTP password: The password to use in order to authenticate against the previously defined SMTP server. If left empty, the system will not authenticate the connection.'),
			'SMTP_TIMEOUT' => __('SMTP timeout: The time to wait before closing the SMTP connection in case we have some trouble reaching the server.'),
			'SMTP_PORT' => __('SMTP port: The port to which eramba will connect'),
			'NO_REPLY_EMAIL' => __('No reply email: Is the email eramba will send the emails "From"'),

			'USE_PROXY' => __('Enable proxy settings to allow the application to communicate, get updates etc.'),
			'BACKUPS_ENABLED' => __('Enable backup of your database during a daily CRON job'),
			'BACKUP_DAY_PERIOD' => __('How often we need to backup your database'),
			'BACKUP_FILES_LIMIT' => __('How many backups should we keep on your system'),
			'EMAIL_NAME' => __('Define a name for this account that will be used as a sender for emails.'),
			'DEBUG' => __('This feature enables more detailed output in case you are experiencing an error or other issue. Enable this if you feel confident you know what you are doing and send us your logs if you think you have found an issue in the application.'),
			'EMAIL_DEBUG' => __('While this option is enabled, eramba wont send emails.'),
			'QUEUE_TRANSPORT_LIMIT' => __('All emails sent by eramba go first to a queue (System / Settings / Email Queue) which flushes as the hourly cron runs (System / Settings / Crons Jobs). This setting defines how many emails are flushed out of the queue every time the hourly cron runs. We do not recommend increasing the size by more than 50 as the cron might time out while sending emails (the cron will run by 5 minutes at most).'),
			'E'
		);

		$this->groupTitles = array(
			'DEBUGCFG' => __('Enabling debug will make errors on the system very explicit. This is useful if you are debugging some issue alone. Do not enable debug unless strictly needed.')
		);

		$this->groupHelpText = array(
			'CLRCACHE' => __('You might need to clear the cache after an upgrade on the system.'),
			'CLRACLCACHE' => __('You might need to clear the ACL (the sub-system that handles who access where) after an upgrade on the system or made significant changes on the ACL assignation. This is a debug feature and we do not recommend using it unless you have been suggested by eramba team.')
		);
	}

	public function formatName($name, $file) {
		return Inflector::slug($name, '-');
	}

	public function transformName($name, $file) {
		return $this->getUploadedFile()->name();
	}

	/**
	 * Sets a value for custom variable.
	 */
	public function updateVariable($variable, $value) {
		$db = $this->getDataSource();
		$dbValue = $db->value($value, 'string');

		$ret = $this->updateAll(
			array(
				'Setting.value' => $dbValue,
				'Setting.modified' => 'NOW()',
			),
			array('Setting.variable' => $variable)
		);

		// if db version is the case, we update also DB version stored in runtime configuration
		if ($ret && $variable == 'DB_SCHEMA_VERSION') {
			Configure::write('Eramba.Settings.DB_SCHEMA_VERSION', $value);
		}

		return $ret;
	}

	public function getVariable($variable) {
		// if db version is the case, we return up-to-date runtime value of DB version
		if ($variable == 'DB_SCHEMA_VERSION' && Configure::read('Eramba.Settings.DB_SCHEMA_VERSION')) {
			return Configure::read('Eramba.Settings.DB_SCHEMA_VERSION');
		}

		$value = $this->find('first', array(
			'conditions' => array(
				'Setting.variable' => $variable
			),
			'fields' => array('id', 'value'),
			'recursive' => -1
		));

		return $value['Setting']['value'];
	}

	/*
	 * Execute database schema file.
	 */
	public function runSchemaFile($path) {
		if (!is_file($path)) {
			return false;
		}
		
		$lines = file($path);

		if (!$lines) {
			return false;
		}

		$templine = '';

		$ret = $this->checkQueryResponse($this->query('SET FOREIGN_KEY_CHECKS=0'));

		foreach ($lines as $line) {
			if (substr($line, 0, 2) == '--' || $line == '') {
				continue;
			}

			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';') {
				$response = $this->query($templine);
				$ret &= $this->checkQueryResponse($response);
				$templine = '';
			}
		}

		$ret &= $this->checkQueryResponse($this->query('SET FOREIGN_KEY_CHECKS=1'));

		return $ret;
	}

	/**
	 * Drop all tables from current database.
	 */
	public function dropAllTables() {
		$result = $this->query("SHOW TABLES");
		$tables = array();

		foreach ($result as $table) {
			$tables[] = current($table['TABLE_NAMES']);
		}

		$ret = $this->checkQueryResponse($this->query("SET FOREIGN_KEY_CHECKS=0"));

		foreach ( $tables as $table ) {
			$ret &= $this->checkQueryResponse($this->query('DROP TABLE IF EXISTS `' . $table . '`'));
		}

		$ret &= $this->checkQueryResponse($this->query("SET FOREIGN_KEY_CHECKS=1"));

		return $ret;
	}

	private function checkQueryResponse($response) {
		return checkQueryResponse($response);
	}

	public function deleteCache($folder) {
		if (empty($folder)) {
			$folder = CACHE;
		}

		return CacheCleaner::deleteCache($folder);
	}

	/**
	 * Reseting the database to its initial status having all migrations up to the latest version present.
	 * 
	 * @param  boolean $keepClientID Keep previous client_id or remove that too.
	 */
	public function resetDatabase($keepClientID = true) {
		ClassRegistry::init('User')->cacheSources = false;

		if ($keepClientID) {
			// before we reset the database we store the CLIENT_ID so the app stays registered 
			$keepClientID = $this->getVariable('CLIENT_ID');
		}

		$ret = $this->dropAllTables();
		$ret &= $this->runMigrations();

		// do necessary synces after reset
		if ($ret) {
			$VisualisationShell = new VisualisationShell();
            $VisualisationShell->startup();
            $ret &= $VisualisationShell->acl_sync();
			$ret &= $VisualisationShell->CustomRoles->sync();

			$DataAssetShell = new DataAssetShell();
			$DataAssetShell->startup();
			$ret &= $DataAssetShell->add_instances();
		
			$ObjectStatusShell = new ObjectStatusShell();
			$ObjectStatusShell->startup();
			$ret &= $ObjectStatusShell->sync_all_statuses();

			$this->recalculateRiskScores();
			$ret &= ClassRegistry::init('SecurityPolicyDocumentType')->syncSecurityPolicyDocumentTypes();

			$this->syncAcl();
		}

		if ($keepClientID) {
			$ret &= $this->updateVariable('CLIENT_ID', $keepClientID);
		}

		$ret &= $this->deleteCache('');

		return $ret;
	}

	// recalculate risk scores after fixes to it in this update package
    public function recalculateRiskScores() {
        $models = [
            'Risk' => 'Asset',
            'ThirdPartyRisk' => 'ThirdParty',
            'BusinessContinuity' => 'BusinessUnit'
        ];

        foreach ($models as $riskModel => $assocModel) {
            $Model = ClassRegistry::init($riskModel);
            $ids = $Model->find('list', ['fields' => "{$Model->alias}.id"]);

            foreach ($ids as $id) {
                $Model->afterSaveRiskScore($id, $assocModel);
            }
        }
    }

	/**
	 * generate md5 integrity hash from controllers models and views
	 * 
	 * @return string integrity hash
	 */
	public function getIntegrityHash() {
		$hash = '';
		$folderFiles = array();

		$folder = new Folder(APP . 'Controller');
		$folderFiles['controller'] = $this->prepareTreeForHash($folder->tree()[1]);

		$folder = new Folder(APP . 'Model');
		$folderFiles['model'] = $this->prepareTreeForHash($folder->tree()[1]);

		$folder = new Folder(APP . 'View');
		$folderFiles['view'] = $this->prepareTreeForHash($folder->tree()[1]);
		
		$hash .= md5(count($folderFiles, COUNT_RECURSIVE));

		foreach ($folderFiles as $folder) {
			foreach ($folder as $file) {
				$str = file($file, FILE_IGNORE_NEW_LINES);
				$str = array_map('rtrim', $str);
				$str = implode("\n", $str);

				$checksum = md5($str);
				$hash .= $checksum;
			}
		}

		return md5($hash);
	}

	/**
	 * Maintain cross platform support when generating checksum.
	 */
	private function prepareTreeForHash($tree) {
		$tree = str_replace('\\', '/', $tree);
		sort($tree, SORT_STRING);

		return $tree;
	}

	/**
	 * Synchronize ACL.
	 *
	 * @param boolean $fullSync True to fully synchronize the ACL,
	 *                          otherwise just update the Aco Tree with new controller actions.
	 * @return boolean			True on success, False otherwise.
	 */
	public function syncAcl($fullSync = true) {
		App::uses('AppModule', 'Lib');
		AppModule::loadAll();

		App::uses('AclExtras', 'AclExtras.Lib');
		$this->AclExtras = new AclExtras();
		$this->AclExtras->startup();
		$this->AclExtras->controller->constructClasses();
		
		if ($fullSync === false) {
			return $this->AclExtras->aco_update();
		}

		return $this->AclExtras->aco_sync();
	}

	/**
	 * Runs DB migrations from within the app.
	 *
	 * @return TBD
	 */
	public function runMigrations($target = null) {
		try {
			// lets disable database cache
			$ds = ConnectionManager::getDataSource('default');
			$ds->cacheSources = false;

			// lets check if there is any new migration
			$PhinxApp = new PhinxApp();
			$ret = $PhinxApp->getStatus();

			// run migrations if necessary
			if ($ret !== true || $target !== null) {
				$ret = $PhinxApp->getMigrate($target);
			}
		}
		catch (Exception $e) {
			App::uses('CakeLog', 'Log');
			CakeLog::write('error', 'Migration error: ' . $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine() . "\n" . $e->getTraceAsString());
			return false;
		}

		return $ret;
	}

	public function syncVisualisation() {
		App::uses('VisualisationShell', 'Visualisation.Console/Command');
		$VisualisationShell = new VisualisationShell();
		$VisualisationShell->startup();
		return $VisualisationShell->acl_sync();
	}

	// recalculate risk scores after fixes to it in this update package
	public function runComposer() {
		// lets disable debug so people wont have --dev vendors
		$debug = Configure::read('debug');
		Configure::write('debug', 0);

		App::uses('SystemShell', 'Console/Command');

		$SystemShell = new SystemShell();
		$SystemShell->startup();
		$SystemShell->args = ['update'];

		$SystemShell->composer();

		Configure::write('debug', $debug);
	}

	public function destroyOtherSessions() {
		App::import('Model', 'CakeSessionTable');
		$session = new CakeSessionTable();

		$session->deleteAll(array(
			'CakeSession.id !=' => CakeSession::id()
		));
	}
}
