<?php
Router::connect('/importTool/upload/*', array('plugin' => 'importTool', 'controller' => 'importTool', 'action' => 'index'));
Router::connect('/importTool/preview', array('plugin' => 'importTool', 'controller' => 'importTool', 'action' => 'preview'));
Router::connect('/importTool/download-template/*', array('plugin' => 'importTool', 'controller' => 'importTool', 'action' => 'downloadTemplate'));