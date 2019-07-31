<?php
class Scope extends AppModel {
	public $actsAs = array(
		'Containable'
	);

	public $validate = array(
	);

	public $belongsTo = array(
		'CisoRole' => array(
			'className' => 'User',
			'foreignKey' => 'ciso_role_id',
			'fields' => array('id', 'name', 'surname')
		),
		'CisoDeputy' => array(
			'className' => 'User',
			'foreignKey' => 'ciso_deputy_id',
			'fields' => array('id', 'name', 'surname')
		),
		'BoardRepresentative' => array(
			'className' => 'User',
			'foreignKey' => 'board_representative_id',
			'fields' => array('id', 'name', 'surname')
		),
		'BoardRepresentativeDeputy' => array(
			'className' => 'User',
			'foreignKey' => 'board_representative_deputy_id',
			'fields' => array('id', 'name', 'surname')
		),
	);

}
