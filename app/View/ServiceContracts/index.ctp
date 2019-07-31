<div class="row">

	<div class="col-md-9">
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

				<?php echo $this->NotificationSystem->getIndexLink('ServiceContract'); ?>

				<?php echo $this->Video->getVideoLink('ServiceContract'); ?>
			</div>
		</div>
	</div>

</div>

<div class="row">

	<div class="col-md-12">

		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				$extra_class = '';
				foreach ( $entry['ServiceContract'] as $service_contract ) {
					if ( ! $extra_class && $this->App->isExpired( $service_contract['end'] ) ) {
						$extra_class = 'widget-header-alert';
					}
				}
				?>
				<div class="widget box widget-closed">
					<div class="widget-header <?php echo $extra_class; ?>">
						<h4><?php echo $entry['ThirdParty']['name']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<ul class="dropdown-menu pull-right">
									<li><?php echo $this->Html->link( '<i class="icon-plus-sign"></i> ' . __( 'Add Service Contract' ), array(
										'controller' => 'serviceContracts',
										'action' => 'add',
										$entry['ThirdParty']['id']
									), array(
										'escape' => false,
										'data-ajax-action' => 'add'
									) ); ?></li>
								</ul>
							</div>
						</div>
					</div>
					<div class="widget-content" style="display:none;">
						<?php if ( ! empty( $entry['ServiceContract'] ) ) : ?>
							<table class="table table-hover table-striped">
								<thead>
									<tr>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the support contract. For example: "Firewalls Onsite Support"' ); ?>'>
							<?php echo __( 'Contract Name' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The description of the support contract.' ); ?>'>
							<?php echo __( 'Description' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The cost of the support contract.' ); ?>'>
							<?php echo __( 'Value' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date where the contract begins.' ); ?>'>
							<?php echo __( 'Start' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date where the contract ends. You can setup a notification for this date.' ); ?>'>
							<?php echo __( 'End Date' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
						<th>
						        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( '"Expired (Red)" - when the date set is in the past. A system record is generated on the contract when that happens.' ); ?>'>
							<?php echo __( 'Status' ); ?>
						        <i class="icon-info-sign"></i>
						        </div>
						</th>
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Use these icons in order to view the details of this object, system records such as when the item was created or modified, add or review comments or simply delete the item.' ); ?>'>
							<?php echo __( 'Actions' ); ?>
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
									<?php foreach ( $entry['ServiceContract'] as $service_contract ) : ?>
									<tr>
										<td><?php echo $service_contract['name']; ?></td>
										<td><?php echo $this->Eramba->getEmptyValue($service_contract['description']); ?></td>
										<td><?php echo CakeNumber::currency( $service_contract['value'] ); ?></td>
										<td><?php echo $service_contract['start']; ?></td>
										<td><?php echo $service_contract['end']; ?></td>
										<td>
											<?php
											echo $this->ServiceContracts->getStatuses($service_contract, true);
											?>
										</td>
										<td class="align-center">
											<?php
											echo $this->Ajax->getActionList($service_contract['id'], array(
												'style' => 'icons',
												'notifications' => true,
												'item' => $service_contract
											));
											?>
										</td>
										<?php /*
										<td class="text-center">
											<?php
											echo $this->element('workflow/action_buttons_1', array(
												'id' => $service_contract['id'],
												'item' => $this->Workflow->getActions($service_contract, $service_contract['WorkflowAcknowledgement'])
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
								'message' => __( 'No Service Contracts found.' )
							) ); ?>
						<?php endif; ?>

					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Third Parties contaning Service Contracts found.' )
			) ); ?>
		<?php endif; ?>

	</div>

</div>
