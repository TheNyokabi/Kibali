<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Compliance exception'); ?>
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
							<?php echo $item['ComplianceException']['title']; ?>
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
							<?php echo $this->ComplianceExceptions->getStatuses($item); ?>
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
							<?php echo __('Compliance packages'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Requestor']); ?>
						</td>
						<td>
							<?php echo !empty($item['ComplianceException']['compliance_packages']) ? $item['ComplianceException']['compliance_packages'] : '-'; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Expiration'); ?>
						</th>
						<th>
							<?php echo __('Closure date'); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php echo $this->Ux->date($item['ComplianceException']['expiration']); ?>
						</td>
						<td>
							<?php echo $this->Ux->date($item['ComplianceException']['closure_date']); ?>
						</td>
						<td>
							<?php echo $item['ComplianceException']['closure_date']; ?>
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
							<?php echo nl2br($item['ComplianceException']['description']); ?>
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
			<div class="item">
				<?php if (!empty($item['ComplianceManagement'])) : ?>
					<table class="tripple-column-uneven-small-middle">
						<tr>
							<th>
								<?php echo __('Package'); ?>
							</th>
							<th>
								<?php echo __('Id'); ?>
							</th>
							<th>
								<?php echo __('Requirement'); ?>
							</th>
						</tr>
						
						<?php foreach ($item['ComplianceManagement'] as $key => $val): ?>
							<tr>
								<td><?php echo $val['CompliancePackageItem']['CompliancePackage']['ThirdParty']['name'] ?></td>
								<td><?php echo $val['CompliancePackageItem']['item_id'] ?></td>
								<td><?php echo $val['CompliancePackageItem']['name'] ?></td>
							</tr>
						<?php endforeach; ?>
					</table>
				<?php else : ?>
					<?php
					echo $this->Html->div('alert', 
						__('No associated items found.')
					);
					?>
				<?php endif; ?>
			</div>
		</div>

	</div>
</div>


<?php echo $this->element('pdf_common_data'); ?>


