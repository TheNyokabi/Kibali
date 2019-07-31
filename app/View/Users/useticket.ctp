<div class="box">
	<div class="content">

		<?php echo $this->Form->create( 'User', array(
			'url' => array(
				'controller' => 'users',
				'action' => 'useticket',
			),
			'class' => 'form-vertical login-form',
			'novalidate' => true
		) ); ?>

			<h3 class="form-title"><?php echo __( 'Change your password' ); ?></h3>
			<p><?php echo __('Please enter your new password below'); ?></p>
			<br />

			<!-- Input Fields -->
			<div class="form-group">
				<div class="input-icon">
					<i class="icon-lock"></i>
					<?php echo $this->Form->input( 'pass', array(
						'type' => 'password',
						'label' => false, 
						'div' => false,
						'placeholder' => __( 'New password' ),
						'class' => 'form-control',
						'autofocus' => 'autofocus',
						'data-rule-required' => 'true',
						'data-msg-required' => __( 'Please enter your new password.' ),
						// 'error' => array(
						// 	'between' => __('Passwords must be between 8 and 30 characters long.'),
						// 	'compare' => __('Password and verify password must be same.')
						// )
					) ); ?>
				</div>
			</div>
			<div class="form-group">
				<div class="input-icon">
					<i class="icon-lock"></i>
					<?php echo $this->Form->input( 'pass2', array(
						'type' => 'password',
						'label' => false, 
						'div' => false,
						'placeholder' => __( 'Verify password' ),
						'class' => 'form-control',
						'data-rule-required' => 'true',
						'data-msg-required' => __( 'Please enter your new password again.' )
					) ); ?>
				</div>
			</div>

			<?php
			echo $this->Users->passwordPolicyAlert();
			?>
			<!-- /Input Fields -->

			<?php echo $this->Form->input('hash', array('type' => 'hidden')); ?>

			<!-- Form Actions -->
			<div class="form-actions">
				<?php echo $this->Form->submit( __( 'Change password' ), array(
					'class' => 'submit btn btn-primary pull-right',
					'div' => false
				) ); ?>
			</div>

		<?php echo $this->Form->end(); ?>

	</div>
</div>