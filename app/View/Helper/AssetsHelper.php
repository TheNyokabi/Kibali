<?php
App::uses('SectionBaseHelper', 'View/Helper');

class AssetsHelper extends SectionBaseHelper {
	public $helpers = ['Html', 'Ajax', 'Eramba'];
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function actionList($item, $options = []) {
		$reviewUrl = array(
			'plugin' => null,
			'controller' => 'reviews',
			'action' => 'index',
			'Asset',
			$item['Asset']['id']
		);

		$this->Ajax->addToActionList(__('Reviews'), $reviewUrl, 'search', 'index');

		$exportUrl = array(
			'controller' => 'assets',
			'action' => 'exportPdf',
			$item['Asset']['id']
		);

		$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

		$options = am([
			AppModule::instance('Visualisation')->getAlias() => true
		], $options);

		return parent::actionList($item, $options);
	}

	public function getStatusArr($item, $allow = '*') {
		$item = $this->Eramba->processItemArray($item, 'Asset');
		$statuses = array();

		if ($this->Eramba->getAllowCond($allow, 'expired_reviews') && $item['Asset']['expired_reviews'] == RISK_EXPIRED_REVIEWS) {
			$statuses[$this->getStatusKey('expired_reviews')] = array(
				'label' => __('Missing Asset Review'),
				'type' => 'warning'
			);
		}

		$inherit = array(
			'SecurityIncidents' => array(
				'model' => 'SecurityIncident',
				'config' => array('ongoing_incident')
			)
		);

		if ($this->Eramba->getAllowCond($allow, INHERIT_CONFIG_KEY)) {
			$statuses = am($statuses, $this->getInheritedStatuses($item, $inherit));
		}

		return $statuses;
	}

	public function getStatuses($item, $options = array()) {
		$options = $this->Eramba->processStatusOptions($options);

		$statuses = $this->getStatusArr($item, $options['allow']);
		return $this->Eramba->styleStatuses($statuses, $options);
	}

}