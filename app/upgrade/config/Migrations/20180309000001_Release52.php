<?php
use Phinx\Migration\AbstractMigration;

class Release52 extends AbstractMigration
{
    protected function bumpVersion($value) {
        $ret = true;

        $this->query("UPDATE `settings` SET `value`='" . $value . "' WHERE `settings`.`variable`='DB_SCHEMA_VERSION'");

        if (class_exists('App')) {
            $status = [];
            App::uses('Configure', 'Core');

            if (class_exists('Configure')) {
                Configure::write('Eramba.Settings.DB_SCHEMA_VERSION', $value);
            }

            // testing handler for exception
            if (Configure::read('Eramba.TRIGGER_UPDATE_FAIL') === true) {
                $status['ConfiguredFailTriggered'] = true;
                throw new Exception("This is a test exception for failed update.", 1);
                return false;
            }

            App::uses('ConnectionManager', 'Model');
            App::uses('ClassRegistry', 'Utility');

            $ds = ConnectionManager::getDataSource('default');
            $ds->cacheSources = false;

            ClassRegistry::init('Setting')->deleteCache(null);

            if (Configure::read('Eramba.version') === 'e1.0.6.051') {
                $ret &= $this->syncVisualisation();
            }

            ClassRegistry::init('Setting')->deleteCache(null);
        }

        if (!$ret) {
            App::uses('CakeLog', 'Log');
            $log = "Error occured when processing database synchronization for release 1.0.6.052.";
            CakeLog::write('error', "{$log} \n" . print_r($status, true));

            throw new Exception($log, 1);
            return false;
        }
    }

    public function syncVisualisation() {
        App::uses('VisualisationShell', 'Visualisation.Console/Command');
        $VisualisationShell = new VisualisationShell();
        $VisualisationShell->startup();

        return $VisualisationShell->acl_sync();
    }

    public function up()
    {
        $this->bumpVersion('c1.0.1.032');
    }

    public function down()
    {
        $this->bumpVersion('e1.0.1.031');
    }
}
