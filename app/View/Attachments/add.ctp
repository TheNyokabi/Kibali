<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
				echo $this->Form->create( 'Attachment', array(
					'url' => array( 'controller' => 'attachments', 'action' => 'add', $model, $foreign_key ),
					'class' => 'form-horizontal row-border',
					'type' => 'file'
				) );

				$submit_label = __( 'Add' );
				?>

				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __( 'File Upload' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'file', array(
							'type' => 'file',
							'label' => false,
							'div' => false,
							'class' => false,
							'data-style' => 'fileinput'
						) ); ?>
						<span class="help-block"><?php echo __( 'Upload your file here.' ); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __( 'Description' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'description', array(
							'type' => 'textarea',
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						) ); ?>
						<span class="help-block"><?php echo __( 'Description to your file here.' ); ?></span>
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