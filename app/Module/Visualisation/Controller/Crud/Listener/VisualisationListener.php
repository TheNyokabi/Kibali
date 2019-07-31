<?php
App::uses('CrudListener', 'Crud.Controller/Crud');

/**
 * Search Listener
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class VisualisationListener extends CrudListener {

/**
 * Default configuration
 *
 * @var array
 */
	protected $_settings = array(
	);

/**
 * Returns a list of all events that will fire in the controller during its lifecycle.
 * You can override this function to add you own listener callbacks
 *
 * We attach at priority 50 so normal bound events can run before us
 *
 * @return array
 */
	public function implementedEvents() {
		return array(
			'Crud.beforeHandle' => array('callable' => 'beforeHandle', 'priority' => 50),
			'Crud.beforePaginate' => array('callable' => 'beforePaginate', 'priority' => 50),
			'Crud.beforeSave' => array('callable' => 'beforeSave', 'priority' => 50),
			'Crud.beforeDelete' => array('callable' => 'beforeDelete', 'priority' => 50),
			'Crud.beforeFind' => array('callable' => 'beforeFind', 'priority' => 50),
			'Crud.beforeRender' => array('callable' => 'beforeRender', 'priority' => 50),
		);
	}

	public function beforeHandle(CakeEvent $event) {
		$controller = $this->_controller();
		$model = $this->_model();

		$this->_ensureComponent($controller);
		$this->_ensureBehavior($model);

		$this->readable = $this->nonReadable = 0;
	}

	// Load required components if missing
	protected function _ensureComponent(Controller $controller) {
		if (!$controller->Components->loaded('Visualisation')) {
			$controller->Visualisation = $controller->Components->load('Visualisation.Visualisation');
			$controller->Visualisation->initialize($controller);
			$controller->Visualisation->startup($controller);
		}
	}

	// Load required behaviors if missing
	protected function _ensureBehavior(Model $model) {
		if ($model->Behaviors->loaded('Visualisation')) {
			return;
		}

		$model->Behaviors->load('Visualisation.Visualisation');
		$model->Behaviors->Visualisation->setup($model);
	}

	public function setup() {
	}

	public function beforeRender(CakeEvent $e) {
		// variables set here cannot be read in Filter views used by FilterAction class
		// all syntax is now moved from here to VisualisationComponent::beforeRender() callback.
	}

	public function beforeSave(CakeEvent $e) {
		if (isset($e->subject->id)) {
			$permissionType = 'update';
			$foreignKey = $e->subject->id;
		}
		else {
			$permissionType = 'create';
			$foreignKey = null;
		}

		$this->_permissionCheck($permissionType, $foreignKey);
	}

	/**
	 * Before delete check of permission.
	 */
	public function beforeDelete(CakeEvent $e) {
		$permissionType = 'delete';
		$foreignKey = $e->subject->id;

		$this->_permissionCheck($permissionType, $foreignKey);
	}

	/**
	 * Before find check for permission.
	 */
	public function beforeFind(CakeEvent $e) {
		// beforeFind is triggered for a specific object but also for entire indexes
		// so we are doing a permission check only for a specific object
		if (isset($e->subject->id)) {
			$permissionType = 'read';
			$foreignKey = $e->subject->id;
			$this->_permissionCheck($permissionType, $foreignKey);
		}
	}

	/**
	 * Common handler for permission checks just before reading or applying changes on an object.
	 * 
	 * @param  string   $permissionType Type of permission to check - 'create', 'update' or 'delete'
	 * @param  int|null $foreignKey     Foreign key of the object that requires permission check,
	 *                                  NULL is for 'create' permission type as there is no ID to put
	 * @return void
	 * @throws ForbiddenException       When logged-in user does not have permission to apply modifications.
	 */
	protected function _permissionCheck($permissionType, $foreignKey) {
		$model = $this->_model();
		$controller = $this->_controller();

		$permission = $controller->Visualisation->check($controller->logged['id'], $model, $foreignKey, $permissionType);
		$controller->Visualisation->cleanup();

		if ($permission === false) {
			throw new ForbiddenException(__('You don\'t have permission to read or modify this object.'));
		}
	}

	public function beforePaginate(CakeEvent $e) {
		$model = $this->_model();
		$controller = $this->_controller();

		$controlled = $model->getControlled();
		$filteredList = $this->getFilteredList($model, $conds = $this->paginatorConditions($controller));

		$nodes = array_intersect($controlled, $filteredList);

		// readable objects (foreign keys)
		// $visualizedObjects = $this->visualizeList($model, $nodes);

		$key = 'pagination_'. $controller->logged['id'].'_'.$controller->name . '_' . $e->subject->action;
		if (($readable = Cache::read($key, 'visualisation')) === false) {
			$readable = [];
			// this gets and foreach all ACL Controlled objects (section's items)
			foreach ($nodes as $foreignKey) {

				// user's read-access check on a certain object
				if ($check = $this->isReadable($model, $foreignKey)) {
					$readable[] = $foreignKey;
				}
			}

			if (empty($conds)) {
				Cache::write($key, $readable, 'visualisation');
			}
		}
		
		$this->readable = count($readable);
		$this->nonReadable = count($nodes) - $this->readable;

		$controller->Visualisation->cleanup();
		$this->_setPaginationOptions($controller, $model, $readable);
	}

	public function getFilteredList(Model $Model, $conds) {
		return $Model->find('list', [
			'conditions' => $conds,
			'fields' => [$Model->alias . '.' . $Model->primaryKey],
			'recursive' => -1
		]);
	}

	// Check permissions on a set of object IDs and return only IDs with access.
	public function visualizeList(Model $model, $list) {
		$controller = $this->_controller();

		return $controller->Visualisation->check($controller->logged['id'], $model, $list, 'read');
	}

	// check read permission on object
	protected function isReadable($model, $foreignKey) {
		$controller = $this->_controller();

		return $controller->Visualisation->check($controller->logged['id'], $model, $foreignKey, 'read');
	}

	public function paginatorConditions(Controller $controller) {
		if (!isset($controller->Paginator->settings['conditions'])) {
			return [];
		}

		return $controller->Paginator->settings['conditions'];
	}

/**
 * Set the pagination options
 *
 * @param Controller $controller
 * @param Model $model
 * @param array $query
 * @return void
 */
	protected function _setPaginationOptions(Controller $controller, Model $Model, $accessible = []) {
		if (empty($accessible)) {
			$accessible = [-1];
		}

		if (!isset($controller->Paginator->settings['conditions'])) {
			$controller->Paginator->settings['conditions'] = array();
		}

		$primaryField = $this->getPrimary($Model);

		$accessIds = implode(',', $accessible);
		$controller->Paginator->settings['conditions'][] = "{$primaryField} IN ({$accessIds})";

		$controller->set('visualisationPagination', [
			'readable' => $this->readable,
			'nonReadable' => $this->nonReadable
		]);

		$controller->set('visualisationTotal', $this->readable + $this->nonReadable);

		if ($this->nonReadable == 0) {
			$controller->set('visualisationNoRestriction', true);
		}
		else {
			$controller->set('visualisationNoRestriction', false);
		}
	}

	public function getPrimary(Model $Model) {
		return $Model->alias . '.' . $Model->primaryKey;
	}

}
