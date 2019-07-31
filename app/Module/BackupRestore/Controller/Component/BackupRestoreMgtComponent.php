<?php
App::uses('Component', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class BackupRestoreMgtComponent extends Component {
    protected $_sql_backup_path;

    public $components = array('Session');
    public $settings = array();

    public function __construct(ComponentCollection $collection, $settings = array()) {
        $this->_sql_backup_path = WWW_ROOT . 'backups' . DS;

        $settings = array_merge($this->settings, (array)$settings);
        parent::__construct($collection, $settings);
    }

    public function initialize(Controller $controller) {
        $this->controller = $controller;
        $this->controller->loadModel('BackupRestore.BackupRestore');
        $this->controller->loadModel('BackupRestore.Backup');
        // $this->controller->helpers[] = 'CustomFields.CustomFields';
    }

    public function startup(Controller $controller) {

    }

    /**
     * exeutes daily backup proccess
     * 
     * @return 
     */
    public function dailyBackup() {
        if (!BACKUPS_ENABLED) {
            return true;
        }

        $fileName = date('Y-m-d') . '.sql';

        if (!$this->backupAvailable()) {
            return true;
        }

        $backupRestore = $this->controller->BackupRestore->backupDatabase($this->_sql_backup_path . $fileName, true);

        if (!$backupRestore) {
            return false;
        }

        $archiveFileName = date('Y-m-d') . '.zip';
        
        $archiveFile = $this->_sql_backup_path . $archiveFileName;
        $zipBackup = $this->controller->BackupRestore->zipBackupFiles($archiveFile, array(
            'backup.sql' => $this->_sql_backup_path . $fileName
        ));
        if (!$zipBackup) {
            $this->deleteTmpFiles();
            return false;
        }

        $this->controller->Backup->createRecord($archiveFileName);

        $this->removeExpiredBackupFiles();

        $this->deleteTmpFiles();

        return true;
    }

    private function deleteTmpFiles() {
        $sqlFile = $this->_sql_backup_path . date('Y-m-d') . '.sql';

        $file = new File($sqlFile);
        if ($file->exists()) {
            $file->delete();
        }
    }

    /**
     * check if backup is available acording to defined period
     * 
     * @return boolean
     */
    private function backupAvailable() {
        $backup = $this->controller->Backup->find('first', array(
            'conditions' => array(
                'DATE(Backup.created) >' => date('Y-m-d', strtotime('- ' . BACKUP_DAY_PERIOD . ' days'))
            )
        ));

        return empty($backup);
    }

    /**
     * remove all expired backup files 
     */
    private function removeExpiredBackupFiles() {
        $backups = $this->controller->Backup->find('all', array(
            'conditions' => array(
                'Backup.deleted_files' => Backup::FILES_NOT_DELETED,
            ),
            'order' => array(
                'Backup.created' => 'DESC'
            ),
            'limit' => 999,
            'offset' => BACKUP_FILES_LIMIT
        ));

        foreach ($backups as $backup) {
            $file = new File($this->_sql_backup_path . $backup['Backup']['sql_file']);
            if ($file->exists()) {
                $file->delete();
                $this->controller->Backup->markFilesAsDeleted($backup['Backup']['id']);
            }
        } 
    }
}
