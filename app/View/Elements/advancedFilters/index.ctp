<div class="btn-toolbar clearfix">
	<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerLeft'); ?>
	<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight', array('showReset' => true)); ?>
</div>
<?php
// debug($data);
// debug($filter);
// debug($this->request->data);
// debug($this->request->query);
// debug($tag_id_data);
?>

<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'activeFilters'); ?>

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
			<?php
			echo $this->element('BulkActions.before_table', array(
				'currentFieldSet' => $currentFieldSet
			));
			?>
			
			<div class="advanced-filter-table-wrapper">
				<table class="advanced-filter-table table table-hover table-striped table-bordered table-highlight-head table-checkable">
					<thead>
						<tr>
							<th class="checkbox-column">
								<?php
								echo $this->Form->input('BulkAction.apply_all', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform bulk-action-checkbox',
									'id' => 'check-all-checkbox'
								));
								?>
							</th>
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
							<?php if (!(isset($filter['settings']['actions']) && $filter['settings']['actions'] == false)) : ?>
								<th class="align-center actions-column"><?php echo __('Actions'); ?></th>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody>
						<?php
						$AdvancedFiltersData = new AdvancedFiltersData($this->viewVars, AdvancedFiltersData::VIEW_TYPE_HTML);
						
						foreach ($data as $item) : ?>
							<tr>
								<td class="checkbox-column">
									<?php
									echo $this->Form->input('BulkAction.apply_id][', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'class' => 'uniform bulk-action-checkbox',
										'value' => $item[$filter['model']]['id'],
										'hiddenField' => false
									));
									?>
								</td>
								<?php
								
								foreach ($filter['fields'] as $fieldSet) {
									$colCounter = 0;
									foreach ($fieldSet as $field => $fieldData) {
										$colCounter++;
										if (!empty($this->request->data[$filter['model']][$field . '__show'])) {
											echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'item', array(
												'item' => $item,
												'field' => $field,
												'fieldData' => $fieldData,
												'AdvancedFiltersData' => $AdvancedFiltersData,
												'colClass' => 'table-col-' . $colCounter
											));
										}
									}
								}
								?>
								<?php if (!(isset($filter['settings']['actions']) && $filter['settings']['actions'] == false)) : ?>
									<td class="align-center actions-column">
										<?php
										App::uses('AppIndexCrudAction', 'Controller/Crud/Action');

										$defaults = array(
											'style' => 'icons',
											'item' => $item
										);

										if (strpos($filter['model'], 'Review') !== false) {
											$defaults['model'] = $filter['model'];
										}

										$allowView = (isset($filter['settings']['view_item'])) ? $filter['settings']['view_item'] : true;
										if ($allowView) {
											if (isset($filter['settings']['view_item']['ajax_action'])) {
												$viewUrl = $filter['settings']['view_item']['ajax_action'];

												$viewUrl['?'] = array(
													'id' => $item[$filter['model']]['id']
												);

												$this->Ajax->addToActionList(__('View'), $viewUrl, 'search', 'index');
											}
											elseif ($allowView === AppIndexCrudAction::VIEW_ITEM_QUERY) {
												$viewUrl = array(
													'controller' => $this->request->params['controller'],
													'action' => 'index',
													'?' => array(
														AppIndexCrudAction::VIEW_ITEM_QUERY => $item[$filter['model']]['id']
													)
												);

												$this->Ajax->addToActionList(__('View'), $viewUrl, 'search', false);
											}
											else {
												$viewUrl = array(
													'controller' => $this->request->params['controller'],
													'action' => 'index',
													'?' => array(
														'id' => $item[$filter['model']]['id']
													)
												);

												$this->Ajax->addToActionList(__('View'), $viewUrl, 'search', false);
											}
										}

										if (isset($filter['settings']['history'])) {
											$modelConfig['history'] = $filter['settings']['history'];
										}

										if (isset($filter['settings']['trash'])) {
											$modelConfig['trash'] = $filter['settings']['trash'];
										}

										$config = array_merge($defaults, $modelConfig);
										echo $this->Ajax->getActionList($item[$filter['model']]['id'], $config);
										?>
									</td>
								<?php else : ?>
									<?php 
									// <td class="align-center actions-column">
									// 	-
									// </td>
									?>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?php
			echo $this->element('BulkActions.after_table');
			?>
		</div>
	</div>

	<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination' ); ?>
	<script type="text/javascript">
	$(function() {
		var maxColumnWidth = 250;
		if ($('.advanced-filter-table th').length > 10) {
			$('.advanced-filter-table-wrapper').addClass('table-responsive-advanced-filters');
		}
		$('.table-responsive-advanced-filters td').each(function() {
			if ($(this).width() > maxColumnWidth) {
				$('.' + $(this).attr('class')).css({
					width: maxColumnWidth + 'px',
					'white-space': 'normal'
				});
				$('.' + $(this).attr('class') + ' .td-inner').css({
					width: maxColumnWidth + 'px',
					display: 'block'
				});
			}
		});
	});
	</script>
<?php else : ?>
	<?php echo $this->element('not_found', array(
		'message' => __('No results found.')
	) ); ?>
<?php endif; ?>

<script type="text/javascript">
$(function() {
	if (window.location.hash == '#advanced-filter-edit') {
		$('#advanced-filter-modal').modal('show');
		$('#advanced-filter-nav-tabs .active').removeClass('active');
		$('#advanced-filter-tabs .active').removeClass('active');
		$('#advanced-filter-nav-tab-manage').addClass('active');
		$('#advanced-filter-tab-manage').removeClass('hidden');
		$('#advanced-filter-tab-manage').addClass('active');

		window.location.hash = "";
	}
});
</script>