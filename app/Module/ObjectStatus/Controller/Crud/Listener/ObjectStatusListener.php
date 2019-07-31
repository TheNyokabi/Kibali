<?php
App::uses('CrudListener', 'Crud.Controller/Crud');

/**
 * Search Listener
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class ObjectStatusListener extends CrudListener {

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
            'Crud.afterSave' => ['callable' => 'afterSave', 'priority' => 50],
            'Crud.afterDelete' => ['callable' => 'afterDelete', 'priority' => 50],
        ];
    }

    public function beforeHandle(CakeEvent $event) {
        $model = $this->_model();
        $this->_ensureBehavior($model);
    }

    protected function _ensureBehavior(Model $model) {
        if ($model->Behaviors->loaded('ObjectStatus')) {
            return;
        }

        $model->Behaviors->load('ObjectStatus.ObjectStatus');
        $model->Behaviors->ObjectStatus->setup($model);
    }

    public function afterSave(CakeEvent $event) {
        if (!empty($event->subject->success)) {
            $model = $this->_model();
            $model->Behaviors->ObjectStatus->triggerObjectStatus($model);
        }
    }

    public function afterDelete(CakeEvent $event) {
        if (!empty($event->subject->success)) {
            $model = $this->_model();
            $model->Behaviors->ObjectStatus->deleteObjectStatus($model, null, $event->subject->id);
        }
    }
}
