<?php
class RiskCalculationsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session', 'Paginator', 'Ajax' => array(
		'actions' => array('edit'),
		'redirects' => array(
			'index' => array(
				'url' => array('controller' => 'risks', 'action' => 'index')
			)
		)
	));

	public function warning() {
		$this->set('title_for_layout', __('Calculation Warning'));
		$this->set('showHeader', true);

		$this->set('changes', $this->Session->read('RiskCalculation.changes'));
		$this->set('warning', __('If you proceed the attribute Risk Score for all your current Risks will set to zero until they get classified again'));

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->request->data = $this->Session->read('RiskCalculation.formData');
			$model = $this->Session->read('RiskCalculation.model');

			$this->Session->write('RiskCalculation.warning', true);
			$this->Session->delete('RiskCalculation.formData');

			return $this->edit($model);
		}
	}

	private function handleWarnings($model, $originalFormData) {
		$changes = $this->RiskCalculation->checkChanges();
		// debug($changes);
		// check session if warning wasnt proceeded before already
		if (!empty($changes) && !$this->Session->check('RiskCalculation.warning')) {
			$this->Session->write('RiskCalculation.formData', $originalFormData);
			$this->Session->write('RiskCalculation.model', $model);
			$this->Session->write('RiskCalculation.changes', $changes);
			$this->Session->write('RiskCalculation.warningType', 'zero-risk-score');

			return $this->redirect(array('action' => 'warning'));
			exit;
		}
		else {
			$changes = $this->Session->read('RiskCalculation.changes');
			// remove session
			$this->Session->delete('RiskCalculation');

			return $changes;
		}

		return true;
	}

	public function edit($model) {
		$data = $this->RiskCalculation->find( 'first', array(
			'conditions' => array(
				'RiskCalculation.model' => $model
			),
			'recursive' => 1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}
		$this->Ajax->processEdit($data['RiskCalculation']['id']);

		$this->set('model', $model);
		$this->set('methods', $this->RiskCalculation->methods);
		$this->set('availableMethods', $this->RiskCalculation->calcRules[$model]);
		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Risk Calculation' ) );
		$this->initAddEditSubtitle();

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
			$originalFormData = $this->request->data;

			$methods = ['eramba', 'erambaMultiply'];

			foreach ($methods as $method) {
				if (!empty($this->request->data['RiskCalculationValue'][$method])) {
					$this->request->data['RiskCalculationValue'][$method];
					foreach ($this->request->data['RiskCalculationValue'][$method] as $key => $value) {
						if (empty($value)) {
							unset($this->request->data['RiskCalculationValue'][$method][$key]);
							continue;
						}

						$this->request->data['RiskCalculationValue'][$method][$key] = array(
							'field' => 'default',
							'value' => $value
						);
					}
				}
			}

			if (!empty($this->request->data['RiskCalculation']['method'])) {
				$this->request->data['RiskCalculationValue'] = $this->request->data['RiskCalculationValue'][$this->request->data['RiskCalculation']['method']];

				if (in_array($this->request->data['RiskCalculation']['method'], ['eramba', 'erambaMultiply'])) {
					if (!empty($this->request->data['RiskCalculationValue'])) {
						/*foreach ($this->request->data['RiskCalculationValue'] as $key => $value) {
							if (empty($value)) {
								unset($this->request->data['RiskCalculationValue'][$key]);
								continue;
							}

							$this->request->data['RiskCalculationValue'][$key] = array(
								'field' => 'default',
								'value' => $value
							);
						}*/
					}
					else {
						$this->request->data['RiskCalculationValue'] = array();
					}
				}
			}
			else {
				// $this->request->data['RiskCalculationValue'] = array();
			}

			$this->request->data['RiskCalculation']['model'] = $model;
			$this->request->data['RiskCalculation']['id'] = $data['RiskCalculation']['id'];

			$this->RiskCalculation->set( $this->request->data );
			$validates = $this->RiskCalculation->saveAll($this->request->data, array('validate' => 'only'));

			if ($validates) {
				
				$warnings = $this->handleWarnings($model, $originalFormData);

				// $this->RiskCalculation->RiskCalculationValue = $this->request->data['RiskCalculationValue'][$this->request->data['RiskCalculation']['method']];

				$dataSource = $this->RiskCalculation->getDataSource();
				$dataSource->begin();

				$ret = $this->RiskCalculation->RiskCalculationValue->deleteAll(array(
					'RiskCalculationValue.risk_calculation_id' => $data['RiskCalculation']['id']
				));
				$ret &= $this->RiskCalculation->saveAssociated();

				$this->loadModel($model);
				$this->{$model}->alterQueries(true);
				$ids = $this->{$model}->find('list', array(
					'fields' => array('id', 'id'),
					'recursive' => -1
				));

				// debug($ids);exit;
				
				if (!empty($warnings) /*&& $warnings == 'zero-risk-score'*/) {
					$this->{$model}->alterQueries(true);
					$risks = $this->{$model}->find('all', array(
						'conditions' => array(
							$model . '.id' => $ids
						),
						'recursive' => -1
					));
					$this->{$model}->alterQueries();
					foreach ($risks as $risk) {
						$riskScore = 0;
						$residualRisk = 0;//getResidualRisk($risk[$model]['residual_score'], $riskScore);

						$saveData = array(
							'risk_score' => $riskScore,
							'residual_risk' => $residualRisk
						);

						$this->{$model}->id = $risk[$model]['id'];
						$ret &= (bool) $this->{$model}->save($saveData, false);
						$ret &= $this->{$model}->triggerStatus('riskAboveAppetite');

						if (in_array('method', $warnings)) {
							$ret &= $this->{$model}->quickLogSave($risk[$model]['id'], 2, __('Risk Calculation has been updated from "%s" to "%s" method the Risk Score attribute for all Risks have been updated to zero until they get classified again',
								$this->RiskCalculation->methods[$data['RiskCalculation']['method']]['name'],
								$this->RiskCalculation->methods[$this->request->data['RiskCalculation']['method']]['name']));
						}
						else {
							$this->loadModel('RiskClassificationType');
							$previous = $this->RiskClassificationType->find('list', array(
								'conditions' => array(
									'RiskClassificationType.id' => $this->RiskCalculation->changesData['settings']['original']
								),
								'fields' => array('id', 'name'),
								'recursive' => -1
							));

							$new = $this->RiskClassificationType->find('list', array(
								'conditions' => array(
									'RiskClassificationType.id' => $this->RiskCalculation->changesData['settings']['request']
								),
								'fields' => array('id', 'name'),
								'recursive' => -1
							));

							$ret &= $this->{$model}->quickLogSave($risk[$model]['id'], 2, __('Risk settings for the methodology "%1$s" have changed from %2$s to %3$s and therefore the Risk score for all Risk have been set to zero until they get classified again',
								$this->RiskCalculation->methods[$this->request->data['RiskCalculation']['method']]['name'],
								implode(', ', $previous),
								implode(', ', $new)
							));
						}
					}
				}
				else {
					$ret &= $this->{$model}->calculateAndSaveRiskScoreById($ids);
					foreach ($ids as $risk_id) {
						$ret &= $this->{$model}->quickLogSave($risk_id, 2, __('Calculation method changed to %s - Risk Scores re-calculated', $this->RiskCalculation->methods[$this->request->data['RiskCalculation']['method']]['name']));
					}
				}
				
				// $ret &= $this->RiskClassification->resaveScores($this->RiskClassification->id);

				if ($ret) {
					$dataSource->commit();
					$this->Ajax->success();

					$this->Session->setFlash( __( 'Risk Calculation was successfully updated.' ), FLASH_OK );
					// $this->redirect( array( 'controller' => 'riskClassifications', 'action' => 'index' ) );
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			}
			else {
				if (!empty($this->request->data['RiskCalculation']['method'])) {
					$this->request->data['RiskCalculationValue'][$this->request->data['RiskCalculation']['method']] = $this->request->data['RiskCalculationValue'];
				}
// debug($this->RiskCalculation->validationErrors);
				if (!empty($this->RiskCalculation->RiskCalculationValue->validationErrors) && !empty($this->request->data['RiskCalculation']['method'])) {
					$this->RiskCalculation->RiskCalculationValue->validationErrors = array($this->request->data['RiskCalculation']['method'] => $this->RiskCalculation->RiskCalculationValue->validationErrors);
				}

				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}
		else {
			$tmpValues = $data['RiskCalculationValue'];
			// unset($data['RiskCalculationValue']);
			$data['RiskCalculationValue'][$data['RiskCalculation']['method']] = $data['RiskCalculationValue'];
			// debug($data);
			$this->request->data = $data;

		}
		// debug($data);
		$this->initOptions();
		// $this->render( 'add' );
	}

	private function initAddEditSubtitle() {
		$this->set( 'subtitle_for_layout', '' );
	}

	private function initOptions() {
		$this->loadModel('RiskClassificationType');
		$types = $this->RiskClassificationType->find('list', array(
			'fields' => array('id', 'name'),
			'recursive' => -1
		));

		$typesNotEmpty = $this->RiskClassificationType->find('list', array(
			'conditions' => array(
				'risk_classification_count >=' => 1
			),
			'fields' => array('id', 'name'),
			'recursive' => 0
		));

		$this->set('riskClassificationTypes', $types);
		$this->set('riskClassificationTypesNotEmpty', $typesNotEmpty);
	}

}
