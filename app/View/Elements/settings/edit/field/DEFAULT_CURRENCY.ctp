<?php
$customCurrencies = getCustomCurrencies();

$options = array();
foreach ($customCurrencies as $c => $currencyOpts) {
	$label = $c;

	if (!empty($currencyOpts['currency'])) {
		$label .= ' - ' . $currencyOpts['currency'];
	}

	$locations = false;
	if (!empty($currencyOpts['locations'])) {
		$locations = implode(', ', $currencyOpts['locations']);
	}
	
	$options[$c] = array(
		'name' => $label,
		'value' => $c,
		'data-locations' => $locations
	);
}
array_unshift($options, array('' => ''));

echo $this->Form->input($fieldName, array(
	'options' => $options,
	'default' => !empty($setting['value']) ? $setting['value'] : $setting['default_value'],
	'class' => 'select2-currency',
	'style' => 'width:100%;',
	'label' => false,
	'div' => false
));
?>

<script type="text/javascript">
	jQuery(function($) {
		var sel2 = $(".select2-currency");
		sel2.select2({
			width: 'resolve',
			placeholder: "<?php echo __('Choose a currency for the system'); ?>",
			formatSelection: function(item, container) {
				var locations = $(item.element).data("locations");

				var label = item.text + "<span class='hidden'>" + locations + "</span>";
				$(container).append(label);
			},
			formatResult: function(item) {
				var locations = $(item.element).data("locations");

				var ret = item.text;

				if (locations) {
					ret += ' <br /><small>' + locations + '</small>';
				}

				return ret;
			}
		});
	});
</script>