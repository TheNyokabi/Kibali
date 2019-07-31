<?php if(!empty($logged['email'])): ?>
	<div class="form-group form-group-first">
		<div class="col-md-2">&nbsp;</div>
		<div class="col-md-10">
			<button class="btn" id="test-mail" data-loading-text="Testing..."><?php echo __('Test Connection'); ?></button>
			<span id="test-result">
				<i></i>
			</span>
			<span class="help-block"><?php echo __('You may test your SMTP settings by clicking on this button. You will be asked an email to which eramba will try to send an email using the settings defined in this form.') ?></span>
		</div>
	</div>
<?php else: ?>
	<?php echo $this->element( 'not_found', array(
					'message' => __( 'Please fill in your email address to be able to test mail connection.' )
				) ); ?>
<?php endif; ?>

<script type="text/javascript">
jQuery(function($) {
	var $smtpEle = $("#SettingSMTPHOST, #SettingSMTPUSER, #SettingSMTPPWD, #SettingSMTPTIMEOUT, #SettingSMTPPORT, #SettingUSESSL");
	$("#SettingSMTPUSE").on("change", function(e) {
		if ($(this).val() == "1") {
			$smtpEle.prop("readonly", false);
		}
		else {
			$smtpEle.prop("readonly", true);
		}
	}).trigger("change");

	$("#test-result").hide();
	$("#test-mail").on("click", function(e) {

		var btn = $(this);
		btn.button('loading');

		bootbox.prompt({
			title: "<?php echo __('Enter email'); ?>",
			value: "<?php echo $logged['email'] ?>",
			callback: function(result) {
				if (result === null) {
					btn.button('reset');
					bootbox.hideAll();
				}
				else {
					sendEmail(result);
				}
			}
		});
		e.preventDefault();
	});

	function sendEmail(email) {

		$.ajax({
    		dataType: "JSON",
			type: "POST",
			data: $("#SettingEditForm").serialize(),
			url: "/settings/testMailConnection/" + email + "/time:"+ new Date().getTime()
    	})
    	.done(function(data) {
    		if(data.success == false){
    			$("#test-result").children().removeClass().addClass("icon-remove text-danger");
    			msg = "<?php echo addslashes($this->element('not_found', array('message' => __('There is a problem with your email connection.'))));?>";
    		}
			else{
				$("#test-result").children().removeClass().addClass("icon-ok text-success");
				msg = "<?php echo addslashes($this->element('not_found', array('message' => __('Worked, we sent you an email.'))));?>";
			}
    		bootbox.dialog({
				message: msg,
				title: "<?php echo __('Email Testing Results'); ?>",
				buttons: {
					main: {
						label: "<?php echo __('Ok'); ?>",
						className: "btn-primary",
						callback: function() {
							$("#test-mail").button('reset');
						}
					}
				}
			});
			$("#test-result").show();
		});
	}
});
</script>