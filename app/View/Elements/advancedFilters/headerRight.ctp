<?php
if (!empty($filter['settings']['additional_actions'])) {
	$additionalLinks = $filter['settings']['additional_actions'];
}
?>
<div class="btn-toolbar pull-right">
	<div class="btn-group">
		<?php
		$filterUrl = '#advanced-filter-modal';
		if (empty($this->request->query['advanced_filter'])) {
			$filterUrl = (!empty($filter['settings']['url'])) ? $filter['settings']['url'] : array('action' => 'index', '?' => array('advanced_filter' => true));
		}
		echo $this->Html->link( '<i class="icon-search"></i>' . __('Filters'), $filterUrl, array(
			'class' => 'btn btn-info',
			'id' => 'advanced-filter-btn',
			'escape' => false,
			'data-toggle' => 'modal'
		));

		if (!empty($additionalLinks)) {
			echo $this->AdvancedFilters->getAdditionalFilterLinks($additionalLinks);
		}
		?>
	</div>
	
	<?php if (!empty($filter['settings']['trash'])) : ?>
		<div class="btn-group">
			<?php
			$url = array(
				'action' => 'trash',
				'?' => array(
					'advanced_filter' => 1
				)
			);
			if (is_array($filter['settings']['trash'])) {
				$url = $filter['settings']['trash'];
			}

			echo $this->Html->link('<i class="icon-trash"></i>' . __('Trash'), $url, array(
				'class' => 'btn btn-danger',
				'escape' => false
			));

			// if (!empty($additionalLinks)) {
			// 	echo $this->Trash->getAdditionalTrashLinks($additionalLinks);
			// }
			?>
		</div>
	<?php endif; ?>

	<?php if (!empty($showReset)) : ?>
		<div class="btn-group">
			<?php
			$resetUrl = (!empty($filter['settings']['reset'])) ? $filter['settings']['reset'] : array('action' => 'index');
			echo $this->Html->link(__('Reset'), $resetUrl, array(
				'class' => 'btn btn-info',
				'id' => 'advanced-filter-reset',
				'escape' => false
			));
			?>
		</div>
	<?php endif; ?>
</div>

<div class="modal fade scale" id="advanced-filter-modal" aria-hidden="false">
	<div class="modal-dialog modal-lg"> 
		<div class="modal-content" id="advanced-filter-modal-content"> 
			<div class="modal-header"> 
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><?php echo __('Advanced Filter') ?></h4>
			</div>
			<div class="modal-body" id="advanced-filter-modal-body">
				<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'filter'); ?>
			</div>
			<div class="modal-footer">
				<div class="advanced-filter-limit">
					<?php echo $this->Form->input('advanced_filter_limit', array(
						'type' => 'select',
						'options' => getFilterPageLimits(),
						'label' => false,
						'div' => false,
						'class' => 'form-control',
						'id' => 'advanced-filter-limit-select'
					)); ?>
					<label><?php echo __('per page') ?></label>
				</div>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close') ?></button>
				<button type="button" class="btn btn-success advanced-filter-submit" data-dismiss="modal"><?php echo __('Filter') ?></button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$(".advanced-filter-submit").on('click', function(e){
			$('.advanced-filter-form').submit();
			return false;
		});

		$(document).on("shown.bs.modal", function(event) {
			$('select.select2').select2();
			$( ".datepicker-advanced-filters" ).datepicker({
				//defaultDate: +7,
				showOtherMonths:true,
				autoSize: true,
				dateFormat: 'yy-mm-dd'// 'dd-mm-yy'
			});
		});
	});
</script>