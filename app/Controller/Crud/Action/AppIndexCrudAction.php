<?php

App::uses('IndexCrudAction', 'Crud.Controller/Crud/Action');
App::uses('CrudActionTrait', 'Controller/Crud/Trait');

class AppIndexCrudAction extends IndexCrudAction {
	use CrudActionTrait;
	const VIEW_ITEM_QUERY = 'view_item_id';
	
	public function __construct(CrudSubject $subject, array $defaults = array()) {
		$defaults = am([
			// contain for the find (moved from model here)
            'contain' => [],
            'filter' => [
				'enabled' => true
			]
        ], $defaults);

        parent::__construct($subject, $defaults);
	}

	public function paginationConfig() {
		// shortcuts
		$controller = $this->_controller();
		$model = $this->_model();

		parent::paginationConfig();
		$settings = &$this->_controller()->Paginator->settings;

		$settings['order'] = [
				$model->alias . '.' . $model->displayField => 'ASC',
				$model->alias . '.' . $model->primaryKey => 'ASC'
		];

		$settings['limit'] = 20;

		// handling the case when this crud action is used on index without filters entirely
		$hasSearchable = $controller->Components->enabled('Search.Prg');
		$hasSearchable &= $model->Behaviors->enabled('Search.Searchable');
		if ($hasSearchable) {
			$parsedParams = $controller->Prg->parsedParams();
			$filterConditions = $model->parseCriteria($parsedParams);

			$paginatorConds = [];
			if (isset($settings['conditions'])) {
				$paginatorConds = $settings['conditions'];
			}

			$settings['conditions'] = am(
				$paginatorConds,
				$filterConditions
			);
		}
		
		$settings['contain'] = $this->config('contain');

		$findCustomFieldValues = $model->getAssociated('CustomFieldValue') !== null;
		$findCustomFieldValues &= $controller->CustomFieldsMgt !== null;
		if ($findCustomFieldValues) {
            $settings['contain'][] = 'CustomFieldValue';
        }

		return $settings;
	}

	protected function _get() {
		$response = $this->handleFilterAction($this->config('filter'));

		// if the return value from FilterCrudAction is a CakeResponse instance
		// it means it is an active advanced filter
		if ($response instanceof CakeResponse) {
			return $response;
		}

		$this->setOptionalData();
		
		$this->_controller()->set('filterArgs', $this->_model()->filterArgs);
		if (isset($this->_controller()->request->query[self::VIEW_ITEM_QUERY])) {
			$id = $this->_controller()->request->query[self::VIEW_ITEM_QUERY];

			$this->_controller()->Paginator->settings['conditions'][$this->_model()->escapeField()] = $id;
		}
		return parent::_get();
	}

	protected function _post() {
		return $this->_get();
	}


}
