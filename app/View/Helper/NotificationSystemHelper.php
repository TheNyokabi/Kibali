<?php
App::uses('ErambaHelper', 'View/Helper');
class NotificationSystemHelper extends ErambaHelper {
	public $settings = array();

	public function __construct(View $view, $settings = array()) {
		$this->helpers[] = 'Community';
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getIndexLink($model) {
		return $this->Community->getIndexLink(__('Notifications'));
	}

	public function getStatuses($item) {
		$statuses = array();

		if (!empty($item['NotificationObject'])) {
			$statuses[] = $this->getLabel(__('Active notification'), 'info');

			$sent = $ignored = false;
			foreach ($item['NotificationObject'] as $notification) {
				if (!$sent && $notification['status_feedback'] == NOTIFICATION_FEEDBACK_WAITING) {
					//$statuses[] = $this->getLabel(__('Notifications sent'), 'info');
					$sent = true;
				}
				if (!$ignored && $notification['status_feedback'] == NOTIFICATION_FEEDBACK_IGNORE) {
					//$statuses[] = $this->getLabel(__('Notifications ignored'), 'warning');
					$ignored = true;
				}
			}
		}

		return $this->processStatusesGroup($statuses);
	}

}