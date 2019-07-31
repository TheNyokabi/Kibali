<?php
App::uses('DashboardAppController', 'Dashboard.Controller');

class DashboardsController extends DashboardAppController {
	public $modelClass = 'DashboardKpi';

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Auth->authorize = false;
	}

	public function user() {
		$this->loadModel('Risk');
		$this->loadModel('CustomRoles.CustomRolesUsers');

		$Risk = $this->Risk;
		$CustomRolesUsers = $this->CustomRolesUsers;

		App::uses('QueryBuilder', 'AdvancedFilters.Lib');
		App::uses('SubQueryFragment', 'AdvancedFilters.Lib/QueryFragment');

		$SubQueryFragment = new SubQueryFragment($CustomRolesUsers, 'foreign_key');
		$SubQueryFragment->addCondition([
			$CustomRolesUsers->escapeField('model') => $Risk->alias,
			$CustomRolesUsers->escapeField('user_id') => [1,2,3]
		]);

		$QueryBuilder = new QueryBuilder($Risk);
		$QueryBuilder->addFragment($SubQueryFragment);

		// debug($QueryBuilder->getQuery());exit;

		$this->Risk->Behaviors->load('AdvancedFilters.AdvancedFilters');
		$Filters = $this->Risk->Behaviors->AdvancedFilters;

		$cfg = [
			'expired_reviews' => 1
		];

		$this->Risk->filterField('expired_reviews')->setComparisonType(AbstractQuery::COMPARISON_EQUAL);
		$test = $this->Risk->filter('count', $cfg);

		debug($test);
	}

	public function saveFilter() {
		// $this->
	}
}
