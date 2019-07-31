<?php echo $this->Ajax->setPagination(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<div class="btn-toolbar">
					<div class="btn-group">
						<?php echo $this->Ajax->addAction(array('url' => array(
							'controller' => 'issues',
							'action' => 'add',
							$model,
							$foreign_key
						))); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="widget box widget-form">
			<div class="widget-content">
				<?php if ( ! empty( $data ) ) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th><?php echo $this->Paginator->sort('Issue.planned_date', __('Date Started')); ?></th>
								<th><?php echo $this->Paginator->sort('Issue.actual_date', __('Date Ended')); ?></th>
								<th><?php echo $this->Paginator->sort('Issue.description', __('Description')); ?></th>
								<th><?php echo $this->Paginator->sort('Issue.user_id', __('Status')); ?></th>
								<th class="align-center">

								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( '...' ); ?>'>
                                                               <?php echo __( 'Action' ); ?>
                                                               <i class="icon-info-sign"></i>
                                                               </div>
								</th>
								<?php /*
								<th class="align-center"><?php echo __( 'Workflows' ); ?> </th>
								*/ ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td><?php echo $entry['Issue']['date_start']; ?></td>
									<td><?php echo $entry['Issue']['date_end']; ?></td>
									<td><?php echo $this->Eramba->getEmptyValue($entry['Issue']['description']); ?></td>
									<td><?php echo getIssueStatuses($entry['Issue']['status']); ?></td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($entry['Issue']['id'], array(
											'style' => 'icons',
											'model' => $issueModel,
											'item' => $entry
										));
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['Issue']['id'],
											'item' => $this->Workflow->getActions($entry['Issue'], $entry['WorkflowAcknowledgement']),
											'currentModel' => $issueModel
										));
										?>
									</td>
									*/ ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

				<?php else : ?>
					<?php
					echo $this->element('not_found', array(
						'message' => __('No Issues found.')
					));
					?>
				<?php endif; ?>

				<?php echo $this->element('ajax-ui/pagination'); ?>
			</div>
		</div>
	</div>
</div>
