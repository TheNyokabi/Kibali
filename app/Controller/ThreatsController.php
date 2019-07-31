<?php
class ThreatsController extends AppController {
	public $name = 'Threats';
	public $components = array('Paginator', 'Ajax' => array(
		'actions' => array('index', 'add', 'delete')
	));

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Security->unlockedActions = array('liveEdit');
	}

	public function index() {
		$this->set('title_for_layout', __('Threats' ));
		$this->set('subtitle_for_layout', __('Threats lists'));

		$this->Paginator->settings = array(
			'order' => array( 'Threat.name' => 'ASC' ),
			'limit' => $this->getPageLimit()
		);

		$data = $this->Paginator->paginate( 'Threat' );
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

			$this->Threat->create();
			$this->Threat->id = (int)$this->request->data['pk'];
			$this->Threat->set(array($this->request->data['name'] => $this->request->data['value']));

			if($this->Threat->save(null, true, array($this->request->data['name']))){
				$newValue = '';
				echo json_encode(array('success' => true, 'newValue' => html_entity_decode($newValue) ));
			}
			else{
				echo json_encode(array('success' => false, 'msg' => $this->Threat->validationErrors[$this->request->data['name']][0]));
			}
		}
		else{
			echo json_encode(array('success' => false, 'msg' => __('Chyba pri uložení')));
		}
	}

	public function add() {
		$this->set( 'title_for_layout', __('Create a Threat') );
		$this->set( 'subtitle_for_layout', __( '' ) );

		if (!empty($this->request->data)) {
			unset($this->request->data['Threat']['id']);

			$this->Threat->set($this->request->data);

			if ($this->Threat->validates()) {
				if ($this->Threat->save()) {
					$this->Ajax->success();

					$this->Session->setFlash(__('Threat was successfully added.'), FLASH_OK);
					// $this->redirect(array('controller' => 'threats', 'action' => 'index'));
				}
				else {
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			}
		}
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Threat'));
		$this->set('subtitle_for_layout', __('Delete a Threat.'));
		$id = (int) $id;

		$data = $this->Threat->find('first', array(
			'conditions' => array(
				'Threat.id' => $id,
			),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Threat->delete($id)) {
				$this->Session->setFlash(__('Threat was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->redirect(array('controller' => 'threats', 'action' => 'index'));
		}
		else {
			$this->request->data = $data;
		}
	}
}