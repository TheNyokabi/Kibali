<?php
class ProjectAchievement_Deadline_002 extends ProjectAchievement_Deadline_Base {
	protected $reminderDays = -5;

	public function __construct($options = array()) {
		parent::__construct($options);
	}
}
