<?php

class LdapConnectorsController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session', 'Paginator', 'Ajax' => array(
		'actions' => array(/*'add', 'edit', */'delete'),
		'modules' => array('comments', 'records', 'attachments')
	));
	public $uses = array('LdapConnector', 'LdapConnectorAuthentication');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Security->unlockedActions = array('testLdap');
	}
	
	public function index() {
		// lets do a quick ACL check on settings in general
		$this->checkSettingsAccess();

		$this->set('title_for_layout', __('LDAP Connectors'));
		$this->set('subtitle_for_layout', false);

		$this->loadModel('LdapConnectorAuthentication');
		$ldapAuth = $this->LdapConnectorAuthentication->find( 'first', array(
			'conditions' => array(
				'LdapConnectorAuthentication.id' => 1
			),
			'recursive' => -1
		));
		$this->set('ldapAuth', $ldapAuth);

		$conditions = array();
		$this->paginate = array(
			'conditions' => $conditions,
			'contain' => array(
				'Comment',
				'Attachment',
				'SecurityPolicy' => [
					'fields' => ['id']
				],
				'AwarenessProgram' => [
					'fields' => ['id']
				]
			),
			'order' => array('LdapConnector.id' => 'ASC'),
			'limit' => $this->getPageLimit()
		);

		$data = $this->paginate('LdapConnector');
		$this->set('data', $data);
	}

	public function delete($id = null) {
		// lets do a quick ACL check on settings in general
		$this->checkSettingsAccess();

		$this->set('title_for_layout', __('Ldap Connectors'));
		$this->set('subtitle_for_layout', __('Delete an Ldap Connector.'));

		$data = $this->LdapConnector->find('first', array(
			'conditions' => array(
				'LdapConnector.id' => $id
			),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->LdapConnector->delete($id)) {
				$this->Session->setFlash(__('Ldap Connector was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash($this->LdapConnector->getErrorMessage(), FLASH_ERROR);
			}

			$this->Ajax->success();
		}

		$this->request->data = $data;
	}

	public function add() {
		// lets do a quick ACL check on settings in general
		$this->checkSettingsAccess();

		$this->set('title_for_layout', __('Create an LDAP Connector'));
		$this->initAddEditSubtitle();
		// $this->initOptions();

		if ($this->request->is('post')) {
			unset($this->request->data['LdapConnector']['id']);

			$this->LdapConnector->set( $this->request->data );

			if ($this->LdapConnector->validates()) {
				$this->LdapConnector->query('SET autocommit = 0');
				$this->LdapConnector->begin();

				$ret = $this->LdapConnector->save();

				if ($ret) {
					$this->LdapConnector->commit();
					$this->Session->setFlash( __( 'LDAP Connector was successfully added.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'ldapConnectors', 'action' => 'index' ) );
				}
				else {
					$this->LdapConnector->rollback();
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			} else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}
	}

	public function edit( $id = null ) {
		// lets do a quick ACL check on settings in general
		$this->checkSettingsAccess();
		
		$id = (int) $id;

		if ( ! empty( $this->request->data ) ) {
			$id = (int) $this->request->data['LdapConnector']['id'];
		}

		$data = $this->LdapConnector->find( 'first', array(
			'conditions' => array(
				'LdapConnector.id' => $id
			)
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __('Edit an LDAP Connector') );
		$this->initAddEditSubtitle();
		// $this->initOptions();

		if ($this->request->is( 'post' ) || $this->request->is( 'put' )) {

			$this->LdapConnector->set($this->request->data);

			if ($this->LdapConnector->validates()) {
				$this->LdapConnector->query('SET autocommit = 0');
				$this->LdapConnector->begin();

				$ret = $this->LdapConnector->save();

				if ($ret) {
					$this->LdapConnector->commit();
					$this->Session->setFlash( __( 'LDAP Connector was successfully edited.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'ldapConnectors', 'action' => 'index' ) );
				}
				else {
					$this->LdapConnector->rollback();
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			} else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}
		else {
			$this->request->data = $data;
		}

		$this->render( 'add' );
	}

	private function initAddEditSubtitle() {
		$this->set('subtitle_for_layout', false);
	}

	public function authentication() {
		$this->set('title_for_layout', __('Authentication'));
		$this->set( 'subtitle_for_layout', __( 'Select which LDAP connector will be used for each of the authentication portals in eramba. Once you save your selection the LDAP will immediately activate and all authentications (except the one for the user admin) will be authenticated using the settings defined on the connector' ) );

		$data = $this->LdapConnectorAuthentication->find( 'first', array(
			'conditions' => array(
				'LdapConnectorAuthentication.id' => 1
			),
			'recursive' => -1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		if(!empty($this->request->query['redirect'])){
			$this->request->data['LdapConnectorAuthentication']['redirectUrl'] = $this->request->query['redirect'];
		}
		if(!empty($this->request->data['LdapConnectorAuthentication']['redirectUrl'])){
			$this->set('redirectUrl', $this->request->data['LdapConnectorAuthentication']['redirectUrl']);
		}

		if ($this->request->is( 'post' ) || $this->request->is( 'put' )) {
			$this->request->data['LdapConnectorAuthentication']['id'] = 1;

			$this->LdapConnectorAuthentication->set($this->request->data);

			if ($this->LdapConnectorAuthentication->validates()) {
				$dataSource = $this->LdapConnectorAuthentication->getDataSource();
				$dataSource->begin();

				$ret = $this->LdapConnectorAuthentication->save();
				if ($ret) {
					$dataSource->commit();
					$this->Session->setFlash(__( 'Authentication was successfully edited.' ), FLASH_OK);

					if(!empty($this->request->data['LdapConnectorAuthentication']['redirectUrl'])){
						$this->redirect($this->request->data['LdapConnectorAuthentication']['redirectUrl']);
					}
					else{
						$this->redirect(array('controller' => 'settings', 'action' => 'index'));
					}
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			}
			else {
				$this->Session->setFlash(__('One or more inputs you entered are invalid. Please try again.'), FLASH_ERROR);
			}
		}
		else {
			$this->request->data = $data;
		}

		$connectors = $this->LdapConnector->find('all', array(
			'recursive' => -1
		));

		$authenticators = $this->LdapConnector->find('list', array(
			'conditions' => array(
				'LdapConnector.type' => 'authenticator'
			),
			'fields' => array('id', 'name'),
			'recursive' => -1
		));

		$this->loadModel('OauthConnector');
		$oauthGoogleConnectors = $this->OauthConnector->find('list', array(
			'fields' => array('id', 'name'),
			'conditions' => array(
				'OauthConnector.provider' => OauthConnector::PROVIDER_GOOGLE,
				'OauthConnector.status' => OauthConnector::STATUS_ACTIVE
			),
			'recursive' => -1
		));

		// debug($authenticators);

		$this->set('authenticators', $authenticators);
		$this->set('oauthGoogleConnectors', $oauthGoogleConnectors);

		$this->set('general_auth_default', LdapConnectorAuthentication::GENERAL_AUTH_DEFAULT);
		$this->set('general_auth_ldap', LdapConnectorAuthentication::GENERAL_AUTH_LDAP);
		$this->set('general_auth_oauth_google', LdapConnectorAuthentication::GENERAL_AUTH_OAUTH_GOOGLE);
	}

	public function testLdap($testType = null) {
		$this->allowOnlyAjax();
		
		$data = $this->request->query['data']['LdapConnector'];

		$ldap = $this->Components->load('LdapConnectorsMgt');
		$ldap->initialize($this);

		$LdapConnector = $ldap->getConnector($data);
		$ldapConnection = $LdapConnector->connect();

		$limit = 200;
		$LdapConnector->setSizeLimit($limit);


		// $ldapConnection = $ldap->ldapConnect($data['host'], $data['port'], $data['ldap_bind_dn'], $data['ldap_bind_pw']);

		$options = am(array(
			'testType' => $testType
		), $this->request->params['named']);

		$results = $LdapConnector->getTest($options);

		// $results = $ldap->getData($data, $options);

		$this->set('ldapConnection', $ldapConnection);
		$this->set('results', $results);
		$this->set('limit', $limit);

		$this->render('/Elements/ldapConnectors/test');
	}

}
