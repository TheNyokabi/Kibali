<?php if ( ! empty( $assets ) ) : ?>
	<?php foreach ( $assets as $entry ) : ?>
		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo '(' . count($entry['Risk']) . ') ' . __('Asset Risk'); ?>: <?php echo $entry['Asset']['name']; ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if ( ! empty( $entry['Risk'] ) ) : ?>
					<?php foreach ( $entry['Risk'] as $tmpRisk ) : ?>
						<?php $risk = $data[ $tmpRisk['id'] ]; ?>

						<?php echo $this->element( '../Risks/view_item', array(
							'risk' => $risk
						) ); ?>
						
					<?php endforeach; ?>

				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Risks related to this Asset found.' )
					) ); ?>
				<?php endif; ?>

			</div>
		</div>

	<?php endforeach; ?>

	<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
<?php else : ?>
	<?php echo $this->element( 'not_found', array(
		'message' => __( 'No Assets found.' )
	) ); ?>
<?php endif; ?>