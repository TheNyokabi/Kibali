<?php if ( ! empty( $business_units ) ) : ?>
	<?php foreach ( $business_units as $entry ) : ?>
		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo '(' . count($entry['BusinessContinuity']) . ') ' . __('Business Continuity Risk'); ?>: <?php echo $entry['BusinessUnit']['name']; ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if ( ! empty( $entry['BusinessContinuity'] ) ) : ?>
					<?php foreach ( $entry['BusinessContinuity'] as $tmpRisk ) : ?>
						<?php $risk = $data[ $tmpRisk['id'] ]; ?>

						<?php echo $this->element( '../BusinessContinuities/view_item', array(
							'risk' => $risk
						) ); ?>
					
					<?php endforeach; ?>

				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Business Continuities related to this Business Unit found.' )
					) ); ?>
				<?php endif; ?>

			</div>
		</div>

	<?php endforeach; ?>

	<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
<?php else : ?>
	<?php echo $this->element( 'not_found', array(
		'message' => __( 'No Business Units found.' )
	) ); ?>
<?php endif; ?>