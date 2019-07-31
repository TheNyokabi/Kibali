<?php
/**
 * @package       Api.Config
 */

Router::parseExtensions('json', 'xml');

$mapResources = Configure::read('ApiModule.mapResources');
ApiRouter::mapCustomResources($mapResources);