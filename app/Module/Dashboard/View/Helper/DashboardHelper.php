<?php
App::uses('AppHelper', 'View/Helper');
App::uses('DashboardKpi', 'Dashboard.Model');
App::uses('Dashboard', 'Dashboard.Lib');

class DashboardHelper extends AppHelper {
	public $helpers = ['Layout', 'Html', 'Eramba'];

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
	}

	/**
	 * Get the dashboard link depending on specified type.
	 * 
	 * @param  int   $type Type of the dashboard.
	 * @return array
	 */
	public function getDashboardUrl($type = DashboardModule::TYPE_USER) {
		$url = [
			'plugin' => 'dashboard'
		];

		if ($type === DashboardModule::TYPE_USER) {
			$url['controller'] = 'dashboard_kpis';
			$url['action'] = 'user';
		}

		if ($type === DashboardModule::TYPE_ADMIN) {
			$url['controller'] = 'dashboard_kpis';
			$url['action'] = 'admin';
		}

		if ($type === DashboardModule::TYPE_CALENDAR) {
			$url['plugin'] = null;
			$url['controller'] = 'pages';
			$url['action'] = 'dashboard';
		}

		return $url;
	}

	/**
	 * Include dashboard buttons, among others, into the header toolbar through LayoutHelper and its toolbar items.
	 * 
	 * @see LayoutHelper::addToolbarItem()
	 */
	public function addToolbarBtns() {
		$this->Layout
			->addToolbarItem(__('User Dashboard'), $this->getDashboardUrl(DashboardModule::TYPE_USER), [
				'icon' => 'home'
			])
			->addToolbarItem(__('Admin Dashboard'), $this->getDashboardUrl(DashboardModule::TYPE_ADMIN), [
				'icon' => 'cog',
				'disabled' => !isAdmin($this->_View->get('logged'))
			])
			->addToolbarItem(__('Calendar'), $this->getDashboardUrl(DashboardModule::TYPE_CALENDAR), [
				'icon' => 'calendar',
				'disabled' => !isAdmin($this->_View->get('logged'))
			]);
	}

	/**
	 * This builds a small simple bar chart for KPIs.
	 */
	public function getKpiSparkline($arr, $color = '#e25856', $maxRange = null) {
		if (empty($arr)) {
			return $this->Eramba->getTruncatedTooltip('', [
				'content' => __('Weekly chart needs to be calculated during CRON job few times to display properly here.')
			]);
		}

		$color = 'grey';
		if (count($arr)) {
			if ((array_sum($arr)/count($arr)) !== end($arr)) {
				if ($this->isChartImproving($arr)) {
					$color = 'green';
				}
				else {
					$color = 'red';
				}
			}
		}

		$maxRange = ($maxRange !== null) ? $maxRange : $this->_View->get('chartRangeMax', null);
		$sparkline = $this->Html->div('dashboard-sparkline', implode(',', $arr), [
			'data-bar-color' => $color,
			'data-chart-range-min' => 0,
			'data-chart-range-max' => $maxRange
		]);

		return $sparkline;
	}

	/**
	 * Chart with overtime values can have falling or raising values, falling values means its improving.
	 */
	public function isChartImproving($arr) {
		$checkpoint = reset($arr);

		$sum = array_sum($arr);
		$result = $sum/count($arr);

		return $result < $checkpoint;
	}


}
