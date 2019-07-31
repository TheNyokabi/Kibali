<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link( '<i class="icon-plus-sign"></i>' . __( 'Add New' ), array(
						'controller' => 'comments',
						'action' => 'add',
						$model,
						$foreign_key
					), array(
						'class' => 'btn',
						'escape' => false
					) ); ?>
				</div>
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
							<th><?php echo $this->Paginator->sort( 'User.full_name', __( 'User' ) ); ?></th>
							<th><?php echo $this->Paginator->sort( 'Comment.message', __( 'Message' ) ); ?></th>
							<th><?php echo __( 'Belongs To' ) ?></th>
							<th><?php echo $this->Paginator->sort( 'Comment.created', __( 'Created' ) ); ?></th>
							<th><?php echo $this->Paginator->sort( 'Comment.modified', __( 'Modified' ) ); ?></th>
							<th class="align-center"><?php echo __( 'Action' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data as $entry ) : ?>
							<tr>
								<td><?php echo $entry['User']['full_name']; ?></td>
								<td><?php echo nl2br(h($entry['Comment']['message'])); ?></td>
								<td><?php echo preg_replace('/(?<!\ )[A-Z]/', ' $0', $entry['Comment']['model']); ?></td>
								<td><?php echo $entry['Comment']['created']; ?></td>
								<td><?php echo $entry['Comment']['modified']; ?></td>
								<td class="align-center">
									<ul class="table-controls">
										<li>
											<?php echo $this->Html->link( '<i class="icon-pencil"></i>', array(
												'action' => 'edit',
												$entry['Comment']['id']
											), array(
												'class' => 'bs-tooltip',
												'escape' => false,
												'title' => __( 'Edit' )
											) ); ?>
										</li>
										<li>
											<?php echo $this->Html->link( '<i class="icon-trash"></i>', array(
												'action' => 'delete',
												$entry['Comment']['id'],
												$model,
												$foreign_key
											), array(
												'class' => 'bs-tooltip',
												'escape' => false,
												'title' => __( 'Trash' )
											) ); ?>
										</li>
									</ul>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

			<?php else : ?>
				<?php
				echo $this->element('not_found', array(
					'message' => __('No Comments found.')
				));
				?>
			<?php endif; ?>

			<?php
			echo $this->element(CORE_ELEMENT_PATH . 'pagination', array(
				'url' => $backUrl
			));
			?>
		</div>
	</div>

</div>