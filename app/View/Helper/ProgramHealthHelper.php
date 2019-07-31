<?php
App::uses('ErambaHelper', 'View/Helper');
class ProgramHealthHelper extends ErambaHelper {
	public $helpers = array('Risks', 'Assets', 'SecurityServices', 'Projects', 'Html');
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		$this->settings = $settings;
	}

	public function getListBtn($url) {
		return $this->Html->link('(' . __('Show list') . ')', $url, array(
			'class' => 'pdf-hide',
			'escape' => false
		));
	}

	/**
	 * @deprecated
	 */
	public function getRiskStatusCount($items = array(), $config/*, $model = 'Risk'*/) {
		$count = 0;
		foreach ($items['Risk'] as $item) {
			$status = $this->Risks->getStatusArr($item, $config, 'Risk');
			if (!empty($status)) {
				$count++;
			}
		}
		foreach ($items['ThirdPartyRisk'] as $item) {
			$status = $this->Risks->getStatusArr($item, $config, 'ThirdPartyRisk');
			if (!empty($status)) {
				$count++;
			}
		}
		foreach ($items['BusinessContinuity'] as $item) {
			$status = $this->Risks->getStatusArr($item, $config, 'BusinessContinuity');
			if (!empty($status)) {
				$count++;
			}
		}

		return $count;
	}

	public function getAssetStatusCount($items = array(), $config) {
		$count = 0;
		if (!empty($items['Asset'])) {
			foreach ($items['Asset'] as $item) {
				$status = $this->Assets->getStatusArr($item, $config);
				if (!empty($status)) {
					$count++;
				}
			}
		}

		return $count;
	}

	public function getSecurityServiceStatusCount($items = array(), $config) {
		$count = 0;
		if (!empty($items['SecurityService'])) {
			foreach ($items['SecurityService'] as $item) {
				$status = $this->SecurityServices->getStatusArr($item, $config);
				if (!empty($status)) {
					$count++;
				}
			}
		}

		return $count;
	}

	public function getProjectStatusCount($items = array(), $config) {
		return $this->getTagStatusCount('Projects', 'Project', $items, $config);
	}

	public function getTagStatusCount($helper, $model, $items = array(), $config) {
		$count = 0;
		if (!empty($items[$model])) {
			foreach ($items[$model] as $item) {
				$status = $this->{$helper}->getStatusArr($item, $config);
				if (!empty($status)) {
					$count++;
				}
			}
		}

		return $count;
	}

}