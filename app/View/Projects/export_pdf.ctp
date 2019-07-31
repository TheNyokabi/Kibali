<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Project Management'); ?>
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
							<?php echo __('Title'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['Project']['title']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Start'); ?>
						</th>
						<th>
							<?php echo __('Deadline'); ?>
						</th>
						<th>
							<?php echo __('Budget'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Owner']); ?>
						</td>
						<td>
							<?php echo $item['Project']['start']; ?>
						</td>
						<td>
							<?php echo $item['Project']['deadline']; ?>
						</td>
						<td>
							<?php echo CakeNumber::currency($item['Project']['plan_budget']); ?>
						</td>
						<td>
							<?php echo $this->Projects->getStatuses($item); ?>
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
					<?php echo __('Associated Tasks'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<?php if (!empty($item['ProjectAchievement'])) : ?>
				<div class="item">
					<table class="table-pdf table-pdf-list" style="">
						<thead>
							<tr>
								<th><?php echo __('Owner'); ?></th>
								<th><?php echo __('Deadline'); ?></th>
								<th><?php echo __('Completion'); ?></th>
								<th><?php echo __('Order'); ?></th>
								<th><?php echo __('Description'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($item['ProjectAchievement'] as $task) : ?>
							<tr>
								<td><?php echo $task['User']['full_name']; ?></td>
								<td><?php echo $task['date']; ?></td>
								<td><?php echo CakeNumber::toPercentage($task['completion'], 0); ?></td>
								<td><?php echo $task['task_order']; ?></td>
								<td><?php echo $task['description']; ?></td>
							</tr>
							<?php endforeach ; ?>
						</tbody>
					</table>
				</div>

			<?php else : ?>
				<?php
				echo $this->element('not_found', array(
					'message' => __('No Tasks found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>
</div>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Associated Expenses'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<?php if (!empty($item['ProjectExpense'])) : ?>
				<div class="item">
					<table class="table-pdf table-pdf-list" style="">
						<thead>
							<tr>
								<th><?php echo __('Date'); ?></th>
								<th><?php echo __('Amount'); ?></th>
								<th><?php echo __('Description'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($item['ProjectExpense'] as $expense) : ?>
							<tr>
								<td><?php echo $expense['date']; ?></td>
								<td><?php echo CakeNumber::currency($expense['amount']); ?></td>
								<td><?php echo nl2br($expense['description']); ?></td>
							</tr>
							<?php endforeach ; ?>
						</tbody>
					</table>
				</div>

			<?php else : ?>
				<?php
				echo $this->element('not_found', array(
					'message' => __('No Expenses found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>
</div>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

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
			<?php
			$noMappedItems = true;
			$noMappedItems &= empty($item['Risk']);
			$noMappedItems &= empty($item['ThirdPartyRisk']);
			$noMappedItems &= empty($item['BusinessContinuity']);
			$noMappedItems &= empty($item['SecurityService']);
			$noMappedItems &= empty($item['SecurityPolicy']);
			$noMappedItems &= empty($item['ComplianceManagement']);
			$noMappedItems &= empty($item['DataAsset']);
			?>
			<?php if (!$noMappedItems) : ?>
				<div class="item">
					<table class="table-pdf table-pdf-list" style="">
						<thead>
							<tr>
								<th><?php echo __('Name'); ?></th>
								<th><?php echo __('Type'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($item['Risk'] as $mappedItem) : ?>
							<tr>
								<td><?php echo $mappedItem['title'] ; ?></td>
								<td><?php echo __('Asset Risk'); ?></td>
							</tr>
							<?php endforeach ; ?>
							<?php foreach ($item['ThirdPartyRisk'] as $mappedItem) : ?>
							<tr>
								<td><?php echo $mappedItem['title']; ?></td>
								<td><?php echo __('Third Party Risk'); ?></td>
							</tr>
							<?php endforeach ; ?>
							<?php foreach ($item['BusinessContinuity'] as $mappedItem) : ?>
							<tr>
								<td><?php echo $mappedItem['title']; ?></td>
								<td><?php echo __('Business Risk'); ?></td>
							</tr>
							<?php endforeach ; ?>
							<?php foreach ($item['SecurityService'] as $mappedItem) : ?>
							<tr>
								<td><?php echo $mappedItem['name']; ?></td>
								<td><?php echo __('Security Service'); ?></td>
							</tr>
							<?php endforeach ; ?>
							<?php foreach ($item['SecurityPolicy'] as $mappedItem) : ?>
							<?php
							$docLabel = __('%s Document', $mappedItem['SecurityPolicyDocumentType']['name']);
							?>
							<tr>
								<td><?php echo $mappedItem['index']; ?></td>
								<td><?php echo $docLabel; ?></td>
							</tr>
							<?php endforeach ; ?>
							<?php foreach ($item['ComplianceManagement'] as $mappedItem) : ?>
							<tr>
								<td><?php echo $mappedItem['CompliancePackageItem']['name']; ?></td>
								<td><?php echo __('Compliance Item'); ?></td>
							</tr>
							<?php endforeach ; ?>
							<?php foreach ($item['DataAsset'] as $mappedItem) : ?>
							<tr>
								<td><?php echo $mappedItem['description']; ?></td>
								<td><?php echo __('Data Asset'); ?></td>
							</tr>
							<?php endforeach ; ?>

							<?php foreach ($item['SecurityServiceAuditImprovement'] as $mappedItem) : ?>
							<?php
							$text = __('Audit correction for "%s" Security Service', $mappedItem['SecurityServiceAudit']['SecurityService']['name']);
							?>
							<tr>
								<td><?php echo $text; ?></td>
								<td><?php echo __('Security Service Audit'); ?></td>
							</tr>
							<?php endforeach ; ?>

							<?php foreach ($item['BusinessContinuityPlanAuditImprovement'] as $mappedItem) : ?>
							<?php
							$text = __('Audit correction for "%s" Business Continuity Plan', $mappedItem['BusinessContinuityPlanAudit']['BusinessContinuityPlan']['title']);
							?>
							<tr>
								<td><?php echo $text; ?></td>
								<td><?php echo __('Business Continuity Plan Audit'); ?></td>
							</tr>
							<?php endforeach ; ?>
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