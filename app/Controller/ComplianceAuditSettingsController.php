<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ComplianceAuditSettingsController extends SectionBaseController {
	use SectionCrudTrait;

	public $components = ['Paginator', 'AdvancedFilters',
		'Ajax' => [
			'actions' => ['setup'],
			'modules' => ['comments', 'records', 'attachments']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'className' => 'Filter',
				],
				'setup' => [
					'className' => 'AppEdit',
					'view' => 'setup',
				]
			]
		],
		'Visualisation.Visualisation'
	];
	public $uses = ['ComplianceAuditSetting', 'Notification'];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'setup']);

		parent::beforeFilter();

		$this->title = __('Third Party Audit Analysis');
		$this->subTitle = __('List of Compliance Package Items.');
	}

	public function index() {
		$response = $this->handleCrudAction('index');

		if ($response === false) {
			$this->redirect(array('controller' => 'complianceAudits', 'action' => 'index'));
		}
	}

	public function edit($id) {
		$setting = $this->ComplianceAuditSetting->find('first', array(
			'conditions' => array(
				'id' => $id
			),
			'fields' => array('compliance_audit_id', 'compliance_package_item_id'),
			'recursive' => -1
		));

		if (empty($setting)) {
			throw new NotFoundException();
		}

		$this->redirect(array(
			'controller' => 'complianceAuditSettings',
			'action' => 'setup',
			$setting['ComplianceAuditSetting']['compliance_audit_id'],
			$setting['ComplianceAuditSetting']['compliance_package_item_id']
		));
	}

	public function setup($auditId, $compliancePackageItemId) {
		$this->title = __('Audit Evidence Settings');

		$this->Ajax->settings['modules'] = ['comments', 'records', 'attachments'];

		$data = $this->ComplianceAuditSetting->readSettings($auditId, $compliancePackageItemId);

		$this->set('auditId', $auditId);
		$this->set('compliancePackageItemId', $compliancePackageItemId);

		$this->initOptions();

		$this->Ajax->setModalPadding();

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data['ComplianceAuditSetting']['compliance_audit_id'] = $auditId;
			$this->request->data['ComplianceAuditSetting']['compliance_package_item_id'] = $compliancePackageItemId;
		}

		return $this->Crud->execute(null, [$data['ComplianceAuditSetting']['id']]);
	}

	private function initOptions() {
		$this->set('statuses', getComplianceAuditSettingStatuses(null, null, true));
		$this->set('users', $this->getUsersList(false));

		$feedbackProfiles = $this->ComplianceAuditSetting->ComplianceAuditFeedbackProfile->find('list', array(
			'order' => array('ComplianceAuditFeedbackProfile.name' => 'ASC'),
			'recursive' => -1
		));
		$this->set('feedbackProfiles', $feedbackProfiles);
	}

	public function sendNotifications($auditId) {
		$this->set('title_for_layout', __('Send Audit Notifications'));
		$this->set('subtitle_for_layout', __('Before we send emails out to auditees you are able to see who will be sent what. If you think there is a mistake go back to the audit and edit its settings.'));

		$data = $this->ComplianceAuditSetting->find('all', array(
			'conditions' => array(
				'ComplianceAuditSetting.compliance_audit_id' => $auditId
			)
		));

		$hasAuditess = false;
		foreach ($data as $setting) {
			if (!empty($setting['Auditee'])) {
				$hasAuditess = true;
				break;
			}
		}

		if (empty($data) || !$hasAuditess) {
			$this->actionUnavailable(array('controller' => 'complianceAudits', 'action' => 'index'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			if (isset($this->request->data['ComplianceAuditNotification']['send']) && !empty($this->request->data['ComplianceAuditNotification']['send']) && is_array($this->request->data['ComplianceAuditNotification']['send']))  {

				$settingIds = $this->request->data['ComplianceAuditNotification']['send'];

				$dataSource = $this->ComplianceAuditSetting->getDataSource();
				$dataSource->begin();

				$ret = $this->ComplianceAuditSetting->saveNotification($settingIds);
				$ret &= $this->ComplianceAuditSetting->saveRecords($settingIds);
				$ret &= $this->notifyAuditees($settingIds, $auditId);

				if ($ret) {
					$dataSource->commit();

					$this->Session->setFlash(__('Audit notifications were successfully sent.'), FLASH_OK);
					$this->redirect(array('controller' => 'complianceAudits', 'action' => 'index'));
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash(__('Error occured. Please try it again.'), FLASH_ERROR);
				}
			}
		}
		else {

		}

		$this->set('data', $data);
		$this->set('auditId', $auditId);
	}

	private function notifyAuditees($settingIds = array(), $auditId) {
		$data = $this->ComplianceAuditSetting->find('all', array(
			'conditions' => array(
				'ComplianceAuditSetting.id' => $settingIds
			),
			'contain' => array(
				'CompliancePackageItem' => array(
					'fields' => array('name')
				),
				'Auditee' => array(
					'fields' => array('id', 'email')
				)
			)
		));

		$audit = $this->ComplianceAuditSetting->ComplianceAudit->find('first', array(
			'conditions' => array(
				'ComplianceAudit.id' => $auditId
			),
			'recursive' => 0
		));

		$auditeeEmails = $audit['ComplianceAudit']['auditee_emails'];
		$auditeeNotifications = $audit['ComplianceAudit']['auditee_notifications'];
		// debug($audit);
		// exit;

		$ret = true;

		$auditeeData = $settingsData = array();
		foreach ($data as $item) {
			if (!empty($item['Auditee'])) {
				$settingsData[$item['ComplianceAuditSetting']['id']] = $item['ComplianceAuditSetting'];

				foreach ($item['Auditee'] as $auditee) {
					if (!isset($auditeeData[$auditee['id']])) {
						$tmpData = array(
							'email' => $auditee['email'],
							'items' => array()
						);
					}
					else {
						$tmpData = $auditeeData[$auditee['id']];
					}

					$tmpData['items'][$item['ComplianceAuditSetting']['id']] = $item['CompliancePackageItem']['name'];
					$auditeeData[$auditee['id']] = $tmpData;
				}
			}
		}

		foreach ($auditeeData as $userId => $userData) {
			$emailItems = array();
			foreach ($userData['items'] as $settingId => $itemName) {
				$feedbackUrl = array(
					'plugin' => 'thirdPartyAudits', 
					'controller' => 'thirdPartyAudits', 
					'action' => 'analyze',
					$auditId
				);

				if ($auditeeEmails) {
					$emailItems[] = $itemName;
				}

				if ($auditeeNotifications) {
					$notificationRecord = array(
						'title' => __('Complete Audit Feedback on "%s"', $itemName),
						'model' => 'ComplianceAuditSetting',
						'user_id' => $userId,
						'url' => Router::url($feedbackUrl)
					);

					$ret = $this->Notification->setNotification($notificationRecord);
				}
			}

			$data = array(
				'items' => $emailItems,
				'url' => Router::url($feedbackUrl, true),
				'audit' => $audit
			);

			$ret &= $this->sendNotificationEmail($userData['email'], $data);
		}

		return $ret;
	}

	private function sendNotificationEmail($email, $data) {
		if (empty($email)) {
			return true;
		}
		
		$audit = $data['audit'];
		if (empty($audit['ComplianceAudit']['use_default_template'])) {
			$emailTemplate = 'customized_email';

			$notificationSystemMgt = $this->Components->load('NotificationSystemMgt');
			$notificationSystemMgt->initialize($this);
			$macros = $notificationSystemMgt->getMacros('ComplianceAudit');

			$model = 'ComplianceAudit';
			$subject = $notificationSystemMgt->parseMacros($audit['ComplianceAudit']['email_subject'], $audit, $macros, $model);
			$body = $notificationSystemMgt->parseMacros($audit['ComplianceAudit']['email_body'], $audit, $macros, $model);

			// custom macros
			$items = $this->getComplianceItemsHtml($data['items']);
			$body = $notificationSystemMgt->replaceMacro($body, COMPLIANCE_AUDIT_MACRO_AUDITCOMPLIANCELIST, $items);
			$body = $notificationSystemMgt->replaceMacro($body, COMPLIANCE_AUDIT_MACRO_LOGINERAMBAURL, $data['url']);
			
			$_subject = $subject;
			$emailData = array(
				'body' => $body
			);
		}
		else {
			$emailTemplate = 'feedback';

			$_subject = __('Compliance Audit Feedback Required');
			$emailData = $data;
		}

		$email = $this->initEmail($email, $_subject, 'compliance_audits/' . $emailTemplate, $emailData);
		return $email->send();
	}

	private function getComplianceItemsHtml($items = array()) {
		$html = false;

		if (!empty($items)) {
			$html = '<ul>';
			foreach ($items as $item) {
				$html .= '<li>' . $item . '</li>';
			}
			$html .= '</ul>';
		}

		return $html;
	}
}
