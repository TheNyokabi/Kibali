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
							echo $this->Html->link(__('BusinessContinuity'), array(
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
								$classificationssWorkflowSettingsId
							), array(
								'escape' => false
							));
							?>
						</li>
					</ul>
					*/ ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'BusinessContinuity'); ?>

				<div class="btn-group group-merge">
					<button class="btn dropdown-toggle" data-toggle="dropdown">
						<?php echo __('Settings'); ?> <span class="caret"></span>
					</button>
					<ul class="dropdown-menu pull-right">
						<li>
							<?php
							echo $this->Html->link(__('Treatment Options'), array(
								'controller' => 'customValidator',
								'action' => 'index',
								'plugin' => 'custom_validator',
								'BusinessContinuity'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Calculation Method'), array(
								'controller' => 'riskCalculations',
								'action' => 'edit',
								'BusinessContinuity'
							), array(
								'escape' => false,
								'data-ajax-action' => 'add'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Residual Risk'), array(
								'controller' => 'settings',
								'action' => 'residualRisk'
							), array(
								'escape' => false,
								'data-ajax-action' => 'add'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Classification'), array(
								'controller' => 'riskClassifications',
								'action' => 'index'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Risk Appetite'), array(
								'plugin' => false,
								'controller' => 'riskAppetites',
								'action' => 'edit',
								1
							), array(
								'escape' => false,
								'data-ajax-action' => 'add'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Threats'), array(
								'controller' => 'threats',
								'action' => 'index'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
						<li>
							<?php
							echo $this->Html->link(__('Vulnerabilities'), array(
								'controller' => 'vulnerabilities',
								'action' => 'index'
							), array(
								'escape' => false,
								'data-ajax-action' => 'index'
							));
							?>
						</li>
					</ul>
				</div>
				
				<?php echo $this->NotificationSystem->getIndexLink('BusinessContinuity'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'BusinessContinuity' => __('Business Continuity'),
				)); ?>

				<?php echo $this->Video->getVideoLink('BusinessContinuity'); ?>

				<?php if ( $view == 'listBusinessUnits' ) : ?>
					<?php //echo $this->element( CORE_ELEMENT_PATH . 'filter' , array('filterElement' => $this->element(FILTERS_ELEMENT_PATH . 'filter_business_continuties'))); ?>
				<?php elseif( $view == 'listBusinessContinuities' ): ?>
					<?php //echo $this->element( CORE_ELEMENT_PATH . 'filter' , array('filterElement' => $this->element(FILTERS_ELEMENT_PATH . 'filter_business_continuties_2'))); ?>
				<?php endif; ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
	</div>


</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'BusinessContinuity')); ?>

<div class="row">
	<div class="col-md-12">

		<?php
		if ( $view == 'listBusinessUnits' ) {
			echo $this->element( '../BusinessContinuities/view_businessunits', array(
				'data' => $data
			) );
		}
		elseif ( $view == 'listBusinessContinuities' ) {
			echo $this->element( '../BusinessContinuities/view_businesscontinuities', array(
				'data' => $data
			) );
		}
		?>

	</div>
</div>