<?php
App::uses('DashboardKpi', 'Dashboard.Model');
?>
<table class="table table-hover table-dashboard table-dashboard-admin">
	<thead>
		<tr>
			<th>
				<?php echo DashboardKpi::categories($category); ?>
			</th>
			<th>
				<?php echo __('Value'); ?>
			</th>
			<?php if ($category == DashboardKpi::CATEGORY_OWNER) : ?>
				<th>
					<?php
					echo __('Actions');
					?>
				</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<!-- blank line for .table-striped to look better -->
		<tr></tr>

		<?php foreach ($items as $item) : ?>

			<tr>
				<td>
					<?php
					echo $item['DashboardKpi']['title'];
					?>
				</td>

				<td>
					<?php
					echo $this->DashboardKpi->getKpiLink($model, $item);
					?>
				</td>
				<?php if ($category == DashboardKpi::CATEGORY_OWNER) : ?>
					<td class="align-center">
						<?php
						echo $this->Ajax->getActionList($item['DashboardKpi']['id'], array(
							'style' => 'icons',
							'trash' => true,
							'edit' => false,
							'comments' => false,
							'records' => false,
							'attachments' => false,
							'item' => $item
						));
						?>
					</td>
				<?php endif; ?>
			</tr>

		<?php endforeach; ?>

	</tbody>
</table>