<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'SecurityIncident', array(
							'url' => array( 'controller' => 'securityIncidents', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'SecurityIncident', array(
							'url' => array( 'controller' => 'securityIncidents', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li class=""><a href="#tab_risk_profile" data-toggle="tab"><?php echo __('Risk Profile'); ?></a></li>
						<li class=""><a href="#tab_incident_stakeholders" data-toggle="tab"><?php echo __('Incident Stakeholders'); ?></a></li>
						<li class=""><a href="#tab_incident" data-toggle="tab"><?php echo __('Incident Profile'); ?></a></li>
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
									<span class="help-block"><?php echo __( 'Give the Security Incident a title, name or code so it\'s easily identified on the menu.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Type'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('type', array(
										'options' => getSecurityIncidentTypes(),
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __( 'Incidents can be potential incidents or confirmed incidents. This usually is defined as the incident is investigated and confirmed.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'OPTIONAL: Describe the Security Incident in detail (when, what, where, why, whom, how). You will later update the incident using lifecycle stages or comments on the incident itself.' ); ?></span>
								</div>
							</div>

							

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									// $labels = null;
									// $labelsArr = array();
									// if (isset($this->request->data['Classification'])) {
									// 	foreach ($this->request->data['Classification'] as $item) {
									// 		$labelsArr[] = $item['name'];
									// 	}

									// 	$labels = implode(',', $labelsArr);
									// }
									// elseif (isset($this->request->data['SecurityIncident']['classifications']) && !empty($this->request->data['SecurityIncident']['classifications'])) {
									// 	$labels = $this->request->data['SecurityIncident']['classifications'];
									// }

									// if (!empty($labels)) {
									// 	$this->request->data['SecurityIncident']['classifications'] = $labels;
									// }

									if (isset($this->request->data['Classification']) && is_array($this->request->data['Classification'])) {
										$labelsArr = array();
										foreach ($this->request->data['Classification'] as $item) {
											$labelsArr[] = $item['name'];
										}
										$this->request->data['SecurityIncident']['Classification'] = implode(',', $labelsArr);
									}

									echo $this->Form->input('SecurityIncident.Classification', array(
										'type' => 'hidden',
										'label' => false,
										'div' => false,
										'class' => 'tags-classifications col-md-12 full-width-fix',
										'multiple' => true,
										'data-placeholder' => __('Add a tag')
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Tag this incident according to their characteristics. This can later be useful to apply filters and export data.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Open Date' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'open_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									) ); ?>
									<span class="help-block"><?php echo __('Set the date this incident was reported.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Closure Date' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'closure_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									) ); ?>
									<span class="help-block"><?php echo __('OPTIONAL:

    If stages have not been defined (Sec. Operations / Sec. Incidents / Settings / Stages), this field is mandatory only if "Status" is "Closed"
    If stages are defined and you checkbox to "Automatically Close" then the closure date will be updated once all states are completed, you can leave this box empty'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'security_incident_status_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'For the time the incident is being managed (investigated, communicated, etc.) the incident status should be open. Otherwise it could be closed.' ); ?></span>
								</div>
							</div>

							<?php
							$stagesExists = !empty($stages);
							?>
							
							<?php if ($stagesExists) : ?>
								<script type="text/javascript">
								$(function() {
									$statusSelect = $('#SecurityIncidentSecurityIncidentStatusId');
									$closureDateInput = $('#SecurityIncidentClosureDate');
									$autoCloseInput = $('#SecurityIncidentAutoCloseIncident');

									function toggleClosureDate() {
										if ($autoCloseInput.is(':checked')) {
											$closureDateInput.attr('disabled', true);
										}
										else {
											$closureDateInput.attr('disabled', false);
										}
									}

									toggleClosureDate();
									$autoCloseInput.on('change', function() {
										toggleClosureDate();
									});
								});
								</script>
							<?php endif; ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Automatically Close Incident'); ?>:</label>
								<div class="col-md-10">
									<label class="checkbox">
										<?php
										echo $this->Form->input('auto_close_incident', array(
											'type' => 'checkbox',
											'label' => false,
											'div' => false,
											'class' => 'uniform auto-close-incident',
											'default' => true,
											'disabled' => !$stagesExists
										));
										?>
									</label>
									<span class="help-block"><?php echo __('When all items on the lifecycle are completed this incident will change to "Closed" status automatically.'); ?></span>
									<?php
									if (!$stagesExists) {
										echo $this->Ux->getAlert(__('Not available as stages are not defined.'), array(
											'type' => 'info'
										));
									}
									?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_risk_profile">
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Related Asset Risks'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('SecurityIncident.AssetRisk', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen eramba-auto-complete',
										'multiple' => true,
										'id' => 'asset-risk-id',
										'data-model' => 'AssetRisk',
										'data-url' => '/securityIncidents/getAssets',
										'data-request-key' => 'riskIds',
										'data-request-type' => 'POST',
										'data-assoc-input' => '#compromised-asset'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: If a Risk was previously documented (Risk Management / Asset Risk Management) describing a scenario where this incident could happen, select it in order to include further documentation on this incident (policies to be followed, controls used, assets affected, Etc).'); ?></span>

									<div id="AssetRisk_policies"></div>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'risks', 'action' => 'add'),'text' => __('Add Asset Risk'))); ?>
								</div>
							</div>

							<div class="form-group ">
								<label class="col-md-2 control-label"><?php echo __('Related Third Party Risks'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('SecurityIncident.ThirdPartyRisk', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen eramba-auto-complete',
										'multiple' => true,
										'id' => 'third-party-risk-id',
										'data-model' => 'ThirdPartyRisk',
										'data-url' => '/securityIncidents/getThirdParties',
										'data-request-key' => 'riskIds',
										'data-request-type' => 'POST',
										'data-assoc-input' => '#third-parties-affected'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: If a Risk was previously documented (Risk Management / Third Party Risk Management) describing a scenario where this incident could happen, select it in order to include further documentation on this incident (policies to be followed, controls used, assets affected, Etc).'); ?></span>

									<div id="ThirdPartyRisk_policies"></div>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'thirdPartyRisks', 'action' => 'add'),'text' => __('Add Third Party Risk'))); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Related Business Risks'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('SecurityIncident.BusinessContinuity', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'business-continuity-id',
										'data-model' => 'BusinessContinuity'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: If a Risk was previously documented (Risk Management / Business Impact Analysis) describing a scenario where this incident could happen, select it in order to include further documentation on this incident (policies to be followed, controls used, assets affected, Etc).'); ?></span>

									<div id="BusinessContinuity_policies"></div>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'businessContinuities', 'action' => 'add'),'text' => __('Add Business Risk'))); ?>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_incident_stakeholders">
							<?= $this->FieldData->inputs([
								$FieldDataCollection->Owner
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Reporter' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'reporter', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Is the individual that reported the incident, could be the same as the owner. If unknown at the time the incident was reported this field can be updated later.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Victim' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'victim', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Is the individual that has been affected by the incident. If unknown at the time the incident was reported this field can be updated later.' ); ?></span>
								</div>
							</div>
						</div>
						<div class="tab-pane fade in" id="tab_incident">
							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Affected Compensating Controls' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'SecurityIncident.SecurityService', array(
										'options' => $services,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'compensating-controls'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select one or more controls (from Control Catalogue / Security Services) that are involved on this incident. This fields might autocomplete as you select risks (on the previous tab).' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'securityServices', 'action' => 'add'),'text' => 'Add Security Service')); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Affected Asset'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('SecurityIncident.Asset', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'compromised-asset'
									));
									?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select one or more assets (from Asset Managemnet / Asset Identification) that are involved on this incident. This fields might autocomplete as you select risks (on the previous tab).' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'assets', 'action' => 'add'),'text' => 'Add Asset')); ?>
								</div>
							</div>


							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Affected Third Parties'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('SecurityIncident.ThirdParty', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'id' => 'third-parties-affected'
									));
									?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select one or more assets (from Organisation / Third Parties) that are involved on this incident. This fields might autocomplete as you select risks (on the previous tab).' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'thirdParties', 'action' => 'add'),'text' => 'Add Third Party')); ?>
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
					echo $this->Ajax->cancelBtn('SecurityIncident');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'SecurityIncident',
			'id' => isset($edit) ? $this->data['SecurityIncident']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
	var obj = $.parseJSON('<?php echo $this->Eramba->jsonEncode(array_values($classifications)); ?>');

	$('.tags-classifications').select2({
		tags: obj
	});
});
</script>

<script type="text/javascript">
// procedures warning
jQuery(function($) {
	$("#asset-risk-id, #third-party-risk-id, #business-continuity-id").on("change.ErambaWarning", function(e) {
		var riskIds = [];
		$.each($(this).find("option:selected"), function(i, e) {
			riskIds.push($(e).val());
		});

		getRiskProcedures(riskIds, $(this).data("model"));
	}).trigger("change.ErambaWarning");

	function getRiskProcedures(riskIds, model) {
		var $formGroup = $("#" + model + "_policies").closest(".form-group");

		$.ajax({
			url: "/securityIncidents/getRiskProcedures",
			type: "GET",
			dataType: "HTML",
			data: {
				riskIds: JSON.stringify(riskIds),
				model: model
			},
			beforeSend: function( xhr ) {
				Eramba.Ajax.blockEle($formGroup);
			}
		})
		.done(function(data) {
			var $policyListWrapper = $("#" + model + "_policies");
			$policyListWrapper.empty().html(data);

			Eramba.Ajax.unblockEle($formGroup);
		});
	}
});
</script>

<script type="text/javascript">
jQuery(function($) {
	$("#asset-risk-id, #third-party-risk-id, #business-continuity-id").erambaAutoComplete({
		input: '#asset-risk-id, #third-party-risk-id, #business-continuity-id',
		url: '/securityIncidents/getControls',
		requestKey: ['riskIds', 'tpRiskIds', 'buRiskIds'],
		requestType: 'GET',
		assocInput: '#compensating-controls'
	});
});
</script>
