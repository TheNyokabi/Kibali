<div class="widget">
	<div class="widget-content">
		<div class="btn-toolbar">
			<div class="btn-group">
				<?php echo $this->Ajax->addAction(array('url' => array(
					'controller' => 'securityServiceAudits',
					'action' => 'add',
					$security_service_id
				))); ?>
			</div>
		</div>
	</div>
</div>

<?php echo $this->Ajax->setPagination(); ?>

<?php
echo $this->element('Visualisation.widget_header', [
	'manageBtn' => false
]);
?>
	
<div class="widget box widget-tabbable">
	<div class="widget-header">
		<h4>&nbsp;</h4>
	</div>
	<div class="widget-content">
		<div class="tabbable box-tabs box-tabs-styled">
			<ul class="nav nav-tabs">
				<?php foreach ($availableYears as $year) : ?>
					<?php
					$class = false;
					if ($currentYear == $year) {
						$class = 'active';
					}
					?>
					<li class="<?php echo $class; ?>">
						<?php
						echo $this->Html->link($year, array(
							'controller' => 'securityServiceAudits',
							'action' => 'index',
							$security_service_id,
							$year
						), array(
							'class' => 'audit-change-year'
						));
						?>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="tab_general">
					<?php if ( ! empty( $data ) ) : ?>
						<?php foreach ( $data as $entry ) : ?>
							<?php
							$widgetClass = $this->SecurityServiceAudits->getWidgetHeaderClass($entry);
							?>
							<div class="widget box widget-closed <?php echo $widgetClass; ?>">
								<div class="widget-header">
									<h4>
										<?php
										echo $this->SecurityServiceAudits->getWidgetHeader($entry);
										?>
									</h4>
									<div class="toolbar no-padding">
										<div class="btn-group">
											<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
											<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
												<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
											</span>
											<?php
											if ($entry['SecurityServiceAudit']['result'] == '0' && $entry['SecurityServiceAuditImprovement']['id'] == null) {

												$improvementLink = array(
													'controller' => 'securityServiceAuditImprovements',
													'action' => 'add',
													$entry['SecurityServiceAudit']['id']
												);
												$this->Ajax->addToActionList(__('Improve'), $improvementLink, 'check', 'add');
											}

											echo $this->Ajax->getActionList($entry['SecurityServiceAudit']['id'], array(
												// 'style' => 'icons',
												'notifications' => true,
												'item' => $entry,
												'history' => true,
											));
											?>
										</div>
									</div>
								</div>

								<div class="widget-content" style="display:none;">

									<table class="table table-hover table-striped table-bordered table-highlight-head">
										<thead>
											<tr>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('Name this Security Service (Firewalls, CCTV, Etc)'); ?>'>

														<?php echo __('Control Name'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('The date where this audit was originally set to start.'); ?>'>

														<?php echo __('Planned Start'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('The date this audit actually started.'); ?>'>

														<?php echo __('Actual Start'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('The day the audit ended.'); ?>'>

														<?php echo __('End Date'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('If the audit pass or not.'); ?>'>

														<?php echo __('Result'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('The individual that executed the audit'); ?>'>

														<?php echo __('Owner'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('TBD'); ?>'>

														<?php echo __('Evidence Owner'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												<th>
													<?php echo __('Status'); ?>
												</th>
												<?php /*
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('Workflows define the approvals required to create, modify or delete an item. Approved items are valid throughout the system, Draft items require approval and Pending Approvals or Validations means that the workflow is still in process and is pending user interaction.'); ?>'>

														<?php echo __('Workflows'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
												*/ ?>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><?php echo $entry['SecurityService']['name']; ?></td>
												<td>
													<?php
													echo $this->Eramba->getEmptyValue($entry['SecurityServiceAudit']['planned_date']);
													?>
												</td>
												<td>
													<?php
													echo $this->Eramba->getEmptyValue($entry['SecurityServiceAudit']['start_date']);
													?>
												</td>
												<td>
													<?php
													echo $this->Eramba->getEmptyValue($entry['SecurityServiceAudit']['end_date']);
													?>
												</td>
												<td>
													<?php
													$options = array(
														0 => __( 'Fail' ),
														1 => __( 'Pass' )
													);
													$result = false;
													if (isset($options[$entry['SecurityServiceAudit']['result']])) {
														$result = $options[$entry['SecurityServiceAudit']['result']];
													}

													echo $this->Eramba->getEmptyValue($result);
													?>
												</td>
												<td>
													<?= $this->UserField->showUserFieldRecords($entry['AuditOwner']); ?>
												</td>
												<td>
													<?= $this->UserField->showUserFieldRecords($entry['AuditEvidenceOwner']); ?>
												</td>
												<td>
													<?php
													echo $this->SecurityServiceAudits->getStatuses($entry, 'SecurityServiceAudit');
													?>
												</td>
												<?php /*
												<td class="text-center">
													<?php
													echo $this->element('workflow/action_buttons_1', array(
														'id' => $entry['SecurityServiceAudit']['id'],
														'item' => $this->Workflow->getActions($entry['SecurityServiceAudit'], $entry['WorkflowAcknowledgement'])
													));
													?>
												</td>
												*/ ?>
											</tr>
										</tbody>
									</table>

									<table class="table table-hover table-striped table-bordered table-highlight-head">
										<thead>
											<tr>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('Controls will require regular audits. Describe in this field what will be the metric (and optionally the process to obtain it) to audit this control. For example: creeping privileges on the Active Directory. Compare the current list of employees against those listed in the Active Directory.'); ?>'>

														<?php echo __('Audit Metric'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<?php
												$extraClass = '';
												if ($entry['SecurityServiceAudit']['audit_metric_description'] != $entry['SecurityService']['audit_metric_description']) {
													$extraClass = 'highlight-cell';
												}
												?>
												<td class="<?php echo $extraClass; ?>">
													<?php
													echo $this->Eramba->getEmptyValue($entry['SecurityServiceAudit']['audit_metric_description']);
													?>
												</td>
											</tr>
										</tbody>
									</table>

									<table class="table table-hover table-striped table-bordered table-highlight-head">
										<thead>
											<tr>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('What the metric should display in order to consider the control audit successful. For example: No creeping privileges'); ?>'>

														<?php echo __('Metric Success Criteria'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<?php
												$extraClass = '';
												if ($entry['SecurityServiceAudit']['audit_success_criteria'] != $entry['SecurityService']['audit_success_criteria']) {
													$extraClass = 'highlight-cell';
												}
												?>
												<td class="<?php echo $extraClass; ?>">
													<?php
													echo $this->Eramba->getEmptyValue($entry['SecurityServiceAudit']['audit_success_criteria']);
													?>
												</td>
											</tr>
										</tbody>
									</table>

									<table class="table table-hover table-striped table-bordered table-highlight-head">
										<thead>
											<tr>
												<th>
													<div class="bs-popover"
														data-trigger="hover"
														data-placement="top"
														data-original-title="<?php echo __('Help'); ?>"
														data-content='<?php echo __('A brief description on what was observed while auditing. You can optionally add evidence as attachments.'); ?>'>

														<?php echo __('Conclusion'); ?>
														<i class="icon-info-sign"></i>
													</div>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<?php
													echo $this->Eramba->getEmptyValue($entry['SecurityServiceAudit']['result_description']);
													?>
												</td>
											</tr>
										</tbody>
									</table>

									<?php if ($entry['SecurityServiceAuditImprovement']['id'] != null) : ?>

										<table class="table table-hover table-striped table-bordered table-highlight-head">
											<thead>
												<tr>
													<th>
														<?php
														echo __('Control Name');
														?>
													</th>
													<th>
														<?php
														echo __('Planned Start');
														?>
													</th>
													<th>
														<?php
														echo __('Actual Start');
														?>
													</th>
													<th>
														<?php
														echo __('Result');
														?>
													</th>
													<th>
														<?php
														echo __('Conclusion');
														?>
													</th>
													<th>
														<?php
														echo __('Owner');
														?>
													</th>
													<th>
														<?php
														echo __('Actions');
														?>
													</th>
												</tr>
											</thead>
											<tbody>
												<tr class="improvement">
													<td><?php echo $entry['SecurityService']['name']; ?></td>
													<td><?php echo date('Y-m-d', strtotime($entry['SecurityServiceAuditImprovement']['created'])); ?></td>
													<td><?php echo date('Y-m-d', strtotime($entry['SecurityServiceAuditImprovement']['created'])); ?></td>
													<td><?php echo __('Correction'); ?></td>
													<td>
														<?php
														$projects = array();
														foreach ($entry['SecurityServiceAuditImprovement']['Project'] as $project) {
															$projects[] = $project['title'];
														}
														echo __('This is a correction project created to fix previous audit issues. The project name is %s', implode(', ', $projects));
														?>
													</td>
													<td><?php echo $entry['SecurityServiceAuditImprovement']['User']['full_name']; ?></td>
													<td class="align-center">
														<?php
														echo $this->Ajax->getActionList($entry['SecurityServiceAuditImprovement']['id'], array(
															'style' => 'icons',
															'notifications' => false,
															'model' => 'SecurityServiceAuditImprovement',
															'controller' => 'securityServiceAuditImprovements',
															'comments' => false,
															'records' => false,
															'attachments' => false
														));
														?>
													</td>
												</tr>
											</tbody>
										</table>

										<table class="table table-hover table-striped table-bordered table-highlight-head">
											<thead>
												<tr>
													<th>
														<?php
														echo __('Projects');
														?>
													</th>
													<th>
														<?php
														echo __('Security Incidents');
														?>
													</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<?php
														$projects = implode(
															', ',
															Hash::extract($entry, 'SecurityServiceAuditImprovement.Project.{n}.title')
														);
														
														echo $this->Eramba->getEmptyValue($projects);
														?>
													</td>
													<td>
														<?php
														$securityIncidents = implode(
															', ',
															Hash::extract($entry, 'SecurityServiceAuditImprovement.SecurityIncident.{n}.title')
														);
														
														echo $this->Eramba->getEmptyValue($securityIncidents);
														?>
													</td>
												</tr>
											</tbody>
										</table>

									<?php endif; ?>

									<?php
									echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
										'item' => $entry
									));
									?>
								</div>
							</div>
						<?php endforeach; ?>

						<?php echo $this->element('ajax-ui/pagination'); ?>
					<?php else : ?>
						<?php echo $this->element( 'not_found', array(
							'message' => __( 'No Audits found.' )
						) ); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(function($) {
		$(".audit-change-year").off("click.Eramba").on("click.Eramba", function(e) {
			e.preventDefault();
			if ($(this).parent("li").hasClass("active")) {
				return true;
			}
			
			Eramba.Ajax.blockEle($("#eramba-modal .modal-content"));

			$.ajax({
				type: "GET",
				url: $(this).prop("href")
			}).done(function(data) {
				Eramba.Ajax.unblockEle($("#eramba-modal .modal-content"));

				Eramba.Ajax.UI.insertModalData(data);
				// Eramba.Ajax.UI.modal.html(data);
				// Eramba.Ajax.UI.attachModalEvents();
			});
		});
	});
</script>