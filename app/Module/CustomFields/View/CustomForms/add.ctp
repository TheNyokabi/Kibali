<?php
if (!empty($customFormCount) && $customFormCount >= CUSTOM_FIELD_MAX_TABS) {
	echo $this->element('not_found', array(
		'message' => __('You can have maximum %s Custom Tabs for a section configured.', CUSTOM_FIELD_MAX_TABS)
	));

	echo $this->Ajax->cancelBtn('CustomForm');
	return;
}
?>
<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'CustomForm', array(
							'url' => array( 'controller' => 'customForms', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						echo $this->Form->input( 'model', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'CustomForm', array(
							'url' => array( 'controller' => 'customForms', 'action' => 'add', $model ),
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
								<label class="col-md-2 control-label"><?php echo __( 'Name' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'This is the name of the custom tab, it will be shown on the right of the popup window (on the left of the popup window are eramba native tabs).' ); ?></span>
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
					echo $this->Ajax->cancelBtn('CustomForm');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'CustomForm',
			'id' => isset($edit) ? $this->data['CustomForm']['id'] : null
		));
		?>
	</div>
</div>
