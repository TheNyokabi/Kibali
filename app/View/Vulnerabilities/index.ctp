<?php
echo $this->element(CORE_ELEMENT_PATH . 'boostrapEditable');
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
									<?php echo __( 'Name' ); ?>
								</th>
								<th class="align-center"><?php echo __( 'Action' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $data as $entry ) : ?>
								<tr>
									<td>
										<?php
											echo $this->Html->tag(
												'span',
												$entry['Vulnerability']['name'],
												array('class' => 'name',
													'data-type' => 'text',
													'data-name' => 'name',
													'data-pk' => $entry['Vulnerability']['id']
												)
											);
										?>
									</td>
									<td class="align-center">
										<ul class="table-controls">
											<li>
												<?php echo $this->Html->link( '<i class="icon-pencil"></i>',
													'#',
													array(
													'class' => 'bs-tooltip editable-controller',
													'escape' => false,
													'title' => __( 'Edit' )
												) ); ?>
											</li>
											<li>
												<?php echo $this->Html->link( '<i class="icon-trash"></i>', array(
													'action' => 'delete',
													$entry['Vulnerability']['id']
												), array(
													'class' => 'bs-tooltip',
													'escape' => false,
													'title' => __( 'Trash' ),
													'data-ajax-action' => 'delete'
												) ); ?>
											</li>
										</ul>
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
						'<i class="icon-exclamation-sign"></i>' . __('No Vulnerabilities found.')
					);
					?>
				<?php endif; ?>
			</div>

		</div>
	</div>

</div>

<?php $liveEditUrl = Router::url(array('controller' => 'vulnerabilities', 'action' => 'liveEdit')); ?>

<script type="text/javascript">
	$(document).ready(function() {
		//defaults (url, pk)
		$.fn.editable.defaults.url = "<?php echo $liveEditUrl ?>";

	    $('.name').editable();

		$('.editable-controller').click(function(e){
			e.stopPropagation();
			$(this).closest('tr').find('.name').trigger("click");
		});
	});
</script>