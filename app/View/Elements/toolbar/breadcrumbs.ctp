<ul id="breadcrumbs" class="breadcrumb">
	<?php
	$is_root = false;
	$rootLabel = __('Dashboard');
	if ( $this->params['controller'] == 'pages' && in_array($this->params['action'], array('dashboard', 'welcome')) ) {
		$is_root = true;
		if ($this->params['action'] == 'welcome') {
			$rootLabel = __('Welcome');
		}
	}
	?>
	<li class="<?php if ( $is_root )echo 'current'; ?>">
		<i class="icon-home"></i>
		<a href="<?php echo Router::url( array('controller' => 'pages', 'action' => 'dashboard', 'admin' => false, 'plugin' => null) ); ?>"><?php echo $rootLabel; ?></a>
	</li>
	<?php if ( ! $is_root ) : ?>
	<li class="current">
		<a href="#" title=""><?php echo $title_for_layout; ?></a>
	</li>
	<?php endif; ?>
</ul>