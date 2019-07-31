<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ProjectAchievement', array(
							'url' => array( 'controller' => 'projectAchievements', 'action' => 'edit', $id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ProjectAchievement', array(
							'url' => array( 'controller' => 'projectAchievements', 'action' => 'add', $project_id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<?php echo $this->Form->input( 'project_id', array(
					'type' => 'hidden',
					'value' => $project_id
				) ); ?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('Tasks'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<?= $this->FieldData->inputs([
								$FieldDataCollection->TaskOwner
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Task Deadline' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker',
										'default' => $lastAchievement
									) ); ?>
									<span class="help-block"><?php echo __( 'The deadline of the task' ); ?></span>

									<?php
									if ($lastAchievement !== false) {
										echo $this->Ux->getAlert(_('The last task on this project has a deadline set, we have included that on the field above. Most likely, you are looking at setting a deadline after that date.'), [
											'type' => 'info'
										]);
									}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'How completed is this task?' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'completion', array(
										'options' => $this->App->getPercentageOptions(),
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Percentage of completion of the task' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Task Order' ); ?>:</label>
								<div class="col-md-10">
									<?php
									$defaultOrder = 0;
									if ($lastOrder !== false) {
										$defaultOrder = $lastOrder;
									}

									// highest number of $order variable is reset($order) = first value
									// because the array is flipped
									if ($defaultOrder != reset($order)){
										// next possible ordering
										$defaultOrder += 1;
									}

									echo $this->Form->input('task_order', array(
										'options' => $order,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'default' => $defaultOrder
									)); ?>
									<span class="help-block"><?php echo __( 'The task number dictates the order in which tasks must be executed' ); ?></span>

									<?php
									if ($lastOrder !== false) {
										echo $this->Ux->getAlert(
											__(
												'It seems the next task will be number %d (there is a task with order %d already and no task with number greater than that).',
												$defaultOrder,
												$lastOrder
											),
											[
												'type' => 'info'
											]
										);
									}
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Description' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'A brief description of what the task goal is' ); ?></span>
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
					echo $this->Ajax->cancelBtn('ProjectAchievement');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ProjectAchievement',
			'id' => isset($edit) ? $this->data['ProjectAchievement']['id'] : null
		));
		?>
	</div>
</div>
