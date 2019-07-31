<?php if (isset($notificationProvided) && $notificationProvided) : ?>
	<div class="alert alert-info">
		<?php
		echo __('This feedback has already been provided.');
		?>
	</div>

	<?php
	return true;
	?>
<?php endif; ?>



<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Feedback'); ?></h4>
	</div>
	<div class="widget-content">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>
						<?php echo __('Section'); ?>
					</th>
					<th>
						<?php echo __('Object'); ?>
					</th>
					<th>
						<?php echo __('Actions'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<?php echo $modelLabel; ?>
					</td>
					<td>
						<?php echo $objectTitle; ?>
					</td>
					<td>
						<?php
						echo $this->Ajax->getActionList($notificationLogId, array(
							'style' => 'icons',
							'edit' => false,
							'trash' => false,
							'records' => false,
							'model' => 'NotificationSystemItemLog',
							'item' => $notificationLog
						));
						?>
					</td>
				</tr>
			</tbody>
		</table>

		<?php
		echo $this->Form->create('NotificationSystemItemFeedback', array(
			'url' => array('controller' => 'notificationSystem', 'action' => 'feedback', $notificationLogId),
			'class' => 'form-horizontal row-border',
			'novalidate' => true,
			'type' => 'file'
		));
		?>

		<div class="form-actions">
			<?php
			echo $this->Form->submit(__('Continue'), array(
				'class' => 'btn btn-primary',
				'div' => false
			));
			?>
			&nbsp;
			<?php
			echo $this->Html->link(__('Cancel'), $url, array(
				'class' => 'btn btn-inverse'
			));
			?>
		</div>

		<?php echo $this->Form->end(); ?>
	</div>
</div>



<!-- <script type="text/javascript">
jQuery(function($) {
	var formKey = <?php echo $formKey; ?>;

	$("#add-feedback-attachment").on("click", function(e) {
		e.preventDefault();
		addAttachment();
	});

	function addAttachment() {
		formKey++;

		$.ajax({
			type: "POST",
			dataType: "HTML",
			async: true,
			url: "/notificationSystem/addFeedbackAttachment",
			data: {formKey: formKey}
		})
		.done(function(html) {
			var ele = $(html);
			ele.appendTo("#feedback-attachments");
			$("[data-style=fileinput]:not(.file-input-loaded)").fileInput().addClass("file-input-loaded");
		});
	}
});
</script> -->