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

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'ThirdParty'); ?>

				<?php echo $this->NotificationSystem->getIndexLink('ThirdParty'); ?>

				<?php echo $this->Video->getVideoLink('ThirdParty'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'ThirdParty' => __('Third Party'),
				)); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>

		<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'ThirdParty')); ?>

		<div class="widget">
			<div class="widget-content">
				<?php if ( ! empty( $data ) ) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th>
									<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Provide a descriptive name for the Third Party.' ); ?>'>
									<?php echo $this->Paginator->sort( 'ThirdParty.name', __( 'Name' ) ); ?>
									<i class="icon-info-sign"></i>
									</div>
								</th>
								<th>
									<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Provide a brief description of the Third Party and how it affects your business.' ); ?>'>
									<?php echo $this->Paginator->sort( 'ThirdParty.description', __( 'Description' ) ); ?>
									<i class="icon-info-sign"></i>
									</div>
								</th>
								<th>
									<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The type of Third Party is important as it defines the nature of the relationship. This is simply used as a descriptive field. There are three types: Customers, Regulators and Suppliers.' ); ?>'>
									<?php echo $this->Paginator->sort( 'ThirdPartyType.name', __( 'Type' ) ); ?>
									<i class="icon-info-sign"></i>
									</div>
								</th>
								<th>
									<?php echo __( 'Sponsor' ); ?>
								</th>
								<th class="align-center">
									<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Risks identified for this Third Parties will be magnified by associating this object with existing Liabilities. See the Risk Management user manual for more information.' ); ?>'>
									<?php echo __( 'Liabilities' ); ?>
									<i class="icon-info-sign"></i>
									</div>
								</th>
								<th>
									<?php echo __( 'Status' ); ?>
								</th>
								<?php if (!empty($customFields_enabled) && !empty($customFields_data[0]['CustomField'])) : ?>
									<th><?php echo __('Custom Fields') ?></th>
								<?php endif; ?>
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
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td><?php echo $entry['ThirdParty']['name']; ?></td>
									<td><?php echo $this->Eramba->getEmptyValue($entry['ThirdParty']['description']); ?></td>
									<td><?php echo $entry['ThirdPartyType']['name']; ?></td>
									<td><?= $this->UserField->showUserFieldRecords($entry['Sponsor']); ?></td>
									<td>
										<?php
										$legals = array();
										foreach ($entry['Legal'] as $legal) {
											$legals[] = $legal['name'];
										}
										echo implode(', ', $legals);
										?>
									</td>
									<td>
										<?php
										echo $this->ThirdParties->getStatuses($entry, true);
										?>
									</td>
									<?php if (!empty($customFields_enabled) && !empty($customFields_data[0]['CustomField'])) : ?>
										<td><?php echo $this->CustomFields->advancedFilterLink($customFields_data, array('id', 'name'), array('id' => $entry['ThirdParty']['id'])); ?></td>
									<?php endif; ?>
									<td class="align-center">
											<?php
											echo $this->Ajax->getActionList($entry['ThirdParty']['id'], array(
												'style' => 'icons',
												'notifications' => true,
												'item' => $entry,
												'history' => true
											));
											?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['ThirdParty']['id'],
											'item' => $this->Workflow->getActions($entry['ThirdParty'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Third Parties found.' )
					) ); ?>
				<?php endif; ?>

			</div>

		</div>
	</div>

</div>
<script type="text/javascript">
jQuery(function($) {
$(".button-prompt-remove").off().on("click", function(e) {
	var r = confirm( "<?php echo __( 'Removing Third Party will also delete related Third Party Risks, Compliance Packages, Compliance Package Items, Complinace Management and Compliance Audits. Continue?' ); ?>" );
	if ( r == true ) {
		return true;
	} else {
		e.preventDefault();
		return false;
	}
});
});
</script>
