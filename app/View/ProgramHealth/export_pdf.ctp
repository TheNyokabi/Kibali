<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="title">
				<h1>
					<?php echo __('Program Health'); ?>
				</h1>
			</div>
		</div>

	</div>
</div>


<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Risks'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<?php
				echo $this->element('pdf/programHealth/risks', array(
					'pdf' => true,
					'model' => 'Risk'
				));
				?>
			</div>
		</div>
	</div>
</div>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Third Party Risks'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<?php
				echo $this->element('pdf/programHealth/risks', array(
					'pdf' => true,
					'model' => 'ThirdPartyRisk'
				));
				?>
			</div>
		</div>
	</div>
</div>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Business Continuities'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<?php
				echo $this->element('pdf/programHealth/risks', array(
					'pdf' => true,
					'model' => 'BusinessContinuity'
				));
				?>
			</div>
		</div>
	</div>
</div>

<div class="separator"></div>


<!-- <div class="separator"></div> -->
<!-- 
<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Assets'); ?>
				</h2>
			</div>
		</div>

	</div>
</div> -->
<!-- 
<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<?php
				/*echo $this->element('pdf/programHealth/assets', array(
					'pdf' => true
				));*/
				?>
			</div>
		</div>
	</div>
</div> -->

<!-- <div class="separator"></div> -->

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Security Services'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<?php
				echo $this->element('pdf/programHealth/securityServices', array(
					'pdf' => true
				));
				?>
			</div>
		</div>
	</div>
</div>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Projects'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<?php
				echo $this->element('pdf/programHealth/projects', array(
					'pdf' => true
				));
				?>
			</div>
		</div>
	</div>
</div>

<div class="separator"></div>

<div class="row">
	<div class="col-xs-12">

		<div class="header">
			<div class="subtitle">
				<h2>
					<?php echo __('Compliance Analysis'); ?>
				</h2>
			</div>
		</div>

	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="body">
			<div class="item">
				<?php
				echo $this->element('pdf/programHealth/compliance', array(
					'pdf' => true
				));
				?>
			</div>
		</div>
	</div>
</div>