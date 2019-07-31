<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Business continuity plan'); ?>
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
							<?php echo $item['BusinessContinuityPlan']['title']; ?>
						</td>
						<td>
							<?php echo $this->BusinessContinuityPlans->getStatuses($item); ?>
						</td>
						<!-- <td>
							<?php
							//echo  $this->Workflow->getStatuses($item['BusinessContinuityPlan']);
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
							<?php echo $item['BusinessContinuityPlan']['objective']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Launch criteria'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['BusinessContinuityPlan']['launch_criteria']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<!-- <div class="item">
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
							!!!!!!!!!!! ESTE NIE JE DOPLNENY!!!!!!!!!!!
						</td>
						<td>
							!!!!!!!!!!! ESTE NIE JE DOPLNENY!!!!!!!!!!!
						</td>
					</tr>
				</table>
			</div> -->

			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Sponsor'); ?>
						</th>
						<th>
							<?php echo __('Launch Initiator'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Owner']); ?>
						</td>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Sponsor']); ?>
						</td>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['LaunchInitiator']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<!-- <div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Url'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							!!!!!!!!!!! ESTE NIE JE DOPLNENY!!!!!!!!!!!
						</td>
					</tr>
				</table>
			</div> -->

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
							<?php echo CakeNumber::currency( $item['BusinessContinuityPlan']['opex'] ); ?>
						</td>
						<td>
							<?php echo CakeNumber::currency( $item['BusinessContinuityPlan']['capex'] ); ?>
						</td>
						<td>
							<?php echo $item['BusinessContinuityPlan']['resource_utilization']; ?>
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
					<?php echo __('Plan Details'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">

			<?php if (!empty($item['BusinessContinuityTask'])) : ?>

				<?php $count = 0; ?>
				<?php foreach ( $item['BusinessContinuityTask'] as $task ) : ?>

					<?php $count++; ?>

					<div class="item">
						<table class="triple-column">
							<tr>
								<th>
									<?php echo __('Step'); ?>
								</th>
								<th>
									<?php echo __('Task Owner'); ?>
								</th>
								<th>
									<?php echo __('Status'); ?>
								</th>
							</tr>

							
							<tr>
								<td>
									<?php echo $task['step']; ?>
								</td>
								<td>
									<?php
									if ($task['awareness_role'] !== null) {
										echo $task['User']['full_name'];
									}
									else {
										echo '-';
									}
									?>
								</td>
								<td>
									<?php echo $this->BusinessContinuityTasks->getStatuses($task); ?>
								</td>
							</tr>

						</table>
					</div>

					<div class="item">
						<table>
							<tr>
								<th>
									<?php echo __('When'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $task['when']; ?>
								</td>
							</tr>
						</table>
					</div>

					<div class="item">
						<table>
							<tr>
								<th>
									<?php echo __('Who'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $task['who']; ?>
								</td>
							</tr>
						</table>
					</div>

					<div class="item">
						<table>
							<tr>
								<th>
									<?php echo __('Does what'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $task['does']; ?>
								</td>
							</tr>
						</table>
					</div>

					<div class="item">
						<table>
							<tr>
								<th>
									<?php echo __('Where'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $task['where']; ?>
								</td>
							</tr>
						</table>
					</div>

					<div class="item">
						<table>
							<tr>
								<th>
									<?php echo __('How'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $task['how']; ?>
								</td>
							</tr>
						</table>
					</div>

					<?php if ( $count < count($item['BusinessContinuityTask'])): ?>
						<div class="separator"></div>
					<?php endif; ?>

				<?php endforeach ; ?>

			<?php else : ?>
				<div class="item">
					<?php
					echo $this->Html->div('alert', 
						__('No plan details found.')
					);
					?>
				</div>
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
					<?php echo __('Audit information'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<?php if (!empty($item['BusinessContinuityPlanAudit'])) : ?>
				<?php foreach ($item['BusinessContinuityPlanAudit'] as $audit) : ?>
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
									<?php echo $audit['audit_success_criteria']; ?>
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
									<?php
									if (!empty($audit['User'])) {
										echo $audit['User']['name'] . ' ' . $audit['User']['surname'];
									}
									else {
										echo '-';
									}
									?>
								</td>
								<td>
									<?php echo $this->SecurityServiceAudits->getStatuses($audit, 'BusinessContinuityPlanAudit'); ?>
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
				<?php endforeach; ?>
			<?php else : ?>
				<div class="item">
					<?php
					echo $this->Html->div('alert', 
						__('No audit information found.')
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php echo $this->element('pdf_common_data'); ?>