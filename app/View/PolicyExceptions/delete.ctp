<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('PolicyExceptionDelete', array(
					'url' => array('controller' => 'policyExceptions', 'action' => 'delete', $this->data['PolicyException']['id']),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));

				echo $this->Form->input('_delete', array(
					'type' => 'hidden',
					'value' => 1
				));
				?>

				<div class="alert alert-danger">
					<?php
					echo __('Are you really sure you want to delete item: <strong>%s</strong>?', $this->data['PolicyException']['title']);
					?>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit(__('Delete'), array(
						'class' => 'btn btn-danger',
						'div' => false
					)); ?>
					&nbsp;
					<?php echo $this->Ajax->cancelBtn('PolicyException');?>
				</div>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>