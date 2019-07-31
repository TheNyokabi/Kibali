<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('ComplianceAuditDelete', array(
					'url' => array('controller' => 'complianceAudits', 'action' => 'delete', $data['ComplianceAudit']['id']),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));

				echo $this->Form->input('_delete', array(
					'type' => 'hidden',
					'value' => 1
				));
				?>

				<div class="alert alert-danger">
					<?php
					echo __('Are you really sure you want to delete item: <strong>%s</strong>?', $data['ComplianceAudit']['name']);
					?>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit(__('Delete'), array(
						'class' => 'btn btn-danger',
						'div' => false
					)); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('ComplianceAudit');
					?>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>