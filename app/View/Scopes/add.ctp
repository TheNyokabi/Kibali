<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create('Scope', array(
							'url' => array( 'controller' => 'scopes', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border'
						));

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create('Scope', array(
							'url' => array( 'controller' => 'scopes', 'action' => 'add' ),
							'class' => 'form-horizontal row-border'
						));
						
						$submit_label = __( 'Add' );
					}
				?>
				
				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __('CISO Role'); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('ciso_role_id', array(
							'options' => $users,
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'empty' => __('None')
						)); ?>
						<span class="help-block"><?php echo __( 'Optionally, chose a user that will take the role of CISO.' ); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('CISO Deputy'); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('ciso_deputy_id', array(
							'options' => $users,
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'empty' => __('None')
						)); ?>
						<span class="help-block"><?php echo __( 'Optionally, chose a user that will take the role of CISO Deputy.' ); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Board Representative'); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('board_representative_id', array(
							'options' => $users,
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'empty' => __('None')
						)); ?>
						<span class="help-block"><?php echo __( 'Optionally, chose a user that will take the role of Board Representative.' ); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Board Representative Deputy'); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('board_representative_deputy_id', array(
							'options' => $users,
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'empty' => __('None')
						)); ?>
						<span class="help-block"><?php echo __( 'Optionally, chose a user that will take the role of Board Representative deputy.' ); ?></span>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
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
