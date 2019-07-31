<?php
App::uses('SectionBase', 'Model');

class PolicyExceptionClassification extends SectionBase {
	public $displayField = 'name';

	public $actsAs = array(
		'Containable',
	);
}
