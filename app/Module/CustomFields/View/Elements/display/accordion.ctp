<?php if (!empty($customFields_available)) : ?>

	<?php foreach ($customFields_data as $group) : ?>
		<?php if (empty($group['CustomField'])) continue; ?>
		
		<?php if (!empty($layout) && $layout == 'pdf') : ?>
			<div class="row">
				<div class="col-xs-12">

					<div class="header">
						<div class="subtitle">
							<h2>
								<?php echo $group['CustomForm']['name']; ?>
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
									<?php foreach ($group['CustomField'] as $field) : ?>
										<th>
											<?php echo $field['name']; ?>
										</th>
									<?php endforeach; ?>
								</tr>
								
								<tr>
									<?php foreach ($group['CustomField'] as $field) : ?>
										<?php
										$default = $this->CustomFields->getItemValue($item, $field);
										?>
										<td>
											<?php echo $default; ?>
										</td>
									<?php endforeach; ?>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		<?php elseif (!empty($layout) && $layout == 'pdf_policy') : ?>
			<div class="row">
				<div class="col-sm-4">
					<div class="modal-padding">
						<?php foreach ($group['CustomField'] as $field) : ?>
							<h3>
								<?php echo $field['name']; ?>
							</h3>
							<?php
							$default = $this->CustomFields->getItemValue($item, $field);
							?>
							<div>
								<?php echo $default; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		<?php else : ?>
			<div class="widget box widget-closed">
				<div class="widget-header">
					<h4><?php echo $group['CustomForm']['name']; ?></h4>
					<div class="toolbar no-padding">
						<div class="btn-group">
							<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
						</div>
					</div>
				</div>
				<div class="widget-content" style="display:none;">
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<?php foreach ($group['CustomField'] as $field) : ?>
									<th>
										<?php echo $field['name']; ?>
									</th>
								<?php endforeach; ?>
							</tr>
						</thead>
						<tbody>
							<tr>

								<?php foreach ($group['CustomField'] as $field) : ?>
									<?php
									$default = $this->CustomFields->getItemValue($item, $field);
									// $default = '-';
									// if (!empty($item['CustomFieldValue'])) {
									// 	foreach ($item['CustomFieldValue'] as $v) {
									// 		if ($v['custom_field_id'] == $field['id']) {
									// 			$default = $v['value'];
									// 		}
									// 	}
									// }
									?>
									<td>
										<?php echo $default; ?>
									</td>
								<?php endforeach; ?>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
		
	<?php endforeach; ?>

<?php endif; ?>

