<div class="" id="ldap-check-form">
	<?php
	if (isset($errorMessage)) :
		echo $this->Ux->getAlert($errorMessage);

		echo $this->Html->link(__('Close'), '#', array(
			'data-dismiss' => 'modal',
			'class' => 'btn btn-inverse'
		));
	else :

		echo $this->Form->create('AwarenessProgramCheck', array(
			'url' => array('controller' => 'awarenessPrograms', 'action' => 'ldapCheck'),
			'class' => 'form-horizontal'
		));


		echo $this->Form->input('ldap_connector_id', array(
			'type' => 'hidden',
			'value' => $ldapConnectorId
		));
		echo $this->Form->input('ldap_groups', array(
			'type' => 'hidden',
			'value' => json_encode($ldapGroups)
		));
		?>

		<div class="form-group" id="ldap-user">
			<label class="control-label col-md-3"><?php echo __('LDAP User'); ?></label>
			<div class="col-md-4">
				<?php
				echo $this->Form->input('ldap_user', array(
					'options' => $ldapUsers,
					'label' => false,
					// 'div' => false,
					'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
					'id' => 'ldap-user-field',
					// 'multiple' => true,
					// 'selected' => $selected,
					// 'disabled' => !empty($edit) ? true : false
				));
				?>
				<span class="help-block"><?php echo __('Choose user that will be used for validation'); ?></span>
			</div>
		</div>

		<div class="form-group" id="ldap-check" style="">
			<label class="control-label col-md-3">&nbsp;</label>
			<div class="col-md-4" id="">
				<?php
				echo $this->Form->submit(__('Check'), [
					'class' => 'btn btn-primary',
					'div' => false,
					'id' => 'ldap-check-btn'
				]);
				?>
				&nbsp;
				<?php
				echo $this->Html->link(__('Close'), '#', array(
					'data-dismiss' => 'modal',
					'class' => 'btn btn-inverse'
				));
			?>
			</div>
		</div>

		<?php
		echo $this->Form->end();
		?>
	<?php
	endif;
	?>
<?php
if (isset($success)) {
	if ($success) {
		echo $this->Ux->getAlert(__('All looks good, click on Close and continue the configuration of your awareness program.'), [
			'type' => 'success',
			'class' => ['ldap-check-message-box', 'fade', 'in']
		]);
	}
	else {
		echo $this->Ux->getAlert(__('Using your LDAP Auth connector we pulled the list of groups for this user and could not find the groups you selected for this awareness program. Check your LDAP connectors and try again.'), [
			'type' => 'danger'
		]);
	}
}
?>

<script type="text/javascript">
jQuery(function($) {
	$("#ldap-user-field").select2();
	Eramba.Ajax.UI.modal.setSize('modal-lg');

	$("[data-ajax-action=cancel]").attr("data-ajax-action", "close");

	<?php if (isset($success)) : ?>
		$("#content").trigger("LdapCheck", ["<?php echo $success; ?>"]);
	<?php endif; ?>
	
	$("#ldap-check-btn").on("click", function(e) {
		e.preventDefault();
		App.blockUI($(".modal-body"));

		$.ajax({
			type: "POST",
			url: $("#ldap-check-form form").attr("action"),
			data: $("#ldap-check-form form").serialize()
		}).done(function(data) {
			$("#ldap-check-form").closest('.modal-body').html(data);
			App.unblockUI($(".modal-body"));
		});
	});
});
</script>

</div>