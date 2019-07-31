<?php
$options = array();
foreach ($field['CustomFieldOption'] as $option) {
	$options[$option['id']] = $option['value']; 
}

echo $this->Form->input('CustomFieldValue.' . $field['id'] . '.value', array(
	'options' => $options,
	'label' => false,
	'div' => false,
	'class' => 'form-control',
	'empty' => __('Choose one'),
	'selected' => $default
));
?>