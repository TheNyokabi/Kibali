<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>

					<?php
					// echo $this->Html->link( '<i class="icon-cog"></i>' . __('Workflow'), array(
					// 	'controller' => 'workflows',
					// 	'action' => 'edit',
					// 	$workflowSettingsId
					// ), array(
					// 	'class' => 'btn',
					// 	'escape' => false
					// ));
					?>
				</div>

				<?php echo $this->NotificationSystem->getIndexLink('Legal'); ?>

				<?php echo $this->Video->getVideoLink('Legal'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>

				<?php
				echo $this->CustomFields->getIndexLink(array(
					'Legal' => ClassRegistry::init('Legal')->label(['singular' => true]),
				));
				?>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'Legal')); ?>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<?php if ( ! empty( $data ) ) : ?>
				<table class="table table-hover table-striped table-bordered table-highlight-head">
					<thead>
						<tr>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Provide a descriptive name for the liability. Examples are: Sarbanes Oxley, Personal Data Act, HIPAA, Etc.' ); ?>'>
									<?php echo $this->Paginator->sort( 'Legal.name', __( 'Name' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Provide a brief description of the liability and how it affects your business.' ); ?>'>
									<?php echo $this->Paginator->sort( 'Legal.description', __( 'Description' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<?php echo __('Legal Owner'); ?>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'This value will automatically increase the Risk levels (by multiplicating the Risk Score with this value) every time this Liability is assigned to an Asset or Third Party. This is used to increase the visibility of this Risks.' ); ?>'>
									<?php echo $this->Paginator->sort( 'Legal.risk_magnifier', __( 'Risk Magnifier' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Use these icons in order to view the details of this object, system records such as when the item was created or modified, add or review comments or simply delete the item.' ); ?>'>
							<?php echo __( 'Actions' ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<?php /*
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Workflows define the approvals required to create, modify or delete an item. Approved items are valid throughout the system, Draft items require approval and Pending Approvals or Validations means that the workflow is still in process and is pending user interaction.' ); ?>'>
							<?php echo __( 'Workflows' ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							*/ ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $entry) : ?>
							<tr>
								<td><?php echo $entry['Legal']['name']; ?></td>
								<td><?php echo $this->Eramba->getEmptyValue($entry['Legal']['description']); ?></td>
								<td><?= $this->UserField->showUserFieldRecords($entry['LegalAdvisor']); ?></td>
								<td><?php echo $entry['Legal']['risk_magnifier']; ?></td>
								<td class="align-center">
									<?php
									echo $this->Ajax->getActionList($entry['Legal']['id'], array(
										'style' => 'icons',
										'notifications' => true,
										'item' => $entry,
										'history' => true,
										AppModule::instance('Visualisation')->getAlias() => true
									));
									?>
								</td>
								<?php /*
								<td class="text-center">
									<?php
									echo $this->element('workflow/action_buttons_1', array(
										'id' => $entry['Legal']['id'],
										'item' => $this->Workflow->getActions($entry['Legal'], $entry['WorkflowAcknowledgement'])
									));
									?>
								</td>
								*/ ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
			<?php else : ?>
				<?php echo $this->element( 'not_found', array(
					'message' => __( 'No Legal Constraints found.' )
				) ); ?>
			<?php endif; ?>

		</div>
	</div>

</div>
