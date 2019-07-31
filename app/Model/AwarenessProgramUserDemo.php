<?php
class AwarenessProgramUserDemo extends AppModel {
	public $useTable = false;

	public $validate = array(
		'email' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'User must be selected'
			),
			'email' => array(
				'rule' => 'email',
				'required' => true,
				'allowEmpty' => false,
				'message' => 'Email address missing'
			)
		)
	);
}