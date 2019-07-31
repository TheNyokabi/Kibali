<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Program Goals'); ?>
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
							<?php echo __('Name'); ?>
						</th>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['Goal']['name']; ?>
						</td>
						<td>
							<?php
							if (!empty($item['Owner']['login'])) {
								echo $item['Owner']['name'] . ' ' .$item['Owner']['surname'];
							}
							else {
								echo '-';
							}
							?>
						</td>
						<td>
							<?php echo getGoalStatuses($item['Goal']['status']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Description'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							echo $this->Eramba->getEmptyValue(nl2br($item['Goal']['description']));
							?>
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>
</div>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Metrics'); ?>
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
							<?php echo __('Metric'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $this->Eramba->getEmptyValue(nl2br($item['Goal']['audit_metric'])); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Success Criteria'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							echo $this->Eramba->getEmptyValue(nl2br($item['Goal']['audit_criteria']));
							?>
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>
</div>

<div class="separator"></div>

<?php
echo $this->element('pdf/audits', array(
	'title' => __('Metrics Reviews')
));
?>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Mapped Activities'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<?php
			$noMappedItems = true;
			$noMappedItems &= empty($item['SecurityService']);
			$noMappedItems &= empty($item['SecurityPolicy']);
			$noMappedItems &= empty($item['Risk']);
			$noMappedItems &= empty($item['ThirdPartyRisk']);
			$noMappedItems &= empty($item['BusinessContinuity']);
			$noMappedItems &= empty($item['Project']);
			?>
			<?php if (!$noMappedItems) : ?>
				<div class="item">
					<table class="table-pdf table-pdf-list" style="">
						<thead>
							<tr>
								<th><?php echo __('Type'); ?></th>
								<th><?php echo __('Name'); ?></th>
								<th><?php echo __('Status'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($item['SecurityService'] as $mapped) : ?>
								<tr>
									<td><?php echo __('Security Service'); ?></td>
									<td><?php echo $mapped['name']; ?></td>
									<td>
										<?php
										echo $this->SecurityServices->getStatuses($mapped);
										?>
									</td>
								</tr>
							<?php endforeach; ?>

							<?php foreach ($item['SecurityPolicy'] as $mapped) : ?>
								<tr>
									<td><?php echo __('Security Policy'); ?></td>
									<td><?php echo $mapped['index']; ?></td>
									<td>
										<?php
										echo $this->SecurityPolicies->getStatuses($mapped);
										?>
									</td>
								</tr>
							<?php endforeach; ?>

							<?php foreach ($item['Risk'] as $mapped) : ?>
								<tr>
									<td><?php echo __('Risk'); ?></td>
									<td><?php echo $mapped['title']; ?></td>
									<td>
										<?php
										echo $this->Risks->getStatuses($mapped, 'Risk');
										?>
									</td>
								</tr>
							<?php endforeach; ?>

							<?php foreach ($item['ThirdPartyRisk'] as $mapped) : ?>
								<tr>
									<td><?php echo __('Third Party Risk'); ?></td>
									<td><?php echo $mapped['title']; ?></td>
									<td>
										<?php
										echo $this->Risks->getStatuses($mapped, 'ThirdPartyRisk');
										?>
									</td>
								</tr>
							<?php endforeach; ?>

							<?php foreach ($item['BusinessContinuity'] as $mapped) : ?>
								<tr>
									<td><?php echo __('Business Impact Analysis'); ?></td>
									<td><?php echo $mapped['title']; ?></td>
									<td>
										<?php
										echo $this->Risks->getStatuses($mapped, 'BusinessContinuity');
										?>
									</td>
								</tr>
							<?php endforeach; ?>

							<?php foreach ($item['Project'] as $mapped) : ?>
								<tr>
									<td><?php echo __('Project'); ?></td>
									<td><?php echo $mapped['title']; ?></td>
									<td>
										<?php
										echo $this->Projects->getStatuses($mapped);
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

			<?php else : ?>
				<?php
				echo $this->element('not_found', array(
					'message' => __('No Activities found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php echo $this->element('pdf_common_data'); ?>