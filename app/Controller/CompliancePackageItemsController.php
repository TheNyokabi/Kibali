<?php
class CompliancePackageItemsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array('Session', 'Paginator', 'AdvancedFilters', 'Ajax' => array(
		'actions' => array(/*'add', 'edit', */'delete'),
		'modules' => array('comments', 'records', 'attachments', 'notifications')
	));

	public function index() {

		return $this->redirect(array(
			'controller' => 'compliancePackages',
			'action' => 'index'
		));
		// @todo leving for next release to include advanced filters for compliance packages section
		$this->set('title_for_layout', __('Compliance Package Items'));
		$this->set('subtitle_for_layout', __('List of Compliance Package Items.'));

		if ($this->AdvancedFilters->filter('CompliancePackageItem')) {
			return;
		}

	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Compliance Package Items'));
		$this->set('subtitle_for_layout', __('Delete a Compliance Package Item.'));

		$data = $this->CompliancePackageItem->find('first', array(
			'conditions' => array(
				'CompliancePackageItem.id' => $id
			),
			'fields' => array('id', 'name'),
			'recursive' => 1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CompliancePackageItem->delete($id)) {
				$this->Session->setFlash(__('Compliance Package Item was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->Ajax->success();
		}
		else {
		}
		$this->request->data = $data;

		// $this->set('data', $data);
	}

	public function add($cp_id = null) {
		$cp_id = (int) $cp_id;

		$this->set('title_for_layout', __('Create a Compliance Package Item'));
		$this->initAddEditSubtitle();

		$data = $this->CompliancePackageItem->CompliancePackage->find('first', array(
			'conditions' => array(
				'CompliancePackage.id' => $cp_id
			),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if ( $this->request->is( 'post' ) ) {
			unset($this->request->data['CompliancePackageItem']['id']);

			$this->CompliancePackageItem->set( $this->request->data );

			if ( $this->CompliancePackageItem->validates() ) {
				$dataSource = $this->CompliancePackageItem->getDataSource();
				$dataSource->begin();

				$ret = $this->CompliancePackageItem->save();
				$Compliance = ClassRegistry::init('ComplianceManagement');
				$ret &= $Compliance->syncObjects();

				if ($ret) {
					$dataSource->commit();

					$this->Session->setFlash( __( 'Compliance Package Item was successfully added.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'compliancePackages', 'action' => 'index' ) );
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			}
			else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}
		else {
			
		}

		$this->set( 'cp_id', $cp_id );
	}

	public function edit( $id = null ) {
		$id = (int) $id;

		if ( ! empty( $this->request->data ) ) {
			$id = (int) $this->request->data['CompliancePackageItem']['id'];
		}

		$data = $this->CompliancePackageItem->find('first', array(
			'conditions' => array(
				'CompliancePackageItem.id' => $id
			)
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Compliance Package Item' ) );
		$this->initAddEditSubtitle();
		$this->set('id', $id);

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {

			$this->CompliancePackageItem->set( $this->request->data );

			if ( $this->CompliancePackageItem->validates() ) {
				$dataSource = $this->CompliancePackageItem->getDataSource();
				$dataSource->begin();

				$ret = $this->CompliancePackageItem->save();

				if ($ret) {
					$dataSource->commit();
					
					$this->Session->setFlash( __( 'Compliance Package Item was successfully edited.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'compliancePackages', 'action' => 'index', $id ) );
				}
				else {
					$dataSource->rollback();
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

	private function initAddEditSubtitle() {
		$this->set( 'subtitle_for_layout', false );
	}

}
