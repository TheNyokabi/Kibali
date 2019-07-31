<?php
App::uses('AppHelper', 'View/Helper');
App::uses('AdvancedFilter', 'Model');
App::uses('AdvancedFilterUserSetting', 'Model');

class AdvancedFiltersHelper extends AppHelper {
	public $helpers = array('Html', 'Form');

	/**
	 * Render entire dropdown with saved filters.
	 */
	public function getViewList($savedFilters, $model, $alignListToLeft = true) {
		if (empty($savedFilters)) {
			return false;
		}

		$list = $this->_getViewItems($savedFilters, $model);

		$baseIndexItem = '';
		if ($this->_containsDefaultIndex($savedFilters, $model) && empty($this->_View->viewVars['filter']['settings']['reset'])) {
			$baseIndexItem = $this->wrapLi($this->Html->link(__('Default'), array('action' => 'index', '?' => array('force_default_index' => true))));
			if (!empty($list)) {
				$baseIndexItem .= '<li role="separator" class="divider"></li>';
			}
		}

		$caret = $this->Html->tag('span', false, array(
			'class' => 'caret'
		));

		// dropdown btn caret toggle
		$dropdownToggle = $this->Html->tag('button', __('Views') . ' ' . $caret, array(
			'class' => 'btn dropdown-toggle',
			'data-toggle' => 'dropdown',
			'escape' => false
		));

		$listItems = $baseIndexItem;
		foreach ($list as $link) {
			$listItems .= $this->wrapLi($link);
		}
		$class = ($alignListToLeft) ? ' pull-left' : ' pull-right';
		$nestedList = $this->Html->tag('ul', $listItems, array(
			'class' => 'dropdown-menu' . $class
		));

		$buttonDropdown = $this->Html->div('btn-group group-merge', $dropdownToggle . $nestedList, array(
			'escape' => false
		));

		return $buttonDropdown;
	}

	/**
	 * Render only dropdown <li> items with saved filters.
	 */
	public function getViewItems($savedFilters, $model) {
		if (empty($savedFilters)) {
			return false;
		}

		$list = $this->_getViewItems($savedFilters, $model);

		return implode('', array_map(array($this, 'wrapLi'), $list));
	}

	private function wrapLi($item) {
		return $this->Html->tag('li', $item, array(
			'escape' => false
		));
	}

	/**
	 * Get array of items with saved filters.
	 */
	private function _getViewItems($savedFilters, $model) {
		$list = array();

		if (!empty($savedFilters)) {
			foreach ($savedFilters as $item) {
				$filterUrl = '';
				// $filterUrl = $this->getFilterUrl($model, $item);
				$filterUrl = $this->getFilterRedirectUrl($item['AdvancedFilter']['id']);


				/*if ($item['AdvancedFilter']['model'] != $model) {

				}
			    if (empty($this->_View->viewVars['filter']['settings']['url'])) {
			        $filterUrl = Router::url(array('controller' => controllerFromModel($item['AdvancedFilter']['model']), 'action' => 'index', '?' => $this->getFilterQuery($item)));
			    }
			    else {
			        $url = $this->_View->viewVars['filter']['settings']['url'];
			        $url['?'] = $this->getFilterQuery($item);
			        $filterUrl = Router::url($url);
			    }*/

				$list[] = $this->Html->link($item['AdvancedFilter']['name'], $filterUrl);
			}
		}

		return $list;
	}

	/**
	 * advanced filters redirect url
	 * 
	 * @param  int $filterId
	 * @return string
	 */
	public function getFilterRedirectUrl($filterId) {
		return Router::url(array('plugin' => 'advanced_filters', 'controller' => 'advancedFilters', 'action' => 'redirectAdvancedFilter', $filterId));
	}

	public function getFilterUrl($model, $item) {
		$filterModel = $item['AdvancedFilter']['model'];
		$settings = $this->_View->viewVars['filter']['settings'];

		$filterUrl = false;
		if ($model == $filterModel) {
			if (!empty($settings['url'])) {
				$filterUrl = $settings['url'];
		        // $filterUrl = ($url);
			}
		}
		elseif ($model != $filterModel) {
			$conds = isset($settings['additional_actions'][$filterModel]);
			$_action = $settings['additional_actions'][$filterModel];

			$conds = $conds && !empty($_action['url']);
			$conds = $conds && is_array($_action['url']);

			if ($conds) {
				$filterUrl = $_action['url'];
			}
			elseif ($url = $this->getFilterUrlSetting($filterModel)) {
				$filterUrl = $url;
			}
		}

		// default behavior for creating a link
		if (empty($filterUrl)) {
			$filterUrl = (array(
				'controller' => controllerFromModel($filterModel),
				'action' => 'index'
			));
		}

		$filterUrl['?'] = $this->getFilterQuery($item);

		$url = Router::url($filterUrl);
		return $url;
	}

	protected function getFilterUrlSetting($model) {
		$_m = _getModelInstance($model);
		if (!empty($_m->advancedFilterSettings['url']) && is_array($_m->advancedFilterSettings['url'])) {
			return $_m->advancedFilterSettings['url'];
		}

		return false;
	}

	/**
	 * Additional dropdown links to indexes that supports Advanced Filters.
	 */
	public function getAdditionalFilterLinks($links) {
		$list = array();
		foreach ($links as $model => $text) {
			// specific details defained as array
			if (is_array($text)) {
				$list[] = $this->Html->link($text['label'], $text['url']);
			}
			// 'divider' value to print out divider line html between dropdown items
			elseif ($text == ADVANCED_FILTER_DROPDOWN_DIVIDER) {
				$list[] = '<div class="divider"></div>';
			}
			else {
				$controller = controllerFromModel($model);

				$list[] = $this->Html->link($text, array(
					'controller' => $controller,
					'action' => 'index',
					'?' => array(
						'advanced_filter' => 1
					)
				));
			}
			
		}

		$caret = $this->Html->tag('span', false, array(
			'class' => 'caret'
		));

		// dropdown btn caret toggle
		$dropdownToggle = $this->Html->tag('button', $caret, array(
			'class' => 'btn btn-info dropdown-toggle',
			'data-toggle' => 'dropdown',
			'escape' => false
		));

		return $dropdownToggle . $this->Html->nestedList($list, array(
			'class' => 'dropdown-menu pull-right'
		));
	}

	public function getFieldLabel($name) {
		return $this->Html->tag('label', $name . ':', array(
			'class' => 'col-md-2 control-label'
		));
	}

	/**
	 * Applies to all fields
	 */
	public function getFieldShowCheckbox($field) {
		$checkbox = $this->Form->input($field . '__show', array(
			'type' => 'checkbox',
			'label' => false,
			'class' => 'uniform advanced-filter-show',
			'div' => false,
		));

		$checkbox .= ' ' . __('Show in result');
		$label = $this->Html->tag('label', $checkbox, array(
			'class' => 'checkbox',
			'escape' => false
		));

		return $this->Html->div('col-md-3', $label, array(
			'escape' => false
		));
	}

	/**
	 * Applies to multiselect fields
	 */
	public function getMultiselectNoneCheckbox($field) {
		$hidden = $this->Form->input($field, array(
			'type' => 'hidden',
			'class' => 'advanced-filter-none-value',
			'data-form-field' => $field,
			'value' => ADVANCED_FILTER_MULTISELECT_NONE,
			'id' => 'advanced-filter-none-value-' . $field
		));

		$checkbox = $this->Form->input($field . '__none', array(
			'type' => 'checkbox',
			'label' => false,
			'class' => 'uniform advanced-filter-none advanced-filter-autoshow',
			'data-form-field' => $field,
			'div' => false,
			'id' => 'advanced-filter-none-' . $field
		));

		$checkbox .= ' ' . __('None');
		$label = $this->Html->tag('label', $checkbox, array(
			'class' => 'checkbox',
			'escape' => false
		));

		return $this->Html->div('col-md-2', $hidden . $label, array(
			'escape' => false
		));
	}

	public function getItemFilteredLink($name, $model, $id, $options = array(), $outputOptions = array()) {
		$options = am(array(
			'key' => 'id',
			'param' => null,
			'plugin' => null,
			'controller' => null,
			'query' => array(),
			'show' => array(),//filter fields to show
		), $options);
		$controller = ($options['controller'] !== null) ? $options['controller'] : controllerFromModel($model);

		$query = (!empty($id)) ? array($options['key'] => $id) : array();
		if (!empty($options['query'])) {
			$query = am($query, $options['query']);
		}

		// link handler that shows only URL for csv export
		if (isset($outputOptions['AdvancedFiltersDataInstance']) && $outputOptions['AdvancedFiltersDataInstance'] instanceof AdvancedFiltersData) {
			$instance = $outputOptions['AdvancedFiltersDataInstance'];
			App::uses('AdvancedFiltersData', 'Lib');
			if ($instance->getViewType() == AdvancedFiltersData::VIEW_TYPE_CSV) {
				$url = Router::url(array(
					'plugin' => $options['plugin'],
					'controller' => $controller,
					'action' => 'index',
					$options['param'],
					'?' => am(array('advanced_filter' => 1), $query)
				), true);

				return $url;//sprintf('=HYPERLINK("%s")', $url);
			}
		}

		foreach ($options['show'] as $field) {
			$query["{$field}__show"] = 1;
		}

		$link = $this->Html->link($name, array(
			'plugin' => $options['plugin'],
			'controller' => $controller,
			'action' => 'index',
			$options['param'],
			'?' => am(array('advanced_filter' => 1), $query)
		), ['escape' => false]);

		return $link;
	}

	public function showLink($label, $fields, $model, $options = array()) {
		$options = am(array(
			'defaults' => array('id'),
			'action' => 'index',
			'query' => array(),
		), $options);

		$controller = controllerFromModel($model);

		$fields = am($options['defaults'], $fields);

		$query = $options['query'];
		foreach ($fields as $item) {
			$query[$item . '__show'] = true;
		}

		return $this->Html->link($label, array(
			'controller' => $controller,
			'action' => $options['action'],
			'?' => am(array('advanced_filter' => true), $query)
		));
	}

	public function getFilterQuery($filter, $excludeFilterParam = false) {
		$filterParams = array();
		$filterParams['advanced_filter_id'] = $filter['AdvancedFilter']['id'];
		foreach ($filter['AdvancedFilterValue'] as $value) {
			if ($excludeFilterParam && $value['field'] == 'advanced_filter') {
				continue;
			}
		 	if ($value['many'] == ADVANCED_FILTER_VALUE_MANY) {
				$filterParams[$value['field']] = explode(',', $value['value']);
			}
			else {
				$filterParams[$value['field']] = $value['value'];
			}
		}
		return http_build_query($filterParams);
	}

	/**
	 * checks if list of filters contains default index for input model
	 *
	 * @param  array $filters
	 * @param  string $model
	 * @return boolean
	 */
	private function _containsDefaultIndex($filters, $model) {
		foreach ($filters as $filter) {
			$defaultIndex = false;
			if (!empty($filter['AdvancedFilterUserSetting']) 
				&& $filter['AdvancedFilterUserSetting']['default_index'] == AdvancedFilterUserSetting::DEFAULT_INDEX
			) {
				$defaultIndex = true;
			}

			if ($defaultIndex && $filter['AdvancedFilter']['model'] == $model) {
				return true;
			}
		}

		return false;
	}
}