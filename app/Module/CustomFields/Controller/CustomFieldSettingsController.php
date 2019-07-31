<?php
App::uses('CustomFieldsAppController', 'CustomFields.Controller');

class CustomFieldSettingsController extends CustomFieldsAppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session', 'Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
	}
	
	public function edit($model) {
		$data = $this->CustomFieldSetting->find('first', array(
			'conditions' => array(
				'CustomFieldSetting.model' => $model
			)
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('edit', true);
		$this->set('model', $model);

		if ($this->request->is('post') || $this->request->is('put')) {

			$this->request->data['CustomFieldSetting']['id'] = $data['CustomFieldSetting']['id'];
			$this->CustomFieldSetting->set($this->request->data);

			if ($this->CustomFieldSetting->validates()) {
				$dataSource = $this->CustomFieldSetting->getDataSource();
				$dataSource->begin();

				$ret = $this->CustomFieldSetting->save();

				if ($ret) {
					$dataSource->commit();
					$this->Session->setFlash(__('Custom Field Settings for this section was successfully edited.'), FLASH_OK);
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			} else {
				$this->Session->setFlash(__('One or more inputs you entered are invalid. Please try again.'), FLASH_ERROR);
			}
		}
		else {
			$this->request->data = $data;
		}

		$this->render('add');
	}


}
