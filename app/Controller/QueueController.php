<?php
App::uses('ErambaCakeEmail', 'Network/Email');
App::uses('SectionBaseController', 'Controller');

class QueueController extends SectionBaseController {

    public $helpers = [
        'Html',
        'Form',
        'Paginator'
    ];

    public $components = [
        'Session', 'Paginator', 'Search.Prg', 'AdvancedFilters',
        'CustomFields.CustomFieldsMgt' => [
            'model' => 'Queue'
        ],
        'Crud.Crud' => [
            'actions' => [
                'index' => [
                    'className' => 'Filter'
                ]
            ]
        ],
    ];

    public function beforeFilter() {
        $this->Crud->enable(['index']);

        parent::beforeFilter();

        $this->title = __('Queue');
        $this->subTitle = __('This is the list of emails queued on the system, every time cron (daily or hourly) runs up to 15 emails are sent. Make sure cron and email settings are correctly configured.');
    }

    public function index() {
        if (empty($this->request->query['advanced_filter'])) {
            return $this->redirect(['action' => 'index', '?' => ['advanced_filter' => '1']]);
        }

        $this->Paginator->settings['order'] = array('Queue.created' => 'DESC');

        return $this->Crud->execute();
    }

    public function delete() {
        
    }

    // public function flush() {
    //     $ret = ErambaCakeEmail::sendQueue();

    //     if ($ret) {
    //         $this->Flash->success(__('Queue has been flushed successfully.'));
    //     }
    //     else {
    //         $this->Flash->error(__('There has been an error while trying to flush queued emails, please try again.'));
    //     }

    //     return $this->redirect(['controller' => 'queue', 'action' => 'index', '?' => ['advanced_filter' => 1]]);
    // }

}