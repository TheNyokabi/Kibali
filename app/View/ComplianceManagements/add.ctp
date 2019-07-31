<div class="row">
	<div class="col-lg-8">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ComplianceManagement', array(
							'url' => array( 'controller' => 'complianceManagements', 'action' => 'edit', $complianceId ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ComplianceManagement', array(
							'url' => array( 'controller' => 'complianceManagements', 'action' => 'add', $compliance_package_item_id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

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
						<li class=""><a href="#tab_asset" data-toggle="tab"><?php echo __('Assets'); ?></a></li>
						<li class=""><a href="#tab_mitigation" data-toggle="tab"><?php echo __('Mitigation Options'); ?></a></li>
						<li class=""><a href="#tab_findings" data-toggle="tab"><?php echo __('Findings'); ?></a></li>
						<li class=""><a href="#tab_drivers" data-toggle="tab"><?php echo __('Compliance Drivers'); ?></a></li>

						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in" id="tab_compliance_item_information">
							<?php
							echo $this->element('compliance_package_items/info', array(
								'data' => $data
							));
							?>
						</div>
						<div class="tab-pane fade in active" id="tab_general">
							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Current Compliance Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'compliance_treatment_strategy_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'compliance_treatment_strategy'
									) ); ?>
									<span class="help-block"><?php echo __( 'Select your desired compliance goal:<br><br>- Compliant: you wish to be compliant.<br>- Not Applicable: this item is not applicable to the scope of this program.<br>- Not Compliant: your organisation has no interest in being compliant with this requirement.' ); ?></span>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Compliance Efficacy' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'efficacy', array(
										'options' => $this->App->getPercentageOptions(),
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: In your subjective appreciation, the controls and policies selected on the next fields mitigate the requirement to which extent? If in doubt, select %100.' ); ?></span>
								</div>
							</div>
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Mitigation Projects' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'Project', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: If you havent got controls and policies that meet this requirement, you can select a project that addresses this issue (Projects are defined in Security Operations / Project Management).' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'projects', 'action' => 'add'),'text' => 'Add Project')); ?>
								</div>
							</div>
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Owner' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'owner_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __( 'None' )
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: select one individual that is most related to this particular requirement. If in doubt, simply select "Admin".' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'users', 'action' => 'add'),'text' => __('Add User'))); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Description' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'description', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_asset">
							<?php 
							echo $this->FieldData->inputs([
								$FieldDataCollection->Asset
							]);
							?>
						</div>
						<div class="tab-pane fade in" id="tab_mitigation">
							<div class="form-group form-group-first form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Mitigation Controls' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'SecurityService', array(
										'options' => $security_services,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen eramba-auto-complete',
										'multiple' => true,
										'id' => 'security_service',
										'data-url' => '/complianceManagements/getPolicies',
										'data-request-key' => 'securityServiceIds',
										'data-assoc-input' => '#security_policy'
									) ); ?>
									<span class="help-block"><?php echo __( 'Select one or more controls (from Control Catalogue / Security Services) used to mitigate this compliance requirement. If you havent got controls you can still select mitigation policies or alternatively, set this requirement as "Not Compliant" and create a "Mitigation Project"' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'securityServices', 'action' => 'add'),'text' => 'Add Security Service')); ?>
								</div>
							</div>
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Mitigating Security Policies' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'SecurityPolicy', array(
										'options' => $security_policies,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'security_policy'
									) ); ?>
									<span class="help-block"><?php echo __( 'Select one or more policies (from Control Catalogue / Security Policies) that mitigate this compliance requirement (they can replace security controls when none is applicable).' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'securityPolicies', 'action' => 'add'),'text' => 'Add Security Policy')); ?>
								</div>
							</div>
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Compliance Exception' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'ComplianceException', array(
										// 'options' => $exceptions,
										'label' => false,
										'div' => false,
										'id' => 'compliance_exception',
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'If the compliance status (from the first tab) is "Not Aplicable" or "Not Compliant" you might want to set a Compliance Exception to state that in a formal record. This is an optional record.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'complianceExceptions', 'action' => 'add'),'text' => 'Add Compliance Exception')); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_findings">
							<div class="form-group form-group-first form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Compliance Findings'); ?>:</label>
								<div class="col-md-9">
									<?php
                                    echo $this->Form->input('ComplianceManagement.ComplianceAnalysisFinding', array(
                                        'label' => false,
                                        'div' => false,
                                        'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
                                        'multiple' => true,
                                    ));
                                    ?>
									<span class="help-block"><?php echo __('OPTIONAL: Select or create one or more Compliance Findings (from Compliance Management / Compliance Finginds) for this compliance requirements. This is typically used when your auditors have identified that your mitigation for this control is innefective and you want to keep track of such incompliance until remediation.'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'complianceAnalysisFindings', 'action' => 'add'),'text' => 'Add Finding')); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_drivers">
							<div class="form-group form-group-first form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Asset Risks' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'Risk', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Certain standards (such as ISO 27001) require you to describe the drivers for meeting their controls. You can use Risks (from Risk Management / Asset Risk Management) as drivers.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'risks', 'action' => 'add'),'text' => 'Add Risk')); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Third Party Risks' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'ThirdPartyRisk', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Certain standards (such as ISO 27001) require you to describe the drivers for meeting their controls. You can use Risks (from Risk Management / Third Party Risk Management) as drivers.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'thirdPartyRisks', 'action' => 'add'),'text' => 'Add Third Party Risk')); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Business Risks' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'BusinessContinuity', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Certain standards (such as ISO 27001) require you to describe the drivers for meeting their controls. You can use Risks (from Risk Management / Business Impact Analysis) as drivers.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'businessContinuities', 'action' => 'add'),'text' => 'Add Business Continuity')); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Liabilities' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'legal_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __( 'None' )
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: If there are liabilities (from Organisation / Legal Constrains) that require you to meet this particular requirement select them here.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'legals', 'action' => 'add'),'text' => 'Add Legal')); ?>
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
					echo $this->Ajax->cancelBtn('ComplianceManagement'/*, isset($edit) ? $this->data['ComplianceManagement']['id'] : null*/);
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ComplianceManagement',
			'id' => isset($edit) ? $this->data['ComplianceManagement']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {
	/*var $compliance_treatment_strategy = $("#compliance_treatment_strategy");
	var $security_service = $("#security_service");
	var $compliance_exception = $("#compliance_exception");
	var $security_policy = $("#security_policy");

	var mitigate_id = <?php echo COMPLIANCE_TREATMENT_MITIGATE; ?>;
	var not_applicable_id = <?php echo COMPLIANCE_TREATMENT_NOT_APPLICABLE; ?>;

	$compliance_treatment_strategy.on("change", function(e) {
		var val = parseInt( $(this).val() );

		if ( val == mitigate_id ) {
			$security_service.prop('disabled', false);
			$compliance_exception.prop('disabled', false);
			$security_policy.prop('disabled', false);
		}

		if ( val == not_applicable_id ) {
			$security_service.prop('disabled', true);
			$compliance_exception.prop('disabled', true);
			$security_policy.prop('disabled', true);
		}
	}).trigger("change");*/
});
</script>
