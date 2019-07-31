<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php echo $this->Html->link( '<i class="icon-plus-sign"></i>' . __( 'Add New' ), array(
						'controller' => 'attachments',
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
							<th><?php echo $this->Paginator->sort( 'Attachment.filename', __( 'Filename' ) ); ?></th>
							<th><?php echo $this->Paginator->sort( 'Attachment.description', __( 'Description' ) ); ?></th>
							<th><?php echo __( 'Belongs To' ) ?></th>
							<th><?php echo $this->Paginator->sort( 'User.Name', __( 'Uploaded by' ) ); ?></th>
							<th><?php echo $this->Paginator->sort( 'Attachment.created', __( 'Created' ) ); ?></th>
							<th class="align-center"><?php echo __( 'Action' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data as $entry ) : ?>
							<tr>
								<td><?php
										echo $this->Html->link( basename( $entry['Attachment']['filename'] ), array(
											'action' => 'download',
											$entry['Attachment']['id']
										), array(
											'class' => 'bs-tooltip',
											'title' => __('Download')
										));
								  ?></td>
								<td><?php echo nl2br($entry['Attachment']['description']); ?> </td>
								<td><?php echo preg_replace('/(?<!\ )[A-Z]/', ' $0', $entry['Attachment']['model']); ?></td>
								<td><?php echo $entry['User']['name']?$entry['User']['name']:'' ?> </td>
								<td><?php echo $entry['Attachment']['created']; ?></td>
								<td class="align-center">
									<ul class="table-controls">
										<li>
											<?php
											echo $this->Html->link('<i class="icon-download"></i>', array(
												'action' => 'download',
												$entry['Attachment']['id']
											), array(
												'class' => 'bs-tooltip',
												'escape' => false,
												'title' => __('Download')
											));
											?>
										</li>
										<li>
											<?php
											echo $this->Html->link('<i class="icon-trash"></i>', array(
												'action' => 'delete',
												$entry['Attachment']['id'],
												$model,
												$foreign_key
											), array(
												'class' => 'bs-tooltip',
												'escape' => false,
												'title' => __( 'Trash' )
											));
											?>
										</li>
									</ul>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

			<?php else : ?>
				<?php echo $this->element( 'not_found', array(
					'message' => __( 'No Attachments found.' )
				) ); ?>
			<?php endif; ?>

			<?php
			echo $this->element( CORE_ELEMENT_PATH . 'pagination', array(
				'url' => $backUrl
			) );
			?>
		</div>
	</div>

</div>