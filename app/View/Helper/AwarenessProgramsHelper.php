<?php
App::uses('ErambaHelper', 'View/Helper');
class AwarenessProgramsHelper extends ErambaHelper {
	public $helpers = array('Html', 'AdvancedFilters');
	public $settings = array();
	
	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);

		$this->settings = $settings;
	}

	public function getStatuses($item) {
		$statuses = array();

		if ($item['AwarenessProgram']['status'] == AWARENESS_PROGRAM_STARTED) {
			$statuses[] = $this->getLabel(__('Started'), 'success');
		}

		if ($item['AwarenessProgram']['status'] == AWARENESS_PROGRAM_STOPPED) {
			$statuses[] = $this->getLabel(__('Paused'), 'warning');
		}

		return $this->processStatuses($statuses);
	}

	public function getExampleLink($type, $title = null) {
		if (empty($title)) {
			$title = __('Download Example');
		}

		return $this->Html->link($title, array(
			'controller' => 'awarenessPrograms',
			'action' => 'downloadExample',
			$type
		), array(
			'class' => 'btn btn-sm'
		));
	}

	/**
	 * Get part of a pulled statistics of a program.
	 * @updated e1.0.6.016 Statistics are now hold as values in database, not pulled and calculated on the fly.
	 */
	public function getStatisticPart($program, $part = null) {
		if (!in_array($part, array('active', 'ignored', 'compliant', 'not_compliant'))) {
			return false;
		}

		$key = $part . '_users';

		$usersCount = $program[$key];

		$usersPercentage = $program[$key . '_percentage'];
		$usersPercentage = CakeNumber::toPercentage($usersPercentage, 0, array('multiply' => false));

		return sprintf(__n('%d user', '%d users', $usersCount), $usersCount) . ' ' . sprintf('(%s)', $usersPercentage);
	}

	public function outputActiveUsersLink($data, $options = array()) {
        $link = $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $data, array(
            'key' => 'awareness_program_id',
            'param' => 'ActiveUser'
        ), $options);
        return $link;
    }

    public function outputIgnoredUsersLink($data, $options = array()) {
        $link = $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $data, array(
            'key' => 'awareness_program_id',
            'param' => 'IgnoredUser'
        ), $options);
        return $link;
    }

    public function outputCompliantUsersLink($data, $options = array()) {
        $link = $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $data, array(
            'key' => 'awareness_program_id',
            'param' => 'CompliantUser'
        ), $options);
        return $link;
    }

    public function outputNotCompliantUsersLink($data, $options = array()) {
        $link = $this->AdvancedFilters->getItemFilteredLink(__('List Users'), 'AwarenessProgramUser', $data, array(
            'key' => 'awareness_program_id',
            'param' => 'NotCompliantUser'
        ), $options);
        return $link;
    }
}