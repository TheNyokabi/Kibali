
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
								echo $this->Html->link(__('Project'), array(
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
								echo $this->Html->link(__('Achievement'), array(
									'controller' => 'workflows',
									'action' => 'edit',
									$achievementWorkflowSettingsId
								), array(
									'escape' => false
								));
								?>
							</li>
							<li>
								<?php
								echo $this->Html->link(__('Expense'), array(
									'controller' => 'workflows',
									'action' => 'edit',
									$expenseWorkflowSettingsId
								), array(
									'escape' => false
								));
								?>
							</li>
						</ul>
						*/ ?>
					</div>

					<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'Project'); ?>

					<?php
					echo $this->NotificationSystem->getIndexLink(array(
						'Project' => __('Project'),
						'ProjectAchievement' => __('Task'),
						'ProjectExpense' => __('Expense')
					));
					?>

					<?php echo $this->Video->getVideoLink('Project'); ?>

					<?php
					echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight');
					?>

					<?php
					echo $this->CustomFields->getIndexLink(array(
						'Project' => ClassRegistry::init('Project')->label(['singular' => true]),
					));
					?>
				</div>
			</div>
		</div>

	</div>

	<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'Project')); ?>


<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				$widgetClass = $this->Projects->getHeaderClass($entry, 'Project');
				?>
				<div class="widget box <?php echo $widgetClass; ?>">
					<div class="widget-header">
						<h4><?php echo $entry['Project']['title']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span data-project-id="<?php echo $entry['Project']['id']; ?>" class="btn btn-xs widget-collapse">
									<i class="icon-angle-up"></i>
								</span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
								$auditUrl = array(
									'controller' => 'projectAchievements',
									'action' => 'add',
									$entry['Project']['id']
								);

								$maintenanceUrl = array(
									'controller' => 'projectExpenses',
									'action' => 'add',
									$entry['Project']['id']
								);

								$this->Ajax->addToActionList(__('Add a task'), $auditUrl, 'plus-sign', 'add');
								$this->Ajax->addToActionList(__('Add an expense'), $maintenanceUrl, 'plus-sign', 'add');

								$exportUrl = array(
									'action' => 'exportPdf',
									$entry['Project']['id']
								);

								$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

								echo $this->Ajax->getActionList($entry['Project']['id'], array(
									'notifications' => $notificationSystemEnabled,
									'item' => $entry,
									'history' => true
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
										<?php echo __('ID'); ?>
									</th>
									<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Projects can be: Planned, Ongoing or Completed.' ); ?>'>
										<?php echo __( 'Status' ); ?>
											<i class="icon-info-sign"></i>
											</div>
									</th>
									<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The day the project starts' ); ?>'>
										<?php echo __( 'Start Date' ); ?>
											<i class="icon-info-sign"></i>
											</div>
									</th>
									<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The planned end date for this project'); ?>'>
										<?php echo __( 'Planned End' ); ?>
											<i class="icon-info-sign"></i>
											</div>
									</th>
									<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'This is the individual accountable for delivery of the project. Typically this is a project manager' ); ?>'>
										<?php echo __( 'Owner' ); ?>
											<i class="icon-info-sign"></i>
											</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Percentage of Project Completion' ); ?>'>
											<?php echo __( 'Completion' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Budget set when defining the project' ); ?>'>
											<?php echo __( 'Planned Budget' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Current budget. Budget updates are input manually.' ); ?>'>
											<?php echo __( 'Current Budget' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<?php echo __('Tag'); ?>
									</th>
									<th class="row-status">
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status can be: - "Over Budget (Red)" - when the current budget is higher than the set on the project. a system record is generated on the project showing both numbers. - "Expired Tasks (Yellow)" - when one or more tasks expiration date is in the past. a system record is generated on the project with the name of the task. - "Expired Project (Red)" - when the project deadline is in the past. a system record is generated when that happens.' ); ?>'>
											<?php echo __( 'Status' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
							<?php /*
							<th class="align-center row-workflow">
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
									<td><?php echo $entry['Project']['id']; ?></td>
									<td><?php echo $entry['ProjectStatus']['name']; ?></td>
									<td><?php echo $entry['Project']['start']; ?></td>
									<td><?php echo $entry['Project']['deadline']; ?></td>

									<td><?= $this->UserField->showUserFieldRecords($entry['Owner']); ?></td>

									<td>
										<?php
										$ultimate = $entry['Project']['ultimate_completion'];
										echo CakeNumber::toPercentage($ultimate, 0, array(
											'multiply' => true
										));
										?>
									</td>
									<td><?php echo CakeNumber::currency( $entry['Project']['plan_budget'] ); ?></td>
									<?php
									$expenses = 0;
									foreach ( $entry['ProjectExpense'] as $expense ) {
										$expenses += $expense['amount'];
									}
									?>
									<?php

									?>
									<td><?php echo CakeNumber::currency( $expenses )?></td>
									<td>
										<?php
										echo $this->Projects->getTags($entry);
										?>
									</td>
									<td>
										<?php
										echo $this->Projects->getStatuses($entry, true);
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['Project']['id'],
											'item' => $this->Workflow->getActions($entry['Project'])
										));
										?>
									</td>
									*/ ?>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="widget-content" style="display:none;">

						<?php
						echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
							'item' => $entry
						));
						?>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The objective of the project' ); ?>'>
	<?php echo __( 'Project Goal' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['Project']['goal']); ?></td>
								</th>
							</tbody>
						</table>

						<div class="widget box">
							<div class="widget-header">
								<h4><?php echo __( 'Project Tasks' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:block;">
								<?php if ( ! empty( $entry['ProjectAchievement'] ) ) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The owner of the task is the individual accountable and must ensure that the task is completed' ); ?>'>
	<?php echo __( 'Task Owner' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A breif task description' ); ?>'>
	<?php echo __( 'Description' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Teh defined deadline for this task' ); ?>'>
	<?php echo __( 'Deadline' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The percentage of completion for this task' ); ?>'>
	<?php echo __( 'Completion' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The order in which tasks are supposed to be executed' ); ?>'>
											<?php echo __( 'Task Order' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
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
											<?php foreach ( $entry['ProjectAchievement'] as $update ) : ?>
											<tr>
												<td><?= $this->UserField->convertAndShowUserFieldRecords('ProjectAchievement', 'TaskOwner', $update); ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($update['description']); ?></td>
												<td><?php echo $update['date'] ?></td>
												<td><?php echo CakeNumber::toPercentage( $update['completion'], 0 ); ?></td>
												<td><?php echo $update['task_order'] ?></td>
												<td class="align-center">
													<?php
													echo $this->Ajax->getActionList($update['id'], array(
														'style' => 'icons',
														'controller' => 'projectAchievements',
														'model' => 'ProjectAchievement',
														'notifications' => true,
														'item' => $update
													));
													?>
												</td>
												<?php /*
												<td class="text-center">
													<?php
													echo $this->element('workflow/action_buttons_1', array(
														'id' => $update['id'],
														'parentId' => $entry['Project']['id'],
														'item' => $this->Workflow->getActions($update),
														'currentModel' => 'ProjectAchievement'
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
										'message' => __( 'No Project Tasks found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>

						<div class="widget box">
							<div class="widget-header">
								<h4><?php echo __( 'Project Expenses' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:block;">
								<?php if ( ! empty( $entry['ProjectExpense'] ) ) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The amount of the expense' ); ?>'>
	<?php echo __( 'Amount' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A brief description of what was the expense all about' ); ?>'>
	<?php echo __( 'Description' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The day the expense was incurred' ); ?>'>
	<?php echo __( 'Date' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
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
											<?php foreach ( $entry['ProjectExpense'] as $expense ) : ?>
											<tr>
												<td><?php echo CakeNumber::currency( $expense['amount'] ); ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($expense['description']); ?></td>
												<td><?php echo $expense['date'] ?></td>
												<td class="align-center">
													<?php
													echo $this->Ajax->getActionList($expense['id'], array(
														'style' => 'icons',
														'controller' => 'projectExpenses',
														'model' => 'ProjectExpense',
														'item' => $expense,
														'notifications' => true
													));
													?>
												</td>
												<?php /*
												<td class="text-center">
													<?php
													echo $this->element('workflow/action_buttons_1', array(
														'id' => $expense['id'],
														'parentId' => $entry['Project']['id'],
														'item' => $this->Workflow->getActions($expense),
														'currentModel' => 'ProjectExpense'
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
										'message' => __( 'No Project expenses found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Associated Items'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php
								$noMappedItems = true;
								$noMappedItems &= empty($entry['Risk']);
								$noMappedItems &= empty($entry['ThirdPartyRisk']);
								$noMappedItems &= empty($entry['BusinessContinuity']);
								$noMappedItems &= empty($entry['SecurityService']);
								$noMappedItems &= empty($entry['SecurityPolicy']);
								$noMappedItems &= empty($entry['ComplianceManagement']);
								$noMappedItems &= empty($entry['DataAsset']);
								?>

								<?php if (!$noMappedItems) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the object where this project has been mapped.' ); ?>'>
	<?php echo __( 'Name' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
<th>
		<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The type of object where this project has been mapped. This can be: Risk, Security Service, Exception or Compliance Item' ); ?>'>
	<?php echo __( 'Type' ); ?>
		<i class="icon-info-sign"></i>
		</div>
</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $entry['Risk'] as $item ) : ?>
											<tr>
												<td><?php echo $item['title'] ; ?></td>
												<td><?php echo __('Asset Risk'); ?></td>
											</tr>
											<?php endforeach ; ?>
											<?php foreach ( $entry['ThirdPartyRisk'] as $item ) : ?>
											<tr>
												<td><?php echo $item['title']; ?></td>
												<td><?php echo __('Third Party Risk'); ?></td>
											</tr>
											<?php endforeach ; ?>
											<?php foreach ( $entry['BusinessContinuity'] as $item ) : ?>
											<tr>
												<td><?php echo $item['title']; ?></td>
												<td><?php echo __('Business Risk'); ?></td>
											</tr>
											<?php endforeach ; ?>
											<?php foreach ( $entry['SecurityService'] as $item ) : ?>
											<tr>
												<td><?php echo $item['name']; ?></td>
												<td><?php echo __('Security Service'); ?></td>
											</tr>
											<?php endforeach ; ?>
											<?php foreach ( $entry['SecurityPolicy'] as $item ) : ?>
											<?php
											$docLabel = __('%s Document', $item['SecurityPolicyDocumentType']['name']);
											?>
											<tr>
												<td><?php echo $item['index']; ?></td>
												<td><?php echo $docLabel; ?></td>
											</tr>
											<?php endforeach ; ?>
											<?php foreach ( $entry['ComplianceManagement'] as $item ) : ?>
											<tr>
												<td><?php echo $item['CompliancePackageItem']['name']; ?></td>
												<td><?php echo __('Compliance Item'); ?></td>
											</tr>
											<?php endforeach ; ?>
											<?php foreach ( $entry['DataAsset'] as $item ) : ?>
											<tr>
												<td><?php echo $item['description']; ?></td>
												<td><?php echo __('Data Asset'); ?></td>
											</tr>
											<?php endforeach ; ?>

											<?php foreach ( $entry['SecurityServiceAuditImprovement'] as $item ) : ?>
											<?php
											$text = __('Audit correction for "%s" Security Service', $item['SecurityServiceAudit']['SecurityService']['name']);
											?>
											<tr>
												<td><?php echo $text; ?></td>
												<td><?php echo __('Security Service Audit'); ?></td>
											</tr>
											<?php endforeach ; ?>

											<?php foreach ( $entry['BusinessContinuityPlanAuditImprovement'] as $item ) : ?>
											<?php
											$text = __('Audit correction for "%s" Business Continuity Plan', $item['BusinessContinuityPlanAudit']['BusinessContinuityPlan']['title']);
											?>
											<tr>
												<td><?php echo $text; ?></td>
												<td><?php echo __('Business Continuity Plan Audit'); ?></td>
											</tr>
											<?php endforeach ; ?>

										</tbody>
									</table>

								<?php else : ?>
									<?php echo $this->element( 'not_found', array(
										'message' => __( 'No Associated Items found.' )
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
				'message' => __( 'No Projects found.' )
			) ); ?>
		<?php endif; ?>


	</div>

</div>

<script type="text/javascript">
	jQuery(function($) {
		<?php if (isset($openId)) : ?>
			$("[data-project-id=<?php echo $openId; ?>]").trigger("click");
		<?php endif; ?>
	});
</script>
