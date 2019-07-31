<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'RiskException', array(
							'url' => array( 'controller' => 'riskExceptions', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'RiskException', array(
							'url' => array( 'controller' => 'riskExceptions', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Title' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'title', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Provide a descriptive title. Example: Lack of budget for Antivirus for Mac-OS.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Requester
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Expiration' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'expiration', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									) ); ?>
									<span class="help-block"><?php echo __( 'Set the deadline for this Risk Exception. At the expiration day, a full re-assessment on this exception is usually done.' ); ?></span>
								</div>
							</div>
							
							<?= $this->FieldData->input($FieldDataCollection->closure_date_toggle); ?>
							<?= $this->FieldData->input($FieldDataCollection->closure_date); ?>

							<script type="text/javascript">
								jQuery(function($) {
									$("#RiskExceptionClosureDateToggle").on("change", function(e) {
										if ($(this).is(":checked")) {
											$("#RiskExceptionClosureDateToggle").attr("checked", true);
											$("#RiskExceptionClosureDate").attr("disabled", "disabled");
										}
										else {
											$("#RiskExceptionClosureDateToggle").attr("checked", false);
											$("#RiskExceptionClosureDate").removeAttr("disabled");
										}
									}).trigger("change");
								});
							</script>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'status', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Defines if the exception is still valid or not.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Description' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Describe why this Exception is required, who authorized, Etc.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->element('taggable_field', array(
										'placeholder' => __('Add a tag'),
										'model' => 'RiskException'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Use tags for this exception, examples are: Approved, To be reviewed, Important, Etc.'); ?></span>
								</div>
							</div>
						</div>

						<?php
						echo $this->element('CustomFields.tabs_content');
						?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('RiskException');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'RiskException',
			'id' => isset($edit) ? $this->data['RiskException']['id'] : null
		));
		?>
	</div>
</div>
