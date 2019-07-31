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
			'animate',
			'main',
			'plugins.css?06062016',
			'responsive',
			'login',
			'icons',
			'fontawesome/font-awesome.min',
			'dropzone',
			'eramba.css?10012018',
			'community'
		);

		$jsFiles = array(
			'libs/jquery-1.10.2.min',
			'plugins/jquery-ui/jquery-ui-1.10.2.custom.min',
			'bootstrap.min',
			'libs/lodash.compat.min',
			'plugins/touchpunch/jquery.ui.touch-punch.min',
			'plugins/event.swipe/jquery.event.move',
			'plugins/event.swipe/jquery.event.swipe',
			'libs/breakpoints',
			'plugins/respond/respond.min',
			'plugins/cookie/jquery.cookie.min',
			'plugins/slimscroll/jquery.slimscroll.min',
			'plugins/slimscroll/jquery.slimscroll.horizontal.min',
			'plugins/nestable/jquery.nestable.min',
			'plugins/sparkline/jquery.sparkline.min',
			'plugins/flot/jquery.flot.min',
			'plugins/flot/jquery.flot.tooltip.min',
			'plugins/flot/jquery.flot.resize.min',
			'plugins/flot/jquery.flot.time.min',
			'plugins/flot/jquery.flot.orderBars.min',
			'plugins/flot/jquery.flot.pie.min',
			'plugins/flot/jquery.flot.growraf.min',
			'plugins/easy-pie-chart/jquery.easy-pie-chart.min',
			'plugins/daterangepicker/moment.min',
			'plugins/daterangepicker/daterangepicker',
			'plugins/blockui/jquery.blockUI.min',
			'plugins/fullcalendar/fullcalendar.min',
			'plugins/noty/jquery.noty',
			'plugins/noty/layouts/top',
			'plugins/noty/themes/default',
			'plugins/uniform/jquery.uniform.min',
			'plugins/select2/select2.min',
			'plugins/fileinput/fileinput',
			'plugins/tagsinput/jquery.tagsinput.min',
			'plugins/nprogress/nprogress',
			'plugins/bootbox/bootbox.min',
			'plugins/validation/jquery.validate.min',
			'plugins/bootstrap-wizard/jquery.bootstrap.wizard.min',
			'plugins/bootstrap-switch/bootstrap-switch.min',
			'plugins/bootstrap-multiselect/bootstrap-multiselect',
			'plugins/bootstrap-colorpicker/bootstrap-colorpicker',
			'plugins/autosize/jquery.autosize.min',
			'plugins/inputlimiter/jquery.inputlimiter.min',
			'AutoComplete.auto-complete-new',
			'AutoComplete.auto-complete-associated',
			'CustomValidator.custom-validator',
			'dropzone',
			'app',
			'plugins',
			'plugins.form-components',
			'custom',
			'default',
			'eramba.js?25062017'
		);

		echo $this->Html->css($cssFiles);
		echo $this->Html->script($jsFiles);

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>

	<style type="text/css">
	/*.menu-organization,
	.menu-assets,
	.menu-controls,
	.menu-risk,
	.menu-compliance,
	.menu-security,
	.menu-system {
		border-bottom-width: 4px;
		border-bottom-style: solid;
		border-color: transparent;
		margin-bottom: -4px;
	}*/
	.menu-controls:after {
		background-color: <?php echo COLOR_CONTROLS; ?>;
	}
	.menu-risk:after {
		background-color: <?php echo COLOR_RISK; ?>;
	}
	.menu-compliance:after {
		background-color: <?php echo COLOR_COMPLIANCE; ?>;
	}
	.menu-security:after {
		background-color: <?php echo COLOR_SECURITY; ?>;
	}
	<?php if(defined('CUSTOM_LOGO') && CUSTOM_LOGO): ?>
		@media only screen and (max-width: 1400px) {
			#logo img {
				content: url(<?php echo $this->Eramba->getCustomLogoUrl(true); ?> );
			}
		}
	<?php endif; ?>
	</style>

	<?php if (isset($showId) && !empty($showId)) : ?>
		<script type="text/javascript">
		//<![CDATA[
		jQuery(function($) {
			var showId = <?php echo (int) $showId; ?>;
			$(".ajax-show[data-itemid=" + showId + "]").trigger("click");
		});
		//]]>
		</script>
	<?php endif; ?>

	<script type="text/javascript">
	//<![CDATA[
		jQuery(function($) {
			Eramba.debug = <?php echo Configure::read('debug'); ?>;
			Eramba.Ajax.currentIndex = "<?php echo str_replace('&amp;', '&', addslashes(Purifier::clean(Router::reverse($this->params), 'Strict'))); ?>";

			<?php if (isset($pushState)) : ?>
				Eramba.Ajax.UI.initPushState = <?php echo json_encode($pushState); ?>;
			<?php endif; ?>

			Eramba.locale = {
				errorTitle: "<?php echo __('Something went wrong'); ?>",
				code: "<?php echo __('Code'); ?>",
				message: "<?php echo __('Message'); ?>",
				requestUrl: "<?php echo __('Request URL'); ?>",
				error403: "<?php echo __('Your session probably expired and you have been logged out of the application.'); ?>",
				errorHuman: "<?php echo __('Error occured and the request failed.<br />Enable debug mode if you want more information.<br />If this problem persist, contact the support.'); ?>",
				tryAgain: "<?php echo __('Please try again or <span>reload the page</span>.'); ?>"
			};

			Eramba.init();
		});
	//]]>
	</script>
</head>
<body>
	<?php
		if ($userJustLogged && !$systemHealthData && isAdmin($logged)) {
			echo $this->element(CORE_ELEMENT_PATH . 'system_health');
		}
		elseif ($userJustLogged && !empty($autoUpdatePending) && isAdmin($logged)) {
			echo $this->element(CORE_ELEMENT_PATH . 'auto_update_pending');
		}
		elseif (Configure::read('debug') > 0) {
			echo $this->element(CORE_ELEMENT_PATH . 'debug_enabled');
		}

		// remove CRON cache if rendering comes here
		// if ($this->params['controller'] == 'cron') {
		// 	if ($this->params['action'] == 'daily' || $this->params['action'] == 'yearly') {
		// 		$m = ClassRegistry::init('Cron');
		// 		$m->setCronTaskAsRunning($this->params['action'], false);
		// 	}
		// } 
	?>

	<?php
	echo $this->Ux->renderFlash();
	?>

	<?php
	echo $this->element($layout_headerPath);
	?>

	<?php echo $this->element( CORE_ELEMENT_PATH . 'sidebar' ); ?>

	<div id="container" class="sidebar-closed">

		<div id="content">

			<div class="container">

				<?php
				echo $this->element($layout_toolbarPath);
				?>

				<?php if (isset($AclNotAllowed) && $AclNotAllowed) : ?>

					<?php
					echo $this->Html->div(
						'alert alert-danger fade in',
						'<i class="icon-exclamation-sign"></i> ' . __('You don\'t have a permission to view this page.'), array(
							'style' => 'margin-top: 20px;'
						)
					);
					?>

				<?php else : ?>

					<?php echo $this->element($layout_pageHeaderPath); ?>

					<div id="main-content">
						<?php echo $this->fetch( 'content' ); ?>
					</div>

				<?php endif; ?>

			</div>

		</div>

	</div>

	<?php
	if (Configure::read('debug')) {
		echo $this->element('toolbar/debug');
	}
	?>

</body>
</html>
