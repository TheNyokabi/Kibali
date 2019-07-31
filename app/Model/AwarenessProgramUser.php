<?php
class AwarenessProgramUser extends AppModel {

    public $useTable = false;

    public $belongsTo = array(
        'AwarenessProgram'
    );

    public $actsAs = array(
        'Containable',
        'Search.Searchable',
        'HtmlPurifier.HtmlPurifier' => array(
            'config' => 'Strict',
            'fields' => array()
        ),
    );

    public function __construct($id = false, $table = null, $ds = null) {
        $this->label = __('Awareness Program Users');

        $this->advancedFilter = array(
            __('General') => array(
                'id' => array(
                    'type' => 'text',
                    'name' => __('ID'),
                    'filter' => false
                ),
                'awareness_program_id' => array(
                    'type' => 'multiple_select',
                    'name' => __('Awareness Program'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'value',
                    ),
                    'data' => array(
                        'method' => 'getAwarenessPrograms',
                    ),
                    'contain' => array(
                        'AwarenessProgram' => array(
                            'title'
                        )
                    ),
                ),
                'uid' => array(
                    'type' => 'text',
                    'name' => __('User'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'like',
                    ),
                ),
                'reminder' => array(
                    'type' => 'text',
                    'name' => __('Reminders'),
                    'show_default' => true,
                    'filter' => false,
                    'outputFilter' => array('AwarenessProgramUsers', 'outputReminders')
                ),
                'training' => array(
                    'type' => 'text',
                    'name' => __('Trainings'),
                    'show_default' => true,
                    'filter' => false,
                    'outputFilter' => array('AwarenessProgramUsers', 'outputTrainings')
                ),
            ),
        );

        parent::__construct($id, $table, $ds);
    }

    public function getAwarenessPrograms() {
        $data = $this->AwarenessProgram->find('list', array(
            'fields' => array('AwarenessProgram.id', 'AwarenessProgram.title'),
        ));
        return $data;
    }
}