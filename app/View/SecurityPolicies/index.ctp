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
							echo $this->Html->link(__('Security Policy'), array(
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
							echo $this->Html->link(__('Reviews'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$reviewsWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'SecurityPolicy'); ?>

				<div class="btn-group group-merge">
					<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __('Settings'); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right">
						<li>
							<?php
							echo $this->Html->link(__('Authentication'), array(
								'controller' => 'ldapConnectors',
								'action' => 'authentication',
								'?' => [
									'redirect' => Router::url(['controller' => 'securityPolicies', 'action' => 'index'], true)
								]
							), array(
								'escape' => false
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Document Types'), array(
								'controller' => 'securityPolicyDocumentTypes',
								'action' => 'index'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
					</ul>
				</div>

				<?php echo $this->ImportTool->getIndexLink('SecurityPolicy'); ?>
				
				<?php echo $this->NotificationSystem->getIndexLink('SecurityPolicy'); ?>

				<?php echo $this->Video->getVideoLink('SecurityPolicy'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>

				<?php
				echo $this->CustomFields->getIndexLink(array(
					'SecurityPolicy' => ClassRegistry::init('SecurityPolicy')->label(['singular' => true]),
				));
				?>
			</div>
		</div>
	</div>


</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'SecurityPolicy')); ?>
<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				$widgetClass = $this->SecurityPolicies->getHeaderClass($entry, 'SecurityPolicy');
				?>
				<div class="widget box widget-closed <?php echo $widgetClass; ?>">
					<div class="widget-header">
						<h4><?php echo __('Security Policy'); ?>: <?php echo $entry['SecurityPolicy']['index']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
								$reviewUrl = array(
									'controller' => 'reviews',
									'action' => 'index',
									'SecurityPolicy',
									$entry['SecurityPolicy']['id']
								);

								$directLinkUrl = array(
									'controller' => 'securityPolicies',
									'action' => 'getDirectLink',
									$entry['SecurityPolicy']['id']
								);

								$cloneUrl = array(
									'controller' => 'securityPolicies',
									'action' => 'duplicate',
									$entry['SecurityPolicy']['id']
								);

								$exportPdf = array(
									'controller' => 'policy',
									'action' => 'documentPdf',
									$entry['SecurityPolicy']['id'],
									'?' => array(
										'allowForLogged' => true
									)
								);

								$this->Ajax->addToActionList(__('Reviews'), $reviewUrl, 'search', 'index');
								$this->Ajax->addToActionList(__('Direct Link'), $directLinkUrl, 'link', false, array(
									'class' => 'get-direct-link'
								));
								$this->Ajax->addToActionList(__('Clone'), $cloneUrl, 'copy', false);

								if ($entry['SecurityPolicy']['status'] == SECURITY_POLICY_RELEASED) {
									$this->Ajax->addToActionList(__('Export PDF'), $exportPdf, 'file', false);
								}

								// view policy button
								$viewUrl = $this->Policy->getDocumentUrl($entry['SecurityPolicy']['id'], true);
								$documentAttrs = $this->Policy->getDocumentAttrs($entry['SecurityPolicy']['id']);
								$this->Ajax->addToActionList(__('View'), $viewUrl, 'search', false, $documentAttrs);

								/*if ($entry['SecurityPolicy']['use_attachments'] == SECURITY_POLICY_USE_CONTENT) {
									$viewUrl = $this->Policy->getDocumentUrl($entry['SecurityPolicy']['id'], true);
									$documentAttrs = $this->Policy->getDocumentAttrs($entry['SecurityPolicy']['id']);
									$documentAttrs['tooltip'] = array(
										'content' => __('Document Content type is set as "%s". Click to view this document.', getPoliciesDocumentContentTypes(SECURITY_POLICY_USE_CONTENT))
									);

									$this->Ajax->addToActionList(__('View'), $viewUrl, 'search', false, $documentAttrs);
								}

								if ($entry['SecurityPolicy']['use_attachments'] == SECURITY_POLICY_USE_URL) {
									$this->Ajax->addToActionList(__('View'), $entry['SecurityPolicy']['url'], 'search', false, array(
										'target' => '_blank',
										'tooltip' => array(
											'content' => __('Document Content type is set as "%s". Click to open it in a new tab.', getPoliciesDocumentContentTypes(SECURITY_POLICY_USE_URL))
										)
									));
								}

								if ($entry['SecurityPolicy']['use_attachments'] == SECURITY_POLICY_USE_ATTACHMENTS) {
									$viewUrl = $this->Policy->getDocumentUrl($entry['SecurityPolicy']['id'], true);
									$documentAttrs = $this->Policy->getDocumentAttrs($entry['SecurityPolicy']['id']);
									$documentAttrs['tooltip'] = array(
										'content' => __('Document Content type is set as "%s". Click to view this document.', getPoliciesDocumentContentTypes(SECURITY_POLICY_USE_ATTACHMENTS))
									);

									$this->Ajax->addToActionList(__('View'), $viewUrl, 'search', false, $documentAttrs);
								}*/

								if ($entry['SecurityPolicy']['permission'] == SECURITY_POLICY_LOGGED) {
									$sendNotificationsUrl = array(
										'controller' => 'securityPolicies',
										'action' => 'sendNotifications',
										$entry['SecurityPolicy']['id']
									);

									$this->Ajax->addToActionList(__('Send Notifications'), $sendNotificationsUrl, 'file', false);
								}

								echo $this->Ajax->getActionList($entry['SecurityPolicy']['id'], array(
									'notifications' => true,
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
										<?php echo __('ID'); ?>
									</th>
									<th>
										<?php echo __('Owner'); ?>
									</th>
									<th>
										<?php echo __('Collaborators'); ?>
									</th>
									<th>
										<?php echo __('Document Type'); ?>
									</th>
									<th>
										<?php echo __('Content Type'); ?>
									</th>
									<th>
										<?php echo __('Permission'); ?>
									</th>
									<th>
										<?php echo __('Version'); ?>
									</th>
									<th>
										<?php echo __('Published Date'); ?>
									</th>
									<th>
										<?php echo __('Review Date'); ?>
									</th>
									<th class="row-status">
										<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'This indicates if the policy is published (Released) or still a draft. Draft policies are not valid across the system (they are not visible)' ); ?>'>
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
									<td>
										<?php echo $entry['SecurityPolicy']['id']; ?>
									</td>
									<td>
										<?= $this->UserField->showUserFieldRecords($entry['Owner']); ?>
									</td>
									<td>
										<?= $this->UserField->showUserFieldRecords($entry['Collaborator']); ?>
									</td>
									<td>
										<?php
										echo $entry['SecurityPolicyDocumentType']['name'];
										?>
									</td>
									<td>
										<?php
										$contentTypes = getPoliciesDocumentContentTypes();
										echo $contentTypes[$entry['SecurityPolicy']['use_attachments']];
										?>
										<?php
										echo $this->SecurityPolicies->documentLink($entry['SecurityPolicy'], array(
											'title' => '<i class="icon-search"></i> ' . __('View'),
											'tooltip' => false,
											'class' => 'policy-link-table'
										));
										?>
									</td>
									<td>
										<?php
										$documentPermissions = getPoliciesDocumentPermissions();
										echo $documentPermissions[$entry['SecurityPolicy']['permission']];
										?>
									</td>
									<td>
										<?php echo $entry['SecurityPolicy']['version']; ?>
									</td>
									<td>
										<?php echo $entry['SecurityPolicy']['published_date']; ?>
									</td>
									<td>
										<?php
										echo $this->Reviews->getLastReviewDate($entry);
										?>
									</td>
									<td>
										<?php
										echo $this->SecurityPolicies->getStatuses($entry, true);
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['SecurityPolicy']['id'],
											'item' => $this->Workflow->getActions($entry['SecurityPolicy'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="widget-subheader">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th><?php echo __( 'Description' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php
										echo $this->Eramba->getEmptyValue($entry['SecurityPolicy']['short_description']);
										?>
									</td>
								</tr>
							</tbody>
						</table>

					</div>


					<div class="widget-content" style="display:block;">
						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Related Documents'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if ( ! empty( $entry['RelatedDocuments'] ) ) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
												<th>
													<?php echo __('Type'); ?>
												</th>
												<th>
													<?php echo __('Title'); ?>
												</th>
												<th>
													<?php echo __('Status'); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($entry['RelatedDocuments'] as $document) : ?>
											<tr>
												<td>
													<?php
													echo $document['SecurityPolicyDocumentType']['name'];
													?>
												</td>
												<td><?php echo $document['index']; ?></td>
												<td>
													<?php
													echo $this->SecurityPolicies->getStatuses($document);
													?>
												</td>
											</tr>
											<?php endforeach ; ?>
										</tbody>
									</table>
								<?php else : ?>
									<?php echo $this->element( 'not_found', array(
										'message' => __( 'No related documents found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>

						<?php
						echo $this->element('reviews', array(
							'item' => $entry
						));
						?>

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __('Mitigated Items by this Policy'); ?></h4>
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
												<?php echo __('Mitigation Type'); ?>
											</th>
											<th>
												<?php echo __('Description'); ?>
											</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($entry['Project'] as $project) : ?>
										<tr>
											<td><?php echo __('Project') ?></td>
											<td><?php echo $this->Ux->getItemLink($project['title'], 'Project', $project['id']); ?></td>
										</tr>
										<?php endforeach ; ?>

										<?php foreach ($entry['SecurityService'] as $securityService) : ?>
										<tr>
											<td><?php echo __('Security Service') ?></td>
											<td><?php echo $this->Ux->getItemLink($securityService['name'], 'SecurityService', $securityService['id']); ?></td>
										</tr>
										<?php endforeach ; ?>

										<?php foreach ($entry['PolicyException'] as $policyException) : ?>
										<tr>
											<td><?php echo __('Policy Exception') ?></td>
											<td><?php echo $this->Ux->getItemLink($policyException['title'], 'PolicyException', $policyException['id']); ?></td>
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
								<h4><?php echo __('Awareness Programs'); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if (!empty($entry['AwarenessProgram'])) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
												<th>
													<?php echo __('Awareness Program'); ?>
												</th>
												<th>
													<?php echo __('Compliant Users'); ?>
												</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($entry['AwarenessProgram'] as $program) : ?>
											<tr>
												<td><?php echo $program['title'] ?></td>
												<td>
													<?php
													echo $this->AwarenessPrograms->getStatisticPart($program, 'compliant');
													?>
												</td>
											</tr>
											<?php endforeach ; ?>
										</tbody>
									</table>
								<?php else : ?>
									<?php
									echo $this->element('not_found', array(
										'message' => __('No Awareness Programs found.')
									));
									?>
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
				'message' => __( 'No Security Policies found.' )
			) ); ?>
		<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {

	$('.get-direct-link').on('click', function(e){
		var href = $(this).attr("href");

		$.ajax({
			type: "GET",
			dataType: "JSON",
			url: href
		}).done(function(data) {
			console.log(data);
			if (data.success) {
				var $input = $("<input class='form-control' type='text' readonly style='cursor:auto;' />");

				bootbox.hideAll();
				bootbox.dialog({
					title: data.title,
					message : $input.val(data.directLink).get(0),
					buttons: {
						close: {
							label: "<?php echo __('Close'); ?>",
							className: "btn",
						}
					}
				});
			}
			else {
				console.log(data.message);
				bootbox.alert(data.message);
			}
		});

		e.preventDefault();
	});
});
</script>
