<?php
App::uses('SectionBaseController', 'Controller');

class SystemLogsController extends SectionBaseController {

	public $components = [
		'Paginator', 'Search.Prg', 'AdvancedFilters',
		'Crud.Crud' => [
			'actions' => [
				'index' => [
				],
			],
		],
	];
	public $helpers = [];

	public $uses = ['SystemLogs.SystemLog'];

	public function beforeFilter() {
		$this->Crud->enable(['index']);

		parent::beforeFilter();

		$this->title = __('System Logs');
		$this->subTitle = __('');
	}

	public function index($model = null) {
		if (empty($model)) {
			throw new NotFoundException();
		}

		$this->SystemLog->adaptFilters($model);

		$this->Paginator->settings['conditions'] = [
			'SystemLogs.model' => $model
		];

		return $this->Crud->execute();
	}

}