<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">
				<?php
					if (isset($edit)) {
						echo $this->Form->create('ComplianceFinding', array(
							'url' => array('controller' => 'complianceFindings', 'action' => 'edit'),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						));

						echo $this->Form->input('id', array( 'type' => 'hidden'));
						$submit_label = __('Edit');
					}
					else {
						echo $this->Form->create('ComplianceFinding', array(
							'url' => array('controller' => 'complianceFindings', 'action' => 'add', $compliance_audit_id, $compliance_package_item_id),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						));

						$submit_label = __('Add');
					}
				?>

				<?php echo $this->Form->input( 'compliance_audit_id', array(
					'type' => 'hidden',
					'value' => $compliance_audit_id
				) ); ?>

				<?php echo $this->Form->input( 'compliance_package_item_id', array(
					'type' => 'hidden',
					'value' => $compliance_package_item_id
				) ); ?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="">
							<a href="#tab_compliance_item_information" data-toggle="tab">
								<?php echo __('Compliance Package Item Info'); ?>	
							</a>
						</li>
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li><a href="#tab_compliance_exception" data-toggle="tab"><?php echo __('Compliance Exception'); ?></a></li>
						<li><a href="#tab_risks" data-toggle="tab"><?php echo __('Third Party Risks'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in" id="tab_compliance_item_information">
							<?php
							echo $this->element('compliance_package_items/info', array(
								'data' => $packageItem
							));
							?>
						</div>
						<div class="tab-pane fade in active" id="tab_general">
							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Compliance Package Item' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'null', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'disabled' => true,
										'value' => $compliance_package_item_name
									) ); ?>
									<span class="help-block"><?php echo __( 'The name for the compliance package to which this finding will be asociated' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Type' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'type', array(
										'options' => $types,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'compliance-finding-type'
									) ); ?>
									<span class="help-block"><?php echo __( 'You can register a "Finding" (when a non-compliance has been identified and must be recorded) or an "Assesment" (when a compliance requirement has been found compliant)' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Title' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'title', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'The title for this finding' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									$labels = null;
									$labelsArr = array();
									if (isset($this->request->data['Classification'])) {
										foreach ($this->request->data['Classification'] as $item) {
											$labelsArr[] = $item['name'];
										}

										$labels = implode(',', $labelsArr);
									}
									elseif (isset($this->request->data['ComplianceFinding']['classifications']) && !empty($this->request->data['ComplianceFinding']['classifications'])) {
										$labels = $this->request->data['ComplianceFinding']['classifications'];
									}

									if (!empty($labels)) {
										$this->request->data['ComplianceFinding']['classifications'] = $labels;
									}

									echo $this->Form->input('classifications', array(
										'type' => 'hidden',
										'label' => false,
										'div' => false,
										'class' => 'tags-classifications col-md-12 full-width-fix',
										'multiple' => true,
										'data-placeholder' => __('Add a tag')
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Deadline' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'deadline', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker',
										'id' => 'compliance-deadline'
									) ); ?>
									<span class="help-block"><?php echo __( 'The date by which this non-compliance (if this is a finding) must be corrected.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'compliance_finding_status_id', array(
										'options' => $statuses,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'default' => 1,
										'id' => 'compliance-status'
									) ); ?>
									<span class="help-block"><?php echo __( 'Status is mostly used when what is being recorded is non-compliance and a deadline is required to ensure the issue is corrected.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'What the auditor observed during the review' ); ?></span>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab_compliance_exception">
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Compliance Exception'); ?>:</label>
								<div class="col-md-9">
									<?php
										// $selected = array();
										// if ( isset( $this->request->data['ComplianceException'] ) ) {
										// 	foreach ( $this->request->data['ComplianceException'] as $entry ) {
										// 		$selected[] = $entry['id'];
										// 	}
										// }

										// if ( isset( $this->request->data['ComplianceFinding']['compliance_exception_id'] ) && is_array( $this->request->data['ComplianceFinding']['compliance_exception_id'] ) ) {
										// 	foreach ( $this->request->data['ComplianceFinding']['compliance_exception_id'] as $entry ) {
										// 		$selected[] = $entry;
										// 	}
										// }
									?>
									<?php
									echo $this->Form->input('ComplianceFinding.ComplianceException', array(
										'type' => 'select',
										'label' => false,
										'div' => false,
										'multiple' => true,
										'class' => 'select2 col-md-12 full-width-fix'
									));
									?>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'complianceExceptions', 'action' => 'add'),'text' => __('Add Compliance Exception'))); ?>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab_risks">
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Third Party Risk'); ?>:</label>
								<div class="col-md-9">
									<?php
										// $selected = array();
										// if ( isset( $this->request->data['ThirdPartyRisk'] ) ) {
										// 	foreach ( $this->request->data['ThirdPartyRisk'] as $entry ) {
										// 		$selected[] = $entry['id'];
										// 	}
										// }

										// if ( isset( $this->request->data['ComplianceFinding']['third_party_risk_id'] ) && is_array( $this->request->data['ComplianceFinding']['third_party_risk_id'] ) ) {
										// 	foreach ( $this->request->data['ComplianceFinding']['third_party_risk_id'] as $entry ) {
										// 		$selected[] = $entry;
										// 	}
										// }
									?>
									<?php
									// echo $this->Form->input('third_party_risk_id', array(
									// 	'options' => $thirdPartyRisks,
									// 	'label' => false,
									// 	'div' => false,
									// 	'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
									// 	'multiple' => true,
									// 	'selected' => $selected
									// ));
									?>
									<?php
									echo $this->Form->input('ComplianceFinding.ThirdPartyRisk', array(
										'type' => 'select',
										'label' => false,
										'div' => false,
										'multiple' => true,
										'class' => 'select2 col-md-12 full-width-fix'
									));
									?>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'thirdPartyRisks', 'action' => 'add'),'text' => __('Add Third Party Risk'))); ?>
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
					echo $this->Ajax->cancelBtn('ComplianceFinding');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ComplianceFinding',
			'id' => isset($edit) ? $this->data['ComplianceFinding']['id'] : null
		));
		?>
	</div>
</div>
<script type="text/javascript">
jQuery(function($) {
	var asset_id = <?php echo COMPLIANCE_FINDING_ASSESED; ?>;
	$("#compliance-finding-type").on("change", function(e) {
		if ($(this).val() == asset_id) {
			$("#compliance-status, #compliance-deadline").prop("disabled", true);
		}
		else {
			$("#compliance-status, #compliance-deadline").prop("disabled", false);
		}
	});

	var obj = $.parseJSON('<?php echo $this->Eramba->jsonEncode($classifications); ?>');

	$('.tags-classifications').select2({
		tags: obj
	});
});
</script>