<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">
				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ComplianceException', array(
							'url' => array( 'controller' => 'complianceExceptions', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ComplianceException', array(
							'url' => array( 'controller' => 'complianceExceptions', 'action' => 'add' ),
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
									<span class="help-block"><?php echo __( 'Provide a descriptive title. Example: Lack of budgets for compliance with 2.3 of PCI-DSS' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Requestor
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
									<span class="help-block"><?php echo __( 'Set the date at which this exception will be reconsidered. You can setup notifications to alarm the requester or anyone else before the date is due.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->input($FieldDataCollection->closure_date_toggle); ?>
							<?= $this->FieldData->input($FieldDataCollection->closure_date); ?>

							<script type="text/javascript">
								jQuery(function($) {
									$("#ComplianceExceptionClosureDateToggle").on("change", function(e) {
										if ($(this).is(":checked")) {
											$("#ComplianceExceptionClosureDateToggle").attr("checked", true);
											$("#ComplianceExceptionClosureDate").attr("disabled", "disabled");
										}
										else {
											$("#ComplianceExceptionClosureDateToggle").attr("checked", false);
											$("#ComplianceExceptionClosureDate").removeAttr("disabled");
										}
									}).trigger("change");
								});
							</script>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'status', array(
										'options' => $statuses,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'default' => 1
									) ); ?>
									<span class="help-block"><?php echo __( 'Register if this exception is closed or open (valid).' ); ?></span>
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
									<span class="help-block"><?php echo __( 'OPTIONAL: A good description should include what the compliance is (threat, vulnerabilities, impact, etc.), the options which were considered and discarded, etc.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->element('taggable_field', array(
										'placeholder' => __('Add a tag'),
										'model' => 'ComplianceException'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Use tags to profile your compliance exceptions, examples are "PCI-DSS", "Budget Issues", Etc.'); ?></span>
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
					<?php echo $this->Ajax->cancelBtn('ComplianceException'); ?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ComplianceException',
			'id' => isset($edit) ? $this->data['ComplianceException']['id'] : null
		));
		?>
	</div>
</div>
