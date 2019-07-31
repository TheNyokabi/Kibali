<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">
				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'Process', array(
							'url' => array( 'controller' => 'processes', 'action' => 'edit', $id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'Process', array(
							'url' => array( 'controller' => 'processes', 'action' => 'add', $bu_id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'business_unit_id', array(
							'type' => 'hidden',
							'value' => $bu_id
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>

						<?php echo $this->element('CustomFields.tabs'); ?>
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
									<span class="help-block"><?php echo __( 'The name of the process in the scope of this business unit. For example "Payroll", "Hiring".' ); ?></span>
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
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'RTO' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'rto', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'default' => 0
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. How long (in days, hours, etc) should it take to get things back on (this should be less or equal to MTO). Most business people usually reply ASAP, but in practice that might not be accurate.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'MTO' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'rpo', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'default' => 0
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. How long things can be broken before they become a serious business problem.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Revenue per Hour' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'rpd', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'default' => 0
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. This should be used as a prioritization tool in order to Risk analyse those business that loss is too high and a mitigation -continuity plan- is cost effective.' ); ?></span>
								</div>
							</div>
						</div>

						<?php echo $this->element('CustomFields.tabs_content'); ?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('Process');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'Process',
			'id' => isset($edit) ? $this->data['Process']['id'] : null
		));
		?>
	</div>
</div>
