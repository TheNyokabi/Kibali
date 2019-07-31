<?php
App::uses('AppModule', 'Lib');

echo $this->Html->script('policy-document', array('inline' => false));
echo $this->Html->css('policy-document', array('inline' => false));

$widgetClass = $this->Risks->getHeaderClass($risk, 'Risk');
?>

<div class="widget box widget-closed <?php echo $widgetClass; ?>">
	<div class="widget-header">
		<h4><?php echo __( 'Risk' ); ?>: <?php echo $risk['Risk']['title']; ?></h4>
		<div class="toolbar no-padding">
			<div class="btn-group">
				<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
				<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
					<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
				</span>
				<?php
					echo $this->Risks->getActionList($risk, array(
						'notifications' => $notificationSystemEnabled,
						'item' => $risk,
						'history' => true,
						// WorkflowsModule::alias() => true
						AppModule::instance('Visualisation')->getAlias() => true	
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
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The mitigation strategy chosen for this risk, options are: Accept, Mitigate, Transfer and Avoid' ); ?>'>
					<?php echo __( 'Treatment Strategy' ); ?>
				        <i class="icon-info-sign"></i>
				        </div>
				</th>
				<th class="text-center">
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content="<?php echo __( 'The score calculated for this risk after it\'s assets (their liabilities) and the risk classification has been factored.' ); ?>">
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
					<?php echo __( 'Risk Review Date' ); ?>
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
							'model' => 'Risk',
							'risk' => $risk
						));
						?>
					</td>
					<td class="text-center">
						<?php
						echo $this->element('risks/calculations/residual_score', array(
							'model' => 'Risk',
							'risk' => $risk
						));
						?>
					</td>
					<td><?= $this->UserField->showUserFieldRecords($risk['Owner']); ?></td>
					<td><?= $this->UserField->showUserFieldRecords($risk['Stakeholder']); ?></td>
					<td><?php echo $risk['Risk']['review']; ?></td>
					<td>
						<?php
						echo $this->Risks->getStatuses($risk, 'Risk', true);
						?>
					</td>
					<?php /*
					<td class="text-center">
						<?php
						echo $this->element('workflow/action_buttons_1', array(
							'id' => $risk['Risk']['id'],
							'item' => $this->Workflow->getActions($risk['Risk'], $risk['WorkflowAcknowledgement'])
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
					<td><?php echo $this->Eramba->getEmptyValue($risk['Risk']['description']); ?></td>
				</tr>
			</tbody>
		</table>

		<?php
		echo $this->element(CORE_ELEMENT_PATH . 'riskClassificationTable', array(
			'risk' => $risk,
			'model' => 'Risk'
		));
		?>

		<?php
		echo $this->element('risks/assets', array(
			'data' => $assetData['joinIds'][$risk['Risk']['id']]
		));
		?>

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
					<td><?php echo $this->Eramba->getEmptyValue($risk['Risk']['threats']); ?></td>
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
					<td><?php echo $this->Eramba->getEmptyValue($risk['Risk']['vulnerabilities']); ?></td>
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
				<?php if (!empty($riskExceptionData['joinIds'][$risk['Risk']['id']])) : ?>
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
						<?php foreach ($riskExceptionData['joinIds'][$risk['Risk']['id']] as $riskExceptionId) : ?>
							<?php
							$risk_exception = $riskExceptionData['formattedData'][$riskExceptionId];
							?>
							<tr>
								<td><?php echo $this->Html->link( $risk_exception['RiskException']['title'], array(
									'controller' => 'risk_exceptions',
									'action' => 'index',
									'?' => array(
										'id' => $risk_exception['RiskException']['id']
									)
								) ); ?></td>
								<td><?php echo $this->Eramba->getEmptyValue($risk_exception['RiskException']['description']); ?></td>
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
			'riskId' => $risk['Risk']['id']
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
				<h4><?php echo __( 'Projects' ); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if ( ! empty($risk['Project'])) : ?>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th><?php echo __( 'Title' ); ?></th>
								<th><?php echo __( 'Goal' ); ?></th>
								<th><?php echo __( 'Deadline' ); ?></th>
								<th><?php echo __( 'Status' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($risk['Project'] as $project) : ?>
							<tr>
								<td><?php echo $this->Html->link( $project['title'], array(
									'controller' => 'projects',
									'action' => 'index',
									'?' => array(
										'id' => $project['id']
									)
								) ); ?></td>
								<td><?php echo $this->Eramba->getEmptyValue($project['goal']); ?></td>
								<td><?php echo $project['deadline']; ?></td>
								<td>
									<?php
									echo $this->Projects->getStatuses($project);
									?>
								</td>
							</tr>
							<?php endforeach ; ?>
						</tbody>
					</table>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Projects found.' )
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
