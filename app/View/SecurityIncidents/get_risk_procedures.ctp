<?php if (!empty($data)) : ?>
	<?php
	$policyNames = array();
	foreach ($data as $policy) {
		$policyNames[] = $policy['SecurityPolicy']['index'];
	}
	?>

	<div class="alert alert-danger"><i class="icon-exclamation-sign"></i> 
		<?php
		$names = implode(', ', $policyNames);
		echo __('The Risk you have selected contains the following incident handling procedure: %s', $names);
		?> 
	</div>
<?php endif; ?>
