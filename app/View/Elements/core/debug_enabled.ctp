<?php
if (Configure::read('Eramba.DISABLE_DEBUG_NOTIFICATION')) {
	return true;
}

$label = __('Warning: You are in Debug mode - click here to disable it');
?>
<script type="text/javascript">
	noty({
		text: '<strong><?php echo $label; ?></strong>',
		type: 'error',
		// buttons: true,		
		 template    : '<div class="noty_message noty_debug_warning"><span class="noty_text"></span><div class="noty_close"></div></div>',
		timeout: false,
		callback: {
			onShow: function() {
    			$(".noty_debug_warning").closest("li").css({width: "70%", left: "15%", position: "relative", opacity: 0.78});
			},
			onClose: function(){
				window.location = "<?php echo Router::url(array('controller' => 'settings', 'action' => 'edit', 'DEBUGCFG', 'admin' => false, 'plugin' => null)); ?>"
			}
		}
	});
</script>