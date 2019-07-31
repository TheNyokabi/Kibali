<?php
App::uses('SectionBaseHelper', 'View/Helper');
App::uses('Country', 'Model');
App::uses('Hash', 'Utility');

class DataAssetSettingsHelper extends SectionBaseHelper {
	public $helpers = ['Html', 'Eramba'];
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getDpo($data, $tags = false) {
		$list = [];

		if ($data['DataAssetSetting']['dpo_empty']) {
			$item = __('Not applicable');
			$list[] = ($tags) ? $this->Eramba->getLabel($item, 'improvement') : $item;
		}
		else {
			$list = Hash::extract($data, 'DataAssetSetting.Dpo.{n}.full_name');
			if ($tags) {
				foreach ($list as $key => $user) {
					$list[$key] = $this->Eramba->getLabel($user, 'improvement');
				}
			}
		}

		$separator = ($tags) ? ' ' : ', ';
		
		return implode($list, $separator);
	}

	public function outputDpo($data) {
		if (empty($data)) {
			return $this->Eramba->getEmptyValue('');
		}

		return $this->getDpo($data['DataAssetInstance'], true);
	}

	public function getControllerRepresentative($data, $tags = false) {
		$list = [];

		if ($data['DataAssetSetting']['controller_representative_empty']) {
			$item = __('Not applicable');
			$list[] = ($tags) ? $this->Eramba->getLabel($item, 'improvement') : $item;
		}
		else {
			$list = Hash::extract($data, 'DataAssetSetting.ControllerRepresentative.{n}.full_name');
			if ($tags) {
				foreach ($list as $key => $user) {
					$list[$key] = $this->Eramba->getLabel($user, 'improvement');
				}
			}
		}

		$separator = ($tags) ? ' ' : ', ';
		
		return implode($list, $separator);
	}

	public function outputControllerRepresentative($data) {
		if (empty($data)) {
			return $this->Eramba->getEmptyValue('');
		}

		return $this->getControllerRepresentative($data['DataAssetInstance'], true);
	}
}