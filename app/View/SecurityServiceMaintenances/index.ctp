<div class="widget">
	<div class="widget-content">
		<div class="btn-toolbar">
			<div class="btn-group">
				<?php echo $this->Ajax->addAction(array('url' => array(
					'controller' => 'securityServiceMaintenances',
					'action' => 'add',
					$security_service_id
				))); ?>
			</div>
		</div>
	</div>
</div>

<?php echo $this->Ajax->setPagination(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-content">
			
				<?php
				echo $this->element('Visualisation.widget_header', [
					'manageBtn' => false
				]);
				?>

				<?php if ( ! empty( $data ) ) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( ' Name this Security Service (Firewalls, CCTV, Etc)' ); ?>'>
								<?php echo $this->Paginator->sort( 'SecurityService.name', __( 'Control Name' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Controls require maintenance. Describe in this field what type of maintenance is required and how that task should be executed. For example: Oil must be changed at regular times on the auxiliary power supply engines in order for they to operate normally. This can be achieved by changing the oil on the engine. Follow the manual instructions.' ); ?>'>
								<?php echo $this->Paginator->sort( 'SecurityServiceMaintenance.maintenance_metric_description', __( 'Maintenance Task' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date where this maintenance was originally set to start.' ); ?>'>
								<?php echo $this->Paginator->sort( 'SecurityServiceAudit.planned_date', __( 'Planned Start' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date this maintenance actually started.' ); ?>'>
								<?php echo $this->Paginator->sort( 'SecurityServiceAudit.start_date', __( 'Actual Start' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The day the maintenance ended.' ); ?>'>
								<?php echo $this->Paginator->sort( 'SecurityServiceAudit.end_date', __( 'End Date' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If the maintenance pass or not.' ); ?>'>
								<?php echo $this->Paginator->sort( 'SecurityServiceAudit.result', __( 'Result' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A brief description on what was observed while executing the maintenance. You can optionally add evidence as attachments.' ); ?>'>
								<?php echo $this->Paginator->sort( 'SecurityServiceAudit.result_description', __( 'Conclusion' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The individual that executed the maintenance' ); ?>'>
								<?php echo $this->Paginator->sort( 'User.name', __( 'Owner' ) ); ?>
										<i class="icon-info-sign"></i>
										</div>
								</th>
								<th><?php echo  __('Status') ?></th>
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
									<td><?php echo $entry['SecurityService']['name']; ?></td>
									<?php
									$extraClass = '';
									if ($entry['SecurityServiceMaintenance']['task'] != $entry['SecurityService']['maintenance_metric_description']) {
										$extraClass = 'highlight-cell';
									}
									?>
									<td class="<?php echo $extraClass; ?>">
										<?php
										echo $this->Eramba->getEmptyValue($entry['SecurityServiceMaintenance']['task']);
										?>
									</td>
									<td><?php echo $entry['SecurityServiceMaintenance']['planned_date']; ?></td>
									<td><?php echo $entry['SecurityServiceMaintenance']['start_date']; ?></td>
									<td><?php echo $entry['SecurityServiceMaintenance']['end_date']; ?></td>
									<td>
										<?php
										$options = array(
											0 => __( 'Fail' ),
											1 => __( 'Pass' )
										);
										if ( isset( $options[ $entry['SecurityServiceMaintenance']['result'] ] ) ) {
											echo $options[ $entry['SecurityServiceMaintenance']['result'] ];
										}
										?>
									</td>
									<td>
										<?php
										echo $this->Eramba->getEmptyValue($entry['SecurityServiceMaintenance']['task_conclusion']);
										?>
									</td>
									<td>
										<?= $this->UserField->showUserFieldRecords($entry['MaintenanceOwner']); ?>
									</td>
									<td>
										<?php
										echo $this->SecurityServiceAudits->getStatuses($entry, 'SecurityServiceMaintenance');
										?>
									</td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($entry['SecurityServiceMaintenance']['id'], array(
											'style' => 'icons',
											'notifications' => true,
											'item' => $entry,
											'history' => true
										));
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['SecurityServiceMaintenance']['id'],
											'item' => $this->Workflow->getActions($entry['SecurityServiceMaintenance'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('No Maintenance found.')
					));
					?>
				<?php endif; ?>
				<?php echo $this->element('ajax-ui/pagination'); ?>
			</div>
		</div>
	</div>
</div>
