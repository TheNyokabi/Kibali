<?php
class RiskCalculationValue extends AppModel {
	public $actsAs = array(
		'Containable'
	);

	public $belongsTo = array(
		'RiskCalculation'
	);


}
