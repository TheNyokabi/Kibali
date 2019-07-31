<?php echo $this->Html->css("ImportTool.styles"); ?>
<?php
echo $this->Form->create('ImportTool', array(
	'url' => $this->ImportTool->getPreviewUrl()
));
?>

<div class="widget">
	<div class="btn-toolbar">
		<?php
		echo $this->Form->submit(__('Import'), array(
			'class' => 'btn btn-primary',
			'div' => false,
			'id' => 'import-submit-btn',
			// 'disabled' => !$ImportToolData->isImportable()
		));

		echo $this->Html->link(__('Cancel'), $this->ImportTool->getUrl($model), array(
			'class' => 'btn btn-inverse'
		));
		?>
	</div>
</div>

<?php if (!$ImportToolData->isImportable()) : ?>
	<?php
	echo $this->Eramba->getNotificationBox(__('We found some problems on the CSV file you just uploaded.'));
	?>
<?php endif; ?>

<div class="table-responsive-import">
	<table class="table table-hover table-striped table-bordered table-highlight-head">
		<thead>
			<tr>
				<th>
					<?php
					echo $this->Form->input('ImportTool.checkAll', array(
						'type' => 'checkbox',
						'label' => false,
						'div' => false,
						'class' => 'uniform',
						'checked' => true,
						'id' => 'check-all-checkbox'
					));
					?>
				</th>
				<?php
				foreach ($ImportToolData->getArguments() as $arg) {
					$label = $arg->getLabel();
				
					$tooltip = $arg->getHeaderTooltip();
					if (!empty($tooltip)) {
						$label = $this->Eramba->getTruncatedTooltip($label, array(
							'truncate' => false,
							'content' => $tooltip
						));
					}

					echo $this->Html->tag('th', $label);
				}
				?>
				<th class="text-center">
					<?php echo __('Status'); ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($ImportToolData->getData() as $row => $ImportToolRow) : ?>
				<?php
				$rowStructureErrors = $ImportToolRow->getStructureErrors();
				$missingStructureCells = $ImportToolRow->getMissingStructureCells();
				?>
				<tr>
					<td>
						<?php
						$options = [
							'type' => 'checkbox',
							'label' => false,
							'div' => false,
							'class' => 'uniform import-row-checkbox',
							'hiddenField' => false,
							'value' => $row
						];

						// if row item is invalid or have a structural issue
						if (!$ImportToolRow->isImportable()) {
							$options['disabled'] = true;
							$options['class'] .= ' import-row-invalid';
						}

						echo $this->Form->input('ImportTool.checked][', $options);
						?>
					</td>
					<?php
					foreach ($ImportToolData->getArguments() as $index => $ImportToolArgument) : ?>
						<?php
						$value = $ImportToolRow->getData($ImportToolArgument);
						$validationErrors = $ImportToolRow->getValidationErrors($ImportToolArgument);
						$showValidationErrors = $validationErrors && !$rowStructureErrors;

						$extraClass = false;
						if ($showValidationErrors) {
							$extraClass = 'danger';
						}

						$missingCell = in_array($index, $missingStructureCells);
						if ($missingCell) {
							$extraClass = 'warning';
						}
						?>
						<td class="<?php echo $extraClass; ?>">
							<?php
							if ($showValidationErrors) {
								echo $this->Eramba->getTruncatedTooltip($value, array(
									'title' => __('Validation Error'),
									'content' => implode('<br />', $validationErrors)
								));
							}
							elseif ($missingCell) {
								echo $this->Eramba->getTruncatedTooltip($value, array(
									'title' => __('Structural Error'),
									'content' => __('This field is missing in your uploaded file')
								));
							}
							else {
								if (strlen($value) > 30) {
									echo $this->Eramba->getTruncatedTooltip($value, array(
										'title' => __('Field Content')
									));
								}
								else {
									if ($value == '0') {
										echo $value;
									}
									else {
										echo $this->Eramba->getEmptyValue($value);
									}
								}
							}
							?>
						</td>
					<?php endforeach; ?>

					<td class="text-center import-tool-status-cell">
						<?php
						if ($ImportToolRow->isImportable()) {
							echo $this->Eramba->getLabel(__('Ok'));
						}
						else {
							$rowValidationErrors = $ImportToolRow->getValidationErrors();

							if (!empty($rowValidationErrors)) {
								echo $this->Eramba->getLabel(__('Row appears to be invalid'), 'danger');

								$validationTooltip = $this->ImportTool->getValidationErrorsContent($rowValidationErrors, $model);

								if (!empty($validationTooltip)) {
									echo $this->Eramba->getTruncatedTooltip(false, array(
										'title' => __('Validation Errors Help'),
										'content' => $validationTooltip,
										'placement' => 'left'
									));
								}
							}

							if (!empty($rowStructureErrors)) {
								foreach ($rowStructureErrors as $error) {
									echo '<br>' . $this->Eramba->getLabel($error, 'warning');
								}
							}
						}
						?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php
echo $this->Form->end();

$ImportToolData = null;
?>

<script type="text/javascript">
jQuery(function($) {
	function getImportableCheckboxes() {
		return $(".import-row-checkbox:not(.import-row-invalid)");
	}

	function getCheckedCheckboxes() {
		return getImportableCheckboxes().filter(":checked");
	}

	$("#check-all-checkbox").on("import:change", function(e) {
		$.uniform.update();
		$("#import-submit-btn").prop("disabled", !getCheckedCheckboxes().length);
	});

	$("#check-all-checkbox").on("change", function(e) {
		if ($(this).is(":checked")) {
			getImportableCheckboxes().prop("checked", true);
		}
		else {
			getImportableCheckboxes().prop("checked", false);
		}

		$(this).trigger("import:change");
	}).trigger("change");

	getImportableCheckboxes().on("change", function(e) {
		var checked = getCheckedCheckboxes().length;
		if (checked < getImportableCheckboxes().length) {
			$("#check-all-checkbox").prop("checked", false);

			$.uniform.update('#check-all-checkbox');
		}
		else if(checked == getImportableCheckboxes().length) {
			$("#check-all-checkbox").prop("checked", true);

			$.uniform.update('#check-all-checkbox');
		}

		$("#check-all-checkbox").trigger("import:change");

		
	});
});
</script>