<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class SecurityIncidentStagesController extends SectionBaseController {
	use SectionCrudTrait;

	public $name = 'SecurityIncidentStages';
	public $uses = ['SecurityIncidentStage', 'SecurityIncidentStagesSecurityIncident', 'SecurityIncident'];
	public $components = [
		'Paginator', 'Search.Prg', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => [/*'add', 'edit', */'delete'],
			// 'modules' => ['comments', 'records', 'attachments', 'notifications']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
					]
				]
			]
		],
	];

	public function beforeFilter() {
		$this->Ajax->settings['modules'] = ['comments', 'records', 'attachments'];
		
		$this->Crud->enable(['index', 'add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Security Incident Stages');
		$this->subTitle = __('Describe the lifecycle of a security incident. The stages defined here will be applied to all incidents.');

		$this->Security->unlockedActions = array('pocessStage');
	}

	public function _afterItemSave(CakeEvent $event) {
		parent::_afterItemSave($event);

		if ($event->subject->success && $event->subject->created && $event->subject->id) {
			$this->mapStage($event->subject->id);
		}
	}

	public function add(){
		$this->title = __('Security Incident Stage');
		$this->subTitle = __('Create a Stage');

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->Crud->execute();
	}

	public function edit($id = null){
		$this->title = __('Security Incident Stage');
		$this->subTitle = __('Edit a Stage');

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->Crud->execute();
	}

	public function _afterSave(CakeEvent $event) {
		if (!empty($event->subject->success)) {
			$this->redirect(['action' => 'index']);
		}
	}

	public function delete($id = null){
		$this->title = __('Security Incident Stage');
		$this->subTitle = __('Delete a Stage');

		return $this->Crud->execute();
	}

	public function pocessStage($id = null, $status){
		$this->allowOnlyAjax();
		$this->autoRender = false;
		$ret = false;

		$item = $this->SecurityIncidentStagesSecurityIncident->getItem(array('SecurityIncidentStagesSecurityIncident.id' => $id));
		// debug($item);
		// exit;
		// check workflow status
		// EDITED - Ignore workflows for now
		/*
		if($workflowUrl !== true){
			echo json_encode(array('workflowUrl' => $workflowUrl));
			exit;
		}*/

		if(!empty($item) && in_array($status, array(0,1))){
			$dataSource = $this->SecurityIncidentStagesSecurityIncident->getDataSource();
			$dataSource->begin();

			$this->SecurityIncidentStagesSecurityIncident->id = $id;
			$ret = $this->SecurityIncidentStagesSecurityIncident->save(array(
				'status' => $status
			));

			if ($ret) {
				$dataSource->commit();

				$this->loadModel('SecurityIncident');
				$securityIncidentId = $item['SecurityIncidentStagesSecurityIncident']['security_incident_id'];
				$this->SecurityIncident->updateLifecycleIncomplete($securityIncidentId, $this->checkLifecycleIncomplete($securityIncidentId));

				$msg = $status?__('Stage marked as completed'):__('Stage marked as incompleted');
				$this->SecurityIncidentStagesSecurityIncident->id = $id;
				$this->SecurityIncidentStagesSecurityIncident->addNoteToLog($msg);
				$this->SecurityIncidentStagesSecurityIncident->setSystemRecord($id, 2);
			}
			else {
				$dataSource->rollback();
			}
		}

		$this->handleSystemRecords();
		echo json_encode(array('result' => $ret));

	}

	private function checkLifecycleIncomplete($securityIncidentId){
		$conditions = array(
			'SecurityIncidentStagesSecurityIncident.security_incident_id' => $securityIncidentId,
			'SecurityIncidentStagesSecurityIncident.status' => 0
		);
		$lifecycle = $this->SecurityIncidentStagesSecurityIncident->getItem($conditions);

		if(!empty($lifecycle)){
			//is incomplete
			return true;
		}
		else{
			//is complete
			return false;
		}
	}

	private function getItem(){
		$incident = $this->SecurityIncidentStagesSecurityIncident->find('first', array(
			'conditions' => array(
				'id' => $id
			)
		));
	}

	private function mapStage($stageId = null){

		if(!$stageId){
			return false;
		}

		$securityIncidents = $this->SecurityIncident->getSecurityIncidentsList();
		$data = array();
		$incidentIds = array();
		if(!empty($securityIncidents )){
			foreach ($securityIncidents as $key => $name) {
				$data[] = array(
					'security_incident_stage_id' => $stageId,
					'security_incident_id' => $key,
				);
				$incidentIds[] = $key;
			}

			if ($this->SecurityIncidentStagesSecurityIncident->saveAll($data)){
				//$ret &= $this->SecurityIncident->triggerStatus('lifecycleIncomplete', $incidentIds);
				foreach ($incidentIds as $incidentId) {
					$this->SecurityIncident->updateLifecycleIncomplete($incidentId, $this->checkLifecycleIncomplete($incidentId));
				}
				return true;
			}
			return false;
		}
		else{
			return true;
		}
	}
}
