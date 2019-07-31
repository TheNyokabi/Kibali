<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
$timeout = 6000;
if (!empty($params['timeout'])) {
    $timeout = $params['timeout'];
}

?>
<script type="text/javascript">
	jQuery(function($) {
		// $(window).on("load", function(e) {
			function initFlash() {
				noty({
					text: '<strong><?php echo h($message); ?></strong>',
					type: 'information',
					timeout: <?php echo $timeout; ?>
				});
			}

			<?php if (!empty($params['renderTimeout'])) : ?>
				setTimeout(function() {
					initFlash();
				}, <?php echo $params['renderTimeout']; ?>);
			<?php else : ?>
				initFlash();
			<?php endif; ?>
		// });
	});
</script>