<?php
class UpdatesController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session', 'AutoUpdate');
	public $uses = array('BackupRestore.BackupRestore');

	public function beforeFilter() {
		parent::beforeFilter();

		if (!isAdmin($this->logged)) {
			$this->Session->setFlash(__('Only accounts members of the admin group are allowed to update the system.'), FLASH_ERROR);
			$this->redirect(array('controller' => 'pages', 'action' => 'welcome'));
		}
	}

	public function index() {
		$this->set('title_for_layout', __('Available updates'));
		$this->set('subtitle_for_layout', __('Use this functionality to update your system'));

		Cache::delete('server_response', 'updates');

		$update = $this->AutoUpdate->check();
		if ($this->AutoUpdate->hasError()) {
			$this->set('errorMessage', $this->AutoUpdate->getErrorMessage());
		}

		$this->set('update', $update);
	}

	public function update() {
		ignore_user_abort(true);
		set_time_limit(600); //10 min

		$update = $this->AutoUpdate->update();
		if ($this->AutoUpdate->hasError()) {
			$this->set('errorMessage', $this->AutoUpdate->getErrorMessage());
		}
		else {
			$this->set('successMessage', __('Successfuly updated.'));
		}

		$this->set('update', $this->AutoUpdate->check());

		$this->render('/Elements/updates/updateWidget');
	}
}
