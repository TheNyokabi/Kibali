<?php
echo $this->Form->create('ComplianceAuditNotification', array(
	'url' => array('controller' => 'complianceAuditSettings', 'action' => 'sendNotifications', $auditId)
));
?>

<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<?php
				echo $this->Form->submit(__('Send'), array(
					'class' => 'btn btn-primary',
					'div' => false
				));

				echo $this->Html->link(__('Cancel'), array(
					'controller' => 'complianceAudits',
					'action' => 'index'
				), array(
					'class' => 'btn btn-inverse'
				));
				?>
			</div>
		</div>
	</div>

</div>

<div class="row">

	<div class="col-md-12">

		<div class="widget box">
			<div class="widget-header">
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-down"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content">
				<?php if (!empty($data)) : ?>
					<table class="table table-hover table-striped">
						<thead>
							<tr>
								<th class="hidden">
									<?php
									echo $this->Form->input('ComplianceAuditNotification.checkAll', array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'class' => 'uniform',
										'checked' => true,
										'id' => 'check-all-checkbox'
									));
									?>
								</th>
								<th><?php echo __( 'Item ID' ); ?></th>
								<th><?php echo __( 'Item Name' ); ?></th>
								<th class="align-center"><?php echo __('Audit Status') ; ?></th>
								<th><?php echo __('Auditee') ; ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $key => $item) : ?>
							<tr>
								<td class="hidden">
									<?php
									echo $this->Form->input('ComplianceAuditNotification.send.' . $key, array(
										'type' => 'checkbox',
										'label' => false,
										'div' => false,
										'class' => 'uniform notification-checkbox',
										'value' => $item['ComplianceAuditSetting']['id'],
										'hiddenField' => false
									));
									?>
								</td>
								<td><?php echo $item['CompliancePackageItem']['item_id']; ?></td>
								<td>
									<div class="bs-popover"
										data-trigger="hover"
										data-placement="top"
										data-original-title="<?php echo __('Description'); ?>"
										data-content='<?php echo $item['CompliancePackageItem']['description']; ?>'>

										<?php echo $item['CompliancePackageItem']['name']; ?> 
										<i class="icon-info-sign"></i>
									</div>
								</td>
								<td class="align-center">
									<?php $this->ComplianceAudits->statusLabels($item['ComplianceAuditSetting']); ?>
								</td>
								<td>
									<?php
									$auditees = array();
									if (!empty($item['Auditee'])) {
										foreach ($item['Auditee'] as $auditee) {
											$auditees[] = $auditee['name'] . ' ' . $auditee['surname'];
										}
									}

									echo implode(', ', $auditees);
									?>
								</td>
							</tr>
							<?php endforeach ; ?>
						</tbody>
					</table>
				<?php endif; ?>

			</div>
		</div>

	</div>

</div>

<?php echo $this->Form->end(); ?>

<script type="text/javascript">
jQuery(function($) {
	$("#check-all-checkbox").on("change", function(e) {
		if ($(this).is(":checked")) {
			$(".notification-checkbox").prop("checked", true);
		}
		else {
			$(".notification-checkbox").prop("checked", false);
		}

		$.uniform.update();
	}).trigger("change");

	$(".notification-checkbox").on("change", function(e) {
		var checked = $(".notification-checkbox:checked").length;
		if (checked < $(".notification-checkbox").length) {
			$("#check-all-checkbox").prop("checked", false);

			$.uniform.update('#check-all-checkbox');
		}
		else if(checked == $(".notification-checkbox").length) {
			$("#check-all-checkbox").prop("checked", true);

			$.uniform.update('#check-all-checkbox');
		}
	});
});
</script>