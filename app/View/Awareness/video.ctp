<div class="awareness-wrapper">
	<?php if ( ! $trainingAllowed ) : ?>
		<h2 class="awareness-title"><?php echo __('No trainings left for you!'); ?></h2>
		<h3 class="awareness-subtitle"><?php echo __('Just hold tight until the time comes and your trainings re-start'); ?></h3>
	<?php else : ?>
		<div class="headings">
			<h2 class="awareness-title"><?php echo $program['AwarenessProgram']['welcome_text']; ?></h2>
			<h3 class="awareness-subtitle"><?php echo $program['AwarenessProgram']['welcome_sub_text']; ?></h3>
		</div>

		<script>
		videojs.options.flash.swf = "/video-js.swf";

		jQuery(function($) {
			var myPlayer = videojs("video_1");
			myPlayer.volume(0.5);

			$(".next-button-wrapper").tooltip();

			var duration = 0;
			myPlayer.one("durationchange", function() {
				duration = myPlayer.duration();
			});

			myPlayer.one("play", function() {
				myPlayer.on("timeupdate", function() {
					var currentTime = myPlayer.currentTime();

					if (currentTime == duration)
					{
						$(".next-button-wrapper input[type=submit]").removeAttr("disabled");
						$("#video-seen").val(1);
						$(".next-button-wrapper").tooltip('destroy');
					}

				});
			});
		});
		</script>
		<div class="video-wrapper">
			<video id="video_1" class="video-js vjs-default-skin" controls preload="none" width="640" height="300"
				data-setup="{}">
				<source src="<?php echo Router::url(array('controller' => 'awareness', 'action' => 'downloadStepFile', $program['AwarenessProgram']['id'], 'video')); ?>" type='<?php echo $program['AwarenessProgram']['video_mime_type']; ?>' />
				<!-- <source src="<?php //echo $videoUrl; ?>.webm" type="video/webm" />
				<source src="<?php //echo $videoUrl; ?>.ogv" type="video/ogg" /> -->
			</video>
		</div>

		<div class="clearfix"></div>

		<div class="next-button-wrapper" data-toggle="tooltip" data-placement="top" title="<?php echo __('You must watch the video'); ?>">
			<?php
			echo $this->Form->create('AwarenessTrainingVideo', array(
				'url' => array('controller' => 'awareness', 'action' => 'video'),
				'class' => 'form-horizontal row-border',
				'novalidate' => true
			));

			echo $this->Form->input('video_seen', array(
				'type' => 'hidden',
				'id' => 'video-seen',
				'value' => 0
			));

			echo $this->Form->submit(__('Understood'), array(
				'class' => 'btn btn-danger btn-lg',
				'div' => false,
				'disabled' => true
			));
			
			echo $this->Form->end();
			?>
		</div>
	<?php endif; ?>
</div>