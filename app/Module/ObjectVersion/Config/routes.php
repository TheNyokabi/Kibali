<?php
Router::connect('/objectVersion/history/*', array('plugin' => 'objectVersion', 'controller' => 'objectVersion', 'action' => 'history'));
Router::connect('/objectVersion/restore/*', array('plugin' => 'objectVersion', 'controller' => 'objectVersion', 'action' => 'restore'));
Router::connect('/objectVersion/cancelAction/*', array('plugin' => 'objectVersion', 'controller' => 'objectVersion', 'action' => 'cancelAction'));