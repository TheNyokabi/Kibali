<?php
App::uses('RiskAppetite', 'Model');
?>
<div class="row">
	<div class="col-lg-12">

		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					echo $this->Form->create( 'RiskAppetite', array(
						'url' => array( 'controller' => 'riskAppetites', 'action' => 'edit', 1 ),
						'class' => 'form-horizontal row-border',
						'novalidate' => true
					) );

					echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
					$submit_label = __( 'Update' );
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<?php $i=0; foreach ($methods as $slug => $method) : ?>
							<?php
							$class = '';
							if ((!empty($this->data['RiskAppetite']['method']) && $this->data['RiskAppetite']['method'] == $slug) || (empty($this->data['RiskAppetite']['method']) && $i == 0)) {
								$activeSlug = $slug;
								$class = 'active';
							}
							?>

							<li class="<?php echo $class; ?>">
								<a href="#tab_<?php echo $slug; ?>" data-toggle="tab"><?php echo $method; ?></a>
							</li>
						<?php $i++; endforeach; ?>
					</ul>
					<div class="tab-content">
						<?php $i=0; foreach ($methods as $slug => $method) : ?>
							<div class="tab-pane fade in <?php if ($activeSlug == $slug) echo 'active'; ?>" id="tab_<?php echo $slug; ?>">

								<div class="row">
									<div class="col-xs-8">
										<dl class="dl-horizontal">
											<dt><?php echo __('Appetite Method Name'); ?></dt>
											<dd><?php echo $method; ?></dd>

											<dt>&nbsp;</dt>
											<dd>&nbsp;</dd>

											<dt><?php echo __('Settings'); ?></dt>
											<dd>
												<?php if ($slug == RiskAppetite::TYPE_INTEGER) : ?>
													<?php
													echo $this->element('risk_appetites/integer_settings');
													?>
												<?php endif; ?>

												<?php if ($slug == RiskAppetite::TYPE_THRESHOLD) : ?>
													<?php
													echo $this->element('risk_appetites/threshold_settings');
													?>
												<?php endif; ?>
											</dd>
										</dl>
									</div>

									<div class="col-xs-3">
										<?php
										$labelFor = 'method-' . $slug;
										?>
										<label class="checkbox" for="<?php echo $labelFor; ?>">
											<?php
											$label = __('Enable');
											echo $this->Form->input('method', array(
												'type' => 'checkbox',
												'label' => '<strong>' . $label . '</strong>',
												'div' => false,
												'class' => 'uniform risk-method-checkbox',
												'value' => $slug,
												'checked' => ($slug == $activeSlug) ? true : false,
												'id' => $labelFor
											));
											?>
										</label>
									</div>
								</div>
							</div>
						<?php $i++; endforeach; ?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('RiskAppetite');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	
</div>

<script type="text/javascript">
	jQuery(function($) {
		$(".risk-method-checkbox").on("change", function(e) {
			var $inputs = $(".risk-method-checkbox").not($(this));
			$inputs.prop("checked", false);
			$.uniform.update($inputs);
		});
	});
</script>