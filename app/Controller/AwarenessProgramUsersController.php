<?php
class AwarenessProgramUsersController extends AppController {
    public $helpers = array('Html', 'Form');
    public $components = array('Session', 'Search.Prg', 'Paginator', 'AdvancedFilters');

    public function beforeFilter() {
        parent::beforeFilter();
    }

    public function index($model = null) {
        if (empty($model)) {
            throw new NotFoundException();
        }
        
        $model = 'AwarenessProgram' . $model;
        $this->set('title_for_layout', getModelLabel($model));

        $this->loadModel($model);
        
        Configure::write('Search.Prg.presetForm', array('model' => $model));
        $this->AdvancedFilters = $this->Components->load('AdvancedFilters');
        $this->AdvancedFilters->initialize($this);

        if ($this->AdvancedFilters->filter($model)) {
            return;
        }
    }
}