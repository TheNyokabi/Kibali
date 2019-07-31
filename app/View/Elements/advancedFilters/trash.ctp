<div class="btn-toolbar clearfix">
	<div class="btn-toolbar">
		<div class="btn-group">
			<?php
			$url = array(
				'action' => 'index',
				'?' => array(
					'advanced_filter' => 1
				)
			);
			if (!empty($filter['settings']['url'])) {
				$url = $filter['settings']['url'];
			}
			echo $this->Html->link(__('Back To Filter'), $url, array(
				'class' => 'btn btn-info',
				'escape' => false
			));
			?>
		</div>
	</div>
</div>

<br />

<?php //echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'activeFilters'); ?>

<?php if (!empty($data)) : ?>
	<?php
	// we get the field list that is shown on the index
	$currentFieldSet = array();
	foreach ($filter['fields'] as $fieldSet) {
		foreach ($fieldSet as $field => $fieldData) {
			if (!empty($this->request->data[$filter['model']][$field . '__show'])) {
				$currentFieldSet[$field] = $fieldData;
			}
		}
	}
	?>

	<div class="widget box advanced-filter-table-widget">
		<div class="widget-content no-padding">

			<table class="advanced-filter-table table table-hover table-striped table-bordered table-highlight-head table-checkable">
				<thead>
					<tr>
						<?php
						foreach ($currentFieldSet as $field => $fieldData) {
							$label = $fieldData['name'];

							$cond = empty($fieldData['contain']);
							$cond &= empty($fieldData['containable']);
							//@todo temporary solution for not allowing sorting for custom fields
							$cond &= empty($fieldData['filter']['customField']);

							if ($cond) {
								$label = $this->Paginator->sort($field, $fieldData['name']);
							}
							if (!empty($fieldData['order'])) {
								$label = $this->Paginator->sort($fieldData['order'], $fieldData['name']);
							}
							echo $this->Html->tag('th', $label);
						}
						?>
						<?php //if (!(isset($filter['settings']['actions']) && $filter['settings']['actions'] == false)) : ?>
							<th class="align-center"><?php echo __('Actions'); ?></th>
						<?php //endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$AdvancedFiltersData = new AdvancedFiltersData($this->viewVars, AdvancedFiltersData::VIEW_TYPE_HTML);
					
					foreach ($data as $item) : ?>
						<tr>
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
							<td class="align-center">
								<?php
								$defaults = array(
									'style' => 'icons',
									'notifications' => false,
									'history' => true,
									'edit' => false,
									'trash' => false,
									'comments' => false,
									'records' => false,
									'attachments' => false
								);

								if (strpos($filter['model'], 'Review') !== false) {
									$defaults['model'] = $filter['model'];
								}

								echo $this->Ajax->getActionList($item[$filter['model']]['id'], $defaults);
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

		</div>
	</div>

	

	<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination' ); ?>
<?php else : ?>
	<?php echo $this->element('not_found', array(
		'message' => __('No results found.')
	) ); ?>
<?php endif; ?>