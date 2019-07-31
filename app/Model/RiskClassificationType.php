<?php
class RiskClassificationType extends AppModel {
	public $actsAs = array(
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name'
			)
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true
		)
	);

	public $hasMany = array(
		'RiskClassification' => [
			'order' => 'RiskClassification.value DESC'
		]
	);
}
