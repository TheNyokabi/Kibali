<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link( '<i class="icon-plus-sign"></i>' . __( 'Add New' ), array(
						'controller' => 'securityIncidentClassifications',
						'action' => 'add'
					), array(
						'class' => 'btn',
						'escape' => false
					) ); ?>

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
			</div>
		</div>
		<div class="widget">
			<?php if ( ! empty( $data ) ) : ?>
				<table class="table table-hover table-striped table-bordered table-highlight-head">
					<thead>
						<tr>
							<th><?php echo $this->Paginator->sort( 'SecurityIncidentClassification.name', __( 'Name' ) ); ?></th>
							<th><?php echo $this->Paginator->sort( 'SecurityIncidentClassification.criteria', __( 'Criteria' ) ); ?></th>
							<th class="align-center"><?php echo __( 'Action' ); ?></th>
							<?php /*
							<th class="text-center"><?php echo __('Workflow'); ?></th>
							*/ ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data as $entry ) : ?>
							<tr>
								<td><?php echo $entry['SecurityIncidentClassification']['name']; ?></td>
								<td><?php echo $entry['SecurityIncidentClassification']['criteria']; ?></td>
								<td class="align-center">
									<?php
									echo $this->element('action_buttons', array( 
										'id' => $entry['SecurityIncidentClassification']['id'],
										'controller' => 'securityIncidentClassifications',
										'workflowRecords' => 'SecurityIncidentClassification'
									));
									?>
								</td>
								<?php /*
								<td class="text-center">
									<?php
									echo $this->element('workflow/action_buttons_1', array(
										'id' => $entry['SecurityIncidentClassification']['id'],
										'item' => $this->Workflow->getActions($entry['SecurityIncidentClassification'])
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
				<?php echo $this->element( 'not_found', array(
					'message' => __( 'No Security Incident Classifications found.' )
				) ); ?>
			<?php endif; ?>

		</div>
	</div>

</div>