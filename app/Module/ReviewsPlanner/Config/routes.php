<?php
Router::connect('/reviews/:action/*', array(
	'plugin' => null,
	'controller' => 'reviews'
));
Router::connect('/reviews/index/*', array(
	'plugin' => null,
	'controller' => 'reviews',
	'action' => 'index'
));