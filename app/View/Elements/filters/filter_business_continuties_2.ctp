<div class="row">
	<div class="col-md-12">
		<?php echo $this->Form->create('BusinessContinuity', array(
			'url' => array('controller' => 'businessContinuities', 'action' => 'index'),
			'class' => 'filter-form form-horizontal'
		)); ?>
			<div class="form-group">
				<label class="col-md-4 control-label"><?php echo __( 'Search' ); ?>:</label>
				<div class="col-md-8">
					<?php echo $this->Form->input( 'search', array(
						'label' => false,
						'div' => false,
						'class' => 'form-control'
					) ); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label"><?php echo __( 'Business Unit' ); ?>:</label>
				<div class="col-md-8">
					<?php echo $this->Form->input( 'bu_id', array(
						'options' => $bus,
						'label' => false,
						'div' => false,
						'class' => 'form-control',
						'empty' => __( 'All Business Units' )
					) ); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label"><?php echo __( 'Owner' ); ?>:</label>
				<div class="col-md-8">
					<?php echo $this->Form->input( 'user_id', array(
						'options' => $owners,
						'label' => false,
						'div' => false,
						'class' => 'form-control',
						'empty' => __( 'All Owners' )
					) ); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label"><?php echo __( 'Stakeholder' ); ?>:</label>
				<div class="col-md-8">
					<?php echo $this->Form->input( 'guardian_id', array(
						'options' => $users,
						'label' => false,
						'div' => false,
						'class' => 'form-control',
						'empty' => __( 'All Stakeholders' )
					) ); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label"><?php echo __( 'Expired Reviews' ); ?>:</label>
				<div class="col-md-8">
					<?php echo $this->Form->input( 'expired_reviews', array(
						'options' => getStatusFilterOption(),
						'label' => false,
						'div' => false,
						'class' => 'form-control',
						'empty' => __( 'All' )
					) ); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-4 control-label"><?php echo __( 'Risk above appetite' ); ?>:</label>
				<div class="col-md-8">
					<?php echo $this->Form->input( 'risk_above_appetite', array(
						'options' => getStatusFilterOption(),
						'label' => false,
						'div' => false,
						'class' => 'form-control',
						'empty' => __( 'All' )
					) ); ?>
				</div>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>