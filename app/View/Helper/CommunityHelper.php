<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Router', 'Routing');

class CommunityHelper extends AppHelper {
	public $helpers = ['Html', 'Icon'];

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
	}

	public function getIndexLink($text) {
		$btn = $this->Html->div("btn-group group-merge",
			$this->Html->link( '<i class="icon-info-sign"></i>' . $text, ERAMBA_ENTERPRISE_URL, array(
				'class' => 'btn',
				'escape' => false,
				'target' => 'blank'
			))
		);

		$div = $this->Html->div('bs-popover', $btn, array(
			'data-trigger' => 'hover',
			'data-placement' => 'top',
			'data-html' => 'true',
			'data-container' => 'body',
			'data-original-title' => 'Enterprise',
			'data-content' => 'This is an Enterprise feature, click to learn more.',
			'style' => 'float:left;'
		));
		
		return $div;	
	}

}