<?php echo $this->Ajax->setPagination(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-content">
				<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'BusinessContinuityPlanAudit')); ?>
				<?php if ( ! empty( $data ) ) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlan.title', __( 'Control Name' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlan.audit_metric_description', __( 'Audit Methodology' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlan.audit_success_criteria', __( 'Metric Success Criteria' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlanAudit.planned_date', __( 'Planned Start' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlanAudit.start_date', __( 'Actual Start' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlanAudit.end_date', __( 'Actual End' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlanAudit.result', __( 'Result' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'BusinessContinuityPlanAudit.result_description', __( 'Conclusion' ) ); ?></th>
								<th><?php echo $this->Paginator->sort( 'User.name', __( 'Owner' ) ); ?></th>
								<th class="align-center"><?php echo __( 'Action' ); ?></th>
								<?php /* <th class="text-center"><?php echo __('Workflow'); ?></th> */ ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td><?php echo $entry['BusinessContinuityPlan']['title']; ?></td>
									<?php
									$extraClass = '';
									if ($entry['BusinessContinuityPlanAudit']['audit_metric_description'] != $entry['BusinessContinuityPlan']['audit_metric']) {
										$extraClass = 'highlight-cell';
									}
									?>
									<td class="<?php echo $extraClass; ?>">
										<?php echo $this->Eramba->getEmptyValue($entry['BusinessContinuityPlanAudit']['audit_metric_description']); ?>
									</td>
									<?php
									$extraClass = '';
									if ($entry['BusinessContinuityPlanAudit']['audit_success_criteria'] != $entry['BusinessContinuityPlan']['audit_success_criteria']) {
										$extraClass = 'highlight-cell';
									}
									?>
									<td class="<?php echo $extraClass; ?>">
										<?php echo $this->Eramba->getEmptyValue($entry['BusinessContinuityPlanAudit']['audit_success_criteria']); ?>
									</td>
									<td><?php echo $entry['BusinessContinuityPlanAudit']['planned_date']; ?></td>
									<td><?php echo $entry['BusinessContinuityPlanAudit']['start_date']; ?></td>
									<td><?php echo $entry['BusinessContinuityPlanAudit']['end_date']; ?></td>
									<td>
										<?php
										$options = array(
											0 => __( 'Fail' ),
											1 => __( 'Pass' )
										);
										if ( isset( $options[ $entry['BusinessContinuityPlanAudit']['result'] ] ) ) {
											echo $options[ $entry['BusinessContinuityPlanAudit']['result'] ];
										}
										?>
									</td>
									<td class="break-word"><?php echo $this->Eramba->getEmptyValue($entry['BusinessContinuityPlanAudit']['result_description']); ?></td>
									<td><?php echo $entry['User']['name']; ?></td>
									<td class="align-center">
										<?php
										if ($entry['BusinessContinuityPlanAudit']['result'] == '0' && $entry['BusinessContinuityPlanAuditImprovement']['id'] == null) {

											$improvementLink = array(
												'controller' => 'businessContinuityPlanAuditImprovements',
												'action' => 'add',
												$entry['BusinessContinuityPlanAudit']['id']
											);
											$this->Ajax->addToActionList(__('Improve'), $improvementLink, 'check', 'add');
										}

										echo $this->Ajax->getActionList($entry['BusinessContinuityPlanAudit']['id'], array(
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
											'id' => $entry['BusinessContinuityPlanAudit']['id'],
											'item' => $this->Workflow->getActions($entry['BusinessContinuityPlanAudit'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
								<?php if ($entry['BusinessContinuityPlanAuditImprovement']['id'] != null) : ?>
									<tr class="improvement-item">
										<td><?php echo $entry['BusinessContinuityPlan']['title']; ?></td>
										<td><?php echo __('N/A'); ?></td>
										<td><?php echo __('N/A'); ?></td>
										<td><?php echo date('Y-m-d', strtotime($entry['BusinessContinuityPlanAuditImprovement']['created'])); ?></td>
										<td><?php echo date('Y-m-d', strtotime($entry['BusinessContinuityPlanAuditImprovement']['created'])); ?></td>
										<td><?php echo __('N/A'); ?></td>
										<td><?php echo __('Correction'); ?></td>
										<td>
											<?php
											$projects = array();
											foreach ($entry['BusinessContinuityPlanAuditImprovement']['Project'] as $project) {
												$projects[] = $project['title'];
											}
											echo __('This is a correction project created to fix previous audit issues. The project name is %s', implode(', ', $projects));
											?>
										</td>
										<td><?php echo $entry['BusinessContinuityPlanAuditImprovement']['User']['full_name']; ?></td>
										<td class="align-center">
											<?php
											echo $this->Ajax->getActionList($entry['BusinessContinuityPlanAuditImprovement']['id'], array(
												'style' => 'icons',
												'notifications' => false,
												'model' => 'BusinessContinuityPlanAuditImprovement',
												'controller' => 'businessContinuityPlanAuditImprovements',
												'comments' => false,
												'records' => false,
												'attachments' => false
											));
											?>
										</td>
										<td></td>
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