<?php
class ScopesController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session' );

	public function index() {
		$this->set('title_for_layout', __('System Roles'));
		$this->set('subtitle_for_layout', __('System wide roles used for workflows and notifications.'));

		$this->paginate = array(
			'order' => array('Scope.id' => 'ASC'),
			'limit' => $this->getPageLimit(),
			'recursive' => 0
		);

		$data = $this->paginate('Scope');
		$this->set('data', $data);
	}

	public function delete( $id = null ) {
		$this->set('title_for_layout', __('Scope'));
		$this->set('subtitle_for_layout', __('Delete a Scope.'));
		$id = (int) $id;

		$data = $this->Scope->find( 'first', array(
			'conditions' => array(
				'Scope.id' => $id
			),
			'recursive' => -1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Scope->delete($id)) {
				$this->Session->setFlash(__('Scope was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->redirect(array('controller' => 'scopes', 'action' => 'index'));
		}
		else {
			$this->request->data = $data;
		}

	}

	public function add() {
		$this->set( 'title_for_layout', __( 'Define Sytem Roles' ) );
		$this->initAddEditSubtitle();
		$this->initOptions();

		if ( $this->request->is( 'post' ) ) {
			unset( $this->request->data['Scope']['id'] );

			$this->Scope->set( $this->request->data );

			if ( $this->Scope->validates() ) {
				if ( $this->Scope->save() ) {
					$this->Session->setFlash( __( 'Scope was successfully added.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'scopes', 'action' => 'index' ) );
				} else {
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			} else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}
	}

	public function edit( $id = null ) {
		$id = (int) $id;

		if ( ! empty( $this->request->data ) ) {
			$id = (int) $this->request->data['Scope']['id'];
		}

		$data = $this->Scope->find( 'first', array(
			'conditions' => array(
				'Scope.id' => $id
			),
			'recursive' => -1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Scope' ) );
		$this->initAddEditSubtitle();
		$this->initOptions();

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {

			$this->Scope->set( $this->request->data );

			if ( $this->Scope->validates() ) {
				if ( $this->Scope->save() ) {
					$this->Session->setFlash( __( 'Scope was successfully edited.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'scopes', 'action' => 'index', $id ) );
				}
				else {
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			} else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}
		else {
			$this->request->data = $data;
		}

		$this->render( 'add' );
	}

	private function initOptions() {
		$this->set('users', $this->getUsersList());
	}

	private function initAddEditSubtitle() {
		$this->set( 'subtitle_for_layout', __( 'System roles are used across the entire system to define workflow and notification settings.' ) );
	}

}
