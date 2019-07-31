<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">
				<?php
				echo $this->Form->create('RiskClassificationDelete', array(
					'url' => array('controller' => 'riskClassifications', 'action' => 'delete', $this->data['RiskClassification']['id']),
					'class' => 'form-horizontal row-border',
					'novalidate' => true
				));

				echo $this->Form->input('_delete', array(
					'type' => 'hidden',
					'value' => 1
				));
				?>

				<div class="alert alert-danger">
					<?php if (!empty($calculationRestrictDelete)) : ?>
						<?php
						echo __('This classification is used by %1$s, the current risk methodology. Since this is the last item for this type (%2$s) we can’t delete it. Please create a new classification type and apply it to %1$s then you will be able to delete this item.', $calculationRestrictDelete['method'], $calculationRestrictDelete['classificationType']);
						?>
					<?php else : ?>
						<?php
						echo __('Are you really sure you want to delete item: <strong>%s</strong>?', $this->data['RiskClassification']['name']);
						?>

						<?php if (!empty($isUsed)) : ?>
							<br /><br />
							<strong><?php echo __('This classification is being used by %d Risks, if you continue these Risks will get its score set to zero and you will need to re-edit them and re-classify them. A system record will be included on every risk notifying this change.', $isUsed); ?></strong>
						<?php endif; ?>
					<?php endif; ?>
				</div>

				<div class="form-actions">
					<?php if (empty($calculationRestrictDelete)) : ?>
						<?php echo $this->Form->submit(__('Delete'), array(
							'class' => 'btn btn-danger',
							'div' => false
						)); ?>
						&nbsp;
					<?php endif; ?>
					<?php echo $this->Html->link(__('Cancel'), array(
						'action' => 'index'
					), array(
						'class' => 'btn btn-inverse'
					)); ?>
				</div>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>