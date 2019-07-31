<div class="row">

	<div class="col-md-12">

		<?php if ( ! empty( $data['CompliancePackage'] ) ) : ?>
			<?php foreach ( $data['CompliancePackage'] as $entry ) : ?>
				<div class="widget box widget-form">
					<div class="widget-header">
						<h4><?php echo $entry['package_id'] . ' - ' . $entry['name'] . ' (' . $entry['description'] . ')'; ?></h4>

						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
							</div>
						</div>
					</div>
					<div class="widget-content">
						<?php if ( ! empty( $entry['CompliancePackageItem'] ) ) : ?>
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th><?php echo __( 'Item ID' ); ?></th>
										<th><?php echo __( 'Item Name' ); ?></th>
										<th>
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Use this option to load new Audit Findings (items that after an audit proved not compliant)' ); ?>'>
											<?php echo __( 'Findings' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th class="align-center">
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'You can use this setting option to request a particular system user to submit evidence for this specific requirement. After this setting has been configured you must manually trigger the notifications to all auditees.' ); ?>'>
											<?php echo __( 'Actions' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th><?php echo __('Feedback Profile'); ?></th>
										<th class="align-center">
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Defines if the audit is ongoign (waiting for evidence) or completed (evidence has been provided or there is no need for evidence)' ); ?>'>
											<?php echo __( 'Status' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>

										<!-- @todo Hidden until new workflows are ready to put here -->
										<th class="text-center hidden">
											<?php echo __( 'Settings Workflows' ); ?>
										</th>
										<th class="align-center">
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If an audit setting was configured for this item, the auditee is the individual who is responsible for providing audit evidence' ); ?>'>
											<?php echo __( 'Auditee' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $entry['CompliancePackageItem'] as $item ) : ?>
									<tr>
										<td><?php echo $item['item_id']; ?></td>
										<td>
											<?php $popupTitle = "<strong>Title:</strong> ". (!empty($item['name'])?$item['name']:'None')."<br/><br/>"; ?>
											<?php $popupDesc = "<strong>Description:</strong> ". (!empty($item['description'])?nl2br(h($item['description'])):'None')."<br/><br/>"; ?>
											<?php $popupAudit = "<strong>Audit Criteria:</strong> ". (!empty($item['audit_questionaire'])?nl2br(h($item['audit_questionaire'])):'None'); ?>
											<div class="bs-popover" data-trigger="hover" data-placement="right" data-html="true" data-original-title="<?php echo __( 'Description' ); ?>" data-content='<?php echo $popupTitle.$popupDesc.$popupAudit ; ?>'>
												<?php echo $this->Text->truncate($item['name'], 30); ?>
												<i class="icon-info-sign"></i>
											</div>
										</td>
										<?php $count = count( $item['ComplianceFinding'] ); ?>
										<td>
											<?php
											$countLabel = __n( '%d Item', '%d Items', $count, $count );

											$findingLink = $this->Html->link('<i class="icon-plus-sign"></i>', array(
												'controller' => 'complianceFindings',
												'action' => 'add',
												$audit_id, $item['id']
											), array(
												'class' => 'bs-tooltip table-control-link',
												'escape' => false,
												'title' => __('Add Finding'),
												'data-ajax-action' => 'add'
											));

											$cell = $countLabel . ' ' . $findingLink;

											// if there is a finding with compliance exception or third party audit associated, show a tooltip
											$list = $this->ComplianceAudits->getFindingsWithAssociations($item['ComplianceFinding']);
											
											if (!empty($list)) {
												echo $this->Eramba->getTruncatedTooltip($cell, array(
													'title' => __('Associated Items'),
													'content' => implode('<br />', $list),
													'truncate' => false,
													'placement' => 'right'
												));
											}
											else {
												echo $cell;
											}
											?>
										</td>
										<td class="align-center">
											<?php
											// if (!(isset($item['ComplianceAuditSettingSingle']['auditeeFeedbacks']) && $item['ComplianceAuditSettingSingle']['auditeeFeedbacks'] === false))	{

												$auditSettingsUrl = array(
													'controller' => 'complianceAuditSettings',
													'action' => 'setup',
													$audit_id,
													$item['id']
												);

												$this->Ajax->addToActionList(__('Audit Settings'), $auditSettingsUrl, 'link', 'edit', array('order' => '0'));

												$actionOptions = array(
													'style' => 'icons',
													'edit' => false,
													'trash' => false,
													'comments' => false,
													'records' => false,
													'attachments' => false,
													'model' => 'ComplianceAuditSetting'
												);

												if (!empty($item['ComplianceAuditSettingSingle'])) {
													$actionOptions['comments'] = true;
													$actionOptions['records'] = true;
													$actionOptions['attachments'] = true;
													$actionOptions['history'] = true;

													$actionOptions['commentsCount'] = count($item['ComplianceAuditSettingSingle']['Comment']);
													$actionOptions['attachmentsCount'] = count($item['ComplianceAuditSettingSingle']['Attachment']);
												}
												
												echo $this->Ajax->getActionList($item['ComplianceAuditSettingSingle']['id'], $actionOptions);
											// }
											// else {
											// 	echo getEmptyValue('');
											// }
											?>
										</td>
										<td>
										<?php if (!empty($item['ComplianceAuditSettingSingle']['compliance_audit_feedback_profile_id'])) : ?>

											<?php
											$html = false;
											if (!empty($item['ComplianceAuditSettingSingle']['auditeeFeedbacks'])) {
												$auditeeFeedbacks = $item['ComplianceAuditSettingSingle']['auditeeFeedbacks'];

												$html = array();
												foreach ($auditeeFeedbacks as $uid => $feedback) {
													$labels = array();
													foreach ($feedback as $f) {
														$labels[] = $this->Eramba->getLabel($f, 'primary');
													}

													$html[] = /*'<strong>' . $uid . '</strong>: ' . */implode(' ', $labels);
												}

												$html = implode('<br />', $html);
											}

											if (empty($html)) {
												$html = $this->Html->tag('span', __('No feedback yet'), array(
													'class' => 'label label-danger'
												));
											}
											?>

											<?php if (!empty($html)) : ?>
												<?php echo $html; ?>
												<!-- <div class="bs-popover" data-trigger="hover" data-placement="top" data-html="true" data-original-title="<?php echo __('Feedback Answers'); ?>" data-content='<?php echo $html; ?>'>
													<?php echo $item['ComplianceAuditSettingSingle']['ComplianceAuditFeedbackProfile']['name']; ?>
													<i class="icon-info-sign"></i>
												</div> -->
											<?php else : ?>
												<?php echo $item['ComplianceAuditSettingSingle']['ComplianceAuditFeedbackProfile']['name']; ?>
											<?php endif; ?>

										<?php else : ?>
											<?php echo __('No feedback settings'); ?>
										<?php endif; ?>
										</td>
										<td class="align-center">
											<?php $this->ComplianceAudits->statusLabels($item['ComplianceAuditSettingSingle']); ?>
										</td>

										<!-- @todo Hidden until new workflows are ready to put here -->
										<td class="text-center hidden">
											-
											<?php
											// echo $this->element('workflow/action_buttons_1', array(
											// 	'id' =>$item['ComplianceAuditSettingSingle']['id'],
											// 	'item' => $this->Workflow->getActions($item['ComplianceAuditSettingSingle'], $item['ComplianceAuditSettingSingle']['WorkflowAcknowledgement']),
											// 	'currentModel' => 'ComplianceAuditSetting'
											// ));
											?>
										</td>
										<td>
											<?php
											$auditees = array();
											if (!empty($item['ComplianceAuditSettingSingle']['Auditee'])) {
												foreach ($item['ComplianceAuditSettingSingle']['Auditee'] as $auditee) {
													$auditees[] = $auditee['name'] . ' ' . $auditee['surname'];
												}
											}

											echo implode(', ', $auditees);
											?>
										</td>
									</tr>
									<?php endforeach ; ?>
								</tbody>
							</table>
						<?php else : ?>
							<?php echo $this->element( 'not_found', array(
								'message' => __( 'No Compliance Package Items found.' )
							) ); ?>
						<?php endif; ?>

					</div>
				</div>

			<?php endforeach; ?>

			<?php //echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Compliance Packages found.' )
			) ); ?>
		<?php endif; ?>

		<div class="row">
			<div class="col-md-6">
				<?php
				echo $this->Html->tag('button', __('Close'), array('class' => 'btn btn-inverse', 'data-dismiss' => 'modal'));
			?>
			</div>
		</div>

	</div>

</div>