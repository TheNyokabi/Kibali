<?php
App::uses('SectionBaseHelper', 'View/Helper');
App::uses('Country', 'Model');
App::uses('DataAssetGdprArchivingDriver', 'Model');
App::uses('Hash', 'Utility');

class DataAssetGdprHelper extends SectionBaseHelper {
	public $helpers = ['Html', 'Eramba'];
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getThirdPartyInvolved($data, $tags = false) {
		$countries = [];

		if ($data['DataAssetGdpr']['third_party_involved_all']) {
			$item = __('Anywhere in the world');
			$countries[] = ($tags) ? $this->Eramba->getLabel($item, 'improvement') : $item;
		}
		else {
			$countryIds = Hash::extract($data, 'DataAssetGdpr.ThirdPartyInvolved.{n}.country_id');
			foreach ($countryIds as $countryId) {
				$item = Country::countries()[$countryId];
				$countries[] = ($tags) ? $this->Eramba->getLabel($item, 'improvement') : $item;
			}
		}

		$separator = ($tags) ? ' ' : ', ';
		
		return implode($countries, $separator);
	}

	public function outputThirdPartyInvolved($data) {
		if (empty($data)) {
			return $this->Eramba->getEmptyValue('');
		}

		return $this->getThirdPartyInvolved($data, true);
	}

	public function getArchivingDriver($data, $tags = false) {
		$list = [];

		if ($data['DataAssetGdpr']['archiving_driver_empty']) {
			$item = __('Not applicable');
			$list[] = ($tags) ? $this->Eramba->getLabel($item, 'improvement') : $item;
		}
		else {
			$driverIds = Hash::extract($data, 'DataAssetGdpr.DataAssetGdprArchivingDriver.{n}.archiving_driver');
			foreach ($driverIds as $driverId) {
				$item = DataAssetGdprArchivingDriver::archivingDrivers()[$driverId];
				$list[] = ($tags) ? $this->Eramba->getLabel($item, 'improvement') : $item;
			}
		}

		$separator = ($tags) ? ' ' : ', ';
		
		return implode($list, $separator);
	}

	public function outputArchivingDriver($data) {
		if (empty($data)) {
			return $this->Eramba->getEmptyValue('');
		}

		return $this->getArchivingDriver($data, true);
	}
}