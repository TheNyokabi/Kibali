<?php
App::uses('ClassRegistry', 'Utility');
App::uses('Audit', 'ObjectVersion.Model');
App::uses('ObjectVersionAudit', 'ObjectVersion.Lib');
App::uses('FieldDataEntity', 'FieldData.Model/FieldData');

class ObjectVersionRestore {
	protected $_auditId;
	protected $_model;
	protected $_foreignKey;
	protected $_jsonObject;
	protected $_data;
	protected $_valid;
	protected $_hasChanges;

	/**
	 * Lets setup the restore class for a certain revision.
	 * 
	 * @param string $auditId  Audit ID.
	 */
	public function __construct($auditId) {
		$this->_auditId = $auditId;
		$Audit = ClassRegistry::init('ObjectVersion.Audit');

		$audit = $Audit->find('first', array(
			'conditions' => array(
				'Audit.id' => $auditId
			),
			'recursive' => -1
		));

		$this->_model = $audit['Audit']['model'];
		$this->_foreignKey = $audit['Audit']['entity_id'];
		$this->_jsonObject = $audit['Audit']['json_object'];
		$this->_data = json_decode($this->_jsonObject, true);

		$this->_buildData();
	}

	/**
	 * We prepare the data for restoring a revision.
	 */
	protected function _buildData() {
		$_m = _getModelInstance($this->_model);

		$ignore = $_m->getAuditIgnoredFields();
		foreach ($this->_data[$this->_model] as $propertyName => $value) {
			if (in_array($propertyName, $ignore)) {
				continue;
			}

			$FieldDataEntity = $_m->getFieldDataEntity($propertyName);

			if (!$FieldDataEntity instanceof FieldDataEntity) {
				continue;
			}

			if ($FieldDataEntity->isHabtm()) {
				$this->_data[$this->_model][$propertyName] = FieldDataEntity::parseHabtmData($value);
			}
		}

		// lets unset the primary key to not cause conflict when saving the data
		// unset($this->_data[$this->_model][$_m->primaryKey]);

		return $this;
	}

	/**
	 * Validation was successfull before the actual save.
	 * 
	 * @return boolean True on success, False otherwise.
	 */
	public function isRestoredDataValid() {
		return $this->_valid;
	}

	/**
	 * Was there one or more value changes made to the object during this save.
	 * 
	 * @return boolean True if there was at least one change of value, False otherwise.
	 */
	public function hasChanges() {
		return $this->_hasChanges;
	}

	/**
	 * Do the restore of the object version.
	 * 
	 * @return bool True on success or false on fail.
	 */
	public function restore() {
		$_m = _getModelInstance($this->_model);

		// $_m->undelete($_m, $this->_foreignKey);
		
		if ($_m->Behaviors->loaded('SoftDelete')) {
			$_m->softDelete(false);
		}
		
		$_m->id = $this->_foreignKey;

		$_m->objectRestored = $this->_auditId;

		$_m->set($this->_data);

		$toggle = ['FileValidation', 'Attachment'];
		$intersect = array_intersect($_m->Behaviors->enabled(), $toggle);

		if (!empty($intersect)) {
			$_m->Behaviors->disable($toggle);

			$ret = $this->_doSave($_m);

			$_m->Behaviors->enable($toggle);
		}
		else {
			$ret = $this->_doSave($_m);
		}

		$this->_hasChanges = $_m->objectVersionHasChanges;

		unset($_m->objectRestored);

		if ($_m->Behaviors->loaded('SoftDelete')) {
			$_m->softDelete(true);
		}

		return $ret;
	}

	protected function _doSave(Model $Model) {
		$this->_valid = $Model->validates();
		return $Model->save($this->_data, false);
	}
}
