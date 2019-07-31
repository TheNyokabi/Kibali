<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ProgramScope', array(
							'url' => array( 'controller' => 'programScopes', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ProgramScope', array(
							'url' => array( 'controller' => 'programScopes', 'action' => 'add' ),
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

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Version'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('version', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __( 'The version of the scope. As you update the version of your scope you should update this number.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Description'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('The purpose of this document is to clearly define the boundaries of the Information Security Management System (ISMS)'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php
									$types = getProgramScopeStatuses();
									if ($hasCurrent) {
										unset($types[PROGRAM_SCOPE_CURRENT]);
									}

									echo $this->Form->input('status', array(
										'options' => $types,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __( 'The status of the scope can be "Draft" (in development), "Current" (its the actual scope of your program. n.b. There can only be one active scope) and "Discarded" (the scope is no longer in use).' ); ?></span>

									<?php if ($hasCurrent) : ?>
										<div class="alert alert-danger fade in">
											<?php echo __('Current scope already exists and therefore cannot be selected as a status.'); ?>
										</div>
									<?php endif; ?>
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
					echo $this->Ajax->cancelBtn('ProgramScope');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ProgramScope',
			'id' => isset($edit) ? $this->data['ProgramScope']['id'] : null
		));
		?>
	</div>
</div>
