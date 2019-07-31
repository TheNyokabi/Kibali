<div class="widget box" id="form_wizard">
	<div class="widget-content">
		<?php
		if (isset($edit)) {
			echo $this->Form->create('AwarenessProgram', array(
				'url' => array('controller' => 'awarenessPrograms', 'action' => 'edit'),
				'class' => 'form-horizontal',
				'id' => 'awareness-form',
				'type' => 'file'
			));

			echo $this->Form->input( 'id', array( 'type' => 'hidden' ) );
			$submit_label = __( 'Edit' );
		}
		else {
			echo $this->Form->create('AwarenessProgram', array(
				'url' => array('controller' => 'awarenessPrograms', 'action' => 'add'),
				'class' => 'form-horizontal',
				'id' => 'awareness-form',
				'type' => 'file'
			));
			
			$submit_label = __( 'Add' );
		}

		echo $this->Form->input('uploads_sort_json', array(
			'type' => 'hidden',
			'id' => 'uploads-sort-json',
			'default' => json_encode(AwarenessProgram::processUploadSorting(array()))
		));
		?>
			<div class="form-wizard">
				<div class="form-body">

					<!--=== Steps ===-->
					<ul class="nav nav-pills nav-justified steps">
						<li>
							<a href="#tab1" data-toggle="tab" class="step">
								<span class="number">1</span>
								<span class="desc"><i class="icon-ok"></i> <?php echo __('Basic Information'); ?></span>
							</a>
						</li>
						<li>
							<a href="#tab1_1" data-toggle="tab" class="step">
								<span class="number">2</span>
								<span class="desc"><i class="icon-ok"></i> <?php echo __('LDAP'); ?></span>
							</a>
						</li>
						<li>
							<a href="#tab2" data-toggle="tab" class="step">
								<span class="number">3</span>
								<span class="desc"><i class="icon-ok"></i> <?php echo __('Uploads'); ?></span>
							</a>
						</li>
						<li>
							<a href="#tab3" data-toggle="tab" class="step">
								<span class="number">4</span>
								<span class="desc"><i class="icon-ok"></i> <?php echo __('Texts'); ?></span>
							</a>
						</li>
						<li>
							<a href="#tab4" data-toggle="tab" class="step">
								<span class="number">5</span>
								<span class="desc"><i class="icon-ok"></i> <?php echo __('Email'); ?></span>
							</a>
						</li>
					</ul>
					<!-- /Steps -->

					<!--=== Progressbar ===-->
					<div id="bar" class="progress progress-striped" role="progressbar">
						<div class="progress-bar progress-bar-success"></div>
					</div>
					<!-- /Progressbar -->

					<!--=== Tab Content ===-->
					<div class="tab-content">

						<div class="alert alert-danger hide-default">
							<button class="close" data-dismiss="alert"></button>
							<?php echo __('You missed some fields. They have been highlighted.'); ?>
						</div>
						<div class="alert alert-success hide-default">
							<button class="close" data-dismiss="alert"></button>
							Good job! :-)
						</div>

						<div class="tab-pane active" id="tab1">

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Title'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('title', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required',
									));
									?>
									<span class="help-block"><?php echo __('Enter a title for this Awareness Program'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Description'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('description', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control',
									));
									?>
									<span class="help-block"><?php echo __('Enter a description for this Awareness Program'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Recurrence'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('recurrence', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required number',
										'min' => 1,
										'disabled' => !empty($edit) ? true : false
									));
									?>
									<span class="help-block"><?php echo __('How often does this training need to be done. For example: 1 means every day, 2 means every second day, Etc.'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Reminders Amount'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('reminder_amount', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required number',
										'min' => 0,
										'disabled' => !empty($edit) ? true : false
									));
									?>
									<span class="help-block"><?php echo __('How many times it will send a reminder email to the users who ignore the email'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Reminders Apart'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('reminder_apart', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required number',
										'min' => 1,
										'disabled' => !empty($edit) ? true : false
									));
									?>
									<span class="help-block"><?php echo __('How many days in between email notifications to the user, so he does not get a daily email'); ?></span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Redirect'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('redirect', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required url',
									));
									?>
									<span class="help-block"><?php echo __('Where to redirect upon completion'); ?></span>
								</div>
							</div>
							
						</div>

						<div class="tab-pane" id="tab1_1">

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('LDAP Connector'); ?></label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('ldap_connector_id', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required',
										'empty' => __('Select an LDAP Connector'),
										'id' => 'ldap-connector-select',
										'disabled' => !empty($edit) ? true : false
									));
									?>
									<span class="help-block"><?php echo __('Choose an LDAP Connector for this program'); ?></span>
								</div>
							</div>

							<div class="form-group" id="ldap-group-select">
								<label class="control-label col-md-3"><?php echo __('LDAP Groups'); ?></label>
								<div class="col-md-4" id="ldap-group-wrapper">
									
								</div>
							</div>

							<div class="form-group" id="ldap-ignored-select" style="display:none;">
								<label class="control-label col-md-3"><?php echo __('Ignored Users'); ?></label>
								<div class="col-md-4" id="ldap-ignored-wrapper">
									
								</div>
							</div>

							<div class="form-group" id="ldap-check-connection" style="">
								<label class="control-label col-md-3">&nbsp;</label>
								<div class="col-md-4" id="">
									<?php
									echo $this->Html->link(__('Check LDAP Connectors'), array(
										'controller' => 'awarenessPrograms',
										'action' => 'ldapCheck',
										
									), array(
										'class' => 'btn btn-md',
										// 'data-ajax-action' => 'custom',
										'id' => 'ldap-check-modal-btn',
										'disabled' => true
									));
									?>
									<br /><br />
									<div class="alert alert-danger" id="ldap-check-error-msg" style="display:none;">
										<?php
										echo __('You need to do a successful LDAP Connectors check in order to continue.');
										?>
									</div>
								</div>
							</div>

							<?php
							echo $this->Form->input('_ldap_results', array(
								'type' => 'hidden',
								'value' => 0,
								'id' => 'ldap-check-results'
							));
							?>

						</div>

						<div class="tab-pane " id="tab2">
							<div class="widget box">
								<div class="widget-header">
									<h4><?php echo __('Configure Associations'); ?></h4>
								</div>
								<div class="widget-content">
									<div class="form-group">
										<label class="control-label col-md-3"><?php echo __('Security Policies'); ?>:</label>
										<div class="col-md-4">
											<?php
											echo $this->Form->input('AwarenessProgram.SecurityPolicy', array(
												'label' => false,
												'div' => false,
												'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
												'multiple' => true,
											));
											?>
											<span class="help-block"><?php echo __('Choose one or more Policies to associate.'); ?></span>
										</div>
									</div>
								</div>
							</div>

							<div class="widget box">
								<div class="widget-header">
									<h4><?php echo __('Configure Program Steps'); ?></h4>
								</div>
								<div class="widget-content">
									<div class="alert alert-info hide-default" style="display: block;">
										<div class="dd-handle dd3-handle dd3-handle-info">Drag</div>
										&nbsp;
										<?php echo __('You can drag and drop the handle to reorder steps.'); ?>
									</div>
									<div class="dd" id="nestable-awareness-program-uploads">
										<ol class="dd-list">
											<li class="dd-item dd-awareness-upload-item" data-type="text-file">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo __('Text File Upload'); ?>:</label>
													<div class="col-md-4">
														<?php
														echo $this->Form->input('text_file', array(
															'type' => 'file',
															'label' => false,
															'div' => false,
															'class' => 'file',
															'data-style' => 'fileinput',
															'required' => false
														));
														?>
														<span class="help-block">
															<?php 
															echo __('Upload a plain text file (.txt) or HTML file (.html) with inline CSS of your own within the file.');
															?>
														</span>
														<?php if (isset($edit) && !empty($data['AwarenessProgram']['text_file'])) : ?>
															<?php
															$url = Router::url(array('controller' => 'awareness', 'action' => 'downloadStepFile', $data['AwarenessProgram']['id'], 'text_file'));
															$link = $this->Html->link($data['AwarenessProgram']['text_file'], $url, array(
																'target' => '_blank'
															));
															?>
															<p><?php echo __('Uploaded text file: <strong>%s</strong>', $link); ?></p>
															<?php
															echo $this->Html->link(__('Delete file'), array(
																'controller' => 'awarenessPrograms',
																'action' => 'deleteTextFile',
																$data['AwarenessProgram']['id']
															), array(
																'class' => 'btn btn-danger btn-sm delete-file-confirm'
															));
															?>
														<?php endif; ?>
													</div>
													<div class="col-md-3">
														<p>
															<?php
															echo $this->AwarenessPrograms->getExampleLink('txt', __('Download Example (txt)'));
															?>
														</p>
														<p>
															<?php
															echo $this->AwarenessPrograms->getExampleLink('html', __('Download Example (html)'));
															?>
														</p>
													</div>
													<div class="clearfix"></div>
													<br>
													<label class="control-label col-md-3"><?php echo __('Iframe Size'); ?>:</label>
													<div class="col-md-4">
														<?php
														echo $this->Form->input('text_file_frame_size', array(
															'label' => false,
															'div' => false,
															'class' => 'select2 col-md-12',
														));
														?>
														<span class="help-block">
															<?php 
															echo __('OPTIONAL: If you upload text/ html you are allowed to adjust the width and hight of the iframe that contains that text /html.');
															?>
														</span>
													</div>
												</div>
												<div class="dd-handle dd3-handle">Drag</div>
											</li>

											<li class="dd-item dd-awareness-upload-item" data-type="video-file">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo __('Video Upload'); ?>:</label>
													<div class="col-md-4">
														<?php
														echo $this->Form->input('video', array(
															'type' => 'file',
															'label' => false,
															'div' => false,
															'class' => 'file',
															'data-style' => 'fileinput',
															'required' => false
														));
														?>
														<span class="help-block"><?php echo __('or upload a video here. (mp4)'); ?></span>
														<?php if (isset($edit) && !empty($data['AwarenessProgram']['video'])) : ?>
															<?php
															$url = Router::url(array('controller' => 'awareness', 'action' => 'downloadStepFile', $data['AwarenessProgram']['id'], 'video'));
															$link = $this->Html->link($data['AwarenessProgram']['video'], $url, array(
																'target' => '_blank'
															));
															?>
															<p><?php echo __('Uploaded video file: <strong>%s</strong>', $link); ?></p>
															<?php
															echo $this->Html->link(__('Delete video'), array(
																'controller' => 'awarenessPrograms',
																'action' => 'deleteVideo',
																$data['AwarenessProgram']['id']
															), array(
																'class' => 'btn btn-danger btn-sm delete-file-confirm'
															));
															?>
														<?php endif; ?>
													</div>
												</div>
												<div class="dd-handle dd3-handle">Drag</div>
											</li>

											<li class="dd-item dd-awareness-upload-item" data-type="questionnaire-file">
												<div class="form-group">
													<label class="control-label col-md-3"><?php echo __('Multiple Choice Upload'); ?>:</label>
													<div class="col-md-4">
														<?php
														echo $this->Form->input('questionnaire', array(
															'type' => 'file',
															'label' => false,
															'div' => false,
															'class' => 'file',
															'data-style' => 'fileinput',
															'required' => false
														));
														?>
														<span class="help-block"><?php echo __('or upload a multiple choice questionnaire file here. (csv)'); ?></span>
														<?php if (isset($edit) && !empty($data['AwarenessProgram']['questionnaire'])) : ?>
															<?php
															$url = Router::url(array('controller' => 'awareness', 'action' => 'downloadStepFile', $data['AwarenessProgram']['id'], 'questionnaire'));
															$link = $this->Html->link($data['AwarenessProgram']['questionnaire'], $url, array(
																'target' => '_blank'
															));
															?>
															<p><?php echo __('Uploaded questionnaire file: <strong>%s</strong>', $link); ?></p>
															<?php
															echo $this->Html->link(__('Delete questionnaire'), array(
																'controller' => 'awarenessPrograms',
																'action' => 'deleteQuestionnaire',
																$data['AwarenessProgram']['id']
															), array(
																'class' => 'btn btn-danger btn-sm delete-file-confirm'
															));
															?>
														<?php endif; ?>
													</div>
													<div class="col-md-3">
														<p>
															<?php
															echo $this->AwarenessPrograms->getExampleLink('csv', __('Download Example (csv)'));
															?>
														</p>
													</div>
												</div>
												<div class="dd-handle dd3-handle">Drag</div>
											</li>
										</ol>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab3">
							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Welcome Header Text'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('welcome_text', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Welcome Sub Header Text'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('welcome_sub_text', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Thank You Header Text'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('thank_you_text', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Thank You Sub Header Text'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('thank_you_sub_text', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control',
									));
									?>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="tab4">
							<div class="form-group">
								<h3 class="block padding-bottom-10px control-label col-md-3"><?php echo __('Invitation Email Settings'); ?></h3>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Email subject'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('email_subject', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required',
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Email body'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('email_body', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control required',
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<h3 class="block padding-bottom-10px control-label col-md-3"><?php echo __('Reminder Email Settings'); ?></h3>
							</div>

							<div class="form-group">
								<label class="control-label col-md-3"><?php echo __('Reminder Email'); ?>:</label>
								<div class="col-md-4">
									<label class="checkbox">
										<?php echo $this->Form->input('email_reminder_custom', array(
											'type' => 'checkbox',
											'label' => false,
											'div' => false,
											'class' => 'uniform',
											'id' => 'reminder-customize-toggle'
										)); ?>
										<?php echo __('Check to customize Reminder email'); ?>
									</label>
								</div>
							</div>

							<div class="form-group reminder-customize-group">
								<label class="control-label col-md-3"><?php echo __('Email subject'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('email_reminder_subject', array(
										'label' => false,
										'div' => false,
										'class' => 'form-control required',
									));
									?>
								</div>
							</div>

							<div class="form-group reminder-customize-group">
								<label class="control-label col-md-3"><?php echo __('Email body'); ?>:</label>
								<div class="col-md-4">
									<?php
									echo $this->Form->input('email_reminder_body', array(
										'type' => 'textarea',
										'label' => false,
										'div' => false,
										'class' => 'form-control required',
									));
									?>
								</div>
							</div>

							<div class="form-group">
								<div class="col-md-4 col-md-offset-3">
									<span class="help-block">
										<?php
										echo $this->element(NOTIFICATION_SYSTEM_ELEMENT_PATH . 'macros_list', array(
											'style' => 'vertical'
										));
										?>
									</span>
								</div>
							</div>
							
						</div>
					</div>
					<!-- /Tab Content -->
				</div>

				<!--=== Form Actions ===-->
				<div class="form-actions fluid">
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-offset-3 col-md-9">
								<a href="javascript:void(0);" class="btn button-previous">
									<i class="icon-angle-left"></i> <?php echo __('Back'); ?>
								</a>
								<a href="javascript:void(0);" class="btn btn-primary button-next">
									<?php echo __('Continue'); ?> <i class="icon-angle-right"></i>
								</a>
								<a href="javascript:void(0);" class="btn btn-success button-submit">
									<?php echo $submit_label; ?> <i class="icon-angle-right"></i>
								</a>
								&nbsp;
								<?php
								echo $this->Html->link(__('Cancel'), [
									'plugin' => null,
									'controller' => 'awarenessPrograms',
									'action' => 'index'
								], [
									'class' => 'btn btn-inverse'
								]);
								?>
							</div>
						</div>
					</div>
				</div>
				<!-- /Form Actions -->
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<script type="text/javascript">
/*
 * form_wizard.js
 *
 * Demo JavaScript used on Form Wizard-page.
 */

"use strict";

$(document).ready(function(){
	$("#ldap-check-modal-btn").on("click", function(e) {
		e.preventDefault();

		var groups = JSON.stringify($("#ldap-groups-field").val());
		var connector = $("#ldap-connector-select").val();

		var href = $(this).attr("href") + "?ldap_connector_id=" + connector + "&ldap_groups=" + groups;
		Eramba.Ajax.UI.requestHandler(href, "custom");
	});

	$("#content").on("LdapCheck", function(e, value) {
		$("#ldap-check-results").val(1);
		$("#ldap-check-error-msg").hide();
	});

	function reloadNestable() {
		var minHeight = 100;
		$(".dd-awareness-upload-item").each(function(i, e) {
			minHeight = Math.max(minHeight, $(e).height());
		});

		$("#nestable-awareness-program-uploads > .dd-list > .dd-item > .form-group,#nestable-awareness-program-uploads > .dd-list > .dd-item").css({"min-height": minHeight});
	}

	$(window).on("load", function(e) {
		reloadNestable();
	});

	var $jsonSortField = $("#uploads-sort-json");
	$("#nestable-awareness-program-uploads").nestable({
		maxDepth: 1,
		group: 0
	}).on("change", function(e) {
		var json = JSON.stringify($(this).nestable('serialize'));
		$jsonSortField.val(json);
	});

	if ($jsonSortField.val()) {
		var savedSort = $.parseJSON($jsonSortField.val());
		
		if (savedSort.length) {
			$.each(savedSort, function(i, v) {
				if ($(".dd-awareness-upload-item").eq(i).data("type") != v.type) {
					$(".dd-awareness-upload-item[data-type="+v.type+"]").insertBefore($(".dd-awareness-upload-item").eq(i));
				}
			});
		}
	}

	$(".delete-file-confirm").on("click", function(e) {
		e.preventDefault();
		var href = $(this).attr("href");

		bootbox.confirm("<?php echo __('Are you sure you want to delete this file?'); ?>", function(result) {
			if (result) {
				window.location = href;
			}
		}); 
	});

	//===== Form Wizard =====//

	// Config
	var form    = $('#awareness-form');
	var wizard  = $('#form_wizard');
	var error   = $('.alert-danger', form);
	var success = $('.alert-success', form);

	jQuery.extend(jQuery.validator.messages, {
		required: "<?php echo __('This field is required.'); ?>",
		file: "<?php echo __('File is required.'); ?>",
		url: "<?php echo __('Please enter a valid URL.'); ?>"
	});

	form.validate({
		doNotHideMessage: true, // To display error/ success message on tab-switch
		focusInvalid: false, // Do not focus the last invalid input
		invalidHandler: function (event, validator) {
			// Display error message on form submit

			success.hide();
			error.show();
		},
		submitHandler: function (form) {
			success.show();
			error.hide();

			$('.button-next').trigger("click");

			// if (form.valid() == false) {
			// 	return false;
			// }

			form.submit();

			// Maybe you want to add some Ajax here to submit your form
			// Otherwise just call form.submit() or remove this submitHandler to submit the form without ajax
		}
	});

	// Functions
	var displayConfirm = function() {
		$('#tab4 .form-control-static', form).each(function(){
			var input = $('[name="'+$(this).attr("data-display")+'"]', form);

			if (input.is(":text") || input.is("textarea")) {
				$(this).html(input.val());
			} else if (input.is("select")) {
				$(this).html(input.find('option:selected').text());
			} else if (input.is(":radio") && input.is(":checked")) {
				$(this).html(input.attr("data-title"));
			}
		});
	}

	var handleTitle = function(tab, navigation, index) {
		var total = navigation.find('li').length;
		var current = index + 1;

		// Set widget title
		// $('.step-title', wizard).text('Step ' + (index + 1) + ' of ' + total);

		// Set done steps
		$('li', wizard).removeClass("done");

		var li_list = navigation.find('li');
		for (var i = 0; i < index; i++) {
			$(li_list[i]).addClass("done");
		}

		if (current == 1) {
			// wizard.find('.button-previous').hide();
		} else {
			// wizard.find('.button-previous').show();
		}

		if (current >= total) {
			wizard.find('.button-next').hide();
			wizard.find('.button-submit').show();
			displayConfirm();
		} else {
			wizard.find('.button-next').show();
			wizard.find('.button-submit').hide();
		}
	}

	// Form wizard example
	wizard.bootstrapWizard({
		'nextSelector': '.button-next',
		'previousSelector': '.button-previous',
		onTabClick: function (tab, navigation, index, clickedIndex) {
			success.hide();
			error.hide();

			if (form.valid() == false) {
				return false;
			}

			handleTitle(tab, navigation, clickedIndex);
		},
		onNext: function (tab, navigation, index) {
			success.hide();
			error.hide();

			if (form.valid() == false) {
				return false;
			}

			if (index == 2) {
				if ($("#ldap-check-results").val() == 0) {
					$("#ldap-check-error-msg").show();
					return false;
				}
				else {
					$("#ldap-check-error-msg").hide();
				}
			}

			if (index == 3) {
				// reloadNestable();
				
				var isValid = false;

				var jsValid = !!$("#AwarenessProgramQuestionnaire").val() || !!$("#AwarenessProgramVideo").val() || !!$("#AwarenessProgramTextFile").val();
				<?php if (isset($edit)) : ?>
					var dbValid = false;
					<?php if (!empty($data['AwarenessProgram']['video']) || !empty($data['AwarenessProgram']['text_file']) || !empty($data['AwarenessProgram']['questionnaire'])) : ?>
							dbValid = true;
					<?php endif; ?>

					isValid = dbValid || jsValid;
				<?php else : ?>
					isValid = jsValid;
				<?php endif; ?>

				var $groups = $("#tab2 input[type=file]").closest(".form-group");
				if (!isValid) {
					$groups
						
						.removeClass("has-success")
						.addClass("has-error");

					return false;
				}
				else {
					$groups.removeClass("has-error");
				}
			}

			handleTitle(tab, navigation, index);
		},
		onPrevious: function (tab, navigation, index) {
			success.hide();
			error.hide();

			handleTitle(tab, navigation, index);
		},
		onTabShow: function (tab, navigation, index) {
			console.log("showTab: " + index);

			// To set progressbar width
			var total = navigation.find('li').length;
			var current = index + 1;
			var $percent = (current / total) * 100;
			wizard.find('.progress-bar').css({
				width: $percent + '%'
			});

			if (index == 2) {
				reloadNestable();
			}

			$(window).resize();
		}
	});

	// wizard.find('.button-previous').hide();
	$('#form_wizard .button-submit').click(function () {
		//alert('You just finished the wizard. :-)');
		// $('.button-next').trigger("click");

		if (form.valid() == false) {
			return false;
		}

		form.trigger("submit");

	}).hide();

	<?php if (isset($this->request->query['showTab'])) : ?>
		wizard.bootstrapWizard('show', <?php echo $this->request->query['showTab']; ?>);
	<?php endif; ?>

	var selectedGroups = new Array();
	<?php if (!empty($this->request->data['AwarenessProgramLdapGroup'])) : ?>
		<?php foreach ($this->request->data['AwarenessProgramLdapGroup'] as $group) : ?>
			selectedGroups.push("<?php echo $group['name']; ?>");
		<?php endforeach; ?>
	<?php elseif (!empty($this->request->data['AwarenessProgram']['ldap_groups'])) : ?>
		<?php foreach ($this->request->data['AwarenessProgram']['ldap_groups'] as $group) : ?>
			selectedGroups.push("<?php echo $group; ?>");
		<?php endforeach; ?>
	<?php endif; ?>

	$("#ldap-connector-select").on("change", function(e) {
		if ($(this).find("option:selected").val()) {
			blockWizard();
			$.ajax({
				type: "POST",
				dataType: "HTML",
				url: "/awarenessPrograms/ldapGroups/" + $("#ldap-connector-select option:selected").val(),
				data: {
					groups: selectedGroups,
					edit: <?php echo !empty($edit) ? '1' : '0'; ?>
				}
			}).done(function(data) {
				$("#ldap-group-wrapper").html(data);
				$("#ldap-group-select").show();

				bindIgnoredUsers();
				unblockWizard();
			});

		}
		else {
			$("#ldap-group-select").hide();
			$("#ldap-group-wrapper").empty();
		}
	}).trigger("change");

	var selectedUsers = new Array();
	<?php if (!empty($this->request->data['AwarenessProgramIgnoredUser'])) : ?>
		<?php foreach ($this->request->data['AwarenessProgramIgnoredUser'] as $user) : ?>
			selectedUsers.push("<?php echo $user['uid']; ?>");
		<?php endforeach; ?>
	<?php elseif (!empty($this->request->data['AwarenessProgram']['ignored_users_uid'])) : ?>
		<?php foreach ($this->request->data['AwarenessProgram']['ignored_users_uid'] as $uid) : ?>
			selectedUsers.push("<?php echo $uid; ?>");
		<?php endforeach; ?>
	<?php endif; ?>

	function bindIgnoredUsers() {
		$("#ldap-groups-field").off("change").on("change", function(e){
			var groups = $(this).val();

			if (groups != null && groups.length > 0) {
				blockWizard();

				$.ajax({
					type: "POST",
					dataType: "HTML",
					url: "/awarenessPrograms/ldapIgnoredUsers/" + $("#ldap-connector-select option:selected").val(),
					data: {
						groups: groups,
						selectedUsers: $.unique($.merge(selectedUsers, $("#ldap-ignored-users-field").select2('val'))),
						edit: <?php echo !empty($edit) ? '1' : '0'; ?>
					}
				}).done(function(data) {
					$("#ldap-ignored-wrapper").html(data);
					$("#ldap-ignored-select").show();

					unblockWizard();
					$("#ldap-check-modal-btn").removeAttr("disabled");
				});
			}
			else {
				$("#ldap-ignored-select").hide();
				$("#ldap-ignored-wrapper").empty();
				$("#ldap-check-modal-btn").attr("disabled", true);
			}
		}).trigger("change");
	}

	function blockWizard() {
		Eramba.Ajax.blockEle($("#form_wizard > .widget-content"));
	}

	function unblockWizard() {
		Eramba.Ajax.unblockEle($("#form_wizard > .widget-content"));
	}

	$("#reminder-customize-toggle").on("change", function(e) {
		if ($(this).is(":checked")) {
			$(".reminder-customize-group").show();
		}
		else {
			$(".reminder-customize-group").hide();
		}
	}).trigger("change");
});
</script>