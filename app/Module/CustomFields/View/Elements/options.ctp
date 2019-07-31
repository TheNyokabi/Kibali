<?php
$labels = null;
if (isset($this->request->data['CustomFieldOption'][0])) {
	$labelsArr = array();
	foreach ($this->request->data['CustomFieldOption'] as $item) {
		$labelsArr[] = $item['value'];
	}

	$labels = implode(',', $labelsArr);
}

if (!empty($labels)) {
	$this->request->data['CustomFieldOption']['options'] = $labels;
}

if (!isset($placeholder)) {
	$placeholder = __('Add an option');
}

echo $this->Form->input('CustomFieldOption.options', array(
	'type' => 'hidden',
	'label' => false,
	'div' => false,
	'class' => 'tags-options col-md-12 full-width-fix',
	'multiple' => true,
	'data-placeholder' => $placeholder
));
?>

<script type="text/javascript">
jQuery(function($) {
	<?php if (isset($options) && !empty($options)) : ?>
		var obj = $.parseJSON('<?php echo $this->Eramba->jsonEncode([]); ?>');
	<?php else : ?>
		var obj = $.parseJSON('<?php echo $this->Eramba->jsonEncode(array()); ?>');
	<?php endif; ?>

	$('.tags-options').select2({
		tags: obj
	});
})
</script>