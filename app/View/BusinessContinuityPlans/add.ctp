<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'BusinessContinuityPlan', array(
							'url' => array( 'controller' => 'businessContinuityPlans', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'BusinessContinuityPlan', array(
							'url' => array( 'controller' => 'businessContinuityPlans', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li><a href="#tab_audit" data-toggle="tab"><?php echo __('Audits'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Title' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'title', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'The name for this Continuity Plan' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Objective' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'objective', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Describe the plan objective, it should be something short and straightforward to understand' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Launch Criteria' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'launch_criteria', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Describe the criteria the plan initiator should use to trigger this continuity plan.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Owner
							]); ?>
							<?= $this->FieldData->inputs([
								$FieldDataCollection->Sponsor
							]); ?>
							<?= $this->FieldData->inputs([
								$FieldDataCollection->LaunchInitiator
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Cost (OPEX)' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'opex', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Describe the associated OPEX for of this Control' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Cost (CAPEX)' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'capex', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Describe the associated CAPEX for this Control' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Resource Utilization' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'resource_utilization', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'The amount of days required to keep the plan operative. For example, 4 people need to work on the plan at least 5 days to ensure is audited, operational, Etc. That would make 20 days of effort (in terms of cost).' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'security_service_type_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'bcp-type'
									) ); ?>
									<span class="help-block"><?php echo __( 'The plan can be either in "Design" or "Production" phases. If the plan is set to "Design" it will not be shown on the rest of the system and audits will not be available' ); ?></span>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_audit">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Audit Methodology' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'audit_success_criteria', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'audit-success-criteria'
									) ); ?>
									<span class="help-block"><?php echo __( 'Define how this continiuty plan will be tested at regular point in time.)' ); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Audit Success Metric Criteria' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'audit_metric', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'audit-metric'
									) ); ?>
									<span class="help-block"><?php echo __( 'What criteria will be used to determine if the plan worked or not.' ); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Audit Dates' ); ?>:</label>
								<div class="col-md-5">
									<div id="audit-inputs-wrapper">
										<button class="btn add-dynamic" id="add_service_audit_calendar"><?php echo __( 'Add Date' ); ?></button>
										<?php
											echo $this->Form->input('BusinessContinuityPlanAuditDate.99', array(
												'type' => 'hidden',
												'value' => ''
											));

											$formKey = 0;

											if (isset($this->data['BusinessContinuityPlanAuditDate']) && !empty($this->data['BusinessContinuityPlanAuditDate'])) {

												foreach ( $this->data['BusinessContinuityPlanAuditDate'] as $key => $audit_date ) {
													echo $this->element( 'ajax/audit_calendar_entry', array(
														'model' => 'BusinessContinuityPlanAuditDate',
														'formKey' => $key,
														'day' => $audit_date['day'],
														'month' => $audit_date['month'],
														'useNewCalendarConvention' => true
													) );
													$formKey++;
												}
											}
										?>
									</div>
									<span class="help-block"><?php echo __( 'Insert dates when this plan needs to be tested every year.' ); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>


				<!--
				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __( 'Awareness Recurrence' ); ?>:</label>
					<div class="col-md-10">
						<?php /*echo $this->Form->input( 'awareness_recurrence', array(
							'options' => $awareness_recurrences,
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'empty' => __('No Notifications')
						) );*/ ?>
					</div>
				</div> -->

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('BusinessContinuityPlan');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'BusinessContinuityPlan',
			'id' => isset($edit) ? $this->data['BusinessContinuityPlan']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$("#bcp-type").on("change", function(e) {
		var readonly = $("#audit-metric, #audit-success-criteria");
		var disabled = $("#add_service_audit_calendar");

		if ($(this).val() == <?php echo SECURITY_SERVICE_DESIGN; ?>) {
			readonly.prop('readonly', true);
			disabled.prop('disabled', true);
		}

		if ($(this).val() == <?php echo SECURITY_SERVICE_PRODUCTION; ?>) {
			readonly.prop('readonly', false);
			disabled.prop('disabled', false);
		}
	}).trigger("change");

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
			url: "/businessContinuityPlans/auditCalendarFormEntry",
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

	$("#add_service_audit_calendar").on("click", function(e) {
		e.preventDefault();
		load_new_entry();
	});
});
</script>
