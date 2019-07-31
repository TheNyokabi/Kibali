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
			'main',
			'plugins',
			'responsive',
			'login',
			'icons',
			'fontawesome/font-awesome.min',
			'eramba'
		);

		$jsFiles = array(
			'libs/jquery-1.10.2.min',
			'plugins/jquery-ui/jquery-ui-1.10.2.custom.min',
			'bootstrap.min',
			'libs/lodash.compat.min',
			'plugins/uniform/jquery.uniform.min',
			'plugins/select2/select2.min',
			'plugins/nprogress/nprogress',
			'login',
			'plugins/noty/jquery.noty',
			'plugins/noty/layouts/top',
			'plugins/noty/themes/default',
			'plugins.form-components',
			'default',
			'eramba'
		);

		echo $this->Html->css($cssFiles);
		echo $this->Html->script($jsFiles);

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
</head>
<body class="login">

	<?php
	echo $this->Ux->renderFlash();
	?>

	<div class="logo">
		<?php echo $this->Eramba->getLogo(DEFAULT_LOGO_URL); ?>
	</div>

	<?php echo $this->fetch( 'content' ); ?>

	<div class="footer">
		<?php echo $this->element(CORE_ELEMENT_PATH . 'loginFooter'); ?>
	</div>

</body>
</html>
