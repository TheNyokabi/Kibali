<?php
App::uses('AwarenessProgramUser', 'Model');

class AwarenessProgramCompliantUser extends AwarenessProgramUser {

    public $useTable = 'awareness_program_compliant_users';

    public function __construct($id = false, $table = null, $ds = null) {

        parent::__construct($id, $table, $ds);

        $dvancedFilter = array(
            __('General') => array(
                'reminder' => array(
                    'field' => 'AwarenessProgramCompliantUser.uid',
                ),
                'training' => array(
                    'field' => 'AwarenessProgramCompliantUser.uid',
                )
            )
        );

        $this->mergeAdvancedFilterFields($dvancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Awareness Program Compliant Users'),
            'pdf_file_name' => __('awareness_program_compliant_users'),
            'csv_file_name' => __('awareness_program_compliant_users'),
            'actions' => false,
            'url' => array(
                'controller' => 'awarenessProgramUsers',
                'action' => 'index',
                'CompliantUser',
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

    /*public function beforeFind($query) {
        $this->virtualFields =array(
            'last_compliant_date' => 'AwarenessTraining.created'
        );
        $query['joins'] = array(
            array(
                'table' => 'awareness_users',
                'alias' => 'AwarenessUser',
                'type' => 'LEFT',
                'conditions' => array(
                    'AwarenessUser.login = AwarenessProgramCompliantUser.uid'
                )
            ),
            array(
                'table' => 'awareness_program_recurrences',
                'alias' => 'AwarenessProgramRecurrence',
                'type' => 'LEFT',
                'conditions' => array(
                    'AwarenessProgramRecurrence.awareness_program_id = AwarenessProgramCompliantUser.awareness_program_id'
                )
            ),
            array(
                'table' => 'awareness_trainings',
                'alias' => 'AwarenessTraining',
                'type' => 'LEFT',
                'conditions' => array(
                    'AwarenessTraining.awareness_user_id = AwarenessUser.id',
                    'AwarenessTraining.demo = 0',
                    'AwarenessProgramCompliantUser.awareness_program_id = AwarenessTraining.awareness_program_id',
                    'AwarenessTraining.awareness_program_recurrence_id = AwarenessProgramRecurrence.id'
                )
            )
        );

        $query['group'] = 'AwarenessProgramCompliantUser.id';
        $query['order']['AwarenessProgramRecurrence.start'] = 'DESC';
        $query['order']['AwarenessProgramCompliantUser.last_compliant_date'] = 'DESC';
        $query['order']['AwarenessTraining.created'] = 'DESC';
        // debug($query);
        
        return $query;
    }*/
}