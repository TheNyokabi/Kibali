<?php
if (empty($autoUpdatePending) || !isAdmin($logged)) {
	return true;
}

$label = __('New updates are available for your application. Click for more details.');
?>
<script type="text/javascript">
if (typeof pendingUpdatesNotyShown == "undefined") {
	noty({
		text: '<strong><?php echo $label; ?></strong>',
		type: 'information',
		timeout: false,
		callback: {
			onClose: function(){
				window.location = "<?php echo Router::url(array('plugin' => null, 'controller' => 'updates', 'action' => 'index')); ?>"
			}
		}
	});
	var pendingUpdatesNotyShown = true;
}
</script>
