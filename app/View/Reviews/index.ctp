<?php echo $this->Ajax->setPagination(); ?>
<div class="row">
	<div class="col-md-12">
		<?php if (!empty($foreign_key)) : ?>
			<div class="widget box">
				<div class="widget-content">
					<div class="btn-toolbar">
						<div class="btn-group">
							<?php echo $this->Ajax->addAction(array('url' => array(
								'controller' => 'reviews',
								'action' => 'add',
								$model,
								$foreign_key
							))); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<div class="widget box widget-form">
			<div class="widget-content">

				<?php
				echo $this->element('Visualisation.widget_header', [
					'manageBtn' => false
				]);
				?>

				<?php if ( ! empty( $data ) ) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th><?php echo $this->Paginator->sort($reviewModel . '.planned_date', __('Planned Date')); ?></th>
								<th><?php echo $this->Paginator->sort($reviewModel . '.actual_date', __('Actual Date')); ?></th>
								<th><?php echo $this->Paginator->sort($reviewModel . '.description', __('Description')); ?></th>
								<th><?php echo $this->Paginator->sort($reviewModel . '.user_id', __('Reviewer')); ?></th>
								<?php if ($model == 'SecurityPolicy') : ?>
									<th><?php echo $this->Paginator->sort($reviewModel . '.version', __('Version')); ?></th>
								<?php endif; ?>
								<th><?php echo $this->Paginator->sort($reviewModel . '.completed', __('Completed')); ?></th>
								<th class="align-center">

								<div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Reviews actions usually imply uploading the evidence (an email, a report) that indicates that the review has been done and evidence exist.' ); ?>'>
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
									<td>
										<?php
										echo $entry[$reviewModel]['planned_date'];

										if ($entry[$reviewModel]['completed'] != REVIEW_COMPLETE && $this->Ux->isMissing($entry[$reviewModel]['planned_date'])) {
											echo ' ' . __('(Missing)');
										}
										?>
									</td>
									<td><?php echo $entry[$reviewModel]['actual_date']; ?></td>
									<td><?php echo $this->Eramba->getEmptyValue($entry[$reviewModel]['description']); ?></td>
									<td><?php echo $entry['User']['full_name']; ?></td>
									<?php if ($model == 'SecurityPolicy') : ?>
										<td><?php echo $entry[$reviewModel]['version']; ?></td>
									<?php endif; ?>
									<td>
										<?php
										if ($entry[$reviewModel]['completed'] == REVIEW_COMPLETE) {
											echo __('Yes');
										}
										else {
											echo __('No');
										}
										?>
									</td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($entry[$reviewModel]['id'], array(
											'style' => 'icons',
											'model' => $reviewModel,
											'item' => $entry,
											'history' => true
										));
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry[$reviewModel]['id'],
											'item' => $this->Workflow->getActions($entry[$reviewModel], $entry['WorkflowAcknowledgement']),
											'currentModel' => $reviewModel
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
