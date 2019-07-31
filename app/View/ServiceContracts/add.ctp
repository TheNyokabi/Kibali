<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ServiceContract', array(
							'url' => array( 'controller' => 'serviceContracts', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						/*echo $this->Form->input( 'third_party_id', array(
							'type' => 'hidden'
						));*/
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ServiceContract', array(
							'url' => array( 'controller' => 'serviceContracts', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						if (!empty($tp_id)) {
							echo $this->Form->input( 'third_party_id', array(
								'type' => 'hidden',
								'value' => $tp_id
							) );
						}

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
									<span class="help-block"><?php echo __( 'Give a name to the contract you have in between this provider and your organization. Examples: Firewall Hardware Support, Firewall Consulting Time, Etc.' ); ?></span>
								</div>
							</div>
							<?= $this->FieldData->inputs([
								$FieldDataCollection->Owner
							]); ?>
							<?php if (/*!isset($edit) && */empty($tp_id)) : ?>
								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('Third Party'); ?>:</label>
									<div class="col-md-10">
										<?php
										echo $this->Form->input('third_party_id', array(
											'label' => false,
											'div' => false,
											'class' => 'form-control',
											'empty' => __('Choose one')
										));
										?>
									<span class="help-block"><?php echo __( 'Select the previously defined third party that delivers this support contract.' ); ?></span>

									</div>
								</div>
							<?php endif; ?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Service Value' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'value', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'min' => 0
									) ); ?>
									<span class="help-block"><?php echo __( 'The value of contract.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'Service contracts usually have a start and end dates. This will help you to keep track of renewals.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Start Date' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'start', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									) ); ?>
									<span class="help-block"><?php echo __( 'When the contract starts' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'End Date' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'end', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									) ); ?>
									<span class="help-block"><?php echo __( 'Service contracts usually have a start and end dates. This will help you to keep track of renewals.' ); ?></span>
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
					echo $this->Ajax->cancelBtn('ServiceContract');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ServiceContract',
			'id' => isset($edit) ? $this->data['ServiceContract']['id'] : null
		));
		?>
	</div>
</div>
