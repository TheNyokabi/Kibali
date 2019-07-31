<?php
App::uses('File', 'Utility');
App::uses('ErambaCakeEmail', 'Network/Email');

class SettingsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array('Paginator', 'Ajax' => array(
		'actions' => array('residualRisk')
	));
	public $name = 'Settings';

	public function beforeFilter() {
		$this->Auth->allow('getLogo');

		if ($this->request->params['action'] == 'deleteCache') {
			Configure::write('Cache.disable', true);
		}

		parent::beforeFilter();

		$this->Security->unlockedActions = array('testMailConnection');

		if ($this->request->params['action'] == 'customLogo') {
			$this->Security->validatePost = true;
		}
	}

	public function index() {
		$this->set('title_for_layout', __('Settings' ));
		$this->set('subtitle_for_layout', __('System Settings'));

		$this->set('groupHelpText', $this->Setting->groupHelpText);
		$this->set('settingsGroups', $this->Setting->SettingGroup->getSettingGroups());
	}

	public function edit($groupSlug = null) {

		$settingGroup = $this->Setting->SettingGroup->getSettingGroup(array('SettingGroup.slug' => $groupSlug));

		if (empty($settingGroup)) {
			throw new NotFoundException();
		}

		$this->set('title_for_layout', $settingGroup['SettingGroup']['name']);
		if(isset($this->Setting->groupTitles[$groupSlug])){
			$this->set('subtitle_for_layout', $this->Setting->groupTitles[$groupSlug]);
		}


		$this->set('settingGroup', $settingGroup);
		$this->set('slug', $groupSlug);
		$this->set('notes', $this->Setting->notes);

		if(!empty($this->request->query)){
			$this->set('redirectUrl', json_encode($this->request->query));
		}
		if ($this->request->is(array('post', 'put'))) {
			$db = $this->Setting->getDataSource();
			$db->begin();

			$ret = true;
			$allowed = $this->createAllowedList($settingGroup);
			
			foreach ($this->request->data['Setting'] as $key => $value) {
				if(in_array($key, $allowed)){
					$ret &= $this->manageBeforeSaveCallbacks($key);

					$ret &= $this->Setting->updateVariable($key, $value);

					$ret &= $this->manageCallbacks($key, $value);
				}
			}

			if ($ret) {
				$db->commit();
				$this->Session->setFlash(__('Settings successfully saved.'), FLASH_OK);
				Cache::delete('settings', 'long');
				if(isset($this->request->data['Setting']['redirectUrl'])){
					$this->redirect(json_decode($this->request->data['Setting']['redirectUrl'], true));
				}
				else{
					$this->redirect(array('controller' => 'settings', 'action' => 'index'));
				}
			}
			else {
				$db->rollback();
				$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
			}
		}
	}

	private function createAllowedList($settingGroup = array()){
		$allowed = array();
		foreach ($settingGroup['Setting'] as $key => $value) {
			$allowed[] = $value['variable'];
		}

		return $allowed;
	}

	public function logs($type = "error") {
		$this->set('title_for_layout', 'Logs');
		$this->set('subtitle_for_layout', $type.' Log');
		$this->set('type', $type);

		$fileName = $type.".log";
		$file = new File( APP. "tmp/logs/" . $fileName);

		$logsLimit = 1000;

		$errorArr = array();

		if($file->exists()){
			if($type == "error"){
				$fileArr = preg_split("/((\r?\n)|(\r\n?))/", $file->read());
				if(!empty($fileArr)){
					foreach ($fileArr as $line) {
						if ($logsLimit <= 0) {
                            break;
                        }
						if(strpos($line, 'Error:')){
							$errorArr[] = explode('Error:', $line);
							$logsLimit--;
						}
					}
					$errorArr = array_reverse($errorArr);
				}
			}
			elseif($type == "email"){
				$fileArr = preg_split("/((\r?\n)|(\r\n?))/", trim($file->read()));

				if(!empty($fileArr)){
					foreach ($fileArr as $lineNum => $line) {
						if ($logsLimit <= 0) {
                            break;
                        }
						if(strpos($line, 'Email:')){
							$explode = (explode('Email:', $line));

							// read CakeEmail log entry for email that was sent
							if (!trim($explode[1])) {
								$whiteList = array('To:', 'Subject:');

								for ($i=$lineNum+1; $i < $lineNum+10; $i++) { 
									if (isset($fileArr[$i])) {
										foreach ($whiteList as $param) {
											if (strpos($fileArr[$i], $param) !== false) {
												$explode[1] .= $fileArr[$i] . PHP_EOL;
											}
										}
									}
								}

								$explode[1] = trim($explode[1]);
								if (!empty($explode[1])) {
									$errorArr[] = $explode;
									$logsLimit--;
								}
							}
							// read EmailDebug entry
							else {
								$errorArr[] = explode('Email:', $line);
								$logsLimit--;
							}
						}
					}
					$errorArr = array_reverse($errorArr);
				}
			}
		}
		else{
			$this->Session->setFlash(__('Log file does not exist or is not readable'), FLASH_ERROR);
			$this->redirect(array('controller' => 'settings', 'action' => 'index'));
		}

		$this->set('errorArr', $errorArr);
	}

	public function deleteLogs($type = "error") {
		$fileName = $type.".log";
		$file = new File( APP. "tmp/logs/" . $fileName);
		if($file->delete()){
			$this->Session->setFlash(__('Log file was deleted'), FLASH_OK);
		}
		else{
			$this->Session->setFlash(__('Log file can not be deleted'), FLASH_ERROR);
		}
		$this->redirect(array('controller' => 'settings', 'action' => 'index'));
	}

	 public function downloadLogs($type = 'error') {
	 	$this->autoRender = false;
        $allowedTypes = array('error', 'email');

        $fileName = $type.'.log';
        $file = new File( APP . 'tmp/logs/' . $fileName);

        if (!in_array($type, $allowedTypes) || !$file->exists()) {
            throw new NotFoundException();
        }

        $this->response->file(
			$file->path,
			array(
				'download' => true,
				'name' => $type . '_' . date('Y-m-d') . '.log'
			)
        );

        return $this->response;
    }

    /**
     * Action outputs a custom logo uploaded to be used in the app. Direct img routing is disabled.
     */
    public function getLogo() {
		$this->response->file('webroot' . CUSTOM_LOGO, array(
			'download' => true,
			'name' => basename(CUSTOM_LOGO)
		));

		return $this->response;
    }

	private function manageBeforeSaveCallbacks($key){
		$ret = true;

		if($key == 'RISK_APPETITE'){
			// $ret &= $this->triggerStatusRiskAppetite('before');
		}

		return $ret;
	}

	private function manageCallbacks($key){
		$ret = true;

		if($key == 'RISK_APPETITE'){
			$ret &= $this->triggerStatusRiskAppetite();
			// exit;
		}
		elseif($key == 'QUEUE_TRANSPORT_LIMIT'){
			$this->Setting->set([$key => $this->request->data['Setting'][$key]]);
			$ret = $this->Setting->validates(array('fieldList' => [$key]));
		}

		return $ret;
	}

	private function triggerStatusRiskAppetite($processType = null, $value = null) {
		$settings = array(
			'fn' => array('statusRiskAboveAppetite', true),
			'disableToggles' => array('default'),
			'customToggles' => array('Setting')
		);

		$ret = true;

		$this->loadModel('Risk');
		$this->loadModel('ThirdPartyRisk');
		$this->loadModel('BusinessContinuity');

		$ret &= $this->Risk->triggerStatus('riskAboveAppetite', null, $processType, $settings);
		$ret &= $this->ThirdPartyRisk->triggerStatus('riskAboveAppetite', null, $processType, $settings);
		$ret &= $this->BusinessContinuity->triggerStatus('riskAboveAppetite', null, $processType, $settings);

		return $ret;
	}

	public function testMailConnection($to = null){
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$emailSettings = $this->request->data;

		if(!empty($emailSettings) && Validation::email($to)){

			$config = $emailSettings['Setting'];
			$config = ErambaCakeEmail::buildErambaConfig($config, true);
			
			$email = new ErambaCakeEmail($config);
			$email->to($to);
			$email->subject('test');
			$email->template('test');

			try {
			    if ($email->send()) {
				echo json_encode(array('success' => true));
				}
				else{
					echo json_encode(array('success' => false));
				}
			} catch (Exception $e) {
			    echo json_encode(array('success' => false));
			}
		}
		else{
			echo json_encode(array('success' => false));
		}
	}

	public function resetDashboards(){
		$this->set('title_for_layout', 'Dashboard');
		$this->set('subtitle_for_layout', 'Reset Dashboards');

		$allowedResets = array(
			'AwarenessOvertimeGraph' => __('Awareness Overtime'),
			'ComplianceAuditOvertimeGraph' => __('Compliance Audit Overtime'),
			'ProjectOvertimeGraph' => __('Project Overtime'),
			'RiskOvertimeGraph' => __('Risk Overtime'),
			'ThirdPartyAuditOvertimeGraph' => __('Third Party Audit Overtime'),
			'ThirdPartyIncidentOvertimeGraph' => __('Third Party Incident Overtime'),
			'ThirdPartyOvertimeGraph' => __('Third Party Overtime'),
			'ThirdPartyRiskOvertimeGraph' => __('Third Party Risk Overtime')
		);

		$this->set('allowedResets', $allowedResets);

		$rules = array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'Select date'
			),
			'date' => array(
				'rule' => 'date',
				'message' => 'Invalid format'
			)
		);

		if ($this->request->is(array('post', 'put'))) {

			$this->Setting->validate['to'] = $rules;
			if (!isset($this->request->data['Setting']['from_beginning'])) {
				$this->Setting->validate['from'] = $rules;
			}

			if ($this->Setting->saveAll($this->request->data, array('validate' => 'only'))) {

				$this->Setting->query('SET autocommit = 0');
				$this->Setting->begin();
				$ret = true;

				foreach ($this->request->data['Model'] as $model => $value) {
					$conditions = array();
					$this->request->data['Setting']['to']++;
					$conditions[$model.'.created <='] = $this->request->data['Setting']['to'];
					if (isset($this->request->data['Setting']['from'])) {
						$conditions[$model.'.created >='] = $this->request->data['Setting']['from'];
					}

					if (isset($allowedResets[$model])) {
						$this->loadModel($model);
						$ret &= $this->{$model}->deleteAll($conditions);
					}
				}

				if ($ret) {
					$this->Setting->commit();
					$this->Session->setFlash(__('Dashboards successfully reseted.'), FLASH_OK);
					$this->request->data = array();
					//$this->redirect(array('controller' => 'settings', 'action' => 'index'));
				}
				else {
					$this->Setting->rollback();
					$this->Session->setFlash( __( 'Error while reseting data. Please try it again.' ), FLASH_ERROR );
				}
			}
		}
	}


	/**
	 * Upload a custom logo for this application instance.
	 */
	public function customLogo(){
		
		$this->set('title_for_layout', 'Logo');
		$this->set('subtitle_for_layout', 'Upload your logo');

		$setting = $this->Setting->find('first', array(
			'conditions' => array(
				'Setting.variable' => 'CUSTOM_LOGO'
			),
			'fields' => array(
				'Setting.id'
			)
		));

		if(isset($this->request->query['delete'])){
			$this->Setting->delete($setting['Setting']['id']);
			$this->Session->setFlash( __( 'Logo was successfully deleted.' ), FLASH_OK );
			$this->redirect( array( 'controller' => 'settings', 'action' => 'customLogo') );
		}

		if ($this->request->is(array('post', 'put'))) {
			unset($this->request->data['Setting']['id']);

			if(isset($this->request->data['Setting']['logo_file'])){

				if(empty($setting)){
					$this->request->data['Setting']['name'] = 'Custom Logo';
					$this->request->data['Setting']['variable'] = 'CUSTOM_LOGO';
				}
				else{
					$this->request->data['Setting']['id'] = $setting['Setting']['id'];
				}
				$this->Setting->set($this->request->data);
				$ret = $this->Setting->save();

				if($ret){
					$this->Session->setFlash( __( 'Logo was successfully changed.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'settings', 'action' => 'customLogo') );
				}
				else {
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			}
		}
	}

	public function deleteCache($folder = ''){
		$this->autoRender = false;

		$ret = $this->Setting->deleteCache($folder);
		if($ret){
			$this->Session->setFlash(__('Cache successfully deleted.'), FLASH_OK);
		}
		else{
			$this->Session->setFlash(__('Cache was deleted but some files might have remained.'), FLASH_WARNING);
		}

		$this->redirect(array('controller' => 'settings', 'action' => 'index'));
	}

	public function resetDatabase(){
		$this->set('title_for_layout', __('Reset Database'));
		//$this->set('subtitle_for_layout', '...');

		if ($this->request->is(array('post', 'put'))) {
			if ($this->request->data['Setting']['reset_db']) {
				// $ret = $this->Setting->runSchemaFile(APP . 'Vendor'. DS . 'settings' . DS . 'schema' . DS . 'reset.sql');
				
				$dataSource = $this->Setting->getDataSource();
				$dataSource->begin();

				$ret = $this->Setting->resetDatabase(true);

				/*if($this->request->data['Setting']['inset_data']){
					$ret &= $this->Setting->runSchemaFile(APP . 'Vendor'. DS . 'settings' . DS . 'schema' . DS . 'data.sql');
				}*/

				if($ret){
					$dataSource->commit();
					$this->Session->setFlash(__('Database succesfully reseted.'), FLASH_OK);
					$this->redirect(array('controller' => 'settings', 'action' => 'index'));
				}
				else{
					$dataSource->rollback();
					$this->Session->setFlash(__('Unable to reset database'), FLASH_ERROR);
				}
			}
			else {
				$this->Session->setFlash(__('Check the Reset database checkbox first.'), FLASH_WARNING);
			}
		}
	}

	public function systemHealth() {
		$this->set('title_for_layout', __('System Health'));
		// $this->set('subtitle_for_layout', __('subtitle'));

		$systemHealthComponent = $this->Components->load('SystemHealth');
		$systemHealthComponent->startup($this);

		$data = $systemHealthComponent->getData();

		$autoUpdateComponent = $this->Components->load('AutoUpdate');
		$autoUpdateComponent->initialize($this);

		$this->set('autoUpdatePending', $autoUpdateComponent->hasPending());
		// debug($data);
		$this->set('data', $data);
	}

	public function getTimeByTimezone($timezone = null) {
		if (empty($timezone)) {
			$timezone = null;
		}

		if (!empty($this->request->query['timezone'])) {
			$timezone = $this->request->query['timezone'];
		}

		$dateTime = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString('now', $timezone));;

		$this->set('dateTime', getEmptyValue($dateTime));
	}

	public function residualRisk() {
		$data = $this->Setting->find('first', array(
			'conditions' => array(
				'Setting.variable' => 'RISK_GRANULARITY'
			),
			'recursive' => -1
		));

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		$this->Ajax->processEdit($data['Setting']['id']);

		$this->set( 'title_for_layout', __( 'Residual Risk Settings' ) );
		$this->set('modalPadding', true);

		if ($this->request->is(array('post', 'put'))) {
			unset($this->request->data['Setting']['id']);

			$this->request->data['Setting']['id'] = $data['Setting']['id'];

			// $ret &= $this->Setting->updateVariable($key, $value);

			$this->Setting->set($this->request->data);
			$ret = $this->Setting->save();
			if($ret){
				$this->Ajax->success();
				$this->Session->setFlash( __( 'Risk configuration was successfully updated.' ), FLASH_OK );
			}
			else {
				$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
			}
		}
		else {
			$this->request->data = $data;
		}
	}
}