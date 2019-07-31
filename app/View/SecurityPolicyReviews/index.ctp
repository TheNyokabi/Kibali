<?php echo $this->Ajax->setPagination(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-content">
				<?php if ( ! empty( $data ) ) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th>
									<?php
									echo $this->Paginator->sort('SecurityPolicyReview.planned_date', __('Planned Date'));
									?>
								</th>
								<th>
									<?php
									echo $this->Paginator->sort('SecurityPolicyReview.actual_review_date', __('Actual Date'));
									?>
								</th>
								<th>
									<?php
									echo $this->Paginator->sort('SecurityPolicyReview.reviewer_id', __('Reviewer'));
									?>
								</th>
								<th><?php echo __('Status'); ?></th>
								<th class="align-center"><?php echo __('Action'); ?></th>
								<?php /*
								<th class="align-center"><?php echo __('Workflows'); ?></th>
								*/ ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td><?php echo $entry['SecurityPolicyReview']['planned_date']; ?></td>
									<td><?php echo $entry['SecurityPolicyReview']['actual_review_date']; ?></td>
									<td><?php echo $entry['Reviewer']['name'] . ' ' . $entry['Reviewer']['surname']; ?></td>
									<td>
										<?php
										echo $this->SecurityPolicyReviews->getStatuses($entry);
										?>
									</td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($entry['SecurityPolicyReview']['id'], array(
											'style' => 'icons',
											'notifications' => true
										));
										?><!-- 
										<ul class="table-controls">
											<li>
												<?php
												echo $this->Html->link('<i class="icon-pencil"></i>', array(
													'action' => 'edit',
													$entry['SecurityPolicyReview']['id']
												), array(
													'class' => 'bs-tooltip',
													'escape' => false,
													'title' => __('Edit')
												));
												?>
											</li>
											<li>
												<?php
												echo $this->Html->link('<i class="icon-trash"></i>', array(
													'action' => 'delete',
													$entry['SecurityPolicyReview']['id']
												), array(
													'class' => 'bs-tooltip',
													'escape' => false,
													'title' => __('Trash')
												));
												?>
											</li>
											<?php
											$extraClass = '';
											if ( count( $entry['Attachment'] ) ) {
												$extraClass = 'has-attachments';
											}
											?>
											<li>
												<?php
												echo $this->Html->link('<i class="icon-cloud-upload ' . $extraClass . '"></i>', array(
													'controller' => 'attachments',
													'action' => 'index',
													'SecurityPolicyReview',
													$entry['SecurityPolicyReview']['id']
												), array(
													'class' => 'bs-tooltip',
													'escape' => false,
													'title' => __('Attachments')
												));
												?>
											</li>
											<?php
											$extraClass = '';
											if ( count( $entry['Comment'] ) ) {
												$extraClass = 'has-attachments';
											}
											?>
											<li>
												<?php
												echo $this->Html->link('<i class="icon-comments ' . $extraClass . '"></i>', array(
													'controller' => 'comments',
													'action' => 'index',
													'SecurityPolicyReview',
													$entry['SecurityPolicyReview']['id']
												), array(
													'class' => 'bs-tooltip',
													'escape' => false,
													'title' => __( 'Comments' )
												));
												?>
											</li>
											<?php if ($notificationSystemEnabled) : ?>
												<li>
													<?php
													echo $this->Html->link('<i class="icon-info-sign"></i>', array(
														'controller' => 'notificationSystem',
														'action' => 'attach',
														'SecurityPolicyReview',
														$entry['SecurityPolicyReview']['id']
													), array(
														'class' => 'bs-tooltip',
														'escape' => false,
														'title' => __('Notifications')
													));
													?>
												</li>
											<?php endif; ?>
										</ul> -->
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['SecurityPolicyReview']['id'],
											'item' => $this->Workflow->getActions($entry['SecurityPolicyReview'], $entry['WorkflowAcknowledgement'])
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
						'message' => __('No Reviews found.')
					));
					?>
				<?php endif; ?>

				<?php echo $this->element('ajax-ui/pagination'); ?>
			</div>
		</div>
	</div>

</div>