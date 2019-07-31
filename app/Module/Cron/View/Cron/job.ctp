<?php
echo $this->Html->css("Cron.cron.css");
?>

<div class="widget widget-cron">
	<?php if ($success === true) : ?>
		<div class="widget-header">
			<h4>
				<?php echo $this->Ux->getIcon('ok-sign'); ?> 
				<?php echo __('Success'); ?>
			</h4>
		</div>
		<div class="widget-content">
			<?php
			echo $this->Ux->getAlert(__('Your CRON Job has been completed successfully'), [
				'type' => 'success'
			]);
			?>
		</div>
	<?php else : ?>
		<div class="widget-header">
			<h4>
				<?php echo $this->Ux->getIcon('warning'); ?> 
				<?php echo __('Error'); ?>
			</h4>
		</div>
		<div class="widget-content">
			<?php
			echo $this->Ux->getAlert(__('There has been one or more issues while running the CRON'), [
				'type' => 'danger'
			]);

			echo $this->Html->nestedList($errors, [
				'class' => 'list-cron-issues'
			]);
			?>
		</div>
	<?php endif; ?>
</div>