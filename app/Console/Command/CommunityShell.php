<?php
/**
 * Acl Visualisation Shell.
 */
App::uses('AppShell', 'Console/Command');
App::uses('ClassRegistry', 'Utility');
App::uses('Hash', 'Utility');

/**
 * Shell for Visualisation ACOs
 *
 * @package		Visualisation.Console.Command
 */
class CommunityShell extends AppShell {

/**
 * Contains arguments parsed from the command line.
 *
 * @var array
 * @access public
 */
	public $args;

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
		Configure::write('Cache.disable', true);
		
		parent::startup();
	}

	// update to the latest db version
	public function update() {
		$ret = true;

		App::uses('ConnectionManager', 'Model');
		Configure::write('Cache.disable', true);

		$ds = ConnectionManager::getDataSource('default');
        $ds->cacheSources = false;

		$Setting = ClassRegistry::init('Setting');

		$Setting->query("DELETE FROM `phinxlog` WHERE (`migration_name` = 'CommunityRelease15');");
		ClassRegistry::flush();
		$ret &= $this->_data();
		$Setting->deleteCache(null);
		ClassRegistry::flush();

		$this->out('<info>Running migrations ...</info>');

		$ret &= $Setting->runMigrations();
		$ret &= $this->_dataAfter();

		// reset app id
		$Setting->query("UPDATE `settings` SET `value` = NULL WHERE `variable` = 'CLIENT_ID';");
		if ($ret) {
			$this->out('<success>Update was successfull</success>');
		}
		else {
			$this->out('<error>Error occured while trying to update your database to the latest version</error>');
		}
	}

	protected function _dataAfter() {
		 $AssetClassification = ClassRegistry::init('AssetClassification');
		$class = $AssetClassification->find('list', array(
			'fields' => array('id', 'id'),
			'recursive' => -1
		));

		$ret = true;

		foreach ($class as $c) {
			$AssetClassification->id = $c;
			$ret &= $AssetClassification->saveField('value', '0', array(
				'validate' => false,
				'callbacks' => false
			));	
		}

		$ComplianceFinding = ClassRegistry::init('ComplianceFinding');
		$ret &= $ComplianceFinding->updateAll(
			array(
				'ComplianceFinding.deadline' => NULL
			),
			array('ComplianceFinding.deadline' => '0000-00-00')
		);

		return $ret;
	}

	protected function _data() {
		$ret = true;

		$SettingGroup = ClassRegistry::init('SettingGroup');
        $ds = $SettingGroup->getDataSource();
        $hidden = $ds->value(0, 'string');

        $ret &= $SettingGroup->updateAll(
            array(
                'SettingGroup.hidden' => $hidden
            ),
            array('SettingGroup.slug' => 'BAR')
        );

       

		$RisksSecurityPolicy = ClassRegistry::init('RisksSecurityPolicy');

		$ds = $RisksSecurityPolicy->getDataSource();
		$incident = $ds->value('incident', 'string');

		$ret &= $RisksSecurityPolicy->updateAll(
			array(
				'RisksSecurityPolicy.type' => $incident
			),
			array('RisksSecurityPolicy.risk_type' => array('third-party-risk', 'business-risk'))
		);


		$SettingGroup = ClassRegistry::init('SettingGroup');

		$ds = $SettingGroup->getDataSource();
		$url = $ds->value('{"controller":"backupRestore","action":"index", "plugin":"backupRestore"}', 'string');
		$ret &= $SettingGroup->updateAll(
			array(
				'SettingGroup.url' => $url
			),
			array('SettingGroup.slug' => 'BAR')
		);

		$Setting = ClassRegistry::init('Setting');
		$opts = $ds->value('{"0":"No Encryption","1":"SSL","2":"TLS"}', 'string');
		$ret &= $Setting->updateAll(
			array(
				'Setting.name' => "'Encryption'",
				'Setting.type' => "'select'",
				'Setting.options' => $opts
			),
			array('Setting.variable' => 'USE_SSL')
		);

		return $ret;
	}

	public function getOptionParser() {		
		return parent::getOptionParser()
			->description(__("Visualisation shell helper manager"))
			->addSubcommand('update')
			->addSubcommand('update', array(
				'help' => __('Update Community to the latest version.')
			));
	}

}
