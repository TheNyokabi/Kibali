 <div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Compliance Finding'); ?>
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
							<?php echo __('Title'); ?>
						</th>
						<th>
							<?php echo __('Item'); ?>
						</th>
						<th>
							<?php echo __('Compliance Exception'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['ComplianceFinding']['title']; ?>
						</td>
						<td>
							<?php
							echo $item['CompliancePackageItem']['item_id'] . ' - ' . $item['CompliancePackageItem']['name'];
							?>
						</td>
						<td>
							<?php
							$exceptions = Hash::extract($item, 'ComplianceException.{n}.title');
							echo $this->Eramba->getEmptyValue(implode(', ', $exceptions));
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table class="triple-column" style="table-layout:fixed;">
					<tr>
						<th>
							<?php echo __('Third Party Risk'); ?>
						</th>
						<th>
							<?php echo __('Deadline'); ?>
						</th>
						<th>
							<?php echo __('Label'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							$risks = Hash::extract($item, 'ThirdPartyRisk.{n}.title');
							echo $this->Eramba->getEmptyValue(implode(', ', $risks));
							?>
						</td>
						<td>
							<?php
							echo $item['ComplianceFinding']['deadline'];
							?>
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
							<?php
							echo $this->ComplianceFindings->getStatuses($item['ComplianceFinding']);
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
							<?php
							echo $this->Eramba->getEmptyValue($item['ComplianceFinding']['description']);
							?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>