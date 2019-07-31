<?php
App::uses('AppHelper', 'View/Helper');

class CrudHelper extends AppHelper {
    public $helpers = array('Html');
    public $settings = array();
    
    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->settings = $settings;
    }

    
}