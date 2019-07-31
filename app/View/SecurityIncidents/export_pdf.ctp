<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Security Incident'); ?>
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
							<?php echo __('ID'); ?>
						</th>
						<th>
							<?php echo __('Title'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['SecurityIncident']['id']; ?>
						</td>
						<td>
							<?php echo $item['SecurityIncident']['title']; ?>
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
							<?php echo __('Reporter'); ?>
						</th>
						<th>
							<?php echo __('Victim'); ?>
						</th>
						<th>
							<?php echo __('Open Date'); ?>
						</th>
						<th>
							<?php echo __('Closure Date'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Owner']); ?>
						</td>
						<td>
							<?php echo $item['SecurityIncident']['reporter']; ?>
						</td>
						<td>
							<?php echo $item['SecurityIncident']['victim']; ?>
						</td>
						<td>
							<?php echo $item['SecurityIncident']['open_date']; ?>
						</td>
						<td>
							<?php echo $item['SecurityIncident']['closure_date']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Type'); ?>
						</th>
						<th>
							<?php echo __('Classification'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo getSecurityIncidentTypes($item['SecurityIncident']['type']); ?>
						</td>
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
							<?php echo $this->SecurityIncidents->getStatuses($item); ?>
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
							<?php echo $this->Eramba->getEmptyValue(nl2br($item['SecurityIncident']['description'])); ?>
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>
</div>

<?php
echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
	'item' => $item, // single database item in a variable
	'layout' => 'pdf'
));
?>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Associated Risks'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<?php if (!empty($item['AssociatedRisks'])) : ?>
				<div class="item">
					<table class="table-pdf table-pdf-list" style="">
						<thead>
							<tr>
								<th><?php echo __('Type'); ?></th>
								<th><?php echo __('Name'); ?></th>
								<th><?php echo __('Incident Containment Procedure'); ?></th>
								<th><?php echo __('Status'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($item['AssociatedRisks'] as $risk) : ?>
							<tr>
								<td><?php echo getRiskTypes($risk['RisksSecurityIncident']['risk_type']); ?></td>
								<td><?php echo $risk['title']; ?></td>
								<td>
									<?php
									foreach ($risk['SecurityPolicy'] as $policy) : ?>
										<?php echo $policy['index']; ?>
										<br />
									<?php endforeach; ?>
								</td>
								<td><?php echo $this->Risks->getStatuses($risk); ?></td>
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
					<?php echo __('Affected Items'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<?php if (!empty($item['Affected'])) : ?>
				<div class="item">
					<table class="table-pdf table-pdf-list" style="">
						<thead>
							<tr>
								<th><?php echo __('Type'); ?></th>
								<th><?php echo __('Name'); ?></th>
								<th><?php echo __('Liabilities'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($item['Affected'] as $affect) : ?>
							<tr>
								<td><?php echo $affect['type']; ?></td>
								<td><?php echo $affect['name']; ?></td>
								<td><?php echo $affect['liabilities']; ?></td>
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
					<?php echo __('Incident Lifecycle'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<?php if (!empty($item['SecurityIncidentStage'])) : ?>
				<div class="item">
					<table class="table-pdf table-pdf-list" style="">
						<thead>
							<tr>
								<th><?php echo __('Stage'); ?></th>
								<th><?php echo __('Status'); ?></th>
								<th><?php echo __('Description'); ?></th>
							</tr>
						</thead>
						
						<tbody>
							<?php foreach ($item['SecurityIncidentStage'] as $stage) : ?>
							<tr>
								<td><?php echo $stage['name']; ?></td>
								<td>
									<?php
										$statuses = getSecurityIncidentStageStatus();
										$labelClass = $stage['SecurityIncidentStagesSecurityIncident']['status']?'label-success':'label-danger';
										$sId = $stage['SecurityIncidentStagesSecurityIncident']['id'];
										$sStatus = $stage['SecurityIncidentStagesSecurityIncident']['status'];
										?>
										<span class="label <?php echo $labelClass ?> stage-<?php echo $sId?>">
											<?php echo $statuses[$sStatus]; ?>
										</span>
								</td>
								<td><?php echo $stage['description']; ?></td>
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

<?php echo $this->element('pdf_common_data'); ?>