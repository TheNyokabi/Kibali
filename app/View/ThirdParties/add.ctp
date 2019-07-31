<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ThirdParty', array(
							'url' => array( 'controller' => 'thirdParties', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ThirdParty', array(
							'url' => array( 'controller' => 'thirdParties', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
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
									<span class="help-block"><?php echo __( 'Give a name to this Third Party, for example: Internet Suppliers' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Type' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'third_party_type_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __( 'Select one' )
									) ); ?>
									<span class="help-block"><?php echo __( 'INFORMATIVE: Select an applicable type for this Third Party, whatever you choose will be simply informative, it wont affect what can be done with this third party.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Sponsor
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Description' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Provide a brief description on how your orgnization collaborates with this Third Party.' ); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Liabilities' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'ThirdParty.Legal', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Choose all applicable liabilites for this third party, this will affect the Risk Score for this Third Party (read our Risk Management documentation to understand how this works).' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'legals', 'action' => 'add'),'text' => 'Add Liability')); ?>
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
					echo $this->Ajax->cancelBtn('ThirdParty');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ThirdParty',
			'id' => isset($edit) ? $this->data['ThirdParty']['id'] : null
		));
		?>
	</div>
</div>
