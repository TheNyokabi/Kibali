<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Risk exception'); ?>
				</h1>
			</div>
			<div class="subtitle">
				<h2>
					<?php echo __('General information'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Exception name'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['RiskException']['title']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $this->PolicyExceptions->getStatuses($item, 'RiskException', true); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table class="double-column">
					<tr>
						<th>
							<?php echo __('Requester'); ?>
						</th>
						<th>
							<?php echo __('Risk score sum'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Requester']); ?>
						</td>
						<td>
							<?php
							$residualRisk = 0;
							foreach ($item['Risk'] as $key => $val){
								$residualRisk+= $val['residual_score'];
							}
							foreach ($item['ThirdPartyRisk'] as $key => $val){
								$residualRisk+= $val['residual_score'];
							}
							foreach ($item['BusinessContinuity'] as $key => $val){
								$residualRisk+= $val['residual_score'];
							}
							echo $residualRisk;
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table class="double-column">
					<tr>
						<th>
							<?php echo __('Expiration'); ?>
						</th>
						<th>
							<?php echo __('Closure date'); ?>
						</th>
						<th>
							<?php echo __('Residual score sum'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $this->Ux->date($item['RiskException']['expiration']); ?>
						</td>
						<td>
							<?php echo $this->Ux->date($item['RiskException']['closure_date']); ?>
						</td>
						<td>
							<?php echo $item['RiskException']['closure_date']; ?>
						</td>
						<td>
							<?php
							$riskScore = 0;
							foreach ($item['Risk'] as $key => $val){
								$riskScore+= $val['risk_score'];
							}
							foreach ($item['ThirdPartyRisk'] as $key => $val){
								$riskScore+= $val['risk_score'];
							}
							foreach ($item['BusinessContinuity'] as $key => $val){
								$riskScore+= $val['risk_score'];
							}
							echo $riskScore;
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Description'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo nl2br($item['RiskException']['description']); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

	</div>
</div>



<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Associated Items'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">

			<?php if (isset($item['Risk']) && (!empty($item['Risk']))): ?>
				<?php foreach ($item['Risk'] as $key => $val): ?>
					
					<div class="item">
						<table class="double-column">
							<tr>
								<th>
									<?php echo __('Type'); ?>
								</th>
								<th>
									<?php echo __('Title'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo __('Asset Risk') ?>
								</td>
								<td>
									<?php echo $val['title'] ?>
								</td>
							</tr>
						</table>
					</div>

					<div class="item">
						<table class="quadruple-column-uneven">
							<tr>
								<th>
									<?php echo __('Risk score'); ?>
								</th>
								<th>
									<?php echo __('Residual risk'); ?>
								</th>
								<th>
									<?php echo __('Review date'); ?>
								</th>
								<th>
									<?php echo __('Status'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $val['residual_score'] ?>
								</td>
								<td>
									<?php echo $val['risk_score'] ?>
								</td>
								<td>
									<?php echo $val['review']; ?>
								</td>
								<td>
									<?php echo $this->Risks->getStatuses($val, 'Risk', false); ?>
								</td>
							</tr>

						</table>
					</div>

				<?php endforeach; ?>

				<div class="separator"></div>

			<?php endif; ?>



			<?php if (isset($item['ThirdPartyRisk']) && (!empty($item['ThirdPartyRisk']))): ?>
				<?php foreach ($item['ThirdPartyRisk'] as $key => $val): ?>
					
					<div class="item">
						<table class="double-column">
							<tr>
								<th>
									<?php echo __('Type'); ?>
								</th>
								<th>
									<?php echo __('Title'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo __('Third Party Risk') ?>
								</td>
								<td>
									<?php echo $val['title'] ?>
								</td>
							</tr>
						</table>
					</div>

					<div class="item">
						<table class="quadruple-column-uneven">
							<tr>
								<th>
									<?php echo __('Risk score'); ?>
								</th>
								<th>
									<?php echo __('Residual risk'); ?>
								</th>
								<th>
									<?php echo __('Review date'); ?>
								</th>
								<th>
									<?php echo __('Status'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $val['residual_score'] ?>
								</td>
								<td>
									<?php echo $val['risk_score'] ?>
								</td>
								<td>
									<?php echo $val['review']; ?>
								</td>
								<td>
									<?php echo $this->Risks->getStatuses($val, 'ThirdPartyRisk', false); ?>
								</td>
							</tr>

						</table>
					</div>

				<?php endforeach; ?>

				<div class="separator"></div>
				
			<?php endif; ?>

			<?php if (isset($item['BusinessContinuity']) && (!empty($item['BusinessContinuity']))): ?>
				<?php foreach ($item['BusinessContinuity'] as $key => $val): ?>
					
					<div class="item">
						<table class="double-column">
							<tr>
								<th>
									<?php echo __('Type'); ?>
								</th>
								<th>
									<?php echo __('Title'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo __('Business Risk') ?>
								</td>
								<td>
									<?php echo $val['title'] ?>
								</td>
							</tr>
						</table>
					</div>

					<div class="item">
						<table class="quadruple-column-uneven">
							<tr>
								<th>
									<?php echo __('Risk score'); ?>
								</th>
								<th>
									<?php echo __('Residual risk'); ?>
								</th>
								<th>
									<?php echo __('Review date'); ?>
								</th>
								<th>
									<?php echo __('Status'); ?>
								</th>
							</tr>
							
							<tr>
								<td>
									<?php echo $val['residual_score'] ?>
								</td>
								<td>
									<?php echo $val['risk_score'] ?>
								</td>
								<td>
									<?php echo $val['review']; ?>
								</td>
								<td>
									<?php echo $this->Risks->getStatuses($val, 'BusinessContinuity', false); ?>
								</td>
							</tr>

						</table>
					</div>

				<?php endforeach; ?>

				<div class="separator"></div>
				
			<?php endif; ?>

		</div>
	</div>
</div>

<?php echo $this->element('pdf_common_data'); ?>

