 <div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Asset identification'); ?>
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
				<table class="triple-column" style="table-layout:fixed;">
					<tr>
						<th>
							<?php echo __('Name'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
						<!-- <th>
							<?php //echo __('Workflows'); ?>
						</th> -->
					</tr>
					
					<tr>
						<td>
							<?php echo $item['Asset']['name']; ?>
						</td>
						<td>
							<?php echo $this->Assets->getStatuses($item); ?>
						</td>
						<!-- <td>
							<?php //echo $this->Workflow->getStatuses($item['Asset']); ?>
						</td> -->
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
							if (!empty($item['Asset']['description'])) {
								echo nl2br($item['Asset']['description']);
							}
							else {
								echo '-';
							}
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table class="triple-column" style="table-layout:fixed !important;">
					<tr>
						<th>
							<?php echo __('Type'); ?>
						</th>
						<th>
							<?php echo __('Label'); ?>
						</th>
						<th>
							<?php echo __('Asociated business units'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['AssetMediaType']['name']; ?>
						</td>
						<td>
							<?php echo isset( $item['AssetLabel']['name'] ) ? $item['AssetLabel']['name'] : '-'; ?>
						</td>
						<td>
							<?php
							$businessUnits = array();
							foreach ($item['BusinessUnit'] as $bu) {
								$businessUnits[] = $bu['name'];
							}
							echo implode(', ', $businessUnits);
							?>
						</td>
					</tr>

				</table>
			</div>

			<div class="item">
				<table class="triple-column" style="table-layout:fixed;">
					<tr>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Guardian'); ?>
						</th>
						<th>
							<?php echo __('User'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo ( ! empty( $item['AssetOwner'] ) ) ? $item['AssetOwner']['name'] : ''; ?>
						</td>
						<td>
							<?php echo ( ! empty( $item['AssetGuardian'] ) ) ? $item['AssetGuardian']['name'] : ''; ?>
						</td>
						<td>
							<?php echo ( ! empty( $item['Asset']['asset_user_id'] ) ) ? $item['AssetUser']['name'] : __('Everyone'); ?>
						</td>
					</tr>

				</table>
			</div>	

			<?php if ( !empty( $item['AssetClassification'] ) ) : ?>

				<div class="separator"></div>

				<?php foreach ( $item['AssetClassification'] as $classification ) : ?>

					<div class="item">
						<table>
							<tr>
								<th><?php echo $classification['AssetClassificationType']['name']; ?></th>
							</tr>
							
							<tr>
								<td><?php echo $classification['name']; ?></td>
							</tr>
						</table>
					</div>
					
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	</div>
</div>


<?php echo $this->element('pdf_reviews_data'); ?>
<?php echo $this->element('pdf_common_data'); ?>