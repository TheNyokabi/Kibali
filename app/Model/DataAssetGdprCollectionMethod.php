<?php
App::uses('AppModel', 'Model');

class DataAssetGdprCollectionMethod extends AppModel {

    /*
     * static enum: Model::function()
     * @access static
     */
    public static function collectionMethods($value = null) {
        $options = array(
            self::AUTOMATED => __('Automated'),
            self::MANUAL => __('Manual'),
        );
        return parent::enum($value, $options);
    }

    const AUTOMATED = 1;
    const MANUAL = 2;
}
