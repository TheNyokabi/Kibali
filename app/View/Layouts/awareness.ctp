<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<title>
		<?php 
		echo $title_for_layout .(!empty($title_for_layout) ? ' | ' : ''). (defined('NAME_SERVICE') ? NAME_SERVICE : DEFAULT_NAME);
		?>
	</title>
	<?php
		echo $this->Html->meta(
			'favicon.ico',
			'/favicon.png',
			array('type' => 'icon')
		);

		$cssFiles = array(
			'bootstrap.min',
			'fontawesome/font-awesome.min',
			'video-js',
			'awareness'
		);

		$jsFiles = array(
			'libs/jquery-1.10.2.min',
			'plugins/jquery-ui/jquery-ui-1.10.2.custom.min',
			'bootstrap.min',
			'video',
			'plugins/noty/jquery.noty',
			'plugins/noty/layouts/top',
			'plugins/noty/themes/default'
		);

		echo $this->Html->css( $cssFiles );
		echo $this->Html->script( $jsFiles );

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

	<link href='//fonts.googleapis.com/css?family=Montserrat:400,700|Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
</head>
<body>

	<?php
	echo $this->Ux->renderFlash();
	?>

	<div class="awareness-header">
		<div class="container">
			<div class="logo">
				<?php $awarenessUrl = Router::url(array('controller' => 'awareness', 'action' => 'index', 'admin' => false, 'plugin' => null)); ?>
				<a href="<?php echo $awarenessUrl; ?>">
					<?php echo $this->Eramba->getLogo(DEFAULT_LOGO_WHITE_URL); ?>
				</a>
			</div>
		</div>
	</div>

	<?php if ((isset($awarenessIndex) && $awarenessIndex) && !$noTrainings) : ?>
		<div class="awareness-subheader">
			<div class="container">
				<h2 class="awareness-title"><?php echo __('Trainings you need to do'); ?></h2>
			</div>
		</div>
	<?php endif; ?>

	
	<?php if (isset($noAwarenessContainer) && $noAwarenessContainer) : ?>
		<?php echo $this->fetch( 'content' ); ?>
	<?php else : ?>
		<div class="container main-container">
			<?php echo $this->fetch( 'content' ); ?>
		</div>
	<?php endif; ?>

</body>
</html>
