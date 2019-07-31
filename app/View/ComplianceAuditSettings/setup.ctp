<div class="row">
	<div class="col-lg-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
				echo $this->Form->create('ComplianceAuditSetting', array(
					'url' => array('controller' => 'complianceAuditSettings', 'action' => 'setup', $auditId, $compliancePackageItemId),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));

				$submitLabel = __('Add');
				if (isset($this->data['ComplianceAuditSetting']['id'])) {
					echo $this->Form->input('id', array('type' => 'hidden'));
					$submitLabel = __('Edit');
				}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __('Status'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('status', array(
										'options' => $statuses,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Select status')
									));
									?>
									<span class="help-block"><?php echo __('Define the phase in which this status is: No Evidence Needed, Evidence Provided, Waiting for Evidence.'); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Auditee'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('ComplianceAuditSetting.Auditee', array(
										'options' => $users,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12',
										'multiple' => true
									));
									?>
									<span class="help-block"><?php echo __('If you chose "Evidence Needed" or "Waiting for Evidence" you should select one or more individuals in this list that will receive notifications asking them to provide evidence.'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'users', 'action' => 'add'),'text' => __('Add User'))); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Feedback Profile' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'compliance_audit_feedback_profile_id', array(
										'options' => $feedbackProfiles,
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('None')
									) ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit($submitLabel, array(
						'class' => 'btn btn-primary',
						'div' => false
					)); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('ComplianceAuditSetting');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ComplianceAuditSetting',
			'id' => isset($edit) ? $this->data['ComplianceAuditSetting']['id'] : null
		));
		?>
	</div>
</div>
