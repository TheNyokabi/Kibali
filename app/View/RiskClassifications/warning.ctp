<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('RiskClassificationWarning', array(
					'url' => array('controller' => 'riskClassifications', 'action' => 'edit', $classificationId),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));

				echo $this->Form->input('__warning_proceeded', array(
					'type' => 'hidden',
					'name' => '__warning_proceeded',
					'value' => 1
				));
				?>

				<div class="alert alert-danger">
					<?php
					echo $warning;
					?>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit(__('Continue'), array(
						'class' => 'btn btn-danger',
						'div' => false
					)); ?>
					&nbsp;
					<?php echo $this->Html->link(__('Cancel'), '#', array(
						'class' => 'btn btn-inverse',
						'data-dismiss' => 'modal'
					)); ?>
				</div>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>