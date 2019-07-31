<?php
App::uses('UserFieldsAppModel', 'UserFields.Model');

class UserFieldsGroup extends UserFieldsAppModel
{
	public $belongsTo = [
		'Group' => [
			'className' => 'Group',
			'foreign_key' => 'group_id'
		]
	];
}