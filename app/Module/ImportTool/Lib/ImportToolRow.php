<?php
App::uses('ImportToolBase', 'ImportTool.Lib');
App::uses('Hash', 'Utility');

class ImportToolRow extends ImportToolBase {
	protected $_ImportToolData = null;
	protected $_rowNumber = null;

	// holds initial row data as array as is in the parsed file
	protected $_rowData = null;

	// holds $_rowData having array keys normalized (matching count()) to be used with array_combine
	protected $_preFormattedData = null;

	// holds formatted data using array_combine with argument field keys and parsed file values
	protected $_formattedData = null;

	protected $_structureErrors = null;
	protected $_missingStructureCells = array();

	// holds boolean returned with Model::validates() method 
	protected $_validatesWithModel = null;

	// holds array of field validation errors from Model::$validationErrors
	protected $_validationErrors = null;

	public function __construct(ImportToolData &$ImportToolData, $rowNumber, $rowData = array()) {
		$this->_ImportToolData = $ImportToolData;
		parent::__construct($this->getDataObject()->_getModelName());


		$this->_rowNumber = $rowNumber;
		$this->_rowData = $rowData;
		$this->_preFormattedData = $rowData;
		$this->_rowDataCount = count($rowData);

		$this->_validateStructure();
		$this->_buildData();
		$this->_validateData();
	}

	protected function _getArgumentFields() {
		$args = $this->getDataObject()->getArguments();
		
		$argFields = array();
		foreach ($args as $arg) {
			$argFields[] = $arg->getField();
		}

		return $argFields;
	}

	/**
	 * Validates only structure comparing with DataObject $args.
	 */
	protected function _validateStructure() {
		$argsCount = $this->getDataObject()->getArgumentsCount();

		if ($this->_rowDataCount < $argsCount) {
			$this->_structureErrors[] = __('Row appears to be incomplete');

			for ($i=0; $i < $argsCount; $i++) { 
				if (!isset($this->_rowData[$i])) {
					$this->_missingStructureCells[] = $i;
					$this->_preFormattedData[$i] = null;
				}
			}
		}

		if ($this->_rowDataCount > $argsCount) {
			// $this->_structureErrors[] = __('');
			$this->_preFormattedData = array_slice($this->_preFormattedData, 0, $argsCount);
		}
	}

	protected function _buildData() {
		$argFields = $this->_getArgumentFields();
		$this->_formattedData = array_combine($argFields, $this->_preFormattedData);

		// purify it
		$this->_formattedData = array_map([$this, 'cleanField'], $this->_formattedData);
	}

	// callback for purifier data cleaner after data is build
	public function cleanField($value) {
		return $this->_getModel()->purifyHtml($value, 'Strict');
	}

	protected function _validateData() {
		$data = $this->getImportableDataArray();
		
		$model = $this->_getModel();
		$model->create();
		$model->set($data);

		$this->_validatesWithModel = $model->validates();
		$this->_validationErrors = $model->validationErrors;
	}

	public function getImportableDataArray() {
		$item = $this->getData();

		$importableArr = array();
		foreach ($this->getDataObject()->getArguments() as $arg) {
			$_item = [
				$arg->getField() => $arg->convertToImport($item[$arg->getField()])
			];
			$_item = Hash::expand($_item);
			
			$importableArr = Hash::merge($importableArr, $_item);

			// old version before update to data convention
			// $item[$arg->getField()] = $arg->convertToImport($item[$arg->getField()]);
		}
		
		return $importableArr;
	}

	/**
	 * Current row's data and structure is valid.
	 * 
	 * @return boolean True to allow database import for this row.
	 */
	public function isImportable() {
		return $this->_validatesWithModel && $this->isStructureValid();
	}

	public function isDataValid() {
		return $this->_validatesWithModel;
	}

	public function getStructureErrors() {
		return $this->_structureErrors;
	}

	public function getMissingStructureCells() {
		return $this->_missingStructureCells;
	}

	public function getValidationErrors($field = null) {
		if (!empty($field)) {
			if ($field instanceof ImportToolArgument) {
				$associationName = $field->getAssocation();
				$field = $field->getField();

				// for other than hasMany associations, we remove model name from the front of the field to match convention
				if ($associationName !== 'hasMany') {
					$exploded = explode('.', $field);
					$field = $exploded[count($exploded)-1];
				}
			}

			return !empty($this->_validationErrors[$field]) ? $this->_validationErrors[$field] : false;
		}

		return $this->_validationErrors;
	}

	public function isStructureValid() {
		return empty($this->_structureErrors);
	}

	/**
	 * Get array of values or a single value from current row of data.
	 * 
	 * @param  mixed  $arg   Null to retrieve the whole deal of data. Instance of ImportToolArgument or string to retrieve a single value.
	 * @return mixed         Array of data or a single value.
	 */
	public function getData($arg = null) {
		if (!empty($arg)) {
			if ($arg instanceof ImportToolArgument) {
				$arg = $arg->getField();
			}

			return $this->_formattedData[$arg];
		}

		return $this->_formattedData;
	}

	/**
	 * Get ImportToolData class.
	 * 
	 * @return ImportToolData
	 */
	public function getDataObject() {
		return $this->_ImportToolData;
	}
}
