<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('AwarenessProgramClean', array(
					'url' => array('controller' => 'awarenessPrograms', 'action' => 'clean', $this->data['AwarenessProgram']['id']),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));
				?>

				<div class="alert alert-info">
					<?php
					echo __('Choose date interval during which records for the Awareness Program should be cleaned. Leave blank to clean all records.');
					?>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('From'); ?>:</label>
					<div class="col-md-4">
						<?php
						echo $this->Form->input('from', array(
							'type' => 'text',
							'label' => false,
							'div' => false,
							'class' => 'form-control datepicker'
						));
						?>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('To'); ?>:</label>
					<div class="col-md-4">
						<?php
						echo $this->Form->input('to', array(
							'type' => 'text',
							'label' => false,
							'div' => false,
							'class' => 'form-control datepicker'
						));
						?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit(__('Submit'), array(
						'class' => 'btn btn-primary',
						'div' => false
					)); ?>
					&nbsp;
					<?php echo $this->Html->link(__('Cancel'), array(
						'action' => 'index'
					), array(
						'class' => 'btn btn-inverse'
					)); ?>
				</div>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>