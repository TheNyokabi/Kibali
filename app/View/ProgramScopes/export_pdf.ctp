<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Program Scope'); ?>
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
							<?php echo __('Version'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['ProgramScope']['version']; ?>
						</td>
						<td>
							<?php echo getProgramScopeStatuses($item['ProgramScope']['status']); ?>
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
							if (!empty($item['ProgramScope']['description'])) {
								echo nl2br($item['ProgramScope']['description']);
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