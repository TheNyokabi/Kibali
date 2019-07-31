<div class="widget-header">
	<h4>
		<i class="icon-bar-chart"></i>
		<?php echo $this->DashboardKpi->displayModelLabel($model); ?>
	</h4>
</div>

<?php
echo $this->element('Dashboard.category_listing', [
	'categories' => $categories,
	'model' => $model,
	'type' => 'admin'
]);
?>

<?php
// echo $this->element('Dashboard.custom_kpis', [
// 	'model' => $model,
// 	'items' => $items,
// 	'type' => 'admin'
// ]);
?>
