<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>
					<?php /*
					<button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-cog"></i><?php echo __('Workflow'); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right" style="text-align: left;">
						<li>
							<?php
							echo $this->Html->link(__('Asset'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$workflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Reviews'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$reviewsWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Classification'), array(
								'controller' => 'workflows',
								'action' => 'edit',
								$classificationsWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'Asset'); ?>

				<div class="btn-group group-merge">
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<?php echo __('Settings'); ?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-right" style="text-align: left;">
						<li>
							<?php
							echo $this->Html->link(__('Classification'), array(
								'controller' => 'assetClassifications',
								'action' => 'index'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Labels'), array(
								'controller' => 'assetLabels',
								'action' => 'index'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Asset Types'), array(
								'controller' => 'assetMediaTypes',
								'action' => 'index'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
					</ul>
				</div>

				<?php echo $this->ImportTool->getIndexLink('Asset'); ?>

				<?php echo $this->NotificationSystem->getIndexLink('Asset'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'Asset' => __('Asset'),
				)); ?>

				<?php echo $this->Video->getVideoLink('Asset'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
	</div>

</div>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'Asset')); ?>

<div class="row">
	<div class="col-md-12">

		<?php if (!empty($data)) : ?>
			<?php foreach ($data as $asset) : ?>

				<?php
				echo $this->element('../Assets/view_item', array(
					'asset' => $asset
				));
				?>

			<?php endforeach; ?>

			<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination'); ?>

		<?php else : ?>

			<?php
			echo $this->element('not_found', array(
				'message' => __('No Assets found.')
			));
			?>

		<?php endif; ?>

	</div>
</div>
