<?php
// $isDefault = (bool) !$index;
?>
<div class="widget box threshold-item">
	<div class="widget-header">
		<h4>
			<?php
			if ($isDefault) {
				echo __('Default Threshold');
			}
			else {
				echo __('General Threshold');
			}
			?>
		</h4>
		<?php if (!$isDefault) : ?>
			<div class="toolbar no-padding">
				<div class="btn-group">
					<span class="btn btn-xs circular-charts-reload threshold-remove-item">
						<i class="icon-remove"></i> 
						<?php echo __('Remove'); ?>
					</span>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<div class="widget-content">
		<div class="form-horizontal">
			<?php
			$colorpickerOptions = [
				'data-field-type' => 'color',
				'between' => '<div class="col-md-10"><div class="input-group">',
				'after' => '<span class="input-group-addon input-colorpicker"><i class="icon-cog"></i></span></div>' . $this->FieldData->help($FieldDataCollection->color) . $this->FieldData->description($FieldDataCollection->color) . '</div>',
				'type' => 'text'
			];

			if (isset($index) && $this->Form->isFieldError('threshold_' . $index)) {
				echo $this->Form->error('threshold_' . $index);
			}
			if (!$isDefault) {
				$i=0;foreach ($classificationOptions as $index2 => $classificationOption) {
					$value = null;
					if (!empty($threshold['RiskAppetiteThresholdClassification'][$i]['risk_classification_id'])) {
						$value = $threshold['RiskAppetiteThresholdClassification'][$i]['risk_classification_id'];
					}

					echo $this->FieldData->input([$RiskAppetiteThresholdClassificationCollection->risk_classification_id, $index], [
						'options' => $classificationOption,
						'inputName' => 'RiskAppetiteThreshold.' . $index . '.RiskAppetiteThresholdClassification.' . $index2 . '.risk_classification_id',
						'value' => $value,
						'data-field-type' => 'risk-classification'
					]);

					$i++;
				}

				echo $this->FieldData->input([$FieldDataCollection->title, $index]);
				echo $this->FieldData->input([$FieldDataCollection->description, $index]);
				echo $this->FieldData->input([$FieldDataCollection->color, $index], $colorpickerOptions);

				echo $this->Form->input('RiskAppetiteThreshold.' . $index . '.type', [
					'type' => 'hidden',
					'value' => RiskAppetiteThreshold::TYPE_GENERAL
				]);
			}
			else {
				$colorPickerOptionsDefault = $colorpickerOptions;
				$colorPickerOptionsDefault['inputName'] = 'RiskAppetiteThresholdDefault.color';
				echo $this->FieldData->input($FieldDataCollection->title, [
					'inputName' => 'RiskAppetiteThresholdDefault.title'
				]);
				echo $this->FieldData->input($FieldDataCollection->description, [
					'inputName' => 'RiskAppetiteThresholdDefault.description'
				]);
				echo $this->FieldData->input($FieldDataCollection->color, $colorPickerOptionsDefault);
				echo $this->Form->input('RiskAppetiteThresholdDefault.type', [
					'type' => 'hidden',
					'value' => RiskAppetiteThreshold::TYPE_DEFAULT
				]);
			}
			
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	jQuery(function($) {
		$("[data-field-type=risk-classification]").select2();
		$("[data-field-type=color]")
			.colorpicker({
				useAlpha: false,
				format: "hex"
			})
			.on("colorpickerChange", function(e) {
				changeColor($(this), e.color.toString());
		});

		$("[data-field-type=color]").each(function(i, e) {
			changeColor($(e), $(e).val());
		});

		function changeColor(ele, color) {
			if (color.length) {
				ele.parent().find(".input-group-addon.input-colorpicker").css('background-color', color);
			}
		}

		$(".threshold-remove-item").on("click", function(e) {
			$(this).closest(".threshold-item").fadeOut(500, function(e) {
				$(this).remove();
			});
		});
	});
</script>