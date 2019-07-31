<?php
foreach ($crumbs as $key => $crumb) {
	$this->Html->addCrumb($crumb['name'], $crumb['link']);
}

echo $this->Html->getCrumbList([
	'separator' => '',
	'lastClass' => 'current',
	'id' => 'breadcrumbs',
	'class' => 'breadcrumb'
], [
    'text' => $this->Html->tag('i', '', ['class' => 'icon-home']) . __('Dashboard'),
    'url' => array('plugin' => null, 'controller' => 'pages', 'action' => 'dashboard', 'admin' => false),
    'escape' => false
]);
?>