<?php
$widgetClass = $this->SecurityIncidents->getHeaderClass($item, 'SecurityIncident');

$extraClass = 'widget-closed';
if (!empty($openStages)) {
	$extraClass = false;
}
?>
<div class="widget box <?php echo $extraClass; ?> <?php echo $widgetClass; ?>">
	<div class="widget-header">
		<h4><?php echo $item['SecurityIncident']['title']; ?></h4>
		<div class="toolbar no-padding">
			<div class="btn-group">
				<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
				<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
					<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
				</span>
				<?php
				$exportUrl = array(
					'action' => 'exportPdf',
					$item['SecurityIncident']['id']
				);

				$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

				echo $this->Ajax->getActionList($item['SecurityIncident']['id'], array(
					'notifications' => $notificationSystemEnabled,
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
						<?php echo __('ID'); ?>
					</th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is the individual in charge of managing the incident' ); ?>'>
							<?php echo __('Owner'); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
					<th>
						<?php echo __('Type'); ?>
					</th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is the individual that reported the incident, could be the same as the owner' ); ?>'>
							<?php echo __('Reporter'); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is the individual that has been affected by the incident. If there is no individual in particular, remember you can tag Thrid Parties and Assets.' ); ?>'>
							<?php echo __('Victim'); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>

					<th><?php echo __('Open Date'); ?></th>
					<th><?php echo __('Closure Date'); ?></th>
					<th><?php echo __('Tags'); ?></th>
					<th class="row-status">
							<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status for security incidents are: - "Open" - when the security incident is open. - "Lifecycle Incomplete" - when the lifecycle defined for all incidents have not been completed' ); ?>'>
						<?php echo __('Status'); ?>
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
					<td><?php echo $item['SecurityIncident']['id']; ?></td>
					<td><?= $this->UserField->showUserFieldRecords($item['Owner']); ?></td>
					<td>
						<?php
						echo getSecurityIncidentTypes($item['SecurityIncident']['type']);
						?>
					</td>
					<td><?php echo $item['SecurityIncident']['reporter']; ?></td>
					<td><?php echo $item['SecurityIncident']['victim']; ?></td>
					<td><?php echo $item['SecurityIncident']['open_date']; ?></td>
					<td><?php echo $item['SecurityIncident']['closure_date']; ?></td>
					<td>
						<?php
						$labels = array();
						foreach ($item['Classification'] as $label) {
							$labels[] = $this->Html->tag('span', $label['name'], array(
								'class' => 'label label-primary'
							));
						}

						echo implode(' ', $labels);
						?>
					</td>
					<td>
						<?php
						echo $this->SecurityIncidents->getStatuses($item, true);
						?>
					</td>
					<?php /*
					<td class="text-center">
						<?php
						echo $this->element('workflow/action_buttons_1', array(
							'id' => $item['SecurityIncident']['id'],
							'item' => $this->Workflow->getActions($item['SecurityIncident'], $item['WorkflowAcknowledgement']),

						));
						?>
					</td>
					*/ ?>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="widget-content" style=";">

		<?php if (!empty($item['SecurityIncident']['description'])) : ?>
			<table class="table table-hover table-striped table-bordered table-highlight-head">
				<thead>
					<tr>
						<th><?php echo __('Description'); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $this->Eramba->getEmptyValue($item['SecurityIncident']['description']); ?></td>
					</tr>
				</tbody>
			</table>
		<?php endif; ?>

		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __('Associated Risks'); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if (!empty($item['AssociatedRisks'])) : ?>
				<table class="table table-hover table-striped table-bordered table-highlight-head">
					<thead>
						<tr>
							<th>
								<?php echo __('Risk Type'); ?>
							</th>
							<th>
								<?php echo __('Name'); ?>
							</th>
							<th>
								<?php echo __('Containment Procedure'); ?>
							</th>
							<th>
								<?php echo __('Status'); ?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($item['AssociatedRisks'] as $risk) : ?>
						<tr>
							<td><?php echo getRiskTypes($risk['RisksSecurityIncident']['risk_type']); ?></td>
							<td><?php echo $risk['title']; ?></td>
							<td>
								<?php
								/*if (!empty($risk['Containment'])) {
									echo $risk['Containment'];
								}
								else {
									echo '-';
								}*/

								foreach ($risk['SecurityPolicy'] as $policy) : ?>

									<?php echo $policy['index']; ?>
									<?php echo $this->SecurityPolicies->documentLink($policy); ?>
									<br />
								<?php endforeach; ?>
								
							</td>
							<td><?php echo $this->Risks->getStatuses($risk); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else : ?>
					<?php echo $this->element('not_found', array(
						'message' => __('No Associated Risks found.')
					)); ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __('Items asociated with this Incident'); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if (!empty($item['Affected'])) : ?>
				<table class="table table-hover table-striped table-bordered table-highlight-head">
					<thead>
						<tr>
							<th>
								<?php echo __('Type'); ?>
							</th>
							<th>
								<?php echo __('Name'); ?>
							</th>
							<th><?php echo __('Liabilities'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($item['Affected'] as $affect) : ?>
						<tr>
							<td><?php echo $affect['type']; ?></td>
							<td><?php echo $affect['name']; ?></td>
							<td><?php echo $affect['liabilities']; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('No Affected items found.')
					));
					?>
				<?php endif; ?>
			</div>
		</div>

		<div class="widget box <?php echo $extraClass; ?>">
			<div class="widget-header">
				<h4><?php echo __('Incident Stages'); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content lifecycle-content" style="">
				<?php
				echo $this->element('securityIncidents/lifecycle', array(
					'item' => $item
				));
				?>
			</div>
		</div>

		<?php
		echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
			'item' => $item
		));
		?>

	</div>
</div>