<?php
App::uses('ErambaHelper', 'View/Helper');
App::uses('Review', 'Model');
class SecurityPoliciesHelper extends ErambaHelper {
	public $helpers = array('NotificationSystem', 'Html', 'Policy');
	public $settings = array();

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatusArr($item, $allow = '*') {
		$item = $this->processItemArray($item, 'SecurityPolicy');
		$statuses = array();

		if ($this->getAllowCond($allow, 'status') && $item['SecurityPolicy']['status'] == SECURITY_POLICY_DRAFT) {
			$statuses[$this->getStatusKey('status')] = array(
				'label' => __('Draft'),
				'type' => 'danger'
			);
		}

		if ($this->getAllowCond($allow, 'expired_reviews') && $item['SecurityPolicy']['expired_reviews'] == RISK_EXPIRED_REVIEWS) {
			$statuses[$this->getStatusKey('expired_reviews')] = array(
				'label' => __('Missing Reviews'),
				'type' => 'warning'
			);
		}

		return $statuses;
	}

	public function getStatuses($item, $options = array()) {
		$options = $this->processStatusOptions($options);
		$statuses = $this->getStatusArr($item, $options['allow']);

		return $this->styleStatuses($statuses, $options);
	}

	public function documentLink($policy, $options = array()) {
		$options = am(array(
			'title' => '<i class="icon-info-sign"></i>',
			'tooltip' => __('View'),
			'class' => array(),
			'style' => null
		), $options);

		if (is_string($options['class'])) {
			$options['class'] = explode(' ', $options['class']);
		}

		$viewUrl = $this->Policy->getDocumentUrl($policy['id'], true);
		$documentAttrs = $this->Policy->getDocumentAttrs($policy['id']);

		$documentAttrs['class'] = implode(' ', $options['class']);
		$documentAttrs['style'] = $options['style'];

		if (!empty($options['tooltip'])) {
			$documentAttrs = am($documentAttrs, array(
				'class' => 'bs-tooltip',
				'title' => $options['tooltip'],
				'style' => 'text-decoration:none;'
			));
		}

		return $this->Html->link($options['title'], $viewUrl, $documentAttrs);

		/*if (empty($policy['use_attachments'])) {
			$viewUrl = $this->Policy->getDocumentUrl($policy['id'], true);
			$documentAttrs = $this->Policy->getDocumentAttrs($policy['id']);
			$documentAttrs = am($documentAttrs, array(
				'class' => 'bs-tooltip',
				'title' => __('View'),
				'style' => 'text-decoration:none;'
			));

			return $this->Html->link('<i class="icon-info-sign"></i>', $viewUrl, $documentAttrs);
		}
		elseif ($policy['use_attachments'] == SECURITY_POLICY_USE_URL) {
			return $this->Html->link('<i class="icon-info-sign"></i>', $policy['url'], array(
				'target' => '_blank',
				'escape' => false
			));
		}
		else {
			$viewUrl = $this->Policy->getDocumentUrl($policy['id'], true);
			$documentAttrs = $this->Policy->getDocumentAttrs($policy['id']);
			$documentAttrs = am($documentAttrs, array(
				'class' => 'bs-tooltip',
				'title' => __('View'),
				'style' => 'text-decoration:none;'
			));

			return $this->Html->link('<i class="icon-info-sign"></i>', $viewUrl, $documentAttrs);
		}*/
	}
}