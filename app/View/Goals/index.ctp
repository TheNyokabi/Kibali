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
							echo $this->Html->link(__('Goal'), array(
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
							echo $this->Html->link(__('Performance Reviews'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$auditsWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>
				
				<?php echo $this->NotificationSystem->getIndexLink('Goal'); ?>

				<?php echo $this->Video->getVideoLink('Goal'); ?>

				<?php
				echo $this->CustomFields->getIndexLink(array(
					'Goal' => ClassRegistry::init('Goal')->label(['singular' => true]),
				));
				?>
			</div>
		</div>
	</div>
</div>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'Goal')); ?>

<div class="row">
	<div class="col-md-12">

		<?php if (!empty($data)) : ?>
			<?php foreach ($data as $item) : ?>
				<?php
				$widgetClass = $this->Goals->getHeaderClass($item, 'Goal');
				?>
				<div class="widget box widget-closed <?php echo $widgetClass; ?>">
					<div class="widget-header">
						<h4><?php echo __( 'Goal' ); ?>: <?php echo $item['Goal']['name']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
									$auditUrl = array(
										'controller' => 'goalAudits',
										'action' => 'index',
										$item['Goal']['id']
									);

									$this->Ajax->addToActionList(__('Performance Reviews'), $auditUrl, 'search', 'index');

									$exportUrl = array(
										'action' => 'exportPdf',
										$item['Goal']['id']
									);

									$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

									echo $this->Ajax->getActionList($item['Goal']['id'], array(
										'notifications' => true,
										'item' => $item
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
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The accountable individual for planning, monitoring and ultimately achieve this goal.' ); ?>'>

											<?php echo __( 'Goal Description' ); ?>
								        	<i class="icon-info-sign"></i>
								        </div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A brief description of the goal.' ); ?>'>

											<?php echo __( 'Owner' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Shows if the goal is currently applicable or not.' ); ?>'>

											<?php echo __( 'Status' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Current statuses for this Goal.' ); ?>'>

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
									<td><?php echo $this->Eramba->getEmptyValue($item['Goal']['description']); ?></td>
									<td><?php echo $item['Owner']['name'] . ' ' .$item['Owner']['surname']; ?></td>
									<td><?php echo getGoalStatuses($item['Goal']['status']); ?></td>
									<td>
										<?php
										echo $this->Goals->getStatuses($item, true);
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $item['Goal']['id'],
											'item' => $this->Workflow->getActions($item['Goal'], $item['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="widget-content" style="display:none;">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('The defiend metric to be collected in order to perform a review.'); ?>'>

													<?php echo __('Performance Metric'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('The criteria upon which the metric is tested in order to decide if the review passes or not.'); ?>'>

													<?php echo __('Success Criteria'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php
										echo $this->Eramba->getEmptyValue($item['Goal']['audit_metric']);
										?>
									</td>
									<td>
										<?php
										echo $this->Eramba->getEmptyValue($item['Goal']['audit_criteria']);
										?>
									</td>
								</tr>
							</tbody>
						</table>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Mapped Activities'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php
								$noMappedItems = true;
								$noMappedItems &= empty($item['SecurityService']);
								$noMappedItems &= empty($item['SecurityPolicy']);
								$noMappedItems &= empty($item['Risk']);
								$noMappedItems &= empty($item['ThirdPartyRisk']);
								$noMappedItems &= empty($item['BusinessContinuity']);
								$noMappedItems &= empty($item['Project']);
								?>
								<?php if (!$noMappedItems) : ?>
								<table class="table table-hover table-striped">
									<thead>
										<tr>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('The type of activity mapped to this goal. This could be a control, risk, policy, Etc.'); ?>'>

													<?php echo __('Type'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('The name of the activity'); ?>'>

													<?php echo __('Name'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('The status of the activity'); ?>'>

													<?php echo __('Status'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
										</tr>
									</thead>
									<tbody>

										<?php foreach ($item['SecurityService'] as $mapped) : ?>
											<tr>
												<td><?php echo __('Security Service'); ?></td>
												<td><?php echo $mapped['name']; ?></td>
												<td>
													<?php
													echo $this->SecurityServices->getStatuses($mapped);
													?>
												</td>
											</tr>
										<?php endforeach; ?>

										<?php foreach ($item['SecurityPolicy'] as $mapped) : ?>
											<tr>
												<td><?php echo __('Security Policy'); ?></td>
												<td><?php echo $mapped['index']; ?></td>
												<td>
													<?php
													echo $this->SecurityPolicies->getStatuses($mapped);
													?>
												</td>
											</tr>
										<?php endforeach; ?>

										<?php foreach ($item['Risk'] as $mapped) : ?>
											<tr>
												<td><?php echo __('Risk'); ?></td>
												<td><?php echo $mapped['title']; ?></td>
												<td>
													<?php
													echo $this->Risks->getStatuses($mapped, 'Risk');
													?>
												</td>
											</tr>
										<?php endforeach; ?>

										<?php foreach ($item['ThirdPartyRisk'] as $mapped) : ?>
											<tr>
												<td><?php echo __('Third Party Risk'); ?></td>
												<td><?php echo $mapped['title']; ?></td>
												<td>
													<?php
													echo $this->Risks->getStatuses($mapped, 'ThirdPartyRisk');
													?>
												</td>
											</tr>
										<?php endforeach; ?>

										<?php foreach ($item['BusinessContinuity'] as $mapped) : ?>
											<tr>
												<td><?php echo __('Business Impact Analysis'); ?></td>
												<td><?php echo $mapped['title']; ?></td>
												<td>
													<?php
													echo $this->Risks->getStatuses($mapped, 'BusinessContinuity');
													?>
												</td>
											</tr>
										<?php endforeach; ?>

										<?php foreach ($item['Project'] as $mapped) : ?>
											<tr>
												<td><?php echo __('Project'); ?></td>
												<td><?php echo $mapped['title']; ?></td>
												<td>
													<?php
													echo $this->Projects->getStatuses($mapped);
													?>
												</td>
											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
								<?php else : ?>
									<?php echo $this->element( 'not_found', array(
										'message' => __( 'No Mapped Activities found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Mapped Issues'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if (!empty($item['ProgramIssue'])) : ?>
								<table class="table table-hover table-striped">
									<thead>
										<tr>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('description'); ?>'>

													<?php echo __('Source'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('description'); ?>'>

													<?php echo __('Type'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
											<th>
												<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Help'); ?>"
													data-content='<?php echo __('description'); ?>'>

													<?php echo __('Status'); ?>
													<i class="icon-info-sign"></i>
												</div>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($item['ProgramIssue'] as $issue) : ?>
											<tr>
												<td><?php echo getProgramIssueSources($issue['issue_source']); ?></td>
												<td>
													<?php
													echo $this->ProgramIssues->getItemTypes($issue);
													?>
												</td>
												<td><?php echo getProgramIssueStatuses($issue['status']); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<?php else : ?>
									<?php echo $this->element( 'not_found', array(
										'message' => __( 'No Mapped Issues found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php
			echo $this->element('not_found', array(
				'message' => __('No Goals found.')
			));
			?>
		<?php endif; ?>

	</div>
</div>
