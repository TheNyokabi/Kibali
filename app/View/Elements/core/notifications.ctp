<?php
if (!isset($newNotifications)) {
	return false;
}

$count = count($newNotifications);
?>
<li id="notification-dropdown" class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="icon-warning-sign"></i>
		<?php if (!empty($newNotifications)) : ?>
			<span class="badge"><?php echo $count; ?></span>
		<?php endif; ?>
	</a>
	<ul class="dropdown-menu extended notification">
		<?php if (empty($newNotifications)) : ?>
			<li class="title">
				<p><?php echo __('You have no new notifications'); ?></p>
			</li>
		<?php else : ?>
			<li class="title">
				<p><?php echo sprintf(__n('You have %d new notification', 'You have %d new notifications', $count), $count); ?></p>
			</li>
			<?php foreach ($newNotifications as $notification) : ?>
				<li>
					<a href="<?php echo $notification['Notification']['url']; ?>">
						<span class="message"><?php echo $notification['Notification']['title']; ?></span>
						<span class="time">
							<?php
							echo CakeTime::timeAgoInWords($notification['Notification']['created'], array(
								'accuracy' => array('day' => 'day', 'hour' => 'hour')
							));
							?>
						</span>
					</a>
				</li>
			<?php endforeach; ?>

			<!-- <li>
				<a href="javascript:void(0);">
					<span class="label label-success"><i class="icon-plus"></i></span>
					<span class="message">New user registration.</span>
					<span class="time">1 mins</span>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);">
					<span class="label label-danger"><i class="icon-warning-sign"></i></span>
					<span class="message">High CPU load on cluster #2.</span>
					<span class="time">5 mins</span>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);">
					<span class="label label-success"><i class="icon-plus"></i></span>
					<span class="message">New user registration.</span>
					<span class="time">10 mins</span>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);">
					<span class="label label-info"><i class="icon-bullhorn"></i></span>
					<span class="message">New items are in queue.</span>
					<span class="time">25 mins</span>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);">
					<span class="label label-warning"><i class="icon-bolt"></i></span>
					<span class="message">Disk space to 85% full.</span>
					<span class="time">55 mins</span>
				</a>
			</li> -->
		<?php endif; ?>
		<li class="footer">
			<?php
			echo $this->Html->link(__('View all notifications'), array(
				'controller' => 'pages',
				'action' => 'dashboard'
			))
			?>
		</li>
	</ul>
</li>