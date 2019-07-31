<?php
App::uses('LdapConnector', 'Model');
?>
<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
				if (isset($edit)) {
					echo $this->Form->create('LdapConnector', array(
						'url' => array('controller' => 'ldapConnectors', 'action' => 'edit'),
						'class' => 'form-horizontal row-border',
						'id' => 'ldap-connector-form',
						'novalidate' => true
					));

					echo $this->Form->input('id', array('type' => 'hidden'));
					$submit_label = __('Edit');
				}
				else {
					echo $this->Form->create('LdapConnector', array(
						'url' => array('controller' => 'ldapConnectors', 'action' => 'add'),
						'class' => 'form-horizontal row-border',
						'id' => 'ldap-connector-form',
						'novalidate' => true
					));

					$submit_label = __('Add');
				}
				?>

				<div class="form-group form-group-first">
					<label class="col-md-2 control-label"><?php echo __('Name'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('name', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('The name of the connector, for example "Corporate LDAP"') ?></span>
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
						<span class="help-block"><?php echo __('Brief description for this connector') ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Status'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('status', array(
							'options' => LdapConnector::statuses(),
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('If the connector is disabled or enabled (is ready to be used across the system)') ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('LDAP Server Hostname'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('host', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('The ldap server you want to connect. If you want to use TSL then don\'t forget to include ldaps:// in front of the server name. For example ldaps://ldap.company.com. Additionally you may need to edit your ldap.conf file and include a setting for TLS_REQCERT (with value "never").') ?></span>
					</div>

				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Mail Domain'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('domain', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('The domain used on your emails, for example mycompany.com') ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Port'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('port', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'default' => 389
						));
						?>
						<span class="help-block"><?php echo __('By default we connect to the port 389. If you are using TSL on your directory you would typically use 636.') ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('LDAP Username'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('ldap_bind_dn', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('This is the username that will be used to connect to the LDAP server using the full DN. <br><br>For example: "CN=Joe Ramone,OU=People,DC=corp,DC=eramba,DC=org"') ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('LDAP Password'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('ldap_bind_pw', array(
							'type' => 'password',
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('This is the password for the account defined on the field above') ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('LDAP Server Base DN'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('ldap_base_dn', array(
							'label' => false,
							'div' => false,
							'class' => 'form-control'
						));
						?>
						<span class="help-block"><?php echo __('This is the base directory where queries will be executed. <br><br>If you are not sure, use the DN of the user you used to connect to AD and keep the domain part, for example  "DC=corp,DC=eramba,DC=org"') ?></span>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><?php echo __('Type'); ?>:</label>
					<div class="col-md-10">
						<?php
						echo $this->Form->input('type', array(
							'options' => LdapConnector::types(),
							'label' => false,
							'div' => false,
							'class' => 'form-control',
							'id' => 'ldap-connector-type'
						));
						?>
						<span class="help-block"><?php echo __('If you are looking to authenticate users in eramba, then select "Authenticator". <br>If you have plans to use the Policy Portal or Awareness module you will need two connectors, one "Authenticator" and another "Group"')?></span>
					</div>
				</div>

				<div id="authenticator-fields">
					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Filter to find account names'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_auth_filter', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control',
								'default' => '(| (sn=%USERNAME%) )'
							));
							?>

							<?php
							echo $this->Form->input('_ldap_auth_filter_username_value', array(
								'type' => 'hidden',
								'id' => 'ldap-auth-filter-username-value'
							));
							?>
							<span class="help-block"><?php echo __('This filter is the one eramba will use to find the exact the login name in your directory. If your login account in AD is john.smith, at the time you login in eramba, this filter will be used to find that user account in your AD. eramba will replace the pattern %USERNAME% with "john.smith" and run the filter. <br><br>For example, a typical filter for AD would be (&(objectcategory=user)(sAMAccountName=%USERNAME%))')?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('LDAP Account Attribute'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_auth_attribute', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This is the attribute eramba will request after it executed the filter and will be used to get the exact login account.<br><br>For example, a typical attribute in AD would be "sAMAccountName"') ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Account Full Name Attribute'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_name_attribute', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This is an attribute in the directory that has the name of the account (John Smith).<br><br>For example, a typical attribute in AD would be "displayName"') ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Account Email Attribute'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_email_attribute', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This attribute is used on the filter above and expected to return the email of the account.<br><br>For example, a typical attribute in AD would be "mail"') ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('LDAP Memberof Attribute'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_memberof_attribute', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This attribute is used on the filter above and is expected to fetch the groups to which a specific account belongs to. <br><br>For example, a typical attribute in AD would be "memberOf"') ?></span>
						</div>
					</div>
				</div>

				<div id="group-fields">
					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Filter to get the list of groups'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_grouplist_filter', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This filter will be used to get the list of groups in your AD. <br><br>For example, a typical attribute in AD would be "(objectCategory=group)"') ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Group Name Attribute'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_grouplist_name', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This attribute is used on the filter above and is expected to return the name of the group. <br><br>For example, a typical attribute in AD would be "distinguishedName" or "cn"') ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Filter to get members of a group'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_groupmemberlist_filter', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This filter is used to pull the members of a group,the filter must the macro %GROUP% that indicates the group to be searched for. <br><br>For example, a typical attribute in AD would be "(&(objectCategory=user)(memberOf=CN=%GROUP%))"') ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Group member Account Name Attribute'); ?>:</label>
						<div class="col-md-10">
							<?php
							echo $this->Form->input('ldap_group_account_attribute', array(
								'label' => false,
								'div' => false,
								'class' => 'form-control'
							));
							?>
							<span class="help-block"><?php echo __('This attribute is used on the filter above and it must return the account name. <br><br>For example, a typical attribute in AD would be "sAMAccountName"') ?></span>
							<?php
							echo $this->Ux->getAlert(__('If this Group connector is planned to be used in conjunction with already existing Auth connector then this attribute field is mandatory be the same and also retrieve the same values as the field "LDAP Account Attribute" you have configured in Auth connector.'), [
								'type' => 'danger'
							]);
							?>
						</div>
					</div>
					
					<?php
					echo $this->element('ldapConnectors/email_attribute');
					?>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Email address for an Account'); ?>:</label>
						<div class="col-md-10">
							<label class="radio">
							<?php
							echo $this->Form->input('ldap_group_fetch_email_type', array(
								'type' => 'radio',
								'options' => getLdapConnectorEmailFetchTypes(),
								'label' => false,
								'div' => false,
								'class' => 'uniform email-type-radio',
								'separator' => '</label><label class="radio">',
								'legend' => false,
								'default' => LDAP_CONNECTOR_EMAIL_FETCH_EMAIL_ATTRIBUTE
							));
							?>
							</label>
							<span class="help-block"><?php echo __('By default AD shuld have the email attribute, but if for some reason that does not work for you (unlikely) you can define the email as the account name plus a domain that you can indicate on the field below.') ?></span>
						</div>
					</div>

					<?php
					echo $this->element('ldapConnectors/account_domain');
					?>

					<div id="authenticator-test-buttons">
						<div class="form-group form-group-first">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-10">
								<button class="btn" id="test-ldap" type="button" data-loading-text="<?php echo __('Testing...'); ?>"><?php echo __('Test Connection'); ?></button>
							</div>
						</div>
					</div>

					<div id="group-test-buttons">
						<div class="form-group">
							<label class="col-md-2 control-label">&nbsp;</label>
							<div class="col-md-10">
								<button class="btn" id="group-connector-users" type="button" data-loading-text="<?php echo __('Testing...'); ?>"><?php echo __('Test Getting Members of a Group'); ?></button>
								&nbsp;
								<button class="btn" id="group-connector-groups" type="button" data-loading-text="<?php echo __('Testing...'); ?>"><?php echo __('Test Getting List Of Groups'); ?></button>
							</div>
						</div>
					</div>
				</div>



				<div class="form-actions">
					<?php
					echo $this->Form->submit($submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false,
						'tabindex' => 0
					));
					?>
					&nbsp;
					<?php
					echo $this->Html->link(__('Cancel'), [
						'plugin' => null,
						'controller' => 'ldapConnectors',
						'action' => 'index'
					], [
						'class' => 'btn btn-inverse'
					]);
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {
	$("#group-connector-users").on("click", function(e) {

		var btn = $(this);
		btn.button('loading');

		bootbox.prompt({
			title: "<?php echo __('Enter group name'); ?>",
			value: "",
			callback: function(result) {
				if (result === null) {
					btn.button('reset');
					bootbox.hideAll();
				}
				else {
					listUsersByGroup(result);
				}
			}
		});
		e.preventDefault();
	});
	$("#group-connector-groups").on("click", function(e) {

		var btn = $(this);
		btn.button('loading');

		$.ajax({
    		dataType: "HTML",
			type: "GET",
			data: $("#ldap-connector-form").serialize(),
			url: "/ldapConnectors/testLdap/listGroups/time:"+ new Date().getTime()
    	})
    	.done(function(data) {
    		$("#group-connector-groups").button('reset');
    		bootbox.dialog({
				message: data,
				title: "<?php echo __('LDAP Results'); ?>",
				buttons: {
					main: {
						label: "<?php echo __('Ok'); ?>",
						className: "btn-primary",
						callback: function() {
						}
					}
				}
			});
		});

		e.preventDefault();
	});

	function listUsersByGroup(groupName) {

		$.ajax({
    		dataType: "HTML",
			type: "GET",
			data: $("#ldap-connector-form").serialize(),
			url: "/ldapConnectors/testLdap/listUsers/groupName:" + groupName + "/time:"+ new Date().getTime()
    	})
    	.done(function(data) {
    		$("#group-connector-users").button('reset');

    		bootbox.dialog({
				message: data,
				title: "<?php echo __('LDAP Results'); ?>",
				buttons: {
					main: {
						label: "<?php echo __('Ok'); ?>",
						className: "btn-primary",
						callback: function() {
						}
					}
				}
			});
		});
	}

	$("#ldap-connector-type").on("change", function(e) {
		$authFields = $("#authenticator-fields input");
		$groupFields = $("#group-fields input");

		if ($(this).find("option:selected").val() == "authenticator") {
			$authFields.prop("readonly", false);
			$groupFields.prop("readonly", true);

			$("#group-test-buttons").hide();
			$("#authenticator-test-buttons").show();
		}
		else {
			$authFields.prop("readonly", true);
			$groupFields.prop("readonly", false);

			$("#group-test-buttons").show();
			$("#authenticator-test-buttons").hide();
		}

		$.uniform.update($authFields);
		$.uniform.update($groupFields);

		$(".email-type-radio").trigger("change");
	}).trigger("change");

	$(".email-type-radio").on("change", function(e) {
		if ($("#ldap-connector-type").find("option:selected").val() != "group") {
			return true;
		}

		$attrFields = $(".email-attribute-field input");
		$domainFields = $(".account-domain-field input");

		var val = $(".email-type-radio:checked").val();

		if (!val) {
			$attrFields.prop("readonly", true);
			$domainFields.prop("readonly", true);

			return true;
		}

		if (val == "<?php echo LDAP_CONNECTOR_EMAIL_FETCH_EMAIL_ATTRIBUTE; ?>") {
			$attrFields.prop("readonly", false);
			$domainFields.prop("readonly", true);
		}

		if (val == "<?php echo LDAP_CONNECTOR_EMAIL_FETCH_ACCOUNT_DOMAIN; ?>") {
			$attrFields.prop("readonly", true);
			$domainFields.prop("readonly", false);
		}
	}).trigger("change");

	$("#test-ldap").on("click", function(e) {
		var btn = $(this);
		btn.button('loading');


		if ($("#ldap-connector-type").find("option:selected").val() == "authenticator") {
			var filter = $("#LdapConnectorLdapAuthFilter").val();
			if (filter.indexOf("%USERNAME%") > -1 || filter.indexOf("%username%") > -1) {
				bootbox.prompt({
					title: "<?php echo __('Enter <strong>%USERNAME%</strong> value'); ?>",
					//value: "",
					callback: function(result) {
						if (result === null) {
							btn.button('reset');
							bootbox.hideAll();
						}
						else {
							$("#ldap-auth-filter-username-value").val(result);
							ldapRequest();
						}
					}
				});


				return false;
			}
		}

		ldapRequest();

		btn.button('reset');
		e.preventDefault();
		return false;
	});

	function ldapRequest() {
		$.ajax({
			dataType: "HTML",
			type: "GET",
			data: $("#ldap-connector-form").serialize(),
			url: "/ldapConnectors/testLdap/time:"+ new Date().getTime()
		})
		.done(function(data) {
			$("#ldap-auth-filter-username-value").val("");
			$("#test-ldap").button('reset');
			bootbox.dialog({
				message: data,
				title: "<?php echo __('LDAP Results'); ?>",
				buttons: {
					main: {
						label: "<?php echo __('Ok'); ?>",
						className: "btn-primary",
						callback: function() {
							
						}
					}
				}
			});


		});
	}
});
</script>
