<?php
$label = __('This installation of eramba has a few issues in order to work properly. Check on the settings page under health monitor what could be the issues or click here.');
?>
<script type="text/javascript">
noty({
	text: '<strong><i class="icon-exclamation-sign"></i> <?php echo $label; ?></strong>',
	type: 'warning',
	timeout: false,
	callback: {
		onClose: function(){
			window.location = "<?php echo Router::url(array('plugin' => null, 'controller' => 'settings', 'action' => 'systemHealth')); ?>"
		}
	}
});
</script>
