<dl class="dl-horizontal">
	<dt><?php echo __('Item ID'); ?></dt>
	<dd>
		<?php
		echo $this->Eramba->getEmptyValue($data['CompliancePackageItem']['item_id']);
		?>
	</dd>
	<dt><?php echo __('Item Name'); ?></dt>
	<dd>
		<?php
		echo $this->Eramba->getEmptyValue($data['CompliancePackageItem']['name']);
		?>
	</dd>
	<dt><?php echo __('Item Description'); ?></dt>
	<dd>
		<?php
		echo $this->Eramba->getEmptyValue($data['CompliancePackageItem']['description']);
		?>
	</dd>
	<dt><?php echo __('Item Details'); ?></dt>
	<dd>
		<?php
		echo $this->Eramba->getEmptyValue($data['CompliancePackageItem']['audit_questionaire']);
		?>
	</dd>
</dl>