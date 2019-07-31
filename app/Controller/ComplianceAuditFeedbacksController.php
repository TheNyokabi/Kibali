<?php
class ComplianceAuditFeedbacksController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session', 'Paginator', 'Ajax' => array(
		'actions' => array('index', 'add', 'edit', 'delete'),
		'redirects' => array(
			'index' => array(
				'url' => array('controller' => 'complianceAudits', 'action' => 'index')
			)
		)
	));

	public function index() {
		$this->set( 'title_for_layout', __( 'Compliance Audit Feedback' ) );
		$this->set( 'subtitle_for_layout', __( 'You will apply this classification to your Risks.' ) );

		$this->paginate = array(
			'order' => array('ComplianceAuditFeedback.id' => 'ASC'),
			'limit' => $this->getPageLimit(),
			'recursive' => 1
		);

		$data = $this->paginate( 'ComplianceAuditFeedback' );
		$this->set( 'data', $data );
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Compliance Audit Feedback'));
		$this->set('subtitle_for_layout', __('Delete a Compliance Audit Feedback.'));

		$data = $this->ComplianceAuditFeedback->find('first', array(
			'conditions' => array(
				'ComplianceAuditFeedback.id' => $id
			),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			$dataSource = $this->ComplianceAuditFeedback->getDataSource();
			$dataSource->begin();

			$ret = $this->ComplianceAuditFeedback->delete($id);

			if ($ret) {
				$dataSource->commit();

				$this->Session->setFlash(__('Compliance Audit Feedback was successfully deleted.'), FLASH_OK);
			}
			else {
				$dataSource->rollback();
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}
			
			$this->Ajax->success();
		}
		else {
			$this->request->data = $data;
		}

		$this->set('data', $data);
	}

	public function add() {
		$this->set( 'title_for_layout', __( 'Create a Compliance Audit Feedback' ) );
		$this->initAddEditSubtitle();

		if ( $this->request->is( 'post' ) ) {
			unset( $this->request->data['ComplianceAuditFeedback']['id'] );

			$this->processSubmit( __( 'Compliance Audit Feedback was successfully added.' ) );
		}

		$this->initOptions();
	}

	public function edit( $id = null ) {
		$id = (int) $id;

		if ( ! empty( $this->request->data ) ) {
			$id = (int) $this->request->data['ComplianceAuditFeedback']['id'];
		}

		$data = $this->ComplianceAuditFeedback->find( 'first', array(
			'conditions' => array(
				'ComplianceAuditFeedback.id' => $id
			),
			'recursive' => -1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}
		$this->Ajax->processEdit($id);

		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Compliance Audit Feedback' ) );
		$this->initAddEditSubtitle();

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
			$this->processSubmit( __( 'Compliance Audit Feedback was successfully edited.' ) );
		}
		else {
			$this->request->data = $data;
		}

		$this->initOptions();
		$this->render( 'add' );
	}

	private function processSubmit( $flashMessage = '' ) {
		if ( $this->request->data['ComplianceAuditFeedback']['compliance_audit_feedback_profile_id'] == '' ) {

			$this->ComplianceAuditFeedback->set( $this->request->data );
			$this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->set( $this->request->data );

			if ( $this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->validates() &&
				$this->ComplianceAuditFeedback->validates()	) {

				$dataSource = $this->ComplianceAuditFeedback->getDataSource();
				$dataSource->begin();

				$ret = $this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->save();

				$this->request->data['ComplianceAuditFeedback']['compliance_audit_feedback_profile_id'] = $this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->id;
				$this->ComplianceAuditFeedback->set( $this->request->data );

				$ret &= $this->ComplianceAuditFeedback->save();

				if ($ret) {
					$dataSource->commit();
					$this->Ajax->success();
					
					$this->Session->setFlash( $flashMessage, FLASH_OK );
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
			$this->ComplianceAuditFeedback->set( $this->request->data );

			if ( $this->ComplianceAuditFeedback->validates() ) {
				$dataSource = $this->ComplianceAuditFeedback->getDataSource();
				$dataSource->begin();

				$ret = $this->ComplianceAuditFeedback->save();

				if ($ret) {
					$dataSource->commit();
					$this->Ajax->success();

					$this->Session->setFlash( __( 'Compliance Audit Feedback was successfully added.' ), FLASH_OK );
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
	}

	public function addClassificationType() {
		$this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->set($this->request->data);

		if ($this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->validates()) {

			$dataSource = $this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->getDataSource();
			$dataSource->begin();

			if ($this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->save()) {
				$dataSource->commit();
				$this->Ajax->success();

				$this->Session->setFlash( __( 'Compliance Audit Feedback Profile was successfully added.' ), FLASH_OK );
			}
			else {
				$dataSource->rollback();
				$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
			}
		}
	}

	private function initAddEditSubtitle() {
		$this->set( 'subtitle_for_layout', __( '' ) );
	}

	private function initOptions() {
		$profiles = $this->ComplianceAuditFeedback->ComplianceAuditFeedbackProfile->find('list', array(
			'order' => array('ComplianceAuditFeedbackProfile.name' => 'ASC'),
			'recursive' => -1
		));

		$this->set( 'profiles', $profiles );
	}

}
