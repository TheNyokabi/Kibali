<?php
App::uses('DashboardKpi', 'Dashboard.Model');

$Model = ClassRegistry::init($model);
$headings = $AwarenessUserInstance->getLabel($Model);
$data = $data[$model][DashboardKpi::CATEGORY_AWARENESS];

?>
<table class="table table-hover table-dashboard">
	<thead>
		<tr>
			<th>
				<?php echo __('Awareness Programs'); ?>
			</th>

			<?php foreach ($headings as $userModel => $label) : ?>
				<th>
					<?php
					echo sprintf($label, $Model->label());
					?>
				</th>
			<?php endforeach; ?>
		</tr>
	</thead>
	<tbody>

	<?php foreach ($awarenessPrograms as $attribute => $title) : ?>
		<tr>
			<td>
				<?php
				echo $title;
				?>
			</td>

			<?php foreach ($headings as $userModel => $label) : ?>
				<?php
				$attrs = [
					'AwarenessProgramUserModel' => $userModel,
					'AwarenessProgram' => $attribute
				];
				
				$item = $this->DashboardKpi->searchKpiByAttributes($items, $attrs);
				$value = $item['DashboardKpi']['value'];
				?>
				<td>
					<?php
					if ($value !== null) {
						echo $this->DashboardKpi->getKpiLink($model, $item);
					}
					else {
						echo $this->DashboardKpi->noValueTooltip();
					}
					?>
				</td>
			<?php endforeach; ?>
		</tr>

	<?php endforeach; ?>
	</tbody>
</table>