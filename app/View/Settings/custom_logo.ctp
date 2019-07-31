<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
					echo $this->Form->create( 'Setting', array(
						'url' => array( 'controller' => 'settings', 'action' => 'customLogo'),
						'class' => 'form-horizontal row-border',
						'type' => 'file'
					) );
				?>

				<?php if(defined('CUSTOM_LOGO') && CUSTOM_LOGO): ?>
					<div class="form-group form-group-first">
						<label class="col-md-2 control-label"><?php echo __( 'Active Logo' ); ?>:</label>
						<div class="col-md-10">
							<?php echo $this->Eramba->getLogo(); ?>

							<span class="help-block">
								<?php echo $this->Html->link( __( 'Delete Logo' ), array(
									'action' => 'customLogo',
									'?' => array(
										'delete' => true
									)),
									array(
										'escape' => false,
										'class' => 'btn btn-xs btn-danger'
									)
									); ?>
							</span>
						</div>
					</div>
				<?php endif; ?>

				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __( 'Logo Upload' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('logo_file', array(
							'type' => 'file',
							'label' => false,
							'div' => false,
							'class' => false,
							'data-style' => 'fileinput',
							'required' => false
						) ); ?>
						<span class="help-block"><?php echo __( 'Upload your logo here.' ); ?></span>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( __('Change'), array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php echo $this->Html->link( __( 'Cancel' ), array(
						'action' => 'index'
					), array(
						'class' => 'btn btn-inverse'
					) ); ?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>