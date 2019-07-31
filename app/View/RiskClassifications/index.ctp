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
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The type is tied with the "name" value, for exaple a type could be: Likelihood (while the name high, medium and low the names for this type)' ); ?>'>
									<?php echo $this->Paginator->sort( 'RiskClassificationType.name', __( 'Type' ) ); ?>
								        <i class="icon-info-sign"></i>
								        </div>
								</th>
								<th>
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The name of the classification is for example: High, Medium, Low' ); ?>'>
									<?php echo $this->Paginator->sort( 'RiskClassification.name', __( 'Name' ) ); ?>
								        <i class="icon-info-sign"></i>
								        </div>
								</th>
								<th>
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The value is a numeric value that is used to calculate the Risk score' ); ?>'>
									<?php echo $this->Paginator->sort( 'RiskClassification.value', __( 'Value' ) ); ?>
								        <i class="icon-info-sign"></i>
								        </div>
								</th>
								<th>
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'The criteria should clearly define what classification applies to the Risk being analysed' ); ?>'>
									<?php echo $this->Paginator->sort( 'RiskClassification.criteria', __( 'Criteria' ) ); ?>
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
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td><?php echo $entry['RiskClassificationType']['name']; ?></td>
									<td><?php echo $entry['RiskClassification']['name']; ?></td>
									<td><?php echo $entry['RiskClassification']['value']; ?></td>
									<td><?php echo $this->Eramba->getEmptyValue($entry['RiskClassification']['criteria']); ?></td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($entry['RiskClassification']['id'], array('style' => 'icons'));
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['RiskClassification']['id'],
											'item' => $this->Workflow->getActions($entry['RiskClassification'], $entry['WorkflowAcknowledgement'])
										));
										?>
									</td>
									*/ ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php echo $this->element('ajax-ui/pagination'); ?>
				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Risk Classifications found.' )
					) ); ?>
				<?php endif; ?>

			</div>
		</div>
	</div>

</div>
