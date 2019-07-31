<?php
App::uses('ErambaHelper', 'View/Helper');

class AttachmentsHelper extends ErambaHelper {
    public $settings = array();
    
    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);

        $this->settings = $settings;
    }

/**
 * returns attachment download link
 * 
 * @param  array $attachment
 * @return string
 */
    public function downloadLink($attachment) {
        $url = $this->downloadUrl($attachment);

        $link = $this->Html->link('<i class="icon-download"></i>', $url, array(
            'class' => 'bs-tooltip',
            'escape' => false,
            'title' => __('Download')
        ));

        return $link;
    }

/**
 * returns attachment download url
 * 
 * @param  array $attachment
 * @return string
 */
    public function downloadUrl($attachment) {
        $url = Router::url(array(
            'controller' => controllerFromModel($attachment['Attachment']['model']),
            'action' => 'downloadAttachment',
            $attachment['Attachment']['id']
        ));

        return $url;
    }
}