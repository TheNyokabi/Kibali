<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
				echo $this->Form->create( 'CompliancePackage', array(
					'url' => array( 'controller' => 'compliancePackages', 'action' => 'import' ),
					'class' => 'form-horizontal row-border',
					'type' => 'file'
				) );
				
				$submit_label = __( 'Import' );
				?>

				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __( 'Third Party' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'third_party_id', array(
							'options' => $third_parties,
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						) ); ?>
						<span class="help-block"><?php echo __( 'Select the third party (from Organisation / Third Parties - only those that are tagged as "Regulators") which will hold compliance requirements.' ); ?>
					</div>
				</div>

				<div class="form-group form-group">
					<label class="col-md-2 control-label"><?php echo __( 'File Upload' ); ?>:</label>
					<div class="col-md-10">
						<?php echo $this->Form->input( 'CsvFile', array(
							'type' => 'file',
							'label' => false,
							'div' => false,
							'class' => false,
							'data-style' => 'fileinput'
						) ); ?>
						<span class="help-block"><?php echo __( 'Upload the Compliance Package in CSV format. Remember we hold a database of pre-compiled packages for most well known frameworks (PCI, ISO, Etc) on our documentation.' ); ?></span>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php echo $this->Html->link( __( 'Cancel' ), array(
						'action' => 'index'
					), array(
						'class' => 'btn btn-inverse'
					) ); ?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>
