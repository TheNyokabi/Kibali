<div class="row">

	<div class="col-md-12">
		<div class="widget">
			<div class="btn-toolbar">
				<div class="btn-group">
					<?php
					echo $this->Html->link('<i class="icon-file"></i>' . __('Export PDF'), array(
						'controller' => 'programHealth',
						'action' => 'exportPdf'
					), array(
						'class' => 'btn',
						'escape' => false
					));
					?>
				</div>
				

				<?php echo $this->Video->getVideoLink('ProgramHealth'); ?>
			</div>
		</div>
	</div>
</div>


<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Risks'); ?></h4>
	</div>
	<div class="widget-subheader">
		<?php
		echo $this->element('pdf/programHealth/risks', array(
			'model' => 'Risk'
		));
		?>
	</div>
</div>

<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Third Party Risks'); ?></h4>
	</div>
	<div class="widget-subheader">
		<?php
		echo $this->element('pdf/programHealth/risks', array(
			'model' => 'ThirdPartyRisk'
		));
		?>
	</div>
</div>

<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Business Impact Analysis'); ?></h4>
	</div>
	<div class="widget-subheader">
		<?php
		echo $this->element('pdf/programHealth/risks', array(
			'model' => 'BusinessContinuity'
		));
		?>
	</div>
</div>

<!-- <div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Assets'); ?></h4>
	</div>
	<div class="widget-subheader">
		<?php
		//echo $this->element('pdf/programHealth/assets');
		?>
	</div>
</div> -->

<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Security Services'); ?></h4>
	</div>
	<div class="widget-subheader">
		<?php
		echo $this->element('pdf/programHealth/securityServices');
		?>
	</div>
</div>

<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Projects'); ?></h4>
	</div>
	<div class="widget-subheader">
		<?php
		echo $this->element('pdf/programHealth/projects');
		?>
	</div>
</div>

<div class="widget box">
	<div class="widget-header">
		<h4><?php echo __('Compliance Analysis'); ?></h4>
	</div>
	<div class="widget-subheader">
		<?php
		echo $this->element('pdf/programHealth/compliance');
		?>
	</div>
</div>