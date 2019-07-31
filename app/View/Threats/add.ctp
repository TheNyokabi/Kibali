<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					echo $this->Form->create( 'Threat', array(
						'url' => array( 'controller' => 'threats', 'action' => 'add' ),
						'class' => 'form-horizontal row-border',
						'novalidate' => true
					) );

					$submit_label = __( 'Add' );
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<div class="form-group" style="border-top:none; padding-top:5px;">
								<label class="col-md-2 control-label"><?php echo __( 'Name' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'error' => array(
											'notEmpty' 	=> __('Name is required.'),
											'unique' 	=> __('Same threat already exists.'),
										)
									) ); ?>
									<span class="help-block"><?php echo __( 'Give a name to this threat' ); ?></span>
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
					echo $this->Ajax->cancelBtn('Threat');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>