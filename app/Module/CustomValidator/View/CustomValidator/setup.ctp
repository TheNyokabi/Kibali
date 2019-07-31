<?php 
// debug(Debugger::exportVar($FieldDataCollection));
App::uses('CustomValidatorField', 'CustomValidator.Model');
?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
				echo $this->Form->create('CustomValidatorField', [
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				]);

				$submitLabel = __('Edit');
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<?php foreach ($validator['fields'] as $fieldName => $fieldConfig) : ?>
								<?php $field = $FieldDataCollection->{$fieldName}; ?>
								<?php //debug(Debugger::exportVar($field->getFieldName())); 
								?>

								<div class="form-group" style="border-top:none; padding-top:5px;">
									<label class="col-md-2 control-label"><?php echo $field->label(); ?>:</label>
									<div class="col-md-10">
										<?php echo $this->Form->input($field->getFieldName(), [
											'type' => 'select',
											'options' => CustomValidatorField::getValidationOptions($field->config('type')),
											'default' => $fieldConfig,
											'label' => false,
											'div' => false,
											'class' => 'form-control',
										]); ?>
									</div>
								</div>

							<?php endforeach; ?>

						</div>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit($submitLabel, [
						'class' => 'btn btn-primary',
						'div' => false
					]); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('CustomValidatorField');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>