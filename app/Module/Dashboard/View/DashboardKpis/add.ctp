<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'DashboardKpi', array(
							'url' => array('plugin' => 'dashboard', 'controller' => 'dashboard_kpis', 'action' => 'edit'),
							'class' => 'form-horizontal row-border',
							'novalidate' => 'novalidate',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'DashboardKpi', array(
							'url' => array('plugin' => 'dashboard', 'controller' => 'dashboard_kpis', 'action' => 'add', $dashboardKpiType),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<?php
								echo $this->FieldData->inputs([
									$FieldDataCollection->title,
								]);

								echo $this->Form->input('DashboardKpi.type', [
									'type' => 'hidden',
									'value' => $dashboardKpiType
								]);

								App::uses('DashboardKpi', 'Dashboard.Model');
								echo $this->Form->input('DashboardKpi.category', [
									'type' => 'hidden',
									'value' => DashboardKpi::CATEGORY_OWNER
								]);

								echo $this->FieldData->input($Attribute->foreign_key, [
									'inputName' => 'DashboardKpiAttribute.0.foreign_key',
								]);

								echo $this->FieldData->input($Attribute->model, [
									'inputName' => 'DashboardKpiAttribute.0.model',
									'type' => 'hidden',
									'value' => 'AdvancedFilter'
								]);

								if ($this->Form->isFieldError('attributes')) {
									echo $this->Html->div('form-group form-group-first', $this->Html->div('col-md-10 col-md-offset-2', $this->Form->error('attributes')));
								}
								?>
						</div>
					</div>

					<div class="form-actions">
						<?php echo $this->Form->submit( $submit_label, array(
							'class' => 'btn btn-primary',
							'div' => false
						) ); ?>
						&nbsp;
						<?php
						echo $this->Ajax->cancelBtn('DashboardKpi');
						?>
					</div>

					<?php echo $this->Form->end(); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>