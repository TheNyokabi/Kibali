<div class="row">
	<div class="col-md-12">
		<div class="widget box" id="reset-database-container">
			<div class="widget-content">
				<?php
					echo $this->Form->create( 'Setting', array(
						'url' => array( 'controller' => 'settings', 'action' => 'resetDatabase'),
						'class' => 'form-horizontal row-border',
						'id' => 'reset-database-form'
					) );
				?>

				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __( 'Reset database' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('reset_db', array(
							'type' => 'checkbox',
							'label' => false,
							'div' => false,
							'class' => 'uniform',
						) ); ?>
						<span class="help-block"><?php echo __( 'Check if you are really sure to reset database.' ); ?></span>
					</div>

				</div>

				<div class="form-group hidden">
					<label class="col-md-2 control-label"><?php echo __( 'Insert Demo Data' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input('inset_data', array(
							'type' => 'checkbox',
							'label' => false,
							'div' => false,
							'class' => 'uniform',
							// 'disabled' => true
						) ); ?>
						<span class="help-block"><?php echo __( 'Load with a preset database' ); ?></span>

					</div>

				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( __('Reset'), array(
						'class' => 'btn btn-primary',
						'id' => 'reset-database-submit',
						'div' => false
					) ); ?>
					&nbsp;
					<?php echo $this->Html->link( __( 'Cancel' ), array(
						'action' => 'index'
					), array(
						'class' => 'btn btn-inverse'
					) ); ?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {
	var $form = $("#reset-database-form");
	var $submit = $("#reset-database-submit");

	$form.on("submit", function(e) {
		setPseudoNProgress();
		App.blockUI($("#reset-database-container"));
	});

	$("#SettingResetDb").on("change", function(e) {
		if ($(this).is(":checked")) {
			$("#SettingInsetData").prop("disabled", false);
		}
		else {
			$("#SettingInsetData").prop("disabled", true);
		}

		$.uniform.update($("#SettingInsetData"));
	}).trigger("change");
});
</script>