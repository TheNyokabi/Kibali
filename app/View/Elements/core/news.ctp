<li id="news-dropdown" class="dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="icon-envelope"></i>
		<?php if (!empty($unreadedNewsCount)) : ?>
			<span class="badge"><?php echo $unreadedNewsCount; ?></span>
		<?php endif; ?>
	</a>
	<ul class="dropdown-menu extended notification">
		<li class="title">
			<p><?php echo __('News'); ?></p>
		</li>
		<?php if (!empty($shortNews)) : ?>
			<?php foreach ($shortNews as $message) : ?>
				<li>
					<a href="<?php echo Router::url(array('plugin' => null, 'controller' => 'news', 'action' => 'index')); ?>">
						<span class="message"><?php echo $message['title']; ?></span>
						<span class="time">
							<?php
							echo CakeTime::timeAgoInWords($message['date'], array(
								'accuracy' => array('day' => 'day', 'hour' => 'day')
							));
							?>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		<li class="footer">
			<?php
			echo $this->Html->link(__('View all news'), array(
				'controller' => 'news',
				'action' => 'index'
			))
			?>
		</li>
	</ul>
</li>
<?php if (!empty($unreadedNewsCount)) : ?>
	<script type="text/javascript">
	$(function() {
		function sendReadResponse() {
			$.ajax({
				url: "<?php echo Router::url(array('plugin' => null, 'controller' => 'news', 'action' => 'markAsRead')); ?>",
			}).done(function(response) {
				$('#news-dropdown .dropdown-toggle .badge').hide();
			}).always(function() {
			});
		}

		$('#news-dropdown .dropdown-toggle').on('click', function() {
			sendReadResponse();
		});
	});
	</script>
<?php endif; ?>