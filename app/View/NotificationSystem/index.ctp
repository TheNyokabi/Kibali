<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php
					// echo $this->Ajax->addAction();
					echo $this->Html->link('<i class="icon-pencil"></i>' . __('Manage'), array(
						'controller' => 'notificationSystem',
						'action' => 'attach',
						$model
					), array(
						'escape' => false,
						'class' => 'btn'
					))
					?>
				</div>

				<?php echo $this->Video->getVideoLink('NotificationSystem'); ?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<?php if (!empty($rows) && !empty($notifications)) : ?>
				<table class="table table-hover table-bordered table-highlight-head table-larger-head">
					<thead>
						<tr>
							<th class="text-center"><?php echo __('Objects'); ?></th>
							<th colspan="<?php echo count($notifications); ?>" class="text-center">
								<?php echo __('Notifications'); ?>
							</th>
						</tr>
						<tr>
							<th>
								<?php echo __('Title'); ?>
							</th>
							<?php foreach ($notifications as $notification) : ?>
								<?php
								$id = $notification['NotificationSystem']['id'];
								$type = $notification['NotificationSystem']['type'];
								?>
								<th class="align-center">
									<?php if (!empty($notification['NotificationSystem']['automated'])) : ?>
										<span class="bs-popover"
											data-trigger="hover"
											data-placement="top"
											data-original-title="<?php echo __('Automated notification'); ?>"
											data-content='<?php echo __('This notification is automatically applied to all new items.'); ?>'>

											<?php echo $notification['NotificationSystem']['name']; ?>
											<i class="icon-info-sign"></i>
										</span>
									<?php elseif ($type == NOTIFICATION_TYPE_REPORT) : ?>
										<span class="bs-popover"
											data-trigger="hover"
											data-placement="top"
											data-original-title="<?php echo __('Report Notification'); ?>"
											data-content='<?php echo __('This is a Report Notification where actions are not applicable.'); ?>'>

											<?php echo $notification['NotificationSystem']['name']; ?>
											<i class="icon-info-sign"></i>
										</span>
									<?php else : ?>
										<?php echo $notification['NotificationSystem']['name']; ?>
									<?php endif; ?>

									<?php
									if ($type != NOTIFICATION_TYPE_REPORT) {
										$associateUrl = array(
											'action' => 'associateForAll',
											$id,
											$model
										);

										$enableUrl = array(
											'action' => 'enableForAll',
											$id,
											$model
										);

										$disableUrl = array(
											'action' => 'disableForAll',
											$id,
											$model
										);

										$this->Ajax->addToActionList(__('Associate for All'), $associateUrl, 'plus-sign', false);
										$this->Ajax->addToActionList(__('Enable for All'), $enableUrl, 'ok', false);
										$this->Ajax->addToActionList(__('Disable for All'), $disableUrl, 'remove', false);

										echo $this->Ajax->getActionList(999, array(
											'style' => 'icons',
											'listClass' => 'table-controls nested-actions pull-right',
											'edit' => false,
											'trash' => false,
											'comments' => false,
											'records' => false,
											'attachments' => false,
											'controller' => 'notificationSystem',
											'model' => 'NotificationSystem'
										));
									}
									?>
								</th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($rows as $row) : ?>
							<tr>
								<td>
									<?php
									if (isset($row[$model]['_customTitle']) && !empty($row[$model]['_customTitle'])) {
										echo $row[$model]['_customTitle'];
									}
									else {
										echo !empty($titleColumn) ? $row[$model][$titleColumn] : $row[$model]['id'];
									}
									?>
								</td>

								<?php foreach ($notifications as $notification) : ?>

									<td>
										<?php
										$id = $notification['NotificationSystem']['id'];
										$type = $notification['NotificationSystem']['type'];
										?>

										<?php if ($type == NOTIFICATION_TYPE_REPORT) : ?>
											<span class="label label-default"><?php echo __('Not Applicable'); ?></span>
											<?php
											continue;
											?>
										<?php endif; ?>

										<?php
										$removeUrl = array('action' => 'remove', $id, $model, $row[$model]['id']);
										$disableForObject = array('action' => 'disableForObject', $id, $model, $row[$model]['id']);
										$enableForObject = array('action' => 'enableForObject', $id, $model, $row[$model]['id']);
										$associateUrl = array('action' => 'associateForObject', $id, $model, $row[$model]['id']);
										?>

										<?php if (isset($row[$model]['notificationIds'][$id]) && $row[$model]['notificationIds'][$id] == 'enabled') : ?>
									
											<?php
											$this->Ajax->addToActionList(__('Disable'), $disableForObject, 'remove', false);
											$this->Ajax->addToActionList(__('Remove'), $removeUrl, 'trash', false);
											?>
										
										<?php elseif (isset($row[$model]['notificationIds'][$id]) && $row[$model]['notificationIds'][$id] == 'disabled') : ?>
											
											<?php
											$this->Ajax->addToActionList(__('Enable'), $enableForObject, 'ok', false);
											$this->Ajax->addToActionList(__('Remove'), $removeUrl, 'trash', false);
											?>
										
										<?php else : ?>
											<?php
											$this->Ajax->addToActionList(__('Associate'), $associateUrl, 'plus-sign', false);
											?>
										<?php endif; ?>

										<?php
										if (isset($row['NotificationObject'][$id])) :
											echo $this->NotificationObjects->getStatuses($row['NotificationObject'][$id]);
										else : ?>
											<span class="label label-default"><?php echo __('Not Associated'); ?></span>
										<?php endif;

										echo $this->Ajax->getUserDefinedActionList(array(
											'listClass' => 'table-controls nested-actions pull-right'
										));
										?>

									</td>

								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else : ?>
				<?php
				echo $this->element('not_found', array(
					'message' => __('Notifications and/or related objects that could be associated not found.')
				));
				?>
			<?php endif; ?>

			<?php
			echo $this->element(CORE_ELEMENT_PATH . 'pagination', array(
				'url' => $backUrl
			));
			?>
		</div>
	</div>

</div>