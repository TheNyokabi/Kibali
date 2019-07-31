<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'RiskClassification', array(
							'url' => array( 'controller' => 'riskClassifications', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'RiskClassification', array(
							'url' => array( 'controller' => 'riskClassifications', 'action' => 'add' ),
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
									<?php echo $this->Form->input( 'risk_classification_type_id', array(
										'options' => $types,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __( 'Choose one or create new below' )
									) ); ?>
									<span class="help-block"><?php echo __( 'Use this drop down to select an existing classification type or use the field below to create a new one.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'New Classification Type' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'RiskClassificationType.name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'If you havent created a Classification type before, you will need to create one. Examples are "Likelihood", "Impact", Etc' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Name' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'You will need to create a name for your classification. Examples could be "High", "Low", etc.' ); ?></span>
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
								<label class="col-md-2 control-label"><?php echo __( 'Value' ); ?>:</label>
								<div class="col-md-10">
									<?php
									if (!empty($isUsed)) {
										echo $this->Html->div(
											'alert alert-danger', 
											'<i class="icon-exclamation-sign"></i> ' . __('This classification is used by %d risks, if you proceed we will update the risk score for this risks.', $isUsed)
										);
									}
									?>
									<?php echo $this->Form->input( 'value', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Set the value for this classification, this numerical value will be used to calculate a Risk Score. Every time you update this number, all risks using this classification will have their Risk Scores recalculated.' ); ?></span>
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
					echo $this->Ajax->cancelBtn('RiskClassification');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'RiskClassification',
			'id' => isset($edit) ? $this->data['RiskClassification']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var $type_ele = $("#RiskClassificationRiskClassificationTypeId");
		var $new_class_ele = $("#RiskClassificationTypeName");

		$type_ele.on("change", function() {
			if ( $(this).val() == '' ) {
				$new_class_ele.prop( 'disabled', false );
			} else {
				$new_class_ele.prop( 'disabled', true );
			}
		}).trigger("change");
	});
</script>
