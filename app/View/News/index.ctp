<?php 
// debug($news);
?>
<?php if (!empty($errorMessage)) : ?>
	<div class="alert alert-danger fade in">
		<i class="icon-exclamation-sign"></i>
		<strong><?php echo __('Error'); ?>:</strong> <?php echo $errorMessage; ?>
	</div>
<?php endif; ?>

<?php if (!empty($news)) : ?>
	<?php foreach ($news as $message) : ?>
		<div class="widget box">
			<div class="widget-header">
				<h4><i class="icon-envelope"></i> <?php echo $message['title']; ?> | <?php echo date('d. m. Y', strtotime($message['date'])); ?></h4>
			</div>
			<div class="widget-content">
				<?php echo $message['content']; ?>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif; ?>