<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link( '<i class="icon-plus-sign"></i>' . __( 'Add New' ), array(
						'controller' => 'compliancePackages',
						'action' => 'add'
					), array(
						'class' => 'btn',
						'escape' => false
					) ); ?>

					<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'CompliancePackage'); ?>

					<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __( 'Actions' ); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right" style="text-align: left;">
						<li><a href="<?php echo Router::url( array( 'action' => 'import' ) ); ?>"><i class="icon-file"></i> <?php echo __( 'Import' ); ?></a></li>

						<li>
							<?php
							echo $this->Html->link(
								'<i class="icon-copy"></i> ' . __('Duplicate'),
								array('action' => 'duplicate'),
								array('escape' => false)
							);
							?>
						</li>
					</ul>

				</div>

				<?php echo $this->Video->getVideoLink('CompliancePackage'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
	</div>

</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'CompliancePackage')); ?>

<div class="row">
	<div class="col-md-12">
	<?php foreach ($groupList as $key => $ThirdParty) : ?>
		<?php
		if (empty($data[$key])) {
			continue;
		}
		?>
		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __('Third Party: %s', $ThirdParty); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
						<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
							<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
						</span>
						<ul class="dropdown-menu pull-right">
							<li><?php echo $this->Html->link( '<i class="icon-eye-open"></i> ' . __( 'View Third Party' ), array(
								'controller' => 'thirdParties',
								'action' => 'edit',
								$key
							), array(
								'escape' => false,
								'data-ajax-action' => 'edit'
							) ); ?></li>
							<li><?php echo $this->Html->link( '<i class="icon-plus-sign"></i> ' . __( 'Add Compliance Package' ), array(
								'controller' => 'compliancePackages',
								'action' => 'add',
								$key
							), array(
								'escape' => false,
								'data-ajax-action' => 'add'
							) ); ?></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if ( ! empty( $data[$key] ) ) : ?>
					<?php foreach ( $data[$key] as $entry ) : ?>
						<?php
						//sort items by item_id
						$entry['CompliancePackageItem'] = $this->CompliancePackageItems->sortByItemId($entry['CompliancePackageItem']);
						?>
						<?php $compliancePackage = $entry['CompliancePackage']; ?>
						<?php $compliancePackage['CompliancePackageItem'] = $entry['CompliancePackageItem']; ?>
						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Compliance Package' ); ?>: <?php echo $compliancePackage['name']; ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
										<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
											<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
										</span>
										<ul class="dropdown-menu pull-right">
											<li><?php echo $this->Html->link( '<i class="icon-pencil"></i> ' . __( 'Edit' ), array(
												'controller' => 'compliancePackages',
												'action' => 'edit',
												$compliancePackage['id']
											), array(
												'escape' => false,
												'data-ajax-action' => 'edit'
											) ); ?></li>
											<li><?php echo $this->Html->link( '<i class="icon-trash"></i> ' . __( 'Delete' ), array(
												'controller' => 'compliancePackages',
												'action' => 'delete',
												$compliancePackage['id'],
												'data-ajax-action' => 'delete'
											), array(
												'escape' => false,
												'data-ajax-action' => 'delete'
											) ); ?></li>
											<li><?php echo $this->Html->link( '<i class="icon-plus-sign"></i> ' . __( 'Add Compliance Package Item' ), array(
												'controller' => 'compliancePackageItems',
												'action' => 'add',
												$compliancePackage['id']
											), array(
												'escape' => false
											) ); ?></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if ( ! empty( $compliancePackage['CompliancePackageItem'] ) ) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
												<th>
												<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The Item ID field is usually used to define the unique number for a copmliance requirement. For example "1.1"' ); ?>'>
												<?php echo __( 'Item ID' ); ?>
												<i class="icon-info-sign"></i>
												</div>
												</th>
												<th>
												<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'help' ); ?>" data-content='<?php echo __( 'the item name is usually used for the title or the brief summary of the requirement. for example "networks are required to be segregated"' ); ?>'>
												<?php echo __( 'item name' ); ?>
												<i class="icon-info-sign"></i>
												</div>
												</th>
												<th>
												<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'help' ); ?>" data-content='<?php echo __( 'The item description is usually used for describing the requirement in detail' ); ?>'>
												<?php echo __( 'Item Description' ); ?>
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
											<?php foreach ( $compliancePackage['CompliancePackageItem'] as $compliancePackageItem ) : ?>
											<tr>
												<td><?php echo $compliancePackageItem['item_id']; ?></td>
												<td><?php echo $compliancePackageItem['name']; ?></td>
												<td><?php echo $this->Eramba->getEmptyValue($compliancePackageItem['description']); ?></td>
												<td class="align-center">
													<?php
													echo $this->Ajax->getActionList($compliancePackageItem['id'], array(
														'style' => 'icons',
														'notifications' => true,
														'disableEditAjax' => true,
														'controller' => 'compliancePackageItems',
														'model' => 'CompliancePackageItem',
														'item' => $compliancePackageItem,
														'history' => true
													));
													?>
												</td>
												<?php /*
												<td class="text-center">
													<?php
													echo $this->element('workflow/action_buttons_1', array(
														'id' => $compliancePackageItem['id'],
														'item' => $this->Workflow->getActions($compliancePackageItem),
														'currentModel' => 'CompliancePackageItem'
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
			</div>
		</div>
		<?php endforeach; ?>

		<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
	</div>
</div>
<script type="text/javascript">
jQuery(function($) {
/*$(".button-prompt-remove-compliance").off().on("click", function(e) {
	var r = confirm( "<?php echo __( 'Removing Compliance Package will also delete related Compliance Package Items, Compliance Management and Compliance Audits. Continue?' ); ?>" );
	if ( r == true ) {
		return true;
	} else {
		e.preventDefault();
		return false;
	}
});*/
});
</script>
