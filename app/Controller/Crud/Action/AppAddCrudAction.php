<?php
App::uses('AddCrudAction', 'Crud.Controller/Crud/Action');
App::uses('CrudActionTrait', 'Controller/Crud/Trait');

/**
 * Handles 'Add' Crud actions
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class AppAddCrudAction extends AddCrudAction {

    use CrudActionTrait;

/**
 * Startup method
 *
 * Called when the action is loaded
 *
 * @param CrudSubject $subject
 * @param array $defaults
 * @return void
 */
    public function __construct(CrudSubject $subject, array $defaults = array()) {
        $defaults = am([
            'messages' => [
                'success' => [
                    'text' => __('%s was successfully added.', $this->getSectionLabel($subject)),
                    'element' => FLASH_OK
                ],
                'error' => [
                    'text' => __('Error while saving the data. Please try it again.'),
                    'element' => FLASH_ERROR
                ]
            ],
            'saveMethod' => 'save',
        ], $defaults);

        parent::__construct($subject, $defaults);
    }

/**
 * HTTP GET handler
 *
 * @return void
 */
    protected function _get() {
        $this->_commonData();

        parent::_get();
    }

/**
 * HTTP POST handler
 *
 * @return void
 */
    protected function _post() {
        $this->_commonData();

        $request = $this->_request();
        $model = $this->_model();

        $this->_trigger('beforeSave');

        $ret = true;

        $ret &= $this->_saveAssociatedHandler();

        // Dont call the save method unless the $ret variable is true
        $ret = $ret && call_user_func(array($model, $this->saveMethod()), $request->data, $this->saveOptions());
        if ($ret) {
            $this->_controller()->Ajax->success();
            $this->setFlash('success');
            $subject = $this->_trigger('afterSave', array('success' => true, 'created' => true, 'id' => $model->id));
        }
        else {
            $this->setFlash('error');
            $subject = $this->_trigger('afterSave', array('success' => false, 'created' => false));
        }

        $request->data = Hash::merge($request->data, $model->data);
        $this->_trigger('beforeRender', $subject);
    }

/**
 * sets common data and executes common processes
 *
 * @param mixed $id
 * @return void
 */
    protected function _commonData() {
        $this->setOptionalData();
    }

}
