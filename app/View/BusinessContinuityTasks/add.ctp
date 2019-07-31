<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'BusinessContinuityTask', array(
							'url' => array( 'controller' => 'businessContinuityTasks', 'action' => 'edit', $id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'BusinessContinuityTask', array(
							'url' => array( 'controller' => 'businessContinuityTasks', 'action' => 'add', $id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'business_continuity_plan_id', array(
							'type' => 'hidden',
							'value' => $id
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('Tasks'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">


							<?= $this->FieldData->inputs([
								$FieldDataCollection->AwarenessRole
							]); ?>

							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Plan Step' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'step', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'In this plan, where this step goes? Example: 1, 4, 6, Etc.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'When' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'when', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'When reading an emergency procedure, is important to know who does what in particular when! Example: no longer than 5 minutes after declared the crisis.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Who' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'who', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Who is executing this task? This shoud be an individual, a group, Etc.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Does Something' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'does', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Valid examples: Warms up engines, Starts passive DC infrastructure. There\'s no point in writting how in details that is to be done since you shouldnt expect someone to learn to do something while in the middle of an emergency' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Where' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'where', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Where is the task executed?' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'How' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'how', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'How is the task executed?' ); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('BusinessContinuityTask');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'BusinessContinuityTask',
			'id' => isset($edit) ? $this->data['BusinessContinuityTask']['id'] : null
		));
		?>
	</div>
</div>
