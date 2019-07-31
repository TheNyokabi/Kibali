<?php
App::uses('Component', 'Controller');
App::uses('AwarenessReminder', 'Model');
class AwarenessMgtComponent extends Component {
	public $components = array('LdapConnectorsMgt', 'NotificationSystemMgt');

	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->LdapConnectorsMgt->initialize($controller);

		$this->controller->loadModel('AwarenessProgram');
		$this->controller->loadModel('AwarenessUser');
	}

	/**
	 * Send out awareness emails to ldap users.
	 */
	public function cron() {
		$programs = $this->controller->AwarenessProgram->find('list', array(
			'conditions' => array(
				'AwarenessProgram.status' => AWARENESS_PROGRAM_STARTED
			),
			'fields' => array('AwarenessProgram.id'),
			'recursive' => -1
		));

		$ret = true;
		foreach ($programs as $programId) {
			// lets update all user information via LDAP
			$ret &= $this->updateActiveUsers($programId);

			$ret &= $this->setProgramRecurrence($programId);
			$ret &= $this->_sendNotificationEmails($programId);

			// lets update satatistics in eramba
			$ret &= $this->_updateStats($programId);
		}

		return $ret;
	}

	/**
	 * Get the list of active users for a program. Method makes a difference between all users and ignored users.
	 * @param  int   $awarenessProgramId  Awareness Program ID.
	 * @return array                      List of users
	 */
	protected function _getActiveUsers($awarenessProgramId, $getList = true) {
		// this cond happens when ldap connection fails
		if (($allUsers = $this->getProgramLdapUsersEmails($awarenessProgramId, $getList)) === false) {
			return false;
		}

		$ignoredUsers = $this->controller->AwarenessProgram->getIgnoredUsers($awarenessProgramId);
		
		$activeUsers = array();
		if ($getList) {
			foreach ($allUsers as $uid => $email) {
				if (!in_array($uid, $ignoredUsers)) {
					$activeUsers[$uid] = $email;
				}
			}
		}
		else {
			foreach ($allUsers as $user) {
				if (!in_array($user['uid'], $ignoredUsers)) {
					$activeUsers[] = $user;
				}
			}
		}

		return $activeUsers;
	}

	/**
	 * Make an awareness program's active users table up to date.
	 * 
	 * @param  int     $id                  Awareness Program ID.
	 * @param  boolean $createSystemRecords Whether to create a system records for a active user differences or not.
	 */
	public function updateActiveUsers($awarenessProgramId, $createSystemRecords = false) {
		$this->controller->loadModel('AwarenessProgram');

		$activeUsersEmails = $this->_getActiveUsers($awarenessProgramId, false);
		if ($activeUsersEmails === false) {
			return false;
		}

		$currentData = $this->controller->AwarenessProgram->AwarenessProgramActiveUser->find('list', array(
			'conditions' => array(
				'AwarenessProgramActiveUser.awareness_program_id' => $awarenessProgramId
			),
			'fields' => array('AwarenessProgramActiveUser.id', 'AwarenessProgramActiveUser.uid'),
			'recursive' => -1
		));

		$activeUsers = Hash::extract($activeUsersEmails, '{n}.uid');

		$diffAdded = array_diff($activeUsers, $currentData);
		$diffRemoved = array_diff($currentData, $activeUsers);

		$ret = true;
		if (!empty($diffAdded)) {
			// $ret &= $this->controller->AwarenessProgram->AwarenessProgramActiveUser->saveAll($dataToAdd);

			// create system records about new users in the training if $createSystemRecords is true
			if ($createSystemRecords) {
				$this->controller->AwarenessProgram->id = $awarenessProgramId;

				foreach ($diffAdded as $uid) {
					$this->controller->AwarenessProgram->addNoteToLog(__('A new account (%s) was detected and included on the training', $uid));					
				}
				
				$ret &= $this->controller->AwarenessProgram->setSystemRecord($awarenessProgramId, 2);
			}
		}

		if (!empty($diffRemoved)) {
			$activeUserIdsToRemove = array_keys($diffRemoved);

			// $ret &= $this->controller->AwarenessProgram->AwarenessProgramActiveUser->deleteAll(array(
			// 	'AwarenessProgramActiveUser.id' => $activeUserIdsToRemove
			// ));

			// create system records about deleted users from the training if $createSystemRecords is true
			if ($createSystemRecords) {
				$this->controller->AwarenessProgram->id = $awarenessProgramId;

				foreach ($diffRemoved as $uid) {
					$this->controller->AwarenessProgram->addNoteToLog(__('An account (%s) was removed from the group and removed from the training', $uid));					
				}

				$ret &= $this->controller->AwarenessProgram->setSystemRecord($awarenessProgramId, 2);
			}
		}

		$ret &= $this->controller->AwarenessProgram->AwarenessProgramActiveUser->deleteAll(array(
			'AwarenessProgramActiveUser.awareness_program_id' => $awarenessProgramId
		));

		if (!empty($activeUsersEmails)) {
			$dataToAdd = array();
			foreach ($activeUsersEmails as $member) {
				$name = '';
				if (!empty($member[$this->controller->ldapAuth['AuthAwareness']['ldap_name_attribute']])) {
					$name = $member[$this->controller->ldapAuth['AuthAwareness']['ldap_name_attribute']];
				}

				$dataToAdd[] = array(
					'awareness_program_id' => $awarenessProgramId,
					'uid' => $member['uid'],
					'email' => $member['email'],
					'name' => $name
				);
			}
			$ret &= $this->controller->AwarenessProgram->AwarenessProgramActiveUser->saveAll($dataToAdd);
		}

		return $ret;
	}

	/**
	 * Updates generic database columns that holds all statistics for a specified program, except active users which happens in the parent function call @see AwarenessMgtComponent::updateActiveUsers().
	 */
	protected function _updateStats($awarenessProgramId) {
		$ret = true;

		$stats = $this->controller->AwarenessProgram->getProgramStats($awarenessProgramId);

		// in case error occured getting statistics
		if ($stats === false) {
			$ret &= $this->controller->AwarenessProgram->updateAll(array(
				'AwarenessProgram.stats_update_status' => AWARENESS_PROGRAM_STATS_UPDATE_FAIL
			), array(
				'AwarenessProgram.id' => $awarenessProgramId
			));

			return $ret;
		}

		$ret &= $this->controller->AwarenessProgram->AwarenessProgramCompliantUser->deleteAll(array(
			'AwarenessProgramCompliantUser.awareness_program_id' => $awarenessProgramId
		));

		$ret &= $this->controller->AwarenessProgram->AwarenessProgramNotCompliantUser->deleteAll(array(
			'AwarenessProgramNotCompliantUser.awareness_program_id' => $awarenessProgramId
		));

		// we save compliant users stats
		if (!empty($stats['compliantUsers'])) {
			$compliantUsers = array();
			foreach ($stats['compliantUsers'] as $uid) {
				$compliantUsers[] = array(
					'awareness_program_id' => $awarenessProgramId,
					'uid' => $uid
				);
			}

			$ret &= $this->controller->AwarenessProgram->AwarenessProgramCompliantUser->saveAll($compliantUsers);
		}

		// we save not compliant users stats
		if (!empty($stats['notCompliantUsers'])) {
			$notCompliantUsers = array();
			foreach ($stats['notCompliantUsers'] as $uid) {
				$notCompliantUsers[] = array(
					'awareness_program_id' => $awarenessProgramId,
					'uid' => $uid
				);
			}

			$ret &= $this->controller->AwarenessProgram->AwarenessProgramNotCompliantUser->saveAll($notCompliantUsers);
		}

		// we update statistic values for a specified awareness program
		$this->controller->AwarenessProgram->id = $awarenessProgramId;
		$this->controller->AwarenessProgram->set(array(
			'id' => $awarenessProgramId,

			'active_users' => $stats['activeUsersCount'],
			'active_users_percentage' => $stats['activeUsersPercentageValue'],

			'ignored_users' => $stats['ignoredUsersCount'],
			'ignored_users_percentage' => $stats['ignoredUsersPercentageValue'],

			'compliant_users' => $stats['compliantUsersCount'],
			'compliant_users_percentage' => $stats['compliantUsersPercentageValue'],

			'not_compliant_users' => $stats['notCompliantUsersCount'],
			'not_compliant_users_percentage' => $stats['notCompliantUsersPercentageValue'],

			'stats_update_status' => AWARENESS_PROGRAM_STATS_UPDATE_SUCCESS
		));

		$ret &= $this->controller->AwarenessProgram->save(null, false);

		return $ret;
	}

	/**
	 * Get list of users for a specified awareness program using LDAP.
	 * Warning! This method should be used only for updating database values as it uses LDAP and takes a lot of time.
	 * 
	 * @see AwarenessProgram::getAllUsers() To get the list from database.
	 */
	public function getProgramLdapUsers($id) {
		$program = $this->controller->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $id
			),
			'contain' => array(
				'LdapConnector',
				'AwarenessProgramLdapGroup'
			)
		));

		$groups = array();
		foreach ($program['AwarenessProgramLdapGroup'] as $group) {
			$groups[] = $group['name'];
		}

		$groupConnector = $program['LdapConnector'];

		$LdapConnector = $this->LdapConnectorsMgt->getConnector($groupConnector);
		$ldapConnection = $LdapConnector->connect();

		$users = $LdapConnector->getMemberList($groups);

		$this->controller->set('ldapConnection', $ldapConnection);

		return $users;
	}

	public function getProgramLdapUsersEmails($id, $getList = true) {
		$program = $this->controller->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $id
			),
			'contain' => array(
				'LdapConnector',
				'AwarenessProgramLdapGroup'
			)
		));

		$groups = array();
		foreach ($program['AwarenessProgramLdapGroup'] as $group) {
			$groups[] = $group['name'];
		}

		$groupConnector = $program['LdapConnector'];

		$LdapConnector = $this->LdapConnectorsMgt->getConnector($groupConnector);
		$ldapConnection = $LdapConnector->connect();

		if ($getList) {
			$users = $LdapConnector->getMemberEmailList($groups);
		}
		else {
			if (empty($this->controller->ldapAuth['LdapConnectorAuthentication']['auth_awareness'])) {
				return false;
			}
			
			$AuthConnectorParams = $this->controller->ldapAuth['AuthAwareness'];
			$users = $LdapConnector->getMemberArray($groups, array($AuthConnectorParams['ldap_name_attribute']));
		}

		$this->controller->set('ldapConnection', $ldapConnection);

		return $users;
	}

	/**
	 * Handles creation of recurrence records for Awareness Program.
	 */
	public function setProgramRecurrence($id) {
		$program = $this->controller->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $id
			),
			'recursive' => -1
		));

		$days = $program['AwarenessProgram']['recurrence'];
		$datetime = CakeTime::format('Y-m-d', CakeTime::fromString('-' . $days . ' days'));

		$recurrence = $this->controller->AwarenessProgram->AwarenessProgramRecurrence->find('first', array(
			'conditions' => array(
				'AwarenessProgramRecurrence.awareness_program_id' => $id,
				// 'AwarenessProgramRecurrence.start >' => $datetime 
			),
			'order' => array('AwarenessProgramRecurrence.start' => 'DESC'),
			'recursive' => -1
		));

		// if a new recurrence for a training triggered, create a record
		if (empty($recurrence) || !($recurrence['AwarenessProgramRecurrence']['start'] > $datetime)) {

			$ret = true;

			if (!empty($recurrence)) {
				$ret &= $this->_setMissingUsersFromRecurrence($recurrence);
			}

			$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

			$saveData = array(
				'awareness_program_id' => $id,
				'start' => $today
			);
			$this->controller->AwarenessProgram->AwarenessProgramRecurrence->create();
			$ret &= $this->controller->AwarenessProgram->AwarenessProgramRecurrence->save($saveData);

			return $ret;
		}

		return true;
	}

	protected function _setMissingUsersFromRecurrence($recurrence) {
		$awarenessProgramId = $recurrence['AwarenessProgramRecurrence']['awareness_program_id'];
		$awarenessProgramRecurrenceId = $recurrence['AwarenessProgramRecurrence']['id'];

		// changed to read active users from db, not from ldap
		// $activeUsers = $this->_getActiveUsers($awarenessProgramId);
		$activeUsers = $this->controller->AwarenessProgram->getActiveUsers($awarenessProgramId);

		$this->controller->loadModel('AwarenessTraining');
		$trainingDone = $this->controller->AwarenessTraining->find('list', array(
			'conditions' => array(
				'AwarenessUser.login' => $activeUsers,
				'AwarenessTraining.awareness_program_id' => $awarenessProgramId,
				'AwarenessTraining.awareness_program_recurrence_id' => $awarenessProgramRecurrenceId,
				'AwarenessTraining.demo' => 0
			),
			'fields' => array('AwarenessUser.login'),
			'recursive' => 0
		));

		// debug($trainingDone);
		$missedUsers = array_diff($activeUsers, $trainingDone);
		// debug($missedUsers);

		$saveData = array();
		$ret = true;
		if (!empty($missedUsers)) {
			$this->controller->loadModel('AwarenessProgramMissedRecurrence');

			foreach ($missedUsers as $uid) {
				$this->controller->AwarenessProgramMissedRecurrence->create();
				$this->controller->AwarenessProgramMissedRecurrence->set(array(
					'uid' => $uid,
					'awareness_program_id' => $awarenessProgramId,
					'awareness_program_recurrence_id' => $awarenessProgramRecurrenceId
				));

				$ret &= $this->controller->AwarenessProgramMissedRecurrence->save(null, false);
			}
		}

		return $ret;
	}

	/**
	 * Starts an awareness training, send emails, writes a system log.
	 * 
	 * @param  int $id Awareness Program ID.
	 * @return bool    True on success.
	 */
	public function startTraining($id) {
		$this->controller->AwarenessProgram->id = $id;
		$this->controller->AwarenessProgram->set(array('status' => AWARENESS_PROGRAM_STARTED));
		$ret = $this->controller->AwarenessProgram->save(null, false);

		$ret &= $this->updateActiveUsers($id);
		$ret &= $this->setProgramRecurrence($id);

		if ($ret) {
			$this->controller->AwarenessProgram->quickLogSave($id, 2, __('The training has started'), ADMIN_ID);

			/*$ret &= */$this->_sendNotificationEmails($id);
		}

		return $ret;
	}

	/**
	 * Stops an awareness training, writes a system log.
	 * 
	 * @param  int $id Awareness Program ID.
	 * @return bool    True on success.
	 */
	public function stopTraining($id) {
		$this->controller->AwarenessProgram->id = $id;
		$this->controller->AwarenessProgram->set(array('status' => AWARENESS_PROGRAM_STOPPED));
		$ret = $this->controller->AwarenessProgram->save(null, false);

		if ($ret) {
			$this->controller->AwarenessProgram->addNoteToLog(__('The training has stopped'));
			$this->controller->AwarenessProgram->setSystemRecord($id, 2);
		}

		return $ret;
	}

	/**
	 * Sends demo email notification to selected user for testing purposes.
	 * 
	 * @param  int $id Awareness Program ID.
	 * @return bool    True on success.
	 */
	public function demoTraining($id, $uid, $email) {
		$program = $this->controller->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $id
			),
			'recursive' => -1
		));

		$ret = $this->_sendDemoReminder($email, $uid, $program);

		//if demo exists we dont have to create another
		if (!$this->controller->AwarenessProgram->AwarenessProgramDemo->liveDemoExists($id, $uid)) {
			$this->controller->AwarenessProgram->AwarenessProgramDemo->create();
			$ret &= $this->controller->AwarenessProgram->AwarenessProgramDemo->save(array(
				'uid' => $uid,
				'awareness_program_id' => $id
			));
		}

		return $ret;
	}

	/**
	 * Cleans training records by date interval. Affects trainings, reminders, program recurrences, program demos, graph.
	 * 
	 * @param  int $id Awareness Program ID.
	 * @return bool    True on success.
	 */
	public function cleanTraining($id, $from = null, $to = null) {
		$conds = $this->_getDateIntervalConds($from, $to, 'AwarenessTraining');
		$conds['AwarenessTraining.awareness_program_id'] = $id;
		$ret = $this->controller->AwarenessProgram->AwarenessTraining->deleteAll($conds);

		$conds = $this->_getDateIntervalConds($from, $to, 'AwarenessReminder');
		$conds['AwarenessReminder.awareness_program_id'] = $id;
		$ret &= $this->controller->AwarenessProgram->AwarenessReminder->deleteAll($conds);

		$conds = $this->_getDateIntervalConds($from, $to, 'AwarenessProgramRecurrence', 'start');
		$conds['AwarenessProgramRecurrence.awareness_program_id'] = $id;
		$ret &= $this->controller->AwarenessProgram->AwarenessProgramRecurrence->deleteAll($conds);

		$conds = $this->_getDateIntervalConds($from, $to, 'AwarenessProgramDemo');
		$conds['AwarenessProgramDemo.awareness_program_id'] = $id;
		$ret &= $this->controller->AwarenessProgram->AwarenessProgramDemo->deleteAll($conds);

		$this->controller->loadModel('AwarenessOvertimeGraph');
		$conds = $this->_getDateIntervalConds($from, $to, 'AwarenessOvertimeGraph');
		$conds['AwarenessOvertimeGraph.awareness_program_id'] = $id;
		$ret &= $this->controller->AwarenessOvertimeGraph->deleteAll($conds);

		if ($ret) {
			$who = $this->controller->logged['login'];
			if (!empty($this->controller->logged['full_name'])) {
				$who = sprintf('%s (%s)', $this->controller->logged['full_name'], $this->controller->logged['login']);
			}

			if (empty($from) && empty($to)) {
				$log = __('All awareness records have been deleted by %s', $who);
			}
			elseif (!empty($from) && !empty($to)) {
				$log = __('All awareness records in between dates %s and %s have been deleted by %s', $from, $to, $who);
			}
			elseif (!empty($from)) {
				$log = __('All awareness records since %s have been deleted by %s', $from, $who);
			}
			elseif (!empty($to)) {
				$log = __('All awareness records until %s have been deleted by %s', $to, $who);
			}

			$this->controller->AwarenessProgram->id = $id;
			$this->controller->AwarenessProgram->addNoteToLog($log);
			$this->controller->AwarenessProgram->setSystemRecord($id, 2);
		}

		return $ret;
	}

	/**
	 * Create conditions based on from date and to date.
	 */
	protected function _getDateIntervalConds($from, $to, $model, $col = 'created') {
		$conds = array();

		if (!empty($from)) {
			$conds[$model . '.' . $col . ' >='] = $from;
		}
		if (!empty($to)) {
			$conds[$model . '.' . $col . ' <='] = $to;
		}

		return $conds;
	}

	/**
	 * Manages sending of email notifications for a single active awareness program.
	 */
	protected function _sendNotificationEmails($id) {
		$program = $this->controller->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $id,
				'AwarenessProgram.status !=' => AWARENESS_PROGRAM_STOPPED
			),
			'recursive' => 1
		));

		$usersEmails = $this->getAllowedUsers($id);

		$ret = true;
		foreach ($usersEmails as $uid => $email) {
			
			if ($this->_isUserMissingReminder($uid, $program)) {
				$ret &= $this->_sendUserReminder($email, $uid, $program);
				if (!$ret) {
					return false;
				}
			}
		}

		return $ret;
	}

	/**
	 * Get uid => email of all users allowed to participate in a awareness program training using LDAP.
	 * 
	 * @param  int $id   Awareness Program ID.
	 * @return array     Emails of users and groups.
	 */
	public function getAllowedUsers($id) {
		$data = $this->controller->AwarenessProgram->AwarenessProgramActiveUser->find('list', array(
			'conditions' => array(
				'AwarenessProgramActiveUser.awareness_program_id' => $id
			),
			'fields' => array('uid', 'email'),
			'recursive' => -1
		));

		return $data;
	}

	/**
	 * Get all existing users using LdapConnector.
	 *
	 * @deprecated
	 */
	public function getAllUsers() {
		$this->controller->loadModel('LdapConnectorAuthentication');
		$authentication = $this->controller->LdapConnectorAuthentication->find('first', array(
			'recursive' => 0
		));

		$connector = $authentication['AuthAwareness'];

		$ldapConnection = $this->LdapConnectorsMgt->ldapConnect($connector['host'], $connector['port'], $connector['ldap_bind_dn'], $connector['ldap_bind_pw']);
		$this->controller->set('ldapConnection', $ldapConnection);
		
		if ($ldapConnection !== true) {
			return false;
		}
		
		$users = $this->LdapConnectorsMgt->getConnectorUsers($connector);

		return $users;
	}

	/**
	 * Check if UID had a reminder already today - to avoid duplicates.
	 * 	
	 * @return boolean   True if an UID already had a reminder today, False otherwise.
	 */
	protected function _userHadReminderToday($uid, $programId) {
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		$count = $this->controller->AwarenessProgram->AwarenessReminder->find('count', array(
			'conditions' => array(
				'AwarenessReminder.uid' => $uid,
				'AwarenessReminder.awareness_program_id' => $programId,
				'AwarenessReminder.demo' => 0,
				'DATE(AwarenessReminder.created)' => $today
			),
			'recursive' => -1
		));

		return (bool) $count;
	}

	/**
	 * Get the count of reminders user got during the latest recurrence.
	 * 
	 * @return int Count of reminders.
	 */
	protected function _userHadRemindersInRecurrence($uid, $programId, $reminderType = null) {
		$latestRecurrence = $this->getLastRecurrence($programId, false);

		$conds = [
			'AwarenessReminder.uid' => $uid,
			'AwarenessReminder.awareness_program_id' => $programId,
			'AwarenessReminder.demo' => 0,
			'DATE(AwarenessReminder.created) >=' => $latestRecurrence
		];

		if ($reminderType !== null) {
			$conds['AwarenessReminder.reminder_type'] = $reminderType;
		}

		$count = $this->controller->AwarenessProgram->AwarenessReminder->find('count', array(
			'conditions' => $conds,
			'recursive' => -1
		));

		return $count;
	}

	/**
	 * Check if a user already completed a training during the latest recurrence.
	 * 
	 * @return bool True if user completed a training in latest recurrence, False otherwise.
	 */
	protected function _userHadTrainingInRecurrence($uid, $programId) {
		$latestRecurrence = $this->getLastRecurrence($programId, false);

		$count = $this->controller->AwarenessProgram->AwarenessTraining->find('count', array(
			'conditions' => array(
				'AwarenessUser.login' => $uid,
				'AwarenessTraining.awareness_program_id' => $programId,
				'AwarenessTraining.demo' => 0,
				'DATE(AwarenessTraining.created) >=' => $latestRecurrence,
			),
			'recursive' => 0
		));

		return (bool) $count;
	}

	/**
	 * User had already reminder as specified in "Reminders Apart" setting for a Program.
	 * 
	 * @return bool True if he had already a reminder, False otherwise.
	 */
	protected function _userHadReminderAlready($uid, $programId) {
		$program = $this->controller->AwarenessProgram->find('first', array(
			'conditions' => array(
				'AwarenessProgram.id' => $programId
			),
			'fields' => array('reminder_apart'),
			'recursive' => -1
		));

		$remindersApart = $program['AwarenessProgram']['reminder_apart'];
		$date = CakeTime::format('Y-m-d', CakeTime::fromString('-' . $remindersApart . ' days'));
		$latestRecurrence = $this->getLastRecurrence($programId, false);

		// this is to determine from which point we are going to check the count of reminders - last recurrence or reminder_apart setting - one that comes later
		$fromDate = $date > $latestRecurrence ? $date : $latestRecurrence;

		$count = $this->controller->AwarenessProgram->AwarenessReminder->find('count', array(
			'conditions' => array(
				'AwarenessReminder.uid' => $uid,
				'AwarenessReminder.awareness_program_id' => $programId,
				'AwarenessReminder.demo' => 0,
				'DATE(AwarenessReminder.created) >' => $fromDate
			),
			'recursive' => -1
		));

		return (bool) $count;
	}

	/**
	 * Check if a user should get a reminder for a training.
	 * 
	 * - today in consideration = today no more than 1 email
	 * - reminder apart + reminder amount in consideration
	 * - invitation/reminder type in consideration - default is invitation, or specified otherwise
	 *
	 * @return boolean True to send a notification email, False otherwise.
	 */
	protected function _isUserMissingReminder($uid, $program) {
		$programId = $program['AwarenessProgram']['id'];
		$reminderAmountForProgram = $program['AwarenessProgram']['reminder_amount'];

		$hasToday = $this->_userHadReminderToday($uid, $programId);
		// if user got already email today (in case of repetitive cron or anything related to this), we skip him
		if ($hasToday) {
			return false;
		}

		$hadTraining = $this->_userHadTrainingInRecurrence($uid, $programId);
		// in case user already have a training completed during the latest recurrence
		if ($hadTraining) {
			return false;
		}

		// number of reminders he got, not counting the first one invitation in recurrence
		$hadRemindersInInterval = $this->_userHadRemindersInRecurrence(
			$uid,
			$programId,
			AwarenessReminder::REMINDER_REMINDER
		);

		// in case user had enough reinders specified for a program recurrence we skip him
		if ($hadRemindersInInterval >= $reminderAmountForProgram) {
			return false;
		}

		$hadAlready = $this->_userHadReminderAlready($uid, $programId);
		// if user already got a reminder in specified "reminder apart" interval, skip him
		if ($hadAlready) {
			return false;
		}

		// if all checks fail, the user is going to get the notification reminder email
		return true;
	}

	/**
	 * Send an awareness email reminder to a user and saves a system log.
	 */
	protected function _sendUserReminder($_email, $uid, $program) {
		$options = array(
			'demo' => 0
		);

		return $this->_sendTrainingReminder($_email, $uid, $program, $options);
	}

	protected function _sendDemoReminder($_email, $uid, $program) {
		$options = array(
			'demo' => 1
		);

		return $this->_sendTrainingReminder($_email, $uid, $program, $options);
	}

	/**
	 * Get email body and subject based on conditions.
	 */
	protected function _getEmailParamsByType($reminderType = false, $program) {
		$_p = $program['AwarenessProgram'];

		$useDefaultsConds = empty($reminderType);
		$useDefaultsConds = $useDefaultsConds || $reminderType == AwarenessReminder::REMINDER_INVITATION;
		$useDefaultsConds = $useDefaultsConds || ($reminderType == AwarenessReminder::REMINDER_REMINDER && empty($_p['email_reminder_custom']));

		if ($useDefaultsConds) {
			return array('subject' => $_p['email_subject'], 'body' => $_p['email_body']);
		}

		if ($reminderType == AwarenessReminder::REMINDER_REMINDER && !empty($_p['email_reminder_custom'])) {
			return array('subject' => $_p['email_reminder_subject'], 'body' => $_p['email_reminder_body']);
		}

		return false;
	}

	/**
	 * Sending a reminder email handler function.
	 */
	protected function _sendTrainingReminder($_email, $uid, $program, $options = array()) {
		$programId = $program['AwarenessProgram']['id'];
		$reminderType = AwarenessReminder::REMINDER_REMINDER;

		$remindersCount = $this->_userHadRemindersInRecurrence($uid, $programId);

		// if no reminders exists for a user, set this as invitation one
		if (!$remindersCount) {
			$reminderType = AwarenessReminder::REMINDER_INVITATION;
		}

		$this->NotificationSystemMgt->initialize($this->controller);
		$macros = $this->NotificationSystemMgt->getMacros('AwarenessProgram');

		$emailParams = $this->_getEmailParamsByType($reminderType, $program);

		$model = 'AwarenessProgram';
		$subject = $this->NotificationSystemMgt->parseMacros($emailParams['subject'], $program, $macros, $model);
		$body = $this->NotificationSystemMgt->parseMacros($emailParams['body'], $program, $macros, $model);

		$body = $this->NotificationSystemMgt->replaceMacro($body, AWARENESS_PROGRAM_MACRO_UID, $uid);
		$body = $this->NotificationSystemMgt->replaceMacro($body, AWARENESS_PROGRAM_MACRO_EMAIL, $_email);

		//replace user name macro from database
		$user = $this->controller->AwarenessProgram->AwarenessProgramActiveUser->find('first', array(
			'conditions' => array(
				'AwarenessProgramActiveUser.uid' => $uid
			),
			'fields' => array('AwarenessProgramActiveUser.name'),
			'recursive' => -1
		));

		$userName = '-';
		if (!empty($user['AwarenessProgramActiveUser']['name'])) {
			$userName = $user['AwarenessProgramActiveUser']['name'];
		}

		$body = $this->NotificationSystemMgt->replaceMacro($body, AWARENESS_PROGRAM_MACRO_NAME, $userName);

		$emailData = array(
			'program' => $program,
			'url' => Router::url(array('controller' => 'awareness', 'action' => 'index', 'plugin' => null), true),
			'body' => $body
		);

		$instant = false;

		if ($options['demo']) {
			$_subject = $subject . ' (' . __('Demo') . ')';
			$emailData['isDemo'] = 1;
			$systemLog = __('Demo email sent to: %s', $_email);
			$instant = true;
		}
		else {
			if ($reminderType == AwarenessReminder::REMINDER_INVITATION) {
				$systemLog = __('Invitation email sent to: %s', $_email);
			}
			if ($reminderType == AwarenessReminder::REMINDER_REMINDER) {
				$systemLog = __('Reminder email sent to: %s', $_email);
			}

			$_subject = $subject;
		}

		// if user doesnt have an email attribute, we log it and skip him without failing.
		if (empty($_email)) {
			$systemLog = __('The user %s was not emailed as they have no email attribute', $uid);

			// system log
			$this->_setAwarenessRecord($programId, $systemLog);
			return true;
		}

		$email = $this->controller->initEmailFromComponent($_email, $_subject, 'awareness', $emailData, 'default', NO_REPLY_EMAIL, 'html', $instant);

		// system log
		$this->_setAwarenessRecord($programId, $systemLog);

		// we save the reminder first in case of duplicite cron running simultaniously
		$ret = $this->_saveReminder($uid, $_email, $programId, $options['demo'], $reminderType);
		$lastReminderId = null;
		if ($ret) {
			$lastReminderId = $this->controller->AwarenessProgram->AwarenessReminder->id;
		}

		$ret &= $emailSent = (bool) $email->send();

		if (!$emailSent && !empty($lastReminderId)) {
			$this->controller->AwarenessProgram->AwarenessReminder->delete($lastReminderId);
		}

		return $ret;
	}

	protected function _setAwarenessRecord($id, $systemLog) {
		$this->controller->AwarenessProgram->id = $id;
		$this->controller->AwarenessProgram->quickLogSave($id, 2, $systemLog, ADMIN_ID);
	}

	/**
	 * Save a database record that reminder has been sent.
	 *
	 * @version e1.0.6.016 Added email and reminder_type fields.
	 */
	protected function _saveReminder($uid, $email, $awarenessProgramId, $demo = 0, $reminderType) {
		$this->controller->AwarenessProgram->AwarenessReminder->create();
		return $this->controller->AwarenessProgram->AwarenessReminder->save(array(
			'uid' => $uid,
			'email' => $email,
			'awareness_program_id' => $awarenessProgramId,
			'demo' => $demo,
			'reminder_type' => $reminderType
		));
	}

	public function getLastRecurrence($id, $returnId = true) {
		$this->setProgramRecurrence($id);

		$latestRecurrence = $this->controller->AwarenessProgram->AwarenessProgramRecurrence->find('first', array(
			'conditions' => array(
				'AwarenessProgramRecurrence.awareness_program_id' => $id
			),
			'order' => array(
				'AwarenessProgramRecurrence.start' => 'DESC'
			),
			'recursive' => -1
		));
		
		if (empty($latestRecurrence)) {
			return $this->getLastRecurrence($id, $returnId);
		}

		if ($returnId) {
			return $latestRecurrence['AwarenessProgramRecurrence']['id'];
		}

		return $latestRecurrence['AwarenessProgramRecurrence']['start'];
	}
}
