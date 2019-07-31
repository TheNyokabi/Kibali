<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( $issueModel, array(
							'url' => array( 'controller' => 'issues', 'action' => 'edit', $id ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( $issueModel, array(
							'url' => array( 'controller' => 'issues', 'action' => 'add', $model, $foreign_key ),
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
								<label class="col-md-2 control-label"><?php echo __( 'Date Started' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'date_start', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker',
										// 'readonly' => isset($edit)?'readonly':false
									) ); ?>
									<span class="help-block"><?php echo __( 'Input the date when this security services started having issues.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Date Ended' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'date_end', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									) ); ?>
									<span class="help-block"><?php echo __( 'Input the date when this security services stopped having issues. If this date is unknown to you (because you are just creating this issue) you may enter a date in the future for now. You are able to edit this date at any time by simply editing this issue.' ); ?></span>
									<span class="help-block"><?php //echo __( '...' ); ?></span>
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
									<span class="help-block"><?php echo __( 'Describe what is the issue, once you save this issue you are able to attach documents to it, this might be useful if you have evidence, emails, records that are worth keeping on the system.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Status'); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input('status', array(
										'options' => getIssueStatuses(),
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									)); ?>
									<span class="help-block"><?php echo __( 'Set the issue status, options are:<br><br>
									- Open: if the issue has not been yet resolved.<br>
									- Closed: if the issue has been resolved.' ); ?></span>
									<span class="help-block"></span>
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
					if(isset($edit)){
						echo $this->Ajax->cancelBtn($issueModel, $id);
					}
					else{
						echo $this->Ajax->cancelBtn($issueModel);
					}

					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'Issue',
			'id' => isset($edit) ? $this->data[$issueModel]['id'] : null
		));
		?>
	</div>
</div>