<?php
/**
 * AppShell file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Shell', 'Console');
App::uses('AppAuthComponent', 'Controller/Component');
App::uses('ComponentCollection', 'Controller');
App::uses('Controller', 'Controller');

/**
 * Application Shell
 *
 * Add your application-wide methods in the class below, your shells
 * will inherit them.
 *
 * @package       app.Console.Command
 */
class AppShell extends Shell {
	public function initialize() {
		// add App Modules for custom baking using original shell
		if ($this->name == 'Bake') {
			$this->tasks[] = 'Module';
		}

		parent::initialize();
	}

	public function startup() {

	}

	protected function _loginAdmin() {
		// login admin
		$comp = (new ComponentCollection());
		$comp->init(new Controller());
		$this->Auth = (new AppAuthComponent($comp, []));
		
		$user = ClassRegistry::init('User')->find('first', [
			'conditions' => [
				'User.id' => ADMIN_ID
			],
			'recursive' => -1
		]);
		
		$this->Auth->login($user['User']);
	}

	public function getOptionParser() {
		$parser = parent::getOptionParser();

		// add Modules also into the list of commands when using Bake shell 
		if ($this->name == 'Bake') {
			$parser->addSubcommand('module', array(
				'help' => __d('cake_console', 'Bake a new App Module which is extended Plugin class having customized base to work on.'),
				'parser' => $this->Module->getOptionParser()
			));
		}

		return $parser;
	}
}
