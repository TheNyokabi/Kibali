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
							echo $this->Html->link(__('Risk'), array(
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

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'Risk'); ?>

				<div class="btn-group group-merge">
					<button class="btn dropdown-toggle" data-toggle="dropdown"><?php echo __('Settings'); ?> <span class="caret"></span></button>
					<ul class="dropdown-menu pull-right">
						<li>
							<?php
							echo $this->Html->link(__('Treatment Options'), array(
								'controller' => 'customValidator',
								'action' => 'index',
								'plugin' => 'custom_validator',
								'Risk'
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
								'Risk'
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
				
				<?php echo $this->NotificationSystem->getIndexLink('Risk'); ?>

				<?php echo $this->Video->getVideoLink('Risk'); ?>

				<?php echo $this->CustomFields->getIndexLink(array(
					'Risk' => __('Asset Risk'),
				)); ?>

				<?php
				// new toolbar buttons
				// echo $this->Html->div(
				// 	'btn-group',
				// 	$this->Modules->getToolbar('Risk')
				// );
				?>

				<?php
				// if ($view == 'listRisks') {
				// 	echo $this->element( CORE_ELEMENT_PATH . 'filter' , array('filterElement' => $this->element(FILTERS_ELEMENT_PATH . 'filter_risk')));
				// }
				?>
				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'Risk')); ?>

<div class="row">
	<div class="col-md-12">

		<?php
		if ( $view == 'listAssets' ) {
			echo $this->element( '../Risks/view_assets', array(
				'data' => $data
			) );
		}
		elseif ( $view == 'listRisks' ) {
			echo $this->element( '../Risks/view_risks', array(
				'data' => $data
			) );
		}
		?>

	</div>
</div>