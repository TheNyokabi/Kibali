<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>

					<?php /*
					<button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i><?php echo __('Workflow'); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right" style="text-align: left;">
						<li>
							<?php
							echo $this->Html->link(__('Business Continuity Plan'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$workflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Business Continuity Task'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$taskWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>

				<div class="btn-group group-merge">
					<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __( 'Actions' ); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-left" style="text-align: left;">
						<li><a href="<?php echo Router::url( array( 'controller' => 'businessContinuityPlans', 'action' => 'export' ) ); ?>"><i class="icon-file"></i> <?php echo __( 'Export Task Reminders CSV' ); ?></a></li>
						<li><a href="<?php echo Router::url( array( 'controller' => 'businessContinuityPlans', 'action' => 'exportAudits' ) ); ?>"><i class="icon-file"></i> <?php echo __( 'Export Audits CSV' ); ?></a></li>
					</ul>
				</div>

				<?php
				echo $this->NotificationSystem->getIndexLink(array(
					'BusinessContinuityPlan' => __('Business Continuity Plan'),
					'BusinessContinuityTask' => __('Business Continuity Task')
				));
				?>

				<?php echo $this->Video->getVideoLink('BusinessContinuityPlan'); ?>
			</div>
		</div>
	</div>
</div>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'BusinessContinuityPlan')); ?>

<div class="row">
	<div class="col-md-12">

		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				/*$extra_class = '';
				if ( $extra_class != 'widget-header-alert' ) {
					if ( $extra_class != 'widget-header-warning' ) {
						if ( ! $entry['BusinessContinuityPlan']['audits_all_done'] ) {
							$extra_class = 'widget-header-warning';
						}
					}

					if ( ! $entry['BusinessContinuityPlan']['audits_last_passed'] ) {
						$extra_class = 'widget-header-alert';
					}

					if ($entry['BusinessContinuityPlan']['audits_improvements']) {
						$extra_class = 'widget-header-improvement';
					}
				}*/

				$widgetClass = $this->BusinessContinuityPlans->getHeaderClass($entry, 'BusinessContinuityPlan');
				?>
				<div class="widget box widget-closed <?php echo $widgetClass; ?>" data-id="<?php echo $entry['BusinessContinuityPlan']['id']; ?>">
					<div class="widget-header">
						<h4><?php echo $entry['BusinessContinuityPlan']['title']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php

									$taskUrl = array(
										'controller' => 'businessContinuityTasks',
										'action' => 'add',
										$entry['BusinessContinuityPlan']['id']
									);

									$this->Ajax->addToActionList(__('Add New Task on Plan'), $taskUrl, 'plus-sign', 'add');

									if ($entry['BusinessContinuityPlan']['security_service_type_id'] != SECURITY_SERVICE_DESIGN){

										$auditsUrl = array(
											'controller' => 'businessContinuityPlanAudits',
											'action' => 'index',
											$entry['BusinessContinuityPlan']['id']
										);

										$this->Ajax->addToActionList(__('Audits'), $auditsUrl, 'search', 'index');
									}

									$exportCsvUrl = array(
										'controller' => 'businessContinuityPlans',
										'action' => 'exportTask',
										$entry['BusinessContinuityPlan']['id']
									);

									$this->Ajax->addToActionList(__('Export CSV'), $exportCsvUrl, 'file', false);

									$exportUrl = array(
										'controller' => 'businessContinuityPlans',
										'action' => 'exportPdf',
										$entry['BusinessContinuityPlan']['id']
									);

									$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

									echo $this->Ajax->getActionList($entry['BusinessContinuityPlan']['id'], array(
										'notifications' => $notificationSystemEnabled,
										'item' => $entry
									));
								?>
							</div>
						</div>
					</div>
					<div class="widget-subheader">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The owner of the plan is usually the individual that is held responsible for the plan management.' ); ?>'>
										<?php echo __( 'Owner' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The sponsor is usually the individual that needs the plan.' ); ?>'>
										<?php echo __( 'Sponsor' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The Launch Initiator is the person who is authorized to launch or declare the need for the plan.' ); ?>'>
										<?php echo __( 'Launch Initiator' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status for continiuty plans are: - "Last audit failed (red)" - when the last audit for this security service is tagged as "failed". A system record is generated on the security service when the audit was tagged as failed.
- "Last audit missing (yellow)" - when the last audit for this security service is incomplete. A system record is generated on the security service when the audit day arrived and the item was not edited.
- "Last maintenance missing (yellow)" - when the last maintenance for this security service is incomplete. A system record is generated on the security service when the maintenance day arrived and the item was not edited.
- "Ongoing Corrective Actions (blue)" - when the last audit of this service was tagged as failed and a project has been asociated. A system record is generated on the security service when the project is assigned to the failed audit.
- "Ongoing Security Incident (yellow)" - when a given securit service has a security incident with status open mapped. A system record is created when the incident has been mapped. The record has the incident title.
- "Design (yellow)" - when a given security service is in status "design". When the item is set to design or production a system record is generated stated if it changed to "design" or "production".' ); ?>'>
										<?php echo __( 'Status' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
							<?php /*
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Workflows define the approvals required to create, modify or delete an item. Approved items are valid throughout the system, Draft items require approval and Pending Approvals or Validations means that the workflow is still in process and is pending user interaction.' ); ?>'>
							<?php echo __( 'Workflows' ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							*/ ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?= $this->UserField->showUserFieldRecords($entry['Owner']); ?></td>
									<td><?= $this->UserField->showUserFieldRecords($entry['Sponsor']); ?></td>
									<td><?= $this->UserField->showUserFieldRecords($entry['LaunchInitiator']); ?></td>
									<td>
										<?php
										echo $this->BusinessContinuityPlans->getStatuses($entry, true);
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['BusinessContinuityPlan']['id'],
											'item' => $this->Workflow->getActions($entry['BusinessContinuityPlan'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</th>
							</tbody>
						</table>
					</div>
					<div class="widget-content" style="display:none;">

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The OPEX cost of the plan. This is useful for budget planning.' ); ?>'>
										<?php echo __( 'Opex' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The CAPEX cost of the plan. This is useful for budget planning.' ); ?>'>
										<?php echo __( 'Capex' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The amount of days required to keep the plan operative. For example, 4 people need to work on the plan at least 5 days to ensure is audited, operational, Etc. That would make 20 days of effort (in terms of cost).' ); ?>'>
										<?php echo __( 'Resource Utilization' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<!-- <th><?php echo __('Awareness Recurrence'); ?></th> -->
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo CakeNumber::currency( $entry['BusinessContinuityPlan']['opex'] ); ?></td>
									<td><?php echo CakeNumber::currency( $entry['BusinessContinuityPlan']['capex'] ); ?></td>
									<td><?php echo $entry['BusinessContinuityPlan']['resource_utilization']; ?></td>
									<!-- <td>
										<?php/*
										if (!$entry['BusinessContinuityPlan']['awareness_recurrence']) {
											echo __('No Notification');
										}
										else {
											echo $awareness_recurrences[$entry['BusinessContinuityPlan']['awareness_recurrence']];
										}
										*/?>
									</td> -->
								</th>
							</tbody>
						</table>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A descriptive criteria for this classification.' ); ?>'>
										<?php echo __( 'Objective' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['BusinessContinuityPlan']['objective']); ?></td>
								</th>
							</tbody>
						</table>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The criteria used to determine if the plan should be launched or not.' ); ?>'>
										<?php echo __( 'Launch Criteria' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['BusinessContinuityPlan']['launch_criteria']); ?></td>
								</th>
							</tbody>
						</table>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The metric used to audit the plan. For example: Time to provide continuity.' ); ?>'>
										<?php echo __( 'Audit Methodology' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The measurement of the metric must be validated against a criteria to determine if the audit pass or not. For example: Under 1hour.' ); ?>'>
										<?php echo __( 'Audit Success Criteria' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['BusinessContinuityPlan']['audit_metric']); ?></td>
									<td><?php echo $this->Eramba->getEmptyValue($entry['BusinessContinuityPlan']['audit_success_criteria']); ?></td>
								</th>
							</tbody>
						</table>


						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Continuity Plan Details' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if ( ! empty( $entry['BusinessContinuityTask'] ) ) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( ' In this plan, where this step goes? Example: 1, 4, 6, Etc.' ); ?>'>
										<?php echo __( 'Step' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'When following an emergency procedure, is important to know who does what in particular when! Example: no longer than 5 minutes after declared the crisis.' ); ?>'>
										<?php echo __( 'When' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Who is executing this task? This shoud be an individual, a group, Etc.' ); ?>'>
										<?php echo __( 'Who' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Select the user that is responsible for this task. This will be used to ensure he is notified at regular periods about his responsabilities on this plan.' ); ?>'>
										<?php echo __( 'Awareness Role' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Describe very briefly what is the task required to execute' ); ?>'>
										<?php echo __( 'Does What' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Where is the task executed?' ); ?>'>
										<?php echo __( 'Where' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'How is the task executed?' ); ?>'>
										<?php echo __( 'How' ); ?>
										<i class="icon-info-sign"></i>
										</div>
									</th>
									<th><?php echo __('Status') ?></th>
												<!-- <th><?php echo __( 'Awareness Status' ); ?></th> -->
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Use these icons in order to view the details of this object, system records such as when the item was created or modified, add or review comments or simply delete the item.' ); ?>'>
							<?php echo __( 'Actions' ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<?php /*
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Workflows define the approvals required to create, modify or delete an item. Approved items are valid throughout the system, Draft items require approval and Pending Approvals or Validations means that the workflow is still in process and is pending user interaction.' ); ?>'>
							<?php echo __( 'Workflows' ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							*/ ?>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $entry['BusinessContinuityTask'] as $task ) : ?>
											<tr>
												<td><?php echo $task['step']; ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($task['when']); ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($task['who']); ?></td>
												<td><?= $this->UserField->convertAndShowUserFieldRecords('BusinessContinuityTask', 'AwarenessRole', $task); ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($task['does']); ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($task['where']); ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($task['how']); ?></td>
												<td>
													<?php
													echo $this->BusinessContinuityTasks->getStatuses($task);
													?>
												</td>
												<!-- <td>
													<?php /*if ( empty( $task['BusinessContinuityTaskReminder'] ) ) : ?>

														<span class="label label-success bs-popover" data-html="true" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Status' ); ?>" data-content="<?php echo __( 'No Warning Sent.' ); ?>"><?php echo __( 'No Warning Sent.' ); ?></span>

													<?php elseif ( ! $task['BusinessContinuityTaskReminder'][0]['seen'] ) : ?>
														<?php $content = __( 'Warning sent: %s', $task['BusinessContinuityTaskReminder'][0]['created'] ); ?>
														<span class="label label-warning bs-popover" data-html="true" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Status' ); ?>" data-content="<?php echo $content; ?>"><?php echo __( 'Warning Sent.' ); ?></span>

													<?php elseif ( $task['BusinessContinuityTaskReminder'][0]['seen'] && ! $task['BusinessContinuityTaskReminder'][0]['acknowledged'] ): ?>

														<?php $content = __( 'Warning sent: %s<br>Clicked: %s', $task['BusinessContinuityTaskReminder'][0]['created'], $task['BusinessContinuityTaskReminder'][0]['modified'] ); ?>
														<span class="label label-warning bs-popover" data-html="true" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Status' ); ?>" data-content="<?php echo $content; ?>"><?php echo __( 'Clicked.' ); ?></span>

													<?php elseif ( $task['BusinessContinuityTaskReminder'][0]['acknowledged'] ): ?>

													<?php $content = __( 'Warning sent: %s<br>Acknowledged: %s', $task['BusinessContinuityTaskReminder'][0]['created'], $task['BusinessContinuityTaskReminder'][0]['modified'] ); ?>
														<span class="label label-success bs-popover" data-html="true" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Status' ); ?>" data-content="<?php echo $content; ?>"><?php echo __( 'Acknowledged.' ); ?></span>

													<?php endif;*/ ?>
												</td> -->
												<td class="align-center">
													<?php
													echo $this->Ajax->getActionList($task['id'], array(
														'style' => 'icons',
														'controller' => 'businessContinuityTasks',
														'model' => 'BusinessContinuityTask',
														'notifications' => true,
														'item' => $task,
														'history' => true,
													));
													?>
												</td>
												<?php /*
												<td class="text-center">
													<?php
													echo $this->element('workflow/action_buttons_1', array(
														'id' => $task['id'],
														'item' => $this->Workflow->getActions($task, $task['WorkflowAcknowledgement'])
													));
													?>
												</td>
												*/ ?>
											</tr>
											<?php endforeach ; ?>
										</tbody>
									</table>
								<?php else : ?>
									<?php echo $this->element( 'not_found', array(
										'message' => __( 'No Business Continuity Plan Tasks found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>

					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Business Continuity Plans found.' )
			) ); ?>
		<?php endif; ?>

	</div>

</div>

<script type="text/javascript">
jQuery(function($) {
<?php if ($openId !== null) : ?>
	var openId = <?php echo (int) $openId; ?>;
	$("[data-id=" + openId + "] .widget-collapse:first").trigger("click");
<?php endif; ?>
});
</script>
