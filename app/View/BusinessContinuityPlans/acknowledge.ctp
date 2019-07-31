<div class="row">
	<div class="col-md-12">

		<?php if ( ! empty( $data ) ) : ?>
			<?php foreach ( $data as $entry ) : ?>
				<?php
				if ( empty( $entry['BusinessContinuityTask'] ) ) {
					continue;
				}

				$extra_class = '';
				/*if ( $extra_class != 'widget-header-alert' ) {
					if ( $extra_class != 'widget-header-warning' ) {
						if ( ! $entry['BusinessContinuityPlan']['status']['all_done'] ) {
							$extra_class = 'widget-header-warning';
						}
					}
					
					if ( ! $entry['BusinessContinuityPlan']['status']['last_passed'] ) {
						$extra_class = 'widget-header-alert';
					}
				}*/
				?>
				<div class="widget box widget-closed">
					<div class="widget-header <?php echo $extra_class; ?>">
						<h4><?php echo $entry['BusinessContinuityPlan']['title']; ?></h4>
						<div class="toolbar no-padding">
							<div class="btn-group">
								<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
							</div>
						</div>
					</div>
					<div class="widget-content" style="display:none;">

						<div class="widget box widget-closed">
							<div class="widget-header">
								<h4><?php echo __( 'Plan Task Details' ); ?></h4>
								<div class="toolbar no-padding">
									<div class="btn-group">
										<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
									</div>
								</div>
							</div>
							<div class="widget-content" style="display:none;">
								<?php if ( ! empty( $entry['BusinessContinuityTask'] ) ) : ?>
									<table class="table table-hover table-striped">
										<thead>
											<tr>
												<th><?php echo __( 'Step' ); ?></th>
												<th><?php echo __( 'When' ); ?></th>
												<th><?php echo __( 'Who' ); ?></th>
												<th><?php echo __( 'Awareness Role' ); ?></th>
												<th><?php echo __( 'Does Something' ); ?></th>
												<th><?php echo __( 'Where' ); ?></th>
												<th><?php echo __( 'How' ); ?></th>
												<th class="align-center"><?php echo __( 'Action' ); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $entry['BusinessContinuityTask'] as $task ) : ?>
											<tr>
												<td><?php echo $task['step']; ?></td>
												<td><?php echo $task['when']; ?></td>
												<td><?php echo $task['who']; ?></td>
												<td><?php echo $userList[ $task['awareness_role'] ]; ?></td>
												<td><?php echo $task['does']; ?></td>
												<td><?php echo $task['where']; ?></td>
												<td><?php echo $task['how']; ?></td>
												<td class="align-center">
													<ul class="table-controls">
														<li>
															<?php echo $this->Html->link( '<i class="icon-check"></i>', array(
																'controller' => 'businessContinuityPlans',
																'action' => 'acknowledgeItem',
																$task['BusinessContinuityTaskReminder'][0]['id']
															), array(
																'class' => 'bs-tooltip',
																'escape' => false,
																'title' => __( 'Acknowledge' )
															) ); ?>
														</li>
													</ul>
												</td>
											</tr>
											<?php endforeach ; ?>
										</tbody>
									</table>
								<?php else : ?>
									<?php echo $this->element( 'not_found', array(
										'message' => __( 'No Business Continuity Plan Tasks found.' )
									) ); ?>
								<?php endif; ?>
							</div>
						</div>

					</div>
				</div>

			<?php endforeach; ?>

		<?php else : ?>
			<?php echo $this->element( 'not_found', array(
				'message' => __( 'There is nothing to show.' )
			) ); ?>
		<?php endif; ?>

	</div>

</div>