<div class="box">
	<div class="content">

		<?php echo $this->Form->create( 'AwarenessUser', array(
			'url' => array(
				'controller' => 'awareness',
				'action' => 'login',
			),
			'class' => 'form-vertical login-form'
		) ); ?>

			<h3 class="form-title"><?php echo __( 'Awareness Module Login' ); ?></h3>

			<!-- Input Fields -->
			<div class="form-group">
				<div class="input-icon">
					<i class="icon-user"></i>
					<?php echo $this->Form->input( 'login', array(
						'label' => false, 
						'div' => false,
						'placeholder' => __( 'Username' ),
						'class' => 'form-control',
						'autofocus' => 'autofocus',
						'data-rule-required' => 'true',
						'data-msg-required' => __( 'Please enter your username.' )
					) ); ?>
				</div>
			</div>
			<div class="form-group">
				<div class="input-icon">
					<i class="icon-lock"></i>
					<?php echo $this->Form->input( 'password', array(
						'label' => false, 
						'div' => false,
						'placeholder' => __( 'Password' ),
						'class' => 'form-control',
						'data-rule-required' => 'true',
						'data-msg-required' => __( 'Please enter your password.' )
					) ); ?>
				</div>
			</div>
			<!-- /Input Fields -->

			<!-- Form Actions -->
			<div class="form-actions">
				<?php echo $this->Form->submit( __( 'Login' ), array(
					'class' => 'submit btn btn-primary pull-right',
					'div' => false
				) ); ?>
			</div>

		<?php echo $this->Form->end(); ?>

	</div>
</div>