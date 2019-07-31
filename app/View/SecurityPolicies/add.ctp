<?php
echo $this->Html->script('tinymce/tinymce.min', array('inline' => true));
?>

<div class="row">
	<div class="col-md-8">
		<div class="widget box widget-form">
			<div class="widget-header">
				<h4>&nbsp;</h4>
			</div>
			<div class="widget-content">

				<?php
					if (isset($edit)) {
						echo $this->Form->create( 'SecurityPolicy', array(
							'url' => array( 'controller' => 'securityPolicies', 'action' => 'edit' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
						$submit_label = __( 'Edit' );
					}
					else {
						echo $this->Form->create( 'SecurityPolicy', array(
							'url' => array( 'controller' => 'securityPolicies', 'action' => 'add' ),
							'class' => 'form-horizontal row-border',
							'novalidate' => true
						) );

						$submit_label = __( 'Add' );
					}

					if ($disabledReviewFields) {
						echo $this->Form->input('_disableReviewFields', array(
							'type' => 'hidden',
							'value' => 1
						));
					}
				?>

				<div class="tabbable box-tabs box-tabs-styled">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_general" data-toggle="tab"><?php echo __('General'); ?></a></li>
						<li class=""><a href="#tab_policy_content" data-toggle="tab"><?php echo __('Policy Content'); ?></a></li>
						<li class=""><a href="#tab_related_documents" data-toggle="tab"><?php echo __('Related Documents'); ?></a></li>
						<li class=""><a href="#tab_access" data-toggle="tab"><?php echo __('Access Restrictions'); ?></a></li>

						<?php
						echo $this->element('CustomFields.tabs');
						?>
					</ul>
					<div class="tab-content">
						<div class="tab-pane fade in active" id="tab_general">

							<div class="form-group form-group-first">
								<label class="col-md-2 control-label"><?php echo __('Title'); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'index', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'This is usually the title of the policy, for example: "Network Policies".' ); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Short Description'); ?>:</label>
								<div class="col-md-10">
									<?php
									$Model = ClassRegistry::init('SecurityPolicy');
									$shortDescription = $Model->schema('short_description');
									$maxLength = $shortDescription['length'];

									echo $this->Form->input('short_description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control auto limited',
										'data-limit' => $maxLength,
										'rows' => 3
									));
									?>
									 <span class="help-block"><?php echo __( 'OPTIONAL: Provide a description for this policy, this is typically an introduction parragraph. This will be displayed on the Policy Portal (if enabled under System / Settings / Authentication).' ); ?></span>
								</div>
							</div>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Owner
							]); ?>

							<?= $this->FieldData->inputs([
								$FieldDataCollection->Collaborator
							]); ?>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Tags'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->element('taggable_field', array(
										'placeholder' => __('Add a tag'),
										'model' => 'SecurityPolicy'
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Select existing or create new tags for this document. Tags are useful for filtering policies and also are used on the policy portal.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Published Date'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('published_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker'
									));
									?>
									<span class="help-block"><?php echo __('The date the policy was created.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Next Review Date'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('next_review_date', array(
										'type' => 'text',
										'label' => false,
										'div' => false,
										'class' => 'form-control datepicker',
										'disabled' => $disabledReviewFields
									));
									?>
									<span class="help-block"><?php echo __('Policies must be reviewed at regular periods of time in order to ensure they remain useful. Setting a review date will enable the owner of the document to get a notification and ensure the review is executed by the collaborators. This date is used to trigger notifications (reminders) to owners and collaborators.'); ?></span>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __('Label'); ?>:</label>
								<div class="col-md-9">
									<?php
									echo $this->Form->input('asset_label_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose an Asset Label')
									));
									?>
									<span class="help-block"><?php echo __('OPTIONAL: Choose one of the Asset Labels, this labels are originated at Asset Management / Asset Identification / Settings / Labels)'); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'assetLabels', 'action' => 'add'),'text' => 'Add Label')); ?>
								</div>
							</div>

							<div class="form-group form-group-quick-create">
								<label class="col-md-2 control-label"><?php echo __( 'Projects' ); ?>:</label>
								<div class="col-md-9">
									<?php echo $this->Form->input( 'SecurityPolicy.Project', array(
										'label' => false,
										'div' => false,
										'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
										'multiple' => true,
									) ); ?>
									<span class="help-block"><?php echo __( 'OPTIONAL: If there are improvements planned for this policy, you are able to map them here. The list of projects presented on this field comes from the Security Operations / Project Management.' ); ?></span>
								</div>
								<div class="col-md-1 quick-create">
									<?php echo $this->Ajax->quickAddAction(array('url' => array('controller' => 'projects', 'action' => 'add'),'text' => 'Add Project')); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Status' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input( 'status', array(
										'options' => $statuses,
										'label' => false,
										'div' => false,
										'class' => 'form-control'
									) ); ?>
									<span class="help-block"><?php echo __( 'Only those policies tagged as "Published" will be used across other parts of the system.' ); ?></span>
								</div>
							</div>

						</div>
						<div class="tab-pane fade in" id="tab_policy_content">
							<?php 
							echo $this->FieldData->inputs([
								$FieldDataCollection->security_policy_document_type_id,
							]);
							?>
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __('Version'); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('version', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'disabled' => $disabledReviewFields
									));
									?>
									<span class="help-block"><?php echo __('The version of the document will be displayed on the header of the policy document. IMPORTANT ! once saved you wont be able to change this date from this form, you will need to create a "Review" by clicking on the policy document and "Management" / "Review".'); ?></span>

									<?php if ($disabledReviewFields) : ?>
										<?php
										echo $this->Ux->getAlert(__('The document version can only be modified with reviews - click on <strong>Manage / Reviews</strong> to add a new review.'), array(
											'type' => 'info'
										));
										?>
									<?php endif; ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Document Content' ); ?>:</label>
								<div class="col-md-10">
									<?php echo $this->Form->input('use_attachments', array(
										'options' => getPoliciesDocumentContentTypes(),
										'label' => false,
										'div' => false,
										'id' => 'use-attachments',
										'class' => 'form-control'
									)); ?>
									<span class="help-block"><?php echo __('Choose the place where the actual content of the policy resides, your options are: <br><br> - Use Content: you can use the content editor below to write and mantain your policies.<br>- Use Attachments: you will attach PDF, Word Files, Etc to this policy once you have saved it. Remember that those attachments must be uploaded to the reviews of the policy (Manage / Reviews), not the policy itself.<br>- Use URL: if your policies are on Sharepoints, Wikis, Etc.'); ?></span>
									
									
								</div>
							</div>

							<div class="form-group form-group-first attachments-wrapper" data-content-type="<?php echo SECURITY_POLICY_USE_ATTACHMENTS; ?>">
								<label class="col-md-2 control-label"></label>
								<div class="col-md-10">
									<?php
									if ($disabledReviewFields) {
										echo $this->Ux->getAlert(__('If you want to use attachments for this policy, create a review under <strong>Manage / Reviews</strong>'), array(
											'type' => 'info',
											'class' => array('content-type-warning')
										));
									}
									else {
										echo $this->Ux->getAlert(__('You selected to keep your policies as attachments, once you save this policy eramba will create two reviews, attach the documents to the review with todays date.'), array(
											'type' => 'info'
										));
									}
									?>
								</div>
							</div>

							<div class="form-group form-group-first tinymce-wrapper" data-content-type="<?php echo SECURITY_POLICY_USE_CONTENT; ?>">
								<label class="col-md-2 control-label"></label>
								<div class="col-md-10">
									<?php
									if ($disabledReviewFields) {
										echo $this->Ux->getAlert(__('If you want to use our content editor for this policy, create a review under <strong>Manage / Reviews</strong>'), array(
											'type' => 'info',
											'class' => array('content-type-warning')
										));
									}
									?>
									<!-- <br /> -->
									<?php
									echo $this->element('securityPolicies' . DS . 'policy_editor', array(
										'fieldName' => 'SecurityPolicy.description',
										'disabled' => $disabledReviewFields
									));
									?>
									<span class="help-block"><?php echo __( 'Brief description of the Policy' ); ?></span>
								</div>
							</div>

							<div class="form-group form-group-first url-wrapper" data-content-type="<?php echo SECURITY_POLICY_USE_URL; ?>">
								<label class="col-md-2 control-label"><?php echo __( 'URL' ); ?>:</label>
								<div class="col-md-10">
									<?php
									if ($disabledReviewFields) {
										echo $this->Ux->getAlert(__('If you want to update the URL for this policy, create a review under <strong>Manage / Reviews</strong>'), array(
											'type' => 'info',
											'class' => array('content-type-warning')
										));
									}
									?>
									<?php
									echo $this->Form->input('url', array(
										'type' => 'textarea',
										'div' => false,
										'label' => false,
										'class' => 'form-control',
										'disabled' => $disabledReviewFields
									));
									?>
								</div>
							</div>

						</div>
						<div class="tab-pane fade in" id="tab_related_documents">
							<?php 
							echo $this->FieldData->inputs([
								$FieldDataCollection->RelatedDocuments,
							]);
							?>
						</div>
						<div class="tab-pane fade in" id="tab_access">
							<div class="form-group">
								<label class="col-md-2 control-label"><?php echo __( 'Policy Portal Permission' ); ?>:</label>
								<div class="col-md-10">
									<?php
									echo $this->Form->input('permission', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
										'empty' => __('Choose a permission'),
										'id' => 'permission'
									));
									?>
									<span class="help-block">
										<?php
										echo __('Choose if you wish yo show this policy on the policy portal or not. Remember that the policy portal is by default disabled (System / Settings / Authentication) and is accesible by using the same URL you use for eramba with /policy/ on the end. For example: http://eramba.com/policy/');
										?>
										<br /><br />
										<?php echo __('Public: Anyone can see this document on the policy portal'); ?>
										<br />
										<?php echo __('Private: No one can see this document on the policy portal'); ?>
										<br />
										<?php echo __('Authorized Uses Only: Only authorized users can see this document. This is controlled by selecting groups from your AD where only those users will be able to access this document.'); ?>
									</span>
								</div>
							</div>

							<div id="ldap-connector-select">
								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('LDAP Connector'); ?>:</label>
									<div class="col-md-10">
										<?php
										echo $this->Form->input('ldap_connector_id', array(
											'label' => false,
											'div' => false,
											'class' => 'form-control',
											'empty' => __('Choose an LDAP Connector'),
											'id' => 'ldap-connector-select'
										));
										?>
										<span class="help-block"><?php echo __('Choose an LDAP Connector to show groups.'); ?></span>
									</div>
								</div>
							</div>

							<div id="ldap-group-select">
								<div class="form-group">
									<label class="col-md-2 control-label"><?php echo __('LDAP Groups'); ?>:</label>
									<div class="col-md-10">
										<!-- <button class="btn btn-default" id="show-ldap-groups"><?php echo __('Show Groups'); ?></button> -->
										<div id="ldap-group-wrapper">

										</div>
									</div>
								</div>
							</div>
						</div>

						<?php echo $this->element('CustomFields.tabs_content'); ?>
					</div>
				</div>

				<div class="form-actions">
					<?php echo $this->Form->submit( $submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					) ); ?>
					&nbsp;
					<?php
					echo $this->Ajax->cancelBtn('SecurityPolicy');
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<?php
		echo $this->element('ajax-ui/sidebarWidget', array(
			'model' => 'SecurityPolicy',
			'id' => isset($edit) ? $this->data['SecurityPolicy']['id'] : null
		));
		?>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {
	var disabledReviewFields = "<?php echo $disabledReviewFields; ?>";
	var currentContentType = null;
	// if (disabledReviewFields) {
	// 	currentContentType = ...;
	// }

	$("#use-attachments").on("change", function(e) {
		var $selected = $(this).find("option:selected").val();

		// if ($selected != currentContentType) {
		// 	var $contentTypeWrappper = $("[data-content-type='" + $selected + "']");
		// }

		if ($selected == "<?php echo SECURITY_POLICY_USE_CONTENT; ?>") {
			$(".tinymce-wrapper").show();
		}
		else {
			$(".tinymce-wrapper").hide();
		}

		if ($selected == "<?php echo SECURITY_POLICY_USE_URL; ?>") {
			$(".url-wrapper").show();
		}
		else {
			$(".url-wrapper").hide();
		}

		if ($selected == "<?php echo SECURITY_POLICY_USE_ATTACHMENTS; ?>") {
			$(".attachments-wrapper").show();
		}
		else {
			$(".attachments-wrapper").hide();
		}
	}).trigger("change");

	$("#permission").on("change", function(e) {
		if ($(this).find("option:selected").val() == "<?php echo SECURITY_POLICY_LOGGED; ?>") {
			$("#ldap-connector-select").show();
			$("#ldap-connector-select").trigger("change");
		}
		else {
			$("#ldap-connector-select").hide();
			$("#ldap-group-select").hide();
			$("#ldap-group-wrapper").empty();
		}
	}).trigger("change");

	var selectedGroups = new Array();
	<?php if (!empty($this->request->data['SecurityPolicyLdapGroup'])) : ?>
		<?php foreach ($this->request->data['SecurityPolicyLdapGroup'] as $group) : ?>
			selectedGroups.push("<?php echo $group['name']; ?>");
		<?php endforeach; ?>
	<?php endif; ?>

	$("#ldap-connector-select").on("change", function(e) {
		if ($(this).find("option:selected").val()) {
			var $blockLoader = $(this).closest(".widget-form");
			Eramba.Ajax.blockEle($blockLoader);

			$.ajax({
				type: "POST",
				dataType: "HTML",
				url: "/securityPolicies/ldapGroups/" + $("#ldap-connector-select option:selected").val(),
				data: {groups: selectedGroups}
			}).done(function(data) {
				$("#ldap-group-wrapper").html(data);
				$("#ldap-group-select").show();

				Eramba.Ajax.unblockEle($blockLoader);
			});

		}
		else {
			$("#ldap-group-select").hide();
			$("#ldap-group-wrapper").empty();
		}
	}).trigger("change");

	/*$("#show-ldap-groups").on("click", function(e) {
		$.ajax({
			type: "GET",
			dataType: "HTML",
			url: "/securityPolicies/ldapGroups/" + $("#ldap-connector-select option:selected").val()
		}).done(function(data) {
			$("#ldap-group-wrapper").html(data);
		});

		e.preventDefault();
	});*/
});
</script>
