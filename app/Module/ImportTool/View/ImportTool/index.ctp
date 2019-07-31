<div class="widget">
	<div class="btn-toolbar">
		<?php
		echo $this->Html->link(__('Download Template'), $this->ImportTool->getDownloadUrl($model), array(
			'class' => 'btn btn-default'
		));

		echo $this->Html->link(__('Download Export'), $this->ImportTool->getDownloadUrl($model, true), array(
			'class' => 'btn btn-default'
		));
		?>
	</div>
</div>

<div class="widget">
	<div class="widget-content">
		<?php
		$alert = $this->Html->tag('strong', __('Warning!')) . '<br />';
		$alert .= __('Unless you have done this before and know the feature well, make sure you read the documentation on how to use imports!');
		
		$helperPath = 'import_tool/helpers/' . $model;
		if ($this->elementExists($helperPath)) {
			echo $this->element($helperPath, [
				'alert' => $alert
			]);
		}
		?>
	</div>
</div>

<div class="widget box">
	<div class="widget-content">


		<?php
		echo $this->Form->create('ImportTool', array(
			'url' => array('plugin' => 'importTool', 'controller' => 'importTool', 'action' => 'index', $model),
			'class' => 'form-horizontal row-border',
			'type' => 'file'
		));
		?>

		<div>
			<div class="form-group">
				<label class="col-md-2 control-label"><?php echo __('File Upload'); ?>:</label>
				<div class="col-md-10">
					<?php echo $this->Form->input( 'CsvFile', array(
						'type' => 'file',
						'label' => false,
						'div' => false,
						'class' => false,
						'data-style' => 'fileinput',
						'required' => false
					) ); ?>
					<span class="help-block"><?php echo __( 'Upload your CSV file here.' ); ?></span>
				</div>
			</div>
		</div>

		<div class="form-actions">
			<?php echo $this->Form->submit(__('Submit'), array(
				'class' => 'btn btn-primary',
				'div' => false
			) ); ?>
			&nbsp;
			<?php echo $this->Html->link( __( 'Cancel' ), array(
				'plugin' => null,
				'controller' => controllerFromModel($model),
				'action' => 'index'
			), array(
				'class' => 'btn btn-inverse'
			) ); ?>
		</div>

		<?php echo $this->Form->end(); ?>

	</div>
</div>
