<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">
				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'SecurityService', array(
							'url' => array( 'controller' => 'securityServices', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'SecurityService', array(
							'url' => array( 'controller' => 'securityServices', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li><a href="#tab_audits" data-toggle="tab"><?php echo __('Audits'); ?></a></li>
						<li><a href="#tab_maintenances" data-toggle="tab"><?php echo __('Maintenances'); ?></a></li>

						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Name' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Name this Security Service (Firewalls, CCTV, Etc)' ); ?></span>
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
									<span class="help-block"><?php echo __( 'OPTIONAL: Give a brief description of what this services does.' ); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Documentation URL' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'documentation_url', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Insert the url where the documentation for this Service is located (Wiki Page, Etc)' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'security_service_type_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'security-service-type'
									) ); ?>
									<span class="help-block"><?php echo __( 'Design: is for controls that are not alive, controls in design are not shown in the other modules and audit methodologies and dates (on the next tab) can not be set. <br><br>Production: the control is functional. 

									' ); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Projects' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'SecurityService.Project', array(
										'options' => $projects,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select from the drop down one or more projects asociated with this security service. Projects are defined in Security Operations / Project Management.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'projects', 'action' => 'add'),'text' => 'Add Project')); ?>
								</div>
							</div>

							<!-- <div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Service Classification' ); ?>:</label>
								<div class="col-md-10">
									<?php /*echo $this->Form->input( 'service_classification_id', array(
										'options' => $classifications,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __( 'Choose one' )
									) );*/ ?>
									<span class="help-block"><?php echo __( 'Apply a classification to the control such as "Expensive", "Compliance Key", Etc.' ); ?></span>
								</div>
							</div> -->

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									$labels = null;
									$labelsArr = array();
									if (isset($this->request->data['Classification'])) {
										foreach ($this->request->data['Classification'] as $item) {
											$labelsArr[] = $item['name'];
										}

										$labels = implode(',', $labelsArr);
									}
									elseif (isset($this->request->data['SecurityService']['Classification']) && !empty($this->request->data['SecurityService']['Classification'])) {
										$labels = $this->request->data['SecurityService']['Classification'];
									}

									if (!empty($labels)) {
										$this->request->data['SecurityService']['Classification'] = $labels;
									}

									echo $this->Form->input('Classification', array(
										'type' => 'hidden',
										'label' => false,
										'div' => false,
										'class' => 'tags-classifications col-md-12 full-width-fix',
										'multiple' => true,
										'data-placeholder' => __('Add a tag')
									));
									?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Apply a tag to the control such as "Expensive", "Compliance Key" this can later be used to group or filter similar controls.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->ServiceOwner
							]); ?>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Collaborator
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Cost (OPEX)' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'opex', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Insert the amount of OPEX this control costs per year or if not applicable set the value to zero. With the use of filters this will allow you list all your controls and costs and therefore calculate budgets quicker.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'Same as above but for CAPEX.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'Input the time (in hours, days or whatever unit you find useful) that takes your team to keep this control audited (tested) and updated. This is important to keep again your budgets well organised.)' ); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Support Contracts' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'SecurityService.ServiceContract', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select any applicable Support Contracts for this Security Service.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'serviceContracts', 'action' => 'add'),'text' => 'Add Service Contract')); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Security Policy Items' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'SecurityService.SecurityPolicy', array(
										'options' => $security_policies,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select one or more security policies that are related to this control (remember that policies are defined under Security Services / Security Policies). Not having security policies mapped to controls is an indication that you might not be using eramba correctly.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'securityPolicies', 'action' => 'add'),'text' => 'Add Security Policy')); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_audits">

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Service Audit Calendar' ); ?>:</label>
								<div class="col-md-5">
									<div id="audit-inputs-wrapper" class="audit-dates-wrapper">
										<button class="btn add-dynamic" id="add_service_audit_calendar"><?php echo __( 'Add Date' ); ?></button>
										<?php
											echo $this->Form->input('SecurityServiceAuditDate.99', array(
												'type' => 'hidden',
												'value' => ''
											));

											$formKey = 0;
											if ( !empty( $this->request->data['SecurityServiceAuditDate']) ) {
												foreach ( $this->request->data['SecurityServiceAuditDate'] as $key => $audit_date ) {
													echo $this->element( 'ajax/audit_calendar_entry', array(
														'model' => 'SecurityServiceAuditDate',
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
									<span class="help-block"><?php echo __( 'OPTIONAL: Select the day/months in the year where this audit must take place. This settings will create audit entries for this calendar year, subsequent years will be generated automatically on the first of January. You may change this settings at any time.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Audit Methodology' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'audit_metric_description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'audit-metric-description'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: eramba assumes you will audit controls, but you may choose not to do so. <br>If that is your case simply input NA on this field. If you choose to audit controls ensure you describe what input (evidence), analysis and output (audit report) you will provide. Use this free text to set that information (this data can be sent as notifications to your control collaborators).' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Audit Success Criteria' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'audit_success_criteria', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'audit-success-criteria'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Describe in this field what is the expected result of the audit in order to call it a "Pass". If you choose not to audit controls, simply set NA.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->input($FieldDataCollection->AuditOwner, [
								'default' => ['User-' . ADMIN_ID]
							]); ?>

							<?= $this->FieldData->input($FieldDataCollection->AuditEvidenceOwner, [
								'default' => ['User-' . ADMIN_ID]
							]); ?>

						</div>
						<div class="tab-pane fade in" id="tab_maintenances">

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Maintenance Calendar' ); ?>:</label>
								<div class="col-md-5">
									<div id="maintenance-inputs-wrapper" class="audit-dates-wrapper">
										<button class="btn add-dynamic" id="add_service_maintenance_calendar"><?php echo __( 'Add Date' ); ?></button>
										<?php
											echo $this->Form->input('SecurityServiceMaintenanceDate.99', array(
												'type' => 'hidden',
												'value' => ''
											));

											$maintenanceFormKey = 0;
											if ( !empty( $this->request->data['SecurityServiceMaintenanceDate'] ) ) {
												foreach ( $this->request->data['SecurityServiceMaintenanceDate'] as $key => $audit_date ) {
													echo $this->element( 'ajax/audit_calendar_entry', array(
														'model' => 'SecurityServiceMaintenanceDate',
														'formKey' => $key,
														'field' => 'maintenance_calendar',
														'day' => $audit_date['day'],
														'month' => $audit_date['month'],
														'useNewCalendarConvention' => true
													) );
													$maintenanceFormKey++;
												}
											}
										?>
									</div>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select the months in the year where this maintenance must take place.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Maintenance Task' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'maintenance_metric_description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'maintenance-metric-description'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: this field describes what mantainances are required for this control. For example if you have an external generator you may want to review oil and fuel levels every month or so. If you choose not to perform mantainances simply set this field as NA".' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->input($FieldDataCollection->MaintenanceOwner, [
								'default' => ['User-' . ADMIN_ID]
							]); ?>
							
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
					echo $this->Ajax->cancelBtn('SecurityService');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'SecurityService',
			'id' => isset($edit) ? $this->data['SecurityService']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	$("#security-service-type").on("change", function(e) {
		var readonly = $("#audit-metric-description, #audit-success-criteria, #maintenance-metric-description");
		var disabled = $("#add_service_audit_calendar, #add_service_maintenance_calendar");

		if ($(this).val() == <?php echo SECURITY_SERVICE_DESIGN; ?>) {
			// readonly.prop('readonly', true);
			// disabled.prop('disabled', true);
		}

		if ($(this).val() == <?php echo SECURITY_SERVICE_PRODUCTION; ?>) {
			// readonly.prop('readonly', false);
			// disabled.prop('disabled', false);
		}
	}).trigger("change");

	var obj = $.parseJSON('<?php echo $this->Eramba->jsonEncode(array_values($classifications)); ?>');

	$('.tags-classifications').select2({
		tags: obj
	});

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
			url: "/securityServices/auditCalendarFormEntry",
			data: { formKey: formKey, model: 'SecurityServiceAuditDate'},
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

	var maintenanceFormKey = <?php echo $maintenanceFormKey; ?>;
	<?php if ( ! $maintenanceFormKey ) : ?>
		//load_new_maintenance_entry();
	<?php endif; ?>
	function load_new_maintenance_entry() {
		maintenanceFormKey++;
		$.ajax({
			type: "POST",
			dataType: "html",
			async: true,
			url: "/securityServices/auditCalendarFormEntry",
			data: { formKey: maintenanceFormKey, model: 'SecurityServiceMaintenanceDate' },
			beforeSend: function () {
			},
			complete: function (XMLHttpRequest, textStatus) {
			},
			success: function (data, textStatus) {
				$("#maintenance-inputs-wrapper").append(data);
			}
		});
	}

	$("#add_service_maintenance_calendar").on("click", function(e) {
		e.preventDefault();
		load_new_maintenance_entry();
	});
});
</script>
<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
