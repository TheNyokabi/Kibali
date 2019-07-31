<?php
App::uses('RiskCalculation', 'Model');

$options = array();
$options_ids = array();
if ( empty( $classification_type['RiskClassification'] ) ) {
	return true;
}

foreach ( $classification_type['RiskClassification'] as $risk_classification ) {
	$name = $risk_classification['name'];
	if (!empty($risk_classification['value'])) {
		$name .= ' (' . $risk_classification['value'] . ')';
	}

	$options[ $risk_classification['id'] ] = array(
		'name' => $name,
		'value' => $risk_classification['id'],
		'data-risk-value' => $risk_classification['value']
	);
	$options_ids[] = $risk_classification['id'];
}

echo $this->Form->input($model . '.' . $classModel . '.', array(
	'options' => $options,
	'label' => false,
	'div' => false,
	'empty' => __( 'Classification' ) . ': ' . $classification_type['RiskClassificationType']['name'],
	'class' => 'form-control risk-classification-select',
	'selected' => $selected,
	'id' => 'risk-classification-select-' . $key,
	'data-index-key' => $key,
	'selected' => $selected
));
?>
<br />

<?php
if ($selected) {
	$infoText = $classificationCriteria[$selected];
}
else {
	$infoText = __('No criteria has been specified for this classification');
}

// info box with a description for currently selected classification item
echo $this->Ux->getAlert($infoText, [
	'type' => 'info',
	'class' => ['risk-classification-info-block'],
	'id' => 'risk-classification-select-helper-' . $key
]);
?>
