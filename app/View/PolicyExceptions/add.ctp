<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'PolicyException', array(
							'url' => array( 'controller' => 'policyExceptions', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'PolicyException', array(
							'url' => array( 'controller' => 'policyExceptions', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li class=""><a href="#tab_asset" data-toggle="tab"><?php echo __('Assets'); ?></a></li>
						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __( 'Title' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'title', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Give a descriptive title to this Exception' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Security Policy Items' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'PolicyException.SecurityPolicy', array(
										'options' => $security_policies,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: If a policy exception is requested is expected that you have a policy documented in eramba. Being able to link your exceptions to your policies allows you further analysis on which policies are not business aligned.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Requestor
							]); ?>

							<?= $this->FieldData->input($FieldDataCollection->Classification); ?>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Third Parties' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'PolicyException.ThirdParty', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: Select any third parties affected by this policy exception.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'thirdParties', 'action' => 'add'),'text' => 'Add Third Party')); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Expiration' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'expiration', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									) ); ?>
									<span class="help-block"><?php echo __( 'Exceptions are not eternal, they must expire at some point time. This setting will let you define notifications before this date to be sent to the requestor or anyone you define.' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->input($FieldDataCollection->closure_date_toggle); ?>
							<?= $this->FieldData->input($FieldDataCollection->closure_date); ?>

							<script type="text/javascript">
								jQuery(function($) {
									$("#PolicyExceptionClosureDateToggle").on("change", function(e) {
										if ($(this).is(":checked")) {
											$("#PolicyExceptionClosureDateToggle").attr("checked", true);
											$("#PolicyExceptionClosureDate").attr("disabled", "disabled");
										}
										else {
											$("#PolicyExceptionClosureDateToggle").attr("checked", false);
											$("#PolicyExceptionClosureDate").removeAttr("disabled");
										}
									}).trigger("change");
								});
							</script>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'status', array(
										'options' => $statuses,
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Describe the exception status, valid or closed.' ); ?></span>
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
									<span class="help-block"><?php echo __( 'OPTIONAL: Describe the Policy Exception in detail (when, what, where, why, whom, how).' ); ?></span>
								</div>
							</div>
						</div>

						<div class="tab-pane fade in" id="tab_asset">
							<?php 
							echo $this->FieldData->inputs([
								$FieldDataCollection->Asset
							]);
							?>
						</div>

						<?php
						echo $this->element('CustomFields.tabs_content');
						?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('PolicyException');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'PolicyException',
			'id' => isset($edit) ? $this->data['PolicyException']['id'] : null
		));
		?>
	</div>
</div>