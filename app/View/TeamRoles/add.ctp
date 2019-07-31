<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'TeamRole', array(
							'url' => array( 'controller' => 'teamRoles', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'TeamRole', array(
							'url' => array( 'controller' => 'teamRoles', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>

						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Name'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('user_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('Select a user that is a team member'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php
									echo $this->Ajax->quickAddAction(array(
										'url' => array(
											'controller' => 'users',
											'action' => 'add'
										),
										'text' => __('Add User')
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Role'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('role', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('Describe the role this team member has within the program'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Responsibilities'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('responsibilities', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('Describe the responsibilities of this team member or role'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Competences'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('competences', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('Describe the competences (skills, etc) that this team member has or plans to acquire'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Status'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('status', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('Select the status for this team member as Active or Inactive (no longer part of the program)'); ?></span>
								</div>
							</div>
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
					echo $this->Ajax->cancelBtn('TeamRole');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'TeamRole',
			'id' => isset($edit) ? $this->data['TeamRole']['id'] : null
		));
		?>
	</div>
</div>
