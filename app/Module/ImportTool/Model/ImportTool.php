<?php
App::uses('ImportToolAppModel', 'ImportTool.Model');
class ImportTool extends ImportToolAppModel {
	public $useTable = false;
	/*public $actsAs = array(
		'Containable',
	);*/

	public $validate = array(
		'CsvFile' => array(
			'extension' => array(
				'required' => true,
				'rule' => array(
					'extension',
					array( 'csv' )
				),
				'message' => 'The file you uploaded is not a valid CSV file'
			)
		)
	);
}
