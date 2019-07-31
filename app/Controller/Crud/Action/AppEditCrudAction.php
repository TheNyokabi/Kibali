<?php
App::uses('EditCrudAction', 'Crud.Controller/Crud/Action');
App::uses('CrudActionTrait', 'Controller/Crud/Trait');

/**
 * Handles 'Add' Crud actions
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class AppEditCrudAction extends EditCrudAction {

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
                    // getSectionLabel in trait
                    'text' => __('%s was successfully updated.', $this->getSectionLabel($subject)),
                    'element' => FLASH_OK
                ],
                'error' => [
                    'text' => __('Error while saving the data. Please try it again.'),
                    'element' => FLASH_ERROR
                ],
            ],
            'view' => 'add',
            'saveMethod' => 'save',
        ], $defaults);

        parent::__construct($subject, $defaults);
    }

/**
 * HTTP GET handler
 *
 * @throws NotFoundException If record not found
 * @param string $id
 * @return void
 */
    protected function _get($id = null) {
        $this->_commonData($id);

        parent::_get($id);
    }

/**
 * HTTP PUT handler
 *
 * @param mixed $id
 * @return void
 */
    protected function _put($id = null) {
        $this->_commonData($id);

        if (!$this->_validateId($id)) {
            return false;
        }

        $request = $this->_request();
        $model = $this->_model();

        $existing = $this->_findRecord($id, 'count');
        if (empty($existing)) {
            return $this->_notFound($id);
        }

        $request->data = $this->_injectPrimaryKey($request->data, $id, $model);

        $this->_trigger('beforeSave', compact('id'));

        $ret = true;

        $ret &= $this->_saveAssociatedHandler();
        
        // Dont call the save method unless the $ret variable is true
        $ret = $ret && call_user_func(array($model, $this->saveMethod()), $request->data, $this->saveOptions());
        if ($ret) {
            $this->_controller()->Ajax->success();
            $this->setFlash('success');
            $subject = $this->_trigger('afterSave', array('id' => $id, 'success' => true, 'created' => false));
        }
        else {
            $this->setFlash('error');
            $subject = $this->_trigger('afterSave', array('id' => $id, 'success' => false, 'created' => false));
        }
        
        $this->_trigger('beforeRender', $subject);
    }

/**
 * sets common data and executes common processes
 *
 * @param mixed $id
 * @return void
 */
    protected function _commonData($id = null) {
        $this->_controller()->set('edit', true);
        $this->_controller()->Ajax->processEdit($id, $this->_model()->alias);
        $this->setOptionalData();
    }
    
}
