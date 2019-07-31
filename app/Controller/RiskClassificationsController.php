<?php
class RiskClassificationsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session', 'Paginator', 'Ajax' => array(
		'actions' => array('index', 'add', 'edit', 'delete'),
		'redirects' => array(
			'index' => array(
				'url' => array('controller' => 'risks', 'action' => 'index')
			)
		)
	));

	public function index() {
		$this->set( 'title_for_layout', __( 'Risk Classification' ) );
		$this->set( 'subtitle_for_layout', __( 'You will apply this classification to your Risks.' ) );

		$this->paginate = array(
			'fields' => array(
				'RiskClassification.id',
				'RiskClassification.name',
				'RiskClassification.criteria',
				'RiskClassification.value',
				'RiskClassificationType.name'
			),
			'order' => array('RiskClassification.id' => 'ASC'),
			'limit' => $this->getPageLimit(),
			'recursive' => 1
		);

		$data = $this->paginate( 'RiskClassification' );
		$this->set( 'data', $data );
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Risk Classification'));
		$this->set('subtitle_for_layout', __('Delete a Risk Classification.'));

		$data = $this->RiskClassification->find('first', array(
			'conditions' => array(
				'RiskClassification.id' => $id
			),
			'fields' => array('id', 'name'),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->set('isUsed', $this->RiskClassification->isUsed($id));
		$this->set('calculationRestrictDelete', $this->RiskClassification->calculationRestrictDelete($id));
		// $this->set('usedByCalculationMethods', );

		if ($this->request->is('post') || $this->request->is('put')) {
			$dataSource = $this->RiskClassification->getDataSource();
			$dataSource->begin();

			$ret = $this->RiskClassification->delete($id);

			if ($ret) {
				$dataSource->commit();

				$this->Session->setFlash(__('Risk Classification was successfully deleted.'), FLASH_OK);
			}
			else {
				$dataSource->rollback();
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->redirect(array('controller' => 'riskClassifications', 'action' => 'index'));
		}
		else {
			$this->request->data = $data;
		}
	}

	public function add() {
		$this->set( 'title_for_layout', __( 'Create a Risk Classification' ) );
		$this->initAddEditSubtitle();

		if ( $this->request->is( 'post' ) ) {
			unset( $this->request->data['RiskClassification']['id'] );

			$this->processSubmit( __( 'Risk Classification was successfully added.' ) );
		}

		$this->initOptions();
	}

	public function edit( $id = null ) {
		if (isset($this->request->data['__warning_proceeded']) && !empty($this->request->data['__warning_proceeded'])) {
			$this->request->data = $this->Session->read('RiskClassificationWarningFormData');
			$this->request->data['__warning_proceeded'] = true;
			$this->Session->delete('RiskClassificationWarningFormData');
		}
		$id = (int) $id;

		if ( ! empty( $this->request->data ) ) {
			$id = (int) $this->request->data['RiskClassification']['id'];
		}

		$data = $this->RiskClassification->find( 'first', array(
			'conditions' => array(
				'RiskClassification.id' => $id
			),
			'recursive' => 0
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}
		$this->set('isUsed', $isUsed = $this->RiskClassification->isUsed($id));
		$this->Ajax->processEdit($id);

		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Risk Classification' ) );
		$this->initAddEditSubtitle();

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
			$type = $data['RiskClassificationType']['name'];
			$name = $data['RiskClassification']['name'];
			$value = $data['RiskClassification']['value'];
			$newValue = $this->request->data['RiskClassification']['value'];
// debug($value);debug($newValue);
			if ($value != $newValue) {
				if (empty($this->request->data['__warning_proceeded'])) {
					$this->Session->write('RiskClassificationWarningFormData', $this->request->data);
					$this->set('warning', __('This classification is used by %d risks, if you proceed we will update the risk score for this risks.', $isUsed));
					$this->set('classificationId', $id);

					return $this->render('warning');
				}
				else {
					
				}

				$args = array($type, $name, $value, $newValue);
				$msg = __('The classification "%1$s - %2$s - %3$s" applied on this risk has got its value updated to "%4$s" and therefore this risk has been updated from %5$s to %6$s');
				$this->RiskClassification->addRiskScoreEvent($msg, $args);
			}
			
			$this->processSubmit( __( 'Risk Classification was successfully edited.' ) );

			$this->RiskClassification->removeRiskScoreListener();
		}
		else {
			$this->request->data = $data;
		}

		$this->initOptions();
		$this->render( 'add' );
	}

	private function processSubmit( $flashMessage = '' ) {
		if ( $this->request->data['RiskClassification']['risk_classification_type_id'] == '' ) {

			$this->RiskClassification->set( $this->request->data );
			$this->RiskClassification->RiskClassificationType->set( $this->request->data );

			$validates = $this->RiskClassification->RiskClassificationType->validates();
			$validates &= $this->RiskClassification->validates();
			if ($validates) {

				$dataSource = $this->RiskClassification->getDataSource();
				$dataSource->begin();

				$ret = $this->RiskClassification->RiskClassificationType->save();

				$this->request->data['RiskClassification']['risk_classification_type_id'] = $this->RiskClassification->RiskClassificationType->id;
				$this->RiskClassification->set( $this->request->data );

				$ret &= $this->RiskClassification->save();

				$ret &= $this->RiskClassification->resaveScores($this->RiskClassification->id);

				if ($ret) {
					$dataSource->commit();
					$this->Ajax->success();
					
					$this->Session->setFlash( $flashMessage, FLASH_OK );
					// $this->redirect( array( 'controller' => 'riskClassifications', 'action' => 'index' ) );
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
			$this->RiskClassification->set( $this->request->data );

			if ( $this->RiskClassification->validates() ) {
				$dataSource = $this->RiskClassification->getDataSource();
				$dataSource->begin();

				$ret = $this->RiskClassification->save();

				$ret &= $this->RiskClassification->resaveScores($this->RiskClassification->id);

				if ($ret) {
					$dataSource->commit();
					$this->Ajax->success();

					$this->Session->setFlash( __( 'Risk Classification was successfully added.' ), FLASH_OK );
					// $this->redirect( array( 'controller' => 'riskClassifications', 'action' => 'index' ) );
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
		$this->RiskClassification->RiskClassificationType->set($this->request->data);

		if ($this->RiskClassification->RiskClassificationType->validates()) {

			$dataSource = $this->RiskClassification->RiskClassificationType->getDataSource();
			$dataSource->begin();

			if ($this->RiskClassification->RiskClassificationType->save()) {
				$dataSource->commit();
				$this->Ajax->success();

				$this->Session->setFlash( __( 'Risk Classification Type was successfully added.' ), FLASH_OK );
			}
			else {
				$dataSource->rollback();
				$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
			}
		}
	}

	private function initAddEditSubtitle() {
		$this->set( 'subtitle_for_layout', __( 'Usually there\'s many assets around in a organization. Trough classification (according to your needs) you will be able to set priorities and profile them in a way their treatment and handling is systematic. Btw, this is a basic requirement for most Security related regulations.' ) );
	}

	private function initOptions() {
		$types = $this->RiskClassification->RiskClassificationType->find('list', array(
			'order' => array('RiskClassificationType.name' => 'ASC'),
			'recursive' => -1
		));

		$this->set( 'types', $types );
	}

}
