<?php
App::uses('SectionBaseController', 'Controller');

class AwarenessTrainingsController extends SectionBaseController {

    public $helpers = [];
    public $components = [
        'Search.Prg', 'Paginator', 'AdvancedFilters',
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

        $this->title = __('Awareness Trainings');
        $this->subTitle = __('');
    }

    public function index($model = null) {
        return $this->Crud->execute();
    }
}