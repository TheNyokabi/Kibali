<?php

App::uses('AuthComponent', 'Controller/Component');
App::uses('Hash', 'Utility');
 
class AppAuthComponent extends AuthComponent
{
	public function login($user = null) 
	{
		if (parent::login($user)) {
			$this->User = ClassRegistry::init('User');

			$groups = $this->User->find('first', array(
				'conditions' => array(
					'User.id' => $this->user('id')
				),
				'contain' => array(
					'Group' => array(
						'fields' => array(
							'Group.id'
						)
					)
				)
			));

			$groups = Hash::extract($groups, 'Group.{n}.id');
			$user = $this->user();
			$user['Groups'] = $groups;

			return parent::login($user);
		}
 
		return $this->loggedIn();
	}
}
