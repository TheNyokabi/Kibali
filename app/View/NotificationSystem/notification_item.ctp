<?php
if (!isset($type)) {
	$type = NOTIFICATION_TYPE_WARNING;
}

if ($type == NOTIFICATION_TYPE_WARNING) {
	$title = __('Warning Notification Settings');
}
if ($type == NOTIFICATION_TYPE_AWARENESS) {
	$title = __('Informative Notification Settings');
}
if ($type == NOTIFICATION_TYPE_DEFAULT) {
	$title = __('Default Notification Settings');
}

$isReport = false;
if ($type == NOTIFICATION_TYPE_REPORT) {
	$title = __('Report Notification Settings');
	$isReport = true;
}

$extraClass = '';
$id = false;
if (isset($this->data['NotificationSystem'][$formKey]['id'])) {
	$id = $this->data['NotificationSystem'][$formKey]['id'];
}
else {
	$extraClass = 'masked';
}

$customizedEmail = false;
if ($type != NOTIFICATION_TYPE_DEFAULT && (!empty($macros) || !empty($customEmail))) {
	$customizedEmail = true;
}

$triggerPeriodHelper = $this->Html->tag(
	'span',
	__('0 means everyday, 1 means every second day ...'),
	array('class' => 'help-block')
);
?>
<div class="notification-item <?php echo $extraClass; ?> col-md-4 col-sm-6">
	<div class="widget box">
		<div class="widget-header">
			<h4><?php echo $title; ?></h4>

			<div class="toolbar no-padding">
				<div class="btn-group">
					<?php
					if (!$id) {
						$url = '#';
						$class = 'remove-notification-btn';
					}
					else {
						$url = array(
							'controller' => 'notificationSystem',
							'action' => 'delete',
							$id
						);
						$class = '';
					}
					
					echo $this->Html->link('<i class="icon-remove"></i> ' . __('Remove'), $url, array(
						'class' => 'btn btn-xs ' . $class,
						'escape' => false
					));
					?>
				</div>
			</div>
		</div>
		<div class="widget-content">
			<div class="form-vertical row-border">
				<?php
				echo $this->Form->input('NotificationSystem.' . $formKey . '.type', array('type' => 'hidden', 'default' => $type));
				echo $this->Form->input('NotificationSystem.' . $formKey . '.id', array('type' => 'hidden'));
				?>

				<div class="form-group-distinct">
					<div class="form-group">
						<label class="control-label"><?php echo __('Notification Name'); ?>:</label>
						<?php
						echo $this->Form->input('NotificationSystem.' . $formKey . '.name', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('Input a name for this notification, for example "10 days to Audit Due date".'); ?></span>
					</div>

					<div class="form-group">
						<label class="control-label"><?php echo __('Notification'); ?>:</label>
						<?php
						$attrs = array(
							'label' => false,
							'div' => false,
							'class' => 'select2-custom full-width-fix select2-offscreen notification-file'
						);

						// for a report notification we select the one available by default and disable the select2
						if ($isReport) {
							$notificationKeys = array_keys($notificationOptions);
							$attrs['default'] = $notificationKeys[0];
							$attrs['data-readonly'] = true;
						}
						else {
							array_unshift($notificationOptions, array('' => ''));
						}

						$attrs['options'] = $notificationOptions;

						echo $this->Form->input('NotificationSystem.' . $formKey . '.filename', $attrs);
						?>
						<span class="help-block"><?php echo __('You must select what condition will trigger this notification. This option will be disabled for "Report" notifications.'); ?></span>
					</div>
				</div>

				<?php
				$conds = !empty($customizedEmail);
				$conds = $conds || $type == NOTIFICATION_TYPE_REPORT;
				?>
				<div class="<?php if ($conds) echo 'form-group-distinct'; ?>">
					<div class="form-group">
						<label class="control-label"><?php echo __('Users'); ?>:</label>
						<?php
						echo $this->Form->input('NotificationSystem.' . $formKey . '.user_id', array(
							'options' => $users,
							'label' => false,
							'div' => false,
							'class' => 'select2 full-width-fix select2-offscreen',
							'multiple' => true
						));
						?>
						<span class="help-block"><?php echo __('OPTIONAL: Select one or more user accounts who will recieve warning notifications.'); ?></span>
					</div>

					<div class="form-group">
						<label class="control-label"><?php echo __('Emails'); ?>:</label>
						<?php
						echo $this->Form->input('NotificationSystem.' . $formKey . '.emails', array(
							'label' => false,
							'div' => false,
						'class' => 'emails'
						));
						?>
						<span class="help-block"><?php echo __('OPTIONAL: Input one or more emails that will recieve warning notifications.'); ?></span>
					</div>

					<div class="form-group">
						<label class="control-label"><?php echo __('Roles'); ?>:</label>
						<?php
						echo $this->Form->input('NotificationSystem.' . $formKey . '.scope_id', array(
							'options' => $scopes,
							'label' => false,
							'div' => false,
							'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
							'multiple' => true
						));
						?>
						<span class="help-block"><?php echo __('OPTIONAL: Select which system roles  will recieve warning notifications, these roles are defined under System / Settings / Roles'); ?></span>
					</div>

					<?php if (!empty($customFields)) : ?>
						<div class="form-group <?php if ($isReport) echo 'hidden'; ?>">
							<label class="control-label"><?php echo __('Custom Roles'); ?>:</label>
							<?php
							echo $this->Form->input('NotificationSystem.' . $formKey . '.custom_roles', array(
								'options' => $customFields,
								'label' => false,
								'div' => false,
								'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
								'multiple' => true
							));
							?>
							<span class="help-block"><?php echo __('OPTIONAL: Select one or more custom roles, this roles belong to the section where you are right now.'); ?></span>
						</div>
					<?php else : ?>
						<div class="form-group">
							<label class="control-label"><?php echo __('Custom Roles'); ?>:</label>
							<div class="alert alert-info">
								<?php echo __('Custom roles for this item are not available.'); ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="form-group">
						<label class="control-label"><?php echo __('Email notifications'); ?>:</label>
							<label class="checkbox">
							<?php
							echo $this->Form->input('NotificationSystem.' . $formKey . '.email_notification', array(
								'type' => 'checkbox',
								'label' => false,
								'div' => false,
								'class' => 'uniform',
								'default' => true
							));
							?>
							<?php echo __('Enable'); ?>
						</label>
						<span class="help-block"><?php echo __('DEFAULT: Check if you want to receive email notifications.'); ?></span>
					</div>

					<div class="form-group <?php if ($isReport) echo 'hidden'; ?>">
						<label class="control-label"><?php echo __('Header notifications'); ?>:</label>
						<label class="checkbox">
							<?php
							echo $this->Form->input('NotificationSystem.' . $formKey . '.header_notification', array(
								'type' => 'checkbox',
								'label' => false,
								'div' => false,
								'class' => 'uniform'
							));
							?>
							<?php echo __('Enable'); ?>
						</label>
						<span class="help-block"><?php echo __('OPTIONAL: Check if you want to include header notifications, this will show up when the user logins on the top right corner.'); ?></span>
					</div>

					<?php if (!in_array($type, array(NOTIFICATION_TYPE_DEFAULT, NOTIFICATION_TYPE_REPORT))) : ?>
						<div class="form-group">
							<label class="control-label"><?php echo __('Feedback'); ?>:</label>
							<label class="checkbox">
								<?php
								echo $this->Form->input('NotificationSystem.' . $formKey . '.feedback', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform feedback-checkbox',
								));
								?>
								<?php echo __('Enable'); ?>
							</label>
							<span class="help-block"><?php echo __('Enabling this option will include on the email sent by the notification an URL that will allow uploading attachments and comments to the object that triggered this notification. <br><br>
								For example, if you are creating a notification that triggers 10 days before an audit due date, the comments and attachments uploaded will be tagged to the audit that trigger this notification.
								'); ?></span>
						</div>

						<div class="form-group chase-interval" id="feedback-chase">
							<div class="row">
								<div class="col-md-6">
									<label class="control-label"><?php echo __('Chase Interval'); ?>:</label>
									<?php
									echo $this->Form->input('NotificationSystem.' . $formKey . '.chase_interval', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'min' => 1,
										'max' => 5,
										'required' => false,
										'default' => 1
									));
									?>
									<span class="help-block"><?php echo __('Set how many days eramba will wait for feedback before it sends a reminder.'); ?></span>
								</div>

								<div class="col-md-6">
									<label class="control-label"><?php echo __('Chase Reminders'); ?>:</label>
									<?php
									echo $this->Form->input('NotificationSystem.' . $formKey . '.chase_amount', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'min' => 0,
										'max' => 15,
										'required' => false,
										'default' => 0
									));
									?>
									<span class="help-block"><?php echo __('Set how many reminders eramba will send. Once feedback has been provided no more emails will be sent.'); ?></span>
								</div>
							</div>
						</div>
					<?php endif; ?>

					<?php if (in_array($type, array(NOTIFICATION_TYPE_AWARENESS))) : ?>
						<div class="form-group chase-interval">
							<div class="row">
								<label class="control-label col-md-12"><?php echo __('Period'); ?>:</label>
								<?php
								echo $this->Form->input('NotificationSystem.' . $formKey . '.trigger_period', array(
									'label' => false,
									'div' => 'col-md-4',
									'class' => 'form-control',
									'min' => 0,
									'max' => 365
								));
								?>
							</div>
							<span class="help-block"><?php echo __('Awareness notifications are triggered at recurrent periods of time. Choose the amount of days in between notifications.'); ?></span>

							<?php echo $triggerPeriodHelper; ?>
						</div>
					<?php endif; ?>

					<div class="form-group <?php if ($isReport) echo 'hidden'; ?>">
						<label class="control-label"><?php echo __('Automated notification'); ?>:</label>
						<label class="checkbox">
							<?php
							echo $this->Form->input('NotificationSystem.' . $formKey . '.automated', array(
								'type' => 'checkbox',
								'label' => false,
								'div' => false,
								'class' => 'uniform',
								'default' => empty($isReport) ? true : false
							));
							?>
							<?php echo __('Enable'); ?>
						</label>
						<span class="help-block"><?php echo __('DEFAULT: By default NEW notifications are not tagged to any object, you must do that once you save them. If you checkbox this option all new objects in this section will have this notification tagged.'); ?></span>
					</div>
				</div>

				<?php if ($type == NOTIFICATION_TYPE_REPORT) : ?>
					<div class="<?php if (!empty($customizedEmail)) echo 'form-group-distinct'; ?>">
						<div class="form-group">
							<label class="control-label"><?php echo __('Filter'); ?>:</label>
							<?php
							echo $this->Form->input('NotificationSystem.' . $formKey . '.advanced_filter_id', array(
								// 'options' => NotificationSystem::reportAttachmentTypes(),
								'label' => false,
								'div' => false,
								'class' => 'select2-custom full-width-fix select2-offscreen'
							));
							?>
							<span class="help-block"><?php echo __('Select from the drop down an existing saved filter for this section. If this drop down is empty then you must create and save a filter for this section.'); ?></span>
						</div>

						<div class="form-group">
							<label class="control-label"><?php echo __('Day Period'); ?>:</label>
							<?php
							echo $this->Form->input('NotificationSystem.' . $formKey . '.trigger_period', array(
								'label' => false,
								'class' => 'form-control',
								'min' => 0,
								'max' => 365,
								'default' => 1
							));
							?>
							<span class="help-block"><?php echo __('Define how often you want this notification to trigger (in days).'); ?></span>

							<?php echo $triggerPeriodHelper; ?>
						</div>

						<div class="form-group">
							<label class="control-label"><?php echo __('Attachment Type'); ?>:</label>
							<?php
							App::uses('NotificationSystem', 'Model');

							echo $this->Form->input('NotificationSystem.' . $formKey . '.report_attachment_type', array(
								'options' => NotificationSystem::reportAttachmentTypes(),
								'label' => false,
								'div' => false,
								'class' => 'select2-custom full-width-fix select2-offscreen',
								'default' => NotificationSystem::REPORT_ATTACHEMENT_BOTH
							));
							?>
							<span class="help-block"><?php echo __('Choose a type of email attachment for this notification.'); ?></span>
						</div>

						<div class="form-group">
							<label class="control-label"><?php echo __('Skip Empty Results'); ?>:</label>
							<label class="checkbox">
								<?php
								echo $this->Form->input('NotificationSystem.' . $formKey . '.report_send_empty_results', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'default' => true
								));
								?>
								<?php echo __('Enable'); ?>
							</label>
							<span class="help-block"><?php echo __('Dont send notifications with empty results.'); ?></span>
						</div>
					</div>
				<?php endif; ?>

				<?php if (!empty($customizedEmail)) : ?>
					<div>
						<div class="form-group">
							<label class="control-label"><?php echo __('Customized Email'); ?>:</label>
							<label class="checkbox">
								<?php
								echo $this->Form->input('NotificationSystem.' . $formKey . '.email_customized', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform customized-email-checkbox'
								));
								?>
								<?php echo __('Enable'); ?>
							</label>
							<span class="help-block"><?php echo __('OPTIONAL: By default eramba uses very simple, enligsh email templates on its emails. By clicking in this checkbox you are allowed to cutomize the notification email subject and body.'); ?></span>
						</div>

						<div class="customized-email-wrapper" style="display:none;">
							<div class="form-group">
								<label class="control-label"><?php echo __('Email Subject'); ?>:</label>
								<?php
								echo $this->Form->input('NotificationSystem.' . $formKey . '.email_subject', array(
									'label' => false,
									'div' => false,
									'class' => 'form-control'
								));
								?>
								<span class="help-block"><?php echo __('Define custom email subject.'); ?></span>
							</div>

							<div class="form-group">
								<label class="control-label"><?php echo __('Email Body'); ?>:</label>
								<?php
								echo $this->Form->input('NotificationSystem.' . $formKey . '.email_body', array(
									'label' => false,
									'div' => false,
									'class' => 'form-control'
								));
								?>
								<span class="help-block">
									<?php
									echo __('Define custom email body.');

									if (!$isReport) {
										echo $this->element(NOTIFICATION_SYSTEM_ELEMENT_PATH . 'macros_list');
									}
									?>
								</span>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>