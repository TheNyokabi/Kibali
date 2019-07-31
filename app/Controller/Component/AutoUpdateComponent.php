<?php
App::uses('Component', 'Controller');
App::uses('ErambaHttpSocket', 'Network/Http');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('AppModule', 'Lib');
App::uses('CronException', 'Cron.Error');

class AutoUpdateComponent extends Component {
	private $requestUrl;
	private $responseUrl;

	private $workingFolderPath;
	private $updateFolderPath;
	private $backupFolderPath;

	private $ignoreFiles = array('gitdelete');

	private $clientId;
	private $clientKey;

	private $error = false;
	private $errorMessage = '';
	private $lastErrorException = null;

	/**
	 * Flag for the list of system health checks to skip while doing update check.
	 * 
	 * @var array
	 */
	public $skipHealthCheck = [];

	public $components = array('ErambaHttp', 'SystemHealth');

	public function initialize(Controller $controller) {
		$this->controller = $controller;

		$apiUrl = Configure::read('Eramba.SUPPORT_API_URL');
		$this->requestUrl = $apiUrl . '/api/check-update';
		$this->responseUrl = $apiUrl . '/api/update-result';

		$this->workingFolderPath = UPDATES_PATH;
		$this->updateFolderPath = UPDATES_PATH . 'update' . DS;
		$this->backupFolderPath = UPDATES_PATH . 'backup' . DS;

		$this->clientId = CLIENT_ID;
		$this->clientKey = $this->getClientKey();

		// $this->ErambaHttp->initialize($controller);
		$this->SystemHealth->initialize($controller);
	}

	/**
	 * returns client ID
	 * 
	 * @return string
	 */
	public function getClientKey() {
		$keyFile = new File(APP . DS . 'Vendor' . DS . 'other' . DS . 'CLIENT_KEY');

		if ($keyFile->exists()) {
			return trim($keyFile->read());
		}

		return false;
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
	 * Last issued CronException object with error message.
	 * 
	 * @return CronException
	 */
	public function getLastCronException() {
		return $this->lastErrorException;
	}

	/**
	 * @return string error message
	 */
	public function setError($message) {
		$this->error = true;

		$CronException = new CronException($message);
		$message = $CronException->getFullMessage();

		$this->lastErrorException = $CronException;
		$this->errorMessage = $message;
		$this->logMessage($message);
	}

	/**
	 * log 
	 * 
	 * @param mixed $message
	 */
	private function logMessage($message) {
		CakeLog::write('updates', $message);
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

		return $http->post($url, json_encode($body));
	}

	private function download($url, $file) {
		$config = $this->ErambaHttp->config;
		$config['timeout'] = 60;

		$http = new ErambaHttpSocket($config);
		$http->configAuth('Basic', $this->clientId, $this->clientKey);
		$http->setContentResource($file);

		return $http->post($url);
	}

	/**
	 * Checks the response in cache if there are pending updates.
	 */
	public function hasPending() {
		$response = Cache::read('server_response', 'updates');
		if (!empty($response)) {
			if ($response['success'] && $response['response']['updates']) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check system health critical dependencies.
	 * 
	 * @return boolean health status
	 */
	public function checkSystemHealth() {
		$systemHealth = $this->SystemHealth->checkCriticalStatuses([
			'skip' => $this->skipHealthCheck
		]);

		if (!$systemHealth) {
			$url = Router::url(['plugin' => false, 'controller' => 'settings', 'action' => 'systemHealth']);
			$healthPage = '<a href="' . $url . '">' . __('System Health') . '</a>';

			$this->setError(__('Since there seem to be system errors (on the linux system were this eramba runs) we cant let you update the system. Visit the %s page check for details on what could be wrong.', $healthPage));
		}

		return (boolean) $systemHealth;
	}

	/**
	 * Check for new updates.
	 */
	public function check() {
		//check system health critical dependencies
		if (!$this->checkSystemHealth() && !Configure::read('debug')) {
			return false;
		}

		if (($response = Cache::read('server_response', 'updates')) === false) {
			$requestBody = array(
				'appVersion' => Configure::read('Eramba.version'),
				'dbVersion' => $this->controller->Setting->getVariable('DB_SCHEMA_VERSION'),
				'phpVersion' => PHP_VERSION,
				'integrityHash' => $this->controller->Setting->getIntegrityHash()
			);
			$request = $this->request($this->requestUrl, $requestBody);
			
			if (!$request || !$request->isOk()) {
				$this->setError(__('The system was not able to connect to our update servers. Please check your internet connection or proxy settings (review our documentation on how to setup proxy settings).'));
				return false;
			}

			$response = json_decode($request->body(), true);

			if (!$response['success']) {
				$this->setError($response['message']);
				return false;
			}

			Cache::write('server_response', $response, 'updates');
		}

		return $this->filterResponseData($response);
	}	

	/**
	 * compare response data with system version
	 */
	private function filterResponseData($response) {
		$systemVersion = Configure::read('Erramba.Settings.version');

		if (!empty($response['response']['pending'])) {
			$pendingUpdates = array();

			foreach ($response['response']['pending'] as $update) {
				if ($systemVersion < $update['version']) {
					$pendingUpdates[] = $update;
				}
			}

			$response['response']['pending'] = $pendingUpdates;
		}

		return $response;
	}

	/**
	 * update
	 * 
	 * @return boolean success/fail
	 */
	public function update() {
		$update = $this->processNextUpdate();

		if (!$update) {
			$this->sendFailResponse();
		}

		return $update;
	}

	/**
	 * process next available update
	 * 
	 * @return boolean success/false
	 */
	private function processNextUpdate() {
		Cache::delete('server_response', 'updates');

		$updates = $this->check();

		if (!$updates['response']['updates'] || empty($updates['response']['pending'])) {
			return false;
		}

		$cacheGlobalConfig = Configure::read('Cache.disable');
		Configure::write('Cache.disable', true);
        $ds = ConnectionManager::getDataSource('default');
        $ds->cacheSources = false;

		if (!$this->prepare($updates)) {
			return false;
		}

		if (!$this->execute()) {
			return false;
		}

		$this->finish();

		Configure::write('Cache.disable', $cacheGlobalConfig);

		return true;
	}

	/**
	 * check dependencies, download update, check permissions, create files and DB backup
	 *
	 * @param array $updates updates response form server
	 * @return boolean success/fail
	 */
	private function prepare($updates) {
		$this->deteteWorkingFolder();

		if (!$this->checkDependencies($updates['response']['pending'][0])) {
			$this->setError('Dependencies not match.');
			return false;
		}

		if (!$this->getUpdatePack($updates['response']['pending'][0]['url'])) {
			$this->setError('You are trying to pull an update which is not available. Please contact support with a screenshot of this error.');
			return false;
		}
		
		if (!$this->checkPermissions()) {
			$files = $this->listNoPermissionsFiles();
			$this->setError('The following files and folders have no required writable permissions. Please contact your system administrator and ensure they can all be written by the web server: ' . PHP_EOL . implode(PHP_EOL, $files));
			return false;
		}

		if (!$this->createFilesBackup()) {
			$this->setError('Cannot create files backup, please contact support with a screenshot of this error.');
			return false;
		}

		if (!$this->createDatabaseBackup()) {
			$this->setError('Cannot create database backup, please contact support with a screenshot of this error.');
			return false;
		}

		return true;
	}

	/**
	 * destroy other sessions, execute copying of files, deleting files, updating of database 
	 * 
	 * @return boolean success/fail
	 */
	private function execute() {
		//delete others sessions
		$this->controller->Setting->destroyOtherSessions();

		if (!$this->copyUpdateFiles()) {
			$this->restoreFilesBackup();
			$this->setError('Copying of update files failed, please contact support with a screenshot of this error.');
			return false;
		}

		if (!$this->deleteFiles()) {
			$this->restoreFilesBackup();
			$this->setError('Deleting files from list failed, please contact support with a screenshot of this error.');
			return false;
		}

		App::uses('AppModule', 'Lib');
		AppModule::loadAll();

		//update DB
		if (!$this->controller->Setting->runMigrations()) {
			$this->restoreFilesBackup();
			$this->restoreDatabaseBackup();
			$this->setError('Updating database failed, please contact support with a screenshot of this error.');
			return false;
		}

		$this->updateSystemVersion();

		$updateClass = $this->getUpdateClass();
		if ($updateClass !== false && $updateClass->run() === false) {
			$errorMsg = $updateClass->getMessage();
			$this->setError(!empty($errorMsg) ? $errorMsg : 'Update was not successful, please contact support with a screenshot of this error.');

			return false;
		}

		return true;
	}

	// get update class to run after the update as a callback with new code that came with the update package
	public function getUpdateClass() {
		// after update callback
		$path = APP . 'Config' . DS . 'Updates' . DS;

		// $version is for example: e1.0.6.036
		$version = Configure::read('Eramba.version');

		// $className would be Update036
		$className = sprintf('Update%s', Inflector::camelize(substr($version, -3, 3)));

		//updateClass
		$file = $path . $className . '.php';

		if (file_exists($file)) {
			include_once $file;

			$updateClass = new $className();
			if ($updateClass instanceof AbstractUpdate) {
				return $updateClass;
			}
		}

		return false;
	}

	/**
	 * finish update, delete working directory,  send response
	 */
	private function finish() {
		$this->deteteWorkingFolder();
		$this->sendSuccessResponse();

		//sync acl
		$this->controller->Setting->syncAcl();
		// $this->controller->Setting->syncVisualisation();

		// run composer
		// $this->controller->Setting->runComposer();

		//delete cache
		$this->controller->Setting->deleteCache(null);
	}

	/**
	 * send success response after success update
	 */
	private function sendSuccessResponse() {
		$requestBody = array(
			'version' => Configure::read('Eramba.version'),
			'db_version' => $this->controller->Setting->getVariable('DB_SCHEMA_VERSION'),
			'success' => true
		);
		$request = $this->request($this->responseUrl, $requestBody);

		if ($request && $request->isOk()) {
			$body = json_decode($request->body, true);

			if (!$body['success']) {
				$this->setError($body['message']);
			}
		} 
	}

	/**
	 * send error response after fail
	 */
	private function sendFailResponse() {
		$updates = $this->check();
		$requestBody = array(
			'version' => $updates['response']['pending'][0]['version'],
			'success' => false
		);
		$request = $this->request($this->responseUrl, $requestBody);
	}

	/**
	 * delete update working tmp folder (update, backup)
	 */
	private function deteteWorkingFolder() {
		$updateFolder = new Folder($this->updateFolderPath);
		$updateFolder->delete();

		$backupFolder = new Folder($this->backupFolderPath);
		$backupFolder->delete();
	}

	/**
	 * download and unpack update
	 * 
	 * @param  sring $url url to download update pack
	 * @return boolean success/fail
	 */
	private function getUpdatePack($url) {
		$workingFolder = new Folder(UPDATES_PATH, true, 0755);
		$updateFolder = new Folder($this->updateFolderPath, true, 0755);

		$file = fopen(UPDATES_PATH . 'update.tar', 'w');
		$this->download($url, $file);
		fclose($file);

		try {
			$phar = new PharData(UPDATES_PATH . 'update.tar');
			$phar->extractTo($updateFolder->path);
		} catch (Exception $e) {
			return false;
		}
	
		return true;
	}

	/**
	 * returns real mirror path to file/folder in tmp update folder
	 * 
	 * @param  string $updatePath file/folder path in tmp update folder
	 * @return string
	 */
	private function updateToRealPath($updatePath) {
		return str_replace($this->updateFolderPath, ROOT . DS, $updatePath);
	}

	/**
	 * returns backup mirror path to file/folder in tmp update folder
	 * 
	 * @param  string $updatePath file/folder path in tmp update folder
	 * @return string
	 */
	private function updateToBackupPath($updatePath) {
		return str_replace($this->updateFolderPath, $this->backupFolderPath, $updatePath);
	}

	/**
	 * returns backup mirror path to real file/folder path 
	 * 
	 * @param  string $path file/folder path
	 * @return string
	 */
	private function realToBackupPath($path) {
		return str_replace(ROOT . DS, $this->backupFolderPath, $path);
	}

	/**
	 * filter ingnored files from list of files and folders
	 * 
	 * @param  array $tree list of files and folders from File::tree()
	 * @return array
	 */
	private function filterIgnoreFiles($tree) {
		foreach ($tree[1] as $key => $file) {
			if (in_array(basename($file), $this->ignoreFiles)) {
				unset($tree[1][$key]);
			}
		}

		return $tree;
	}

	/**
	 * check all dependencies
	 *
	 * @param  string $update data of single update from response
	 * @return boolean success/fail
	 */
	private function checkDependencies($update) {
		$appVersion = Configure::read('Eramba.version');
		return ($appVersion == $update['depend_prev']['version']);
	}

	/**
	 * check if files and folders have writable permission
	 * 
	 * @return boolean success/fail
	 */
	private function checkPermissions() {
		$permissions = true;
		$updateFolder = new Folder($this->updateFolderPath);
		$tree = $this->filterIgnoreFiles($updateFolder->tree());

		//folders
		foreach ($tree[0] as $folderPath) {
			$realPath = $this->updateToRealPath($folderPath);
			
			if ($this->skipRealPath($realPath)) {
				continue;
			}
			if (file_exists($realPath) && !is_writable($realPath)) {
				return false;
			}
		}
		//files
		foreach ($tree[1] as $filePath) {
			$realPath = $this->updateToRealPath($filePath);
			if (file_exists($realPath) && !is_writable($realPath)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * return list of files and folder with no writable permissions
	 * 
	 * @return array
	 */
	private function listNoPermissionsFiles() {
		$list = array();
		$updateFolder = new Folder($this->updateFolderPath);
		$tree = $this->filterIgnoreFiles($updateFolder->tree());

		//folders
		foreach ($tree[0] as $folderPath) {
			$realPath = $this->updateToRealPath($folderPath);
			if ($this->skipRealPath($realPath)) {
				continue;
			}
			if (file_exists($realPath) && !is_writable($realPath)) {
				$list[] = $realPath;
			}
		}
		//files
		foreach ($tree[1] as $filePath) {
			$realPath = $this->updateToRealPath($filePath);
			if (file_exists($realPath) && !is_writable($realPath)) {
				$list[] = $realPath;
			}
		}

		return $list;
	}

	/**
	 * Root folders to skip when checking permissions as they will not be affected.
	 * Root folder; root/app; root/app/
	 */
	private function skipRealPath($realPath) {
		return $realPath == (ROOT . DS) || $realPath == (ROOT . DS . APP_DIR) || $realPath == APP;
	}

	/**
	 * create backup of files and folders that will be affected
	 * 
	 * @return boolean success/fail
	 */
	private function createFilesBackup() {
		$backupFolder = new Folder($this->backupFolderPath, true, 0755);
		$updateFolder = new Folder($this->updateFolderPath);
		$tree = $this->filterIgnoreFiles($updateFolder->tree());

		//folders
		foreach ($tree[0] as $folderPath) {
			$realFolder = new Folder($this->updateToRealPath($folderPath));
			$folder = new Folder();
			if (!empty($realFolder->path) && !$folder->create($this->updateToBackupPath($folderPath), 0755)) {
				return false;
			}
		}
		//files
		foreach ($tree[1] as $filePath) {
			$file = new File($this->updateToRealPath($filePath));
			if ($file->exists() && !$file->copy($this->updateToBackupPath($filePath))) {
				return false;
			}
		}

		$ret = $this->backupGitdeleteFiles();

		return $ret;
	}

	/**
	 * create backup of files and folders listed in gitdelete file
	 * 
	 * @return boolean success/fail
	 */
	private function backupGitdeleteFiles() {
		$deleteFile = new File($this->updateFolderPath . 'gitdelete');

		if (!$deleteFile->exists()) {
			return true;
		}

		$success = true;

		$deleteFile->open('r');

		while (($line = fgets($deleteFile->handle)) !== false) {
			$line = trim($line);

			if ($line == '' || strlen($line) < 2 || !file_exists(ROOT . DS . $line)) {
				continue;
			}

			if (is_file(ROOT . DS . $line)) {
				$file = new File(ROOT . DS . $line);
				if ($file->Folder->path != ROOT) {
					$folders = explode(DS, str_replace(ROOT . DS, '', $file->Folder->path));
					$folderPath = $this->backupFolderPath;
					foreach ($folders as $folderName) {
						$folder = new Folder($folderPath . $folderName, true);
						$folderPath = $folder->path . DS;
					}
				}

				if (!$file->copy($this->realToBackupPath($file->path), true)) {
					$success = false;

				}
			}
		}

		$deleteFile->close();

		return $success;
	}

	/**
	 * restore backup
	 */
	private function restoreFilesBackup() {
		$updateFolder = new Folder($this->updateFolderPath);
		$tree = $this->filterIgnoreFiles($updateFolder->tree());

		//files
		foreach ($tree[1] as $filePath) {
			$realFile = new File($this->updateToRealPath($filePath));
			$backupFile = new File($this->updateToBackupPath($filePath));
			if ($backupFile->exists()) {
				$backupFile->copy($this->updateToRealPath($filePath), true);
			}
			else {
				$realFile->delete();
			}
		}
		//folders
		foreach (array_reverse($tree[0]) as $folderPath) {
			$realFolder = new Folder($this->updateToRealPath($folderPath));
			$backupFolder = new Folder($this->updateToBackupPath($folderPath));
			if (empty($backupFolder->path)) {
				$realFolder->delete();
			}
		}

		$this->restoreDeletedFiles();
	}

	/**
	 * restore deleted files and folders listed in gitdelete file
	 */
	private function restoreDeletedFiles() {
		$deleteFile = new File($this->updateFolderPath . 'gitdelete');

		if (!$deleteFile->exists()) {
			return;
		}

		$deleteFile->open('r');

		while (($line = fgets($deleteFile->handle)) !== false) {
			$line = trim($line);

			if ($line == '' || strlen($line) < 2 || !file_exists($this->backupFolderPath . $line)) {
				continue;
			}

			if (is_dir($this->backupFolderPath . $line)) {
				$realFolder = new Folder(ROOT . DS . $line, true);
			}
			else {
				$backupFile = new File($this->backupFolderPath . $line);
				if ($backupFile->exists()) {
					$realFolder = new Folder(ROOT . DS . dirname($line), true);
					$backupFile->copy($this->updateToRealPath(ROOT . DS . $line), true);
				}
			}
		}

		$deleteFile->close();
	}

	/**
	 * execute file update, copy update failes to real paths
	 * 
	 * @return boolean success/fail
	 */
	private function copyUpdateFiles() {
		$updateFolder = new Folder($this->updateFolderPath);
		$tree = $this->filterIgnoreFiles($updateFolder->tree());
		//folders
		foreach ($tree[0] as $folderPath) {
			$folder = new Folder();
			if (!$folder->create($this->updateToRealPath($folderPath), 0755)) {
				return false;
			}
		}
		//files
		foreach ($tree[1] as $filePath) {
			$file = new File($filePath);
			if (!$file->copy($this->updateToRealPath($filePath), true)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * delete system files from gitdelete list
	 * 
	 * @return boolean success/fail
	 */
	private function deleteFiles() {
		$deleteFile = new File($this->updateFolderPath . 'gitdelete');

		if (!$deleteFile->exists()) {
			return true;
		}

		$deleteFile->open('r');

		$ret = true;
		while (($line = fgets($deleteFile->handle)) !== false) {
			$line = trim($line);

			if ($line == '' || strlen($line) < 2) {
				continue;
			}

			$path = ROOT . DS . $line;

			// directory check and file check before trying to delete it, otherwise it would fail.
			if (is_dir($path)) {
				$folder = new Folder($path);

				// pwd() cannot be null
				if ($folder->pwd() !== null) {
					$ret &= $folder->delete();
				}
			}
			elseif (file_exists($path)) {
				$file = new File($path);

				// additional check via cakephp
				if ($file->exists()) {
					$ret &= $file->delete();
				}
			}
		}

		$deleteFile->close();

		return $ret;
	}

	/**
	 * create database backup
	 * 
	 * @return boolean success/fail
	 */
	public function createDatabaseBackup() {
		return $this->controller->BackupRestore->backupDatabase($this->workingFolderPath . 'sqlbackup.sql', true);
	}

	/**
	 * restore database backup
	 * 
	 * @return boolean success/fail
	 */
	public function restoreDatabaseBackup() {
		// we drop all tables in case new tables could have been already created during an update and additional updating will be failing everytime because new tables would already exist.
		$ret = $this->controller->Setting->dropAllTables();

		// we restore the backed up database.
		$ret &= $this->controller->Setting->runSchemaFile($this->workingFolderPath . 'sqlbackup.sql');

		return $ret;
	}

	/**
	 * update system version variable
	 */
	private function updateSystemVersion() {
		$versionFile = new File(ROOT . DS . 'VERSION');
		$appVersion = trim($versionFile->read());
		Configure::write('Eramba.version', $appVersion);
	}
}
