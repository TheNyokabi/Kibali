<?php
// additional form elements for specific functionality
$formElement = 'settings/edit/form/' . $settingGroup['SettingGroup']['slug'];
?>
<?php 
if ($settingGroup['SettingGroup']['slug'] == 'BACKUP') {
	echo $this->Ux->getAlert('Warning: the backup functionality only looks at the database, attached files are not going to be back up by this functionality, please review our Install / Configuration for suggestions on how we recommend doing full application backups.', ['type' => 'info']);
}
?>
<?php if($this->elementExists($formElement)) : ?>
	<?php echo $this->element($formElement); ?>
<?php else : ?>
	<div class="row">
		<div class="col-md-12">
			<div class="widget box">
				<div class="widget-content">

					<?php
						echo $this->Form->create( 'Setting', array(
							'url' => array( 'controller' => 'settings', 'action' => 'edit', $slug ),
							'class' => 'form-horizontal row-border'
						) );

						$submit_label = __( 'Edit' );
					?>

					<?php foreach ($settingGroup['Setting'] as $key => $setting): ?>
						<?php
						$formGroupClass = 'form-group';
						if ($key == 0) {
							$formGroupClass .= ' form-group-first';
						}
						?>
						<div class="<?php echo $formGroupClass; ?>">
							<label class="col-md-2 control-label"><?php echo $setting['name'] ; ?>:</label>
							<div class="col-md-10">
								<?php echo $this->element('settings/edit/input', array('setting' => $setting)); ?>

								<span class="help-block"><?php echo isset($notes[$setting['variable']])?$notes[$setting['variable']]:''; ?></span>
							</div>
						</div>
					<?php endforeach; ?>

					<?php
					// additional group elements for specific functionality
					$groupElement = 'settings/edit/group/' . $settingGroup['SettingGroup']['slug'];
					if($this->elementExists($groupElement)) {
						echo $this->element($groupElement);
					}
					?>

					<?php if(isset($redirectUrl)): ?>
					<?php echo $this->Form->hidden('redirectUrl', array(
						'value' => $redirectUrl
					));?>
					<?php endif;  ?>

					<?php echo $this->element('settings/edit/form_actions', array('submitLabel' => $submit_label)); ?>

					<?php echo $this->Form->end(); ?>

				</div>
			</div>
		</div>
	</div>
<?php endif; ?>