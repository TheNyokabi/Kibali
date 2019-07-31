<?php
App::uses('AppHelper', 'View/Helper');

class CommentsHelper extends AppHelper {
    public $helpers = array('Html', 'Text');
    public $settings = array();
    
    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->settings = $settings;
    }

    public function outputList($data, $options) {
        $result = array();

        if (empty($data)) {
            return getEmptyValue($data);
        }

        foreach ($data as $item) {
            $result[] = '(' . $item['User']['full_name'] . ') (' . date(DATE_FORMAT, strtotime($item['created'])) . ') ' . $item['message'];
        }

        return implode('<br>', $result);
    }
}