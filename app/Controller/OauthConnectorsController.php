<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class OauthConnectorsController extends SectionBaseController
{
	use SectionCrudTrait;

	public $helpers = [];
	public $components = [
		'Search.Prg', 'Paginator',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete'],
			'modules' => ['comments', 'records', 'attachments']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'Comment',
						'Attachment' => [
							'fields' => ['id']
						]
					],
				]
			]
		]
	];

	public function beforeFilter()
	{
		$this->Crud->enable(['index', 'add', 'edit', 'delete']);

		parent::beforeFilter();

		$this->title = __('OAuth Connectors');
		$this->subTitle = __('This section allows you to manage OAuth connectors which you can use in this eramba application.');
	}

	public function add()
	{
		$this->title = __('Create an OAuth Connector');
		
		$this->setAddEditCommonData();

		return $this->Crud->execute();
	}

	public function edit( $id = null )
	{
		$this->title = __('Edit an OAuth Connector');

		$this->setAddEditCommonData();

		return $this->Crud->execute();
	}

	private function setAddEditCommonData()
	{
		$this->set('providers', $this->OauthConnector->providers());
		$this->set('redirectUrls', $this->OauthGoogleAuth->getRedirectUrls());
	}

	public function delete($id = null)
	{
		$this->Crud->on('beforeDelete', [$this, '_beforeDelete']);

		return $this->Crud->execute();
	}

	public function _beforeDelete(CakeEvent $event)
	{
		$success = $this->OauthConnector->prepareDelete($event->subject->id);
		$this->Crud->action('delete')->config('messages', [
			'error' => [
				'text' => $this->OauthConnector->getErrorMessage(),
				'element' => FLASH_ERROR
			]
		]);

		if (!$success) {
			$event->stopPropagation();
		}
	}

}
