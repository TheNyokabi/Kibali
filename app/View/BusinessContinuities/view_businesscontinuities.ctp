<?php if ( ! empty( $data ) ) : ?>
	<?php foreach ( $data as $risk ) : ?>
		<?php echo $this->element( '../BusinessContinuities/view_item', array(
			'risk' => $risk
		) ); ?>
	<?php endforeach; ?>
	<?php echo $this->element( CORE_ELEMENT_PATH . 'pagination' ); ?>

<?php else : ?>
	<?php echo $this->element( 'not_found', array(
		'message' => __( 'No Business Continuities found.' )
	) ); ?>
<?php endif; ?>