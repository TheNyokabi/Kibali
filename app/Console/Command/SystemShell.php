<?php
/**
 * Acl Dashboard Shell.
 */
App::uses('AppShell', 'Console/Command');
App::uses('SystemHealthLib', 'Lib');
App::uses('ClassRegistry', 'Utility');
App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('SystemHealthComponent', 'Controller/Component');
App::uses('SystemHealthComponent', 'Controller/Component');
App::uses('AppComposer', 'Utility');

/**
 * Shell for Dashboard ACOs
 *
 * @package		Dashboard.Console.Command
 */
class SystemShell extends AppShell {

/**
 * Contains arguments parsed from the command line.
 *
 * @var array
 * @access public
 */
	public $args;

/**
 * SystemHealthLib instance
 */
	public $SystemHealthLib;

/**
 * Constructor
 */
	public function __construct($stdout = null, $stderr = null, $stdin = null) {
		parent::__construct($stdout, $stderr, $stdin);
	}

/**
 * Start up And load Acl Component / Aco model
 *
 * @return void
 **/
	public function startup() {
		parent::startup();
	}

	public function migrate_db() {
		$Setting = ClassRegistry::init([
			'table' => false,
			'ds' => 'default',
			'alias' => 'Setting',
			'name' => 'Setting',
			'class' => 'Setting'
		]);
		$ret = $Setting->runMigrations();
		
		if ($ret) {
			$this->out("Database migration completed successfully.");
		}
		else {
			$this->out("<error>Error(s) occured while migrating your database</error>");
		}
	}

	public function sync_db() {
		$ret = ClassRegistry::init('SecurityPolicyDocumentType')->syncSecurityPolicyDocumentTypes();
		
		if ($ret) {
			$this->out("Additional database sync completed successfully.");
		}
		else {
			$this->out("<error>Error(s) occured while doing an additional database sync</error>");
		}
	}

	public function delete_cache() {
		return $this->dispatchShell('update', 'deleteCache');
	}

	public function composer() {
		$ret = true;

		if (empty($this->args)) {
			$this->error(__('Argument for this command is missing. Use either composer update or composer install to proceed.'));
		}

		$AppComposer = new AppComposer();
		$AppComposer->Shell = $this;
		
		$cmd = $this->args[0];

		if (!in_array($cmd, ['install', 'update'])) {
			$this->error('Invalid command to execute in composer.');
		}

		$AppComposer->{$cmd}();
		if (!$AppComposer->hasError()) {
			$this->out("Composer command 'composer {$cmd}' completed successfully.");
		}
		else {
			$this->out("<error>Error(s) occured while running a composer:</error>");
			$this->out($AppComposer->getErrors());
			$this->error('');
		}
	}

/**
 * Sync the ACO table
 *
 * @return void
 **/
	public function check() {
		$ret = true;

		$controller = new Controller(new CakeRequest());
		$collection = new ComponentCollection();
		$this->SystemHealth = new SystemHealthComponent($collection);
		$this->SystemHealth->startup($controller);
		$this->controller = $controller;
		$this->SystemHealthLib = new SystemHealthLib();

		$this->SystemHealth->loadChecks();
		$data = $this->SystemHealth->getStatuses();

		$this->out('System health check started...');

		$width = 70;
		foreach ($data as $groupKey => $group) {
			$this->out(null);
			$this->out('' . str_repeat('-', $width) . '');
			$this->out('Category: ' . $group['groupName'] . '');
			$this->out('' . str_repeat('-', $width) . '');
			$this->out(null);
			foreach ($group['checks'] as $checkKey => $check) {
				$check = $this->SystemHealth->initCheckFunction($check['fn']);

				$name = $data[$groupKey]['checks'][$checkKey]['name'];
				$description = $data[$groupKey]['checks'][$checkKey]['description'];

				$name = '' . $name . ' ... ';
				if ($check) {
					$name .= '<success>OK</success>';
				}
				else {
					$name .= '<error>NOT OK</error>';
				}

				$this->out($name);

				// show desciption for failed checks or when description parameter was passed to the command
				if (!$check || !empty($this->params['verbose'])) {
					$this->out('<info>' . strip_tags($description) . '</info>');
					$this->out(null);
				}
				
				$ret &= $check;
			}
		}

		if ($ret) {
			$this->out('Everything looks fine.');
		}
		else {
			$this->error('Some issues have been highlighted.');
		}

		return $ret;
	}

	public function getOptionParser() {
		return parent::getOptionParser()
			->description(__("System Shell"))
			->addSubcommand('check', [
				'help' => __('Console version of system health check.') . PHP_EOL .
						  __('Verbose (-v) shows description for each check, otherwise only failed checks have them.')
			])
			->addSubcommand('delete_cache', [
				'help' => __('Clean all cache.')
			])
			->addSubcommand('composer', [
				'help' => __('Run composer update, or composer install.')
			])
			->addSubcommand('migrate_db', [
				'help' => __('Database migration using Phinx - the same migration tool as the one that runs during update process.')
			])
			->addSubcommand('sync_db', [
				'help' => __('Additional database synchronization of data.')
			]);
	}

}
