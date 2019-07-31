<?php
App::uses('AppModule', 'Lib');
App::uses('Hash', 'Utility');
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'ComplianceException'); ?>
				
				<?php echo $this->NotificationSystem->getIndexLink('ComplianceException'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'ComplianceException' => __('Compliance Exception'),
				)); ?>

				<?php echo $this->Video->getVideoLink('ComplianceException'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>

				<?php //echo $this->element( CORE_ELEMENT_PATH . 'filter' , array('filterElement' => $this->element(FILTERS_ELEMENT_PATH . 'filter_compliance_exception'))); ?>
			</div>
		</div>
	</div>

</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'ComplianceException')); ?>
<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				$widgetClass = $this->PolicyExceptions->getHeaderClass($entry, 'ComplianceException');
				?>
				<div class="widget box widget-closed <?php echo $widgetClass; ?>">
					<div class="widget-header">
						<h4><?php echo __('Compliance Exeptions'); ?>: <?php echo $entry['ComplianceException']['title']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
								$exportUrl = array(
									'controller' => 'complianceExceptions',
									'action' => 'exportPdf',
									 $entry['ComplianceException']['id']
								);

								$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

								echo $this->Ajax->getActionList($entry['ComplianceException']['id'], array(
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
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The list of compliance packages that use this compliance exception. If this list is empty then the exception is not being used.' ); ?>'>
        <?php echo __( 'Compliance Packages' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is the individual that approved the compliance exception' ); ?>'>
        <?php echo __( 'Requester' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is the date when the compliance exception expired and should be reviewed for applicability' ); ?>'>
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
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is the number of times that this compliance exception has been mapped to a compliance requirement.' ); ?>'>
        <?php echo __( 'Compliance Package Count' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
	<?php
	echo __('Tags');
	?>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Compliance Exceptions can have the following status: "Expired (red)" - when the date set is in the past. A system record is generated on the exception when that happens.' ); ?>'>
        <?php echo __( 'Status' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<?php
										$packageNames = Hash::extract($entry, 'ComplianceManagement.{n}.CompliancePackageItem.CompliancePackage.ThirdParty.name');
										echo $this->Eramba->getEmptyValue(implode(', ', $packageNames));
										?>
									</td>
									<td><?= $this->UserField->showUserFieldRecords($entry['Requestor']); ?></td>
									<td><?php echo $this->Ux->date($entry['ComplianceException']['expiration']); ?></td>
									<td><?php echo $this->Ux->date($entry['ComplianceException']['closure_date']); ?></td>
									<td class="text-center"><?php echo count($entry['ComplianceManagement']) ?></td>
									<td>
										<?php
										echo $this->ComplianceExceptions->getTags($entry);
										?>
									</td>
									<td>
										<?php
										echo $this->PolicyExceptions->getStatuses($entry, 'ComplianceException', true);
										?>
									</td>
								</tr>
							</tbody>
						</table>
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th><?php echo __( 'Description' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['ComplianceException']['description']); ?></td>
								</tr>
							</tbody>
						</table>
						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Compliance Packages' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<table class="table table-hover table-striped table-bordered table-highlight-head">
									<thead>
										<tr>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Is the name of the compliance package, the one that holds all the compliance requiremnts for given third party.' ); ?>'>
	<?php echo __( 'Compliance Package' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The ID of the specific requirement (Compliance Package Item)' ); ?>'>
	<?php echo __( 'Item ID' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
<th>
        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the compliance package item.' ); ?>'>
	<?php echo __( 'Compliance Package Item' ); ?>
        <i class="icon-info-sign"></i>
        </div>
</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($entry['ComplianceManagement'] as $key => $val): ?>
											<tr>
												<td><?php echo $val['CompliancePackageItem']['CompliancePackage']['ThirdParty']['name'] ?></td>
												<td>
													<?php
													// $viewUrl = $complianceManagementViewItemUrl;
													// $viewUrl['?'] = array(
													// 	'id' => $val['id']
													// );
													// echo $this->Ajax->popupLink($val['CompliancePackageItem']['item_id'], $viewUrl);
													?>
													<?php
													echo $this->AdvancedFilters->getItemFilteredLink(
														$val['CompliancePackageItem']['item_id'],
														'ComplianceManagement',
														$val['id']
													);
													?>
												</td>
												<td><?php echo $val['CompliancePackageItem']['name'] ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>

						<?php
						echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
							'item' => $entry // single database item in a variable
						));
						?>
					</div>
					<div class="widget-content" style="display:none;">

					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Compliance Exeptions found.' )
			) ); ?>
		<?php endif; ?>
	</div>
</div>
