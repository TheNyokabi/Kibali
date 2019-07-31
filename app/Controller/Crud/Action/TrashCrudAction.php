<?php
App::uses('CrudAction', 'Crud.Controller/Crud');
App::uses('CrudActionTrait', 'Controller/Crud/Trait');
App::uses('AdvancedFiltersComponent', 'Controller/Component');

/**
 * Handles 'Trash' Crud actions
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 */
class TrashCrudAction extends CrudAction {
	use CrudActionTrait;

	const ACTION_SCOPE = CrudAction::SCOPE_MODEL;
	
	protected $_settings = array(
		'enabled' => true,
		'serialize' => array(),
		'api' => array(
			'success' => array(
				'code' => 200
			),
			'error' => array(
				'code' => 400
			)
		),
		'filter' => [
			'enabled' => true,
			'filterType' => AdvancedFiltersComponent::FILTER_TYPE_TRASH
		]
	);

	public function __construct(CrudSubject $subject, array $defaults = array()) {
		parent::__construct($subject, $defaults);

	}

	protected function _get() {
		// just remove the query param?
		// $q = &$this->_controller()->request->query;
		// if (isset($q['advanced_filter'])) {
		// 	return $this->_controller()->redirect(['action' => 'trash']);
		// }
		// $q['advanced_filter'] = 1;

		$this->_controller()->title = sprintf('%s (%s)', $this->_controller()->title, __('Trash'));
		
		return $this->handleFilterAction($this->config('filter'));
	}
}