<?php
class FieldGroupEntity {
	protected $_config = array();

	protected $_defaults = array(
		'label' => null
	);

	protected $_fields = array();

	public function __construct($config) {
		$config = am($this->_defaults, $config);
		$this->_config = $config;
	}

	public function getLabel() {
		return $this->_config['label'];
	}

	/**
	 * Get Group class for the current FieldData entity.
	 * 
	 * @return FieldGroupEntity
	 */
	public function getFields() {
		
	}
}