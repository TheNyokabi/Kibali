<div class="row">
	<div class="col-md-12">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">
				<?php
				if (isset($edit)) {
					echo $this->Form->create('SecurityPolicyDocumentType', [
						'url' => ['controller' => 'securityPolicyDocumentTypes', 'action' => 'edit'],
						'class' => 'form-horizontal row-border',
						'novalidate' => true
					]);
					echo $this->Form->input('id', ['type' => 'hidden']);
					$submitLabel = __('Edit');
				}
				else {
					echo $this->Form->create('SecurityPolicyDocumentType', [
						'url' => ['controller' => 'securityPolicyDocumentTypes', 'action' => 'add'],
						'class' => 'form-horizontal row-border',
						'novalidate' => true
					]);
					$submitLabel = __('Add');
				}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">
							<?php
							echo $this->FieldData->inputs([
								$FieldDataCollection->name,
							]);
							?>
						</div>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit($submitLabel, [
						'class' => 'btn btn-primary',
						'div' => false
					]); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('SecurityPolicyDocumentType');
					?>
				</div>

				<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>