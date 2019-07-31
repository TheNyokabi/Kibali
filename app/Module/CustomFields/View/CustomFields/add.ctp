<?php
if (!empty($customFieldCount) && $customFieldCount >= $customFieldMaxCount) {
	echo $this->element('not_found', array(
		'message' => __('You can have maximum %s Custom Fields for a Custom Tab configured.', $customFieldMaxCount)
	));

	echo $this->Ajax->cancelBtn('CustomField');
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
						echo $this->Form->create( 'CustomField', array(
							'url' => array( 'controller' => 'customFields', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						echo $this->Form->input( 'custom_form_id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'CustomField', array(
							'url' => array( 'controller' => 'customFields', 'action' => 'add', $customFormId ),
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
								<label class="col-md-2 control-label"><?php echo __('Name'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __( 'Give a name to this Custom Field, this name will be shown on the left of the field.' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Type'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('type', array(
										'options' => getCustomFieldTypes(),
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose a type'),
										'id' => 'custom-field-type'
									));
									?>
									<span class="help-block"><?php echo __( 'Choose one of the available types for this Custom Field.' ); ?></span>
								</div>
							</div>

							<div class="form-group" id="custom-field-dropdown-options">
								<label class="col-md-2 control-label"><?php echo __('Dropdown Options'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->element('options');
									?>
									<span class="help-block"><?php echo __('Create dropdown options for this Custom Field.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Description'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('description', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __( 'Give a description to this Custom Field, this text will be shown below the field (just like this text you are reading now)' ); ?></span>
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
					echo $this->Ajax->cancelBtn('CustomField');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'CustomField',
			'id' => isset($edit) ? $this->data['CustomField']['id'] : null
		));
		?>
	</div>
</div>


<script type="text/javascript">
	jQuery(function($) {
		$("#custom-field-type").on("change", function(e) {
			if ($(this).val() == <?php echo CUSTOM_FIELD_TYPE_DROPDOWN; ?>) {
				$("#custom-field-dropdown-options").show();
			}
			else {
				$("#custom-field-dropdown-options").hide();
			}
		}).trigger("change");
	});
</script>
