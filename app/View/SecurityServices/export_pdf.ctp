<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Security Service'); ?>
				</h1>
			</div>
			<div class="subtitle">
				<h2>
					<?php echo __('General information'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Name'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
						<!-- <th>
							<?php //echo __('Workflows'); ?>
						</th> -->
					</tr>
					
					<tr>
						<td>
							<?php echo $item['SecurityService']['name']; ?>
						</td>
						<td>
							<?php
							echo $this->SecurityServices->getStatuses($item);
							?>
						</td>
						<!-- <td>
							<?php
							//echo $this->Workflow->getStatuses($item['SecurityService']);
							?>
						</td> -->
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Objective'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo nl2br($item['SecurityService']['objective']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table class="double-column">
					<tr>
						<th>
							<?php echo __('Classification'); ?>
						</th>
						<th>
							<?php echo __('Release'); ?>
						</th>
					</tr>
					
					<tr>
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
							<?php echo $item['SecurityServiceType']['name']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Collaborator'); ?>
						</th>
						<th>
							<?php echo __('Audit Owner'); ?>
						</th>
						<th>
							<?php echo __('Audit Evidence Owner'); ?>
						</th>
						<th>
							<?php echo __('Maintenance Owner'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['ServiceOwner']); ?>
						</td>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Collaborator']); ?>
						</td>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['AuditOwner']); ?>
						</td>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['AuditEvidenceOwner']); ?>
						</td>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['MaintenanceOwner']); ?>
						</td>
						<td>
							<?php
							if (!empty($item['AuditOwner']['login'])) {
								echo $item['AuditOwner']['name'] . ' ' . $item['AuditOwner']['surname'];
							}
							else {
								echo '-';
							}
							?>
						</td>
						<td>
							<?php
							if (!empty($item['AuditEvidenceOwner']['login'])) {
								echo $item['AuditEvidenceOwner']['name'] . ' ' . $item['AuditEvidenceOwner']['surname'];
							}
							else {
								echo '-';
							}
							?>
						</td>
						<td>
							<?php
							if (!empty($item['MaintenanceOwner']['login'])) {
								echo $item['MaintenanceOwner']['name'] . ' ' . $item['MaintenanceOwner']['surname'];
							}
							else {
								echo '-';
							}
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Url'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['SecurityService']['documentation_url']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Opex'); ?>
						</th>
						<th>
							<?php echo __('Capex'); ?>
						</th>
						<th>
							<?php echo __('Resource utilization'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo CakeNumber::currency( $item['SecurityService']['opex'] ); ?>
						</td>
						<td>
							<?php echo CakeNumber::currency( $item['SecurityService']['capex'] ); ?>
						</td>
						<td>
							<?php echo $item['SecurityService']['resource_utilization']; ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Security Policies'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">
					
			<div class="item">
				<table class="">
					<tr>
						<th>
							<?php echo __('Name'); ?>
						</th>
						<th>
							<?php echo __('Description'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<?php foreach ($item['SecurityPolicy'] as $policy) : ?>
					<tr>
						<td><?php echo $policy['index']; ?></td>
						<td><?php echo $policy['short_description']; ?></td>
						<td>
							<?php
							echo $this->SecurityPolicies->getStatuses($policy);
							?>
						</td>

					</tr>
					<?php endforeach ; ?>

				</table>
			</div>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Support Contracts'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">
					
			<div class="item">
				<table class="">
					<tr>
						<th>
							<?php echo __('Name'); ?>
						</th>
						<th>
							<?php echo __('Description'); ?>
						</th>
						<th>
							<?php echo __('Value'); ?>
						</th>
						<th>
							<?php echo __('Start Date'); ?>
						</th>
						<th>
							<?php echo __('Expiration Date'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<?php foreach ($item['ServiceContract'] as $serviceContract) : ?>
					<tr>
						<td><?php echo $serviceContract['name']; ?></td>
						<td><?php echo $serviceContract['description']; ?></td>
						<td><?php echo CakeNumber::currency($serviceContract['value']); ?></td>
						<td><?php echo $serviceContract['start']; ?></td>
						<td><?php echo $serviceContract['end']; ?></td>
						<td>
							<?php
							echo $this->App->getExpiredLabel($serviceContract['end']);
							?>
						</td>
					</tr>
					<?php endforeach ; ?>

				</table>
			</div>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Mitigation Points'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">
					
			<div class="item">
				<table class="double-column-uneven special-borders">
					<tr>
						<th>
							<?php echo __('Type'); ?>
						</th>
						<th>
							<?php echo __('Name'); ?>
						</th>
					</tr>
					
					<?php foreach ( $item['Risk'] as $risk ) : ?>
					<tr>
						<td><?php echo __( 'Asset based Risk' ) ?></td>
						<td><?php echo $risk['title']; ?></td>
					</tr>
					<?php endforeach ; ?>

					<?php foreach ( $item['ThirdPartyRisk'] as $risk ) : ?>
					<tr>
						<td><?php echo __( 'Third Party Risk' ) ?></td>
						<td><?php echo $risk['title']; ?></td>
					</tr>
					<?php endforeach ; ?>

					<?php foreach ( $item['BusinessContinuity'] as $risk ) : ?>
					<tr>
						<td><?php echo __( 'Business based Risk' ) ?></td>
						<td><?php echo $risk['title']; ?></td>
					</tr>
					<?php endforeach ; ?>

					<?php foreach ( $item['SecurityIncident'] as $security_incident ) : ?>
					<tr>
						<td><?php echo __( 'Security Incident' ) ?></td>
						<td><?php echo $security_incident['title']; ?></td>
					</tr>
					<?php endforeach ; ?>

					<?php foreach ( $item['DataAsset'] as $data_asset ) : ?>
					<tr>
						<td><?php echo __( 'Data Asset' ) ?></td>
						<td><?php echo $data_asset['description']; ?></td>
					</tr>
					<?php endforeach ; ?>

					<?php foreach ( $item['ComplianceManagement'] as $compliance ) : ?>
					<tr>
						<td><?php echo __( 'Compliance' ) ?></td>
						<?php
						$compliance_name = '(' . $compliance['CompliancePackageItem']['CompliancePackage']['ThirdParty']['name'] . ') ' . $compliance['CompliancePackageItem']['name'];
						?>
						<td><?php echo $compliance_name; ?></td>
					</tr>
					<?php endforeach ; ?>
				</table>
			</div>

		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Projects (Control Improvements)'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">

			<?php if ( ! empty( $item['Projects'] ) ) : ?>

				<?php $count = 0; ?>
				<?php foreach ( $item['Projects'] as $project ) : ?>

					<?php $count++; ?>
					<div class="item">
						<table class="tripple-column-uneven-small-middle">
							<tr>
								<th>
									<?php echo __('Title'); ?>
								</th>
								<th>
									<?php echo __('Deadline'); ?>
								</th>
								<th>
									<?php echo __('Goal'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $project['title']; ?>
								</td>
								<td>
									<?php echo $project['deadline']; ?>
								</td>
								<td>
									<?php echo $project['goal']; ?>
								</td>
							</tr>
						</table>
					</div>

					<?php if ( $count < count($item['Comment'])): ?>
						<div class="separator"></div>
					<?php endif; ?>

				<?php endforeach ; ?>

			<?php else : ?>
				<?php echo $this->element( 'not_found', array(
					'message' => __( 'No Projects found.' )
				) ); ?>
			<?php endif; ?>

		</div>
	</div>
</div>



<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Audit Information'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<?php foreach ($item['SecurityServiceAudit'] as $audit) : ?>
				<div class="item">
					<table>
						<tr>
							<th>
								<?php echo __('Audit metric'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php echo nl2br($audit['audit_metric_description']); ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="item">
					<table>
						<tr>
							<th>
								<?php echo __('Audit success criteria'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php echo nl2br($audit['audit_success_criteria']); ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="item">
					<table class="triple-column">
						<tr>
							<th>
								<?php echo __('Planned audit date'); ?>
							</th>
							<th>
								<?php echo __('Actual start date'); ?>
							</th>
							<th>
								<?php echo __('Actual end date'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php echo $audit['planned_date']; ?>
							</td>
							<td>
								<?php echo $audit['start_date']; ?>
							</td>
							<td>
								<?php echo $audit['end_date']; ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="item">
					<table class="triple-column">
						<tr>
							<th>
								<?php echo __('Result'); ?>
							</th>
							<th>
								<?php echo __('Owner'); ?>
							</th>
							<th>
								<?php echo __('Evidence Owner'); ?>
							</th>
							<th>
								<?php echo __('Status'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php
								if ($audit['result'] !== null) {
									echo getAuditStatuses($audit['result']);
								}
								else {
									echo '-';
								}
								?>
							</td>
							<td>
								<?= $this->UserField->convertAndShowUserFieldRecords('SecurityServiceAudit', 'AuditOwner', $audit); ?>
							</td>
							<td>
								<?= $this->UserField->convertAndShowUserFieldRecords('SecurityServiceAudit', 'AuditEvidenceOwner', $audit); ?>
							</td>
							<td>
								<?php
								if (!empty($audit['AuditEvidenceOwner'])) {
									echo $audit['AuditEvidenceOwner']['name'] . ' ' . $audit['AuditEvidenceOwner']['surname'];
								}
								else {
									echo '-';
								}
								?>
							</td>
							<td>
								<?php echo $this->SecurityServiceAudits->getStatuses($audit, 'SecurityServiceAudit'); ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="item">
					<table>
						<tr>
							<th>
								<?php echo __('Conclusion'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php echo !empty($audit['result_description']) ? $audit['result_description'] : '-'; ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="separator"></div>
			<?php endforeach; ?>

		</div>
	</div>
</div>



<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Maintenances Information'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<?php foreach ($item['SecurityServiceMaintenance'] as $maintenance) : ?>	
				<div class="item">
					<table>
						<tr>
							<th>
								<?php echo __('Task'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php echo nl2br($maintenance['task']); ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="item">
					<table class="triple-column">
						<tr>
							<th>
								<?php echo __('Planned maintenance date'); ?>
							</th>
							<th>
								<?php echo __('Actual start date'); ?>
							</th>
							<th>
								<?php echo __('Actual end date'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php echo $maintenance['planned_date']; ?>
							</td>
							<td>
								<?php echo $maintenance['start_date']; ?>
							</td>
							<td>
								<?php echo $maintenance['end_date']; ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="item">
					<table class="triple-column">
						<tr>
							<th>
								<?php echo __('Result'); ?>
							</th>
							<th>
								<?php echo __('Owner'); ?>
							</th>
							<th>
								<?php echo __('Status'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php
								if ($maintenance['result'] !== null) {
									echo getAuditStatuses($maintenance['result']);
								}
								else {
									echo '-';
								}
								?>
							</td>
							<td>
								<?= $this->UserField->convertAndShowUserFieldRecords('SecurityServiceMaintenance', 'MaintenanceOwner', $maintenance); ?>
							</td>
							<td>
								<?php echo $this->SecurityServiceAudits->getStatuses($maintenance, 'SecurityServiceMaintenance'); ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="item">
					<table>
						<tr>
							<th>
								<?php echo __('Conclusion'); ?>
							</th>
						</tr>
						
						<tr>
							<td>
								<?php echo !empty($maintenance['task_conclusion']) ? $maintenance['task_conclusion'] : '-'; ?>
							</td>
						</tr>
					</table>
				</div>

				<div class="separator"></div>
			<?php endforeach; ?>
		</div>
	</div>
</div>



<?php echo $this->element('pdf_common_data'); ?>