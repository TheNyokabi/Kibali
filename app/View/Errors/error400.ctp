<?php
if ($error->getCode() == 403) {
	echo $error->getMessage();
	return true;
}

$this->set('errorView', true);
$this->set('hidePageHeader', true);
$this->set('title_for_layout', __('Not Found'));

$msg = __('Page you requested has not been found :-!');
$subMsg = __('Requested page is no longer available or wrong. Check the URL and if the problem persist, you can:');
if ($error->getCode() == 400) {
	$msg = __('We have blocked this request');
	$subMsg = __('This could have happened for a variety of reasons, for example you have refreshed this page multiple times, you have accessed a part of the system you have no access, Etc. Close the window and start again.<br /><br />If you can reproduce this message and you are sure is an error report it as a bug.');
}
?>
<div class="text-center error-msg">
	<h2><?php echo $msg; ?></h2>

	<p class="error">
		<?php
		$home = Router::url(array('controller' => 'pages', 'action' => 'welcome'));
		?>
		<?php echo  $subMsg ?>
	</p>
	<br />
	<p>
		<?php
		echo $this->Html->link(__('Go back'), 'javascript:window.history.back();', array(
			'class' => 'btn btn-primary'
		))
		?>
		&nbsp;
		<?php echo __('or'); ?>
		&nbsp;
		<?php
		echo $this->Html->link(__('Home'), array(
			'controller' => 'pages',
			'action' => 'welcome'
		), array(
			'class' => 'btn btn-primary'
		))
		?>
	</p>
</div>
<?php
if (Configure::read('debug') > 0): 
	if (!empty($message)) :
		echo '<h2>' . $message . '</h2>';
	endif;
	
	printf(
		__d('cake', 'The requested address %s was not found on this server.'),
		"<strong>'{$url}'</strong>"
	);
	echo $this->element('exception_stack_trace');
endif;
?>

