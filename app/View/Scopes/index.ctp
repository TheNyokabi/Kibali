<div class="row">

	<div class="col-md-9">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php
					if (empty($data)) {
						echo $this->Html->link( '<i class="icon-plus-sign"></i>' . __('Add New'), array(
							'controller' => 'scopes',
							'action' => 'add'
						), array(
							'class' => 'btn',
							'escape' => false
						));
					}
					?>
				</div>
				<?php echo $this->Video->getVideoLink('Scoper'); ?>
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
							<th><?php echo $this->Paginator->sort('CisoRole.name', __('CISO Role')); ?></th>
							<th><?php echo $this->Paginator->sort('CisoDeputy.name', __('CISO Deputy')); ?></th>
							<th><?php echo $this->Paginator->sort('BoardRepresentative.name', __('Board Representative')); ?></th>
							<th><?php echo $this->Paginator->sort('BoardRepresentativeDeputy.name', __('Board Representative Deputy')); ?></th>
							<th class="align-center"><?php echo __('Action'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data as $entry ) : ?>
							<tr>
								<td>
									<?php
									if (!empty($entry['CisoRole']['id'])) {
										echo $entry['CisoRole']['name'] . ' ' . $entry['CisoRole']['surname'];
									}
									else {
										echo __('None');
									}
									?>
								</td>
								<td>
									<?php
									if (!empty($entry['CisoDeputy']['id'])) {
										echo $entry['CisoDeputy']['name'] . ' ' . $entry['CisoDeputy']['surname'];
									}
									else {
										echo __('None');
									}
									?>
								</td>
								<td>
									<?php
									if (!empty($entry['BoardRepresentative']['id'])) {
										echo $entry['BoardRepresentative']['name'] . ' ' . $entry['BoardRepresentative']['surname'];
									}
									else {
										echo __('None');
									}
									?>
								</td>
								<td>
									<?php
									if (!empty($entry['BoardRepresentativeDeputy']['id'])) {
										echo $entry['BoardRepresentativeDeputy']['name'] . ' ' . $entry['BoardRepresentativeDeputy']['surname'];
									}
									else {
										echo __('None');
									}
									?>
								</td>
								<td class="align-center">
									<?php
									echo $this->element('action_buttons', array(
										'id' => $entry['Scope']['id'],
										'controller' => 'scopes'
									));
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<?php echo $this->element(CORE_ELEMENT_PATH . 'pagination'); ?>
			<?php else : ?>
				<?php
				echo $this->element('not_found', array(
					'message' => __('No Scopes found.')
				));
				?>
			<?php endif; ?>

		</div>
	</div>

</div>