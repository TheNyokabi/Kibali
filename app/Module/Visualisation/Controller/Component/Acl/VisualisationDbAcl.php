<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller.Component.Acl
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AclInterface', 'Controller/Component/Acl');
App::uses('DbAcl', 'Controller/Component/Acl');
App::uses('Hash', 'Utility');
App::uses('ClassRegistry', 'Utility');

/**
 * Visualisation ACL implementation to manage objects.
 * 
 * @package       Visualisation.Controller.Component.Acl
 */
class VisualisationDbAcl extends DbAcl implements AclInterface {

/**
 * Constructor
 */
	public function __construct() {
		parent::__construct();
	}

	protected function _getNodeCacheKey($node) {
		if (is_array(current($node)) && is_string(key($node))) {
			$name = key($node);
			$n = current($node);
			$id = $n['id'];

			// for group ARO case we format the group ID values as follows
			if (is_array($id)) {
				$id = implode('-', $id);
			}

			return sprintf('%s_%s', $name, $id);
		}

		trigger_error('Incorrect ACL node format while using VisualisationDbAcl. ' . print_r($node, true));
		return false;
	}

	public function getCacheKey($aro, $aco, $action) {
		$cacheAro = $this->_getNodeCacheKey($aro);
		$cacheAco = $this->_getNodeCacheKey($aco);

		if ($cacheAro && $cacheAco) {
			return sprintf('permission_%s_%s_%s', $cacheAro, $cacheAco, $action);
		}

		return false;
	}

	public function check($aro, $aco, $action = "*") {
		$cacheKey = $this->getCacheKey($aro, $aco, $action);

		if ($cacheKey) {
			$check = Cache::read($cacheKey, 'visualisation');
			if ($check !== false) {
				return $check == 1;
			}
		}

		$check = $this->Permission->check($aro, $aco, $action);
		if ($cacheKey) {
			Cache::write($cacheKey, $check ? 1 : 0, 'visualisation');
		}

		return $check;
	}

	public function allow($aro, $aco, $actions = "*", $value = 1) {
		if ($ret = $this->Permission->allow($aro, $aco, $actions, $value)) {
			Cache::clearGroup('Visualisation', 'visualisation');
		}

		return $ret;
	}

}
