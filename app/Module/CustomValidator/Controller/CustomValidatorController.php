<?php
App::uses('CustomValidatorAppController', 'CustomValidator.Controller');
App::uses('CustomValidatorField', 'CustomValidator.Model');

class CustomValidatorController extends CustomValidatorAppController {

	public $uses = ['CustomValidator.CustomValidatorField'];
	public $components = [
		'Ajax' => [
			'actions' => ['index', 'setup'],
		]
	];

	public function beforeFilter() {
		parent::beforeFilter();

		if (in_array($this->request->params['action'], ['getValidation'])) {
			$this->Security->csrfCheck = false;
		}
	}

	public function index($model) {
		$this->loadModel($model);

		if (!$this->{$model}->Behaviors->enabled('CustomValidator.CustomValidator')) {
			throw new NotFoundException();
		}

		$data = $this->{$model}->getCustomValidator();

		$this->set('title_for_layout', __('%s - Custom Validator', $this->{$model}->label));
		$this->set('model', $model);
		$this->set('data', $data);
	}

	public function setup($model, $validator) {
		$this->loadModel($model);

		if (!$this->{$model}->Behaviors->enabled('CustomValidator.CustomValidator')) {
			throw new NotFoundException();
		}

		$validatorConfig = $this->{$model}->getCustomValidator($validator);

		if (empty($validator)) {
			throw new NotFoundException();
		}

		if ($this->request->is('post')) {
			$ret = $this->{$model}->saveCustomValidator($validator, $this->request->data['CustomValidatorField']);
			if ($ret) {
				$this->Ajax->success();
				$this->Session->setFlash(__('Custom Validato was succesfully saved.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
			}
		}
		else {
			$this->request->data['CustomValidatorField'] = $this->{$model}->getCustomValidatorData($validator);
		}

		$this->set($this->{$model}->getFieldDataEntity()->getViewOptions());

		$this->set('title_for_layout', $validatorConfig['title']);
		$this->set('model', $model);
		$this->set('validator', $validatorConfig);
		$this->set('modalPadding', true);
	}

	public function getValidation($model) {
		$this->autoRender = false;
		$response = false;

		$Model = ClassRegistry::init($model);

		$validator = $Model->findValidator($this->request->data);

		if (!empty($validator)) {
			$data = $Model->getCustomValidatorData($validator);
			$response = [];

			foreach ($data as $field => $value) {
				$response[$field] = CustomValidatorField::getHtmlClass($value);
			}
		}
		
		echo json_encode($response);
	}

}
