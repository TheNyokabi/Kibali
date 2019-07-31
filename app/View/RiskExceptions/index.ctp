<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>

					<?php
					// echo $this->Html->link( '<i class="icon-cog"></i>' . __('Workflow'), array(
					// 	'controller' => 'workflows',
					// 	'action' => 'edit',
					// 	$workflowSettingsId
					// ), array(
					// 	'class' => 'btn',
					// 	'escape' => false
					// ));
					?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'RiskException'); ?>
				
				<?php echo $this->NotificationSystem->getIndexLink('RiskException'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'RiskException' => __('Risk Exception'),
				)); ?>

				<?php echo $this->Video->getVideoLink('RiskException'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>

				<?php //echo $this->element( CORE_ELEMENT_PATH . 'filter' , array('filterElement' => $this->element(FILTERS_ELEMENT_PATH . 'filter_risk_exception'))); ?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'RiskException')); ?>
<div class="row">
	<div class="col-md-12">
		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				$widgetClass = $this->PolicyExceptions->getHeaderClass($entry, 'RiskException');
				?>
				<div class="widget box widget-closed <?php echo $widgetClass; ?>">
					<div class="widget-header">
						<h4><?php echo __('Risk Exeptions'); ?>: <?php echo $entry['RiskException']['title']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
									<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
								</span>
								<?php
								$exportUrl = array(
									'controller' => 'riskExceptions',
									'action' => 'exportPdf',
									 $entry['RiskException']['id']
								);

								$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

								echo $this->Ajax->getActionList($entry['RiskException']['id'], array(
									'notifications' => $notificationSystemEnabled,
									'item' => $entry,
									'history' => true
								));
							?>
							</div>
						</div>
					</div>
					<div class="widget-subheader">
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The requester is usually the person that approved the risk Exception (and usually the person that accepts taking the risk asociated with it)' ); ?>'>
								<?php echo __( 'Requester' ); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date when this risk exceptions expires. You can optionally set an email notification.' ); ?>'>
								<?php echo __('Expiration'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'TBD' ); ?>'>
								<?php echo __('Closure date'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th class="text-center">
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The sum of the raw risk scores asociated with the risks where this exception takes part. This is an interesting number as if its too high (in comparison with the average risk score or the total risk score) it could indicate that the risk being accepted is too much.' ); ?>'>
								<?php echo __('Risk Score Sum'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th class="text-center">
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The same as risk score but asociated with the residual risk scores.' ); ?>'>
								<?php echo __('Residual Score Sum'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
								<?php
								echo __('Tags');
								?>
							</th>
							<th class="text-center">
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status for risk exceptions can be: "Expired (red)" - when the date set is in the past. A system record is generated on the exception when that happens.'); ?>'>
								<?php echo __('Status'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<?php /*
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Workflows define the approvals required to create, modify or delete an item. Approved items are valid throughout the system, Draft items require approval and Pending Approvals or Validations means that the workflow is still in process and is pending user interaction.' ); ?>'>
							<?php echo __( 'Workflows' ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							*/ ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td ><?= $this->UserField->showUserFieldRecords($entry['Requester']); ?></td>
									<td ><?php echo $this->Ux->date($entry['RiskException']['expiration']); ?></td>
									<td ><?php echo $this->Ux->date($entry['RiskException']['closure_date']); ?></td>
									<td class="text-center">
										<?php
										$residualRisk = 0;
										foreach ($entry['Risk'] as $key => $val){
											$residualRisk+= $val['risk_score'];
										}
										foreach ($entry['ThirdPartyRisk'] as $key => $val){
											$residualRisk+= $val['risk_score'];
										}
										foreach ($entry['BusinessContinuity'] as $key => $val){
											$residualRisk+= $val['risk_score'];
										}
										echo $residualRisk;
										?>
									</td>
									<td class="text-center">
										<?php
										$riskScore = 0;
										foreach ($entry['Risk'] as $key => $val){
											$riskScore+= $val['residual_risk'];
										}
										foreach ($entry['ThirdPartyRisk'] as $key => $val){
											$riskScore+= $val['residual_risk'];
										}
										foreach ($entry['BusinessContinuity'] as $key => $val){
											$riskScore+= $val['residual_risk'];
										}
										echo $riskScore;
										?>
									</td>
									<td>
										<?php
										echo $this->RiskExceptions->getTags($entry);
										?>
									</td>
									<td>
										<?php
										echo $this->PolicyExceptions->getStatuses($entry, 'RiskException', true);
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['RiskException']['id'],
											'item' => $this->Workflow->getActions($entry['RiskException'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
							</tbody>
						</table>
						<table class="table table-hover table-striped table-bordered table-highlight-head">
							<thead>
								<tr>
									<th><?php echo __( 'Description' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><?php echo $this->Eramba->getEmptyValue($entry['RiskException']['description']); ?></td>
								</tr>
							</tbody>
						</table>
						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Associated Risks' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<table class="table table-hover table-striped table-bordered table-highlight-head">
									<thead>
										<tr>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The type of risk indicates what is in which risks this risk exception is applied to. Options are: Risk (asset based risk), Third Party Risk or Business Risk.' ); ?>'>
								<?php echo __('Type'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The risk title' ); ?>'>
								<?php echo __('Risk Title'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th class="text-center">
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The risk score asociated with this risk' ); ?>'>
								<?php echo __('Risk Score'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th class="text-center">
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The residual risk asociated with this risk' ); ?>'>
								<?php echo __('Residual Risk'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The date (configured on the risk) when a review is required' ); ?>'>
								<?php echo __('Risk Review Date'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th class="text-center">
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status of the risk (for details review the risk module)' ); ?>'>
								<?php echo __('Status'); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($entry['Risk'] as $key => $val): ?>
										<tr>
											<td><?php echo __('Asset Risk') ?></td>
											<td><?php echo $this->Ux->getItemLink($val['title'], 'Risk', $val['id']);?></td>
											<td class="text-center"><?php echo $val['risk_score'] ?></td>
											<td class="text-center"><?php echo $val['residual_risk'] ?></td>
											<td><?php echo $val['review']; ?></td>
											<td>
												<?php
												echo $this->Risks->getStatuses($val, 'Risk', false);
												?>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php foreach ($entry['ThirdPartyRisk'] as $key => $val): ?>
										<tr>
											<td><?php echo __('Third Party Risk') ?></td>
											<td><?php echo $this->Ux->getItemLink($val['title'], 'ThirdPartyRisk', $val['id']);?></td>
											<td class="text-center"><?php echo $val['risk_score'] ?></td>
											<td class="text-center"><?php echo $val['residual_risk'] ?></td>
											<td><?php echo $val['review']; ?></td>
											<td>
												<?php
												echo $this->Risks->getStatuses($val, 'ThirdPartyRisk', false);
												?>
											</td>
										</tr>
										<?php endforeach; ?>
										<?php foreach ($entry['BusinessContinuity'] as $key => $val): ?>
										<tr>
											<td><?php echo __('Business Risk') ?></td>
											<td><?php echo $this->Ux->getItemLink($val['title'], 'BusinessContinuity', $val['id']);?></td>
											<td class="text-center"><?php echo $val['risk_score'] ?></td>
											<td class="text-center"><?php echo $val['residual_risk'] ?></td>
											<td><?php echo $val['review']; ?></td>
											<td>
												<?php
												echo $this->Risks->getStatuses($val, 'BusinessContinuity', false);
												?>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>

						<?php
						echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
							'item' => $entry // single database item in a variable
						));
						?>
					</div>
					<div class="widget-content" style="display:none;">

					</div>
				</div>

			<?php endforeach; ?>

			<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'No Risk Exeptions found.' )
			) ); ?>
		<?php endif; ?>
	</div>
</div>
