<?php
App::uses('Component', 'Controller');
App::uses('NotificationSystem', 'Model');
App::uses('CakePdf', 'CakePdf.Pdf');
App::uses('CakeLog', 'Log');

class NotificationSystemMgtComponent extends Component {
	public $components = array('CustomFields.CustomFieldsMgt', 'AdvancedFiltersCron', 'Pdf');

	public function __construct(ComponentCollection $collection, $settings = array()) {
		$bases = array(
			'SecurityServiceAudit_Begin_Base',
			'ServiceContract_Expiration_Base',
			'PolicyException_Expiration_Base',
			'RiskException_Expiration_Base',
			'ComplianceFinding_Deadline_Base',
			'Asset_Expiration_Base',
			'BusinessContinuity_Expiration_Base',
			'Risk_Expiration_Base',
			'ThirdPartyRisk_Expiration_Base',
			'SecurityServiceMaintenance_Start_Base',
			'SecurityPolicy_ReviewDate_Base',
			'BusinessContinuityPlan_AuditBegin_Base',
			'Project_Deadline_Base',
			'ProjectAchievement_Deadline_Base',
			'ComplianceAudit_Deadline_Base',
			'ComplianceAnalysisFinding_Expiration_Base',
			'ComplianceException_Expiration_Base',
			'InactivityBase',
			'VendorAssessment_EndDate_Base',
			'VendorAssessment_RecurrenceDate_Base',
			'VendorAssessmentFinding_Deadline_Base',
		);

		App::import('Vendor', 'NotificationsBase', array('file' => 'notifications/NotificationsBase.class.php'));
		$this->initCustomBases($bases);

		parent::__construct($collection, $settings);
	}

	// custom bases for notifications
	private function initCustomBases($bases) {
		foreach ($bases as $base) {
			App::import('Vendor', $base, array(
				'file' => 'notifications/' . $base . '.php'
			));
		}
	}

	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->CustomFieldsMgt->initialize($controller);

		$this->controller->loadModel('NotificationObject');
		$this->NotificationObject = $this->controller->NotificationObject;
		App::import('Vendor', 'NotificationsBase', array('file' => 'notifications/NotificationsBase.class.php'));
	}

	/**
	 * Loads and returns a custom notification class.
	 */
	public function loadClass($file, $type = NOTIFICATION_TYPE_WARNING, $options = array()) {
		/*if (isset($this->loadedClasses[$file])) {
			return $this->loadedClasses[$file];
		}*/

		$className = basename($file, '.php');
		App::import('Vendor', $className, array('file' => 'notifications/' . $type . '/' . $file));
		/*$this->loadedClasses[$file] = new $className();

		return $this->loadedClasses[$file];*/
		return new $className($options);
	}

	/**
	 * CRON function to process all notifications by type and notifies users about it.
	 */
	public function cron($type = NOTIFICATION_TYPE_WARNING) {
		$this->controller->loadModel('NotificationObject');
		$this->NotificationObject = $this->controller->NotificationObject;

		$this->controller->loadModel('Notification');

		//firstly we check if there are feedback chase reminders that should go out
		$ret = $this->checkFeedbackChase($type);

		// debug($type);

		//then we get notification IDs of item that was triggered
		$triggeredItems = $this->getTriggeredData($type);

		//we log a reminder and send out emails and notifications about it
		if (!empty($triggeredItems)) {
			foreach ($triggeredItems as $id) {
				$logData = array(
					'notification_system_item_object_id' => $id
				);
				$ret &= $this->setLogReminder($logData);

				$logId = $this->NotificationObject->NotificationLog->id;

				$ret &= $this->sendNotificationsEmails($logId);
			}
		}

		return $ret;
	}

	/**
	 * Checks which records have met a conditions and/or were triggered by time period.
	 */
	private function getTriggeredData($type) {
		$data = $this->NotificationObject->find('all', array(
			'conditions' => array(
				'NotificationSystem.type' => $type,
				'NotificationObject.status_feedback' => array(NOTIFICATION_FEEDBACK_OK, NOTIFICATION_FEEDBACK_IGNORE),
				'NotificationObject.status' => NOTIFICATION_OBJECT_ENABLED
			),
			'fields' => array('id', 'model', 'foreign_key', 'log_count', 'created'),
			'contain' => array(
				'NotificationSystem' => array(
					'fields' => array('name', 'filename', 'trigger_period')
				),
				'NotificationLog' => array(
					'conditions' => array('is_new' => 1),
					'fields' => array('is_new', 'feedback_resolved', 'created', 'modified', 'DATE(created) as date'),
					'limit' => 1,
					'order' => array('created' => 'DESC')
				)
			)
		));

		$now = date('Y-m-d H:i:s');
		$triggeredItems = array();
		foreach ($data as $checkItem) {

			// dont send more than one warnings for an item a day
			if ($checkItem['NotificationObject']['log_count'] && isset($checkItem['NotificationLog'][0]) && $checkItem['NotificationLog'][0][0]['date'] == date('Y-m-d')) {
				continue;
			}

			$class = $this->loadClass($checkItem['NotificationSystem']['filename'], $type, array(
				'triggerPeriod' => $checkItem['NotificationSystem']['trigger_period']
			));

			// if is default type
			if ($class->isDefaultType) {
				continue;
			}

			if (empty($class->model)) {
				$class->model = $checkItem['NotificationObject']['model'];
			}

			if ($class->triggerPeriod && $checkItem['NotificationObject']['log_count']) {
				$timestamp = strtotime('+' . $class->_periodFormatted, strtotime($checkItem['NotificationLog'][0]['created']));
				$triggerDate = date('Y-m-d H:i:s', $timestamp);
				
				if ($triggerDate > $now) {
					continue;
				}
			}

			$isTriggered = $this->compareItem($class, $checkItem['NotificationObject']['foreign_key']);
			if (!empty($isTriggered)) {
				$triggeredItems[] = $checkItem['NotificationObject']['id'];

				$ret = $this->logNotification($class->model, $checkItem['NotificationObject']['foreign_key'], $checkItem['NotificationSystem']['name']);

				/*$this->controller->{$class->model}->id = $checkItem['NotificationObject']['foreign_key'];
				$this->controller->{$class->model}->addNoteToLog(__('Notification "%s" was triggered', $checkItem['NotificationSystem']['name']));
				$ret = $this->controller->{$class->model}->setSystemRecord($checkItem['NotificationObject']['foreign_key'], 2);*/
			}
		}

		return $triggeredItems;
	}

	private function logNotification($model, $itemId, $notificationName) {
		// in case foreign key is NULL, we continue
		if (empty($itemId)) {
			return true;
		}

		$this->controller->{$model}->id = $itemId;
		$this->controller->{$model}->addNoteToLog(__('Notification "%s" was triggered', $notificationName));
		return $this->controller->{$model}->setSystemRecord($itemId, 2);
	}

	/**
	 * Compare selected itemId against the conditions of a custom notification.
	 */
	private function compareItem($class, $itemId) {
		// in case this is a report type notification, we dont compare and parse items
		if ($class->isReportType) {
			return true;
		}

		$conds = $class->conditions;
		$conds[$class->model . '.id'] = $itemId;

		$this->controller->loadModel($class->model);
		$this->controller->{$class->model}->alterQueries(true);
		$item = $this->controller->{$class->model}->find('first', array(
			'conditions' => $conds,
			'contain' => $class->contain,
			'recursive' => -1
		));
		$this->controller->{$class->model}->alterQueries();

		$results = $class->parseData($item);

		return $results;
	}

	/**
	 * We check if there is a feedback chase reminder that should go out.
	 */
	private function checkFeedbackChase($type) {
		$data = $this->NotificationObject->find('all', array(
			'conditions' => array(
				'NotificationSystem.feedback' => 1,
				'NotificationObject.status_feedback' => NOTIFICATION_FEEDBACK_WAITING,
				'NotificationSystem.type' => $type
			),
			'fields' => array('NotificationObject.id', 'NotificationObject.status_feedback', 'NotificationObject.foreign_key', 'NotificationObject.model'),
			'contain' => array(
				'NotificationSystem' => array(
					'fields' => array('chase_interval', 'chase_amount', 'feedback', 'type')
				),
				'NotificationLog' => array(
					//'limit' => 1,
					'order' => array('created' => 'DESC')
				)
			)
		));

		$ret = true;
		foreach ($data as $item) {
			$chaseAmount = $item['NotificationSystem']['chase_amount'];
			if ($chaseAmount == 0) {
				continue;
			}

			$chaseInt = $item['NotificationSystem']['chase_interval'];
			$now = date('Y-m-d H:i:s');

			$logCount = $this->getLatestNotificationLogCount($item['NotificationLog']);

			if (empty($item['NotificationLog'])) {
				$chaseDate = false;
			}
			else {
				$timestamp = strtotime('+' . $chaseInt . ' days', strtotime($item['NotificationLog'][0]['created']));
				$chaseDate = date('Y-m-d H:i:s', $timestamp);
			}

			if (($chaseDate == false || $chaseDate < $now) && $logCount <= $chaseAmount) {
				$reminderData = array(
					'notification_system_item_object_id' => $item['NotificationObject']['id'],
					'is_new' => 0
				);

				$ret &= $this->setLogReminder($reminderData);
				$logId = $this->NotificationObject->NotificationLog->id;
				$ret &= $this->sendNotificationsEmails($logId);
			}
			elseif ($logCount > $chaseAmount) {
				$this->NotificationObject->id = $item['NotificationObject']['id'];
				$ret &= (boolean) $this->NotificationObject->saveField('status_feedback', NOTIFICATION_FEEDBACK_IGNORE, false);

				$this->controller->loadModel($item['NotificationObject']['model']);
				$this->controller->{$item['NotificationObject']['model']}->id = $item['NotificationObject']['foreign_key'];
				$this->controller->{$item['NotificationObject']['model']}->addNoteToLog(__('Notifications for this item ignored'));
				$ret &= $this->controller->{$item['NotificationObject']['model']}->setSystemRecord($item['NotificationObject']['foreign_key'], 2);
			}

		}

		return $ret;
	}

	/**
	 * Counts reminders sent for latest notification trigger.
	 */
	private function getLatestNotificationLogCount($data) {
		if (empty($data)) {
			return 0;
		}

		$count = 1;
		foreach ($data as $log) {
			if ($log['is_new'] == 1) {
				return $count;
			}
			else {
				$count++;
			}
		}
	}

	/**
	 * Saves a notification log data.
	 */
	private function setLogReminder($saveData) {
		$this->NotificationObject->NotificationLog->create();
		$this->NotificationObject->NotificationLog->set($saveData);
		return $this->NotificationObject->NotificationLog->save();
	}


	/**
	 * Sends a notification alerts.
	 */
	private function sendNotificationsEmails($notificationLogId = null, $notificationId = null, $additionalData = array()) {
		if (empty($notificationLogId) && empty($notificationId)) {
			return false;
		}

		if (!empty($notificationLogId)) {
			$notificationLogData = $this->NotificationObject->NotificationLog->find('first', array(
				'conditions' => array(
					'NotificationLog.id' => $notificationLogId
				),
				'fields' => array('id', 'notification_system_item_object_id'),
				'recursive' => -1
			));

			$notificationId = $notificationLogData['NotificationLog']['notification_system_item_object_id'];
		}

		$notificationData = $this->NotificationObject->find('first', array(
			'conditions' => array(
				'NotificationObject.id' => $notificationId
			),
			'recursive' => 0
		));

		$class = $this->loadClass($notificationData['NotificationSystem']['filename'], $notificationData['NotificationSystem']['type']);
		$model = $notificationData['NotificationObject']['model'];
		$foreign_key = $notificationData['NotificationObject']['foreign_key'];
		$this->controller->loadModel($model);
		$this->controller->{$model}->id = $foreign_key;

		// handler for deleted objects that could make their way here, not applicable to report notifications
		if ($foreign_key !== null && !$this->controller->{$model}->exists()) {
			CakeLog::write('debug', sprintf('Object %s.%s cannot have notifications sent because it is already deleted. %s', $model, $foreign_key, print_r($notificationData, true)));
			return true;
		}

		$url = $itemTitle = $itemData = $macros = null;
		// we use item's data only if the notification is not a report type
		if (!$class->isReportType) {
			$url = $this->controller->getIndexUrlFromComponent($model, $foreign_key);
			$itemTitle = $this->controller->{$model}->getRecordTitle($foreign_key);
			$itemData = $this->getItemData($model, $foreign_key);

			$macros = $this->getMacros($model);
		}
		else {

		}
		
		//plugin prefix url fix
		if (is_array($url)) {
			$url['plugin'] = null;
		}
		
		//$userData = $this->pullUserData($notificationData);
		$userData = $this->pullUserData($notificationData['NotificationSystem']['id'], $notificationData['NotificationObject']['id']);
		// debug($userData);exit;

		$ret = true;
		if ($notificationData['NotificationSystem']['email_notification']) {
			$emailsUsed = array();

			if ($notificationData['NotificationSystem']['email_customized']) {
				$feedbackUrl = false;
				if ($notificationData['NotificationSystem']['feedback']) {
					$feedbackUrl = Router::url($this->getFeedbackUrl($notificationLogId), true);
				}

				$ret &= $this->sendCustomizedEmails($userData['allEmails'], array(
					'notificationData' => $notificationData,
					'itemData' => $itemData,
					'macros' => $macros,
					'model' => $model,
					'feedbackUrl' => $feedbackUrl,
					'notificationInstance' => (array) $class
				));

				$emailsUsed = $userData['allEmails'];
			}
			else {
				//if feedback is set
				if ($notificationData['NotificationSystem']['feedback']) {
					$ret &= $this->sendFeedbackEmails($userData['userEmails'], array(
						'feedbackUrl' => Router::url($this->getFeedbackUrl($notificationLogId), true),
						'url' => Router::url($url, true),
						'notificationTitle' => $class->title,
						'itemTitle' => $itemTitle,
						'itemData' => $itemData,
						'additionalData' => $additionalData,
						'macros' => $macros,
						'model' => $model,
						'notificationData' => $notificationData,
						'notificationInstance' => (array) $class
					), $class);

					$emailsUsed = array_unique(am($emailsUsed, $userData['userEmails']));
				}
				else {
					$ret &= $this->sendWarningEmails($userData['userEmails'], array(
						'url' => Router::url($url, true),
						'notificationTitle' => $class->title,
						'itemTitle' => $itemTitle,
						'itemData' => $itemData,
						'additionalData' => $additionalData,
						'macros' => $macros,
						'model' => $model,
						'notificationData' => $notificationData,
						'notificationInstance' => (array) $class
					), $class);

					$emailsUsed = array_unique(am($emailsUsed, $userData['userEmails']));
				}

				//send general warnings to external emails
				$ret &= $this->sendWarningCustomEmails($userData['customEmails'], array(
					'notificationTitle' => $class->title,
					'itemTitle' => $itemTitle,
					'itemData' => $itemData,
					'additionalData' => $additionalData,
					'macros' => $macros,
					'model' => $model,
					'notificationData' => $notificationData,
					'notificationInstance' => (array) $class
				), $class);

				$emailsUsed = array_unique(am($emailsUsed, $userData['customEmails']));
			}
		}

		if ($notificationData['NotificationSystem']['header_notification']) {
			//feedback request header notification
			if ($notificationData['NotificationSystem']['feedback']) {
				foreach ($userData['userIds'] as $userId) {
					$notificationRecord = array(
						'title' => $class->title . ' (' . __('Feedback Required') . ')',
						'model' => $model,
						'user_id' => $userId,
						'url' => Router::url($this->getFeedbackUrl($notificationLogId))
					);

					$ret &= $this->controller->Notification->setNotification($notificationRecord);
				}
			}
			else {
				//just a warning header notification
				foreach ($userData['userIds'] as $userId) {
					$notificationRecord = array(
						'title' => $class->title,
						'model' => $model,
						'user_id' => $userId,
						'url' => Router::url($url)
					);

					$ret &= $this->controller->Notification->setNotification($notificationRecord);
				}
			}
		}

		//if feedback is required, we update a database record accordingly
		if ($notificationData['NotificationSystem']['feedback']) {
			$this->NotificationObject->id = $notificationId;
			$ret &= (boolean) $this->NotificationObject->saveField('status_feedback', NOTIFICATION_FEEDBACK_WAITING, false);
		}

		$options = array(
			'titleSuffix' => ' <b>(' . __('Notifications sent') . ')</b>'
		);

		if (!empty($emailsUsed)) {
			$log = __('Notification emails sent to: %s', implode(', ', $emailsUsed));
		}
		else {
			$log = __('There is no one to send Notification emails to');
		}
		//save a customized system record
		$ret &= $this->controller->{$model}->setSystemRecord($foreign_key, 2, $options);

		$this->controller->{$model}->id = $foreign_key;
		$this->controller->{$model}->addNoteToLog($log);
		$ret &= $this->controller->{$model}->setSystemRecord($foreign_key, 2);

		return $ret;
	}

	/**
	 * Get the list of macros for a certain model. Possibility to add custom macro names into the list in format 'MACRO' => __('name')
	 */
	public function getMacros($model, $customMacros = array()) {
		if (!isset($this->settings[$model]['macros'])) {
			$this->controller->loadModel($model);
			$macros = $this->controller->{$model}->notificationSystem['macros'];
			$macros = $macros + $this->getCustomFieldMacros($model, false);

			// custom field macros from associated models
			if (!empty($this->controller->{$model}->notificationSystem['associateCustomFields'])) {
				foreach ($this->controller->{$model}->notificationSystem['associateCustomFields'] as $assocModel => $assocSlug) {
					$macros = $macros + $this->getCustomFieldMacros($assocModel, $assocSlug);
				}
			}

			$this->settings[$model]['macros'] = $macros;
		}

		if (!empty($customMacros)) {
			$_macros = $this->settings[$model]['macros'];

			foreach ($customMacros as $customMacro => $customName) {
				$_macros[$customMacro] = array(
					'name' => $customName
				);
			}

			return $_macros;
		}

		return $this->settings[$model]['macros'];
	}

	private function getCustomFieldMacros($model, $customSlug = false) {
		$customFieldsData = $this->CustomFieldsMgt->getData($model);

		$macros = array();
		if (!empty($customFieldsData['available'])) {
			foreach ($customFieldsData['customFieldsList'] as $field) {
				$slug = 'CUSTOM_';
				$name = __('Custom Field');
				if ($customSlug) {
					$slug .= $customSlug . '_';
					$name = __('Custom Field (%s)', getModelLabel($model, array('singular' => true)));
				}

				$slug .= $field['CustomField']['slug'];

				$macros[$slug] = array(
					'type' => 'CustomField',
					'associated' => $customSlug ? $model : false,
					'name' => sprintf('%s %s', $name, $field['CustomField']['name']),
					'CustomField' => $field['CustomField']
				);
			}
		}

		return $macros;
	}

	public function getCustomEmail($model) {
		if (!isset($this->settings[$model]['customEmail'])) {
			$this->controller->loadModel($model);
			$this->settings[$model]['customEmail'] = $this->controller->{$model}->notificationSystem['customEmail'];
		}
		
		return $this->settings[$model]['customEmail'];
	}

	private function getFeedbackUrl($notificationLogId) {
		return array(
			'plugin' => null,
			'controller' => 'notificationSystem',
			'action' => 'feedback',
			$notificationLogId
		);
	}

	private function getItemData($model, $foreign_key) {
		$_m = $this->controller->{$model};

		$data = $this->controller->{$model}->find('first', array(
			'conditions' => array(
				$model . '.id' => $foreign_key
			),
			'recursive' => 2
		));

		// custom field macros from associated models
		if (!empty($_m->notificationSystem['associateCustomFields'])) {
			foreach ($_m->notificationSystem['associateCustomFields'] as $assocModel => $assocSlug) {
				$_m->{$assocModel}->bindCustomFieldValues();
				$assoc = $_m->getAssociated($assocModel);

				if ($assoc['association'] == 'belongsTo') {
					$conds = array(
						$assoc['className'] . '.' . $_m->{$assocModel}->primaryKey => $data[$model][$assoc['foreignKey']]
					);
				}

				$_m->{$assocModel}->alterQueries(true);
				$subData = $_m->{$assocModel}->find('first', array(
					'conditions' => $conds,
					'contain' => array('CustomFieldValue')
				));
				$_m->{$assocModel}->alterQueries();

				$data[$assocModel]['CustomFieldValue'] = $subData['CustomFieldValue'];
			}
		}
		
		return $data;
	}

	private function sendCustomizedEmails($emails, $data) {
		if (empty($emails)) {
			return true;
		}

		$item = $data['itemData'];
		$notification = $data['notificationData']['NotificationSystem'];
		$macros = $data['macros'];
		$model = $data['model'];

		$subject = $this->parseMacros($notification['email_subject'], $item, $macros, $model);
		$body = $this->parseMacros($notification['email_body'], $item, $macros, $model);
		
		$_subject = $subject;
		$email = $this->controller->initEmailFromComponent($emails, $_subject, 'notifications/customized_email', array(
			'body' => $body,
			'feedbackUrl' => $data['feedbackUrl']
		));

		// for report notification we include attachments
		if ($notification['type'] == NOTIFICATION_TYPE_REPORT) {
			$attachments = $this->getAdvancedFiltersAttachments($notification);
			if (!empty($attachments)) {
				$email->attachments($attachments);
			}
		}

		return (bool) $email->send();
	}

	public function parseMacros($text, $item, $macros, $model) {
		if (empty($macros)) {
			return $text;
		}

		foreach ($macros as $macro => $options) {

			// custom field usage
			if (isset($options['type']) && $options['type'] == 'CustomField') {
				$pullFromData = $item;
				if ($options['associated']) {
					$pullFromData = $item[$options['associated']];
				}

				$value = getCustomFieldItemValue($pullFromData, $options['CustomField']);
			}

			// statuses usage
			elseif (isset($options['type']) && $options['type'] == 'status') {
				$statusModel = $options['status']['model'];

				$baseHelper = Inflector::pluralize($statusModel);
				$baseHelperClass = $baseHelper . 'Helper';

				App::import('Helper', $baseHelper);
				$baseClass = new $baseHelperClass(new View());
				
				$value = $baseClass->getFilterStatusesValue($item, $statusModel, '*', true);
			}

			//custom callback usage
			elseif (isset($options['type']) && $options['type'] == 'callback') {

				// if field parameter is provided we use extracted value as an argument
				if (isset($options['field'])) {
					$arg = Hash::extract($item, $options['field']);
					$arg = implode(', ', $arg);
				}
				// otherwise we use item's ID
				else {
					$this->controller->loadModel($model);
					$primaryKey = $this->controller->{$model}->primaryKey;
					$arg = $item[$model][$primaryKey];
				}

				$value = call_user_func_array($options['callback'], array($arg));
			}

			// default usage
			else {
				$value = Hash::extract($item, $options['field']);
			}

			if (!empty($value) && is_array($value)) {
				$value = implode(', ', $value);
			}
			elseif (empty($value)) {
				$value = '-';
			}

			$text = $this->replaceMacro($text, $macro, $value);
		}

		return $text;
	}

	/**
	 * Replace a macro with a provided value inside of a text string.
	 */
	public function replaceMacro($text, $macro, $value) {
		return preg_replace('/%' . $macro . '%/', $value, $text);
	}

	/**
	 * Sends warning notification emails with feedback requirement for an array of emails.
	 */
	private function sendFeedbackEmails($emails, $emailData, $class) {
		if (empty($emails)) {
			return true;
		}

		$template = 'feedback';
		if (!empty($class->customEmailTemplate)) {
			$template = 'custom/' . $class->internal . '_feedback';
		}

		$_subject = $emailData['notificationTitle'] . ' (' . __('Feedback Required') . ')';
		$email = $this->controller->initEmailFromComponent($emails, $_subject, 'notifications/' . $template, $emailData);
		return (bool) $email->send();
	}

	/**
	 * Sends warning notification emails for an array of emails.
	 */
	private function sendWarningEmails($emails, $emailData, $class) {
		if (empty($emails)) {
			return true;
		}

		$template = 'warning';
		if (!empty($class->customEmailTemplate)) {
			$template = 'custom/' . $class->internal . '_warning';
		}

		$_subject = $emailData['notificationTitle'];

		if (!$class->isReportType) {
			$email = $this->controller->initEmailFromComponent($emails, $_subject, 'notifications/' . $template, $emailData);
		}
		else {
			$email = $this->sendReportNotificationEmails($emails, $_subject, 'notifications/' . $template, $emailData);
		}

		
		return (bool) $email->send();
	}

	/**
	 * Sends warning custom notification emails for an array of emails.
	 */
	private function sendWarningCustomEmails($emails, $emailData, $class) {
		if (empty($emails)) {
			return true;
		}

		$template = 'warning_custom';
		if (!empty($class->customEmailTemplate)) {
			$template = 'custom/' . $class->internal . '_warning_custom';
		}

		$_subject = $emailData['notificationTitle'];

		if (!$class->isReportType) {
			$email = $this->controller->initEmailFromComponent($emails, $_subject, 'notifications/' . $template, $emailData);
		}
		else {
			$email = $this->sendReportNotificationEmails($emails, $_subject, 'notifications/' . $template, $emailData);
		}

		$ret = $email->send();
		return (bool) $ret;
	}

	private function sendReportNotificationEmails($emails, $_subject, $template, $emailData) {
		$email = $this->controller->initEmailFromComponent($emails, $_subject, '' . $template, $emailData);

		$attachments = $this->getAdvancedFiltersAttachments($emailData['notificationData']['NotificationSystem']);

		if (!empty($attachments)) {
			$email->attachments($attachments);
		}

		// debug($email->send());
		// exit;

		return $email;
	}

	private function getAdvancedFiltersAttachments($notificationSystem) {
		$this->controller->autoRender = false;
		$attachments = array();

		$filterId = $notificationSystem['advanced_filter_id'];
		$attachmentType = $notificationSystem['report_attachment_type'];

		$csvFileName = $this->AdvancedFiltersCron->exportDataResults($filterId);
		if(in_array($attachmentType, array(NotificationSystem::REPORT_ATTACHEMENT_BOTH, NotificationSystem::REPORT_ATTACHEMENT_CSV)) 
			&& !empty($csvFileName)
		) {

			App::uses('CsvView', 'CsvView.View');
			$csvView = new CsvView($this->controller);
			$csvView->set($this->controller->viewVars);
			$render = $csvView->render();

			// $this->controller->viewClass = 'CsvView.Csv';
			$attachments[$csvFileName . '.csv'] = array(
				'data' => $render,//$this->controller->render()->body(),
				'mimetype' => 'text/csv'
			);
			$this->controller->request->data = array();
			$this->controller->response->type('html');
		}
		
		$csvFileName = $this->AdvancedFiltersCron->exportDataResults($filterId, 'pdf');
		if(in_array($attachmentType, array(NotificationSystem::REPORT_ATTACHEMENT_BOTH, NotificationSystem::REPORT_ATTACHEMENT_PDF)) 
			&& !empty($csvFileName)
		) {
			$pdf = $this->Pdf->getPdfContent('/Elements/advancedFilters/pdf', 'pdf', $this->controller->viewVars);
			$attachments[$csvFileName . '.pdf'] = array(
				'data' => $pdf,
				'mimetype' => 'application/pdf'
			);
            $this->controller->request->data = array();
			$this->controller->response->type('html');
		}

		return $attachments;
	}

	/**
	 * Returns array of user data (emails, user ids...) from notification item.
	 */
	public function pullUserData($notificationId, $notificationObjectId) {
		$customEmails = $this->NotificationObject->NotificationSystem->NotificationEmail->find('list', array(
			'conditions' => array(
				'NotificationEmail.notification_system_item_id' => $notificationId
			),
			'fields' => array('id', 'email')
		));

		$users = $this->NotificationObject->NotificationSystem->NotificationSystemItemsUser->find('list', array(
			'conditions' => array(
				'NotificationSystemItemsUser.notification_system_item_id' => $notificationId
			),
			'fields' => array(
				'NotificationSystemItemsUser.user_id',
				'User.email'
			),
			'recursive' => 0
		));

		$scopes = $this->NotificationObject->NotificationSystem->NotificationSystemItemsScope->find('list', array(
			'conditions' => array(
				'NotificationSystemItemsScope.notification_system_item_id' => $notificationId
			),
			'fields' => array(
				'NotificationSystemItemsScope.user_id',
				'User.email'
			),
			'recursive' => 0
		));

		$customUsers = $this->NotificationObject->NotificationCustomUser->find('list', array(
			'conditions' => array(
				'NotificationCustomUser.notification_system_item_object_id' => $notificationObjectId
			),
			'fields' => array(
				'NotificationCustomUser.user_id',
				'User.email'
			),
			'recursive' => 0
		));

		$systemUsers = array_replace($users, $scopes, $customUsers);

		$userEmails = $allEmails = $userIds = array();
		foreach ($systemUsers as $userId => $userEmail) {
			$userEmails[] = $userEmail;
			$userIds[] = $userId;
		}

		foreach ($scopes as $userId => $userEmail) {
			$userEmails[] = $userEmail;
			$userIds[] = $userId;
		}

		$userEmails = array_unique($userEmails);
		$allEmails = array_unique(am($customEmails, $userEmails));
		$userIds = array_unique($userIds); 

		return array(
			'allEmails' => $allEmails,
			'customEmails' => $customEmails,
			'userEmails' => $userEmails,
			'userIds' => $userIds
		);
	}

	/* Default Notification Types */
	public function setupDefaultTypes() {
		$this->controller->loadModel('NotificationObject');
		$this->NotificationObject = $this->controller->NotificationObject;

		$data = $this->NotificationObject->find('all', array(
			'conditions' => array(
				'NotificationSystem.type' => array(NOTIFICATION_TYPE_DEFAULT, NOTIFICATION_TYPE_WARNING)
			),
			'fields' => array('id', 'model', 'foreign_key', 'log_count', 'created'),
			'contain' => array(
				'NotificationSystem' => array(
					'fields' => array('name', 'filename', 'type')
				),
				/*'NotificationLog' => array(
					'conditions' => array('is_new' => 1),
					'fields' => array('is_new', 'feedback_resolved', 'created', 'modified', 'DATE(created) as date'),
					'limit' => 1,
					'order' => array('created' => 'DESC')
				)*/
			)
		));

		// debug($data);
		// return false;

		$now = date('Y-m-d H:i:s');
		$triggeredItems = array();
		$this->defaultTypes = array();
		foreach ($data as $checkItem) {
			// dont send more than one warnings for an item a day
			/*if ($checkItem['NotificationObject']['log_count'] && isset($checkItem['NotificationLog'][0]) && $checkItem['NotificationLog'][0][0]['date'] == date('Y-m-d')) {
				continue;
			}*/

			$type = $checkItem['NotificationSystem']['type'];
			$class = $this->defaultTypes[] = $this->loadClass($checkItem['NotificationSystem']['filename'], $type);

			/*if ($class->triggerPeriod && $checkItem['NotificationObject']['log_count']) {
				$timestamp = strtotime('+' . $class->_periodFormatted, strtotime($checkItem['NotificationLog'][0]['created']));
				$triggerDate = date('Y-m-d H:i:s', $timestamp);
				
				if ($triggerDate > $now) {
					continue;
				}
			}*/

			/*$isTriggered = $this->compareItem($class, $checkItem['NotificationObject']['foreign_key']);
			if (!empty($isTriggered)) {
				$triggeredItems[] = $checkItem['NotificationObject']['id'];

				$this->controller->{$class->model}->id = $checkItem['NotificationObject']['foreign_key'];
				$this->controller->{$class->model}->addNoteToLog(__('Notification "%s" was triggered', $checkItem['NotificationSystem']['name']));
				$ret = $this->controller->{$class->model}->setSystemRecord($checkItem['NotificationObject']['foreign_key'], 2);
			}*/
		}
		// debug($this->defaultTypes);
		return $triggeredItems;
	}

	public function triggerHandler($settings, $model, $foreign_key, $additionalData = array()) {
		$ret = true;
		foreach ($this->defaultTypes as $c) {
			if ($c->defaultTypeSettings == $settings) {
				$triggered = $this->NotificationObject->find('first', array(
					'conditions' => array(
						'NotificationSystem.type' => array(NOTIFICATION_TYPE_DEFAULT, NOTIFICATION_TYPE_WARNING),
						'NotificationSystem.filename' => $c->filename,
						'NotificationObject.model' => $model,
						'NotificationObject.foreign_key' => $foreign_key,
						'NotificationObject.status' => NOTIFICATION_OBJECT_ENABLED,
					),
					'fields' => array('NotificationObject.id', 'NotificationObject.foreign_key', 'NotificationSystem.name', 'NotificationSystem.type'),
					'recursive' => 0
				));

				// debug($triggered);exit;

				if (!empty($triggered)) {
					$reminderData = array(
						'notification_system_item_object_id' => $triggered['NotificationObject']['id'],
						// 'is_new' => 0
					);

					$ret &= $this->setLogReminder($reminderData);
					$logId = $this->NotificationObject->NotificationLog->id;

					if ($triggered['NotificationSystem']['type'] == NOTIFICATION_TYPE_WARNING) {
						$isTriggered = $this->compareItem($c, $triggered['NotificationObject']['foreign_key']);
						if (!empty($isTriggered)) {
							$ret = $this->logNotification($model, $foreign_key, $triggered['NotificationSystem']['name']);
							return $this->sendNotificationsEmails($logId, $triggered['NotificationObject']['id'], $additionalData);
						}
					}
					else {
						return $this->sendNotificationsEmails($logId, $triggered['NotificationObject']['id'], $additionalData);
					}
				}
			}
		}

		return null;
	}

	/**
	 * Gets notification files options based on model for all types.
	 */
	public function getFileOptionsAllTypes($model = null) {
		$types = getNotificationTypesValues();

		$typeOptions = array();
		foreach ($types as $t) {
			$typeOptions[$t] = $this->getFileOptionsByType($model, $t);
		}

		return $typeOptions;
	}

	/**
	 * Gets notification files options based on model and type.
	 */
	public function getFileOptionsByType($model = null, $type = NOTIFICATION_TYPE_WARNING) {
		$findPath = "({$model}_|_).*\.php";
		if ($type == NOTIFICATION_TYPE_DEFAULT) {
			// $model = null;
			$findPath = '.*\.php';
		}

		// we allow report notifications only on sections where advanced filters are enabled
		if ($type == NOTIFICATION_TYPE_REPORT && $this->hasAdvancedFilters($model)) {
			$findPath = '.*\.php';
			
		}

		$dir = new Folder(NOTIFICATION_PATH . $type);
		$files = $dir->find($findPath);

		$options = array();
		foreach ($files as $file) {
			$class = $this->loadClass($file, $type);

			$options[$file] = array(
				'name' => $class->title,
				'value' => $file,
				'data-description' => $class->description,
				'data-default-type' => $class->isDefaultType ? "true" : "false",
				'data-deprecated' => $class->deprecated ? "{$class->deprecated}" : "false"
			);
			unset($class);
		}

		return $options;
	}

	public function hasAdvancedFilters($model) {
		$Model = ClassRegistry::init($model);
		return !empty($Model->advancedFilter);
	}

}
