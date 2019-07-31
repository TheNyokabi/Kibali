<!-- Breadcrumbs line -->
<div class="crumbs">
	<?php
	if (!empty($use_new_breadcrumbs)) {
		echo $this->element('toolbar/breadcrumbs_new', [
			'crumbs' => isset($use_new_breadcrumbs_data) ? $use_new_breadcrumbs_data : []
		]);
	}
	else {
		echo $this->element('toolbar/breadcrumbs');
	}
	?>

	<?php
	echo $this->Layout->getToolbarItemList([
		'class' => 'crumb-buttons toolbar-buttons'
	]);
	?>

	<!-- <ul class="crumb-buttons toolbar-buttons">
		<?php
		// echo $this->element('toolbar/dropdown');
		?>
	</ul> -->
</div>
<!-- /Breadcrumbs line -->