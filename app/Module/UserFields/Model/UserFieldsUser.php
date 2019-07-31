<?php
App::uses('UserFieldsAppModel', 'UserFields.Model');

class UserFieldsUser extends UserFieldsAppModel
{
	public $belongsTo = [
		'User' => [
			'className' => 'User',
			'foreign_key' => 'user_id'
		]
	];
}