<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Asset Indentification'); ?>
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
								<th><?php echo __('Business Units'); ?></th>
								<th><?php echo __('Type'); ?></th>
								<th><?php echo __('Liabilities'); ?></th>
								<th><?php echo __('Owner'); ?></th>
								<th><?php echo __('Review Date'); ?></th>
								<th><?php echo __('Status'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($items as $item) : ?>
								<tr>
									<td><?php echo $item['Asset']['name']; ?></td>
									<td>
										<?php
										$businessUnits = array();
										foreach ($item['BusinessUnit'] as $bu) {
											$businessUnits[] = $bu['name'];
										}
										echo implode(', ', $businessUnits);
										?>
									</td>
									<td><?php echo isset( $item['AssetMediaType']['name'] ) ? $item['AssetMediaType']['name'] : ''; ?></td>
									<td>
									<?php
										$legals = array();
										foreach ($item['Legal'] as $legal) {
											$legals[] = $legal['name'];
										}
										echo implode(', ', $legals);
										?>
									</td>
									<td><?php echo ( ! empty( $item['AssetOwner'] ) ) ? $item['AssetOwner']['name'] : ''; ?></td>
									<td><?php echo $item['Asset']['review']; ?></td>
									<td>
										<?php
										echo $this->Assets->getStatuses($item, true);
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