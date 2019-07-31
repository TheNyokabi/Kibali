<?php
App::uses('SectionBase', 'Model');
App::uses('File', 'Utility');
App::uses('BulkAction', 'BulkActions.Model');

class Queue extends SectionBase {

    public $name = 'Queue';
    public $useTable = 'queue';

    public $mapController = 'queue';

    public $actsAs = array(
        'Search.Searchable',
        'HtmlPurifier.HtmlPurifier' => array(
            'config' => 'Strict',
            'fields' => array()
        ),
        'CustomFields.CustomFields'
    );

    /*
     * static enum: Model::function()
     * @access static
     */
    public static function statuses($value = null) {
        $options = array(
            self::STATUS_CREATED => __('Created'),
            self::STATUS_PENDING => __('Pending'),
            self::STATUS_SUCCESS => __('Success'),
            self::STATUS_FAILED => __('Failed')
        );
        return parent::enum($value, $options);
    }

    const STATUS_CREATED = 3;
    const STATUS_PENDING = 0;
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED = 2;

    public function __construct($id = false, $table = null, $ds = null) {
        $this->label = __('Queue');

        $this->fieldGroupData = array(
            'default' => array(
                'label' => __('General')
            ),
        );

        $this->fieldData = array(
            'queue_id' => array(
                'label' => __('Queue ID'),
                'editable' => false
            ),
            'description' => array(
                'label' => __('Description'),
                'editable' => false
            ),
            'status' => array(
                'label' => __('Status'),
                'options' => array($this, 'getStatuses'),
                'editable' => false
            ),
        );

        $this->advancedFilter = array(
            __('General') => array(
                'id' => array(
                    'type' => 'text',
                    'name' => __('ID'),
                    'filter' => false
                ),
                'queue_id' => array(
                    'type' => 'text',
                    'name' => __('Queue ID'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'like',
                        'field' => array('Queue.queue_id'),
                    )
                ),
                'description' => array(
                    'type' => 'text',
                    'name' => __('Description'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'like',
                        'field' => array('Queue.description'),
                    )
                ),
                'status' => array(
                    'type' => 'select',
                    'name' => __('Status'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'value',
                    ),
                    'data' => array(
                        'method' => 'getStatuses',
                        'empty' => __('All'),
                        'result_key' => true,
                    ),
                ),
                'created' => array(
                    'type' => 'date',
                    'comparison' => true,
                    'name' => __('Date'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'query',
                        'method' => 'setComparisonType',
                        'field' => 'Queue.created'
                    ),
                ),
            ),
        );

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Queue'),
            'pdf_file_name' => __('queue'),
            'csv_file_name' => __('queue'),
            'max_selection_size' => 10,
            'actions' => false,
            'url' => array(
                'controller' => 'queue',
                'action' => 'index',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'reset' => array(
                'controller' => 'queue',
                'action' => 'index',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'bulk_actions' => array(
                BulkAction::TYPE_DELETE,
            ),
            'include_timestamps' => false,
        );

        parent::__construct($id, $table, $ds);
    }

    public function beforeDelete($cascade = true) {
        $queueItem = $this->find('first', array(
            'conditions' => array(
                'Queue.id' => $this->id
            ),
            'recursive' => -1
        ));

        if (!empty($queueItem)) {
            $this->_deleteItemData($queueItem['Queue']['queue_id'], $queueItem['Queue']['id']);
        }
        
        return true;
    }

    public function getStatuses() {
        return self::statuses();
    }

    /**
     * Insert email to queue.
     * 
     * @param ErambaCakeEmail $data
     * @return mixed false on fail | Queue.id on success
     */
    public function add($data, $queueId = null) {
        // $queueId = $data->getQueueId();

        // $item = $this->find('count', array(
        //     'conditions' => array(
        //         'queue_id' => $queueId,
        //         'data' => $serializedData,
        //     )
        // ));

        // if (!empty($item)) {
        //     $trace = Debugger::trace(array('start' => 1, 'format' => 'log'));
        //     CakeLog::write('error', 'Duplicit email in queue with queue_id ' . $data->getQueueId() . "\n" . 'Stack Trace:' .  "\n" . $trace);

        //     return true;
        // }

        $recipients = (is_array($data->to())) ? implode(', ', $data->to()) : $data->to();

        $this->create(array(
            'queue_id' => $queueId,
            'status' => self::STATUS_CREATED,
            'description' => __('Email to %s', $recipients)
        ));
        $result = $this->save();

        if ($result) {
            $result &= $this->_writeItemData($data, $this->id);
            $result &= $this->markAsPending();
        }

        return ($result) ? $this->id : false;
    }

    /**
     * returns all pending queue items
     *
     * @param int $limit
     * @param array $conditions Additional conditions on id, queue_id.
     * @return array
     */
    public function getPending($limit, $conditions = []) {
        $defaultConditions = [
            'Queue.status' => self::STATUS_PENDING
        ];

        $conditions = array_merge($defaultConditions, $conditions);

        return $this->find('all', [
            'conditions' => $conditions,
            'limit' => $limit,
            'order' => array('Queue.created' => 'ASC')
        ]);
    }

    /**
     * changes status of queue item to success
     * 
     * @param  array $item queue item
     * @return mixed false on fail | array on success
     */
    public function markAsSuccess($item) {
        $this->create($item);
        $result = $this->save([
            'status' => self::STATUS_SUCCESS,
            'data' => null // we purge the serialized class that is not needed anymore
        ]);

        if ($result) {
            $this->_deleteItemData($item['Queue']['queue_id'], $item['Queue']['id']);
        }

        return $result;
    }

    /**
     * changes status of queue item to failed
     * 
     * @param  array $item queue item
     * @return mixed false on fail | array on success
     */
    public function markAsFailed($item) {
        $this->create($item);
        return $this->save([
            'status' => self::STATUS_FAILED
        ]);
    }

    /**
     * changes status of queue item to pending
     * 
     * @param  array $item queue item
     * @return mixed false on fail | array on success
     */
    public function markAsPending($item = null) {
        if ($item !== null) {
            $this->create($item);
        }
        
        return $this->save([
            'status' => self::STATUS_PENDING
        ]);
    }

    /**
     * writes emails serialized data to vendors folder
     * 
     * @param  array $data emails data
     * @param  int $queueItemId
     * @return boolean
     */
    protected function _writeItemData($data, $queueItemId) {
        $serializedData = serialize($data);
        $fileName = self::getFileName($data->queueId(), $queueItemId);

        $file = new File(self::getDataPath() . $fileName, true);
        if (!$file->writable()) {
            return false;
        }

        $result = $file->write($serializedData);
        $file->close();

        return $result;
    }

    /**
     * deletes item data from vendor folder
     * 
     * @param  string|int $queueId 
     * @param  string|int $queueItemId 
     * @return boolean
     */
    protected function _deleteItemData($queueId, $queueItemId) {
        $fileName = self::getFileName($queueId, $queueItemId);

        $file = new File(self::getDataPath() . $fileName);

        if (!$file->exists()) {
            return true;
        }

        return $file->delete();
    }

    /**
     * returns unserialized data for input queueId and queueItemId
     * 
     * @param  string|int $queueId 
     * @param  string|int $queueItemId 
     * @return ErambaCakeEmail data | false on failure
     */
    public static function getItemData($queueId, $queueItemId) {
        $fileName = self::getFileName($queueId, $queueItemId);

        $file = new File(self::getDataPath() . $fileName);

        if (!$file->exists()) {
            return false;
        }

        $content = $file->read();

        $data = unserialize($content);

        return $data;
    }

    /**
     * queue email data path
     * 
     * @return String
     */
    public static function getDataPath() {
        return APP . 'Vendor' . DS . 'queue' . DS;
    }

    /**
     * returns item file name for input queueId and queueItemId
     *
     * @param  string|int $queueId 
     * @param  string|int $queueItemId 
     * @return String
     */
    public static function getFileName($queueId, $queueItemId) {
        return sprintf('%s_%s', $queueId, $queueItemId) . '.txt';
    }
}

