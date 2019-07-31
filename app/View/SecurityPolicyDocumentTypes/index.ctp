<?php
echo $this->element(CORE_ELEMENT_PATH . 'paginatorFilterOptions', array('data' => @$this->data['Filter']));
echo $this->Ajax->setPagination();
?>
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
				<?php if (!empty($data)) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th>
									<?php echo __('ID'); ?>
								</th>
								<th>
									<?php echo __('Name'); ?>
								</th>
								<th class="align-center"><?php echo __('Action'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $entry) : ?>
								<tr>
									<td>
										<?php echo $entry['SecurityPolicyDocumentType']['id']; ?>
									</td>
									<td>
										<?php echo $entry['SecurityPolicyDocumentType']['name']; ?>
									</td>
									<td class="align-center">
									 	<?php if($entry['SecurityPolicyDocumentType']['editable']): ?>
											<ul class="table-controls">
												<li>
													<?php echo $this->Html->link('<i class="icon-pencil"></i>',
														[
															'action' => 'edit',
															$entry['SecurityPolicyDocumentType']['id']
														],
														[
															'class' => 'bs-tooltip editable-controller',
															'escape' => false,
															'title' => __('Edit'),
															'data-ajax-action' => 'edit'
														]
													); ?>
												</li>
												<li>
													<?php echo $this->Html->link('<i class="icon-trash"></i>', 
														[
															'action' => 'delete',
															$entry['SecurityPolicyDocumentType']['id']
														], 
														[
															'class' => 'bs-tooltip',
															'escape' => false,
															'title' => __('Trash'),
															'data-ajax-action' => 'delete'
														]
													); ?>
												</li>
											</ul>
										<?php else:?>
											<?php echo __('System types can not be modified') ?>
										<?php endif; ?>

									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

					<?php echo $this->element('ajax-ui/pagination'); ?>

				<?php else : ?>
					<?php
					echo $this->Html->div(
						'alert alert-info',
						'<i class="icon-exclamation-sign"></i>' . __('No asset types found.')
					);
					?>
				<?php endif; ?>
			</div>

		</div>
	</div>

</div>