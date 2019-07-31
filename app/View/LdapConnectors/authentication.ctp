<div class="row">
	<div class="col-md-12">
		<div class="widget box">
			<div class="widget-content">

				<?php
				echo $this->Form->create('LdapConnectorAuthentication', array(
					'url' => array('controller' => 'ldapConnectors', 'action' => 'authentication'),
					'class' => 'form-horizontal row-border',
					'id' => 'ldap-connector-form',
					'novalidate' => true
				));
				
				$submit_label = __('Edit');
				?>

				<div>
					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('General Eramba Authentication'); ?>:</label>
						<div class="col-md-10">

							<label class="radio">
								<?php
								echo $this->Form->input('general_auth', array(
									'type' => 'radio',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'id' => 'auth-users-default-radio',
									'options' => array($general_auth_default => __('Use Default authentication'))
								));
								?>
							</label>
							<span class="help-block"><?php echo __('OPTIONAL: If you click on the radiobox your system will start authenticating with local database. You need to create user accounts on the system (System / Settings / User Management).'); ?></span>
							
							<hr>

							<label class="radio">
								<?php
								echo $this->Form->input('general_auth', array(
									'type' => 'radio',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'id' => 'auth-users-ldap-radio',
									'data-select-elem' => '#auth-users-ldap-select',
									'options' => array($general_auth_ldap => __('Use LDAP to authenticate users'))
								));
								?>
							</label>
							<br />
							<?php
							echo $this->Form->input('auth_users_id', array(
								'options' => $authenticators,
								'label' => false,
								'div' => false,
								'class' => 'form-control',
								'id' => 'auth-users-ldap-select',
								'empty' => __('Choose an LDAP Connector')
							));
							?>
							<span class="help-block"><?php echo __('OPTIONAL: If you click on the radiobox and select a functional LDAP connector (System / Settings / LDAP Connectors) your system will start authenticating with LDAP instead of the local database. You also need to create user accounts on the system (System / Settings / User Management) so the AD login and the local account login matches (they are the same).'); ?></span>

							<hr>

							<label class="radio">
								<?php echo $this->Form->input('general_auth', array(
									'type' => 'radio',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'id' => 'auth-users-oauth-google-radio',
									'data-select-elem' => '#auth-users-oauth-google-select',
									'options' => array($general_auth_oauth_google => __('Use OAuth Google to authenticate users'))
								)); ?>
							</label>
							<br />
							<?php
							echo $this->Form->input('oauth_google_id', array(
								'options' => $oauthGoogleConnectors,
								'label' => false,
								'div' => false,
								'class' => 'form-control',
								'id' => 'auth-users-oauth-google-select',
								'empty' => __('Choose an OAuth Connector')
							));
							?>
							<span class="help-block"><?php echo __('OPTIONAL: If you click on the radiobox and select a functional OAuth Connector (System / Settings / OAuth Connectors) users will be able to use Google SignIn button for login. You also need to create user accounts on the system (System / Settings / User Management) so the OAuth login (email) and the local account login (email) matches (they are the same).'); ?></span>
						</div>

					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Awareness Portal Authentication'); ?>:</label>
						<div class="col-md-10">
							<label class="checkbox">
								<?php echo $this->Form->input('auth_awareness', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'id' => 'auth-awareness-checkbox'
								)); ?>
								<?php echo __('Enable LDAP for Awareness module authentication'); ?>
							</label>
							<br />
							<?php
							echo $this->Form->input('auth_awareness_id', array(
								'options' => $authenticators,
								'label' => false,
								'div' => false,
								'class' => 'form-control',
								'id' => 'auth-awareness-select',
								'empty' => __('Choose an LDAP Connector')
							));
							?>
							<span class="help-block"><?php echo __('OPTIONAL: If you enable the checkbox the awareness portal (used for awareness trainings) will be active and authenticating users with LDAP. No local user accounts are needed for this functionality to operate.'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Policy Portal Authentication'); ?>:</label>
						<div class="col-md-10">
							<label class="checkbox">
								<?php echo $this->Form->input('auth_policies', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'id' => 'auth-policies-checkbox'
								)); ?>
								<?php echo __('Enable Policy Portal'); ?>
							</label>
							<br />
							<?php
							echo $this->Form->input('auth_policies_id', array(
								'options' => $authenticators,
								'label' => false,
								'div' => false,
								'class' => 'form-control',
								'id' => 'auth-policies-select',
								'empty' => __('Activate the portal without LDAP')
							));
							?>
							<span class="help-block"><?php echo __('OPTIONAL: If you enable the checkbox the policy portal (used for displaying policies on a dedicated portal) will be active and authenticating users with LDAP or not (simply allowing users without authentication). This functionality is tied with the settings you have defined for each policy (under Control Catalogue / Security Policies)'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Vendor Assesments Portal Authentication'); ?>:</label>
						<div class="col-md-10">
							<label class="checkbox">
								<?php echo $this->Form->input('auth_vendor_assessment', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'id' => 'auth-vendor-assessmemnt'
								)); ?>
								<?php echo __('Enable Vendor Assesments Portal'); ?>
							</label>
							<span class="help-block"><?php echo __('OPTIONAL: If you enable the checkbox the venodr assesments portal will be active and also re-use general eramba authentication configuration.'); ?></span>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label"><?php echo __('Third Party Audits Portal Authentication'); ?>:</label>
						<div class="col-md-10">
							<label class="checkbox">
								<?php echo $this->Form->input('auth_compliance_audit', array(
									'type' => 'checkbox',
									'label' => false,
									'div' => false,
									'class' => 'uniform',
									'id' => 'auth-compliance-checkbox'
								)); ?>
								<?php echo __('Enable Third Party Audit Portal'); ?>
							</label>
							<span class="help-block"><?php echo __('OPTIONAL: If you enable the checkbox the third party audits portal will be active and also re-use general eramba authentication configuration.'); ?></span>
						</div>
					</div>
				</div>

				<?php
				if(isset($redirectUrl)) {
					echo $this->Form->hidden('redirectUrl', array(
						'value' => $redirectUrl
					));
				}
				?>

				<div class="form-actions">
					<?php
					echo $this->Form->submit($submit_label, array(
						'class' => 'btn btn-primary',
						'div' => false
					));
					?>
					&nbsp;
					<?php
					$cancelUrl = [
						'controller' => 'settings',
						'action' => 'index'
					];
					if (isset($redirectUrl)) {
						$cancelUrl = $redirectUrl;
					}
					echo $this->Html->link(__('Cancel'), $cancelUrl, array(
						'class' => 'btn btn-inverse'
					));
					?>
				</div>

				<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
jQuery(function($) {
	bindAuthRadioEvents("data[LdapConnectorAuthentication][general_auth]");
	bindAuthEvents("#auth-awareness-checkbox", "#auth-awareness-select");
	bindAuthEvents("#auth-policies-checkbox", "#auth-policies-select");

	function bindAuthEvents(checkboxEle, selectEle) {
		$(checkboxEle).on("change", function(e) {
			setReadonlySelectBox($(this), selectEle);
		}).trigger("change");
	}

	function bindAuthRadioEvents(radioEleName) {
		$radioElems = $("[name='" + radioEleName + "']");
		$("[name='" + radioEleName + "']").on("change", function(e) {
			$radioElems.each(function() {
				var selectElem = $(this).attr('data-select-elem');
				setReadonlySelectBox($(this), selectElem);
			});
		}).trigger("change");
	}

	function setReadonlySelectBox(triggeredElem, selectElem)
	{
		if (selectElem) {
			if (triggeredElem.is(":checked")) {
				$(selectElem).removeAttr("readonly");
			} else {
				$(selectElem).attr("readonly", "readonly");
			}
		}
	}
});
</script>