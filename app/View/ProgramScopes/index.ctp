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

				<?php echo $this->NotificationSystem->getIndexLink('ProgramScope'); ?>

				<?php echo $this->Video->getVideoLink('ProgramScope'); ?>
			</div>
		</div>
	</div>
</div>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'ProgramScope')); ?>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<?php if ( ! empty( $data ) ) : ?>
				<table class="table table-hover table-striped table-bordered table-highlight-head">
					<thead>
						<tr>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The version of the scope as this might change from time to time and is good idea to keep it versioned.' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramScope.version', __( 'Version' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The purpose of this document is to clearly define the boundaries of the Information Security Management System (ISMS).' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramScope.description', __( 'Description' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status of the scope can be Draft (is being developed), Current (Its the actual scope, there can onnly be one scope item as current) and Discarded (the scope is no longer in use)' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramScope.status', __( 'Status' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'You might want to see the records, upload files that support the scope definition, create notifications or delete it all together.' ); ?>'>
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
						<?php foreach ($data as $item) : ?>
							<tr>
								<td><?php echo $item['ProgramScope']['version']; ?></td>
								<td>
									<?php if (!empty($item['ProgramScope']['description'])) : ?>
										<div class="bs-popover"
											data-trigger="hover"
											data-placement="top"
											data-html="true"
											data-original-title="<?php echo __('Description'); ?>"
											data-content='<?php echo nl2br(h($item['ProgramScope']['description'])); ?>'>

											<?php echo $this->Text->truncate($item['ProgramScope']['description'], 20); ?>
											<i class="icon-info-sign"></i>
										</div>
									<?php endif; ?>
								</td>
								<td><?php echo getProgramScopeStatuses($item['ProgramScope']['status']); ?></td>
								<td class="align-center">
									<?php
									$exportUrl = array(
										'action' => 'exportPdf',
										$item['ProgramScope']['id']
									);

									$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

									echo $this->Ajax->getActionList($item['ProgramScope']['id'], array(
										'style' => 'icons',
										'notifications' => true,
										'item' => $item
									));
									?>
								</td>
								<?php /*
								<td class="text-center">
									<?php
									echo $this->element('workflow/action_buttons_1', array(
										'id' => $item['ProgramScope']['id'],
										'item' => $this->Workflow->getActions($item['ProgramScope'], $item['WorkflowAcknowledgement'])
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
				<?php
				echo $this->element('not_found', array(
					'message' => __('No Scopes found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>

</div>
