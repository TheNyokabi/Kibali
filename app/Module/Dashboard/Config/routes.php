<?php
Router::connect('/dashboard', array(
	'plugin' => 'dashboard',
	'controller' => 'dashboard_kpis',
	'action' => 'user'
));

Router::connect('/dashboard/user', array(
	'plugin' => 'dashboard',
	'controller' => 'dashboard_kpis',
	'action' => 'user'
));

Router::connect('/dashboard/add/*', array(
	'plugin' => 'dashboard',
	'controller' => 'dashboard_kpis',
	'action' => 'add'
));

Router::connect('/dashboard/admin', array(
	'plugin' => 'dashboard',
	'controller' => 'dashboard_kpis',
	'action' => 'admin'
));

Router::connect('/dashboard/sync/*', array(
	'plugin' => 'dashboard',
	'controller' => 'dashboard_kpis',
	'action' => 'sync'
));

Router::connect('/dashboard/calendar', array(
	'plugin' => null,
	'controller' => 'pages',
	'action' => 'dashboard'
));
