<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ComplianceAuditFeedback', array(
							'url' => array( 'controller' => 'complianceAuditFeedbacks', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ComplianceAuditFeedback', array(
							'url' => array( 'controller' => 'complianceAuditFeedbacks', 'action' => 'add' ),
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
								<label class="col-md-2 control-label"><?php echo __('Feedback Profile'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('compliance_audit_feedback_profile_id', array(
										'options' => $profiles,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one or create new below')
									));
									?>
									<span class="help-block"><?php echo __( 'Select one feedback profile from the list above or create a new one using the field below.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('New Feedback Profile'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('ComplianceAuditFeedbackProfile.name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __( 'Feedback Profiles group possible answers to your questionnaires (you define possible answers in the field below)' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Name'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __( 'Possible answers for your questionnaires, such as "Yes we are compliant", "No we are not compliant", Etc. ' ); ?></span>
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
					echo $this->Ajax->cancelBtn('ComplianceAuditFeedback');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ComplianceAuditFeedback',
			'id' => isset($edit) ? $this->data['ComplianceAuditFeedback']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		var $type_ele = $("#ComplianceAuditFeedbackComplianceAuditFeedbackProfileId");
		var $new_class_ele = $("#ComplianceAuditFeedbackProfileName");

		$type_ele.on("change", function() {
			if ( $(this).val() == '' ) {
				$new_class_ele.prop( 'disabled', false );
			} else {
				$new_class_ele.prop( 'disabled', true );
			}
		}).trigger("change");
	});
</script>