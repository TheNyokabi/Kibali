
<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo $title_for_layout; ?>
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
							<?php echo __('Finding Title'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['ComplianceAnalysisFindings']['title']; ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Owners'); ?>
						</th>
						<th>
							<?php echo __('Collaborators'); ?>
						</th>
						<th>
							<?php echo __('Tags'); ?>
						</th>
						<th>
							<?php echo __('Due Date'); ?>
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
							<?= $this->UserField->showUserFieldRecords($item['Collaborator']); ?>
						</td>
						<td>
							<?php
							echo $this->ComplianceAnalysisFindings->getTags($item);
							?>
						</td>
						<td>
							<?php
							echo $this->ComplianceAnalysisFindings->getDueDate($item);
							?>
						</td>
						<td>
							<?php
							echo $this->ComplianceAnalysisFindings->getStatuses($item);
							?>
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
							<?php
							echo $this->ComplianceAnalysisFindings->getDescription($item);
							?>
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
					<?php echo __('Associated Compliance Package Items'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<?php
			$assocData = $this->ComplianceAnalysisFindings->getAssociatedData($item);

			echo $this->element('pdf/table', array(
				'tableData' => $assocData,
				'notFound' => __('No Compliance items has been linked to this finding.')
			));
			?>
		</div>
	</div>
</div>

<?php echo $this->element('pdf_common_data'); ?>

