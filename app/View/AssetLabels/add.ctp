<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'AssetLabel', array(
							'url' => array( 'controller' => 'assetLabels', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'AssetLabel', array(
							'url' => array( 'controller' => 'assetLabels', 'action' => 'add' ),
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
							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Name' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'The name of the label, for example: Confidential, Secret, Etc.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'The description of the label, such as the treatment they require and the audiences in scope.' ); ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('AssetLabel');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'AssetLabel',
			'id' => isset($edit) ? $this->data['AssetLabel']['id'] : null
		));
		?>
	</div>
</div>
