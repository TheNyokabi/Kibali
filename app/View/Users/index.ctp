<?php
echo $this->element(CORE_ELEMENT_PATH . 'paginatorFilterOptions', array('data' => @$this->data['Filter']));
?>
<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Ajax->addAction(); ?>
				</div>

				<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'Asset', true); ?>

				<?php echo $this->Video->getVideoLink('User'); ?>

				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
			</div>
		</div>
		<div class="widget">

			<div class="widget-content">
				<?php if (!empty($data)) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th><?php echo $this->Paginator->sort('User.id', __('ID')) ?></th>
								<th><?php echo $this->Paginator->sort('User.login', __('Login')) ?></th>
								<th><?php echo $this->Paginator->sort('User.name', __('Name')) ?></th>
								<th><?php echo $this->Paginator->sort('User.surname', __('Surname')) ?></th>
								<th><?php echo $this->Paginator->sort('User.email', __('Email')) ?></th>
								<th class="align-center">
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Access to different sections of eramba is limitd trough the use of Groups assignations. Select groups that you wish to assign to this user.' ); ?>'>
									<?php echo __( 'Group' ); ?>
								        <i class="icon-info-sign"></i>
								        </div>
								</th>
								<th><?php echo __('Status'); ?></th>
								<th class="align-center">
								        <div class="bs-popover" data-trigger="hover" data-placement="top" data-original-title="<?php echo __( 'Help' ); ?>" data-content='<?php echo __( 'Only system users with the role of "admin" can modify other user accounts.' ); ?>'>
									<?php echo __( 'Action' ); ?>
								        <i class="icon-info-sign"></i>
								        </div>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $entry) : ?>
								<?php if (!in_array(ADMIN_GROUP_ID, $logged['Groups']) && $entry['User']['id'] != $logged['id']) continue; ?>
								<tr>
									<td><?php echo $entry['User']['id']; ?></td>
									<td><?php echo $entry['User']['login']; ?></td>
									<td><?php echo $entry['User']['name']; ?></td>
									<td><?php echo $entry['User']['surname']; ?></td>
									<td><?php echo $entry['User']['email']; ?></td>
									<td>
										<?php foreach ($entry['Group'] as $entryGroup): ?>
											<?= $entryGroup['name']; ?><br>
										<?php endforeach; ?>
									</td>
									<td>
										<?php
										echo $this->Users->getStatuses($entry);
										?>
									</td>
									<td class="align-center">
										<?php
										$options = array('trash' => false);
										if ($entry['User']['id'] != ADMIN_ID && in_array(ADMIN_GROUP_ID, $logged['Groups'])) {
											$options['trash'] = true;
										}

										if (!empty($entry['User']['blocked'])) {
											$unblockUrl = array(
												'controller' => 'users',
												'action' => 'unblock',
												$entry['User']['id']
											);

											$this->Ajax->addToActionList(__('Unlock'), $unblockUrl, 'unlock-alt', false);
										}
										echo $this->Ajax->getActionList($entry['User']['id'], am(array('style' => 'icons'), $options));
										?>

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
						'<i class="icon-exclamation-sign"></i>' . __('No users found.')
					);
					?>
				<?php endif; ?>
			</div>

		</div>
	</div>

</div>
