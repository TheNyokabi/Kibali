<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'Legal', array(
							'url' => array( 'controller' => 'legals', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border'
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'Legal', array(
							'url' => array( 'controller' => 'legals', 'action' => 'add' ),
							'class' => 'form-horizontal row-border'
						) );
						
						$submit_label = __( 'Add' );
					}
				?>

				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __( 'Name' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'name', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						) ); ?>
						<span class="help-block"><?php echo __( 'Give a name to this Liability. For example "Contractual Liabilities, Legal Liabilities, Customer Liabilities, Etc' ); ?></span>
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
						<span class="help-block"><?php echo __( 'Give a brief description of what this liability involves.' ); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __( 'Risk Magnifier' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'risk_magnifier', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						) ); ?>
						<span class="help-block"><?php echo __( 'This value will automatically increase the Risk levels (by multiplicating the Risk Score with this value) every time this Liability is assigned to an Asset or Third Party. This is used to increase the visibility of this Risks.' ); ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Legal Advisor'); ?>:</label>
					<div class="col-md-10">
						<?php
							$selected = array();
							if (isset($this->request->data['LegalAdvisor'])) {
								foreach ($this->request->data['LegalAdvisor'] as $item) {
									$selected[] = $item['id'];
								}
							}

							if (isset($this->request->data['Legal']['legal_advisor_id']) && is_array($this->request->data['Legal']['legal_advisor_id'])) {
								foreach ($this->request->data['Legal']['legal_advisor_id'] as $entry) {
									$selected[] = $entry;
								}
							}
						?>
						<?php echo $this->Form->input('legal_advisor_id', array(
							'options' => $users,
							'label' => false,
							'div' => false,
							'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
							'multiple' => true,
							'selected' => $selected
						)); ?>
						<span class="help-block"><?php echo __( 'Choose one representative for this liability.' ); ?></span>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Workflow->cancelBtn('Legal');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>
