<?php

App::uses('AppController', 'Controller');

class CustomFieldsAppController extends AppController {
    
    public function cancelAction($model, $foreignKey = null) {
        return parent::cancelAction($model, $foreignKey);
    }
}
