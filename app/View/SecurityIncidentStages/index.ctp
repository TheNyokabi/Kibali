<?php
echo $this->element(CORE_ELEMENT_PATH . 'paginatorFilterOptions', array('data' => @$this->data['Filter']));
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link( '<i class="icon-plus-sign"></i>' . __( 'Add New' ), array(
						'controller' => 'securityIncidentStages',
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

			<div class="widget-content">
				<?php if (!empty($data)) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th><?php echo $this->Paginator->sort('SecurityIncidentStage.name', __('Name')) ?></th>
								<th><?php echo $this->Paginator->sort('SecurityIncidentStage.description', __('Description')) ?></th>
								<th class="align-center"><?php echo __( 'Action' ); ?></th>
								<?php /*
								<th class="align-center"><?php echo __( 'Workflows' ); ?></th>
								*/ ?>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data as $item ) : ?>
								<tr>
									<td><?php echo $item['SecurityIncidentStage']['name']; ?></td>
									<td><?php echo $item['SecurityIncidentStage']['description']; ?></td>
									<td class="align-center">
										<?php
										echo $this->Ajax->getActionList($item['SecurityIncidentStage']['id'], array(
											'style' => 'icons',
											// 'notifications' => true,
											'disableEditAjax' => true,
											'controller' => 'securityIncidentStages',
											'model' => 'SecurityIncidentStage',
											'item' => $item
										));
										?>
									</td>
									<?php /*
									<td class="align-center">
									<?php
										echo $this->element('workflow/action_buttons_1', array(
										    'id' => $item['SecurityIncidentStage']['id'],
										    'item' => $this->Workflow->getActions($item['SecurityIncidentStage'], $item['WorkflowAcknowledgement'])
										));
									?>
									</td>
									*/ ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination'); ?>

				<?php else : ?>
					<?php
					echo $this->Html->div(
						'alert alert-info',
						'<i class="icon-exclamation-sign"></i>' . __('No stages found.')
					);
					?>
				<?php endif; ?>
			</div>

		</div>
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link(__('Back'), array(
						'controller' => 'securityIncidents',
						'action' => 'index'
					), array(
						'class' => 'btn btn-inverse'
					)); ?>

				</div>
			</div>
		</div>
	</div>

</div>
