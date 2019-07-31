<?php
App::uses('AppModel', 'Model');
App::uses('CakeTime', 'Utility');
App::uses('Hash', 'Inflector');

abstract class SectionBase extends AppModel {
	public $label = null;

    public  $filterArgs = [];

	public $actsAs = [
		// 'HtmlPurifier.HtmlPurifier',
		'Containable',
        'EventManager.EventManager',
		// optional with advanced filters
		// 'Search.Searchable',
		// 'AuditLog.Auditable',
		// we cant force trash, optional
		// 'Utils.SoftDelete'
	];

	public $mapping = [
		// 'titleColumn' => 'title',
		'logRecords' => true,
		'notificationSystem' => ['index']
	];

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);
	}

	//@todo
	public function parentNode() {
		return 'visualisation/objects';
		// return [$this->alias => [$this->primaryKey => null]];

		// other possible solution is
		// return [$this->alias => [$this->primaryKey => $this->id]];
	}

    public function parentNodeId() {
    	if (!$this instanceof InheritanceInterface) {
    		return false;
    	}

    	$parent = $this->parentNode();
    	if ($parent !== null) {
	    	return $parent[$this->parentModel()][$this->{$this->parentModel()}->primaryKey];
	    }

	    return false;
    }

     /**
     * Validate date in the future.
     */
    public function validateFutureDate($check) {
        $value = array_values($check);
        $date = $value[0];

        $today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
        if ($date > $today) {
            return true;
        }

        return false;
    }

     /**
     * Validate date in the past.
     */
    public function validatePastDate($check) {
        $value = array_values($check);
        $date = $value[0];

        $today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
        if ($date <= $today) {
            return true;
        }

        return false;
    }

    /**
     * default list finder
     * 
     * @return array
     */
    public function getList() {
        $data = $this->find('list', [
            'order' => [
                $this->alias . '.' . $this->displayField => 'ASC'
            ]
        ]);

        return $data;
    }

    /**
     * Validation for special fields with empty checkbox.
     */
    public function checkEmptyCheckbox($check, $emptyField) {
        reset($check);
        $mainField = key($check);

        $result = true;

        if (empty($this->data[$this->alias][$mainField]) && empty($this->data[$this->alias][$emptyField]) 
        ) {
            $result = false;
        }

        return $result;
    }

    const SYSTEM_LOG_COMMENT = 991;
    const SYSTEM_LOG_ATTACHMENT = 992;
    const SYSTEM_LOG_ATTACHMENT_DELETE = 993;
    const SYSTEM_LOG_WIDGET_VIEW = 994;

    public function getSystemLogsConfig() {
        return [
            'logs' => [
                self::SYSTEM_LOG_COMMENT => [
                    'action' => self::SYSTEM_LOG_COMMENT,
                    'label' => __('Comment add'),
                    'message' => __('Comment "%s" added to question "%s".')
                ],
                self::SYSTEM_LOG_ATTACHMENT => [
                    'action' => self::SYSTEM_LOG_ATTACHMENT,
                    'label' => __('Attachment add'),
                    'message' => __('Attachment "%s" added to question "%s".')
                ],
                self::SYSTEM_LOG_ATTACHMENT_DELETE => [
                    'action' => self::SYSTEM_LOG_ATTACHMENT_DELETE,
                    'label' => __('Attachment delete'),
                    'message' => __('Attachment "%s" deleted from question "%s".')
                ],
                self::SYSTEM_LOG_WIDGET_VIEW => [
                    'action' => self::SYSTEM_LOG_WIDGET_VIEW,
                    'label' => __('Widget show'),
                    'message' => __('Widget show.')
                ],
            ],
        ];
    }
}
