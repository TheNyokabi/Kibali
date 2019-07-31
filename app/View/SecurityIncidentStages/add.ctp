<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'SecurityIncidentStage', array(
							'url' => array( 'controller' => 'securityIncidentStages', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border'
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'SecurityIncidentStage', array(
							'url' => array( 'controller' => 'securityIncidentStages', 'action' => 'add' ),
							'class' => 'form-horizontal row-border'
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="form-group" style="border-top:none; padding-top:5px;">
					<label class="col-md-2 control-label"><?php echo __( 'Name' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'name', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'error' => array(
								'notEmpty' 	=> __('Name is required.'),
							)
						) ); ?>
						<span class="help-block"><?php echo __( 'Give a name to this stage. For example "Identification" or "Containment"' ); ?></span>
					</div>
				</div>

				<div class="form-group" style="border-top:none; padding-top:5px;">
					<label class="col-md-2 control-label"><?php echo __( 'Description' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'description', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'error' => array(
								'notEmpty' 	=> __('Description is required.'),
							)
						) ); ?>
						<span class="help-block"><?php echo __( 'Give a description to this stage' ); ?></span>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Html->link(__('Cancel'), [
						'plugin' => null,
						'controller' => 'securityIncidentStages',
						'action' => 'index'
					], [
						'class' => 'btn btn-inverse'
					]);
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>
