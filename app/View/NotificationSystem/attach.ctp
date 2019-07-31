<?php
echo $this->Form->create('NotificationSystem', array(
	'url' => array('controller' => 'notificationSystem', 'action' => 'attach', $model),
	'novalidate' => 'novalidate'
));
?>

<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php
					$disabled = false;
					if (empty($typeOptions[NOTIFICATION_TYPE_WARNING])) {
						$disabled = true;
					}
					?>
					<button type="button" id="add-warning-btn" class="btn" <?php echo $disabled ? 'disabled="disabled"' : ''; ?>>
						<i class="icon-plus-sign"></i><?php echo __('Add Warning'); ?>
					</button>

					<?php
					$disabled = false;
					if (empty($typeOptions[NOTIFICATION_TYPE_AWARENESS])) {
						$disabled = true;
					}
					?>
					<button type="button" id="add-awareness-btn" class="btn" <?php echo $disabled ? 'disabled="disabled"' : ''; ?>>
						<i class="icon-plus-sign"></i><?php echo __('Add Awareness'); ?>
					</button>

					<?php
					$disabled = false;
					if (empty($typeOptions[NOTIFICATION_TYPE_DEFAULT])) {
						$disabled = true;
					}
					?>
					<button type="button" id="add-default-btn" class="btn" <?php echo $disabled ? 'disabled="disabled"' : ''; ?>>
						<i class="icon-plus-sign"></i><?php echo __('Add Default'); ?>
					</button>

					<?php
					$disabled = false;
					if (empty($typeOptions[NOTIFICATION_TYPE_REPORT])) {
						$disabled = true;
					}
					?>
					<button type="button" id="add-report-btn" class="btn" <?php echo $disabled ? 'disabled="disabled"' : ''; ?>>
						<i class="icon-plus-sign"></i><?php echo __('Add Report'); ?>
					</button>
				</div>

				<?php
				echo $this->Form->submit(__('Save'), array(
					'class' => 'btn btn-primary',
					'div' => false
				));

				echo $this->Html->link(__('Back'), array('action' => 'index', $model), array(
					'class' => 'btn btn-inverse'
				));
				?>
			</div>
		</div>
	</div>

</div>

<div class="row" id="notifications-container">
	<?php
	$formKey = 0;
	if (isset($this->data['NotificationSystem']) && !empty($this->data['NotificationSystem'])) {
		foreach ($this->data['NotificationSystem'] as $key => $item) {
			$options = $typeOptions[$item['type']];

			echo $this->element('../NotificationSystem/notification_item', array(
				'formKey' => $key,
				'notificationOptions' => $options,
				'type' => $item['type']
			));

			$formKey = $key;
		}
	}
	?>
</div>

<?php echo $this->Form->end(); ?>

<script type="text/javascript">
jQuery(function($) {
	var formKey = <?php echo $formKey; ?>;
	var model = "<?php echo $model; ?>";

	$("#add-warning-btn").on("click", function(e) {
		addNotification("warning");
	});

	$("#add-awareness-btn").on("click", function(e) {
		addNotification("awareness");
	});

	$("#add-default-btn").on("click", function(e) {
		addNotification("default");
	});

	$("#add-report-btn").on("click", function(e) {
		addNotification("report");
	});

	$("#notifications-container").on("change", ".feedback-checkbox", function(e) {
		if ($(this).is(":checked")) {
			$(this).closest(".form-group").next(".chase-interval").slideDown();
		}
		else {
			$(this).closest(".form-group").next(".chase-interval").slideUp();
		}
	}).trigger("change");

	$("#notifications-container").on("change", ".notification-file", function(e) {
		var $feedbackChase = $(this).parents(".notification-item").find("#feedback-chase");

		if ($feedbackChase.length) {
			if ($(this).children(":selected").data("default-type")) {
				// $feedbackChase.addClass("hidden");
				setHelp($feedbackChase, "<?php echo __('This Notification does not support Chase option.'); ?>")
			}
			else {
				hideHelp($feedbackChase);
				// $feedbackChase.removeClass("hidden");
			}
		}

		disableNotificationFileSelect();
	}).trigger("change");

	$("#notifications-container").on("change", ".customized-email-checkbox", function(e) {
		var $customizedEmailWrapper = $(this).parents(".notification-item").find('.customized-email-wrapper');
		
		if ($(this).is(":checked")) {
			$customizedEmailWrapper.slideDown();
		}
		else {
			$customizedEmailWrapper.slideUp();
		}
	}).trigger("change");

	$(window).on("load", function(e) {
		$(".feedback-checkbox").trigger("change");
		$(".notification-file").trigger("change");
		$(".customized-email-checkbox").trigger("change");
	});

	function addNotification(type) {
		formKey++;

		$.ajax({
			type: "POST",
			dataType: "html",
			async: true,
			url: "/notificationSystem/addNotification",
			data: {formKey: formKey, type: type, model: model}
		})
		.done(function(html) {
			var ele = $(html);
			ele.appendTo("#notifications-container");
			ele.focus();
			ele.removeClass("masked");
			initPlugins(true);
			$("#notifications-container .feedback-checkbox").trigger("change");
			$("#notifications-container .customized-email-checkbox").trigger("change");
			disableNotificationFileSelect()
		});
	}

	function disableNotificationFileSelect() {
		$(".notification-file").each(function(i, e) {
			if ($(this).data("readonly")) {
				$(this).select2("readonly", true);
			}
		});
	}

	function initPlugins(loadLast) {
		var selector = $("#notifications-container");
		if (loadLast) {
			selector = $("#notifications-container > div:last");
		}
		var sel2 = selector.find(".select2-custom");
		sel2.select2({
			width: 'resolve',
			placeholder: "<?php echo __('Select a notification'); ?>",
			minimumResultsForSearch: -1,
			formatSelection: function(item, container) {
				var desc = $(item.element).data("description");
				var deprecated = $(item.element).data("deprecated");

				var label = item.text;

				if (deprecated) {
					label += " <span class='select2-deprecated-notice'>(<?php echo __('Deprecated'); ?>)</span>";
				}

				// if (desc) {
				// 	label += ' <br /><small>(' + desc + ')</small>';
				// }

				$(container).append(label);
			},
			formatResult: function(item) {
				var desc = $(item.element).data("description");
				var deprecated = $(item.element).data("deprecated");

				var ret = item.text;

				if (deprecated) {
					if (typeof deprecated == "boolean" || deprecated == "1") {
						ret += " <span class='select2-deprecated-notice'>(<?php echo __('Deprecated'); ?>)</span>";;
					}
					else {
						ret += ' <br /><span class="select2-deprecated-notice"><?php echo __('Deprecated'); ?>: ' + deprecated + '</span>';
					}
				}

				if (desc) {
					ret += ' <br /><small>(' + desc + ')</small>';
				}

				return ret;
			}
		});

		selector.find("select.select2").select2();

		selector.find(".emails").tagsInput({
			width: '100%',
			height: 'auto',
			defaultText: "<?php echo __('add an email'); ?>"
		});

		selector.find(':radio.uniform, :checkbox.uniform').uniform();
	}

	initPlugins(false);

	$("#notifications-container").on("click", ".remove-notification-btn", function(e) {
		var $item = $(this).closest(".notification-item");

		// close possible stuck select2 dropdowns
		$item.find("select").filter(function(){return $(this).data("select2")}).select2("close");

		// hide the box
		$item
			.addClass("hide-item")
			.one(whichTransitionEvent(), function(event) {
				$(this).remove();
			});

		e.preventDefault();
	});
});
</script>
