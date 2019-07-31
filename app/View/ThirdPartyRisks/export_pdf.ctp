<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Third Party risk'); ?>
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
							<?php echo $item['ThirdPartyRisk']['title']; ?>
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
							<?php echo $item['ThirdPartyRisk']['description']; ?>
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
							<?php echo $this->Risks->getStatuses($item, 'ThirdPartyRisk', true); ?>
						</td>
						</td>
						<td>
							<?php echo $item['ThirdPartyRisk']['review']; ?>
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
							<?php echo __('Residual Risk score'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							echo $this->element('risks/calculations/risk_score', array(
								'model' => 'ThirdPartyRisk',
								'risk' => $item
							));
							?>
						</td>
						<td>
							<?php
							echo $this->element('risks/calculations/residual_score', array(
								'model' => 'ThirdPartyRisk',
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

			<!-- <div class="separator-without-border"></div> -->

		</div>

	</div>
</div>



<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Assets being Risk Analysed'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<table class="triple-column">
					<tr>
						<th>
							<?php echo __('Asset name'); ?>
						</th>
						<th>
							<?php echo __('Description'); ?>
						</th>
						<th>
							<?php echo __('Label'); ?>
						</th>
						<th>
							<?php echo __('Liabilities'); ?>
						</th>
					</tr>
					
					<?php foreach ( $item['Asset'] as $asset ) : ?>

						<tr>
							<td>
								<?php echo $asset['name']; ?>
							</td>
							<td>
								<?php echo nl2br($asset['description']); ?>
							</td>
							<td>
								<?php echo ! empty( $asset['AssetLabel'] ) ? $asset['AssetLabel']['name'] : ''; ?>
							</td>
							<td>
								<?php
								$legals = array();
								foreach ($asset['Legal'] as $legal) {
									$legals[] = $legal['name'];
								}
								echo implode(', ', $legals);
								?>
							</td>
						</tr>

					<?php endforeach; ?>
				</table>
			</div>
		</div>

	</div>
</div>

<!-- Third Parties -->
<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Third Parties'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">

		<div class="body">
			<div class="item">
				<?php if (!empty($item['ThirdParty'])) : ?>
					<table class="triple-column">
						<tr>
							<th>
								<?php echo __('Name'); ?>
							</th>
							<th>
								<?php echo __('Description'); ?>
							</th>
							<th>
								<?php echo __('Liabilities'); ?>
							</th>
						</tr>
						

						<tbody>
							<?php foreach ( $item['ThirdParty'] as $tp ) : ?>
								<?php
								$legals = array();
								foreach ($tp['Legal'] as $legal) {
									$legals[] = $legal['name'];
								}
								?>
								<tr>
									<td><?php echo $tp['name']; ?></td>
									<td><?php echo nl2br($tp['description']); ?></td>
									<td><?php echo implode(', ', $legals); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>

					</table>
				<?php else : ?>
					<?php
					echo $this->Html->div('alert', 
						__('No Third Parties found.')
					);
					?>
				<?php endif; ?>
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
					<?php echo __('Risk Assesment &amp; Classification'); ?>
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
							<?php echo __('Threat tags'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php foreach ( $item['Threat'] as $threat ) : ?>

								<?php echo $threat['name']; ?>

							<?php endforeach; ?>
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
							<?php echo nl2br($item['ThirdPartyRisk']['threats']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Vulnerability tags'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php foreach ( $item['Vulnerability'] as $vulnerability ) : ?>
							
								<?php echo $vulnerability['name']; ?>
							
							<?php endforeach; ?>
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
							<?php echo nl2br($item['ThirdPartyRisk']['vulnerabilities']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="separator"></div>
			
		</div>
	</div>
</div>

<?php echo $this->element('pdf/riskClassification'); ?>

<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Risk Assesment &amp; Classification'); ?>
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
							<?php echo __('Type'); ?>
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

<?php echo $this->element('pdf_reviews_data'); ?>
<?php echo $this->element('pdf_common_data'); ?>
