<?php
App::uses('Issue', 'Model');
class SecurityServiceIssue extends Issue {
	public $useTable = 'issues';
	protected $issueParentModel = 'SecurityService';

	public function __construct($id = false, $table = null, $ds = null) {
		$this->mapping = $this->getMapping();
		$this->mapping['indexController'] = 'issues';

		parent::__construct($id, $table, $ds);
	}
}
