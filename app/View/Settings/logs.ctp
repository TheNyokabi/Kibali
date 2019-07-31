<div class="row">
	<div class="col-md-4">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
                    <?php echo $this->Html->link( '<i class="icon-download"></i>' . __('Download Log file'), array(
                        'controller' => 'settings',
                        'action' => 'downloadLogs',
                        $type
                    ), array(
                        'class' => 'btn',
                        'escape' => false
                    ) ); ?>
                </div>
				<div class="btn-group">
					<?php echo $this->Html->link( '<i class="icon-remove"></i>' . __('Delete Log file'), array(
						'controller' => 'settings',
						'action' => 'deleteLogs',
						$type
					), array(
						'class' => 'btn log_delete',
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
			<div class="widget-content">
				<?php if (!empty($errorArr)) : ?>
					<table class="table table-hover table-striped table-bordered table-highlight-head">
						<thead>
							<tr>
								<th><?php echo __('Date')?></th>
								<th class="align-center"><?php echo __( 'Message' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $errorArr as $entry ) : ?>
								<tr>
									<td><?php echo $entry[0]; ?></td>
									<td><?php echo nl2br(trim($entry[1])); ?></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

				<?php else : ?>
					<?php
					echo $this->Html->div(
						'alert alert-info',
						'<i class="icon-exclamation-sign"></i> ' . __('No logs found.')
					);
					?>
				<?php endif; ?>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(".log_delete").on('click', function(e){
		e.preventDefault();
		var el = $(this);
		bootbox.dialog({
			message: "<?php echo __('Do you want to delete this log file?'); ?>",
			buttons: {
				main: {
					label: "<?php echo __('No') ?>",
					className: "btn-default",
					callback: function() {
						bootbox.hideAll();
					}
				},
				success: {
					label: "<?php echo __('Yes, delete') ?>",
					className: "btn-primary",
					callback: function() {
						window.location = el.attr('href');
					}
				}
			}
		});
	})

</script>