<?php
// if a custom field element exists, we use it
$fieldElement = 'settings/edit/field/' . $setting['variable'];
if($this->elementExists($fieldElement)) {
    echo $this->element($fieldElement, array(
        'fieldName' => 'Setting.' . $setting['variable'],
        'setting' => $setting
    ));
}

// if we dont have a custom field element, we generate the input according to the settings
else {
    $value = !empty($setting['value'])?$setting['value']:$setting['default_value'];

    $class = 'form-control';
    if($setting['type'] == 'select'){
        $options = array(
            'options' => json_decode($setting['options'], true),
            'select' => $value
        );
    }
    elseif($setting['type'] == 'checkbox') {
        $options = array(
            'checked' => $value
        );
        $value = 1;
    }
    else{
        $options = !empty($setting['options'])?json_decode($setting['options'], true):array();
    }

    if($setting['type'] == 'checkbox'){
        $class = "uniform";
    }

    $input = $this->Form->input($setting['variable'], am($options, array(
        'label' => false,
        'div' => false,
        'class' => $class,
        'value' => $value,
        'type'   => $setting['type'],
    )));

    if($setting['type'] == 'checkbox'){
        echo $this->Html->tag('label', $input, array(
            'escape' => false,
            'class' => 'checkbox'
        ));
    }
    else{
        echo $input;
    }
}
?>