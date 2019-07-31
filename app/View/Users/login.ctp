<?php echo $this->element('modal_login'); ?>

<div class="box">
	<div class="content">

		<?php echo $this->Form->create( 'User', array(
			'url' => array(
				'controller' => 'users',
				'action' => 'login',
			),
			'class' => 'form-vertical login-form'
		) ); ?>

			<h3 class="form-title"><?php echo __( 'Sign In to your Account' ); ?></h3>

			<!-- Language selector -->
			<div class="form-group form-group-language-selector">
				<?php
				echo $this->Form->input('language', array(
					'label' => false, 
					'div' => false,
					'options' => availableLangs(),
					'default' => Configure::read('Config.language'),
					'id' => 'language-selector',
					'style' => 'width:100%;'
				));
				?>
			</div>

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

	<div class="inner-box">
		<div class="content">
			<!-- Close Button -->
			<i class="icon-remove close hide-default"></i>

			<?php echo $this->Html->link( __( 'Forgot your Password?' ),
				array( 'controller' => 'users', 'action' => 'resetpassword' ),
				array( 'class' => 'forgot-password-link' )
			); ?>
		</div>
	</div>
</div>

<?php
if ($OauthGoogleAllowed):
?>
	<div class="single-sign-on">
		<span><?= __('or'); ?></span>
		<a class="btn btn-google-plus btn-block" href="<?= $OauthGoogleAuthUrl ?>"> <i class="icon-google-plus"></i><?= __('Sign in with Google'); ?></a>
	</div>
<?php
endif;
?>


<script type="text/javascript">
	jQuery(function($) {
		var $langSelector = $("#language-selector");
		var langUrl = "<?php echo Router::url(array('controller' => 'users', 'action' => 'changeLanguage')); ?>";

		$langSelector.select2({
			minimumResultsForSearch: -1
		});
		$langSelector.on("change", function(e) {
			setPseudoNProgress();

			var lang = $(this).val();
			window.location.href = langUrl + "/" + lang;
		});

		$(".login-form").on("submit", function(e) {
			setPseudoNProgress();
		});
	});
</script>