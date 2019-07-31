<?php
App::uses('DeleteCrudAction', 'Crud.Controller/Crud/Action');
App::uses('CrudActionTrait', 'Controller/Crud/Trait');

/**
 * Handles 'Delete' Crud actions
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class AppDeleteCrudAction extends DeleteCrudAction {

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
                    'text' => __('%s was successfully deleted.', $this->getSectionLabel($subject)),
                    'element' => FLASH_OK
                ],
                'error' => [
                    'text' => __('Error while deleting the data. Please try it again.'),
                    'element' => FLASH_ERROR
                ]
            ],
            'view' => '/Elements/section/delete'
        ], $defaults);

        parent::__construct($subject, $defaults);
    }

/**
 * HTTP DELETE handler
 *
 * @throws NotFoundException If record not found
 * @param string $id
 * @return void
 */
    protected function _delete($id = null) {
        if (!$this->_validateId($id)) {
            return false;
        }

        $request = $this->_request();
        $model = $this->_model();

        $data = $this->_findRecord($id, 'first');
        if (empty($data)) {
            return $this->_notFound($id);
        }

        $this->_setData($data);

        $subject = $this->_trigger('beforeDelete', compact('id'));
        if ($subject->stopped) {
            $this->setFlash('error');
            return;
        }

        if ($model->delete($id)) {
            $this->_controller()->Ajax->success();
            $this->setFlash('success');
            $subject = $this->_trigger('afterDelete', array('id' => $id, 'success' => true));
        } else {
            $this->setFlash('error');
            $subject = $this->_trigger('afterDelete', array('id' => $id, 'success' => false));
        }

        $this->_trigger('beforeRender', $subject);
    }

    protected function _notFound($id) {
        $this->_trigger('recordNotFound', compact('id'));

        $message = $this->message('recordNotFound', compact('id'));
        $exceptionClass = $message['class'];
        throw new $exceptionClass($message['text'], $message['code']);
    }

/**
 * Find a record from the ID
 *
 * @param string $id
 * @param string $findMethod
 * @return array
 */
    protected function _findRecord($id, $findMethod = null) {
        $model = $this->_model();

        $query = array();
        $query['conditions'] = array($model->escapeField() => $id);

        if (!$findMethod) {
            $findMethod = $this->_getFindMethod($findMethod);
        }

        $subject = $this->_trigger('beforeFind', compact('query', 'findMethod'));
        return $model->find($subject->findMethod, $subject->query);
    }

/**
 * Set data to view
 *
 * @param array $data
 * @return void
 */
    protected function _setData($data = null) {
        $controller = $this->_controller();
        $model = $this->_model();

        $controller->set('showHeader', true);
        $controller->set('controller', $controller->name);
        $controller->set('model', $model->alias);
        $controller->set('displayField', (isset($model->displayField) ? $model->displayField : 'title'));
        $controller->set('data', $data);
    }

/**
 * Read detail data for delete view
 *
 * @throws NotFoundException If record not found
 * @param string $id
 * @return void
 */
    protected function _detail($id = null) {
        if (!$this->_validateId($id)) {
            return false;
        }

        $data = $this->_findRecord($id, 'first');
        if (empty($data)) {
            return $this->_notFound($id);
        }

        $this->_setData($data);

        $this->_trigger('beforeRender');
    }

/**
 * HTTP GET handler
 *
 * @param mixed $id
 * @return void
 */
    protected function _get($id = null) {
        return $this->_detail($id);
    }

}
