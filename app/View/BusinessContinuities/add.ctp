<?php
App::uses('RiskClassification', 'Model');
App::uses('RiskAppetite', 'Model');
?>

<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'BusinessContinuity', array(
							'url' => array( 'controller' => 'businessContinuities', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border custom-validator-form',
							'novalidate' => true,
							'data-model' => 'BusinessContinuity',
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'BusinessContinuity', array(
							'url' => array( 'controller' => 'businessContinuities', 'action' => 'add' ),
							'class' => 'form-horizontal row-border custom-validator-form',
							'novalidate' => true,
							'data-model' => 'BusinessContinuity',
						) );

						$submit_label = __( 'Add' );
					}
				?>
				<?php echo $this->Form->input( 'mitigate_id', array(
					'type' => 'hidden',
					'value' => $mitigate_id,
					'id' => 'mitigate_id'
				) ); ?>
				<?php echo $this->Form->input( 'accept_id', array(
					'type' => 'hidden',
					'value' => $accept_id,
					'id' => 'accept_id'
				) ); ?>
				<?php echo $this->Form->input( 'transfer_id', array(
					'type' => 'hidden',
					'value' => $transfer_id,
					'id' => 'transfer_id'
				) ); ?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li class=""><a href="#tab_analysis" data-toggle="tab"><?php echo __('Analysis'); ?></a></li>
						<li class=""><a href="#tab_mitigation" data-toggle="tab"><?php echo __('Treatment'); ?></a></li>
						<li class=""><a href="#tab_containment" data-toggle="tab"><?php echo __('Risk Response Plan'); ?></a></li>

						<?php echo $this->element('CustomFields.tabs'); ?>
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
									<span class="help-block"><?php echo __( 'Give this risk a descriptive title. For example, loss of service due power electricity.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'Describe this risk scenario, context, triggers, Etc.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Owner
							]); ?>
							
							<?= $this->FieldData->inputs([
								$FieldDataCollection->Stakeholder
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->element('taggable_field', array(
										'placeholder' => __('Add a tag'),
										'model' => 'BusinessContinuity'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Use tags to classify or tag your risk, examples are "Risk beign treated", "High Risk", "Financial Risk", Etc.'); ?></span>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Review' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'review', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker',
										'disabled' => !empty($edit)
									) ); ?>
									<span class="help-block"><?php echo __( 'enter the date when this risk will be reviewed. You can setup notifications that will triggered (to the roles defined above or other user accounts) before and after the date you in this field. Further updates  to this date must be performed using reviews ("Manage" / "Reviews").' ); ?></span>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_analysis">
							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Applicable Business Units' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'BusinessContinuity.BusinessUnit', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen risk-classifications-trigger related-risk-item-input',
										'multiple' => true,
										'id' => 'risk-bu-id'
									) ); ?>
									<span class="help-block"><?php echo __( 'Select one or more business units (defined at Organisation / Business Units) that you want to include on the scope of this risk.' ); ?></span>
								</div>
							</div>

							<script>
								$(function() {
									$('#risk-bu-id').erambaAutoComplete({
										url: '/businessContinuities/getThreatsVulnerabilities',
										requestKey: ['assocIds'],
										requestType: 'POST',
										responseKey: ['threats', 'vulnerabilities'],
										assocInput: '#risk-threat-id, #risk-vulnerability-id'
									});
								});
							</script>
							
							<?php
							// echo $this->element('../Risks/threat_vulnerability_script', array(
							// 	'assocId' => 'risk-bu-id',
							// 	'url' => '/businessContinuities/getThreatsVulnerabilities'
							// ));
							?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Processes' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'BusinessContinuity.Process', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen related-process-input risk-classifications-trigger',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'Select one or more processes (defined under each business unit at Organisation / Bussiness Units) that you want to include on the scope of this Risk.' ); ?></span>
								</div>
							</div>
							<div class="form-group" id="risk-score-group">
								<label class="col-md-2 control-label"><?php echo __( 'Revenue per Day' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'rpd', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'disabled' => 'disabled',
										'id' => 'rpd-input'
									) ); ?>
									<span class="help-block"><?php echo __( 'The total revenue per day is automatically calculated based on the processes chosen.' ); ?></span>
								</div>
							</div>
							<div class="form-group" id="risk-score-group">
								<label class="col-md-2 control-label"><?php echo __( 'Minimum RTO' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'rto', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'disabled' => 'disabled',
										'id' => 'rto-input'
									) ); ?>
									<span class="help-block"><?php echo __( 'The minimum RTO objective is shown from is automatically calculated based on the processes chosen.' ); ?></span>
								</div>
							</div>
							<div class="form-group" id="risk-score-group">
								<label class="col-md-2 control-label"><?php echo __( 'Minimum MTO' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'mto', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'disabled' => 'disabled',
										'id' => 'mto-input'
									) ); ?>
									<span class="help-block"><?php echo __( 'The minimum MTO objective is shown from is automatically calculated based on the processes chosen.' ); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Business Impact' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'impact', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: What is the Business Impact if this Risk materializes?' ); ?></span>
								</div>
							</div>
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Threats Tags' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'BusinessContinuity.Threat', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'risk-threat-id'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select one or more applicable threats tags.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'threats', 'action' => 'add'),'text' => 'Add Threat')); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Threat Description' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'threats', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Describe the context of the threats vectors for this risk.' ); ?></span>
								</div>
							</div>
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Vulnerabilities Tags' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'BusinessContinuity.Vulnerability', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'risk-vulnerability-id'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select one or more applicable vulnerability tags.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'vulnerabilities', 'action' => 'add'),'text' => 'Add Vulnerability')); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Vulnerabilities Description' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'vulnerabilities', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Describe the context of the vulnerabilities vectors for this risk.' ); ?></span>
								</div>
							</div>
							<div id="risk-classification-analysis" class="form-group risk-classification-group">
								<label class="col-md-2 control-label"><?php echo __( 'Risk Classification' ); ?>:</label>
								<div class="col-md-10 risk-classification-fields">
									<div id="risk-classification-analysis-fields">
										<?php
										echo $this->element('risks/risk_classifications/classifications_ajax', [
											'classifications' => $classifications,
											'justLoaded' => true,
											'model' => 'BusinessContinuity',
											'type' => RiskClassification::TYPE_ANALYSIS,
											'element' => '#risk-classification-analysis-fields'
										]);
										?>
									</div>
									<span class="help-block"><?php echo __( 'eramba will display your risk classification options as per the settings defined at Risk Management / Business Impact Analysis / Settings / Classification and the Risk Calculation method choosen at Risk Management / Business Impact Analysis / Settings / Risk Calculation.' ); ?></span>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_mitigation">
							<div class="form-group" id="risk-score-group">
								<label class="col-md-2 control-label"><?php echo __( 'Risk Score' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'risk_score', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'disabled' => 'disabled',
										'id' => 'risk-score-input'
									) ); ?>

									<div id="risk-score-math">
									</div>

									<?php if ($appetiteMethod == RiskAppetite::TYPE_INTEGER) : ?>
										<p class="error-message">
											<?php
											echo __('Exceeds Risk Appetite of %s', RISK_APPETITE);
											?>
										</p>
									<?php endif; ?>

									<span class="help-block"><?php echo __( 'Risk Score is automatically calculated using the classification selected on the previous tab and the Risk calculation defined at Risk Management / Business Impact Analysis / Settings / Risk Calculation.' ); ?></span>
								</div>
							</div>

							<?php
							echo $this->FieldData->input($FieldDataCollection->risk_mitigation_strategy_id, [
								'id' => 'risk_mitigation_strategy',
								'class' => ['custom-validator-trigger']
							]);

							echo $this->FieldData->input($FieldDataCollection->SecurityService, [
								'class' => ['eramba-auto-complete'],
								'id' => 'compensating_controls',
								'data-url' => '/risks/getPolicies',
								'data-request-key' => 'controlIds',
								'data-assoc-input' => '#procedure-documents, #policy-documents, #standard-documents'
							]);

							echo $this->FieldData->input($FieldDataCollection->SecurityPolicyTreatment);

							echo $this->FieldData->input($FieldDataCollection->RiskException, [
								'id' => 'risk_exceptions',
							]);

							echo $this->FieldData->input($FieldDataCollection->Project, [
								'id' => 'risk_exceptions',
							]);
							?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Mitigating Business Continuity Plans' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input('BusinessContinuity.BusinessContinuityPlan', array(
										'options' => $bcps,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'compensating_controls',
									)); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select which Business Continuity plans (defined at Control Catalogue / Business Continuity Plans) you wish to utilize to deal with the mitigation of this risk.' ); ?></span>
								</div>
							</div>
							<div class="form-group <?php if ($appetiteMethod == RiskAppetite::TYPE_THRESHOLD) echo 'hidden'; ?>">
								<label class="col-md-2 control-label"><?php echo __( 'Residual Score' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'residual_score', array(
										'options' => $this->Risks->getGranularityList(),
										'label' => false,
										'div' => false,
										'class' => 'select2 col-xs-12'
									) ); ?>
									<span class="help-block"><?php echo __( 'Select the percentage of Risk Reduction that was achieved by applying Security Controls. If the risk score for this risk is 100 points and you select %100, the residual for this risk will be 100 points. If you choose %30, the residual will be 30.<br><br>
									If you want to change the scales used (by default 10) click on Settings / Residual Score and adjust settings in that window. 
									' ); ?></span>
								</div>
							</div>
							<?php if ($appetiteMethod == RiskAppetite::TYPE_THRESHOLD) : ?>
								<div id="risk-classification-treatment" class="form-group risk-classification-group">
									<label class="col-md-2 control-label"><?php echo __('Risk Classification'); ?>:</label>
									<div class="col-md-10 risk-classification-fields">
										<div id="risk-classification-treatment-fields">
											<?php
											echo $this->element('risks/risk_classifications/classifications_ajax', [
												'classifications' => $classifications,
												'justLoaded' => true,
												'model' => 'BusinessContinuity',
												'type' => RiskClassification::TYPE_TREATMENT,
												'element' => '#risk-classification-treatment-fields'
											]);
											?>
										</div>
										<span class="help-block"><?php echo __( 'eramba will display your risk classification options as per the settings defined at Risk Management / Asset Risk Management / Settings / Classification and the Risk Calculation method choosen at Risk Management / Asset Risk Management / Settings / Risk Calculation.' ); ?></span>
									</div>
								</div>
							<?php endif; ?>
						</div>
						<div class="tab-pane fade in" id="tab_containment">
							<?php
							echo $this->FieldData->inputs([
								$FieldDataCollection->SecurityPolicyIncident
							]);
							?>
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
					echo $this->Ajax->cancelBtn('BusinessContinuity');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'BusinessContinuity',
			'id' => isset($edit) ? $this->data['BusinessContinuity']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
	CustomValidator.init();
</script>
