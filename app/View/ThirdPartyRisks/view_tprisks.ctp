<?php if ( ! empty( $data ) ) : ?>
	<?php foreach ( $data as $risk ) : ?>
		<?php echo $this->element( '../ThirdPartyRisks/view_item', array(
			'risk' => $risk
		) ); ?>
	<?php endforeach; ?>
	<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>

<?php else : ?>
	<?php echo $this->element( 'not_found', array(
		'message' => __( 'No Third Party Risks found.' )
	) ); ?>
<?php endif; ?>
