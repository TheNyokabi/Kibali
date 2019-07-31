<div class="row">
	<div class="col-lg-7">

		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content" id="finding-form-wrapper">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ComplianceAnalysisFinding', array(
							'url' => array( 'controller' => 'complianceAnalysisFindings', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'id' => 'compliance-analysis-finding-form',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ComplianceAnalysisFinding', array(
							'url' => array( 'controller' => 'complianceAnalysisFindings', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'id' => 'compliance-analysis-finding-form',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li>
							<a href="#tab_affected_compliance_items" data-toggle="tab">
								<?php echo __('Affected Compliance Items'); ?>
							</a>
						</li>
						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __('Title'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('title', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('Provide a title for this finding, such as "FIND01 - Missing Firewall Policies"'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Description' ); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Describe the finding, once the fingind has been saved you can attach documents (such as evidence, notifications, Etc) to this finding.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Due Date'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('due_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									));
									?>
									<span class="help-block"><?php echo __('Input the date by which this compliance findings must be resolved, you can assign notifications to remind your team about this deadline.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->element('taggable_field', array(
										'placeholder' => __('Add a tag'),
										'model' => 'ComplianceAnalysisFinding'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: You can use tags to profile your findings, examples are "In Remediation", "Networks", Etc.'); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Owner
							]); ?>
							<?= $this->FieldData->inputs([
								$FieldDataCollection->Collaborator
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Status'); ?>:</label>
								<div class="col-md-10">
									<?php
									App::uses('ComplianceAnalysisFinding', 'Model');

									echo $this->Form->input('status', array(
										'options' => ComplianceAnalysisFinding::statuses(),
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12',
										'default' => ComplianceAnalysisFinding::STATUS_OPEN
									));
									?>
									<span class="help-block"><?php echo __('Set the status of this compliance finding as "open" or "closed" (once is resolved).'); ?></span>
								</div>
							</div>
						</div>

						<div class="tab-pane fade in" id="tab_affected_compliance_items">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Compliance Packages'); ?>:</label>
								<div class="col-md-10">
									<?php
                                    echo $this->Form->input('ComplianceAnalysisFinding.ThirdParty', array(
                                        'options' => $packages,
                                        'label' => false,
                                        'div' => false,
                                        'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
                                        'id' => 'package-select-field',
                                        'multiple' => true,
                                    ));
                                    ?>
									<span class="help-block"><?php echo __( 'Select a compliance package (we list all avalilable frameworks shown at Compliance Management / Compliance Analysis) - all its Compliace Package Items will be displayed on a box below. You can choose one ore more items that are related to this finding.' ); ?></span>
								</div>
							</div>

							<div id="package-items-wrapper">
								<div></div>
								<?php
								echo $this->element('../ComplianceAnalysisFindings/load_package_items', [
								]);
								?>
							</div>
						</div>

						
						<script type="text/javascript">
							
							jQuery(function($) {
								$("#package-select-field").on("change", function(e) {
									Eramba.Ajax.blockEle($("#finding-form-wrapper"));

									$.ajax({
										url: "/complianceAnalysisFindings/loadPackageItems/<?php echo isset($edit) ? $this->request->data['ComplianceAnalysisFinding']['id'] : ''; ?>",
										data: $("#compliance-analysis-finding-form").serializeArray()
									}).done(function(data) {
										$("#package-items-wrapper").html(data);
										FormComponents.init();
										Eramba.Ajax.unblockEle($("#finding-form-wrapper"));
									});
								});
							});
						</script>
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
					echo $this->Ajax->cancelBtn('ComplianceAnalysisFinding');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ComplianceAnalysisFinding',
			'id' => isset($edit) ? $this->data['ComplianceAnalysisFinding']['id'] : null
		));
		?>
	</div>
</div>
