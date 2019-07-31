<?php
trait CrudActionTrait  {

/**
 * Handles data replacement corruption caused by Model::validateAssociated()
 * 
 * @return boolean
 */
    protected function _saveAssociatedHandler() {
        $request = $this->_request();
        $model = $this->_model();
        $_originalData = $request->data;
        $ret = true;

        if ($this->saveMethod() == 'saveAssociated') {
            //validate main model
            $model->set($request->data);
            $ret &= $model->validates($this->saveOptions());
            //validate associated models
            foreach ($request->data as $modelName => $item) {
                if (!empty($model->$modelName)) {
                    $model->$modelName->set($request->data);
                    $ret &= $model->$modelName->validates($this->saveOptions());
                }
            }

            // $ret &= $model->validateAssociated($request->data, $this->saveOptions());

            $request->data = $_originalData;
            $this->saveOptions(['validate' => false, 'atomic' => true]);
        }
        
        return $ret;
    }

    /**
     * Get the label of a currently mapped model.
     */
    public function getSectionLabel(CrudSubject $subject) {
        return $subject->model->label(['singular' => true]);
    }

    // return action label
    public function mapActionToLabel() {
        $map = [
            'FilterCrudAction' => __('Filter'),
            'IndexCrudAction' => __('Index'),
            'AddCrudAction' => __('Add'),
            'EditCrudAction' => __('Edit'),
            'DeleteCrudAction' => __('Delete'),
            'TrashCrudAction' => __('Trash'),
        ];

        foreach ($map as $className => $label) {
            if ($this instanceof $className) {
                return $label;
            }
        }

        return false;
    }
    
    /**
     * Initializes a FilterCrudAction class on the fly which can then be used by other CrudAction classes.
     *
     * @return FilterCrudAction
     */
    protected function _mapFilterAction() {
        $this->_container->args = [];
        $this->_crud()->mapAction('filter', ['className' => 'Filter', 'enabled' => false], false);

        return $this->_crud()
            ->action('filter');
    }

    /**
     * Method maps FilterCrudAction and executes handle() method and returns the results,
     * either instance of CakeResponse class with successfull response, or (bool) false.
     *    
     * @param  array  $settings  Custom filter configuration.
     * @return CakeResponse|false
     */
    public function handleFilterAction($settings = []) {
        // no reason to continue loading up FilterCrudAction class in case filters are missing,
        // the same would happen after the class is handled, so for performance reasons we skip it.
        if (!$this->hasAdvancedFilters()) {
            return false;
        }
        
        return $this->_mapFilterAction()->config($settings)->handle($this->_container);
    }

    /**
     * Check that current controller/section includes Advanced Filters functionality.
     * 
     * @return boolean True if it includes filters, False otherwise.
     */
    public function hasAdvancedFilters() {
        return $this->_controller()->Components->enabled('AdvancedFilters');
    }

    /**
     * Set data related to optional features which may not be enabled/available at all sections.
     */
    protected function setOptionalData() {
        if ($this->_controller()->CustomFieldsMgt !== null) {
            $this->_controller()->CustomFieldsMgt->setData();
        }
    }
}
