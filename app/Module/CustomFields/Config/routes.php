<?php
Router::connect('/customForms/index/*', array('plugin' => 'customFields', 'controller' => 'customForms', 'action' => 'index'));
Router::connect('/customForms/delete/*', array('plugin' => 'customFields', 'controller' => 'customForms', 'action' => 'delete'));
Router::connect('/customForms/add/*', array('plugin' => 'customFields', 'controller' => 'customForms', 'action' => 'add'));
Router::connect('/customForms/edit/*', array('plugin' => 'customFields', 'controller' => 'customForms', 'action' => 'edit'));
Router::connect('/customForms/cancelAction/*', array('plugin' => 'customFields', 'controller' => 'customForms', 'action' => 'cancelAction'));

Router::connect('/customFields/delete/*', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'delete'));
Router::connect('/customFields/add/*', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'add'));
Router::connect('/customFields/edit/*', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'edit'));
Router::connect('/customFields/warning', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'warning'));
Router::connect('/customFields/saveOptions', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'saveOptions'));
Router::connect('/customFields/deleteOptions/*', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'deleteOptions'));
Router::connect('/customFields/getOptions/*', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'getOptions'));
Router::connect('/customFields/cancelAction/*', array('plugin' => 'customFields', 'controller' => 'customFields', 'action' => 'cancelAction'));

Router::connect('/customFieldSettings/edit/*', array('plugin' => 'customFields', 'controller' => 'customFieldSettings', 'action' => 'edit'));