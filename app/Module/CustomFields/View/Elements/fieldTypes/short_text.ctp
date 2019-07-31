<?php
echo $this->Form->input('CustomFieldValue.' . $field['id'] . '.value', array(
	'type' => 'text',
	'label' => false,
	'div' => false,
	'class' => 'form-control',
	'value' => $default
));
?>