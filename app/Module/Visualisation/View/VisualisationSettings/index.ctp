<div class="widget">
	<div class="btn-toolbar">
		<div class="btn-group">
			<?php
			echo $this->Html->link('<i class="icon icon-info-sign"></i> ' . __('Synchronize'), [
				'plugin' => 'visualisation',
				'controller' => 'visualisationSettings',
				'action' => 'sync'
			], array(
				'class' => 'btn bs-popover',
				'data-container' => 'body',
				'data-trigger' => 'hover',
				'data-placement' => 'right',
				'data-original-title' => __('IMPORTANT'),
				'data-content' => __('Unless requested by support is not necessary to sync visualisations and therefore you should not have the need to click here.'),
				'escape' => false
			));

			?>
		</div>
	</div>
</div>

<div class="widget">
	<table class="table table-hover table-striped table-bordered table-highlight-head">
		<thead>
			<tr>
				<th>
					<?php echo $this->Paginator->sort('VisualisationSetting.model', __('Section')); ?>
				</th>
				<th>
					<?php
					echo __('Exempted Users');
					?>
				</th>
				<th>
					<?php
					echo __('Status');
					?>
				</th>
				<th>
					<?php
					echo __('Action');
					?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($data as $entry) : ?>
				<tr>
					<td>
						<?php
						$Model = $entry['VisualisationSetting']['model'];
						App::uses($Model, 'Model');
						$Class = ClassRegistry::init($Model);

						echo $Class->groupLabel();
						?>
					</td>
					<td>
						<?= $this->UserField->showUserFieldRecords($entry['ExemptedUser']); ?>
					</td>
					<td>
						<?php
						echo call_user_func_array("VisualisationSetting::statuses", [$entry['VisualisationSetting']['status']]);
						?>
					</td>
					<td>
						<?php
						echo $this->Visualisation->getSectionLink($entry['VisualisationSetting']['model']);
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>