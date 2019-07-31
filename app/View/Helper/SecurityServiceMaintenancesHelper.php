<?php
App::uses('SectionBaseHelper', 'View/Helper');

class SecurityServiceMaintenancesHelper extends SectionBaseHelper {
    public $helpers = array('Html');
    public $settings = array();

    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);

        $this->settings = $settings;
    }

    /**
     * result field output filter
     */
    public function outputResult($data, $options = array()) {
        $statuses = getAuditStatuses();
        $value = '';

        if ($data === null || $data === false) {
            $value = __('Incomplete');
        }
        else {
            $value = $statuses[$data];
        }

        return $value;
    }

}