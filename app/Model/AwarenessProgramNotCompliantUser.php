<?php
App::uses('AwarenessProgramUser', 'Model');

class AwarenessProgramNotCompliantUser extends AwarenessProgramUser {

    public $useTable = 'awareness_program_not_compliant_users';

    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);

        $advancedFilter = array(
            __('General') => array(
                'reminder' => array(
                    'field' => 'AwarenessProgramNotCompliantUser.uid',
                ),
                'training' => array(
                    'field' => 'AwarenessProgramNotCompliantUser.uid',
                )
            )
        );

        $this->mergeAdvancedFilterFields($advancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Awareness Program Not Compliant Users'),
            'pdf_file_name' => __('awareness_program_not_compliant_users'),
            'csv_file_name' => __('awareness_program_not_compliant_users'),
            'actions' => false,
            'url' => array(
                'controller' => 'awarenessProgramUsers',
                'action' => 'index',
                'NotCompliantUser',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'reset' => array(
                'controller' => 'awarenessPrograms',
                'action' => 'index',
            )
        );

        $this->initAdvancedFilter();
    }
}