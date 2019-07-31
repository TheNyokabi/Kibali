<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'Goal', array(
							'url' => array( 'controller' => 'goals', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'Goal', array(
							'url' => array( 'controller' => 'goals', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li><a href="#tab_performance" data-toggle="tab"><?php echo __('Performance'); ?></a></li>
						<li><a href="#tab_activities" data-toggle="tab"><?php echo __('Activities'); ?></a></li>
						<li><a href="#tab_issues" data-toggle="tab"><?php echo __('Issues'); ?></a></li>

						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Name'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('The name of the goal'); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Owner'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('owner_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('The individual accountable for planning, monitoring and ultimately acheving this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'users',
											'action' => 'add'
										),
										'text' => __('Add User')
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Description'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('description', array(	
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('A brief description of the goal'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Status'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('status', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('Select the current status of this goal'); ?></span>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab_performance">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Metric'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('audit_metric', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('What and how evidence will be used and collected to determine if the goal has been achieved'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __(' Success Criteria'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('audit_criteria', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('How that metric will need to look in order to determine if it was achieved or not?'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Audit Calendar'); ?>:</label>
								<div class="col-md-5">
									<div id="audit-inputs-wrapper">
										<button class="btn add-dynamic" id="add-audit-calendar"><?php echo __('Add Date'); ?></button>
										<?php
										echo $this->Form->input('GoalAuditDate.99', array(
											'type' => 'hidden',
											'value' => ''
										));
										
										$formKey = 0;
										if (isset($this->data['GoalAuditDate']) && !empty($this->data['GoalAuditDate'])) {
											foreach ($this->data['GoalAuditDate'] as $key => $audit_date) {
												echo $this->element('ajax/audit_calendar_entry', array(
													'model' => 'GoalAuditDate',
													'formKey' => $key,
													'day' => $audit_date['day'],
													'month' => $audit_date['month'],
													'useNewCalendarConvention' => true
												));
												$formKey++;
											}
										}
										?>
									</div>
									<span class="help-block"><?php echo __('Select the months in the year where this audit must take place'); ?></span>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab_activities">
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Security Services'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('Goal.SecurityService', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Select controls that are being (or have been) developed and put into production to support the completion of this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'securityServices',
											'action' => 'add'
										),
										'text' => __('Add Security Service')
									));
									?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Asset Risks'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('Goal.Risk', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Select asset based risks that have emerged or have mitigated as part of this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'risks',
											'action' => 'add'
										),
										'text' => __('Add Risk')
									));
									?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Third Party Risks'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('Goal.ThirdPartyRisk', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Select third party based risks that have emerged or have mitigated as part of this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'thirdPartyRisks',
											'action' => 'add'
										),
										'text' => __('Add Third Party Risk')
									));
									?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Business Continuities'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('Goal.BusinessContinuity', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Select business risks that have emerged or have mitigated as part of this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'businessContinuities',
											'action' => 'add'
										),
										'text' => __('Add Business Continuity')
									));
									?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Projects'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('Goal.Project', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Select projects that have been createed to meet this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'projects',
											'action' => 'add'
										),
										'text' => __('Add Project')
									));
									?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Security Policies'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('Goal.SecurityPolicy', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Select Security Policies that have been developed and implemented as part of this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'securityPolicies',
											'action' => 'add'
										),
										'text' => __('Add Security Policy')
									));
									?>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab_issues">
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Program Issues'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('Goal.ProgramIssue', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									));
									?>
									<span class="help-block"><?php echo __('Select any issues that will be mitigated with the achievement of this goal'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'programIssues',
											'action' => 'add'
										),
										'text' => __('Add Issues')
									));
									?>
								</div>
							</div>
						</div>

						<?php
						echo $this->element('CustomFields.tabs_content');
						?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('Goal');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'Goal',
			'id' => isset($edit) ? $this->data['Goal']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	var formKey = <?php echo $formKey; ?>;
	<?php if ( ! $formKey ) : ?>
		//load_new_entry();
	<?php endif; ?>
	function load_new_entry() {
		formKey++;
		$.ajax({
			type: "POST",
			dataType: "html",
			async: true,
			url: "/goals/auditCalendarFormEntry",
			data: { formKey: formKey },
			beforeSend: function () {
			},
			complete: function (XMLHttpRequest, textStatus) {
			},
			success: function (data, textStatus) {
				$("#audit-inputs-wrapper").append(data);
			}
		});
	}

	$("#add-audit-calendar").on("click", function(e) {
		e.preventDefault();
		load_new_entry();
	});
});
</script>