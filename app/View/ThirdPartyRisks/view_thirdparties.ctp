<?php if ( ! empty( $third_parties ) ) : ?>
	<?php foreach ( $third_parties as $entry ) : ?>
		<div class="widget box widget-closed">
			<div class="widget-header">
				<h4><?php echo '(' . count($entry['ThirdPartyRisk']) . ') ' . __('Third Party Risk'); ?>: <?php echo $entry['ThirdParty']['name']; ?></h4>
				<div class="toolbar no-padding">
					<div class="btn-group">
						<span class="btn btn-xs widget-collapse"><i class="icon-angle-up"></i></span>
					</div>
				</div>
			</div>
			<div class="widget-content" style="display:none;">
				<?php if ( ! empty( $entry['ThirdPartyRisk'] ) ) : ?>
					<?php foreach ( $entry['ThirdPartyRisk'] as $tmpRisk ) : ?>
						<?php $risk = $data[ $tmpRisk['id'] ]; ?>

						<?php echo $this->element( '../ThirdPartyRisks/view_item', array(
							'risk' => $risk
						) ); ?>
		
					<?php endforeach; ?>

				<?php else : ?>
					<?php echo $this->element( 'not_found', array(
						'message' => __( 'No Third Party Risks related to this Third Party found.' )
					) ); ?>
				<?php endif; ?>

			</div>
		</div>

	<?php endforeach; ?>

	<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>
<?php else : ?>
	<?php echo $this->element( 'not_found', array(
		'message' => __( 'No Third Parties found.' )
	) ); ?>
<?php endif; ?>