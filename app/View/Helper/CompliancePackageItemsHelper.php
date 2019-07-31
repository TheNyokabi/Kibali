<?php
class CompliancePackageItemsHelper extends AppHelper {
	public $helpers = array('Html');
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	//sort items by item_id
	public function sortByItemId($data){
		$defaultSortItemId = [0, 0, 0, 0, 0, 0, 0];
		foreach ($data as $key => $packageItem) {
			$explodeSortId = explode('.', $packageItem['item_id']) + $defaultSortItemId;
			$sortId = '';
			foreach ($explodeSortId as $idItem) {
				$sortId .= sprintf('%08d', $idItem);
			}
			$data[$key]['sort_item_id'] = $sortId;
		}

		return Hash::sort($data, '{n}.sort_item_id', 'asc');
	}
}