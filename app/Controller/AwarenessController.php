<?php
App::uses('AwarenessProgram', 'Model');
App::uses('Hash', 'Utility');

class AwarenessController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session', 'AwarenessMgt' );
	public $uses = array('Awareness', 'AwarenessProgram', 'AwarenessTraining');
	public static $mapSortedActionSteps = array(
		'text-file' => 'text',
		'video-file' => 'video',
		'questionnaire-file' => 'questionnaire'
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->layout = 'awareness';
	}

	/**
	 * Custom authentication used for awareness portal.
	 */
	protected function _setupAuthentication() {
		parent::_setupAuthentication();

		// awareness portal doesnt require the same ACL auth
		$this->Auth->authorize = false;
		$this->Auth->allow('exportIgnoringUsers', 'exportReminders', 'exportUserTrainings', 'logout', 'downloadStepFile');

		AuthComponent::$sessionKey = 'Auth.Awareness';
		$this->Auth->loginAction = array('controller' => 'awareness', 'action' => 'login', 'admin' => false, 'plugin' => null);
		$this->Auth->loginRedirect = array('controller' => 'awareness', 'action' => 'index', 'admin' => false, 'plugin' => null);
		$this->Auth->logoutRedirect = array('controller' => 'awareness', 'action' => 'login', 'admin' => false, 'plugin' => null);

		$ldapAuth = $this->LdapConnectorAuthentication->getAuthData();
		if ($ldapAuth['LdapConnectorAuthentication']['auth_awareness']) {
			$this->_initLdapAuth($ldapAuth['AuthAwareness'], 'AwarenessUser', 'awareness');
		}
	}

	/**
	 * Skipping.
	 */
	protected function _currentAuthExtras() {
		// already enough
	}

	/**
	 * List of available programs.
	 */
	public function index() {
		$groups = $this->logged['ldapGroup'];

		$neededTrainings = $this->getNeededTrainings();
		$demoTrainings = $this->getDemoTrainings();

		$noTrainings = false;
		if (empty($neededTrainings) && empty($demoTrainings)) {
			$noTrainings = true;
		}

		$this->set('noTrainings', $noTrainings);
		$this->set('neededTrainings', $neededTrainings);
		$this->set('demoTrainings', $demoTrainings);

		$this->set('awarenessIndex', true);

		// $niceNames = json_decode( AWARENESS_GROUP_NICENAMES, true );
	}

	/**
	 * Checks which trainings needs to be done and returns them.
	 */
	private function getNeededTrainings() {
		$userGroups = $this->logged['ldapGroup'];
		$programs = $this->getStartedPrograms();

		$neededTrainings = array();
		foreach ($programs as $program) {
			if ($this->hasTrainingPermission($program)) {
				$neededTrainings[] = $program;
			}
		}

		return $neededTrainings;
	}

	/**
	 * Checks if a demo training is available for current user and returns a list.
	 */
	private function getDemoTrainings() {
		$demos = $this->AwarenessProgram->AwarenessProgramDemo->find('all', array(
			'conditions' => array(
				'AwarenessProgramDemo.uid' => $this->logged['login'],
				'AwarenessProgramDemo.completed' => 0
			),
			'contain' => array(
				'AwarenessProgram' => array(
					'AwarenessProgramLdapGroup'
				)
			)
		));

		$demoTrainings = array();
		foreach ($demos as $demo) {
			if ($this->checkTrainingGroupAccess($demo['AwarenessProgram'])) {
				$demoTrainings[] = $demo;
			}
		}

		return $demoTrainings;
	}

	/**
	 * Checks if current user has group access to a training.
	 */
	private function checkTrainingGroupAccess($program) {
		$userGroups = $this->logged['ldapGroup'];

		$programGroups = array();
		foreach ($program['AwarenessProgramLdapGroup'] as $group) {
			$programGroups[] = $group['name'];
		}

		if (array_intersect($programGroups, $userGroups)) {
			return true;
		}

		return false;
	}

	/**
	 * Permission complete check to access a program for current user.
	 */
	private function hasTrainingPermission($program) {
		if ($this->checkTrainingGroupAccess($program)) {
			if ($this->isTrainingNeeded($program['AwarenessProgram']['id'])) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get programs that are started/active.
	 */
	private function getStartedPrograms() {
		$data = $this->AwarenessProgram->find('all', array(
			'conditions' => array(
				'AwarenessProgram.status' => AWARENESS_PROGRAM_STARTED
			),
			'contain' => array(
				'AwarenessProgramLdapGroup'
			)
		));

		return $data;
	}

	/**
	 * Checks if demo training for a program exists for the current user.
	 */
	private function hasDemoTraining($awarenessProgramId, $redirect = true) {
		$hasDemo = $this->AwarenessProgram->AwarenessProgramDemo->find('count', array(
			'conditions' => array(
				'AwarenessProgramDemo.uid' => $this->logged['login'],
				'AwarenessProgramDemo.awareness_program_id' => $awarenessProgramId,
				'AwarenessProgramDemo.completed' => 0
			),
			'fields' => array('AwarenessProgramDemo.awareness_program_id')
		));

		return $hasDemo;
	}

	/**
	 * Checks if current training session is a demo.
	 */
	private function isDemo() {
		return $this->Session->check('AwarenessTraining.demo') && $this->Session->read('AwarenessTraining.demo');
	}

	/**
	 * Checks the session and returns validated program.
	 */
	private function getProgram() {
		$awarenessProgramId = $this->validateTrainingSession();

		/*$conds = array(
			'AwarenessProgram.id' => $awarenessProgramId,
			'AwarenessProgram.status' => AWARENESS_PROGRAM_STARTED
		);*/

		if ($this->isDemo()) {
			if ($this->hasDemoTraining($awarenessProgramId)) {
				//$conds['AwarenessProgram.status'] = AWARENESS_PROGRAM_STOPPED;
			}
			else {
				$this->Session->setFlash(__('Demo training is no longer unavailable.'), FLASH_ERROR);
				$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
			}
		}

		$program = $this->getProgramData($awarenessProgramId);
		/*$program = $this->AwarenessProgram->find('first', array(
			'conditions' => $this->getProgramFindConds($awarenessProgramId),
			'contain' => array(
				'AwarenessProgramLdapGroup'
			)
		));*/

		if (empty($program) || !$this->hasTrainingPermission($program)) {
			$this->Session->setFlash(__('This training is not available for you.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}

		// @deprecated checking custom validation rule here
		/*if (empty($program['AwarenessProgram']['video']) && empty($program['AwarenessProgram']['questionnaire'])) {
			$this->Session->setFlash(__('Training is missing video and questionnaire. Please try again.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}*/

		return $program;
	}

	/**
	 * Get find query conditions for the current awareness program instance.
	 */
	protected function getProgramFindConds($awarenessProgramId) {
		$conds = array(
			'AwarenessProgram.id' => $awarenessProgramId,
			'AwarenessProgram.status' => AWARENESS_PROGRAM_STARTED
		);

		if ($this->isDemo() && $this->hasDemoTraining($awarenessProgramId)) {
			$conds['AwarenessProgram.status'] = AWARENESS_PROGRAM_STOPPED;
		}

		return $conds;
	}

	/**
	 * Get program data from the database.
	 */
	protected function getProgramData($awarenessProgramId) {
		return $this->AwarenessProgram->find('first', array(
			'conditions' => $this->getProgramFindConds($awarenessProgramId),
			'contain' => array(
				'AwarenessProgramLdapGroup'
			)
		));
	}

	/**
	 * Checks if session exists and returns program ID.
	 */
	private function validateTrainingSession() {
		if ($this->Session->check('AwarenessTraining') && $this->Session->check('AwarenessTraining.id')) {
			return $this->Session->read('AwarenessTraining.id');
		}
		else {
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
			exit;
		}
	}

	/**
	 * Starts a training session, check video and csv and redirect user correctly.
	 */
	public function training($awarenessProgramId, $demo = false) {
		$conds = array(
			'AwarenessProgram.id' => $awarenessProgramId,
			'AwarenessProgram.status' => AWARENESS_PROGRAM_STARTED
		);

		$program = $this->AwarenessProgram->find('first', array(
			'conditions' => $conds,
			'contain' => array(
				'AwarenessProgramLdapGroup'
			)
		));

		if (empty($program)) {
			$this->Session->setFlash(__('Training is unavailable.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}

		$this->initProgram($program, false);
	}

	/**
	 * Starts a demo session and initialize demo program.
	 * 
	 * @param  int $demoId AwarenessProgramDemo ID.
	 */
	public function demo($demoId) {
		$demo = $this->AwarenessProgram->AwarenessProgramDemo->find('first', array(
			'conditions' => array(
				'AwarenessProgramDemo.uid' => $this->logged['login'],
				'AwarenessProgramDemo.completed' => 0,
				'AwarenessProgramDemo.id' => $demoId
			),
			'fields' => array('AwarenessProgramDemo.awareness_program_id')
		));

		if (empty($demo)) {
			$this->Session->setFlash(__('Demo training is no longer available.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}

		$program = $this->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $demo['AwarenessProgramDemo']['awareness_program_id']
			),
			'contain' => array(
				'AwarenessProgramLdapGroup'
			)
		));

		if (empty($program)) {
			$this->Session->setFlash(__('This Awareness Program is not available at the moment. Please try again later.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}

		$this->initProgram($program, $demoId);
	}

	/**
	 * Initialize a training, reset session, check permissions.
	 */
	private function initProgram($program, $demo = false) {
		$this->Session->delete('AwarenessTraining');

		//program cannot be missing both video and questionnaire.
		//@deprecated this check
		/*if (empty($program['AwarenessProgram']['video']) && empty($program['AwarenessProgram']['questionnaire'])) {
			$this->Session->setFlash(__('Training is missing video and questionnaire. Please try again.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}*/

		$this->Session->write('AwarenessTraining.id', $program['AwarenessProgram']['id']);
		$this->Session->write('AwarenessTraining.demo', $demo);

		if (empty($program) || !$this->hasTrainingPermission($program)) {
			$this->Session->setFlash(__('This training is not available for you.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}

		//redirect depending on what the program contains.
		/*if ($this->programHasVideo($program)) {
			$this->redirect(array('controller' => 'awareness', 'action' => 'video'));
		}
		elseif ($this->programHasTextFile($program)) {
			$this->Session->write('AwarenessTraining.videoCompleted', true);
			$this->redirect(array('controller' => 'awareness', 'action' => 'text'));
		}
		elseif ($this->programHasQuestionnaire($program)) {
			$this->Session->write('AwarenessTraining.textCompleted', true);
			$this->redirect(array('controller' => 'awareness', 'action' => 'questionnaire'));
		}
		else {
			$this->Session->setFlash(__('Error occured. Please try again.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}*/

		return $this->startAwareness();
	}

	protected function startAwareness() {
		return $this->redirectGateway(false);
	}

	/**
	 * Mark a step as completed. Continue with processing the next step determined by order.
	 */
	protected function stepCompleted($type, $saveData = array()) {
		$stepsCompleted = array();
		if ($this->Session->check('AwarenessTraining.stepsCompleted')) {
			$stepsCompleted = $this->Session->read('AwarenessTraining.stepsCompleted');
		}

		$stepsCompleted[] = $type;
		$this->Session->write('AwarenessTraining.stepsCompleted', $stepsCompleted);

		if (!empty($saveData)) {
			$this->Session->write('AwarenessTraining.saveData', $saveData);
		}

		return $this->redirectGateway($stepsCompleted);
	}

	/**
	 * Redirect the user based on steps order settings made in the program, comparing with current user's session.
	 */
	protected function redirectGateway($stepsCompleted) {
		$awarenessProgramId = $this->validateTrainingSession();
		$steps = $this->AwarenessProgram->getUploadsSorting($awarenessProgramId);

		$nextStep = 0;
		if (!empty($stepsCompleted)) {
			$currentStep = 0;
			foreach ($steps as $sortKey => $sortData) {
				if (in_array($sortData['type'], $stepsCompleted)) {
					$currentStep = max($currentStep, $sortKey);
				}
			}

			$nextStep = $currentStep + 1;
		}

		if (isset($steps[$nextStep])) {
			$program = $this->getProgramData($awarenessProgramId);

			$nextType = $steps[$nextStep]['type'];
			$action = self::$mapSortedActionSteps[$nextType];

			$configuredFn = 'isConfigured' . $action;
			$configuredStep = $this->{$configuredFn}($program);
			if (empty($configuredStep)) {
				return $this->stepCompleted($nextType);
			}

			$validateFn = 'isValid' . $action;
			$validateStep = $this->{$validateFn}($program);
			if (!$validateStep) {
				$this->Session->setFlash(__('Error occured processing the next step. Please try again.'), FLASH_ERROR);
				return $this->redirect(array('controller' => 'awareness', 'action' => 'index'));
			}

			return $this->redirect(array('controller' => 'awareness', 'action' => $action));
		}

		if (!$this->saveTraining()) {
			$this->Session->setFlash(__('Error occured while saving your training data. Please try it again.'), FLASH_ERROR);
			return $this->retryLastStep();
		}

		return $this->redirect(array('controller' => 'awareness', 'action' => 'results'));
	}

	/**
	 * Retry last step in case something failed, meaning set up session and redirect.
	 */
	protected function retryLastStep() {
		$stepsCompleted = $this->Session->read('AwarenessTraining.stepsCompleted');
		$stepsCompleted = array_pop($stepsCompleted);
		$this->Session->write('AwarenessTraining.stepsCompleted', $stepsCompleted);

		return $this->redirectGateway($stepsCompleted);
	}

	private function isConfiguredVideo($program) {
		return !empty($program['AwarenessProgram']['video']);
	}

	private function isConfiguredText($program) {
		return !empty($program['AwarenessProgram']['text_file']);
	}

	private function isConfiguredQuestionnaire($program) {
		return !empty($program['AwarenessProgram']['questionnaire']);
	}

	private function isValidVideo($program) {
		return $this->programHasVideo($program);
	}

	private function isValidText($program) {
		return $this->programHasTextFile($program);
	}

	private function isValidQuestionnaire($program) {
		return $this->programHasQuestionnaire($program);
	}

	private function programHasVideo($program) {
		return !empty($program['AwarenessProgram']['video']) && file_exists(AWARENESS_PATH . 'videos' . DS . $program['AwarenessProgram']['video']);
	}

	private function programHasTextFile($program) {
		return !empty($program['AwarenessProgram']['text_file']) && file_exists(AWARENESS_PATH . 'text_files' . DS . $program['AwarenessProgram']['text_file']);
	}

	private function programHasQuestionnaire($program) {
		return !empty($program['AwarenessProgram']['questionnaire']) && file_exists(AWARENESS_PATH . 'questionnaires' . DS . $program['AwarenessProgram']['questionnaire']);
	}

	public function video() {
		$program = $this->getProgram();

		// if (!$this->programHasVideo($program)) {
		// 	$this->Session->setFlash(__('Error occured. Please try again.'), FLASH_ERROR);
		// 	$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		// }

		if ($this->request->is('post') || $this->request->is('put')) {
			if (!empty($this->request->data['AwarenessTrainingVideo']['video_seen'])) {
				// $this->Session->write('AwarenessTraining.videoCompleted', true);
				// return $this->redirect(array('controller' => 'awareness', 'action' => 'text'));

				return $this->stepCompleted('video-file');

				// if program is missing text then save a training and move quertionnaire
				if (!$this->programHasTextFile($program) && !$this->programHasQuestionnaire($program)) {
					if ($this->saveTraining()) {
						$this->redirect(array('controller' => 'awareness', 'action' => 'questionnaire'));
					}
					else {
						$this->Session->setFlash(__('Error occured while saving the data. Please try it again.'), FLASH_ERROR);
						$this->redirect(array('controller' => 'awareness', 'action' => 'video'));
					}
				}
				else {
					$this->Session->write('AwarenessTraining.videoCompleted', true);
					$this->redirect(array('controller' => 'awareness', 'action' => 'text'));
				}
			}
		}

		$videoUrl = AWARENESS_VIDEO_PATH_HTML . $program['AwarenessProgram']['video'];
		$this->set('videoUrl', $videoUrl);

		$this->set('program', $program);
		$this->set('trainingAllowed', true);
	}

	public function downloadStepFile($programId, $fileType) {
		$types = AwarenessProgram::$uploads_sort_json;
		$allowedFiles = Hash::extract($types, '{n}.field');

		$this->autoRender = true;
		$programId = (int) $programId;

		$program = $this->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $programId
			),
			'contain' => array()
		));

		if (empty($program) || !in_array($fileType, $allowedFiles) || empty($program['AwarenessProgram'][$fileType])) {
			throw new NotFoundException();
		}

		$fileUrl = $this->getStepFileUrl($fileType, $program);
		$file = new File($fileUrl);

		if (!$file->exists()) {
			throw new NotFoundException();
		}

		$this->response->file($file->path, array(
			'download' => true,
			'name' => $program['AwarenessProgram'][$fileType]
		));

		return $this->response;
	}

	private function getStepFileUrl($fileType, $program) {
		$types = AwarenessProgram::$uploads_sort_json;
		$paths = Hash::combine($types, '{n}.field', '{n}.path');

		return $paths[$fileType] . $program['AwarenessProgram'][$fileType];
	}

	public function text() {
		$program = $this->getProgram();

		// if ($this->programHasVideo($program) && !$this->Session->check('AwarenessTraining.videoCompleted')) {
		// 	$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		// }

		// if (!$this->programHasTextFile($program)) {
		// 	$this->Session->setFlash(__('Error occured. Please try again.'), FLASH_ERROR);
		// 	$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		// }

		if ($this->request->is('post') || $this->request->is('put')) {
			if (!empty($this->request->data['AwarenessTrainingTextFile']['text_seen'])) {
				return $this->stepCompleted('text-file');

				$this->Session->write('AwarenessTraining.textCompleted', true);
				return $this->redirect(array('controller' => 'awareness', 'action' => 'questionnaire'));

				// if program is missing questionnaire then save a training and move directly to results
				if (!$this->programHasQuestionnaire($program)) {
					if ($this->saveTraining()) {
						$this->redirect(array('controller' => 'awareness', 'action' => 'results'));
					}
					else {
						$this->Session->setFlash(__('Error occured while saving the data. Please try it again.'), FLASH_ERROR);
						$this->redirect(array('controller' => 'awareness', 'action' => 'video'));
					}
				}
				else {
					$this->Session->write('AwarenessTraining.textCompleted', true);
					$this->redirect(array('controller' => 'awareness', 'action' => 'questionnaire'));
				}
			}
		}

		$textFileUrl = AWARENESS_TEXT_FILE_PATH_HTML . $program['AwarenessProgram']['text_file'];
		$this->set('textFileUrl', $textFileUrl);

		$this->set('program', $program);
		$this->set('trainingAllowed', true);
		$this->set('noAwarenessContainer', true);
	}

	public function viewText($programId) {
		Configure::write('debug', 0);

		$programId = (int) $programId;

		$program = $this->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $programId
			),
			'contain' => array()
		));

		if (empty($program) || empty($program['AwarenessProgram']['text_file'])) {
			throw new NotFoundException();
		}

		$textFileUrl = AWARENESS_TEXT_FILE_PATH . $program['AwarenessProgram']['text_file'];
		$textFile = new File($textFileUrl);

		if (!$textFile->exists()) {
			throw new NotFoundException();
		}

		$this->set('content', $textFile->read());
		$this->set('program', $program);
		$this->layout = 'clean';
	}


	/**
	 * Checks if training was already completed by the current user and is not needed again.
	 */
	private function isTrainingNeeded($awarenessProgramId) {
		if ($this->isDemo()) {
			return true;
		}

		$data = $this->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $awarenessProgramId
			),
			'fields' => array('recurrence'),
			'recursive' => -1
		));

		$days = $data['AwarenessProgram']['recurrence'];

		if (!$days) {
			return true;
		}

		$latestRecurrence = $this->AwarenessMgt->getLastRecurrence($awarenessProgramId);

		$count = $this->AwarenessProgram->AwarenessTraining->find( 'count', array(
			'conditions' => array(
				'AwarenessTraining.awareness_user_id' => $this->logged['id'],
				'AwarenessTraining.awareness_program_id' => $awarenessProgramId,
				'AwarenessTraining.awareness_program_recurrence_id' => $latestRecurrence,
				'AwarenessTraining.demo' => 0
			),
			'recursive' => -1
		) );

		if ($count) {
			return false;
		}

		return true;
	}

	public function questionnaire() {
		$program = $this->getProgram();

		// if ($this->programHasTextFile($program) && !$this->Session->check('AwarenessTraining.textCompleted')) {
		// 	$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		// }

		$questionnaire = $this->getQuestionnaire($program['AwarenessProgram']['questionnaire']);
		// if (empty($questionnaire) && $this->programHasVideo($program)) {
		// 	if ($this->saveTraining()) {
		// 		$this->redirect(array('controller' => 'awareness', 'action' => 'results'));
		// 	}
		// 	else {
		// 		$this->Session->setFlash(__('Error occured while saving the data. Please try it again.'), FLASH_ERROR);
		// 		$this->redirect(array('controller' => 'awareness', 'action' => 'video'));
		// 	}
		// }
		// elseif (empty($questionnaire)) {
		// 	$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		// }

		$this->set('questionnaire', $questionnaire);
		$this->set('program', $program);

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
			if ( $this->validateQuestionnaire() ) {
				$answerStats = $this->getAnswerStats( $questionnaire );
				$saveData = array(
					'answers_json' => $this->getAnswersJson(),
					'correct' => $answerStats['correct'],
					'wrong' => $answerStats['wrong']
				);

				return $this->stepCompleted('questionnaire-file', $saveData);

				if ($this->saveTraining($saveData)) {
					// return $this->stepCompleted('questionnaire-file', $saveData);

					$this->redirect(array('controller' => 'awareness', 'action' => 'results'));
				}
				else {
					$this->Session->setFlash(__('Error occured while saving the data. Please try it again.'), FLASH_ERROR);
					$this->redirect(array('controller' => 'awareness', 'action' => 'questionnaire'));
				}
			}
			else {
				$this->Session->setFlash( __( 'Questionnaire is incomplete. You have to fill in all answers.' ), FLASH_ERROR );
			}
		}
	}

	/**
	 * Saves a training for the current user about current program.
	 */
	private function saveTraining($saveData = array()) {
		$awarenessProgramId = $this->validateTrainingSession();

		if ($this->Session->check('AwarenessTraining.saveData')) {
			$saveData = $this->Session->read('AwarenessTraining.saveData');
		}

		if ($this->isDemo()) {
			$this->AwarenessProgram->AwarenessProgramDemo->id = $this->Session->read('AwarenessTraining.demo');
			$this->AwarenessProgram->AwarenessProgramDemo->set(array('completed' => 1), false);
			if (!$this->AwarenessProgram->AwarenessProgramDemo->save()) {
				$this->Session->setFlash(__('Error occured while completing the demo. Please try to run the demo again.'), FLASH_ERROR);
				$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
			}

			$this->AwarenessProgram->quickLogSave($awarenessProgramId, 2, __('Demo training completed'), ADMIN_ID);

			$saveData['demo'] = 1;
		}
		else {
			$this->AwarenessProgram->quickLogSave($awarenessProgramId, 2, __('A user "%s" completed the training', $this->logged['login']), ADMIN_ID);
		}

		$this->handleSystemRecords(true);

		$latestRecurrence = $this->AwarenessMgt->getLastRecurrence($awarenessProgramId);

		$saveData['awareness_user_id'] = $this->logged['id'];
		$saveData['awareness_program_id'] = $awarenessProgramId;
		$saveData['awareness_program_recurrence_id'] = $latestRecurrence;
		
		$this->AwarenessProgram->AwarenessTraining->set($saveData);
		if ($this->AwarenessProgram->AwarenessTraining->save()) {
			$this->Session->write('AwarenessTraining.awarenessTrainingId', $this->AwarenessProgram->AwarenessTraining->id);

			return true;
		}

		return false;
	}

	/**
	 * Thank you page after the training.
	 */
	public function results($awarenessTrainingId=null) {
		if (!$this->Session->check('AwarenessTraining.awarenessTrainingId')) {
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}

		$awarenessTrainingId = $this->Session->read('AwarenessTraining.awarenessTrainingId');
		$training = $this->AwarenessTraining->find('first', array(
			'conditions' => array(
				'AwarenessTraining.id' => $awarenessTrainingId,
				'AwarenessTraining.awareness_user_id' => $this->logged['id']
			),
			'contain' => array(
				'AwarenessProgram' => array(
					'AwarenessProgramLdapGroup'
				)
			)
		));

		if (empty($training)) {
			$this->redirect(array('controller' => 'awareness', 'action' => 'index'));
		}

		$questionnaire = $this->getQuestionnaire($training['AwarenessProgram']['questionnaire']);
		if (empty($questionnaire)) {
			$this->set('questionnaire', false);
		}
		else {
			$this->set( 'userAnswers', json_decode( $training['AwarenessTraining']['answers_json'], true ) );
			$this->set( 'questionnaire', $questionnaire );
		}

		$this->set( 'training', $training );
	}

	private function getQuestionnaire($questionnaire) {
		if (empty($questionnaire)) {
			return false;
		}

		$tmp_name = AWARENESS_PATH . 'questionnaires' . DS . $questionnaire;
		if (!file_exists($tmp_name)) {
			return false;
		}

		if ( ( $handle = fopen( $tmp_name, 'r' ) ) !== FALSE ) {
			$questionnaire = array();
			while ( ( $data = fgetcsv( $handle, 0, ',' ) ) !== FALSE ) {
				if (empty($data[0]) || !isset($data[1]) || empty($data[2])) {
					continue;
				}
				$tmp = array(
					'question' => $data[0],
					'description' => $data[1],
					'correctAnswer' => $data[2]
				);
				$answers = array();
				for ( $i = 3; $i < count( $data ); $i++ ) {
					$answer = trim($data[$i]);
					if ($answer != '') {
						$answers[] = $answer;
					}
				}
				
				//check if correct answer exists
				if (count($answers) < $data[2]) {
					continue;
				}

				$tmp['answers'] = $answers;
				$questionnaire[] = $tmp;
			}

			fclose( $handle );

			return $questionnaire;
		}

		return false;
	}

	private function getAnswersJson() {
		$answers = array();
		foreach ( $this->request->data['Awareness'] as $answer ) {
			$answers[] = (int) $answer['answer'] + 1;
		}

		return json_encode( $answers );
	}

	private function getAnswerStats( $questionnaire ) {
		$status = array(
			'correct' => 0,
			'wrong' => 0
		);

		foreach ( $questionnaire as $key => $question ) {
			if ( (int) $question['correctAnswer'] == ( ( (int) $this->request->data['Awareness'][ $key ]['answer'] ) + 1 ) ) {
				$status['correct']++;
			}
			else {
				$status['wrong']++;
			}
		}

		return $status;
	}

	/**
	 * Validates if all questionnaire answers are chosen.
	 */
	private function validateQuestionnaire() {
		foreach ( $this->request->data['Awareness'] as $answer ) {
			if ( $answer['answer'] === '' ) {
				$this->Awareness->invalidate( 'answers' );
				$this->set( 'answers_error', __( 'You need to fill in all answers.' ) );
				return false;
			}
		}

		return true;
	}

	/**
	 * Awareness module LDAP login.
	 */
	public function login() {
		
		//
		// If portal is disabled
		if (empty($this->ldapAuth['LdapConnectorAuthentication']['auth_awareness'])) {
			echo __('Awareness portal is disabled. Please go to Eramba -> Settings -> Authentication to enable it.');
			exit;
		}
		//

		$this->layout = 'login';
		$this->set('title_for_layout', __('Awareness Login'));

		if ($this->logged != null) {
			$this->redirect($this->Auth->loginRedirect);
		}

		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$userId = $this->Auth->user('id');
				return $this->redirect($this->Auth->redirect());
			}
			else {
				$errorMsg = __('Email or password was incorrect.');

				$ldapErr = $this->getLdapLoginError();
				if (!empty($ldapErr)) {
					$errorMsg = $ldapErr;
				}

				$this->Session->setFlash($errorMsg, FLASH_ERROR);
			}
		}
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}
}
