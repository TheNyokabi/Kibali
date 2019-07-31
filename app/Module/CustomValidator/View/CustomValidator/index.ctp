<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-content">
				<?php if (!empty($data)) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th>
									<?php echo __('Name'); ?>
								</th>
								<th class="align-center">
									<?php echo __('Action'); ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($data as $name => $entry) : ?>
								<tr>
									<td>
										<?php echo $entry['title']; ?>
									</td>
									<td class="align-center">
										<ul class="table-controls">
											<li>
												<?php echo $this->Html->link('<i class="icon-cog"></i>',
													[
														'controller' => 'customValidator',
														'action' => 'setup',
														'plugin' => 'custom_validator',
														$model,
														$name
													],
													[
														'class' => 'bs-tooltip editable-controller',
														'escape' => false,
														'title' => __('Setup'),
														'data-ajax-action' => 'edit'
													]
												); ?>
											</li>
										</ul>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php else : ?>
					<?php
					echo $this->Html->div(
						'alert alert-info',
						'<i class="icon-exclamation-sign"></i>' . __('No Custom Validators for this section.')
					);
					?>
				<?php endif; ?>

				<?php echo $this->Ajax->cancelBtn(); ?>
			</div>

		</div>
	</div>

</div>
