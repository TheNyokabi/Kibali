<?php
echo $this->Form->input('Attachment.' . $formKey . '.file', array(
	'type' => 'file',
	'label' => false,
	'class' => false,
	'div' => 'input file dynamic',
	'data-style' => 'fileinput'
));
?>