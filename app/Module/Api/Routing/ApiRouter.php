<?php
App::uses('Router', 'Routing');

/**
 * @package       Api.Routing
 */
class ApiRouter extends Router {

	/**
	 * Customized resource mapping to quickly allow having the same api/:controller name route with api as it is named ib the app.
	 * 
	 * @param  $mapResources Controller names in original format.
	 */
	public static function mapCustomResources($mapResources) {
		$plugin = 'api';
		$id = (Router::ID . '|' . Router::UUID);

		foreach ($mapResources as $name) {
			$urlName = $plugin . '_' . $name;

			foreach (Router::resourceMap() as $params) {
				$url = '/' . $plugin . '/' . $name . (($params['id']) ? '/:id' : '');

				Router::connect($url,
					array(
						'plugin' => $plugin,
						'controller' => $urlName,
						'action' => $params['action'],
						'[method]' => $params['method']
					),
					array_merge(
						array('id' => $id, 'pass' => array('id')),
						array()
					)
				);

			}

			self::addMappedResource($name);
		}
	}

	public static function addMappedResource($urlName) {
		parent::$_resourceMapped[] = $urlName;
	}

}