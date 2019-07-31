<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class SecurityPoliciesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = ['ImportTool.ImportTool', 'UserFields.UserField'];
	public $components = [
		'Search.Prg', 'AdvancedFilters', 'Paginator', 'RequestHandler', 'Pdf', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'SecurityPolicyDocumentType',
						'Comment' => ['fields' => ['id']],
						'Attachment' => ['fields' => ['id']],
						'AssetLabel',
						'RelatedDocuments' => [
							'fields' => ['id', 'index', 'security_policy_document_type_id', 'status', 'expired_reviews'],
							'SecurityPolicyDocumentType'
						],
						'SecurityService' => [
							'fields' => ['id', 'name']
						],
						'PolicyException' => [
							'fields' => ['id', 'title']
						],
						'Project' => [
							'fields' => ['id', 'title']
						],
						'Review' => [
							'User'
						],
						'AwarenessProgram',
						'ComplianceManagement'
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'ReviewsPlanner.Reviews',
		'ObjectStatus.ObjectStatus',
		'CustomFields.CustomFieldsMgt' => array('model' => 'SecurityPolicy'),
		'UserFields.UserFields' => [
			'fields' => ['Owner', 'Collaborator']
		]
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->Security->unlockedActions = array('ldapGroups');

		$this->title = __('Security Policies');
		$this->subTitle = __('You are able to define at high-level your Security Policies, Standards or Procedure. This is used across the system in multiple places such as: Risks, Control, Compliance Management and others.');
	}

	public function index() {
		$this->initOptions();

		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		return $this->Crud->execute();
	}

	public function _afterPaginate(CakeEvent $event) {
		$this->SecurityPolicy->ComplianceManagement->attachCompliancePackageData($event->subject->items);
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Security Policy.');

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create a Security Policy');
		$this->subTitle = __('Record and manage your program security incidents. Incidents can be linked to controls, assets and third parties in order to make it clear what components of the program have been affected.');

		$this->initOptions();

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->Crud->execute();
	}

	public function _afterSave(CakeEvent $event) {
		if ($event->subject->success) {
			$this->Flash->set(
				__('We have created two reviews for this policy, one with todays date, another with the date in the future where you plan to review this policy. Remember, if you used "Attachments" as content you must attach your policy documents in the review we created for today.'),
				[
					'key' => 'info',
					'params' => [
						'renderTimeout' => 1500
					]
				]
			);
		}
	}

	public function edit( $id = null ) {
		$id = (int) $id;

		$this->title = __('Edit a Security Policy');
		$this->initAddEditSubtitle();

		$this->initOptions(true);

		$this->Crud->on('beforeSave', array($this, '_beforeSave'));

		return $this->Crud->execute();
	}

	public function _beforeSave(CakeEvent $event) {
		$data = $this->SecurityPolicy->find('first', array(
			'conditions' => array(
				'SecurityPolicy.id' => $event->subject->id
			),
			'recursive' => 1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->request->data['SecurityPolicy']['version'] = $data['SecurityPolicy']['version'];
		$this->request->data['SecurityPolicy']['next_review_date'] = $data['SecurityPolicy']['next_review_date'];
		$this->request->data['SecurityPolicy']['url'] = $data['SecurityPolicy']['url'];
		$this->request->data['SecurityPolicy']['description'] = $data['SecurityPolicy']['description'];
		unset($this->SecurityPolicy->validate['next_review_date']);
	}


	public function trash() {
	    $this->set('title_for_layout', __('Security Policies (Trash)'));
	    $this->set('subtitle_for_layout', __('This is the list of security policies.'));

	    return $this->Crud->execute();
	}

	private function initOptions($disabledReviewFields = false) {
		$this->set('disabledReviewFields', $disabledReviewFields);

		$ldapConnectors = $this->SecurityPolicy->getConnectors();
		
		$this->set('ldapConnectors', $ldapConnectors);
		$this->set('tags', $this->SecurityPolicy->Tag->getTags('SecurityPolicy'));
	}

	private function initAddEditSubtitle() {
		$this->subTitle = false;
	}

	/**
	 * Create/get direct url for a document.
	 *
	 * @param  int $id Security Policy ID.
	 */
	public function getDirectLink($id) {
		$this->autoRender = false;
		$this->allowOnlyAjax();

		$data = $this->SecurityPolicy->find('first', array(
			'conditions' => array(
				'SecurityPolicy.id' => $id
			),
			'fields' => array('index', 'hash'),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if (empty($data['SecurityPolicy']['hash'])) {
			$rand = mt_rand(1, 999999) . $data['SecurityPolicy']['index'] . $id;
			$hash = sha1($rand);

			$this->SecurityPolicy->id = $id;
			if (!$this->SecurityPolicy->saveField('hash', $hash, false)) {
				echo json_encode(array(
					'success' => false,
					'message' => __('Error occured while creating a direct url. Please try again.')
				));
				return false;
			}
		}
		else {
			$hash = $data['SecurityPolicy']['hash'];
		}

		$url = Router::url(array('plugin' => null, 'controller' => 'policy', 'action' => 'documentDirect', $hash), true);

		echo json_encode(array(
			'success' => true,
			'title' => __('Direct link (%s)', $data['SecurityPolicy']['index']),
			'index' => $data['SecurityPolicy']['index'],
			'directLink' => $url
		));
	}

	/**
	 * Create a copy of a document.
	 *
	 * @param  int $id Security Policy ID.
	 */
	public function duplicate($id) {
		$this->autoRender = false;

		$document = $this->SecurityPolicy->find('first', array(
			'conditions' => array(
				'SecurityPolicy.id' => $id
			),
			'contain' => $this->UserFields->attachFieldsToArray(['Owner', 'Collaborator'], array(
				'Project' => array(
					'fields' => array('id')
				),
				'RelatedDocuments' => array(
					'fields' => array('id')
				),
				'SecurityPolicyLdapGroup' => array(
					'fields' => array('name')
				),
				'Tag' => array(
					'fields' => array('id', 'title')
				)
			))
		));

		if (empty($document)) {
			throw new NotFoundException();
		}

		//remove data we dont want to save
		unset($document['SecurityPolicy']['id']);
		unset($document['SecurityPolicy']['created']);
		unset($document['SecurityPolicy']['modified']);

		$document['SecurityPolicy']['Project'] = array();
		foreach ($document['Project'] as $item) {
			$document['SecurityPolicy']['Project'][] = $item['id'];
		}

		$document['SecurityPolicy']['Owner'] = array();
		foreach ($document['Owner'] as $item) {
			$document['SecurityPolicy']['Owner'][] = $item['id'];
		}

		$document['SecurityPolicy']['Collaborator'] = array();
		foreach ($document['Collaborator'] as $item) {
			$document['SecurityPolicy']['Collaborator'][] = $item['id'];
		}

		$document['SecurityPolicy']['RelatedDocuments'] = array();
		foreach ($document['RelatedDocuments'] as $item) {
			$document['SecurityPolicy']['RelatedDocuments'][] = $item['id'];
		}

		$document['SecurityPolicy']['SecurityPolicyLdapGroup'] = array();
		foreach ($document['SecurityPolicyLdapGroup'] as $item) {
			$document['SecurityPolicy']['SecurityPolicyLdapGroup'][] = $item['id'];
		}
		
		$tags = array();
		foreach ($document['Tag'] as $item) {
			$tags[] = $item['title'];
		}

		//we change the title to Copy of ...
		$document['SecurityPolicy']['index'] = __('Copy of %s', $document['SecurityPolicy']['index']);

		$this->SecurityPolicy->set($document['SecurityPolicy']);

		$dataSource = $this->SecurityPolicy->getDataSource();
		$dataSource->begin();

		$ret = $this->SecurityPolicy->save(null, false);

		$ret &= $this->SecurityPolicy->Tag->saveTagsArr($tags, 'SecurityPolicy', $this->SecurityPolicy->id);

		if ($ret) {
			$dataSource->commit();
			$this->Session->setFlash(__('Security Policy was successfully cloned.'), FLASH_OK);
		}
		else {
			$dataSource->rollback();
			$this->Session->setFlash(__('Error occured while saving the cloned data. Please try it again.'), FLASH_ERROR);
		}

		$this->redirect(array('controller' => 'securityPolicies', 'action' => 'index'));
	}

	/**
	 * Render a multiselect with LDAP groups.
	 *
	 * @param  int $id LDAP Connector ID.
	 */
	public function ldapGroups($id) {
		$this->allowOnlyAjax();

		$ldap = $this->Components->load('LdapConnectorsMgt');
		$ldap->initialize($this);

		$LdapConnector = $ldap->getConnector($id);
		$ldapConnection = $LdapConnector->connect();

		$groups = $LdapConnector->getGroupList();

		$this->set('groups', $groups);
		$this->set('ldapConnection', $ldapConnection);

		$this->render('/Elements/securityPolicies/ldapGroupsField');
	}

	/**
	 * Sends email notifications to users that has permission to this doc.
	 */
	public function sendNotifications($securityPolicyId) {
		$this->set('title_for_layout', __('Send Notifications'));
		$this->set('subtitle_for_layout', __('Send email to all people that has permission to this Policy.'));

		$data = $this->SecurityPolicy->find('first', array(
			'conditions' => array(
				'SecurityPolicy.id' => $securityPolicyId,
				'SecurityPolicy.permission' => SECURITY_POLICY_LOGGED
			),
			'contain' => array(
				'LdapConnector',
				'SecurityPolicyLdapGroup'
			)
		));

		if (empty($data)) {
			$this->actionUnavailable(array('controller' => 'securityPolicies', 'action' => 'index'));
		}

		if ($this->request->is('post') || $this->request->is('put')) {

			if (isset($this->request->data['SecurityPolicyNotification']['send']) && !empty($this->request->data['SecurityPolicyNotification']['send']) && is_array($this->request->data['SecurityPolicyNotification']['send']))  {

				$users = $this->request->data['SecurityPolicyNotification']['send'];

				$dataSource = $this->SecurityPolicy->getDataSource();
				$dataSource->begin();

				$ret = $this->SecurityPolicy->saveNotificationLog($securityPolicyId, $users);

				$ret &= $this->sendNotificationEmail($users, array(
					'policy' => $data,
					'portalUrl' => Router::url(array('plugin' => null, 'controller' => 'policy', 'action' => 'index'), true),
					'documentUrl' => Router::url(array('plugin' => null, 'controller' => 'policy', 'action' => 'index', $securityPolicyId), true)
				));

				if ($ret) {
					
					$dataSource->commit();

					$this->Session->setFlash(__('Notifications were successfully sent.'), FLASH_OK);
					$this->redirect(array('controller' => 'securityPolicies', 'action' => 'index'));
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash(__('Error occured. Please try it again.'), FLASH_ERROR);
				}
			}
			else {
				$this->Session->setFlash(__('Please choose one or more users to send notification.'), FLASH_ERROR);
			}
		}
		else {
			
		}
		
		$this->set('allowedUsers', $this->getAllowedUsers($data));
		$this->set('data', $data);
		$this->set('securityPolicyId', $securityPolicyId);
	}

	private function getAllowedUsers($data) {

		if (empty($data['SecurityPolicyLdapGroup'])) {
			throw new NotFoundException();
		}

		$connector = $data['LdapConnector'];

		$groups = array();
		foreach ($data['SecurityPolicyLdapGroup'] as $group) {
			$groups[] = $group['name'];
		}

		$ldap = $this->Components->load('LdapConnectorsMgt');
		$ldap->initialize($this);

		$LdapConnector = $ldap->getConnector($connector);
		$ldapConnection = $LdapConnector->connect();

		return $LdapConnector->getGrouppedEmailsList($groups);

		/*$LdapConnectorsMgt = $this->Components->load('LdapConnectorsMgt');
		$LdapConnectorsMgt->initialize($this);
		// $ldapConnection = $ldap->ldapConnect($connector['host'], $connector['port'], $connector['ldap_bind_dn'], $connector['ldap_bind_pw']);


		$this->loadModel('LdapConnectorAuthentication');
		$data = $this->LdapConnectorAuthentication->find('first', array(
			'recursive' => 0
		));

		$groupsUsers = $ldap->getUserEmailsByGroups($connector, $groups, $data['AuthPolicies']);
		
		return $groupsUsers;*/
	}

	private function sendNotificationEmail($email, $emailData) {
		if (empty($email)) {
			return true;
		}

		$_subject = __('Policy Document');
		$email = $this->initEmail($email, $_subject, 'security_policy_notification', $emailData);
		return $email->send();
	}
	
}
