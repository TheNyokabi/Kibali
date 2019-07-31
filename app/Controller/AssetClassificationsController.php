<?php
App::uses('SectionBaseController', 'Controller');

class AssetClassificationsController extends SectionBaseController {
	public $helpers = [];
	public $components = [
		'Paginator', 
		'Ajax' => [
			'actions' => ['index', 'add', 'edit', 'delete'],
			'redirects' => [
				'index' => [
					'url' => ['controller' => 'assets', 'action' => 'index']
				]
			]
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'AssetClassificationType'
					]
				],
				'add' => [
					'saveMethod' => 'saveAssociated'
				],
				'edit' => [
					'saveMethod' => 'saveAssociated'
				],
				'delete' => [
					'view' => 'delete'
				],
			]
		],
	];

	public function beforeFilter() {

		$this->Auth->allow('index');
		
		$this->Ajax->settings['modules'] = ['comments', 'records', 'attachments'];

		$this->Crud->enable(['index', 'add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('Asset Classifications');
		$this->subTitle = __('Asset classification is a common good practice and a requirement in certain standards such as ISO 27001 The classification must be defined according to business needs. Once the classification has been defined, it can be applied on every identified asset.');
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete an Asset Classification.');

		$this->set('isUsed', $this->AssetClassification->isUsed($id));

		return $this->Crud->execute();
	}

	public function add() {
		$this->title = __('Create an Asset Classification');
		$this->initAddEditSubtitle();

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit an Asset Classification');
		$this->initAddEditSubtitle();

		$this->set('isUsed', $this->AssetClassification->isUsed($id));

		return $this->Crud->execute();
	}

	public function addClassificationType() {
		$this->AssetClassification->AssetClassificationType->set($this->request->data);

		if ($this->AssetClassification->AssetClassificationType->validates()) {

			$dataSource = $this->AssetClassification->AssetClassificationType->getDataSource();
			$dataSource->begin();

			if ($this->AssetClassification->AssetClassificationType->save()) {
				$dataSource->commit();
				$this->Ajax->success();

				$this->Session->setFlash( __( 'Asset Classification Type was successfully added.' ), FLASH_OK );
				// $this->redirect( array( 'controller' => 'assetClassifications', 'action' => 'index' ) );
			}
			else {
				$dataSource->rollback();
				$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
			}
		}
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Asset classification is a common good practice and a requirement in certain standards such as ISO 27001:2005. The classification must be defined according to business needs. Once the classification has been defined, it can be applied on every identified asset.');
	}
}
