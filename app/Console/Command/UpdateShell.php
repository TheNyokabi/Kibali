<?php
App::uses('AppShell', 'Console/Command');
App::uses('DbAcl', 'Model');
App::uses('CacheDbAcl', 'Lib');
App::uses('Hash', 'Utility');

class UpdateShell extends AppShell {
	public $uses = array('Setting');

	public function startup() {
		parent::startup();
	}

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(
				'Updates Shell Helper.' .
				'')
			->addSubcommand('deploy', array(
				'help' => __('Deploy fresh database on your current datasource. Drops all existing tables, runs migrations to the latest database. Will reset your CLIENT ID if you have one defined already.')))
			->addSubcommand('reset', array(
				'help' => __('Same as deploy but will keep your CLIENT ID.')))
			->addSubcommand('syncAcl', array(
				'help' => __('ACL synchronization.')))
			->addSubcommand('deleteCache', array(
				'help' => __('Deletes cache.')))
			->addSubcommand('cleanup', array(
				'help' => __('ACL Sync and delete cache both.')));
	}

	public function drop_tables() {
		if($this->Setting->dropAllTables()){
			$this->out('All tables removed.');
		}
		else {
			$this->error('Error occured!');
		}
	}

	public function deploy() {
		$this->out('Deploying fresh database...');

		$options = array(
			'name' => 'Setting', 'class' => 'Setting', 'table' => false, 'ds' => 'default', 'alias' => 'Setting'
		);

		$m = ClassRegistry::init($options);
		$m->useTable = false;
		
		$dataSource = $m->getDataSource();
		$dataSource->begin();

		$ret = $m->resetDatabase(false);
		
		if($ret){
			$this->out('Deployed successfully! Your app will get registered with a new Client ID upon first login.');
			$dataSource->commit();
		}
		else {
			$this->error('Error occured while trying to deploy a fresh installation!');
			$dataSource->rollback();
		}
	}

	public function reset() {
		$this->out('Reseting database...');

		$dataSource = $this->Setting->getDataSource();
		$dataSource->begin();

		$ret = $this->Setting->resetDatabase(true);

		if($ret){
			$this->out('Reset done! Your application registration remained unchanged.');
			$dataSource->commit();
		}
		else {
			$this->error('Error occured!');
			$dataSource->rollback();
		}
	}

	public function cleanup() {
		$this->syncAcl();
		$this->deleteCache();
	}

	public function syncAcl() {
		$this->out('Syncing ACL...');

		$this->Setting->syncAcl();

		$this->out('ACL in sync!');
	}

	public function deleteCache() {
		$this->out('Deleting cache...');

		$cache = $this->Setting->deleteCache('');

        $this->out($cache ? 'Cache removed' : 'Cache partially removed');
    }

    public function system_hash() {
    	App::uses('Folder', 'Utility');
		App::uses('File', 'Utility');
    	$this->out($this->Setting->getIntegrityHash());
    }

    public function hey_there() {
        $this->out('Hey there ' . $this->args[0]);
    }
}