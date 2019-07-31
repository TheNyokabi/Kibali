<?php echo $this->element(CORE_ELEMENT_PATH . 'errorMessage'); ?>
<?php
if (Configure::read('debug') > 0):
	if (!empty($message)) :
		echo '<h2>' . $message . '</h2>';
	endif;
	
	echo __d('cake', 'An Internal Error Has Occurred.');
	echo $this->element('exception_stack_trace');
endif;
?>

