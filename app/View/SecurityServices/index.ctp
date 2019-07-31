<?php
App::uses('AppModule', 'Lib');

echo $this->Html->script('policy-document', array('inline' => false));
echo $this->Html->css('policy-document', array('inline' => false));
?>
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
							echo $this->Html->link(__('Security Service'), array(
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
							echo $this->Html->link(__('Audit'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$auditsWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Maintenance'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$maintenancesWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Issues'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$issuesWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>

				<?php
				echo $this->AdvancedFilters->getViewList($savedFilters, 'SecurityService');

				echo $this->ImportTool->getIndexLink('SecurityService');

				echo $this->NotificationSystem->getIndexLink(array(
					'SecurityService' => __('Security Service'),
					'SecurityServiceAudit' => __('Audit'),
					'SecurityServiceMaintenance' => __('Maintenances')
				));

				echo $this->CustomFields->getIndexLink(array(
					'SecurityService' => __('Security Service'),
					'SecurityServiceAudit' => __('Audit'),
					'SecurityServiceMaintenance' => __('Maintenance')
				));

				echo $this->Video->getVideoLink('SecurityService');
				?>

				<?php
				echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight');
				?>
			</div>
		</div>
	</div>

</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'SecurityService')); ?>


<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				/*$extra_class = '';
				if ( $extra_class != 'widget-header-alert' ) {
					if ( $extra_class != 'widget-header-warning' ) {
						if ( ! $entry['SecurityService']['audits_all_done'] || ! $entry['SecurityService']['maintenances_all_done'] ) {
							$extra_class = 'widget-header-warning';
						}
					}

					if ( ! $entry['SecurityService']['audits_last_passed'] || ! $entry['SecurityService']['maintenances_last_passed'] ) {
						$extra_class = 'widget-header-alert';
					}

					if ($entry['SecurityService']['audits_improvements']) {
						$extra_class = 'widget-header-improvement';
					}
				}*/

				$widgetClass = $this->SecurityServices->getHeaderClass($entry, 'SecurityService');
				?>
				<div class="widget box widget-closed <?php echo $widgetClass; ?>" data-id="<?php echo $entry['SecurityService']['id']; ?>">
					<div class="widget-header">
						<h4><?php echo $entry['SecurityService']['name']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
								if ($entry['SecurityServiceType']['id'] != SECURITY_SERVICE_DESIGN) {
									$auditUrl = array(
										'controller' => 'securityServiceAudits',
										'action' => 'index',
										$entry['SecurityService']['id']
									);

									$maintenanceUrl = array(
										'controller' => 'securityServiceMaintenances',
										'action' => 'index',
										$entry['SecurityService']['id']
									);

									$this->Ajax->addToActionList(__('Audits'), $auditUrl, 'search', 'index');
									$this->Ajax->addToActionList(__('Maintenances'), $maintenanceUrl, 'search', 'index');
								}

								$issueUrl = array(
									'controller' => 'issues',
									'action' => 'index',
									'SecurityService',
									$entry['SecurityService']['id']
								);
								$this->Ajax->addToActionList(__('Issues'), $issueUrl, 'bug', 'index');

								$exportUrl = array(
									'controller' => 'securityServices',
									'action' => 'exportPdf',
									$entry['SecurityService']['id']
								);

								$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

								echo $this->Ajax->getActionList($entry['SecurityService']['id'], array(
									'notifications' => $notificationSystemEnabled,
									'item' => $entry,
									'history' => true,
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
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The objective is to understand what is the status of this service: Design (working on the design and implementation), Production (the service is working, metrics are being taken, etc)' ); ?>'>
							<?php echo __( 'Release' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The owner of the control is the one that ensures that the control is used across the Security system and its audits are correctly executed.' ); ?>'>
							<?php echo __( 'Owner' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is usually the individual that looks after the control to ensure its working. For example, a control related to firewalls the "collaborator" would be the firewall administrator.' ); ?>'>
							<?php echo __( 'Collaborator' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The annual OPEX cost of the control.' ); ?>'>
							<?php echo __( 'Opex' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Control CAPEX cost of the control.' ); ?>'>
							<?php echo __( 'Capex' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
									<th>
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Resource Utilization' ); ?>" data-content='<?php echo __( 'The amount of Days / Year that must be dedicated in order to build, operate, improve, audit this control. For example, 30 days year (Some 10 hours / Week of one resource)' ); ?>'>
											<?php echo __( 'Resource Utilization' ); ?>
											<i class="icon-info-sign"></i>
										</div>
									</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Tags are used to group similar security services and later be able to filter according to them.' ); ?>'>
							<?php echo __( 'Tags' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th class="row-status">
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The available status for security services are: - "Last audit failed (red)" - when the last audit for this security service is tagged as "failed". A system record is generated on the security service when the audit was tagged as failed. - "Last audit missing (yellow)" - when the last audit for this security service is incomplete. A system record is generated on the security service when the audit day arrived and the item was not edited. - "Last maintenance missing (yellow)" - when the last maintenance for this security service is incomplete. A system record is generated on the security service when the maintenance day arrived and the item was not edited. - "Ongoing Corrective Actions (blue)" - when the last audit of this service was tagged as failed and a project has been asociated. A system record is generated on the security service when the project is assigned to the failed audit. - "Ongoing Security Incident (yellow)" - when a given securit service has a security incident with status open mapped. A system record is created when the incident has been mapped. The record has the incident title. - "Design (yellow)" - when a given security service is in status "design". When the item is set to design or production a system record is generated stated if it changed to "design" or "production".' ); ?>'>
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
									<td><?php echo $entry['SecurityServiceType']['name']; ?></td>
									<td><?= $this->UserField->showUserFieldRecords($entry['ServiceOwner']); ?></td>
									<td><?= $this->UserField->showUserFieldRecords($entry['Collaborator']); ?></td>
									<td><?php echo CakeNumber::currency( $entry['SecurityService']['opex'] ); ?></td>
									<td><?php echo CakeNumber::currency( $entry['SecurityService']['capex'] ); ?></td>
									<td><?php echo $entry['SecurityService']['resource_utilization']; ?></td>
									<td>
										<?php
										$labels = array();
										foreach ($entry['Classification'] as $label) {
											$labels[] = $this->Html->tag('span', $label['name'], array(
												'class' => 'label label-primary'
											));
										}

										echo implode(' ', $labels);
										?>
									</td>
									<td>
										<?php
										echo $this->SecurityServices->getStatuses($entry, true);
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['SecurityService']['id'],
											'item' => $this->Workflow->getActions($entry['SecurityService'], $entry['WorkflowAcknowledgement'])
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
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A brief description of what the control aims.' ); ?>'>
							<?php echo __( 'Objective' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['SecurityService']['objective']); ?></td>
								</th>
							</tbody>
						</table>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'This URL could optionally point to the control documentation or technology supplier.' ); ?>'>
							<?php echo __( 'URL' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php
										$url = false;
										if (!empty($entry['SecurityService']['documentation_url'])) {
											$url = $this->Html->link($entry['SecurityService']['documentation_url'], $entry['SecurityService']['documentation_url'], array(
												'target' => '_blank'
											));
										}

										echo $this->Eramba->getEmptyValue($url);

										?>
									</td>
								</th>
							</tbody>
						</table>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Related Security Policies' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if ( ! empty( $entry['SecurityPolicy'] ) ) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the Security Policy' ); ?>'>
							<?php echo __( 'Name' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Security Policy description' ); ?>'>
							<?php echo __( 'Description' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'his indicates if the policy is published (Released) or still a draft. Draft policies are not valid across the system (they are not visible)' ); ?>'>
							<?php echo __( 'Status' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $entry['SecurityPolicy'] as $securityPolicy ) : ?>
											<tr>
												<td>
													<?php echo $this->Ux->getItemLink($securityPolicy['index'], 'SecurityPolicy', $securityPolicy['id']); ?>
													<?php echo $this->SecurityPolicies->documentLink($securityPolicy); ?>
												</td>
												<td><?php echo $securityPolicy['short_description']; ?></td>
												<td>
													<?php
													echo $this->SecurityPolicies->getStatuses($securityPolicy);
													?>
												</td>
											</tr>
											<?php endforeach ; ?>
										</tbody>
									</table>
								<?php else : ?>
									<?php echo $this->element( 'not_found', array(
										'message' => __( 'No Security Policies found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Support Contracts'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if (!empty($entry['ServiceContract'])) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the contract, for example: Firewall Support Services' ); ?>'>
							<?php echo __( 'Name' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The description of what the contract includes' ); ?>'>
							<?php echo __( 'Description' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The value of contract' ); ?>'>
							<?php echo __( 'Value' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th><?php echo __('Start Date'); ?></th>
						<th><?php echo __('Expiration Date'); ?></th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( '"Expired (Red)" - when the expiration date set is in the past. A system record is generated on the contract when that happens.' ); ?>'>
							<?php echo __( 'Status' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($entry['ServiceContract'] as $serviceContract) : ?>
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
										</tbody>
									</table>
								<?php else : ?>
									<?php
									echo $this->element('not_found', array(
										'message' => __('No Service Contracts found.')
									));
									?>
								<?php endif; ?>
							</div>
						</div>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'What was the audit methodology defined for this control.' ); ?>'>
							<?php echo __( 'Audit Methodology' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'What the metric is expected to call this audit a "pass"' ); ?>'>
							<?php echo __( 'Audit Success Criteria' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'TBD' ); ?>'>
							<?php echo __( 'Audit Owner' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'TBD' ); ?>'>
							<?php echo __( 'Audit Evidence Owner' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'TBD' ); ?>'>
							<?php echo __( 'Audit Dates' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['SecurityService']['audit_metric_description']); ?></td>
									<td><?php echo $this->Eramba->getEmptyValue($entry['SecurityService']['audit_success_criteria']); ?></td>
									<td><?= $this->UserField->showUserFieldRecords($entry['AuditOwner']); ?></td>
									<td><?= $this->UserField->showUserFieldRecords($entry['AuditEvidenceOwner']); ?></td>
									<td>
										<?php foreach ($entry['SecurityServiceAuditDate'] as $ssAuditDate): ?>
											<?php
												$dateObj = DateTime::createFromFormat('!m', $ssAuditDate['month']);
												$monthName = $dateObj->format('F');
												echo $this->Ux->text($ssAuditDate['day'] . '/' . $monthName);
											?>
											<br />
										<?php endforeach; ?>
									</td>
								</th>
							</tbody>
						</table>

						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Controls require maintenance. Describe in this field what type of maintenance is required and how that task should be executed. For example: Oil must be changed at regular times on the auxiliary power supply engines in order for they to operate normally. This can be achieved by changing the oil on the engine. Follow the manual instructions.' ); ?>'>
							<?php echo __( 'Maintenance Task' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'TBD' ); ?>'>
							<?php echo __( 'Maintenance Owner' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'TBD' ); ?>'>
							<?php echo __( 'Maintenance Dates' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['SecurityService']['maintenance_metric_description']); ?></td>
									<?php
									$extra_class = '';
									if ( ! $entry['SecurityService']['maintenances_all_done'] ) {
										$extra_class = 'cell-warning';
									}
									if ( ! $entry['SecurityService']['maintenances_last_passed'] ) {
										$extra_class = 'cell-alert';
									}
									?>
									<td><?= $this->UserField->showUserFieldRecords($entry['MaintenanceOwner']); ?></td>
									<td>
										<?php foreach ($entry['SecurityServiceMaintenanceDate'] as $ssMaintenanceDate): ?>
											<?php
												$dateObj = DateTime::createFromFormat('!m', $ssMaintenanceDate['month']);
												$monthName = $dateObj->format('F');
												echo $this->Ux->text($ssMaintenanceDate['day'] . '/' . $monthName);
											?>
											<br />
										<?php endforeach; ?>
									</td>
								</tr>
							</tbody>
						</table>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Mitigated Items by this Control' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<table class="table table-hover table-striped">
									<thead>
										<tr>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The mitigation tab is used to describe where this control is applied. Mitigation types can be: Risks, Compliance Requirements and Data Flows controls. If this tab is empty, the control is effectibly not doing much on your program.' ); ?>'>
							<?php echo __( 'Mitigation Type' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the Risk, Compliance Package or Data Flow.' ); ?>'>
							<?php echo __( 'Description' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ( $entry['Risk'] as $risk ) : ?>
										<tr>
											<td><?php echo __( 'Asset based Risk' ) ?></td>
											<td><?php echo $this->Ux->getItemLink($risk['title'], 'Risk', $risk['id']); ?></td>
										</tr>
										<?php endforeach ; ?>

										<?php foreach ( $entry['ThirdPartyRisk'] as $risk ) : ?>
										<tr>
											<td><?php echo __( 'Third Party Risk' ) ?></td>
											<td><?php echo $this->Ux->getItemLink($risk['title'], 'ThirdPartyRisk', $risk['id']); ?></td>
										</tr>
										<?php endforeach ; ?>

										<?php foreach ( $entry['BusinessContinuity'] as $risk ) : ?>
										<tr>
											<td><?php echo __( 'Business based Risk' ) ?></td>
											<td><?php echo $this->Ux->getItemLink($risk['title'], 'BusinessContinuity', $risk['id']); ?></td>
										</tr>
										<?php endforeach ; ?>

										<?php foreach ( $entry['SecurityIncident'] as $security_incident ) : ?>
										<tr>
											<td><?php echo __( 'Security Incident' ) ?></td>
											<td><?php echo $this->Ux->getItemLink($security_incident['title'], 'SecurityIncident', $security_incident['id']); ?></td>
										</tr>
										<?php endforeach ; ?>

										<?php foreach ( $entry['DataAsset'] as $data_asset ) : ?>
										<tr>
											<td><?php echo __( 'Data Asset' ) ?></td>
											<td><?php echo $this->Ux->getItemLink($data_asset['description'], 'DataAsset', $data_asset['id']); ?></td>
										</tr>
										<?php endforeach ; ?>

										<?php if (!empty($entry['ComplianceManagement'])) : ?>
											<?php foreach ($entry['ComplianceManagement'] as $complianceManagement) : ?>
												<?php
												$compliance_name = sprintf(
													'(%s) (%s) %s',
													$complianceManagement['CompliancePackageItem']['CompliancePackage']['ThirdParty']['name'],
													$complianceManagement['CompliancePackageItem']['item_id'],
													$complianceManagement['CompliancePackageItem']['name']
												);
												?>
												<tr>
													<td><?php echo __('Compliance') ?></td>
													<td>
														<?php
														echo $this->AdvancedFilters->getItemFilteredLink(
															$compliance_name,
															'ComplianceManagement',
															$complianceManagement['id']
														);
														?>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Improvement Projects' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if ( ! empty( $entry['Project'] ) ) : ?>
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
											<?php foreach ( $entry['Project'] as $project ) : ?>
											<tr>
												<td><?php echo $this->Ux->getItemLink($project['title'], 'Project', $project['id']); ?></td>
												<td><?php echo $project['goal']; ?></td>
												<td><?php echo $project['deadline']; ?></td>
												<td><?php echo $this->Projects->getStatuses($project, true); ?></td>
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
						echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
							'item' => $entry
						));
						?>

					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Security Services found.' )
			) ); ?>
		<?php endif; ?>

	</div>

</div>
<script type="text/javascript">
jQuery(function($) {
<?php if ($openId !== null) : ?>
	var openId = <?php echo (int) $openId; ?>;
	$("[data-id=" + openId + "] .widget-collapse:first").trigger("click");
<?php endif; ?>
});
</script>