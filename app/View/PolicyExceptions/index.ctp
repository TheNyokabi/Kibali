<?php
echo $this->Html->script('policy-document', array('inline' => false));
echo $this->Html->css('policy-document', array('inline' => false));
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>

					<?php
					// echo $this->Html->link( '<i class="icon-cog"></i>' . __('Workflow'), array(
					// 	'controller' => 'workflows',
					// 	'action' => 'edit',
					// 	$workflowSettingsId
					// ), array(
					// 	'class' => 'btn',
					// 	'escape' => false
					// ));
					?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'PolicyException'); ?>

				<?php echo $this->NotificationSystem->getIndexLink('PolicyException'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'PolicyException' => __('Policy Exception'),
				)); ?>

				<?php echo $this->Video->getVideoLink('PolicyException'); ?>
				
				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
				
				<?php //echo $this->element( CORE_ELEMENT_PATH . 'filter' , array('filterElement' => $this->element(FILTERS_ELEMENT_PATH . 'filter_policy_exception'))); ?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'PolicyException')); ?>

<div class="row">
	<div class="col-md-12">
			<?php if ( ! empty( $data ) ) : ?>
				<?php foreach ( $data as $entry ) : ?>
					<?php
					$widgetClass = $this->PolicyExceptions->getHeaderClass($entry, 'PolicyException');
					?>
					<div class="widget box widget-closed <?php echo $widgetClass; ?>">
						<div class="widget-header">
							<h4><?php echo __('Policy Exception'); ?>: <?php echo $entry['PolicyException']['title']; ?></h4>
							<div class="toolbar no-padding">
								<div class="btn-group">
									<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
										<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
									</span>
									<?php
									echo $this->Ajax->getActionList($entry['PolicyException']['id'], array(
										'notifications' => true,
										'item' => $entry,
										'history' => true,
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
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is usually the individual who requested the exception.' ); ?>'>
							<?php echo __( 'Requester' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Some policy exceptions affect third parties (such as not complying with a third party requirment), in such cases is good idea to asociate the affected third party for future analysis.' ); ?>'>
							<?php echo __( 'Third Parties' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Exceptions as such are not eternal, each exception needs to have a defined expiration date. You can optionally set notifications to the requester in order to notify him when the expiration date is met.' ); ?>'>
							<?php echo __( 'Expiration' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
							    <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'TBD' ); ?>'>
								<?php echo __('Closure date'); ?>
							        <i class="icon-info-sign"></i>
							    </div>
							</th>
						<th>
							<?php
							echo __('Tags');
							?>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The available status for policy exceptions is: "Expired (Red)" - when the expiration date set is in the past. A system record is generated on the exception when that happens.' ); ?>'>
							<?php echo __( 'Status' ); ?>
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
									<tr>
										<td><?= $this->UserField->showUserFieldRecords($entry['Requestor']); ?></td>
										<td>
											<?php
												$third_parties = array();
												foreach ($entry['ThirdParty'] as $value) {
													$third_parties[] = $value['name'];
												}
												echo implode($third_parties, ', ');
											?>
										</td>
										<td><?php echo $this->Ux->date($entry['PolicyException']['expiration']); ?></td>
										<td><?php echo $this->Ux->date($entry['PolicyException']['closure_date']); ?></td>
										<td>
											<?php if ( ! empty( $entry['Classification'] ) ) : ?>
												<?php 
												foreach ($entry['Classification'] as $tag) {
													echo $this->Html->tag('span', h($tag['name']), array('class' => 'label label-info')) . ' ';
												}
												?>
											<?php else : ?>
												<?php echo getEmptyValue(null); ?>
											<?php endif; ?>
										</td>
										<td class="text-center">
											<?php
											echo $this->PolicyExceptions->getStatuses($entry, 'PolicyException', true);
											?>
										</td>
										<?php /*
										<td class="text-center">
											<?php
											echo $this->element('workflow/action_buttons_1', array(
												'id' => $entry['PolicyException']['id'],
												'item' => $this->Workflow->getActions($entry['PolicyException'], $entry['WorkflowAcknowledgement'])
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
										<td><?php echo $this->Eramba->getEmptyValue($entry['PolicyException']['description']); ?></td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="widget-content" style="display: block;">
							<div class="widget box widget-closed">
								<div class="widget-header">
									<h4><?php echo __('Security Policies'); ?></h4>
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
												<th><?php echo __('Index'); ?></th>
												<th><?php echo __('Author'); ?></th>
												<th><?php echo __('Status'); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $entry['SecurityPolicy'] as $policy ) : ?>
											<tr>
												<td>
													<?php echo $this->Ux->getItemLink($policy['index'], 'SecurityPolicy', $policy['id']);?>
													<?php echo $this->SecurityPolicies->documentLink($policy); ?>
												<td>
													<?= $this->UserField->convertAndShowUserFieldRecords('SecurityPolicy', 'Owner', $policy); ?>
												</td>
												<td>
													<?php
													$labelClass = $policy['status']?'label-success':'label-danger'
													?>
													<span class="label <?php echo $labelClass ?>">
														<?php echo $statuses[$policy['status']]; ?>
													</span>
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
							$assetWidgetData = (!empty($assetData['joinIds'][$entry['PolicyException']['id']])) ? $assetData['joinIds'][$entry['PolicyException']['id']] : [];
							echo $this->element('risks/assets', array(
								'data' => $assetWidgetData
							));
							?>
						</div>

						<div class="widget-subheader">
							<?php
							echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
								'item' => $entry // single database item in a variable
							));
							?>
					    </div>

					</div>
				<?php endforeach; ?>

				<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
			<?php else : ?>
				<?php echo $this->element( 'not_found', array(
					'message' => __( 'No Policy Exceptions found.' )
				) ); ?>
			<?php endif; ?>
		</div>

	</div>
</div>
