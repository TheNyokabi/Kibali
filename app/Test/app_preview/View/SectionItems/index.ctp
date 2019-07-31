<?php
// echo $this->SectionDispatch->toolbar('SectionItem', ['my','custom','array','args']);
?>

<div class="widget">
	<div class="btn-toolbar">
		<div class="btn-group">
			<?php echo $this->Ajax->addAction(); ?>
		</div>

		<?php echo $this->ImportTool->getIndexLink('SectionItem'); ?>

		<?php echo $this->AdvancedFilters->getViewList($savedFilters, 'SectionItem'); ?>

		<?php echo $this->NotificationSystem->getIndexLink('SectionItem'); ?>

		<?php
		$SectionItem = _getModelInstance('SectionItem');

		echo $this->CustomFields->getIndexLink(array(
			'SectionItem' => $SectionItem->label()
		));
		?>

		<?php
		// new toolbar buttons
		echo $this->Html->div(
			'btn-group',
			$this->Modules->getToolbar('SectionItem')
		);
		?>

		<?php echo $this->element(ADVANCED_FILTERS_ELEMENT_PATH . 'headerRight'); ?>
	</div>
</div>

<?php echo $this->element( CORE_ELEMENT_PATH . 'active_filter_box', array('filterName' => 'SectionItem')); ?>

<div class="row">
	<div class="col-md-12">
			<?php if ( ! empty( $data ) ) : ?>
				<?php foreach ( $data as $item ) : ?>

					<div class="widget box widget-closed">
						<div class="widget-header">
							<h4><?php echo __('Section Item: %s', $item['SectionItem']['varchar']); ?></h4>
							<div class="toolbar no-padding">
								<div class="btn-group">
									<span class="btn btn-xs dropdown-toggle" data-toggle="dropdown">
										<?php echo __( 'Manage' ); ?> <i class="icon-angle-down"></i>
									</span>
									<?php
									echo $this->SectionDispatch->getActionList('SectionItem', $item);
									// the same as
									// echo $this->SectionItems->getActionList($item);
									?>
								</div>
							</div>
						</div>

						<div class="widget-subheader">
							<table class="table table-hover table-striped table-bordered table-highlight-head">
								<thead>
									<tr>
										<th>
											<?php
											echo $FieldDataCollection->user_id->label();
											?>
										</th>
										<th>
											<?php
											echo $FieldDataCollection->HasAndBelongsToMany->label();
											?>
										</th>
										<th>
											<?php
											echo $FieldDataCollection->Tag->label();
											?>
										</th>
										<th>
											<?php
											echo $FieldDataCollection->date->label();
											?>
										</th>
										<th>
											<?php echo __('Status'); ?>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<?php
											echo $this->SectionItems->getBelongsTo($item);
											?>
										</td>
										<td>
											<?php
											echo $this->SectionItems->getHasAndBelongsToMany($item);
											?>
										</td>
										<td>
											<?php
											echo $this->SectionItems->getTags($item);
											?>
										</td>
										<td>
											<?php
											echo $this->SectionItems->getDate($item);
											?>
										</td>
										<td class="text-center">
											<?php
											echo $this->SectionItems->getStatuses($item);
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
							<div class="widget-subheader">
							<table class="table table-hover table-striped table-bordered table-highlight-head">
								<thead>
									<tr>
										<th>
											<?php
											echo $FieldDataCollection->text->label();
											?>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>
											<?php
											echo $this->SectionItems->getText($item);
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="widget-subheader">
							<?php
							echo $this->element('CustomFields.' . CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH . 'accordion', array(
								'item' => $item // single database item in a variable
							));
							?>
					    </div>

					</div>
				<?php endforeach; ?>

				<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
			<?php else : ?>
				<?php echo $this->element( 'not_found', array(
					'message' => __( 'No Items found.' )
				) ); ?>
			<?php endif; ?>
		</div>

	</div>
</div>
