<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Security Services'); ?>
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
								<th><?php echo __('Release'); ?></th>
								<th><?php echo __('Owner'); ?></th>
								<th><?php echo __('Opex'); ?></th>
								<th><?php echo __('Capex'); ?></th>
								<th><?php echo __('Classification'); ?></th>
								<th><?php echo __('Status'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($items as $item) : ?>
								<tr>
									<td><?php echo $item['SecurityService']['name']; ?></td>
									<td><?php echo $item['SecurityServiceType']['name']; ?></td>
									<td><?= $this->UserField->showUserFieldRecords($item['ServiceOwner']); ?></td>
									<td><?php echo CakeNumber::currency( $item['SecurityService']['opex'] ); ?></td>
									<td><?php echo CakeNumber::currency( $item['SecurityService']['capex'] ); ?></td>
									<td>
										<?php
										$labels = array();
										foreach ($item['Classification'] as $label) {
											$labels[] = $this->Html->tag('span', $label['name'], array(
												'class' => 'label label-primary'
											));
										}

										echo implode(' ', $labels);
										?>
									</td>
									
									<td>
										<?php
										echo $this->SecurityServices->getStatuses($item, true);
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