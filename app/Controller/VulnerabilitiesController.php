<?php
class VulnerabilitiesController extends AppController {
	public $name = 'Vulnerabilities';
	public $components = array('Paginator', 'Ajax' => array(
			'actions' => array('index', 'add', 'delete')
	));

	public function beforeFilter() {
		parent::beforeFilter();

		$this->Security->unlockedActions = array('liveEdit');
	}

	public function index() {
		$this->set('title_for_layout', __('Vulnerabilities' ));
		$this->set('subtitle_for_layout', __('Vulnerabilities lists'));

		$this->Paginator->settings = array(
			'order' => array( 'Vulnerability.name' => 'ASC' ),
			'limit' => $this->getPageLimit()
		);

		$data = $this->Paginator->paginate( 'Vulnerability' );
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

			$this->Vulnerability->create();
			$this->Vulnerability->id = (int)$this->request->data['pk'];
			$this->Vulnerability->set(array($this->request->data['name'] => $this->request->data['value']));

			if($this->Vulnerability->save(null, true, array($this->request->data['name']))){
				$newValue = '';
				echo json_encode(array('success' => true, 'newValue' => html_entity_decode($newValue) ));
			}
			else{
				echo json_encode(array('success' => false, 'msg' => $this->Vulnerability->validationErrors[$this->request->data['name']][0]));
			}
		}
		else{
			echo json_encode(array('success' => false, 'msg' => __('Chyba pri uložení')));
		}
	}

	public function add() {
		$this->set( 'title_for_layout', __('Create a Vulnerability') );
		$this->set( 'subtitle_for_layout', __( '' ) );

		if (!empty($this->request->data)) {
			unset($this->request->data['Vulnerability']['id']);

			$this->Vulnerability->set($this->request->data);

			if ($this->Vulnerability->validates()) {
				if ($this->Vulnerability->save()) {
					$this->Ajax->success();
					
					$this->Session->setFlash(__('Vulnerability was successfully added.'), FLASH_OK);
					// $this->redirect(array('controller' => 'vulnerabilities', 'action' => 'index'));
				}
				else {
					$this->Session->setFlash(__('Error while saving the data. Please try it again.'), FLASH_ERROR);
				}
			}
		}
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Vulnerability'));
		$this->set('subtitle_for_layout', __('Delete a Vulnerability.'));
		$id = (int) $id;

		$data = $this->Vulnerability->find('first', array(
			'conditions' => array(
				'Vulnerability.id' => $id,
			),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Vulnerability->delete($id)) {
				$this->Session->setFlash(__('Vulnerability was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->redirect(array('controller' => 'vulnerabilities', 'action' => 'index'));
		}
		else {
			$this->request->data = $data;
		}
	}
}