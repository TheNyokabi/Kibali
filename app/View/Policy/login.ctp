<?php if (!empty($onlyGuestLogin) && empty($hasPublicDocs)) : ?>
	<div id="login-box">
		<h2 style="margin-top: 35px;"><?php echo __('There isn\'t any document published yet!'); ?></h2>
	</div>

	<?php return; ?>
<?php endif; ?>


<div id="login-box"> 
	<?php if (!empty($onlyGuestLogin)) : ?>
		<?php
		$guestHeadline = __('Login as guest');
		?>

	<?php else : ?>
		<?php
		$guestHeadline = __('or Login as guest');
		?>

		<h2><?php echo __('Login with your credentials'); ?></h2>

		<?php
		echo $this->Form->create('PolicyUser', array(
			'url' => array(
				'controller' => 'policy',
				'action' => 'login',
			)
		));
		?>
			<?php
			echo $this->Form->input('login', array(
				'label' => __('Username'),
				'autofocus' => 'autofocus',
				'data-rule-required' => 'true',
				'data-msg-required' => __( 'Please enter your username.' )
			));
			?>

			<?php
			echo $this->Form->input('password', array(
				'label' => __('Password'),
				'autofocus' => 'autofocus',
				'data-rule-required' => 'true',
				'data-msg-required' => __( 'Please enter your password.' )
			));
			?>
			<div class="submit">
				<?php
				echo $this->Form->button(__('Login'), array(
					'type' => 'submit',
					// 'class' => 'submit btn btn-primary pull-right',
					// 'div' => false
				));
				?>
			</div>
		<?php echo $this->Form->end(); ?>
	<?php endif; ?>

	<?php if ($hasPublicDocs) : ?>
		<div class="desc">
			<div class="line"></div>
			<div class="text"><?php echo $guestHeadline; ?></div>
		</div>

		<div class="guest">
			<?php
			echo $this->Html->link(__('Login as Guest'),
				array('controller' => 'policy', 'action' => 'guestLogin'),
				array('class' => 'guest-link btn red')
			);
			?>
		</div>
	<?php endif; ?>

</div>