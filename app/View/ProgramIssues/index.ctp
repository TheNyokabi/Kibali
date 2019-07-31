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

				<?php echo $this->NotificationSystem->getIndexLink('ProgramIssue'); ?>

				<?php echo $this->Video->getVideoLink('ProgramIssue'); ?>

				<?php
				echo $this->CustomFields->getIndexLink(array(
					'ProgramIssue' => ClassRegistry::init('ProgramIssue')->label(['singular' => true]),
				));
				?>
			</div>
		</div>
	</div>
</div>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'ProgramIssue')); ?>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<?php if ( ! empty( $data ) ) : ?>
				<table class="table table-hover table-striped table-bordered table-highlight-head">
					<thead>
						<tr>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The issue is usually internal or external in terms of its context' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramIssue.name', __( 'Issue Source' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The type of issue is a classification of the issues' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramIssue.name', __( 'Type' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the identified issue' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramIssue.name', __( 'Name' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A description of the issue.' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramIssue.description', __( 'Description' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status can be one of the following Draft (the issue is being developed), Discarded (the issue is not applicable any longer), Current (the issue is applicable).' ); ?>'>
									<?php echo $this->Paginator->sort( 'ProgramIssue.status', __( 'Status' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'You might want to attach documents that support the identified issue, delete, edit or review its records.' ); ?>'>
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
								<td><?php echo getProgramIssueSources($item['ProgramIssue']['issue_source']); ?></td>
								<td>
									<?php
									echo $this->ProgramIssues->getItemTypes($item);
									?>
								</td>
								<td><?php echo $item['ProgramIssue']['name']; ?></td>
								<td>
									<?php if (!empty($item['ProgramIssue']['description'])) : ?>
										<div class="bs-popover"
											data-trigger="hover"
											data-placement="top"
											data-html="true"
											data-original-title="<?php echo __('Description'); ?>"
											data-content='<?php echo nl2br($item['ProgramIssue']['description']); ?>'>

											<?php echo $this->Text->truncate($item['ProgramIssue']['description'], 20); ?>
											<i class="icon-info-sign"></i>
										</div>
									<?php endif; ?>
								</td>
								<td><?php echo getProgramIssueStatuses($item['ProgramIssue']['status']); ?></td>
								<td class="align-center">
									<?php
									$exportUrl = array(
										'action' => 'exportPdf',
										$item['ProgramIssue']['id']
									);

									$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

									echo $this->Ajax->getActionList($item['ProgramIssue']['id'], array(
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
										'id' => $item['ProgramIssue']['id'],
										'item' => $this->Workflow->getActions($item['ProgramIssue'], $item['WorkflowAcknowledgement'])
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
					'message' => __('No Issues found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>

</div>
