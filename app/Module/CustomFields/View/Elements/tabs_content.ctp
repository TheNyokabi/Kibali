<?php if (!empty($customFields_available)) : ?>

	<?php $inc=0; foreach ($customFields_data as $group) : ?>
		<?php if (empty($group['CustomField'])) continue; ?>

		<div class="tab-pane fade in" id="<?php echo $this->CustomFields->getGroupAlias($group['CustomForm']['slug']); ?>">
			<?php foreach ($group['CustomField'] as $field) : ?>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo h($field['name']); ?>:</label>
					<div class="col-md-10">
						<?php
						$default = null;
					
						if (!empty($this->data['CustomFieldValue'])) {
							foreach ($this->data['CustomFieldValue'] as $v) {
								if (empty($v['custom_field_id'])) {
									continue; // ?????
								}

								if ($v['custom_field_id'] == $field['id']) {
									$default = $v['value'];
								}
							}
						}

						echo $this->element('CustomFields.' . CUSTOM_FIELDS_TYPES_ELEMENT_PATH . $this->CustomFields->getFieldTypeSlug($field['type']), array(
							'field' => $field,
							'default' => $default
						));

						if ($this->Form->isFieldError('customFieldValue_' . $field['id'])) {
							echo $this->Form->error('customFieldValue_' . $field['id']);
						}
						?>

						<?php if (!empty($field['description'])) : ?>
							<span class="help-block"><?php echo h($field['description']); ?></span>
						<?php endif; ?>
					</div>
				</div>

			<?php $inc++; endforeach; ?> 
		</div>

	<?php endforeach; ?>

<?php endif; ?>