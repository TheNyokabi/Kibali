<?php
App::uses('ImportToolAppController', 'ImportTool.Controller');
class ImportToolController extends ImportToolAppController {
	public $helpers = array('Eramba');
	// public $uses = array('')

	public function beforeFilter() {
		parent::beforeFilter();
		ini_set("auto_detect_line_endings", true);
	}

	protected function getImportArgs($model) {
		$this->loadModel($model);
		if (!empty($this->{$model}->importArgs)) {
			return $this->{$model}->importArgs;
		}

		return false;
	}

	public function index($model) {
		if (empty($model)) {
			throw new NotFoundException();
		}

		$this->set('title_for_layout', __('Import Tool For %s', getModelLabel($model)));
		$this->set('subtitle_for_layout', __('Upload a CSV file to import your data, download a blank CSV file template or download an export of your current data.'));
		$this->set('model', $model);

		if ($this->request->is('post')) {
			$this->ImportTool->set($this->request->data);

			if ($this->ImportTool->validates()) {
				$data = array();

				$uploadedFile = $this->request->data['ImportTool']['CsvFile']['tmp_name'];

				App::uses('ImportToolCsv', 'ImportTool.Lib');
				$csv = new ImportToolCsv($uploadedFile);
				$data = $csv->getData();
				$errors = $csv->getErrors();

				if (!empty($data) && empty($errors)) {
					// we delete previous cached import
					Cache::delete('user_preview_ImportToolData_' . $this->logged['id'], 'ImportTool');

					// and reload the new import data
					Cache::write('user_preview_data_' . $this->logged['id'], $data, 'ImportTool');
					Cache::write('user_preview_model_' . $this->logged['id'], $model, 'ImportTool');

					$this->redirect(array('action' => 'preview'));
				}
				// error parsing the file
				else {
					$this->Session->setFlash(__('Error occured while processing the file. Please try again.'), FLASH_ERROR);
				}
			}
			// validation failed
			else {

			}
		}
	}

	/**
	 * Let the user download a CSV taht includes fields necessary for import
	 */
	public function downloadTemplate($model, $getData = false) {
		if (empty($model)) {
			throw new NotFoundException();
		}

		App::uses('ImportToolTemplate', 'ImportTool.Lib');
		$Model = ClassRegistry::init($model);
		$templateClass = new ImportToolTemplate($Model);
		$arguments = $templateClass->getArguments();
		$argumentLabels = $templateClass->getArgumentLabels();

		if (empty($getData)) {
			$data = array($argumentLabels);

			$_serialize = 'data';

			$this->response->download(Inflector::slug($model) . '-import-template.csv');
			$this->viewClass = 'CsvView.Csv';
			$this->set(compact('data', '_serialize'));
		}
		else {
			$modelData = $Model->find('all');
			$data = $templateClass->convertDataToExport($modelData);

			$_serialize = 'data';
			$_header = false;

			$_bom = true;

			$this->response->download(Inflector::slug($model) . '-export.csv');
			$this->viewClass = 'CsvView.Csv';
			$this->set( compact( 'data', '_header', '_serialize' ) );
		}
	}

	/**
	 * Preview page for the parsed file's data that user wants to import.
	 */
	public function preview() {
		App::uses('ImportToolData', 'ImportTool.Lib');
		App::uses('ImportToolImport', 'ImportTool.Lib');

		$this->set('title_for_layout', __('Preview Imported Data'));

		$data = Cache::read('user_preview_data_' . $this->logged['id'], 'ImportTool');
		$model = Cache::read('user_preview_model_' . $this->logged['id'], 'ImportTool');

		$previewAvailable = !empty($data) && !empty($model);

		if (!$previewAvailable) {
			$this->Session->setFlash(__('There is nothing to preview. Upload a CSV file again please.'), FLASH_ERROR);
			$this->redirect(array('plugin' => null, 'controller' => 'pages', 'action' => 'welcome'));
		}

		$this->loadModel($model);
		$_model = $this->{$model};

		$cacheStr = 'user_preview_ImportToolData_' . $this->logged['id'];

		// disable cached preview for debugging
		if (Configure::read('debug') || ($ImportToolData = Cache::read($cacheStr, 'ImportTool')) === false) {
			$ImportToolData = new ImportToolData($_model, $data);

			Cache::write(
				'user_preview_ImportToolData_' . $this->logged['id'],
				$ImportToolData,
				'ImportTool'
			);
		}

		if ($this->request->is('post')) {
			$data = $this->request->data;
			
			if (empty($data['ImportTool']['checkAll']) && empty($data['ImportTool']['checked'])) {
				$this->Session->setFlash(__('You have to check at least one item to start the import. Please try again.'), FLASH_ERROR);
				$this->redirect(array('action' => 'preview'));
			}

			// if ($ImportToolData->isImportable()) {
				$ImportToolImport = new ImportToolImport($ImportToolData);
				// if (empty($data['ImportTool']['checkAll'])) {
					$ImportToolImport->setImportRows($data['ImportTool']['checked']);
				// }

				$saveData = $ImportToolImport->saveData($this->logged['id']);
				if ($saveData) {
					$this->Session->setFlash(__( 'Importing was successfully completed.'), FLASH_OK);
					$this->redirect(array(
						'plugin' => null,
						'controller' => controllerFromModel($model),
						'action' => 'index'
					));
				}
				else {
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			
			// }
			// else {
			// 	$this->Session->setFlash(__('CSV file data you are trying to import is corrupted or invalid. Please fix it first based on the preview statuses and try again.'), FLASH_ERROR);
			// }
		}
		
		$this->set('ImportToolData', $ImportToolData);
		$ImportToolData = null;
		$this->set('model', $model);
	}

}