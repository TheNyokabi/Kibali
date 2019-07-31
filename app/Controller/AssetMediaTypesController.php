<?php
class AssetMediaTypesController extends AppController {
	public $name = 'AssetMediaTypes';
	public $components = array('Paginator', 'Ajax' => array(
		'actions' => array('index', 'add', 'delete'),
		'redirects' => array(
			'index' => array(
				'url' => array('controller' => 'assets', 'action' => 'index')
			)
		)
	));

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Security->unlockedActions = array('liveEdit');
	}

	public function index() {
		$this->set('title_for_layout', __('Asset Types' ));
		$this->set('subtitle_for_layout', __('Asset types lists'));

		$this->Paginator->settings = array(
			'order' => array( 'AssetMediaType.id' => 'ASC' ),
			'limit' => $this->getPageLimit()
		);

		$data = $this->Paginator->paginate( 'AssetMediaType' );
		$this->set('data', $data);
	}

	/**
	 * 	Live uprava zaznamov na indexe
	 *
	 */
	public function liveEdit(){
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$allowedFields = array('name');
		if(!empty($this->request->data) && in_array($this->request->data['name'], $allowedFields)){

			$this->AssetMediaType->create();
			$this->AssetMediaType->id = (int)$this->request->data['pk'];
			$this->AssetMediaType->set(array($this->request->data['name'] => $this->request->data['value']));

			if($this->AssetMediaType->save(null, true, array($this->request->data['name']))){
				$newValue = '';
				echo json_encode(array('success' => true, 'newValue' => html_entity_decode($newValue) ));
			}
			else{
				echo json_encode(array('success' => false, 'msg' => $this->AssetMediaType->validationErrors[$this->request->data['name']][0]));
			}
		}
		else{
			echo json_encode(array('success' => false, 'msg' => __('Chyba pri uložení')));
		}
	}

	public function add() {
		$this->set( 'title_for_layout', __('Create a Asset Type') );
		$this->set( 'subtitle_for_layout', __( '' ) );

		if (!empty($this->request->data)) {
			unset($this->request->data['AssetMediaType']['id']);
			$this->request->data['AssetMediaType']['editable'] = 1;

			$this->AssetMediaType->set($this->request->data);

			if ($this->AssetMediaType->validates()) {
				if ($this->AssetMediaType->save()) {
					$this->Session->setFlash(__('Asset type was successfully added.'), FLASH_OK);
					$this->redirect(array('controller' => 'assetMediaTypes', 'action' => 'index'));
				}
				else {
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			}
		}
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Asset Media Type'));
		$this->set('subtitle_for_layout', __('Delete a Asset Media Type.'));
		$id = (int) $id;

		$data = $this->AssetMediaType->find('first', array(
			'conditions' => array(
				'AssetMediaType.id' => $id,
			),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->AssetMediaType->delete($id)) {
				$this->Session->setFlash(__('Asset type was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->redirect(array('controller' => 'assetMediaTypes', 'action' => 'index'));
		}
		else {
			$this->request->data = $data;
		}
	}
}