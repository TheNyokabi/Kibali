<?php
if (isset($ldapConnection) && $ldapConnection !== true) {
	echo $this->element('ldapConnectors/ldapConnectionStatus');
	return false;
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('AwarenessProgramUserDemo', array(
					'url' => array('controller' => 'awarenessPrograms', 'action' => 'demo', $awarenessProgramId),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));
				?>

				<div class="alert alert-info">
					<?php
					echo __('Choose one user from the list of users to test this course. Demo email will be sent to that user.');
					?>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('User'); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('email', array(
							'options' => $usersList,
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'empty' => __('Select a user')
						)); ?>
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