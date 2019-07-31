<?php
App::uses('AwarenessProgramUser', 'Model');

class AwarenessProgramActiveUser extends AwarenessProgramUser {

    public $useTable = 'awareness_program_active_users';

    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);

        $dvancedFilter = array(
            __('General') => array(
                'reminder' => array(
                    'field' => 'AwarenessProgramActiveUser.uid',
                ),
                'training' => array(
                    'field' => 'AwarenessProgramActiveUser.uid',
                )
            )
        );

        $this->mergeAdvancedFilterFields($dvancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Awareness Program Active Users'),
            'pdf_file_name' => __('awareness_program_active_users'),
            'csv_file_name' => __('awareness_program_active_users'),
            'actions' => false,
            'url' => array(
                'controller' => 'awarenessProgramUsers',
                'action' => 'index',
                'ActiveUser',
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