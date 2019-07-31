<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'Asset', array(
							'url' => array( 'controller' => 'assets', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'Asset', array(
							'url' => array( 'controller' => 'assets', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li><a href="#asset_owners" data-toggle="tab"><?php echo __('Asset Owner'); ?></a></li>
						<li><a href="#asset_class" data-toggle="tab"><?php echo __('Asset Classification'); ?></a></li>
						<?php echo $this->element('CustomFields.tabs'); ?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<?php
							echo $this->QuickActionFields->input($FieldDataCollection->BusinessUnit, [
								'data-quick-add' => [
									'url' => ['controller' => 'businessUnits', 'action' => 'add'],
									'text' => __('Add Business Unit')
								],
								'class' => ['eramba-auto-complete'],
								'id' => 'business-unit-id',
								'data-url' => '/assets/getLegals',
								'data-request-key' => 'buIds',
								'data-assoc-input' => '#legal-id'
							]);

							echo $this->FieldData->inputs([
								$FieldDataCollection->name,
								$FieldDataCollection->description,
							]);

							echo $this->QuickActionFields->input($FieldDataCollection->asset_label_id, [
								'data-quick-add' => [
									'url' => ['controller' => 'assetLabels', 'action' => 'add'],
									'text' => __('Add Label')
								]
							]);

							echo $this->FieldData->inputs([
								$FieldDataCollection->asset_media_type_id,
								$FieldDataCollection->RelatedAssets,
							]);

							echo $this->QuickActionFields->input($FieldDataCollection->Legal, [
								'data-quick-add' => [
									'url' => ['controller' => 'legals', 'action' => 'add'],
									'text' => __('Add Liability')
								],
								'id' => 'legal-id'
							]);

							echo $this->FieldData->input($FieldDataCollection->review, [
								'disabled' => !empty($edit)
							]);
							?>
						</div>
						<div class="tab-pane fade in" id="asset_owners">
							<?php
							echo $this->QuickActionFields->input($FieldDataCollection->asset_owner_id, [
								'data-quick-add' => [
									'url' => ['controller' => 'businessUnits', 'action' => 'add'],
									'text' => __('Add Business Unit')
								],
								'id' => 'legal-id'
							]);

							echo $this->FieldData->inputs([
								$FieldDataCollection->asset_guardian_id,
								$FieldDataCollection->asset_user_id,
							]);
							?>
						</div>
						<div class="tab-pane fade in" id="asset_class">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Classification' ); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->element('assets/asset_classifications/asset_classification_fields', array(
										'classifications' => $classifications
									));
									?>
									<span class="help-block"><?php echo __( 'OPTIONAL: If you defined Asset Classifications (Asset Management / Asset Identification / Settings / Classifications) you can now classify this asset. This will only be useful if you plan to use "Magerit" as a Risk Management calculation methodology (Risk Management/  Asset Risk Management / Settings / Risk Calculation)' ); ?></span>
								</div>
							</div>
						</div>

						<?php echo $this->element('CustomFields.tabs_content'); ?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('Asset');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'Asset',
			'id' => isset($edit) ? $this->data['Asset']['id'] : null
		));
		?>
	</div>
</div>
