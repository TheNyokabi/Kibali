<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo $filter['settings']['pdf_title']; ?>
				</h1>
			</div>
		</div>

	</div>
</div>

<?php
echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'activeFilters', array(
	'pdf' => true
));
?>

<?php if (!empty($data)): ?>

	<div class="row">
		<div class="col-xs-12">
			<div class="body">

				<div class="item">
					<table class="table-pdf table-pdf-system-records" style="">
						<thead>
							<tr>
								<?php if (!empty($data[0]['__cron_date'])) : ?>
									<th><?php echo __('Date'); ?></th>
								<?php endif; ?>
								<?php
								foreach ($filter['fields'] as $fieldSet) {
									foreach ($fieldSet as $field => $fieldData) {
										if (!empty($this->request->data[$filter['model']][$field . '__show'])) {
											echo $this->Html->tag('th', $fieldData['name']);
										}
									}
								}
								?>
							</tr>
						</thead>
						
						<tbody>
							<?php
							$AdvancedFiltersData = new AdvancedFiltersData($this->viewVars, AdvancedFiltersData::VIEW_TYPE_HTML);

							foreach ($data as $item) : ?>
								<tr>
									<?php if (!empty($item['__cron_date'])) : ?>
										<td><?php echo $item['__cron_date']; ?></td>
									<?php endif; ?>
									<?php
									foreach ($filter['fields'] as $fieldSet) {
										foreach ($fieldSet as $field => $fieldData) {
											if (!empty($this->request->data[$filter['model']][$field . '__show'])) {
												echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'item', array(
													'item' => $item,
													'field' => $field,
													'fieldData' => $fieldData,
													'AdvancedFiltersData' => $AdvancedFiltersData
												));
											}
										}
									}
									?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
<?php else : ?>
	<?php
	echo $this->element('not_found', array(
		'message' => __('No items found.')
	));
	?>
<?php endif; ?>
