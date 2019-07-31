<?php
App::uses('AppModule', 'Lib');

$residual_risk = $risk['BusinessContinuity']['residual_risk'];

$widgetClass = $this->Risks->getHeaderClass($risk, 'BusinessContinuity');
?>

<div class="widget box widget-closed <?php echo $widgetClass; ?>">
	<div class="widget-header">
		<h4><?php echo __( 'Business Continuity' ); ?>: <?php echo $risk['BusinessContinuity']['title']; ?></h4>
		<div class="toolbar no-padding">
			<div class="btn-group">
				<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
				<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
					<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
				</span>
				<?php
					$reviewUrl = array(
						'controller' => 'reviews',
						'action' => 'index',
						'BusinessContinuity',
						$risk['BusinessContinuity']['id']
					);

					$this->Ajax->addToActionList(__('Reviews'), $reviewUrl, 'search', 'index');

					$exportUrl = array(
							'controller' => 'businessContinuities',
							'action' => 'exportPdf',
							 $risk['BusinessContinuity']['id']
						);

					$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

					echo $this->Ajax->getActionList($risk['BusinessContinuity']['id'], array(
						'notifications' => $notificationSystemEnabled,
						'item' => $risk,
						'history' => true,
						AppModule::instance('Visualisation')->getAlias() => true
					));
			?>
			</div>
		</div>
	</div>
	<?php if(!empty($risk['Process'])):?>
		<?php
		$rpd = 0;
		$mto = 0;
		$rto = $risk['Process'][0]['rto'];
		foreach ($risk['Process'] as $key => $value) {
			$rpd+=$value['rpd'];
			$mto = ($mto<$value['rpo'])?$value['rpo']:$mto;
			$rto = ($rto>$value['rto'])?$value['rto']:$rto;
		}?>
	<?php endif; ?>
	<div class="widget-subheader">


		<table class="table table-hover table-striped table-bordered table-highlight-head">
			<thead>
				<tr>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The mitigation strategy chosen for this risk, options are: Accept, Mitigate, Transfer and Avoid' ); ?>'>
					<?php echo __( 'Mitigation Strategy' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th class="text-center">
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The score calculated for this risk after it\'s assets (their liabilities) and the risk classification has been factored.' ); ?>'>
					<?php echo __( 'Risk Score' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th class="text-center">
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The residual risk remaining' ); ?>'>
					<?php echo __( 'Residual Score' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'This is the total daily revenue generated per day by all the business processes included on this Risk' ); ?>'>
					<?php echo __( 'Revenue per Day' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'From all the processes included in this Risk, this is the maximum MTO. This means that the contigencies (continuity plans) that mitigate this Risk should meet this MTO to ensure all processes included in this Risk are addressed. Remember, MTO means for how long things can be broken before they become a serious issue.' ); ?>'>
					<?php echo __( 'Maximum MTO' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'From all the processes included in this Risk, this is the minimum RTO. This means that the contigencies (continuity plans) that mitigate this Risk should meet this RTO to ensure all processes included in this Risk are addressed. Remember, RTO means how long it should take to put things up, this should be less or equal to MTO.' ); ?>'>
					<?php echo __( 'Minimum RTO' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The owner of the risk is usually the individual that ensures the risk is properly analysed, documented, communicated and reviewed' ); ?>'>
					<?php echo __( 'Owner' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is usually the individual that owns the risk. For example, if risk analysing an aircraft electronic system, the engineer (or its manager) that knows and understand the system could potentially be the stakeholder.' ); ?>'>
					<?php echo __( 'Stakeholder' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Risks must be reviewed at regular points in time to ensure they remain relevant and updated to the business. Notifications are triggered (optionaly) when this date arrives' ); ?>'>
					<?php echo __( 'Review Date' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th class="row-status">
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Risks can have multiple status: - "Risk Review Expired (Red)" - when the risk review date is in the past. A system record is generated when that event happened. - "Controls with Issues (the worst colour that inherits from the security service)" - when at least one of the security controls used to mitigate this risk has a failed or missing audit. A system record is generated when this happens. The record includes the control name. - "Exceptions with Issues (red)" - when at least one of the exceptions used in this risk is expired. A system record is generated when that happens. The record includes the name of the exception. - "Risk above appettite (red)" - when the risk score is above the risk appetite. A system record is generated when that happens with the risk score and the appetite score.' ); ?>'>
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
					<td><?php echo $risk['RiskMitigationStrategy']['name']; ?></td>
					<td class="text-center">
						<?php
						echo $this->element('risks/calculations/risk_score', array(
							'model' => 'BusinessContinuity',
							'risk' => $risk
						));
						?>
					</td>
					<td class="text-center">
						<?php
						echo $this->element('risks/calculations/residual_score', array(
							'model' => 'BusinessContinuity',
							'risk' => $risk
						));
						?>
					</td>

					<td class="text-center">
						<?php echo isset($rpd)?$rpd:'-'; ?>
					</td>
					<td class="text-center">
						<?php echo isset($mto)?$mto:'-'; ?>
					</td>
					<td class="text-center">
						<?php echo isset($rto)?$rto:'-'; ?>
					</td>
					<td><?= $this->UserField->showUserFieldRecords($risk['Owner']); ?></td>
					<td><?= $this->UserField->showUserFieldRecords($risk['Stakeholder']); ?></td>
					<td><?php echo $risk['BusinessContinuity']['review']; ?></td>
					<td>
						<?php
						echo $this->Risks->getStatuses($risk, 'BusinessContinuity', true);
						?>
					</td>
					<?php /*
					<td class="text-center">
						<?php
						echo $this->element('workflow/action_buttons_1', array(
							'id' => $risk['BusinessContinuity']['id'],
							'item' => $this->Workflow->getActions($risk['BusinessContinuity'], $risk['WorkflowAcknowledgement'])
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
				<tr>
					<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A more detailed description.' ); ?>'>
							<?php echo __( 'Description' ); ?>
				        	<i class="icon-info-sign"></i>
				        </div>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $this->Eramba->getEmptyValue($risk['BusinessContinuity']['description']); ?></td>
				</tr>
			</tbody>
		</table>

		<?php
		echo $this->element(CORE_ELEMENT_PATH . 'riskClassificationTable', array(
			'risk' => $risk,
			'model' => 'BusinessContinuity'
		));
		?>

		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __( 'Processes' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if (!empty($risk['Process'])) : ?>
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th><?php echo __('Business Unit'); ?></th>
							<th><?php echo __('Process'); ?></th>
							<th><?php echo __('MTO'); ?></th>
							<th><?php echo __('RTO'); ?></th>
							<th><?php echo __('Revenue per day'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($risk['Process'] as $process) : ?>
						<tr>
							<td><?php echo $process['BusinessUnit']['name']; ?></td>
							<td><?php echo $process['name']; ?></td>
							<td><?php echo __n('%d Hour', '%d Hours', $process['rto'], $process['rto']); ?></td>
							<td><?php echo __n('%d Hour', '%d Hours', $process['rpo'], $process['rpo']); ?></td>
							<td><?php echo CakeNumber::currency($process['rpd']); ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else : ?>
					<?php echo $this->element('not_found', array(
						'message' => __('No Processes found.')
					)); ?>
				<?php endif; ?>
			</div>
		</div>

		<table class="table table-hover table-striped table-bordered table-highlight-head">
			<thead>
				<tr>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A description of the business impact should the risk materializes' ); ?>'>
					<?php echo __( 'Business Impact' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $this->Eramba->getEmptyValue($risk['BusinessContinuity']['impact']); ?></td>
				</tr>
			</tbody>
		</table>

		<table class="table table-hover table-striped table-bordered table-highlight-head">
			<thead>
				<tr>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The list of threats (from a predefined list) for this risk' ); ?>'>
					<?php echo __( 'Threat Tags' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The list of vulnerabilities (from a predefined list) for this risk' ); ?>'>
					<?php echo __( 'Vulnerability Tags' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<?php foreach ( $risk['Threat'] as $threat ) : ?>
							<span class="label label-info"><?php echo $threat['name']; ?></span>
						<?php endforeach; ?>
					</td>
					<td>
						<?php foreach ( $risk['Vulnerability'] as $vulnerability ) : ?>
							<span class="label label-info"><?php echo $vulnerability['name']; ?></span>
						<?php endforeach; ?>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="table table-hover table-striped table-bordered table-highlight-head">
			<thead>
				<tr>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A more detailed description of the thread' ); ?>'>
					<?php echo __( 'Threat Description' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $this->Eramba->getEmptyValue($risk['BusinessContinuity']['threats']); ?></td>
				</tr>
			</tbody>
		</table>

		<table class="table table-hover table-striped table-bordered table-highlight-head">
			<thead>
				<tr>
				<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A more detailed description of the vulnerability' ); ?>'>
					<?php echo __( 'Vulnerability Description' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $this->Eramba->getEmptyValue($risk['BusinessContinuity']['vulnerabilities']); ?></td>
				</tr>
			</tbody>
		</table>

		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __( 'Risk Exceptions' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if (!empty($riskExceptionData['joinIds'][$risk['BusinessContinuity']['id']])) : ?>
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the exception' ); ?>'>
								<?php echo __( 'Title' ); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th><?php echo __( 'Description' ); ?></th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The requester is usually the person that approved the risk Exception (and usually the person that accepts taking the risk asociated with it)' ); ?>'>
								<?php echo __( 'Requester' ); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date when this risk exceptions expires. You can optionally set an email notification.' ); ?>'>
								<?php echo __('Expiration'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status for risk exceptions can be: "Expired (red)" - when the date set is in the past. A system record is generated on the exception when that happens.'); ?>'>
								<?php echo __('Status'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($riskExceptionData['joinIds'][$risk['BusinessContinuity']['id']] as $riskExceptionId) : ?>
							<?php
							$risk_exception = $riskExceptionData['formattedData'][$riskExceptionId];
							?>
							<tr>
								<td><?php echo $risk_exception['RiskException']['title']; ?></td>
								<td><?php echo nl2br($risk_exception['RiskException']['description']); ?></td>
								<td><?= $this->UserField->showUserFieldRecords($risk_exception['Requester']); ?></td>
								<td><?php echo $risk_exception['RiskException']['expiration']; ?></td>
								<td>
									<?php
									echo $this->PolicyExceptions->getStatuses($risk_exception, 'RiskException');
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Risks Exceptions found.' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>

		<?php
		echo $this->element('risks/security_controls', array(
			'riskId' => $risk['BusinessContinuity']['id']
		));
		?>

		<?php
		echo $this->element('risks/security_policies', array(
			'data' => $risk['SecurityPolicyTreatment'],
			'widgetTitle' => __('Treatment Policies')
		));
		?>

		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __( 'Business Continuity Plans' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if ( ! empty( $risk['BusinessContinuityPlan'] ) ) : ?>
				<table class="table table-hover table-striped">
					<thead>
						<tr>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name for the Continuity Plan' ); ?>'>
										<?php echo __( 'Plan Title' ); ?>
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
								</div>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $risk['BusinessContinuityPlan'] as $bcp ) : ?>
						<tr>
							<td><?php echo $bcp['title']; ?></td>
							<td><?= $this->UserField->convertAndShowUserFieldRecords('BusinessContinuityPlan', 'LaunchInitiator', $bcp); ?></td>
							<td><?= $this->UserField->convertAndShowUserFieldRecords('BusinessContinuityPlan', 'Owner', $bcp); ?></td>
							<td><?= $this->UserField->convertAndShowUserFieldRecords('BusinessContinuityPlan', 'Sponsor', $bcp); ?></td>

<?php
/*$msg = array();
if ( $bcp['security_service_type_id'] == SECURITY_SERVICE_DESIGN ) {
	$msg[] = '<span class="label label-warning">' . __( 'Status is Design.' ) . '</span>';
}
if ( ! $bcp['audits_all_done'] ) {
	$msg[] = '<span class="label label-warning">' . __( 'Missing audits.' ) . '</span>';

}
if ( ! $bcp['audits_last_passed'] ) {
	$msg[] = '<span class="label label-danger">' . __( 'Last audit failed.' ) . '</span>';
}
if ($bcp['audits_improvements']) {
	$msg[] = '<span class="label label-primary">' . __( 'Being fixed.' ) . '</span>';
}

if ( $bcp['audits_all_done'] && $bcp['audits_last_passed'] ) {
	$msg[] = '<span class="label label-success">' . __( 'No audit issues.' ) . '</span>';
}

foreach ($bcp['BusinessContinuityTask'] as $task) {
	if (!empty($task['BusinessContinuityTaskReminder'])) {
		$msg[] = '<span class="label label-warning">' . __( 'At least one warning sent.' ) . '</span>';
		break;
	}
}*/
?>
							<td>
								<?php
								echo $this->BusinessContinuityPlans->getStatuses($bcp);
								?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Business Continuity Plans found.' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>

		<?php
		echo $this->element('risks/security_policies', array(
			'data' => $risk['SecurityPolicyIncident'],
			'widgetTitle' => __('Risk Response Plan')
		));
		?>

		<?php
		echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
			'item' => $risk
		));
		?>

	</div>
</div>
