<?php
class ComplianceAnalysisFinding_Expiration extends ComplianceAnalysisFinding_Expiration_Base {
	protected $reminderDays = -10;

	public function __construct($options = array()) {
		parent::__construct($options);
	}
}
