<?php
if ($ldapConnection !== true) {
	echo $this->element('not_found', array(
		'message' => $ldapConnection
	));

	return false;
}
?>
<?php
$selected = array();
if (isset($this->request->data['AwarenessProgramLdapGroup'])) {
	foreach ($this->request->data['AwarenessProgramLdapGroup'] as $entry) {
		$selected[] = $entry['id'];
	}
}

if (isset($this->request->data['AwarenessProgram']['ldap_groups']) && is_array($this->request->data['AwarenessProgram']['ldap_groups'])) {
	foreach ($this->request->data['AwarenessProgram']['ldap_groups'] as $entry) {
		$selected[] = $entry;
	}
}

if (isset($this->request->data['groups']) && is_array($this->request->data['groups'])) {
	foreach ($this->request->data['groups'] as $entry) {
		$selected[] = $entry;
	}
}

?>
<?php
echo $this->Form->input('AwarenessProgram.ldap_groups', array(
	'options' => $groups,
	'label' => false,
	'div' => false,
	'class' => 'select2 col-md-12 full-width-fix select2-offscreen',
	'id' => 'ldap-groups-field',
	'multiple' => true,
	'selected' => $selected,
	'disabled' => !empty($edit) ? true : false
));
?>
<span class="help-block"><?php echo __('Choose one or more LDAP groups'); ?></span>

<script type="text/javascript">
jQuery(function($) {
	$("#ldap-groups-field").select2({
		minimumInputLength: 2
	});
});
</script>