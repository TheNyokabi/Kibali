<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'welcome'));
	
	Router::redirect('/portal', array('plugin' => 'thirdPartyAudits', 'controller' => 'thirdPartyAudits', 'action' => 'index'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	// Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

	Router::connect('/reports/risk', array('controller' => 'riskReports', 'action' => 'index'));
	Router::connect('/reports/security-controls', array('controller' => 'securityControlReports', 'action' => 'index'));
	Router::connect('/reports/compliance', array('controller' => 'complianceReports', 'action' => 'index'));
	Router::connect('/reports/security-operations', array('controller' => 'securityOperationReports', 'action' => 'index'));

	Router::connect('/about', array('controller' => 'pages', 'action' => 'about'));
	Router::connect('/license', array('controller' => 'pages', 'action' => 'license'));
	Router::connect('/document-direct/*', array('controller' => 'policy', 'action' => 'documentDirect'));

	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/change-language/*', array('controller' => 'users', 'action' => 'changeLanguage'));

	Router::connect('/securityIncident/stage/:action/*', array('controller' => 'securityIncidentStagesSecurityIncidents'));

	Router::connect('/risk-appetites', array('plugin' => false, 'controller' => 'riskAppetites', 'action' => 'edit', 1));


/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * API Configuration
 */
	// Router::mapResources('security_incidents');
	// Router::parseExtensions('json');
	
/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
