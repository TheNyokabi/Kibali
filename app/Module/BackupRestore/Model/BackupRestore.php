<?php
App::uses('BackupRestoreAppModel', 'BackupRestore.Model');
App::uses('DebugMemory', 'DebugKit.Lib');
App::uses('CakeLog', 'Log');
App::uses('CakeNumber', 'Utility');
App::uses('File', 'Utility');

class BackupRestore extends BackupRestoreAppModel {
	public $useTable = false;

	public $actsAs = array(
		'Uploader.FileValidation' => array(
			'ZipFile' => array(
				'extension' => array('zip'),
				'mimeType' => array('application/x-zip-compressed', 'application/zip-compressed', 'application/zip'),
				'required' => array(
					'rule' => array('required'),
					'message' => 'File required',
				)
			)
		)
	);

	/**
	 * Makes a backup of current database.
	 */
	public function backupDatabase($path = null, $dropTables = false) {
		$ret = true;

		$ds = $this->getDataSource($this->useDbConfig);
		$database = $ds->config['database'];
		$file = new File($path);

		if ($file->exists()) {
			$ret &= $file->delete();
		}

		// create blank output file
		$ret &= $file->safe() && $file->create();

		if (!$ret) {
			return false;
		}

		//exclude queue table from exporting values because of MEDIUM_TEXT data column
		$excludedTableInserts = array('queue');

		// get all table names in the database so we backup everything not depending on the app version
		$result = $this->query( "SHOW TABLES" );
		$tables = array();

		foreach ( $result as $table ) {
			$tables[] = current( $table['TABLE_NAMES'] );
		}

		$return = "SET FOREIGN_KEY_CHECKS=0;\n\n";
		foreach ( $tables as $table ) {
			if ($dropTables) {
				$return .= 'DROP TABLE IF EXISTS `' . $table . '`;';
			}
			else {
				$return .= "TRUNCATE `" . $table . "`;\n\n";
			}
			
			// create table statement
			$create_table = $this->query( "SHOW CREATE TABLE " . $table );

			if ($dropTables) {
				$return .= "\n\n" . $create_table[0][0]['Create Table'] . ";\n\n";
			}

			// skip one of the excluded tables defined statically
			if (in_array($table, $excludedTableInserts)) {
				continue;
			}

			// for performance reasons we consider splitting SELECT statement by size of each table
			$status = $this->query( "SHOW TABLE STATUS FROM  {$database} LIKE '" . $table ."'" );
			$tableSize = $status[0]['TABLES']['Data_length'];

			// iterate each 4.77mb of data from each table
			$splitRows = ceil($tableSize/5000000);

			// in case no splitting is required, skip additional calculations
			$selectIterate = $limit = null;
			if ($splitRows != 1) {
				$count = $this->query( "SELECT COUNT(*) FROM " . $table );
				$count = $count[0][0]['COUNT(*)'];
				$selectIterate = ceil($count / $splitRows);
			}

			$result = [];
			for ($i=0; $i < $splitRows; $i++) {
				// builds OFFSET part of the LIMIT part in the SELECT query
				$key = '';
				if ($i > 0) {
					$key = ($i * $selectIterate);
					$key = $key  . ',';
				}

				// builds LIMIT part using previously calculated OFFSET
				$limit = '';
				if ($selectIterate !== null) {
					// e.g LIMIT 15, 30
					$limit = "LIMIT {$key} {$selectIterate}";
				}
				
				// pull requested data
				$nextIterationData = $this->query( "SELECT * FROM " . $table . " {$limit}");

				// in case there is no data to be inserted for this certain table, we skip putting an INSERT statement
				if (empty($nextIterationData)) {
					continue;
				}

				// builds INSERT statement(s) depending on calculations
				$return .= 'INSERT INTO `' . $table . '` VALUES ';
				$lengthRow = count($nextIterationData);
				$iRow = 0;
				foreach ( $nextIterationData as $row ) {
					$return .= "(";

					$lengthCol = count( $row[ $table ] );
					$iCol = 0;
					foreach ( $row[ $table ] as $value ) {
						$return .= $ds->value($value);
						
						if ( $iCol < $lengthCol - 1 ) {
							$return .= ',';
						}

						$iCol++;
					}
					$iRow++;

					if ( $iRow < $lengthRow ) {
						$return .= "),\n";
					}
					else {
						 $return .= ");\n";
					}
				}

				$ret &= $file->append($return);
				$return = '';
			}
		}
		$return .= "SET FOREIGN_KEY_CHECKS=1;";

		$ret &= $file->append($return) && $file->close();
		$return = '';

		// lets log unusually high memory usage, more than 500mb
		if ($file->size() >= 500000000) {
			$size = CakeNumber::toReadableSize($file->size());
			CakeLog::write(LOG_DEBUG, sprintf('Database Backup file too huge - %s', $size));
		}

		return $ret;
	}

	/**
	 * adding backup files to zip archive
	 */
	public function zipBackupFiles($archivePath, $files) {
		$zip = new ZipArchive();
		if (!$zip->open($archivePath, ZipArchive::CREATE)) {
			return false;
			
		}

		foreach ($files as $archiveFileName => $file) {
			if (!$zip->addFile($file, $archiveFileName)) {
				return false;
			}
		}
		
		$zip->close();

		return true;
	}

	/**
	 * Makes a sql query to restore database.
	 *
	 * @deprecated
	 */
	public function restoreDatabase( $path = null ) {
		// Temporary variable, used to store current query
		$templine = '';
		// Read in entire file
		$lines = file( $path );

		if ( ! $lines ) {
			return false;
		}

		foreach ( $lines as $line ) {
			if ( substr( $line, 0, 2 ) == '--' || $line == '' ) {
				continue;
			}

			$templine .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';') {
				//debug($templine);
				 // Perform the query
				$this->query($templine);// or print('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
				// Reset temp variable to empty
				$templine = '';
			}
		}

		return true;
	}
}