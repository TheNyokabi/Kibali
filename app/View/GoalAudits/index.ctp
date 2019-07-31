<?php echo $this->Ajax->setPagination(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-content">
				<?php if ( ! empty( $data ) ) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( ' Name this Security Service (Firewalls, CCTV, Etc)' ); ?>'>
								<?php echo $this->Paginator->sort( 'Goal.name', __( 'Goal Name' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Controls will require regular audits. Describe in this field what will be the metric (and optionally the process to obtain it) to audit this control. For example: creeping privileges on the Active Directory. Compare the current list of employees against those listed in the Active Directory.' ); ?>'>
								<?php echo $this->Paginator->sort( 'GoalAudit.audit_metric', __( 'Performance Review Metric' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'What the metric should display in order to consider the control audit successful. For example: No creeping privileges' ); ?>'>
								<?php echo $this->Paginator->sort( 'GoalAudit.audit_criteria', __( 'Metric Success Criteria' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date where this audit was originally set to start.' ); ?>'>
								<?php echo $this->Paginator->sort( 'GoalAudit.planned_date', __( 'Planned Start' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date this audit actually started.' ); ?>'>
								<?php echo $this->Paginator->sort( 'GoalAudit.start_date', __( 'Actual Start' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The day the audit ended.' ); ?>'>
								<?php echo $this->Paginator->sort( 'GoalAudit.end_date', __( 'End Date' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If the audit pass or not.' ); ?>'>
								<?php echo $this->Paginator->sort( 'GoalAudit.result', __( 'Result' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A brief description on what was observed while auditing. You can optionally add evidence as attachments.' ); ?>'>
								<?php echo $this->Paginator->sort( 'GoalAudit.result_description', __( 'Conclusion' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The individual that executed the audit' ); ?>'>
								<?php echo $this->Paginator->sort( 'User.name', __( 'Owner' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>  <?php echo __('Status'); ?>
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
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td><?php echo $entry['Goal']['name']; ?></td>
									<?php
									$extraClass = '';
									if ($entry['GoalAudit']['audit_metric_description'] != $entry['Goal']['audit_metric']) {
										$extraClass = 'highlight-cell';
									}
									?>
									<td class="<?php echo $extraClass; ?>">
										<?php echo $this->Eramba->getEmptyValue($entry['GoalAudit']['audit_metric_description']); ?>
									</td>
									<?php
									$extraClass = '';
									if ($entry['GoalAudit']['audit_success_criteria'] != $entry['Goal']['audit_criteria']) {
										$extraClass = 'highlight-cell';
									}
									?>
									<td class="<?php echo $extraClass; ?>">
										<?php echo $this->Eramba->getEmptyValue($entry['GoalAudit']['audit_success_criteria']); ?>
									</td>
									<td><?php echo $entry['GoalAudit']['planned_date']; ?></td>
									<td><?php echo $entry['GoalAudit']['start_date']; ?></td>
									<td><?php echo $entry['GoalAudit']['end_date']; ?></td>
									<td>
										<?php
										$options = array(
											0 => __( 'Fail' ),
											1 => __( 'Pass' )
										);
										if ( isset( $options[ $entry['GoalAudit']['result'] ] ) ) {
											echo $options[ $entry['GoalAudit']['result'] ];
										}
										?>
									</td>
									<td class="break-word"><?php echo $this->Eramba->getEmptyValue($entry['GoalAudit']['result_description']); ?></td>
									<td><?php echo $entry['User']['name'] . ' ' . $entry['User']['surname']; ?></td>
									<td>
										<?php
										echo $this->GoalAudits->getStatuses($entry, 'GoalAudit');
										?>
									</td>
									<td class="align-center">
										<?php
										if ($entry['GoalAudit']['result'] == '0' && $entry['GoalAuditImprovement']['id'] == null) {

											$improvementLink = array(
												'controller' => 'goalAuditImprovements',
												'action' => 'add',
												$entry['GoalAudit']['id']
											);
											$this->Ajax->addToActionList(__('Improve'), $improvementLink, 'check', 'add');
										}

										echo $this->Ajax->getActionList($entry['GoalAudit']['id'], array(
											'style' => 'icons',
											'notifications' => true,
											'item' => $entry
										));
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['GoalAudit']['id'],
											'item' => $this->Workflow->getActions($entry['GoalAudit'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
								<?php if ($entry['GoalAuditImprovement']['id'] != null) : ?>
									<tr class="improvement-item">

										<td><?php echo $entry['Goal']['name']; ?></td>
										<td><?php echo __('N/A'); ?></td>
										<td><?php echo __('N/A'); ?></td>
										<td><?php echo date('Y-m-d', strtotime($entry['GoalAuditImprovement']['created'])); ?></td>
										<td><?php echo date('Y-m-d', strtotime($entry['GoalAuditImprovement']['created'])); ?></td>
										<td><?php echo __('N/A'); ?></td>
										<td><?php echo __('Correction'); ?></td>
										<td>
											<?php
											$projects = array();
											foreach ($entry['GoalAuditImprovement']['Project'] as $project) {
												$projects[] = $project['title'];
											}
											echo __('This is a correction project created to fix previous audit issues. The project name is %s', implode(', ', $projects));
											?>
										</td>
										<td><?php echo $entry['GoalAuditImprovement']['User']['full_name']; ?></td>
										<td>&nbsp;</td>
										<td class="align-center">
											<?php
											echo $this->Ajax->getActionList($entry['GoalAuditImprovement']['id'], array(
												'style' => 'icons',
												'notifications' => false,
												'model' => 'GoalAuditImprovement',
												'controller' => 'goalAuditImprovements',
												'comments' => false,
												'records' => false,
												'attachments' => false
											));
											?>
										</td>
										<td></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>

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