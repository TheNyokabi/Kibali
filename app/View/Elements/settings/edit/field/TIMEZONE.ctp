<?php
$options = CakeTime::listTimezones();

$options['UTC Timezone'] = $options['UTC'];
unset($options['UTC']);

foreach ($options as $group => $values) {
	if (empty($values)) {
		continue;
	}

	foreach ($values as $tz => $val) {
		$options[$group][$tz] = sprintf('%s (%s)', $val, $tz);
	}
}

echo $this->Form->input($fieldName, array(
	'options' => $options,
	'default' => date_default_timezone_get(),
	'class' => 'select2',
	'style' => 'width:100%;',
	'label' => false,
	'div' => false,
	'id' => 'timezone-field'
));
?>
<br />
<br />

<div id="timezone-actual-time"></div>

<script type="text/javascript">
	jQuery(function($) {
		var $timezoneField = $("#timezone-field");
		var $timezoneContent = $("#timezone-actual-time");
		var $formContent = $(".widget-content");

		$timezoneField.on("change", function(e) {
			Eramba.Ajax.blockEle($formContent);
			$.get("/settings/getTimeByTimezone/?timezone=" + $(this).val(), function(data) {
				$timezoneContent.html(data);
				Eramba.Ajax.unblockEle($formContent);
			});
		}).trigger("change");
	});
</script>