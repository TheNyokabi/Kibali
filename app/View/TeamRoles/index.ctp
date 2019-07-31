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

				<?php echo $this->NotificationSystem->getIndexLink('TeamRole'); ?>

				<?php echo $this->Video->getVideoLink('TeamRole'); ?>

				<?php
				echo $this->CustomFields->getIndexLink(array(
					'TeamRole' => ClassRegistry::init('TeamRole')->label(['singular' => true]),
				));
				?>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="widget">
			<?php if ( ! empty( $data ) ) : ?>
				<table class="table table-hover table-striped table-bordered table-highlight-head">
					<thead>
						<tr>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the team member (the account in eramba)' ); ?>'>
									<?php echo $this->Paginator->sort( 'User.name', __( 'Name' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The member role in the program.' ); ?>'>
									<?php echo $this->Paginator->sort( 'TeamRole.role', __( 'Role' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A description of the responsabilities of the member.' ); ?>'>
									<?php echo $this->Paginator->sort('TeamRole.responsibilities', __('Responsibilities')); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'A brief description of the skills and background of the memeber.' ); ?>'>
									<?php echo $this->Paginator->sort('TeamRole.competences', __('Competences')); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th>
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The status of the mmeber can be either active or discarded (not a member anymore).' ); ?>'>
									<?php echo $this->Paginator->sort( 'TeamRole.status', __( 'Status' ) ); ?>
									<i class="icon-info-sign"></i>
								</div>
							</th>
							<th class="align-center">
								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'You might want to attach documents such as certificates, CVs planned trainings, Etc. The other options allow you to edit, delete or review system records for this object' ); ?>'>
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
								<td><?php echo $item['User']['full_name']; ?></td>
								<td><?php echo $item['TeamRole']['role']; ?></td>
								<td>
									<?php if (!empty($item['TeamRole']['responsibilities'])) : ?>
										<div class="bs-popover"
											data-trigger="hover"
											data-placement="top"
											data-html="true"
											data-original-title="<?php echo __('Responsibilities'); ?>"
											data-content='<?php echo nl2br($item['TeamRole']['responsibilities']); ?>'>

											<?php echo $this->Text->truncate($item['TeamRole']['responsibilities'], 20); ?>
											<i class="icon-info-sign"></i>
										</div>
									<?php endif; ?>
								</td>
								<td>
									<?php if (!empty($item['TeamRole']['competences'])) : ?>
										<div class="bs-popover"
											data-trigger="hover"
											data-placement="top"
											data-html="true"
											data-original-title="<?php echo __('Competences'); ?>"
											data-content='<?php echo nl2br($item['TeamRole']['competences']); ?>'>

											<?php echo $this->Text->truncate($item['TeamRole']['competences'], 20); ?>
											<i class="icon-info-sign"></i>
										</div>
									<?php endif; ?>
								</td>
								<td><?php echo getTeamRoleStatuses($item['TeamRole']['status']); ?></td>
								<td class="align-center">
									<?php
									$exportUrl = array(
										'action' => 'exportPdf',
										$item['TeamRole']['id']
									);

									$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);

									echo $this->Ajax->getActionList($item['TeamRole']['id'], array(
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
										'id' => $item['TeamRole']['id'],
										'item' => $this->Workflow->getActions($item['TeamRole'], $item['WorkflowAcknowledgement'])
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
					'message' => __('No Team Roles found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>

</div>
