<?php
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');

App::uses('BackupRestoreAppController', 'BackupRestore.Controller');

class BackupRestoreController extends BackupRestoreAppController {
    public $helpers = array( 'Html', 'Form' );
    public $components = array( 'Session' );

    public function beforeFilter() {
        parent::beforeFilter();
        // $this->Security->unlockedActions = array('index');
        // $this->Security->validatePost = true;
        // $this->Security->csrfCheck = false;

        if (
           ($this->request->is('post') || $this->request->is('put')) &&
           empty($_POST) && empty($_FILES)
        ) {
            $this->Security->csrfCheck = false;
        }
    }

    public function index() {
        $this->set( 'title_for_layout', __( 'Backup and Restore' ) );
        $this->set( 'subtitle_for_layout', __( 'You might generate backups in this section which includes the database (without users) and uploaded files. The zip downloaded can be also uploaded to the system. ' ) );

        if ($this->request->is(array('post', 'put'))) {
            $this->BackupRestore->set( $this->request->data );

            if ( !empty($this->request->data) && $this->BackupRestore->validates() ) {
                $tmp_name = $this->request->data['BackupRestore']['ZipFile']['tmp_name'];
                $this->restoreBackup( $tmp_name );
            }
            else {
                $this->Session->setFlash( __( 'You forgot to upload a backup file or it is in wrong format.' ), FLASH_ERROR );
            }
        }
    }

    /**
     * Extracts backup zip file and import database and attachments.
     */
    private function restoreBackup( $file ) {
        $this->cleanRestoreTmpFiles();

        $zip = new ZipArchive;
        $res = $zip->open( $file );

        if ( $res === TRUE ) {
            $zip->extractTo( TMP . 'restore/' );
            $zip->close();
        }
        else {
            $this->Session->setFlash( __( 'Error occured while opening the archive.' ) , FLASH_ERROR );
            $this->redirect( array( 'controller' => 'backupRestore', 'action' => 'index' ) );
        }

        $this->loadModel('Setting');
        $ret = $this->Setting->dropAllTables();

        // $output = $this->BackupRestore->restoreDatabase( TMP . 'restore/backup.sql' );
        $ret &= $this->Setting->runSchemaFile(TMP. 'restore/backup.sql');

        if (!$ret) {
            $this->Session->setFlash( __( 'Error occured while restoring data.' ) , FLASH_ERROR );
            $this->redirect( array( 'controller' => 'backupRestore', 'action' => 'index' ) );
        }

        // $this->recurse_copy( TMP . 'restore/uploads', APP . 'webroot/files/uploads/' );
        $this->cleanRestoreTmpFiles();

        $this->Session->setFlash( __( 'Restore completed successfully.' ) , FLASH_OK );
        $this->redirect( array( 'controller' => 'backupRestore', 'action' => 'index' ) );
    }

    private function cleanRestoreTmpFiles() {
        $dir = new Folder( TMP . 'restore' );
        $dir->delete();
    }

    private function recurse_copy( $src, $dst ) {
        $dir = opendir($src);
        //@mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                /*if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {*/
                    copy($src . '/' . $file,$dst . '/' . $file);
                //}
            }
        }
        closedir($dir);
    }

    /**
     * Makes a zip file from sql dump and file uploads.
     */
    public function getBackup() {
        $today = CakeTime::format( 'Y-m-d', CakeTime::fromString( 'now' ) );

        $this->autoRender = false;

        $this->cleanTmpFiles();

        $output = $this->BackupRestore->backupDatabase( TMP . 'backup.sql', true );

        if ( ! $output ) {
            $this->Session->setFlash( __( 'Error occured while creating a backup database file.' ), FLASH_ERROR );
            $this->redirect( array( 'controller' => 'backupRestore', 'action' => 'index' ) );
        }

        $filename = 'eramba_' . $today . '.zip';
        $filepath = TMP . $filename;
        $zipBackup = $this->BackupRestore->zipBackupFiles($filepath, array(
            'backup.sql' => TMP . 'backup.sql'
        ));
        if (!$zipBackup) {
            $this->Session->setFlash( __( 'Error occured while creating a backup file.' ), FLASH_ERROR );
            $this->redirect( array( 'controller' => 'backupRestore', 'action' => 'index' ) );
        }

        // if ( ! $zip->addEmptyDir( 'uploads' ) ) {
        //     $this->Session->setFlash( __( 'Error occured while creating a backup file.' ), FLASH_ERROR );
        //     $this->redirect( array( 'controller' => 'backupRestore', 'action' => 'index' ) );
        // }

        // $options = array( 'add_path' => 'uploads/', 'remove_all_path' => TRUE );
        // if ( ! $zip->addGlob( './files/uploads/*', GLOB_BRACE, $options ) ) {
        //     $this->Session->setFlash( __( 'Error occured while moving attachments to a backup file.' ), FLASH_ERROR );
        //     $this->redirect( array( 'controller' => 'backupRestore', 'action' => 'index' ) );
        // }

        header("Content-type: application/zip");
        header("Content-Disposition: attachment; filename={$filename}");
        //header("Content-length: " . filesize($filepath));
        header("Pragma: no-cache");
        header("Expires: 0");
        readfile("$filepath");

        $this->cleanTmpFiles();

        exit;
    }

    private function cleanTmpFiles() {
        $today = CakeTime::format( 'Y-m-d', CakeTime::fromString( 'now' ) );

        $filename = 'eramba_' . $today . '.zip';
        $filepath = TMP . $filename;

        $file = new File( $filepath );
        if ( $file ) {
            $file->delete();
        }

        $file = new File( TMP . 'backup.sql' );
        if ( $file ) {
            $file->delete();
        }
    }
}