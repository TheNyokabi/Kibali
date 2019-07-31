<?php
App::uses('ObjectVersionAppController', 'ObjectVersion.Controller');
App::uses('ObjectVersionHistory', 'ObjectVersion.Lib');
App::uses('ObjectVersionRestore', 'ObjectVersion.Lib');
App::uses('Audit', 'ObjectVersion.Model');

class ObjectVersionController extends ObjectVersionAppController {
	public $components = array( 'Session', 'Ajax');

	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function history($model, $foreignKey) {
		if (empty($model)) {
			throw new NotFoundException();
		}

		$this->set('title_for_layout', __('History Timeline'));
		
		$historyClass = new ObjectVersionHistory($model, $foreignKey);
		$this->set('historyClass', $historyClass);
		$this->set('showHeader', true);
	}

	public function restore($auditId) {
		$this->Audit = ClassRegistry::init('ObjectVersion.Audit');

		$audit = $this->Audit->find('first', array(
			'conditions' => array(
				'Audit.id' => $auditId
			),
			'recursive' => -1
		));

		if (empty($audit)) {
			throw new NotFoundException();
		}

		$restoreClass = new ObjectVersionRestore($auditId);
		if ($restoreClass->restore()) {
			// $this->Ajax->success();
			
			if ($restoreClass->isRestoredDataValid()) {
				$this->Session->setFlash(__('Object was successfully restored.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Object was restored but its data failed validation. Please edit and re-save the object to manually resolve possible issues.'), FLASH_WARNING);
			}
			
			// in case everything went fine during the restore but revisions are identical
			if ($restoreClass->hasChanges() === false && $restoreClass->isRestoredDataValid()) {
				$this->Flash->set(__('Current revision and the revision you chose to restore are identical, there is no need to add another one.'));
			}

		}
		else {
			$this->Session->setFlash(__('Error occured while trying to restore the object. Please try it again.'), FLASH_ERROR);
		}

		// return $this->redirect(array('action' => 'history', $audit['Audit']['model'], $audit['Audit']['entity_id']));

		$this->history($audit['Audit']['model'], $audit['Audit']['entity_id']);
		$this->render('history');
	}

}