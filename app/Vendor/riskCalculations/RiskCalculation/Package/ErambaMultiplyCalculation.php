<?php
namespace RiskCalculation\Package;
use RiskCalculation\Package\ErambaCalculation;

class ErambaMultiplyCalculation extends ErambaCalculation {

	public function __construct() {
		parent::__construct();

		$this->mathOperator = '*';
		
		$this->name = 'Eramba (multiplication)';
		$this->description = __('The same as original Eramba calculation, only instead of sum of user-selected options, it will multiply them.');
	}

}