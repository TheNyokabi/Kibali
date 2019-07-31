 <div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Third Party Audit'); ?>
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
							<?php echo __('Start Date'); ?>
						</th>
						<th>
							<?php echo __('End Date'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['ComplianceAudit']['name']; ?>
						</td>
						<td>
							<?php echo $item['ComplianceAudit']['start_date']; ?>
						</td>
						<td>
							<?php echo $item['ComplianceAudit']['end_date']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table class="triple-column" style="table-layout:fixed;">
					<tr>
						<th>
							<?php echo __('Auditor'); ?>
						</th>
						<th>
							<?php echo __('Third Party Contact'); ?>
						</th>
						<th>
							<?php echo __('Compliance Package'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							echo $this->Eramba->getEmptyValue($item['Auditor']['full_name']);
							?>
						</td>
						<td>
							<?php
							echo $this->Eramba->getEmptyValue($item['ThirdPartyContact']['full_name']);
							?>
						</td>
						<td>
							<?php echo $item['ThirdParty']['name']; ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
$auditInfo = getComplianceAuditCalculatedData($item);
?>

<div class="row">
	<div class="col-xs-12">

		<div class="header-separator"></div>
		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Evidence Stats'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<table class="quadruple-column uneven">
					<tr>
						<th>
							<?php echo __('Waiting for Evidence'); ?>
						</th>
						<th>
							<?php echo __('Evidence Provided'); ?>
						</th>
						<th>
							<?php echo __('No Evidence Needed'); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php echo $auditInfo['waitingEvidencePercentage'] . ' (' . $auditInfo['waitingForEvidence'] . ')'; ?>
						</td>
                        <td>
                        	<?php echo $auditInfo['providedEvidencePercentage'] . ' (' . $auditInfo['evidenceProvided'] . ')'; ?>
                    	</td>
                        <td>
                        	<?php echo $auditInfo['noEvidencePrecentage'] . ' (' . $auditInfo['noEvidenceNeeded'] . ')'; ?>
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
					<?php echo __('Evidence Stats'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<table class="quadruple-column uneven">
					<tr>
						<th>
							<?php echo __('Findings'); ?>
						</th>
						<th>
							<?php echo __('Expired Findings'); ?>
						</th>
						<th>
							<?php echo __('Assessed Items'); ?>
						</th>
					</tr>
					<tr>
						<td>
							<?php echo __n( '%d Item', '%d Items', $auditInfo['findings_count'], $auditInfo['findings_count'] ); ?>
						</td>
                        <td>
                        	<?php echo $auditInfo['expired_percentage'] . ' (' . $auditInfo['expired_count'] . ')'; ?>
                    	</td>
                        <td>
                            <?php echo __n('%d Item', '%d Items', $auditInfo['assesed_count'], $auditInfo['assesed_count']) . ' (' . $auditInfo['assessed_percentage'] .')'; ?>
                        </td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php if (!empty($auditInfo['auditeeFeedbacks'])) : ?>
    <div class="row">
		<div class="col-xs-12">

			<div class="header-separator"></div>
			<div class="header">
				<div class="subtitle">
					<h2>
						<?php echo __('Answers Stats'); ?>
					</h2>
				</div>
			</div>

		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="body">
				<div class="item">
					<table class="quadruple-column uneven">
						<tr>
	                    	<?php foreach ($auditInfo['auditeeFeedbacks'] as $feedbackItem) : ?>
	                    		<th><?php echo $feedbackItem['name']; ?></th>
	                    	<?php endforeach; ?>
	                    </tr>
						<tr>
	                    	<?php foreach ($auditInfo['auditeeFeedbacks'] as $feedbackItem) : ?>
	                    		<td>
	                    			<?php echo $feedbackItem['percentage']; ?> 
									<?php echo __n('(%d Item)', '(%d Items)', $feedbackItem['count'], $feedbackItem['count']); ?>
	                    		</td>
	                    	<?php endforeach; ?>
	                    </tr>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<?php echo $this->element('pdf_common_data'); ?>
