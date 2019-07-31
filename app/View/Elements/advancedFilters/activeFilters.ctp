<?php
echo $this->element('Visualisation.widget_header');
?>

<?php
App::uses('Inflector', 'Utility');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');

$filterFields = array();
foreach ($filter['fields'] as $fieldSet) {
	$filterFields = array_merge($filterFields, $fieldSet);
}
// debug($filterFields);
$activeFilters = array();
// debug($this->request->data[$filter['model']]);
foreach ($this->request->data[$filter['model']] as $field => $value) {
	if (strpos($field, '__comp_type') === false && strpos($field, '__show') === false && $value != '' && !empty($filterFields[$field])) {
		$filterData = $filterFields[$field];
		$optionsVar = $field . '_data';
		$options = (!empty($$optionsVar)) ? $$optionsVar : null;

		if (!empty($filterData['filter']['method']) && $filterData['filter']['method'] == 'findComplexType') {
			$queryClass = Inflector::classify($filterData['type']) . 'Query';
        	App::uses($queryClass, 'Lib/AdvancedFilters/Query');

			$comparisonType = isset($this->request->data[$filter['model']][$field . '__comp_type']) ? $this->request->data[$filter['model']][$field . '__comp_type'] : $queryClass::getComparisonTypes(null, false)[0];
			$text = $filterFields[$field]['name'];
			$text .= ' ' . strtoupper(AbstractQuery::getComparisonLabel($comparisonType));

			if (!in_array($comparisonType, [AbstractQuery::COMPARISON_IS_NULL, AbstractQuery::COMPARISON_IS_NOT_NULL])) {
				$text .= ' ';
				if (is_array($value)) {
					foreach ($value as $key => $subValue) {
						if ($key > 0) {
							if (in_array($comparisonType, [AbstractQuery::COMPARISON_IN, AbstractQuery::COMPARISON_NOT_IN])) {
								$text .= sprintf(' %s ', __('OR'));
							}
							else {
								$text .= sprintf(' %s ', __('AND'));
							}
						}

						if (isset($options[$subValue])) {
							$text .= $options[$subValue];
						}
						else {
							$text .= 'N/A';
						}
					}
				}
				else {
					if (!empty($options)) {
						$text .= $options[$value];
					}
					elseif (in_array($value, array_keys(AbstractQuery::getSpecialValueLabel()))) {
						$text .= AbstractQuery::getSpecialValueLabel()[$value];
					}
					else {
						$text .= $value;
					}
				}
			}

			$activeFilters[] = $text;
			continue;
		}

		$text = $filterFields[$field]['name'] . ': ';
		if (is_array($value)) {
			foreach ($value as $key => $subValue) {
				if ($key > 0) {
					if ($filterData['type'] == 'multiple_select' && $filterData['filter']['type'] == 'subquery' && $filterData['filter']['method'] == 'findByHabtm' && empty($filterData['filter']['orCondition'])) {
						$text .= sprintf(' %s ', __('AND'));
					}
					else {
						$text .= sprintf(' %s ', __('OR'));
					}
					// $text .= ', ';
				}

				if (isset($options[$subValue])) {
					$text .= $options[$subValue];
				}
				else {
					$text .= 'N/A';
				}
			}
		}
		else {
			// special "None" multi-select option
			if ($value === ADVANCED_FILTER_MULTISELECT_NONE) {
				$text .= __('None');
			}
			elseif (!empty($options)) {
				$text .= $options[$value];
			}
			else {
				$text .= $value;
			}
		}

		$activeFilters[] = $text;
	}
	elseif (strpos($field, '__comp_type') !== false && in_array($value, [AbstractQuery::COMPARISON_IS_NULL, AbstractQuery::COMPARISON_IS_NOT_NULL])) {
		$fieldName = str_replace('__comp_type', '', $field);
		$filterFields[$fieldName]['name'];
		$activeFilters[] = $filterFields[$fieldName]['name'] . ' ' . strtoupper(AbstractQuery::getComparisonLabel($value));
	}
}
?>

<?php
$count = 0;
if (isset($pagingCount)) {
	$count = $pagingCount;
}
elseif (!empty($data)) {
	$count = count($data);
}
$resultsLabel = sprintf(__n('%d result', '%d results', $count), $count);


if (!empty($activeFilters)) : ?>
	<?php if (!empty($pdf)) : ?>
		<div class="row">
			<div class="col-xs-12">

				<div class="header">
					<div class="subtitle">
						<h2>
							<?php
							echo __('Active Filters (%s)', $resultsLabel);
							?>
						</h2>
					</div>
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">

				<div class="body">
					<div class="item custom-padding">
						<?php foreach ($activeFilters as $value): ?>
							<span class="label label-info label-filter"><?php echo $value; ?></span> 
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>

		<div class="header-separator"></div>
	<?php else : ?>
		<div class="widget box widget-active-filter">
			<div class="widget-header">
				<h4>
					<?php
					echo __('Active Filters (%s)', $resultsLabel);
					?>
				</h4>
			</div>
			<div class="widget-content">
				<div class="btn-toolbar">
					<?php foreach ($activeFilters as $value): ?>
						<span class="label label-info label-filter"><?php echo $value; ?></span> 
					<?php endforeach ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
<?php else : ?>
	<?php if (!empty($pdf)) : ?>
		<div class="row">
			<div class="col-xs-12">

				<div class="header">
					<div class="subtitle">
						<h2>
							<?php
							echo $resultsLabel;
							?>
						</h2>
					</div>
				</div>

			</div>
		</div>

		<div class="header-separator"></div>
	<?php else : ?>
		<div class="widget box widget-active-filter">
			<div class="widget-header">
				<h4>
					<?php
					echo $resultsLabel;
					?>
				</h4>
			</div>
		</div>
	<?php endif; ?>
<?php endif; ?>
