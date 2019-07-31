<?php
App::uses('LdapConnector', 'Model');
?>
<div class="row">

	<div class="col-md-9">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link('<i class="icon-plus-sign"></i>' . __('Add New'), array(
						'controller' => 'ldapConnectors',
						'action' => 'add'
					), array(
						'class' => 'btn',
						'escape' => false
					)); ?>

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

				<?php echo $this->Video->getVideoLink('LdapConnector'); ?>
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
								<?php echo $this->Paginator->sort('LdapConnector.name', __('Connector Name')); ?>
							</th>
							<th>
								<?php echo $this->Paginator->sort('LdapConnector.description', __('Description')); ?>
							</th>
							<th>
								<?php echo $this->Paginator->sort('LdapConnector.type', __('Type')); ?>
							</th>
							<th>
								<?php echo __('Eramba Portal'); ?>
							</th>
							<th>
								<?php echo __('Policy Portal'); ?>
							</th>
							<th>
								<?php echo __('Awareness Portal'); ?>
							</th>
							<th>
								<?php echo __('Security Policies'); ?>
							</th>
							<th>
								<?php echo __('Awareness Programs'); ?>
							</th>
							<th>
								<?php echo __('Status'); ?>
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
						<?php foreach ($data as $item) : ?>
							<tr>
								<td><?php echo $item['LdapConnector']['name']; ?></td>
								<td><?php echo $this->Eramba->getEmptyValue($item['LdapConnector']['description']); ?></td>
								<td>
									<?php
									$type = LdapConnector::types($item['LdapConnector']['type']);
									echo $this->Eramba->getEmptyValue($type);
									?>
								</td>
								<td>
									<?php
									$isUsed = false;
									if ($ldapAuth['LdapConnectorAuthentication']['auth_users']
										&& $ldapAuth['LdapConnectorAuthentication']['auth_users_id'] == $item['LdapConnector']['id']
									) {
										$isUsed = true;
									}
									echo getStatusFilterOption()[$isUsed];
									?>
								</td>
								<td>
									<?php
									$isUsed = false;
									if ($ldapAuth['LdapConnectorAuthentication']['auth_policies']
										&& $ldapAuth['LdapConnectorAuthentication']['auth_policies_id'] == $item['LdapConnector']['id']
									) {
										$isUsed = true;
									}
									echo getStatusFilterOption()[$isUsed];
									?>
								</td>
								<td>
									<?php
									$isUsed = false;
									if ($ldapAuth['LdapConnectorAuthentication']['auth_awareness']
										&& $ldapAuth['LdapConnectorAuthentication']['auth_awareness_id'] == $item['LdapConnector']['id']
									) {
										$isUsed = true;
									}
									echo getStatusFilterOption()[$isUsed];
									?>
								</td>
								<td>
									<?php
									$isUsed = (!empty($item['SecurityPolicy'])) ? true : false;
									$label = getStatusFilterOption()[$isUsed];
									if ($isUsed) {
										$label = $this->AdvancedFilters->getItemFilteredLink($label, 'SecurityPolicy', $item['LdapConnector']['id'], [
								            'key' => 'ldap_connector_id',
										]);
									}
									echo $label;
									?>
								</td>
								<td>
									<?php
									$isUsed = (!empty($item['AwarenessProgram'])) ? true : false;
									$label = getStatusFilterOption()[$isUsed];
									if ($isUsed) {
										$label = $this->AdvancedFilters->getItemFilteredLink($label, 'AwarenessProgram', $item['LdapConnector']['id'], [
								            'key' => 'ldap_connector_id',
										]);
									}
									echo $label;
									?>
								</td>
								<td>
									<?php
									echo $this->LdapConnectors->getStatuses($item);
									?>
								</td>
								<td class="align-center">
									<?php
									echo $this->Ajax->getActionList($item['LdapConnector']['id'], array(
										'style' => 'icons',
										'notifications' => false,
										'disableEditAjax' => true,
										'controller' => 'ldapConnectors',
										'model' => 'LdapConnector',
										'item' => $item
									));
									?>
								</td>
								<?php /*
								<td class="text-center">
									<?php
									echo $this->element('workflow/action_buttons_1', array(
										'id' => $item['LdapConnector']['id'],
										'item' => $this->Workflow->getActions($item['LdapConnector'], $item['WorkflowAcknowledgement'])
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
				echo $this->element('not_found', array(
					'message' => __('No LDAP Connectors found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>

</div>
