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
							echo $this->Html->link(__('Business Unit'), array(
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
							echo $this->Html->link(__('Process'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$processWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'BusinessUnit'); ?>

				<?php
				echo $this->NotificationSystem->getIndexLink(array(
					'BusinessUnit' => __('Business Unit'),
					'Process' => __('Process')
				));

				echo $this->CustomFields->getIndexLink(array(
					'BusinessUnit' => __('Business Unit'),
					'Process' => __('Process'),
				));
				?>

				<?php echo $this->Video->getVideoLink('BusinessUnit'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>

		<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'BusinessUnit')); ?>

		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<div class="widget box widget-closed">
					<div class="widget-header">
						<h4><?php echo __( 'Business Unit Name:' ) . ' ' . $entry['BusinessUnit']['name']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
								$processUrl = array(
									'controller' => 'processes',
									'action' => 'add',
									$entry['BusinessUnit']['id']
								);

								$this->Ajax->addToActionList(__('Add Process'), $processUrl, 'plus-sign', 'add');

								echo $this->Ajax->getActionList($entry['BusinessUnit']['id'], array(
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
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Business Unit name.' ); ?>'>
												<?php echo __( 'Description' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
										<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Libilities that impact this business unit. Depending on the risk calculation you use, these liabilities will later affect risk scores under Risk Management / Business Impact Analysis' ); ?>'>
												<?php echo __( 'Liabilities' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
										<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The Business Unit owner is the individual accountable for that area of the business.' ); ?>'>
												<?php echo __( 'Business Owner' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
										<?php /*
										<th>
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
											<?php echo $entry['BusinessUnit']['id']; ?>
										</td>
										<td>
											<?php echo $this->Eramba->getEmptyValue($entry['BusinessUnit']['description']); ?>
										</td>
										<td>
											<?php if (!empty($entry['Legal'])) : ?>
												<?php
													$legals = array();
													foreach ($entry['Legal'] as $legal) {
														$legals[] = $legal['name'];
													}
													echo implode(', ', $legals);
												?>
											<?php endif; ?>
										</td>
										<td>
											<?= $this->UserField->showUserFieldRecords($entry['BusinessUnitOwner']); ?>
										</td>
										<?php /*
										<td class="text-center">
											<?php
											echo $this->element('workflow/action_buttons_1', array(
												'id' => $entry['BusinessUnit']['id'],
												'item' => $this->Workflow->getActions($entry['BusinessUnit'], $entry['WorkflowAcknowledgement'])
											));
											?>
										</td>
										*/ ?>
									</tr>
								</tbody>
							</table>
					</div>
					
					<div class="widget-content" style="display:none;">
						<?php
						echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
							'item' => $entry
						));
						?>

						<?php if ( ! empty( $entry['Process'] ) ) : ?>
							<table class="table table-hover table-striped">
								<thead>
									<tr>
										<th><?php echo __( 'Process Name' ); ?></th>
										<th><?php echo __( 'Process Description' ); ?></th>
										<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. How long (in days, hours, etc) should it take to get things back on (this should be less or equal to MTO). Most business people usually reply ASAP, but in practice that might not be accurate.' ); ?>'>
												<?php echo __( 'RTO' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
										<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. How long things can be broken before they become a serious business problem.' ); ?>'>
												<?php echo __( 'MTO' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
										<th><?php echo __( 'Revenue per Hour' ); ?></th>
										<th>
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. This should be used as a prioritization tool in order to Risk analyse those business that loss is higher. The calculation is done as Revenue per Hour / MTO).' ); ?>'>
												<?php echo __( 'BIA Priority' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
										<?php if (!empty($process_customFields_enabled) && !empty($process_customFields_data)) : ?>
											<th><?php echo __('Custom Fields') ?></th>
										<?php endif; ?>
										<th class="align-center">
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Use these icons in order to view the details of this object, system records such as when the item was created or modified, add or review comments or simply delete the item.' ); ?>'>
										<?php echo __( 'Action' ); ?>
												<i class="icon-info-sign"></i>
											</div>
										</th>
										<?php /*
										<th class="text-center">
											<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Workflows define the approvals required to create, modify or delete an item. Approved items are valid throughout the system, Draft items require approval and Pending Approvals or Validations means that the workflow is still in process and is pending user interaction.' ); ?>'>
										<?php echo __('Workflow'); ?>
											<i class="icon-info-sign"></i>
											</div>
										</th>
										*/ ?>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $entry['Process'] as $process ) : ?>
									<tr>
										<td><?php echo $process['name']; ?></td>
										<td><?php echo $this->Eramba->getEmptyValue($process['description']); ?></td>
										<td><?php echo __n( '%d Hour', '%d Hours', $process['rto'], $process['rto'] ); ?></td>
										<td><?php echo __n( '%d Hour', '%d Hours', $process['rpo'], $process['rpo'] ); ?></td>
										<td><?php echo CakeNumber::currency( $process['rpd'] ); ?></td>
										<td><?php
											if($process['rpo']){
												echo CakeNumber::currency( $process['rpd']/$process['rpo']  ).'/hs';
											}
											else{
												echo CakeNumber::currency( 0 ).'/hs';
											}

										?></td>
										<?php if (!empty($process_customFields_enabled) && !empty($process_customFields_data)) : ?>
											<td><?php echo $this->CustomFields->advancedFilterLink($process_customFields_data, array('id', 'name'), array('id' => $process['id'])); ?></td>
										<?php endif; ?>
										<td class="align-center">
											<?php
											echo $this->Ajax->getActionList($process['id'], array(
												'notifications' => true,
												'style' => 'icons',
												'controller' => 'processes',
												'model' => 'Process',
												'item' => $process,
												'history' => true
											));
											?>
										</td>
										<?php /*
										<td class="text-center">
											<?php
											echo $this->element('workflow/action_buttons_1', array(
												'id' => $process['id'],
												'parentId' => $entry['BusinessUnit']['id'],
												'item' => $this->Workflow->getActions($process, $process['WorkflowAcknowledgement']),
												'currentModel' => 'Process'
											));
											?>
										</td>
										*/ ?>
									</tr>
									<?php endforeach ; ?>
								</tbody>
							</table>
						<?php else : ?>
							<?php echo $this->element( 'not_found', array(
								'message' => __( 'No Business Processes found.' )
							) ); ?>
						<?php endif; ?>

					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Business Units found.' )
			) ); ?>
		<?php endif; ?>

	</div>

</div>
<script type="text/javascript">
jQuery(function($) {
$(".button-prompt-remove-bu").on("click", function(e) {
	var r = confirm( "<?php echo __( 'Removing Business Unit will also delete related Business Processes, Assets and Risks. Continue?' ); ?>" );
	if ( r == true ) {
		return true;
	} else {
		e.preventDefault();
		return false;
	}
});
});
</script>
