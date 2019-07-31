<?php
App::uses('AppModel', 'Model');

class CustomRolesAppModel extends AppModel {
	public $tablePrefix = 'custom_roles_';

	/**
	 * Initialize ACL but with Visualisation ACL adapter.
	 */
	public function initAcl($controller = null) {
		if ($this->Acl instanceof AclComponent) {
			return $this->Acl;
		}
		
		$originalAdapter = Configure::read('Acl.classname');
		Configure::write('Acl.classname', Configure::read('Visualisation.Acl.classname'));

		if (!$controller) {
			$controller = new Controller(new CakeRequest());
		}
		$collection = new ComponentCollection();
		$this->Acl = new AclComponent($collection);
		$this->Acl->startup($controller);
		$this->Aco = $this->Acl->Aco;
		$this->Aro = $this->Acl->Aro;
		$this->controller = $controller;

		Configure::write('Acl.classname', $originalAdapter);

		return $this->Acl;
	}
}
