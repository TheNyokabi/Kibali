<?php
App::uses('AppModel', 'Model');

class DataAssetGdprDataType extends AppModel {

    /*
     * static enum: Model::function()
     * @access static
     */
    public static function dataTypes($value = null) {
        $options = array(
            self::PERSONAL => __('Personal Data'),
            self::SENSITIVE => __('Sensitive Data'),
            self::CRIMINAL_OFFENCES => __('Criminal Offences'),
            self::CHILD => __('Child\'s Data'),
            self::PSEUDONYMOUS => __('Pseudonymous Data'),
            self::OTHER => __('Other')
        );
        return parent::enum($value, $options);
    }

    const PERSONAL = 1;
    const SENSITIVE = 2;
    const CHILD = 3;
    const OTHER = 4;
    const CRIMINAL_OFFENCES = 5;
    const PSEUDONYMOUS = 6;
}
