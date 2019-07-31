<?php
echo $this->Ajax->flash();
?>

<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('General Section Settings'); ?></h4>
	</div>
	<div class="widget-content">
		<?php
		echo $this->Form->create('CustomFieldSetting', array(
			'url' => array('controller' => 'customFieldSettings', 'action' => 'edit', $model),
			'class' => 'form-horizontal row-border',
			'id' => 'custom-fields-settings-form',
			'novalidate' => true
		));
		?>

		<div class="form-group form-group-first">
			<label class="col-md-2 control-label"><?php echo __('Enabled'); ?>:</label>
			<div class="col-md-10">
				<?php
				echo $this->Form->input('status', array(
					'type' => 'checkbox',
					'label' => false,
					'div' => 'make-switch switch-small',
					'class' => 'toggle',
					'id' => 'custom-fields-toggle'
				));
				?>
				<span class="help-block"><?php echo __('By default custom fields are disabled. You can enable this feature with this toogle. If you disable this toogle while custom fields exist, they will not be removed, just not shown.'); ?></span>
			</div>
		</div>

		<?php
		echo $this->Form->end();
		?>
	</div>
</div>
