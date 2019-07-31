<?php
App::uses('AppModel', 'Model');

class DataAssetGdprLawfulBase extends AppModel {

    /*
     * static enum: Model::function()
     * @access static
     */
    public static function lawfulBases($value = null) {
        $options = array(
            self::CONSENT => __('Consent'),
            self::CONTRACTUAL_OBLIGATION => __('Contractual Obligation'),
            self::LEGAL_OBLIGATION => __('Legal Obligation'),
            self::VITAL_INTEREST => __('Vital Interest'),
            self::PUBLIC_INTEREST => __('Public Interest'),
            self::LEGITIMATE_INTEREST => __('Legitimate Interest'),
        );
        return parent::enum($value, $options);
    }

    const CONSENT = 1;
    const CONTRACTUAL_OBLIGATION = 2;
    const LEGAL_OBLIGATION = 3;
    const VITAL_INTEREST = 4;
    const PUBLIC_INTEREST = 5;
    const LEGITIMATE_INTEREST = 6;
}
