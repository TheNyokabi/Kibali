<?php
App::uses('ErambaHelper', 'View/Helper');
class UsersHelper extends ErambaHelper {
	public $settings = array();
	public $helpers = ['Ux', 'Html', 'Text', 'AdvancedFilters'];
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatuses($user) {
		$statuses = array();

		if ($user['User']['blocked']) {
			$statuses[] = $this->getLabel(__('Brute Force Blocked'), 'danger');
		}

		if ($user['User']['status'] == USER_NOTACTIVE) {
			$statuses[] = $this->getLabel(__('Disabled'), 'danger');
		}

		return $this->processStatuses($statuses);
	}

	/**
     * Get list of full_names from data array.
     * 
     * @return array List of names.
     */
    public function getNameList($data, $modelAlias = 'User') {
        $list = array();
        foreach ($data[$modelAlias] as $item) {
            $list[] = $item['full_name'];
        }

        return $list;
    }

    public function listNames($data, $modelAlias = 'User') {
    	$list = $this->getNameList($data, $modelAlias);

    	return $this->Ux->commonListOutput($list);
    }

    /**
     * Reusable alert box that explains the requirements for the succesful password validation.
     * 
     * @return string Alert box.
     */
    public function passwordPolicyAlert() {
    	return $this->Ux->getAlert(__('The password must be 8-30 alphanumeric characters long and optionally, include the following: "!@#$%^&()][" characters'));
    }

    public function listWithFilterLinks($users) {
        $list = [];
        foreach ($users as $item) {
            $list[] = $this->AdvancedFilters->getItemFilteredLink($item['full_name'], 'User', $item['id']);
        }
        return implode(', ', $list);
    }

}