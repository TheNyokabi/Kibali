<?php
$options = array();
$options_ids = array();

if ( empty( $classification_type['AssetClassification'] ) ) {
	// continue;
	return true;
}

foreach ( $classification_type['AssetClassification'] as $asset_classification ) {
	$name = $asset_classification['name'];
	if (!empty($asset_classification['value'])) {
		$name = sprintf('%s (%s)', $asset_classification['name'], $asset_classification['value']);
	}

	$options[ $asset_classification['id'] ] = $name;
	$options_ids[] = $asset_classification['id'];
}

$selected = null;
if (isset($this->data['Asset']['_selected_classification_ids'])) {
	foreach($this->data['Asset']['_selected_classification_ids'] as $cIndex => $ac) {
		if (in_array($ac, $options_ids)) {
			$selected = $ac;
			unset($this->request->data['Asset']['_selected_classification_ids'][$cIndex]);
			
			break;
		}
	}
}

echo $this->Form->input( 'Asset.AssetClassification.', array(//asset_classification_id][
	'options' => $options,
	'label' => false,
	'div' => false,
	// 'style' => 'margin-bottom:5px;',
	'empty' => __( 'Classification' ) . ': ' . $classification_type['AssetClassificationType']['name'],
	'class' => 'form-control',
	'selected' => $selected
) );