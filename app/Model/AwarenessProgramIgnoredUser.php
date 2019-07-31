<?php
App::uses('AwarenessProgramUser', 'Model');

class AwarenessProgramIgnoredUser extends AwarenessProgramUser {

    public $useTable = 'awareness_program_ignored_users';

    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);

        $dvancedFilter = array(
            __('General') => array(
                'reminder' => array(
                    'field' => 'AwarenessProgramIgnoredUser.uid',
                ),
                'training' => array(
                    'field' => 'AwarenessProgramIgnoredUser.uid',
                )
            )
        );

        $this->mergeAdvancedFilterFields($dvancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Awareness Program Ignored Users'),
            'pdf_file_name' => __('awareness_program_ignored_users'),
            'csv_file_name' => __('awareness_program_ignored_users'),
            'actions' => false,
            'url' => array(
                'controller' => 'awarenessProgramUsers',
                'action' => 'index',
                'IgnoredUser',
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