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
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Provide the type of classification, for example: "Confidentiality, Value, Etc"' ); ?>'>
								<?php echo $this->Paginator->sort( 'AssetClassificationType.name', __( 'Type' ) ); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Provide a name for this Classification, for example: "High, Medium, Low"' ); ?>'>
								<?php echo $this->Paginator->sort( 'AssetClassification.name', __( 'Name' ) ); ?>
							        <i class="icon-info-sign"></i>
							        </div>
							</th>
							<th>
							   	<?php echo $this->Paginator->sort( 'AssetClassification.value', __( 'Value' ) ); ?>
							</th>
							<th>
							        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Define a clear criteria to define if a given asset belongs to this classification or not. For example: "Legal implications highly probable"' ); ?>'>
								<?php echo $this->Paginator->sort( 'AssetClassification.criteria', __( 'Criteria' ) ); ?>
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
									<td><?php echo $entry['AssetClassificationType']['name']; ?></td>
									<td><?php echo $entry['AssetClassification']['name']; ?></td>
									<td><?php echo $entry['AssetClassification']['value']; ?></td>
									<td>
										<?php
										echo $this->Eramba->getEmptyValue($entry['AssetClassification']['criteria']);
										?>
									</td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($entry['AssetClassification']['id'], array('style' => 'icons'));
										?>
									</td>
									<?php /*
									<td class="text-center">
										<?php
										echo $this->element('workflow/action_buttons_1', array(
											'id' => $entry['AssetClassification']['id'],
											'item' => $this->Workflow->getActions($entry['AssetClassification'], $entry['WorkflowAcknowledgement'])
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
						'message' => __( 'No Asset Classifications found.' )
					) ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

</div>
