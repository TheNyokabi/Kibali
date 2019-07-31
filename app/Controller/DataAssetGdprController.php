<?php
App::uses('SectionBaseController', 'Controller');

class DataAssetGdprController extends SectionBaseController {

    public $helpers = [];
    public $components = [
        'Search.Prg', 'Paginator', 'AdvancedFilters',
        // 'Crud.Crud' => [
        //     'actions' => [
        //         'index' => [
        //             'className' => 'Filter',
        //         ]
        //     ]
        // ],
    ];

    public function beforeFilter() {
        // $this->Crud->enable(['index']);

        parent::beforeFilter();

        $this->title = __('Data Asset GDPR');
        $this->subTitle = __('');
    }

    // public function index() {
    //     $response = $this->handleCrudAction('index');

    //     if ($response === false) {
    //         $this->redirect(['controller' => 'dataAssets', 'action' => 'index']);
    //     }
    // }

}
