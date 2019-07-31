<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Program Team'); ?>
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
							<?php echo __('Role'); ?>
						</th>
						<th>
							<?php echo __('Status'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php echo $item['User']['full_name']; ?>
						</td>
						<td>
							<?php echo $item['TeamRole']['role']; ?>
						</td>
						<td>
							<?php echo getTeamRoleStatuses($item['TeamRole']['status']); ?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Competences'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							if (!empty($item['TeamRole']['competences'])) {
								echo nl2br($item['TeamRole']['competences']);
							}
							else {
								echo '-';
							}
							?>
						</td>
					</tr>
				</table>
			</div>

			<div class="item">
				<table>
					<tr>
						<th>
							<?php echo __('Responsibilities'); ?>
						</th>
					</tr>
					
					<tr>
						<td>
							<?php
							if (!empty($item['TeamRole']['responsibilities'])) {
								echo nl2br($item['TeamRole']['responsibilities']);
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