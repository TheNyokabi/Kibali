<?php
/**
 * @package       Api.Config
 */

App::uses('ApiRouter', 'Api.Routing');

/**
 * mapResources specifies an array of (underscored) controller names under this module, without 'api_' prefix in the name, that can be used for API purposes.
 */
Configure::write('ApiModule', array(
	'mapResources' => array('security_incidents', 'security_incident_stages')
));