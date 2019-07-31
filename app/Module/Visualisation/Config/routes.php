<?php
/**
 * @package       Visualisation.Config
 */

App::uses('AppModule', 'Lib');

Router::connect('/visualisation/sync', array(
	'plugin' => 'visualisation',
	'controller' => 'visualisationSettings',
	'action' => 'sync'
));

foreach (AppModule::instance('Visualisation')->whitelist() as $model) {
	$c = controllerFromModel($model);

	Router::connect('/'.$c.'/visualisation/share', array(
		'plugin' => 'visualisation',
		'controller' => 'visualisation',
		'action' => 'share',
		$model
	));
}