<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Third Party Risks'); ?>
				</h1>
			</div>
			<div class="subtitle">
				<h2>
					<?php echo __('Summary'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<?php if (!empty($items)): ?>

	<div class="row">
		<div class="col-xs-12">
			<div class="body">

				<div class="item">
					<table class="table-pdf table-pdf-system-records" style="">
						<thead>
							<tr>
								<th><?php echo __('Name'); ?></th>
								<th><?php echo __('Stakeholder'); ?></th>
								<th><?php echo __('Mitigation Strategy'); ?></th>
								<th><?php echo __('Risk Score'); ?></th>
								<th><?php echo __('Residual Score'); ?></th>
								<th><?php echo __('Review Date'); ?></th>
								<th><?php echo __('Status'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($items as $item) : ?>
								<tr>
									<td><?php echo $item['ThirdPartyRisk']['title']; ?></td>
									<td><?= $this->UserField->showUserFieldRecords($item['Stakeholder']); ?></td>
									<td><?php echo $item['RiskMitigationStrategy']['name']; ?></td>
									<td><?php echo $item['ThirdPartyRisk']['risk_score']; ?></td>
									<td><?php echo $item['ThirdPartyRisk']['residual_risk']; ?></td>
									<td><?php echo $item['ThirdPartyRisk']['review']; ?></td>
									<td>
										<?php
										echo $this->Risks->getStatuses($item, 'ThirdPartyRisk', true);
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
<?php else : ?>
	<?php
	echo $this->element('not_found', array(
		'message' => __('No items found.')
	));
	?>
<?php endif; ?>