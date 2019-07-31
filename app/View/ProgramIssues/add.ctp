<div class="row">
	<div class="col-md-7">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'ProgramIssue', array(
							'url' => array( 'controller' => 'programIssues', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'ProgramIssue', array(
							'url' => array( 'controller' => 'programIssues', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>

						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Name'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('name', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __( 'A brief title for this issue.' ); ?></span>
								</div>
							</div>


							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Issue Source'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('issue_source', array(
										'options' => getProgramIssueSources(),
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'id' => 'issue-sources',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('Issues fall in  two categories, those that happen in an internal or external context. Select one of the options and you will provided with categories for each.'); ?></span>
								</div>
							</div>

							<div class="form-group" id="internal-types">
								<label class="col-md-2 control-label"><?php echo __('Internal Types'); ?>:</label>
								<div class="col-md-10">
									<?php
									$selected = array();
									if (!empty($edit) && !empty($this->request->data['ProgramIssueType']) && $this->request->data['ProgramIssue']['issue_source'] == PROGRAM_ISSUE_INTERNAL) {
										foreach ($this->request->data['ProgramIssueType'] as $item) {
											$selected[] = $item['type'];
										}
									}

									if (isset($this->request->data['ProgramIssue']['types']) && is_array($this->request->data['ProgramIssue']['types']) && $this->request->data['ProgramIssue']['issue_source'] == PROGRAM_ISSUE_INTERNAL) {
										$selected = array();
										foreach ($this->request->data['ProgramIssue']['types'] as $entry) {
											$selected[] = $entry;
										}
									}
									echo $this->Form->input('types', array(
										'options' => $internalTypes,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'selected' => $selected
									));
									?>
									<span class="help-block"><?php echo __('Select one or more categories for your internal concerns.'); ?></span>
								</div>
							</div>

							<div class="form-group" id="external-types">
								<label class="col-md-2 control-label"><?php echo __('External Types'); ?>:</label>
								<div class="col-md-10">
									<?php
									$selected = array();
									if (!empty($edit) && !empty($this->request->data['ProgramIssueType']) && $this->request->data['ProgramIssue']['issue_source'] == PROGRAM_ISSUE_EXTERNAL) {
										foreach ($this->request->data['ProgramIssueType'] as $item) {
											$selected[] = $item['type'];
										}
									}

									if (isset($this->request->data['ProgramIssue']['types']) && is_array($this->request->data['ProgramIssue']['types']) && $this->request->data['ProgramIssue']['issue_source'] == PROGRAM_ISSUE_EXTERNAL) {
										$selected = array();
										foreach ($this->request->data['ProgramIssue']['types'] as $entry) {
											$selected[] = $entry;
										}
									}
									echo $this->Form->input('types', array(
										'options' => $externalTypes,
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
										'hiddenField' => false,
										'selected' => $selected
									)); ?>
									<span class="help-block"><?php echo __('Select one or more categories for your external concerns.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Description'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									));
									?>
									<span class="help-block"><?php echo __('Provide a brief description of the issue.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Status'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('status', array(
										'options' => getProgramIssueStatuses(),
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose one')
									));
									?>
									<span class="help-block"><?php echo __('The status should be one of the following: "Draft" (the issue is being identified and documented), "Current" (the issue is active) or "Discarded" (the issue is no longer applicable).'); ?></span>
								</div>
							</div>
						</div>

						<?php
						echo $this->element('CustomFields.tabs_content');
						?>
					</div>
				</div>

				<div class="form-actions">
					<?php
					echo $this->Form->submit($submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					));
					?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('ProgramIssue');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-5">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'ProgramIssue',
			'id' => isset($edit) ? $this->data['ProgramIssue']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
	jQuery(function($) {
		$("#issue-sources").on("change", function(e) {
			$("#external-types").hide();
			$("#external-types select").attr("disabled", "disabled");
			$("#internal-types").hide();
			$("#internal-types select").attr("disabled", "disabled");

			if ($(this).val() == "<?php echo PROGRAM_ISSUE_INTERNAL; ?>") {
				$("#internal-types").show();
				$("#internal-types select").removeAttr("disabled");
			}

			if ($(this).val() == "<?php echo PROGRAM_ISSUE_EXTERNAL; ?>") {
				$("#external-types").show();
				$("#external-types select").removeAttr("disabled");
			}
		}).trigger("change");
	});
</script>
