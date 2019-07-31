<?php
App::uses('ApiAppController', 'Api.Controller');

/**
 * @package       Api.Controller
 */
class ApiSecurityIncidentsController extends ApiAppController {
	public $uses = array('SecurityIncident');
	protected $fieldList = array(
		'title', // string

		/**
		 * integer,
		 * lookup bootstrap.php function getSecurityIncidentTypes() for a list.
		 */
		'type',

		'description', // text
		'classifications', // string, where each another classification is delimited with a comma
		'open_date', // date
		'closure_date', // date

		/**
		 * Integer, you can use these constants:
		 * SECURITY_INCIDENT_ONGOING or SECURITY_INCIDENT_CLOSED.
		 */
		'security_incident_status_id',

		'asset_risk_id', // array, with list of related IDs to join.
		'third_party_risk_id', // array, with list of related IDs to join.
		'business_risk_id', // array, with list of related IDs to join.
		'user_id', // int
		'reporter', // string
		'victim', // string
		'security_service_id', // array, with list of related IDs to join.
		'asset_id', // array, with list of related IDs to join.
		'third_party_id', // array, with list of related IDs to join.
		'workflow_status', // integer, 1-4 one of them.
		'workflow_owner_id', // int, user id.
		'created', // date
		'modified' // date
	);

	public function beforeFilter() {
		parent::beforeFilter();
	}
	/**
	 * List data of multiple items.
	 * 
	 * Example:
	 * HTTP GET /api/security_incidents.json.
	 */ 
	public function index() {
		$data = $this->SecurityIncident->find('all', array(
			'recursive' => -1
		));

		$this->set(array(
			'data' => $data,
			'_serialize' => array('data')
		));
	}
 
	/**
	 * Add a new item using POST request having item data as json or x-www-form-urlencoded type.
	 * 
	 * Example:
	 * HTTP POST /api/security_incidents.json.
	 */
	public function add() {
		$data = $this->getDefaultRequestData();
		
		unset($data['id']);
		unset($data['SecurityIncident']['id']);
		$this->SecurityIncident->create();

		$validationErrors = null;
		$data = $this->setWorkflowDefaults($data);
		$this->beforeSave();
		if ($this->SecurityIncident->itemSave($data, array(
				'autocommit' => true,
				'fieldList' => $this->fieldList
			))) {
			$message = 'Created';
		}
		else {
			$message = 'Error';
			$validationErrors = $this->SecurityIncident->validationErrors;
		}

		$this->set(array(
			'message' => $message,
			'validationErrors' => $validationErrors,
			'_serialize' => array('message', 'validationErrors')
		));
	}
	
	/**
	 * View single item alone.
	 * 
	 * Example:
	 * HTTP GET /api/security_incidents/1.json.
	 * 
	 * @param int $id Item ID.
	 */
	public function view($id) {
		$data = $this->SecurityIncident->findById($id);
		$this->set(array(
			'data' => $data,
			'_serialize' => array('data')
		));
	}
 
	/**
	 * Edit a specified item.
	 * 
	 * Example:
	 * HTTP PUT /api/security_incidents/1.json.
	 *
	 * @param int $id Item ID.
	 */
	public function edit($id) {
		$data = $this->SecurityIncident->find( 'first', array(
			'conditions' => array(
				'SecurityIncident.id' => $id
			),
			'recursive' => 1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		$validationErrors = null;
		$this->SecurityIncident->id = $id;

		$data = $this->getDefaultRequestData();
		$this->beforeSave();
		if ($this->SecurityIncident->itemSave($data, array(
				'autocommit' => true,
				'fieldList' => $this->fieldList
			))) {
			$message = 'Saved';
		} else {
			$message = 'Error';
			$validationErrors = $this->SecurityIncident->validationErrors;
		}

		$this->set(array(
			'message' => $message,
			'validationErrors' => $validationErrors,
			'_serialize' => array('message', 'validationErrors')
		));
	}
	
	/**
	 * Delete an item.
	 *
	 * Example:
	 * HTTP DELETE /api/security_incidents/1.json.
	 *
	 * @param int $id Item ID.
	 */
	public function delete($id) {
		if ($this->SecurityIncident->delete($id)) {
			$message = 'Deleted';
		} else {
			$message = 'Error';
		}
		$this->set(array(
			'message' => $message,
			'_serialize' => array('message')
		));
	}

	private function beforeSave() {
		$this->disableModelConfigs(array(
			'SecurityService',
			'AssetRisk',
			'ThirdPartyRisk',
			'BusinessContinuity',
			'Asset',
			'ThirdParty'
		));
	}
}