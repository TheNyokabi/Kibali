<?php 
App::uses('QueueTransport', 'Network/Email');
?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('AwarenessProgramStart', array(
					'url' => array('controller' => 'awarenessPrograms', 'action' => 'start', $this->data['AwarenessProgram']['id']),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));
				?>

				<div class="alert alert-info">
					<?php
					echo __('Are you sure you want to start "%s"?', $this->data['AwarenessProgram']['title']);
					?>
					<br />
					<?php
					echo __('All emails asociated with this awareness trainings are put on an email queue and flushed every hour by a daily cron. The cron pushes up to %s email, this means you need 10 hours to flush %s email. You can monitor the queue on System / Settings / Mail Queue.', QueueTransport::getQueueLimit(), (QueueTransport::getQueueLimit()*10));
					?>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit(__('Start'), array(
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