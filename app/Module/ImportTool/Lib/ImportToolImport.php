<?php
App::uses('ImportToolBase', 'ImportTool.Lib');

class ImportToolImport extends ImportToolBase {
	protected $ImportToolData = null;
	protected $_importRows = array();
	protected $_importSpecificRows = false;

	public function __construct(ImportToolData $ImportToolData) {
		$this->ImportToolData = $ImportToolData;
		parent::__construct($this->ImportToolData->_getModelName());
	}

	/**
	 * Set variable for importing just specific rows.
	 * 
	 * @param array $rows
	 */
	public function setImportRows($rows = array()) {
		if (!empty($rows)) {
			$this->_importRows = $rows;
			$this->_importSpecificRows = true;
		}
	}

	public function saveData($userId, $additionalItemData = []) {
		$data = $this->ImportToolData->getImportableDataArray();
		
		$ret = true;
		foreach ($data as $row => $item) {
			if (!$this->_importSpecificRows || ($this->_importSpecificRows && in_array($row, $this->_importRows))) {
				$item['workflow_owner_id'] = $userId;
				$item['workflow_status'] = WORKFLOW_APPROVED;

				$item = Hash::merge($item, $additionalItemData);

				$this->_getModel()->create();
				$ret &= $this->_getModel()->saveAssociated($item, array(
					'autocommit' => true,
					'validate' => false
					// 'fieldList' => ...
				));

				//if there is ObjecStatus trigger calculations
				if ($this->_getModel()->Behaviors->enabled('ObjectStatus')) {
					$this->_getModel()->triggerObjectStatus();
				}
			}
		}

		return $ret;
	}

}
