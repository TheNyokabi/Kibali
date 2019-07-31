<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'GoalAuditImprovement', array(
							'url' => array( 'controller' => 'goalAuditImprovements', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'GoalAuditImprovement', array(
							'url' => array( 'controller' => 'goalAuditImprovements', 'action' => 'add', $audit_id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );
						
						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Goal Name' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'goal_name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'disabled' => true,
										'default' => $goal_name
									) ); ?>
									<span class="help-block"><?php echo __( 'The name of the control for which an improvement is planned.' ); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Improvement Project' ); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('GoalAuditImprovement.Project', array(
										'options' => $projects,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('The name of the project created to correct this issue.'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'projects', 'action' => 'add'
										),
										'text' => __('Add Project')
									));
									?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Security Incidents'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('GoalAuditImprovement.SecurityIncident', array(
										'options' => $securityIncidents,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Map one or more security incidents for this improvement.'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'securityIncidents', 'action' => 'add'
										),
										'text' => __('Add Security Incident')
									));
									?>
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
					echo $this->Ajax->cancelBtn('GoalAuditImprovement');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>