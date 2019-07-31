<?php
echo $this->element('Visualisation.widget_header');
?>

<?php
	$activeFilters = array();

	if(!empty($this->request->data[$filterName])){
		foreach ($this->request->data[$filterName] as $key => $value) {
			if(trim($value) != ''){
				$activeFilters[] = $filterArgs[$key]['_name'];
			}
		}
	}
?>

<?php if(!empty($activeFilters)):?>

<div class="widget box widget-active-filter">
	<div class="widget-header">
		<h4><?php echo __('Active Filters'); ?></h4>
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