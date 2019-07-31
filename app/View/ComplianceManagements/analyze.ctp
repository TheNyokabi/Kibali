<div class="row">

	<div class="col-md-12">
		<?php if ( ! empty( $data['CompliancePackage'] ) ) : ?>
			<?php foreach ( $data['CompliancePackage'] as $entry ) : ?>
				<?php 
				$allEmpty = true;
				foreach ( $entry['CompliancePackageItem'] as $item ) {
					// this condition works only as customization for filtering a single item
					if (!(!empty($this->request->query['id']) && (empty($item['ComplianceManagement']) || $item['ComplianceManagement']['id'] != $this->request->query['id']))) {
						$allEmpty = false;
						break;
					}
				}
				if ($allEmpty) {
					continue;
				}
				?>
				<div class="widget box">
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
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Treatment options are: Compliant, Non-Compliant or Not-Applicable (when the requirement is simply not applicable to the organisation).' ); ?>'>
											<?php echo __( 'Compliance Status' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th>
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If controls are used to mitigate this requirement' ); ?>'>
											<?php echo __( 'Mitigation Controls' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th>
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If policy items are used to mitigate this requirement' ); ?>'>
											<?php echo __( 'Policy Items' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th>
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If Compliance Exceptions are used in this requirement. Compliance exceptions are used when the strategy is Non-Compliant or Not Applicable.' ); ?>'>
											<?php echo __( 'Exception' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th>
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If Risks (of any type) are applicable for this requirement' ); ?>'>
											<?php echo __( 'Risks' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th>
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'If there are liabilities asociated with this requirement' ); ?>'>
											<?php echo __( 'Liabilities' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<th>
										     <?php echo __( 'Projects' ); ?>
										</th>
										<th>
										     <?php echo __( 'Owner' ); ?>
										</th>
										<th>
										        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Any other description used to describe how this requirement is being treated' ); ?>'>
											<?php echo __( 'Description' ); ?>
										        <i class="icon-info-sign"></i>
										        </div>
										</th>
										<?php
											if (!empty($customFields_enabled) && !empty($customFields_data[0]['CustomField'])) : ?>
											<th><?php echo __('Custom Fields') ?></th>
										<?php endif; ?>
										<th><?php echo __('Active Status'); ?></th>
										<th class="align-center">
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Use these icons in order to view the details of this object, system records such as when the item was created or modified, add or review comments or simply delete the item.' ); ?>'>
										<?php echo __( 'Actions' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $entry['CompliancePackageItem'] as $item ) : ?>
									<?php
									// this condition works only as customization for filtering a single item
									if (!empty($this->request->query['id'])) {
										if (empty($item['ComplianceManagement']) || $item['ComplianceManagement']['id'] != $this->request->query['id']) {
											continue;
										}
									}

									$rowClass = '';
									$firstColsClass = '';
									if (empty($item['ComplianceManagement'])) {
										$rowClass = '';
									}
									elseif ($item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_APPLICABLE) {
										$rowClass = '';
										$firstColsClass = 'grey';
									}
									elseif ($item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_NOT_COMPLIANT) {
										$firstColsClass = 'danger';
									}
									elseif ($item['ComplianceManagement']['compliance_treatment_strategy_id'] == COMPLIANCE_TREATMENT_COMPLIANT) {
										$firstColsClass = 'success';
									}
									?>
									<tr class="popover-has-label row-cells-border <?php echo $rowClass; ?>">
										<td class="<?php echo $firstColsClass; ?>"><?php echo $item['item_id']; ?></td>
										<td class="<?php echo $firstColsClass; ?>">
											<?php $popupTitle = "<strong>Title:</strong> ". (!empty($item['name'])?$item['name']:'None')."<br/><br/>"; ?>
											<?php $popupDesc = "<strong>Description:</strong> ". (!empty($item['description'])?nl2br(h($item['description'])):'None')."<br/><br/>"; ?>
											<?php $popupAudit = "<strong>Audit Criteria:</strong> ". (!empty($item['audit_questionaire'])?nl2br(h($item['audit_questionaire'])):'None'); ?>
											<div class="bs-popover" data-trigger="hover" data-placement="right" data-html="true" data-original-title="<?php echo __( 'Description' ); ?>" data-content='<?php echo $popupTitle.$popupDesc.$popupAudit ; ?>'>
												<?php echo $this->Text->truncate($item['name'], 30); ?>
												<i class="icon-info-sign"></i>
											</div>
										</td>

										<?php if ( ! empty( $item['ComplianceManagement'] ) ) : ?>
											<td class="<?php echo $firstColsClass; ?>">
												<?php
												if (!empty($item['ComplianceManagement']['compliance_treatment_strategy_id'])) {
													echo $complianceTreatmentStrategies[ $item['ComplianceManagement']['compliance_treatment_strategy_id'] ];
												}
												else {
													echo __('Undefined');
												}
												?>
											</td>

											<?php
											$securityServices = array();
											$types = array();
											$allow = array(
												'audits_last_passed',
												'audits_last_missing',
												'maintenances_last_missing',
												'ongoing_corrective_actions',
												'security_service_type_id',
												'control_with_issues'
											);
											foreach ($item['ComplianceManagement']['SecurityService'] as $control) {
												$securityServices[] = $control['name'] . ' ' . $this->SecurityServices->getStatuses($control, array(
														'allow' => $allow
													));

												$types[] = $this->SecurityServices->getStatusClass($control, 'SecurityServices', $allow);
											}

											$extra_class = getWorstColorType($types);
											?>
											<td class="<?php echo $extra_class; ?>">
												<?php if (!empty($securityServices)) : ?>
													<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-html="true"
													data-original-title="<?php echo __('Mitigation Controls'); ?>"
													data-content='<?php echo h(implode('<br />', $securityServices)); ?>'>

														<?php echo __('Yes'); ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo __('No'); ?>
												<?php endif; ?>
											</td>

											<?php
											$securityPolicies = array();
											$types = array();
											foreach ($item['ComplianceManagement']['SecurityPolicy'] as $policy) {
												$securityPolicies[] = $policy['index'] . ' ' . $this->SecurityPolicies->getStatuses($policy);
												$types[] = $this->SecurityPolicies->getStatusClass($policy, 'SecurityPolicies');

												/*if (!$policy['status']) {
													$extra_class = 'danger';
													break;
												}*/
											}

											$extra_class = getWorstColorType($types);
											?>
											<td class="<?php echo $extra_class; ?>">
												<?php if (!empty($securityPolicies)) : ?>
													<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-html="true"
													data-original-title="<?php echo __('Policy Items'); ?>"
													data-content='<?php echo h(implode('<br />', $securityPolicies)); ?>'>

														<?php echo __('Yes'); ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo __('No'); ?>
												<?php endif; ?>
											</td>

											<?php
											// $types = array();
											// $today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
											$exception = $item['ComplianceManagement']['ComplianceException'];
											/*if (!empty($exception) && $exception['status'] == 1 && $exception['expiration'] < $today) {
												$extra_class = 'danger';
											}*/
											$extra_class = '';

											if (!empty($exception)) {
												foreach ($exception as $exceptionItem) {
													$extra_class .= ' ' . $this->PolicyExceptions->getStatusClass(['ComplianceException' => $exceptionItem], 'ComplianceException', array('expired'));
												}
											}
											?>
											<td class="<?php echo $extra_class; ?>">
												<?php if ($item['ComplianceManagement']['ComplianceException']) : ?>
													<?php
													$e = [];
													foreach ($item['ComplianceManagement']['ComplianceException'] as $exceptionItem) {
														$e[] = $exceptions[$exceptionItem['id']] . ' ' . $this->PolicyExceptions->getStatuses(['ComplianceException' => $exceptionItem], 'ComplianceException', array('expired'));
													}
													?>
													<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-html="true"
													data-original-title="<?php echo __('Exception'); ?>"
													data-content='<?php echo h(implode('<br>', $e)); ?>'>

														<?php echo __('Yes'); ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo __('No'); ?>
												<?php endif; ?>
											</td>

											<?php
											$risks = array();
											$types = array();
											$opts = array(
												'allow' => array('expired_reviews', 'risk_above_appetite')
											);
											foreach ($item['ComplianceManagement']['Risk'] as $risk) {
												$risks[] = $risk['title'] . ' ' . $this->Risks->getStatuses($risk, 'Risk', $opts);
												$types[] = $this->Risks->getStatusClass($risk, 'Risk', $opts['allow']);
											}
											foreach ($item['ComplianceManagement']['ThirdPartyRisk'] as $risk) {
												$risks[] = $risk['title'] . ' ' . $this->Risks->getStatuses($risk, 'ThirdPartyRisk', $opts);
												$types[] = $this->Risks->getStatusClass($risk, 'ThirdPartyRisk', $opts['allow']);
											}
											foreach ($item['ComplianceManagement']['BusinessContinuity'] as $risk) {
												$risks[] = $risk['title'] . ' ' . $this->Risks->getStatuses($risk, 'BusinessContinuity', $opts);
												$types[] = $this->Risks->getStatusClass($risk, 'BusinessContinuity', $opts['allow']);
											}

											$extra_class = getWorstColorType($types);
											?>
											<td class="<?php echo $extra_class; ?>">
												<?php if (!empty($risks)) : ?>
													<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-html="true"
													data-original-title="<?php echo __('Risks'); ?>"
													data-content='<?php echo h(implode('<br />', $risks)); ?>'>

														<?php echo __('Yes'); ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo __('No'); ?>
												<?php endif; ?>
											</td>

											<td>
												<?php if ($item['ComplianceManagement']['legal_id']) : ?>
													<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-original-title="<?php echo __('Liabilities'); ?>"
													data-content='<?php echo h($item['ComplianceManagement']['Legal']['name']); ?>'>

														<?php echo __('Yes'); ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo __('No'); ?>
												<?php endif; ?>
											</td>

											<?php
											$projects = array();
											$types = array();
											foreach ($item['ComplianceManagement']['Project'] as $project) {
												$projects[] = $project['title'] . ' ' . $this->Projects->getStatuses($project);
												$types[] = $this->Projects->getStatusClass($project);
											}

											$extra_class = getWorstColorType($types);
											?>
											<td class="<?php echo $extra_class; ?>">
												<?php if (!empty($projects)) : ?>
													<div class="bs-popover"
													data-trigger="hover"
													data-placement="top"
													data-html="true"
													data-original-title="<?php echo __('Projects'); ?>"
													data-content='<?php echo h(implode('<br />', $projects)); ?>'>

														<?php echo __('Yes'); ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo __('No'); ?>
												<?php endif; ?>
											</td>

											<td>
												<?php
												if (!empty($item['ComplianceManagement']['Owner'])) {
													echo $item['ComplianceManagement']['Owner']['name'] . ' ' . $item['ComplianceManagement']['Owner']['surname'];
												}
												else {
													echo __('None');
												}
												?>
											</td>

											<?php
											$extra_class = '';
											if (!empty($item['ComplianceManagement']['description'])) {
												$extra_class = '';
											}
											?>
											<td class="<?php echo $extra_class; ?>">
												<?php if ($item['ComplianceManagement']['description']) : ?>
													<div class="bs-popover" data-trigger="hover" data-placement="left" data-original-title="<?php echo __( 'Description' ); ?>" data-content='<?php echo h($item['ComplianceManagement']['description']); ?>'>
														<?php echo __('Yes') ?>
														<i class="icon-info-sign"></i>
													</div>
												<?php else : ?>
													<?php echo __('No'); ?>
												<?php endif; ?>
											</td>
											<?php if (!empty($customFields_enabled) && !empty($customFields_data[0]['CustomField'])) : ?>
												<td><?php echo $this->CustomFields->advancedFilterLink($customFields_data, array('third_party', 'item_id', 'item_name'), array('id' => $item['ComplianceManagement']['id'])); ?></td>
											<?php endif; ?>
											<td>
												<?php
												echo $this->ComplianceManagements->getStatuses($item['ComplianceManagement'], null, true);
												?>
											</td>
											<td class="align-center">
												<?php
												if (empty($item['ComplianceManagement'])) {
													/*$addAnalysisUrl = array(
														'controller' => 'complianceManagements',
														'action' => 'add',
														$item['id']
													);

													$this->Ajax->addToActionList(__('Add analysis'), $addAnalysisUrl, 'plus-sign', 'add');*/
												}
												else {
													$editAnalysisUrl = array(
														'controller' => 'complianceManagements',
														'action' => 'edit',
														$item['ComplianceManagement']['id']
													);

													$this->Ajax->addToActionList(__('Edit analysis'), $editAnalysisUrl, 'pencil', 'edit');
												}

												echo $this->Ajax->getActionList($item['ComplianceManagement']['id'], array(
													'style' => 'icons',
													'edit' => false,
													'trash' => false,
													'model' => 'ComplianceManagement',
													'item' => $item
												));
												?>
											</td>
										<?php else : ?>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>-</td>
											<td>
												<?php
												echo $this->ComplianceManagements->getStatuses($item['ComplianceManagement']);
												?>
											</td>
											<td class="align-center">
											<?php
											$addAnalysisUrl = array(
												'controller' => 'complianceManagements',
												'action' => 'add',
												$item['id']
											);

											$this->Ajax->addToActionList(__('Add analysis'), $addAnalysisUrl, 'plus-sign', 'add');

											echo $this->Ajax->getUserDefinedActionList(array(
												'item' => $item
											));
											?>
											</td>
										<?php endif; ?>
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

		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Compliance Packages found.' )
			) ); ?>
		<?php endif; ?>

		<div>
			<?php
				echo $this->Html->tag('button', __('Close'), array('class' => 'btn btn-inverse', 'data-dismiss' => 'modal'));
			?>
		</div>
	</div>

</div>
