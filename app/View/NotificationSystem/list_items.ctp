<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<?php echo $this->Video->getVideoLink('NotificationSystem'); ?>
			</div>
		</div>
	</div>
</div>


<?php if (!empty($data)) : ?>
	<?php foreach ($data as $model => $notifications) : ?>
		<div class="widget box">
			<div class="widget-header">
				<h4><?php echo getModelLabel($model); ?></h4>
			</div>
			<div class="widget-subheader">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th><?php echo __('Name'); ?></th>
							<th><?php echo __('Notification'); ?></th>
							<th><?php echo __('Type'); ?></th>
							<th><?php echo __('Automated'); ?></th>
							<th><?php echo __('Status'); ?></th>
							<th><?php echo __('Created'); ?></th>
							<th class="text-center"><?php echo __('Actions'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($notifications as $notification) : ?>
							<tr>
								<td>
									<?php
									echo $notification['NotificationSystem']['name'];
									?>
								</td>
								<td>
									<?php
									echo $notification['NotificationSystem']['notification_title'];
									?>
									<br />
									<small>
										<?php
										echo $notification['NotificationSystem']['notification_description'];
										?>
									</small>
								</td>
								<td>
									<?php
									echo getNotificationTypes($notification['NotificationSystem']['type']);
									?>
								</td>
								<td>
									<?php
									if (!empty($notification['NotificationSystem']['automated'])) {
										echo __('Yes');
									}
									else {
										echo __('No');
									}
									?>
								</td>
								<td>
									<?php
									$associated = $notification[0]['associated'];
									$associatedLabel = sprintf(__n('%s item', '%s items', $associated), $associated);

									$enabled = $notification[0]['enabled'];
									$enabledLabel = sprintf(__n('%s item', '%s items', $enabled), $enabled);

									$disabled = $notification[0]['disabled'];
									$disabledLabel = sprintf(__n('%s item', '%s items', $disabled), $disabled);
									?>

									<?php echo __('Associated on %s', $associatedLabel); ?><br />
									<?php echo __('Enabled on %s', $enabledLabel); ?><br />
									<?php echo __('Disabled on %s', $disabledLabel); ?>
								</td>
								<td>
									<?php
									echo $notification['NotificationSystem']['created'];
									?>
								</td>
								<td class="text-center">
									<?php
									$editUrl = array(
										'controller' => 'notificationSystem',
										'action' => 'attach',
										$notification['NotificationSystem']['model']
									);
									$this->Ajax->addToActionList(__('Edit'), $editUrl, 'pencil', false);
									
									echo $this->Ajax->getUserDefinedActionList();
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endforeach; ?>
<?php else : ?>
	<?php
	echo $this->element('not_found', array(
		'message' => __('No Notifications found.')
	));
	?>
<?php endif; ?>