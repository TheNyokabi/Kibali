<div class="widget">
	<div class="btn-toolbar pull-right">
		<div class="btn-group">
			<?php
			echo $this->Html->link(__('Back'), array(
				'plugin' => false,
				'controller' => controllerFromModel($model),
				'action' => 'index'
			), array(
				'class' => 'btn btn-info',
				'escape' => false
			));
			?>
		</div>
	</div>

	<div class="btn-toolbar">
		<div class="btn-group">
			<?php
			echo $this->Html->link('<i class="icon-plus-sign"></i>' . __('Add New Tab'),
				array(
					'controller' => 'customForms',
					'action' => 'add',
					$model
				), array(
				'class' => 'btn',
				'data-ajax-action' => 'add',
				'escape' => false
			));
			?>
		</div>

		<?php //echo $this->Video->getVideoLink('CustomField'); ?>
	</div>
</div>

<div id="custom-field-setting-wrapper">
	<?php
	echo $this->element('../CustomFieldSettings/add');
	?>
</div>

<?php if (!empty($data)) : ?>
	<?php foreach ($data as $item) : ?>
		<div class="widget box">
			<div class="widget-header">
				<h4><?php echo __('Custom Tab: %s', $item['CustomForm']['name']); ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse hidden"><i class="icon-angle-down"></i></span>
						<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
							<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
						</span>
						<?php
						$fieldsUrl = array(
							'controller' => 'customFields',
							'action' => 'add',
							$item['CustomForm']['id']
						);

						$this->Ajax->addToActionList(__('Add Custom Field'), $fieldsUrl, 'plus-sign', 'add');

						echo $this->Ajax->getActionList($item['CustomForm']['id'], array(
							'notifications' => false,
							'model' => 'CustomForm',
							'item' => $item
						));
						?>
					</div>
				</div>
			</div>
			<div class="widget-content" style="">
				<?php if (!empty($item['CustomField'])) : ?>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th><?php echo __('Name'); ?></th>
								<th><?php echo __('Type'); ?></th>
								<th class="align-center"><?php echo __('Action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($item['CustomField'] as $field) : ?>
								<tr>
									<td>
										<?php
										echo $field['name'];
										?>
									</td>
									<td>
										<?php
										echo getCustomFieldTypes($field['type']);
										?>
									</td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($field['id'], array(
											'notifications' => false,
											'style' => 'icons',
											'controller' => 'customFields',
											'model' => 'CustomField',
											'item' => $field
										));
										?>
									</td>
								</tr>
							<?php endforeach ; ?>
						</tbody>
					</table>
				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('We could not find Custom Fields for this tab and therefore it wont be active. Click on Manage / Add Custom Field to create a new field.')
					));
					?>
				<?php endif; ?>

			</div>
		</div>

	<?php endforeach; ?>

	<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
<?php else : ?>
	<?php echo $this->element( 'not_found', array(
		'message' => __( 'No Custom Fields found.' )
	) ); ?>
<?php endif; ?>

<script type="text/javascript">
	jQuery(function($) {
		var toggleState = getToggleState();

		var $settingsForm = "#custom-fields-settings-form";
		var $settingsWrapper = $("#custom-field-setting-wrapper");

		function getToggleState() {
			return $("#custom-fields-toggle").is(":checked");
		}

		$settingsWrapper.on("change", $settingsForm, function(e) {
			e.preventDefault();

			// lets not run ajax saving if toggle has not been changed for this case
			if (getToggleState() == toggleState) {
				return true;
			}

			var formData = $(this).serializeArray();

			$.ajax({
				type: "POST",
				url: $(this).prop("action"),
				data: formData
			}).done(function(data) {
				$settingsWrapper.html(data);

				FormComponents.init();
				toggleState = getToggleState();
			});
		});

	});
</script>
