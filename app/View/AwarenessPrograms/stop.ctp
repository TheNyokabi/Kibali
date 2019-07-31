<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('AwarenessProgramStop', array(
					'url' => array('controller' => 'awarenessPrograms', 'action' => 'stop', $this->data['AwarenessProgram']['id']),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));
				?>

				<div class="alert alert-info">
					<?php
					echo __('Are you sure you want to stop "%s"?', $this->data['AwarenessProgram']['title']);
					?>
					<br />
					<?php
					echo __('You might have emails on the mail queue for this awareness, please go to System / Settings / Mail Queue and search mails under the description: Awareness Training: %s of Awareness. Otherwise this emails will be sent out.', $this->data['AwarenessProgram']['title']);
					?>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit(__('Stop'), array(
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