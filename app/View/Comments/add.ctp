<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
				if (isset($edit)) {
					echo $this->Form->create( 'Comment', array(
						'url' => array( 'controller' => 'comments', 'action' => 'edit', $model, $foreign_key ),
						'class' => 'form-horizontal row-border'
					) );

					echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
					$submit_label = __( 'Edit' );
				}
				else {
					echo $this->Form->create( 'Comment', array(
						'url' => array( 'controller' => 'comments', 'action' => 'add', $model, $foreign_key ),
						'class' => 'form-horizontal row-border'
					) );
					
					$submit_label = __( 'Add' );
				}
				?>

				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __( 'Message' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'message', array(
							'type' => 'textarea',
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						) ); ?>
					</div>
				</div>

				<?php echo $this->Form->input( 'model', array(
					'type' => 'hidden',
					'value' => $model
				) ); ?>

				<?php echo $this->Form->input( 'foreign_key', array(
					'type' => 'hidden',
					'value' => $foreign_key
				) ); ?>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php echo $this->Html->link( __( 'Cancel' ), array(
						'action' => 'index',
						$model,
						$foreign_key
					), array(
						'class' => 'btn btn-inverse'
					) ); ?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>