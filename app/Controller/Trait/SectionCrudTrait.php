<?php
trait SectionCrudTrait  {

/**
 * Default index action for a Crud.
 */
    public function index() {
        return $this->Crud->execute();
    }

/**
 * Default add action for a Crud.
 */
    public function add() {
        return $this->Crud->execute();
    }

/**
 * Default edit action for a Crud.
 * 
 * @param int $id item id
 */
    public function edit($id = null) {
        return $this->Crud->execute();
    }

/**
 * Default delete action for a Crud.
 * 
 * @param int $id item id
 */
    public function delete($id = null) {
        return $this->Crud->execute();
    }

/**
 * Default trash action for a Crud.
 */
    public function trash() {
        return $this->Crud->execute();
    }
}
