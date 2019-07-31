<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'SecurityPolicyReview', array(
							'url' => array( 'controller' => 'securityPolicyReviews', 'action' => 'edit', $id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Planned Date'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('planned_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker',
										'disabled' => true
									));
									?>
									<span class="help-block"><?php echo __('..'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Actual Date'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('actual_review_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									));
									?>
									<span class="help-block"><?php echo __('...'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Reviewer'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('reviewer_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose a reviewer')
									));
									?>
									<span class="help-block"><?php echo __('...'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Comments'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('comments', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('...'); ?></span>
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
					echo $this->Ajax->cancelBtn('SecurityPolicyReview');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'SecurityPolicyReview',
			'id' => isset($edit) ? $this->data['SecurityPolicyReview']['id'] : null
		));
		?>
	</div>
</div>