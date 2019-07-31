<?php
App::uses('CrudListener', 'Crud.Controller/Crud');

/**
 * Search Listener
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class CustomValidatorListener extends CrudListener {

/**
 * Default configuration
 *
 * @var array
 */
    protected $_settings = [];

/**
 * Returns a list of all events that will fire in the controller during its lifecycle.
 * You can override this function to add you own listener callbacks
 *
 * We attach at priority 50 so normal bound events can run before us
 *
 * @return array
 */
    public function implementedEvents() {
        return [
            'Crud.beforeHandle' => ['callable' => 'beforeHandle', 'priority' => 50],
            'Crud.beforeSave' => ['callable' => 'beforeSave', 'priority' => 50],
        ];
    }

    public function beforeHandle(CakeEvent $event) {
        $model = $this->_model();
        $this->_ensureBehavior($model);
    }

    protected function _ensureBehavior(Model $model) {
        if ($model->Behaviors->loaded('CustomValidator')) {
            return;
        }

        $model->Behaviors->load('CustomValidator.CustomValidator');
        $model->Behaviors->CustomValidator->setup($model);
    }

    public function beforeSave(CakeEvent $event) {
        $model = $this->_model();
        $model->Behaviors->CustomValidator->setCustomValidator($model, $event->subject->request->data);
    }
}
