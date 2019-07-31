<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Business based risk'); ?>
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
							<?php echo __('Risk name'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['BusinessContinuity']['title']; ?>
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
							<?php echo $item['BusinessContinuity']['description']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table class="double-column">
					<tr>
						<th>
							<?php echo __('Status'); ?>
						</th>
						<th>
							<?php echo __('Next review'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $this->Risks->getStatuses($item, 'BusinessContinuity', true); ?>
						</td>
						<td>
							<?php echo $item['BusinessContinuity']['review']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table class="double-column">
					<tr>
						<th>
							<?php echo __('Risk score'); ?>
						</th>
						<th>
							<?php echo __('Residual risk score'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							echo $this->element('risks/calculations/risk_score', array(
								'model' => 'BusinessContinuity',
								'risk' => $item
							));
							?>
						</td>
						<td>
							<?php
							echo $this->element('risks/calculations/residual_score', array(
								'model' => 'BusinessContinuity',
								'risk' => $item
							));
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table class="double-column">
					<tr>
						<th>
							<?php echo __('Owner'); ?>
						</th>
						<th>
							<?php echo __('Stakeholder'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Owner']); ?>
						</td>
						<td>
							<?= $this->UserField->showUserFieldRecords($item['Stakeholder']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<?php if(!empty($item['Process'])):?>
				<?php
				$rpd = 0;
				$mto = 0;
				$rto = $item['Process'][0]['rto'];
				foreach ($item['Process'] as $key => $value) {
					$rpd+=$value['rpd'];
					$mto = ($mto<$value['rpo'])?$value['rpo']:$mto;
					$rto = ($rto>$value['rto'])?$value['rto']:$rto;
				}?>
			<?php endif; ?>
			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Revenue per day'); ?>
						</th>
						<th>
							<?php echo __('Maximum mto'); ?>
						</th>
						<th>
							<?php echo __('Minimum rto'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo isset($rpd)?$rpd:'-'; ?>
						</td>
						<td>
							<?php echo isset($mto)?$mto:'-'; ?>
						</td>
						<td>
							<?php echo isset($rto)?$rto:'-'; ?>
						</td>
					</tr>
				</table>
			</div>
		</div>

	</div>
</div>

<div class="separator"></div>
<?php echo $this->element('pdf/riskClassification'); ?>

<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Involved Processes'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table class="quintuple-column-uneven">

					<?php if (!empty($item['Process'])) : ?>
						<tr>
							<th>
								<?php echo __('Bu name'); ?>
							</th>
							<th>
								<?php echo __('Process name'); ?>
							</th>
							<th>
								<?php echo __('Mto'); ?>
							</th>
							<th>
								<?php echo __('Rto'); ?>
							</th>
							<th>
								<?php echo __('Revenue per day'); ?>
							</th>
						</tr>
						
						<?php foreach ($item['Process'] as $process) : ?>
							<tr>
								<td>
									<?php echo $process['BusinessUnit']['name']; ?>
								</td>
								<td>
									<?php echo $process['name']; ?>
								</td>
								<td>
									<?php echo __n('%d Hour', '%d Hours', $process['rto'], $process['rto']); ?>
								</td>
								<td>
									<?php echo __n('%d Hour', '%d Hours', $process['rpo'], $process['rpo']); ?>
								</td>
								<td>
									<?php echo CakeNumber::currency($process['rpd']); ?>
								</td>
							</tr>
						<?php endforeach; ?>

					<?php else : ?>

						<?php echo $this->element('not_found', array(
							'message' => __('No Processes found.')
						)); ?>

					<?php endif; ?>
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
					<?php echo __('Risk Treatment'); ?>
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
							<?php echo __('Mitigation strategy'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['RiskMitigationStrategy']['name']; ?>
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
					<?php echo __('Risk Treatment - Controls'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table class="triple-column special-borders">
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

					<?php foreach ( $item['SecurityService'] as $security_service ) : ?>
					<tr>
						<td><?php echo $security_service['name']; ?></td>
					
						<td>
							<?php echo $security_service['SecurityServiceType']['name']; ?>
						</td>

						<td>
							<?php
							echo $this->SecurityServices->getStatuses($security_service);
							?>
						</td>
					</tr>
					<?php endforeach; ?>
					
				</table>
			</div>

		</div>

	</div>
</div>

<?php
echo $this->element('pdf/risks/security_policies', array(
	'data' => $item['SecurityPolicyIncident'],
	'widgetTitle' => __('Risk Incident - Policies')
));
?>

<?php
echo $this->element('pdf/risks/security_policies', array(
	'data' => $item['SecurityPolicyTreatment'],
	'widgetTitle' => __('Risk Treatment - Policies')
));
?>

<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Risk Treatment - Exceptions'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table class="quadruple-column">
					<tr>
						<th>
							<?php echo __('Title'); ?>
						</th>
						<th>
							<?php echo __('Expiration'); ?>
						</th>
						<th>
							<?php echo __('Requester'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<?php foreach ( $item['RiskException'] as $risk_exception ) : ?>
						<tr>
							<td>
								<?php echo $risk_exception['title']; ?>
							</td>
							<td>
								<?php echo $risk_exception['expiration']; ?>
							</td>
							<td>
								<?= $this->UserField->convertAndShowUserFieldRecords('RiskException', 'Requester', $risk_exception); ?>
							</td>
							<td>
								<?php
								echo $this->RiskExceptions->getStatuses($risk_exception, false);
								?>
							</td>
						</tr>
					<?php endforeach; ?>

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
					<?php echo __('Risk Treatment - Plans &amp; Improvements'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<?php if ( ! empty($item['Project'])) : ?>

				<?php foreach ($item['Project'] as $project) : ?>
					<div class="item">
						<table class="triple-column uneven">
							<tr>
								<th>
									<?php echo __('Title'); ?>
								</th>
								<th>
									<?php echo __('Deadline'); ?>
								</th>
								<th>
									<?php echo __('Goal'); ?>
								</th>
							</tr>
							
							<tr>
								<td><?php echo $project['title']; ?></td>
								<td><?php echo $project['deadline']; ?></td>
								<td><?php echo nl2br($project['goal']); ?></td>
							</tr>
						</table>
					</div>

					<div class="separator"></div>
				<?php endforeach ; ?>

			<?php else : ?>
				<div class="item">
					<?php
					echo $this->Html->div('alert', 
						__('No projects found.')
					);
					?>
				</div>
			<?php endif; ?>

		</div>

	</div>
</div>



<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Risk Reviews'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<?php if ( ! empty($item['Review'])) : ?>

				<?php foreach ($item['Review'] as $review) : ?>
					<div class="item">
						<table class="quadruple-column uneven">
							<tr>
								<th>
									<?php echo __('Planned date'); ?>
								</th>
								<th>
									<?php echo __('Actual date'); ?>
								</th>
								<th>
									<?php echo __('Reviewer'); ?>
								</th>
								<th>
									<?php echo __('Description'); ?>
								</th>
							</tr>
							
							<tr>
								<td><?php echo $review['planned_date']; ?></td>
								<td><?php echo $review['actual_date']; ?></td>
								<td><?php echo !empty($review['User']) ? $review['User']['full_name'] : '-'; ?></td>
								<td><?php echo $review['description']; ?></td>
							</tr>
						</table>
					</div>

					<div class="separator"></div>
				<?php endforeach ; ?>

			<?php else : ?>
				<div class="item">
					<?php
					echo $this->Html->div('alert', 
						__('No reviews found.')
					);
					?>
				</div>
			<?php endif; ?>
		</div>

	</div>
</div>

<?php echo $this->element('pdf_common_data'); ?>



