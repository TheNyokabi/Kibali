<div class="row">
	<div class="col-lg-7">
		<?php
		if (!empty($isUsed)) {
			echo $this->Html->div(
				'alert alert-danger',
				'<i class="icon-exclamation-sign"></i> ' . __('This Classification is in use and changing it will recalculate all Risks!')
			);
		}
		?>
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'AssetClassification', array(
							'url' => array( 'controller' => 'assetClassifications', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'AssetClassification', array(
							'url' => array( 'controller' => 'assetClassifications', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Classification Type' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'asset_classification_type_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __( 'Choose one or create new below' )
									) ); ?>
									<span class="help-block"><?php echo __( 'Select from this drop down an existing classification type or create a new one with the field below.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'New Classification Type' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'AssetClassificationType.name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Set the name of your classifications, for example "Confidentiality", "Integrity", "Availability", "Value", Etc.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Classification Options' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'For each classification type, you will need to proivde options. Examples could be "High", "Low", etc.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Criteria' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'criteria', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: define a criteria for this classification option' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Value'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('value', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Certain risk calculation methods (Magerit, Allegro, Etc) require you to classify your assets in numerical values, for that reason you need to provide a value for this classification.'); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('AssetClassification');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'AssetClassification',
			'id' => isset($edit) ? $this->data['AssetClassification']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var $type_ele = $("#AssetClassificationAssetClassificationTypeId");
		var $new_class_ele = $("#AssetClassificationTypeName");

		$type_ele.on("change", function() {
			if ( $(this).val() == '' ) {
				$new_class_ele.prop( 'disabled', false );
			} else {
				$new_class_ele.prop( 'disabled', true );
			}
		}).trigger("change");
	});
</script>
