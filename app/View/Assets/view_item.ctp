<?php
$widgetClass = $this->Assets->getHeaderClass($asset, 'Asset');
?>
<div class="widget box widget-closed <?php echo $widgetClass; ?>">
	<div class="widget-header">
		<h4><?php echo __( 'Asset' ); ?>: <?php echo $asset['Asset']['name']; ?></h4>
		<div class="toolbar no-padding">
			<div class="btn-group">
				<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
				<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
					<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
				</span>
				<?php
				echo $this->Assets->getActionList($asset, array(
					'notifications' => true,
					'item' => $asset,
					'history' => true
				));
				?>
			</div>
		</div>
	</div>
	<div class="widget-subheader">
		<table class="table table-hover table-striped table-bordered table-highlight-head">
			<thead>
				<tr>
					<th><?php echo __( 'Description' ); ?></th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'What type of asset is this, remember you can define further types at Asset Management / Settings / Asset Types' ); ?>'>
							<?php echo __( 'Type' ); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'What labels apply to this asset. For example: Confidential, Restricited, Public, Etc' ); ?>'>
							<?php echo __( 'Label' ); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The liabilities that are asociated with this asset. This is a rather important field as those liabilites mapped to an asset will magnify all risks scores asociated with it.' ); ?>'>
							<?php echo __( 'Liabilities' ); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
					<th>
				        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Assets must be reviewed at regular points in time to ensure they remain relevant and updated to the business. Notifications are triggered (optionaly) when this date arrives' ); ?>'>
							<?php echo __( 'Review Date' ); ?>
					        <i class="icon-info-sign"></i>
				        </div>
					</th>
					<th class="row-status">
						<?php echo __('Status'); ?>
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
						<?php
						echo $this->Eramba->getEmptyValue($asset['Asset']['description']);
						?>
					</td>
					<td><?php echo isset( $asset['AssetMediaType']['name'] ) ? $asset['AssetMediaType']['name'] : ''; ?></td>
					<td><?php echo isset( $asset['AssetLabel']['name'] ) ? $asset['AssetLabel']['name'] : ''; ?></td>
					<td>
						<?php
						$legals = array();
						foreach ($asset['Legal'] as $legal) {
							$legals[] = $legal['name'];
						}
						echo implode(', ', $legals);
						?>
					</td>
					<td><?php echo $asset['Asset']['review']; ?></td>
					<td>
						<?php
						echo $this->Assets->getStatuses($asset, true);
						?>
					</td>
					<?php /*
					<td class="text-center">
						<?php
						echo $this->element('workflow/action_buttons_1', array(
							'id' => $asset['Asset']['id'],
							'item' => $this->Workflow->getActions($asset['Asset'], $asset['WorkflowAcknowledgement']),
							'pulledObjectFields' => array('BusinessUnit' => $asset['Asset']['asset_owner_id'])
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
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Select from the list of business unit, which one is the one owning the asset.' ); ?>'>
							<?php echo __( 'Owner' ); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Select from the list of business unit, which one is in charge of maintening the asset.' ); ?>'>
							<?php echo __( 'Guardian' ); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
					<th>
						<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Select from the list of business unit, which one is using the asset. You can optionally choose "Everyone".' ); ?>'>
							<?php echo __( 'User' ); ?>
							<i class="icon-info-sign"></i>
						</div>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo ( ! empty( $asset['AssetOwner'] ) ) ? $asset['AssetOwner']['name'] : ''; ?></td>
					<td><?php echo ( ! empty( $asset['AssetGuardian'] ) ) ? $asset['AssetGuardian']['name'] : ''; ?></td>
					<td><?php echo ( ! empty( $asset['Asset']['asset_user_id'] ) ) ? $asset['AssetUser']['name'] : __('Everyone'); ?></td>
				</tr>
			</tbody>
		</table>

		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __('Related Business Units'); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if (!empty($asset['BusinessUnit'])) : ?>
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th><?php echo __('Name'); ?></th>
							<th><?php echo __('Liabilities'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($asset['BusinessUnit'] as $bu) : ?>
							<tr>
								<td>
									<?php
									echo $bu['name'];
									?>
								</td>
								<td>
									<?php
									$liabilities = array();
									if (!empty($bu['Legal'])) {
										foreach ($bu['Legal'] as $l) {
											$liabilities[] = $l['name'];
										}
									}

									echo $this->Eramba->getEmptyValue(implode(', ', $liabilities));
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('No Business Units found.')
					));
					?>
				<?php endif; ?>
			</div>
		</div>

		<?php
		echo $this->element('assets/classification_table', array(
			'asset' => $asset
		));
		?>

		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo __('Related Assets'); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if (!empty($assetsRelatedData[$asset['Asset']['id']])) : ?>
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th><?php echo __('Name'); ?></th>
							<th><?php echo __('Liabilities'); ?></th>
							<th><?php echo __('Classification'); ?></th>
							<th><?php echo __('Status'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($assetsRelatedData[$asset['Asset']['id']] as $assetId) : ?>
							<?php
							$item = $allAssetsData[$assetId];
							$legals = array();
							foreach ($item['Legal'] as $legal) {
								$legals[] = $legal['name'];
							}
							?>
							<tr>
								<td><?php echo $item['Asset']['name']; ?></td>
								<td><?php echo implode(', ', $legals); ?></td>
								<td>
									<?php
									echo $this->element('assets/classification_table_cell', array(
										'assetId' => $assetId
									));
									?>
								</td>
								<td><?php echo $this->Assets->getStatuses($item); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('No Related Assets found.')
					));
					?>
				<?php endif; ?>
			</div>
		</div>

		<?php
		echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
			'item' => $asset
		));
		?>
	</div>
</div>
