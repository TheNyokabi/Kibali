<?php
App::uses('AppHelper', 'View/Helper');
class ObjectVersionAuditHelper extends AppHelper {
	public $helpers = array('Html', 'Ux');

	public function getTimelineClass(ObjectVersionAuditEvent $Event, $i) {
		if ($i % 2) {
			return 'timeline-inverted';
		}

		return false;

		// if ($Event->isEdited()) {
		// 	return 'timeline-inverted';
		// }

		// return false;
	}

	public function getTimelineBadge(ObjectVersionAuditEvent $Event) {
		$badgeClass = false;
		$iconClass = false;

		if ($Event->isCreated()) {
			$badgeClass = 'success';
			$iconClass = 'plus-sign';
		}

		if ($Event->isEdited()) {
			$badgeClass = 'info';
			$iconClass = 'pencil';
		}

		if ($Event->isDeleted()) {
			$badgeClass = 'danger';
			$iconClass = 'trash';
		}

		if ($Event->isRestored()) {
			$badgeClass = 'warning';
			$iconClass = 'retweet';
		}

		if (empty($badgeClass) || empty($iconClass)) {
			return false;
		}

		$divClassArr = array('timeline-badge', $badgeClass);
		$divClass = implode(' ', $divClassArr);

		$iconHtml = $this->Ux->getIcon($iconClass);
		$divHtml = $this->Html->div($divClass, $iconHtml);

		return $divHtml;
	}
}