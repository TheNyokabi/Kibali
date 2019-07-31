<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">
				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'SecurityServiceMaintenance', array(
							'url' => array( 'controller' => 'securityServiceMaintenances', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border'
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'SecurityServiceMaintenance', array(
							'url' => array( 'controller' => 'securityServiceMaintenances', 'action' => 'add', $securityService['SecurityService']['id'] ),
							'class' => 'form-horizontal row-border'
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a>
						</li>

						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<?php
							echo $this->FieldData->inputs([
								$FieldDataCollection->planned_date,
							]);

							echo $this->FieldData->input($FieldDataCollection->task, [
								'default' => (!empty($securityService['SecurityService']['maintenance_metric_description'])) ? $securityService['SecurityService']['maintenance_metric_description'] : '',
							]);

							echo $this->FieldData->input($FieldDataCollection->MaintenanceOwner, [
								'default' => $this->UserField->setDefaultValues('MaintenanceOwner', isset($securityService) ? $securityService : [], true)
							]);

							echo $this->FieldData->inputs([
								$FieldDataCollection->task_conclusion,
								$FieldDataCollection->start_date,
								$FieldDataCollection->end_date,
								$FieldDataCollection->result,
							]);
							?>
						</div>

						<?php
						echo $this->element('CustomFields.tabs_content');
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
					echo $this->Html->link(__('Close'), '#', [
						'data-dismiss' => 'modal',
						'class' => 'btn btn-inverse'
					]);
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'SecurityServiceMaintenance',
			'id' => isset($edit) ? $this->data['SecurityServiceMaintenance']['id'] : null
		));
		?>
	</div>
</div>
