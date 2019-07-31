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
								<th><?php echo __('Type'); ?></th>
								<th><?php echo __('Author'); ?></th>
								<th><?php echo __('Permission'); ?></th>
								<th><?php echo __('Version'); ?></th>
								<th><?php echo __('Labels'); ?></th>
								<th><?php echo __('Review'); ?></th>
								<th><?php echo __('Status'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($items as $item) : ?>
								<tr>
									<td><?php echo $item['SecurityPolicy']['index']; ?></td>
									<td>
										<?php
										echo $item['SecurityPolicyDocumentType']['name'];
										?>
									</td>
									<td>
										<?= $this->UserField->showUserFieldRecords($item['Owner']); ?>
									</td>
									<td>
										<?php
										$documentPermissions = getPoliciesDocumentPermissions();
										echo $documentPermissions[$item['SecurityPolicy']['permission']];
										?>
									</td>
									<td>
										<?php echo $item['SecurityPolicy']['version']; ?>
									</td>
									<td>
										<?php
										if (!empty($item['AssetLabel']['id'])) {
											echo $item['AssetLabel']['name'];
										}
										?>
									</td>
									<td>
										<?php echo $item['SecurityPolicy']['next_review_date']; ?>
									</td>
									<td>
										<?php
										echo $this->SecurityPolicies->getStatuses($item, true);
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