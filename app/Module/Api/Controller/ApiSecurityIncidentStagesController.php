<?php
App::uses('ApiAppController', 'Api.Controller');

/**
 * @package       Api.Controller
 */
class ApiSecurityIncidentStagesController extends ApiAppController {
	public $uses = array('SecurityIncidentStage');
 
	public function index() {
		$data = $this->SecurityIncidentStage->find('all', array(
			'recursive' => -1
		));

		$this->set(array(
			'data' => $data,
			'_serialize' => array('data')
		));
	}
	 
	public function view($id) {
		$data = $this->SecurityIncidentStage->findById($id);
		$this->set(array(
			'data' => $data,
			'_serialize' => array('data')
		));
	}

}