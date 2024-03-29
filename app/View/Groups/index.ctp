<?php
echo $this->element(CORE_ELEMENT_PATH . 'paginatorFilterOptions', array('data' => @$this->data['Filter']));
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link( '<i class="icon-plus-sign"></i>' . __( 'Add New' ), array(
						'controller' => 'groups',
						'action' => 'add'
					), array(
						'class' => 'btn',
						'escape' => false
					) ); ?>
				</div>
				<?php echo $this->Video->getVideoLink('Group'); ?>
			</div>
		</div>
		<div class="widget">

			<div class="widget-content">
				<?php if (!empty($data)) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th><?php echo $this->Paginator->sort('Group.name', __('Name')) ?></th>
								<th>
									<?php
									echo __('Access List');
									?>
								</th>
								<th class="align-center"><?php echo __( 'Action' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td><?php echo $entry['Group']['name']; ?></td>
									<td>
										<?php
										$url = '/admin/acl/aros/ajax_role_permissions/' . $entry['Group']['id'];
										echo $this->Html->link(__('Configure Access List'), $url);
										?>
									</td>
									<td class="align-center">
										<ul class="table-controls">
											<li>
												<?php echo $this->Html->link( '<i class="icon-pencil"></i>', array(
													'action' => 'edit',
													$entry['Group']['id']
												), array(
													'class' => 'bs-tooltip',
													'escape' => false,
													'title' => __( 'Edit' )
												) ); ?>
											</li>
											<?php if (!in_array($entry['Group']['id'], [ADMIN_GROUP_ID, AUDITEE_GROUP_ID])) : ?>
											<li>
												<?php echo $this->Html->link( '<i class="icon-trash"></i>', array(
													'action' => 'delete',
													$entry['Group']['id']
												), array(
													'class' => 'bs-tooltip',
													'escape' => false,
													'title' => __( 'Trash' )
												) ); ?>
											</li>
											<?php endif; ?>
										</ul>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination'); ?>

				<?php else : ?>
					<?php
					echo $this->Html->div(
						'alert alert-info',
						'<i class="icon-exclamation-sign"></i>' . __('No roles found.')
					);
					?>
				<?php endif; ?>
			</div>

		</div>
	</div>

</div>