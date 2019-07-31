<?php echo $this->Ajax->setPagination(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<div class="btn-toolbar">
					<div class="btn-group">
						<?php echo $this->Ajax->addAction(); ?>
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
								<th>
									<div>
										<?php echo $this->Paginator->sort('ComplianceAuditFeedback.name', __('Name')); ?>
									</div>
								</th>
								<th>
									<div>
										<?php echo $this->Paginator->sort('ComplianceAuditFeedbackProfile.name', __('Profile')); ?>
									</div>
								</th>
								<th class="align-center">
									<div>
										<?php echo __('Actions'); ?>
									</div>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $entry) : ?>
								<tr>
									<td><?php echo $entry['ComplianceAuditFeedback']['name']; ?></td>
									<td><?php echo $entry['ComplianceAuditFeedbackProfile']['name']; ?></td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($entry['ComplianceAuditFeedback']['id'], array('style' => 'icons'));
										?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Compliance Audit Feedback items found.' )
					) ); ?>
				<?php endif; ?>

			</div>
		</div>
	</div>

</div>
