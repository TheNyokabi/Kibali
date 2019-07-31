<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Program Issues'); ?>
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
							<?php echo __('Name'); ?>
						</th>
						<th>
							<?php echo __('Source'); ?>
						</th>
						<th>
							<?php echo __('Type'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['ProgramIssue']['name']; ?>
						</td>
						<td>
							<?php echo getProgramIssueSources($item['ProgramIssue']['issue_source']); ?>
						</td>
						<td>
							<?php echo $this->ProgramIssues->getItemTypes($item); ?>
						</td>
						<td>
							<?php echo getProgramIssueStatuses($item['ProgramIssue']['status']); ?>
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
							if (!empty($item['ProgramIssue']['description'])) {
								echo nl2br($item['ProgramIssue']['description']);
							}
							else {
								echo '-';
							}
							?>
						</td>
					</tr>
				</table>
			</div>

		</div>

	</div>
</div>

<?php echo $this->element('pdf_common_data'); ?>