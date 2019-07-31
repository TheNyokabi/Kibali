<div class="widget pull-left">
	<div class="btn-toolbar">
		<div class="btn-group">
			<div class="btn-group group-merge">
				<?php 
				if (!empty($filter['settings']['add'])) {
					echo $this->Ajax->addAction();
				}
				if (!empty($filter['settings']['add-no-ajax'])) {
					echo $this->Ajax->addActionNoAjax();
				}
				?>
				<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __( 'Actions' ); ?> <span class="caret"></span></button>
				<ul class="dropdown-menu" style="text-align: left;">
					<li><a href="<?php echo Router::url(array('plugin' => 'advanced_filters', 'controller' => 'advancedFilters', 'action' => 'exportAdvancedFilterToCsv', $filter['model'], '?' => $this->request->query)); ?>"><i class="icon-file"></i> <?php echo __( 'Export CSV' ); ?></a></li>
					<li>
						<a href="<?php echo Router::url(array('plugin' => 'advanced_filters', 'controller' => 'advancedFilters', 'action' => 'exportAdvancedFilterToPdf', $filter['model'], '?' => $this->request->query)); ?>">
							<i class="icon-file"></i> <?php echo __('Export PDF'); ?>
						</a>
					</li>
					<?php if (!empty($activeFilter) && $activeFilter['AdvancedFilter']['log_result_count'] == ADVANCED_FILTER_LOG_ACTIVE) : ?>
						<li>
							<a href="<?php echo Router::url(array('plugin' => 'advanced_filters', 'controller' => 'advancedFilters', 'action' => 'exportDailyCountResults', $activeFilter['AdvancedFilter']['id'])); ?>">
								<i class="icon-file"></i> <?php echo __('CSV with daily count results'); ?>
							</a>
						</li>
					<?php endif; ?>
					<?php if (!empty($activeFilter) && $activeFilter['AdvancedFilter']['log_result_data'] == ADVANCED_FILTER_LOG_ACTIVE) : ?>
						<li>
							<a href="<?php echo Router::url(array('plugin' => 'advanced_filters', 'controller' => 'advancedFilters', 'action' => 'exportDailyDataResults', $activeFilter['AdvancedFilter']['id'])); ?>">
								<i class="icon-file"></i> <?php echo __('CSV with daily full filter results'); ?>
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>

		<?php
		echo $this->AdvancedFilters->getViewList($savedFilters, $filter['model']);
		?>

		<?php
		// temporarily test for dispatcher on Queue feature
		if ($filter['model'] == 'Queue') {
			echo $this->SectionDispatch->toolbar($filter['model'], []);
		}
		?>
	</div>
</div>