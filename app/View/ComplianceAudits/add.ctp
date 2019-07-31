<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ComplianceAudit', array(
							'url' => array( 'controller' => 'complianceAudits', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ComplianceAudit', array(
							'url' => array( 'controller' => 'complianceAudits', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li><a href="#tab_notification_settings" data-toggle="tab"><?php echo __('Notification Settings'); ?></a></li>
						<li><a href="#tab_portal_settings" data-toggle="tab"><?php echo __('Portal Settings'); ?></a></li>

						<?php if (empty($edit)) : ?>
							<li><a href="#tab_audit_settings" data-toggle="tab"><?php echo __('Audit Settings'); ?></a></li>
						<?php endif; ?>
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
									<span class="help-block"><?php echo __('Give a name to this audit. For example: "May Audit for ISO 27001"'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Compliance Package' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'third_party_id', array(
										'options' => $compliance_packages,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one'),
										'disabled' => !empty($edit)
									) ); ?>
									<span class="help-block"><?php echo __('Choose from the available list of compliance packages the one you will use to audit. You will only see items here if you have previously imported a set of compliance requirements (see Compliance Management / Compliance Packages'); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Auditor'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('auditor_id', array(
										'options' => $users,
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('Select the user account that will take the role of auditor'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'users', 'action' => 'add'),'text' => 'Add User')); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Third Party Contact'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('third_party_contact_id', array(
										'options' => $users,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Select the user account that will will be your contact at the organisation you are auditing. This field is only used for notifications (such as reminders of deadlines, etc) and is not the user that will be asked to respond to your questions.'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'users', 'action' => 'add'),'text' => 'Add User')); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Audit Start Date'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('start_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									));
									?>
									<span class="help-block"><?php echo __('INFORMATIVE FIELD: When is this audit scheduled to start? This field is only informative.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Audit End Date'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('end_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									));
									?>
									<span class="help-block"><?php echo __('INFORMATIVE FIELD: When is this audit scheduled to end? This is used to trigger notifications to the third party contact defined above.'); ?></span>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab_notification_settings">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Use Default Template'); ?>:</label>
								<div class="col-md-10">
									<label class="checkbox">
										<?php
										echo $this->Form->input('use_default_template', array(
											'type' => 'checkbox',
											'label' => false,
											'div' => false,
											'class' => 'uniform customized-email-checkbox',
											'default' => true
										));
										?>
									</label>
									<span class="help-block"><?php echo __('OPTIONAL: eramba can send email notifications to the individuals you define as responsible for responding your questions (you define this on the next tab) - this option sets the content of that email. You can set your own message and subject (by unchecking this box) or use a default.'); ?></span>
								</div>
							</div>

							<div class="customized-email-wrapper" style="display:none;">
								<div class="form-group"></div>
								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('Email Subject'); ?>:</label>
									<div class="col-md-10">
										<?php
										echo $this->Form->input('email_subject', array(
											'label' => false,
											'div' => false,
											'class' => 'form-control'
										));
										?>
										<span class="help-block"><?php echo __('Define custom email subject.'); ?></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('Email Body'); ?>:</label>
									<div class="col-md-10">
										<?php
										echo $this->Form->input('email_body', array(
											'label' => false,
											'div' => false,
											'class' => 'form-control'
										));
										?>
										<span class="help-block">
											<?php
											echo __('Define custom email body.');
											echo $this->element(NOTIFICATION_SYSTEM_ELEMENT_PATH . 'macros_list');
											?>
										</span>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab_portal_settings">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Page Title'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('auditee_title', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('Those you set as auditees (the ones that will be asked to respond to your questions) will be presented with a portal, this options set the title for that portal.'); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Auditee Instructions'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('auditee_instructions', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('The portal includes a free text box where you can define whatever content you wish. Typically this is used for instructions (such as respond all questions, provide evidence if requested, Etc).'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Auditee recieves'); ?>:</label>
								<div class="col-md-10">
									<label class="checkbox">
										<?php echo $this->Form->input('auditee_notifications', array(
											'type' => 'checkbox',
											'label' => false,
											'div' => false,
											'class' => 'uniform',
											'default' => true
										)); ?>
										<?php echo __('Notifications'); ?>
									</label>

									<label class="checkbox">
										<?php echo $this->Form->input('auditee_emails', array(
											'type' => 'checkbox',
											'label' => false,
											'div' => false,
											'class' => 'uniform',
											'default' => true
										)); ?>
										<?php echo __('Emails'); ?>
									</label>
									<span class="help-block"><?php echo __('Every time the auditor provides a response, comment or attachment the auditor (defined on the first tab) will receive an email notification.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Auditor recieves'); ?>:</label>
								<div class="col-md-10">
									<label class="checkbox">
										<?php echo $this->Form->input('auditor_notifications', array(
											'type' => 'checkbox',
											'label' => false,
											'div' => false,
											'class' => 'uniform',
											'default' => true
										)); ?>
										<?php echo __('Notifications'); ?>
									</label>

									<label class="checkbox">
										<?php echo $this->Form->input('auditor_emails', array(
											'type' => 'checkbox',
											'label' => false,
											'div' => false,
											'class' => 'uniform',
											'default' => true
										)); ?>
										<?php echo __('Emails'); ?>
									</label>
									<span class="help-block"><?php echo __('Every time the auditee provides a response, comment or attachment the auditor (defined on the first tab) will receive an email notification.'); ?></span>

									<?php
									echo $this->Ux->getAlert(__('Emails might take up to 60 minutes to get sent from the email queue.'))
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Fields to display on each compliance item'); ?>:</label>
								<div class="col-md-10">
									<div class="form-group">
										<div class="col-md-12">
											<label class="checkbox">
												<?php echo $this->Form->input('show_analyze_title', array(
													'type' => 'checkbox',
													'label' => false,
													'div' => false,
													'class' => 'uniform',
													'default' => true,
													'error' => false
												)); ?>
												<?php echo __('Show title'); ?>
											</label>

											<label class="checkbox">
												<?php echo $this->Form->input('show_analyze_description', array(
													'type' => 'checkbox',
													'label' => false,
													'div' => false,
													'class' => 'uniform',
													'default' => true,
													'error' => false
												)); ?>
												<?php echo __('Show description'); ?>
											</label>

											<label class="checkbox">
												<?php echo $this->Form->input('show_analyze_audit_criteria', array(
													'type' => 'checkbox',
													'label' => false,
													'div' => false,
													'class' => 'uniform',
													'default' => true,
													'error' => false
												)); ?>
												<?php echo __('Show audit criteria'); ?>
											</label>

											<?php
											if ($this->Form->isFieldError('ComplianceAudit.show_analyze_title')) {
												echo $this->Form->error('ComplianceAudit.show_analyze_title');
											}
											?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-md-12">
											<label class="checkbox">
												<?php echo $this->Form->input('show_findings', array(
													'type' => 'checkbox',
													'label' => false,
													'div' => false,
													'class' => 'uniform',
													'default' => true,
													'error' => false
												));

												echo __('Display Findings to Auditee in PDF format.'); ?>
											</label>
										</div>
									</div>
									<span class="help-block"><?php echo __('The first three checkboxes determine which columns from the compliance package will be shown on the portal (at least one must be selected). As the audit takes place the auditor can create findings, this include a title, description, deadline, etc - the auditor can make those available to the auditee on the portal in PDF format.'); ?></span>
								</div>
							</div>

						</div>

						<?php if (empty($edit)) : ?>
							<div class="tab-pane fade" id="tab_audit_settings">
								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('Default Auditee'); ?>:</label>
									<div class="col-md-10">
										<?php
										$selected = array();
										if (isset( $this->request->data['Default_ComplianceAuditSetting']['Auditee']) && is_array($this->request->data['Default_ComplianceAuditSetting']['Auditee'])) {
											foreach ($this->request->data['Default_ComplianceAuditSetting']['Auditee'] as $entry) {
												$selected[] = $entry;
											}
										}
										?>
										<?php
										echo $this->Form->input('Default_ComplianceAuditSetting.Auditee', array(
											'options' => $auditee,
											'label' => false,
											'div' => false,
											'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
											'multiple' => true,
											'selected' => $selected
										));
										?>
										<span class="help-block"><?php echo __('Select one or more individuals that will be responsible for responding the questions set on this assessments (we call them auditees). Altough the settings here will apply to each question on your compliance package you can later fine-tune this settings by editing the audit (once saved)'); ?></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('Default Status'); ?>:</label>
									<div class="col-md-10">
										<?php
										echo $this->Form->input('Default_ComplianceAuditSetting.status', array(
											'options' => getComplianceAuditSettingStatuses(null, null, true),
											'label' => false,
											'div' => false,
											'class' => 'form-control',
											'empty' => __('None')
										));
										?>
										<span class="help-block"><?php echo __('OPTIONAL: if you are interested in keeping track of evidence you can use this setting setting it to the value "Waiting for Evidence". Every time you get evidence for a question you can change that status to "Evidence Provided". If you are not interested in using this tags simply set "No evidence Needed".'); ?></span>
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('Possible Answers'); ?>:</label>
									<div class="col-md-10">
										<?php
										echo $this->Form->input('Default_ComplianceAuditSetting.compliance_audit_feedback_profile_id', array(
											'options' => $feedbackProfiles,
											'label' => false,
											'div' => false,
											'class' => 'form-control',
											'empty' => __('None')
										));
										?>
										<span class="help-block"><?php echo __('If you want to define a set of predefined answers for the auditor to use (such as "Yes, we are compliant", "No we are not compliant", etc) you need to define them on the Third Party Audit / Settings option. If you havent done that yet, you need to cancel this form and start all over :)'); ?></span>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('ComplianceAudit');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ComplianceAudit',
			'id' => isset($edit) ? $this->data['ComplianceAudit']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
	$(".customized-email-checkbox").on("change", function(e) {
		var $customizedEmailWrapper = $('.customized-email-wrapper');
		
		if (!$(this).is(":checked")) {
			$customizedEmailWrapper.slideDown();
		}
		else {
			$customizedEmailWrapper.slideUp();
		}
	}).trigger("change");
</script>