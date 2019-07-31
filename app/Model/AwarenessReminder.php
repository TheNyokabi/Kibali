<?php
App::uses('SectionBase', 'Model');

class AwarenessReminder extends SectionBase {
    public $displayField = 'uid';

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
        $this->label = __('Awareness Reminders');

        $this->fieldGroupData = [
            'default' => [
                'label' => __('General')
            ],
        ];

        $this->fieldData = [
            'uid' => [
                'label' => __('Uid'),
                'editable' => false,
            ],
            'email' => [
                'label' => __('Email'),
                'editable' => false,
            ],
            'awareness_program_id' => [
                'label' => __('Awareness Program'),
                'editable' => false,
            ],
            'demo' => [
                'label' => __('Demo'),
                'editable' => false,
            ],
            'reminder_type' => [
                'label' => __('Reminder Type'),
                'editable' => false,
                'options' => [$this, 'reminderTypes']
            ],
        ];

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
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'AwarenessReminder.awareness_program_id',
                        'field' => 'AwarenessReminder.id',
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
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'AwarenessReminder.uid',
                        'field' => 'AwarenessReminder.id',
                    ),
                ),
                'demo' => array(
                    'type' => 'select',
                    'name' => __('Demo'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'AwarenessReminder.demo',
                        'field' => 'AwarenessReminder.id',
                    ),
                    'data' => array(
                        'method' => 'getStatusFilterOption',
                        'empty' => __('All'),
                        'result_key' => true
                    ),
                ),
                'reminder_type' => array(
                    'type' => 'select',
                    'name' => __('Type'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'AwarenessReminder.reminder_type',
                        'field' => 'AwarenessReminder.id',
                    ),
                     'data' => array(
                        'method' => 'getReminderTypes',
                        'empty' => __('All'),
                        'result_key' => true
                    ),
                ),
                'created' => array(
                    'type' => 'date',
                    'name' => __('Date'),
                    'comparison' => true,
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => 'AwarenessReminder.created',
                        'field' => 'AwarenessReminder.id',
                    ),
                ),
            ),
        );

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Awarness Reminders'),
            'pdf_file_name' => __('awareness_reminders'),
            'csv_file_name' => __('awareness_reminders'),
            'actions' => false,
            'reset' => array(
                'controller' => 'awarenessPrograms',
                'action' => 'index',
            ),
            'use_new_filters' => true,
            'include_timestamps' => false,
        );

        parent::__construct($id, $table, $ds);
    }

    /*
	 * static enum: Model::function()
	 * @access static
	 */
	 public static function reminderTypes($value = null) {
		$options = array(
			self::REMINDER_DEFAULT => __('Default'),
			self::REMINDER_INVITATION => __('Invitation'),
			self::REMINDER_REMINDER => __('Reminder')
		);
		return parent::enum($value, $options);
	}
    // default is not used anymore, its here only for backwards compatibility
	const REMINDER_DEFAULT = 0;
	const REMINDER_INVITATION = 1;
	const REMINDER_REMINDER = 2;

    public function getReminderTypes() {
        return self::reminderTypes();
    }

    public function getAwarenessPrograms() {
        $data = $this->AwarenessProgram->find('list', array(
            'fields' => array('AwarenessProgram.id', 'AwarenessProgram.title'),
        ));
        return $data;
    }
}