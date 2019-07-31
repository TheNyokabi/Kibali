<?php
App::uses('RiskCalculation', 'Model');

class RiskClassification extends AppModel {
	const TYPE_ANALYSIS = 0;
	const TYPE_TREATMENT = 1;
	
	public $actsAs = array(
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'criteria'
			)
		),
		'Containable'
	);

	public $mapping = array(
		'titleColumn' => 'name',
		'logRecords' => true,
		'workflow' => false
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true
		),
		'value' => array(
			'rule' => 'numeric'
		)
	);

	public $belongsTo = array(
		'RiskClassificationType' => array(
			'counterCache' => true
		)
	);

	public $hasAndBelongsToMany = array(
		'Risk',
		'ThirdPartyRisk',
		'BusinessContinuity'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'RiskClassification'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'RiskClassification'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'RiskClassification'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Risk Classifications');
		
		parent::__construct($id, $table, $ds);
	}

	public function beforeSave($options = array()) {
		if (isset($this->data['RiskClassification']['value']) && is_numeric($this->data['RiskClassification']['value'])) {
			$this->data['RiskClassification']['value'] = CakeNumber::precision($this->data['RiskClassification']['value'], 2);
		}

		return true;
	}

	public function beforeDelete($cascade = true) {
		$ret = true;

		// if (!$ret &= $this->calculationAllowDelete($this->id)) {
		// }
		
		if (!empty($this->id)) {
			$this->relatedRisks = $this->getRelatedRisks($this->id);
			$data = $this->find('first', array(
				'conditions' => array(
					'RiskClassification.id' => $this->id
				),
				'recursive' => 0
			));
			$type = $data['RiskClassificationType']['name'];
			$name = $data['RiskClassification']['name'];
			$value = $data['RiskClassification']['value'];

			$args = array($type, $name, $value);
			$msg = __('Since the classification "%1$s - %2$s - %3$s" used by this Risk was deleted, the original Risk Score %4$s for this risk has been set to zero until the Risk is classified again');

			$this->addRiskScoreEvent($msg, $args);
		}

		return $ret;
	}

	public function afterDelete() {
		$ret = true;

		// delete empty classification types
		$data = $this->RiskClassificationType->find('list', array(
			'conditions' => array(
				'RiskClassificationType.risk_classification_count' => 0
			),
			'fields' => array('id'),
			'recursive' => -1
		));

		$ret &= $this->RiskClassificationType->deleteAll(array(
			'RiskClassificationType.id' => $data
		));

		// trigger risk status checks
		
		if (isset($this->relatedRisks)) {
			$ret &= $this->resaveRelatedRisksScore($this->relatedRisks, true);
			$this->removeRiskScoreListener();

			/*$riskIds = $this->relatedRisks['riskIds'];
			$tpRiskIds = $this->relatedRisks['tpRiskIds'];
			$bcRiskIds = $this->relatedRisks['bcRiskIds'];

			$msg = __('Since the classification (type) (name) (value)  used by this Risk was deleted, the original Risk Score (old risk score) for this risk has been set to zero until the Risk is classified again');
			$ret &= $this->Risk->quickLogSave($riskIds, 2, $msg);
			$ret &= $this->ThirdPartyRisk->quickLogSave($tpRiskIds, 2, $msg);
			$ret &= $this->BusinessContinuity->quickLogSave($bcRiskIds, 2, $msg);

			$saveData = array(
				'risk_score' => 0,
				'residual_risk' => 0
			);

			$ret &= $this->Risk->updateAll($saveData, array(
				'Risk.id' => $riskIds
			));
			$ret &= $this->ThirdPartyRisk->updateAll($saveData, array(
				'ThirdPartyRisk.id' => $tpRiskIds
			));
			$ret &= $this->BusinessContinuity->updateAll($saveData, array(
				'BusinessContinuity.id' => $bcRiskIds
			));

			$ret &= $this->Risk->triggerStatus('riskAboveAppetite', $riskIds);
			$ret &= $this->ThirdPartyRisk->triggerStatus('riskAboveAppetite', $tpRiskIds);
			$ret &= $this->BusinessContinuity->triggerStatus('riskAboveAppetite', $bcRiskIds);*/

			// $ret &= $this->{$model}->triggerStatuses($this->relatedRisks);
		}

		return $ret;
	}

	/**
	 * Read appetite threshold data.
	 */
	public function getRiskAppetiteThreshold($ids = []) {
		$RiskAppetite = ClassRegistry::init('RiskAppetite');
		$appetiteMethod = $RiskAppetite->getCurrentType();

		$data = $RiskAppetite->RiskAppetiteThreshold->getThreshold($ids);
		if ($data) {
			return $data['RiskAppetiteThreshold'];
		}
		
		return null;
	}

	/**
	 * Get risk criteria descriptions.
	 */
	public function getRiskCriteria($ids = array()) {
		$data = $this->find('all', array(
			'conditions' => array(
				'RiskClassification.id' => $ids
			),
			'fields' => array(
				'RiskClassification.id',
				'RiskClassification.name',
				'RiskClassification.criteria',
				'RiskClassificationType.name'
			),
			'recursive' => 0
		));

		$list = array();
		foreach ($data as $item) {
			$str = sprintf(
				'%s / %s: %s',
				$item['RiskClassificationType']['name'],
				$item['RiskClassification']['name'],
				$item['RiskClassification']['criteria']
			);

			$id = $item['RiskClassification']['id'];
			$list[$id] = $str;
		}

		return $list;
	}

	public function addRiskScoreEvent($msg, $args) {
		$this->Risk->logAfterRiskScoreChange = array(
			'message' => $msg,
			'args' => $args
		);
		$this->ThirdPartyRisk->logAfterRiskScoreChange = array(
			'message' => $msg,
			'args' => $args
		);
		$this->BusinessContinuity->logAfterRiskScoreChange = array(
			'message' => $msg,
			'args' => $args
		);
	}

	public function removeRiskScoreListener() {
		$this->Risk->logAfterRiskScoreChange = false;
		$this->ThirdPartyRisk->logAfterRiskScoreChange = false;
		$this->BusinessContinuity->logAfterRiskScoreChange = false;
	}

	public function calculationRestrictDelete($id) {
		$classification = $this->find('first', array(
			'conditions' => array(
				'RiskClassification.id' => $id
			),
			'fields' => array(
				'RiskClassification.risk_classification_type_id',
				'RiskClassificationType.risk_classification_count',
				'RiskClassificationType.name'
			),
			'recursive' => 0
		));

		$models = array('Risk', 'ThirdPartyRisk', 'BusinessContinuity');
		foreach ($models as $model) {
			$method = $this->{$model}->getMethod();

			$count = $this->{$model}->RiskCalculation->RiskCalculationValue->find('count', array(
				'conditions' => array(
					'RiskCalculation.method' => $method,
					'RiskCalculationValue.value' => $classification['RiskClassification']['risk_classification_type_id']
				)
			));

			if ($count && $classification['RiskClassificationType']['risk_classification_count'] == 1) {
				return array(
					'method' => $method,
					'classificationType' => $classification['RiskClassificationType']['name']
				);
			}
		}

		return false;
	}

	// private function validateCalculations() {
	// 	$ret = $this->Risk->
	// }

	private function triggerStatuses($relatedRisks) {
		$ret = true;
		
		$riskIds = $relatedRisks['riskIds'];
		$tpRiskIds = $relatedRisks['tpRiskIds'];
		$bcRiskIds = $relatedRisks['bcRiskIds'];

		$settings = array(
			'disableToggles' => array('default'),
			'customToggles' => array('RiskClassification')
		);

		$ret &= $this->Risk->triggerStatus('riskAboveAppetite', $riskIds, null, $settings);
		$ret &= $this->ThirdPartyRisk->triggerStatus('riskAboveAppetite', $tpRiskIds, null, $settings);
		$ret &= $this->BusinessContinuity->triggerStatus('riskAboveAppetite', $bcRiskIds, null, $settings);
	

		return $ret;
	}

	public function getRelatedRisks($classificationId) {
		$data = $this->find('all', array(
			'conditions' => array(
				'RiskClassification.id' => $classificationId
			),
			'contain' => array(
				'Risk' => array(
					'fields' => array('id')
				),
				'ThirdPartyRisk' => array(
					'fields' => array('id')
				),
				'BusinessContinuity' => array(
					'fields' => array('id')
				)
			)
		));

		$riskIds = $tpRiskIds = $bcRiskIds = array();
		foreach ($data as $rc) {
			foreach ($rc['Risk'] as $risk) {
				$riskIds[] = $risk['id'];
			}
			foreach ($rc['ThirdPartyRisk'] as $risk) {
				$tpRiskIds[] = $risk['id'];
			}
			foreach ($rc['BusinessContinuity'] as $risk) {
				$bcRiskIds[] = $risk['id'];
			}
		}

		return array(
			'riskIds' => $riskIds,
			'tpRiskIds' => $tpRiskIds,
			'bcRiskIds' => $bcRiskIds
		);
	}

	public function isUsed($classificationId) {
		$related = $this->getRelatedRisks($classificationId);

		$count = count($related['riskIds']);
		$count += count($related['tpRiskIds']);
		$count += count($related['bcRiskIds']);
		if (!empty($count)) {
			return $count;
		}

		return false;
	}

	public function cannotBeDeleted($classificationId) {
		$method = $this->Risk->getMethod();
		if ($method == RiskCalculation::METHOD_MAGERIT) {
			$values = $this->Risk->getClassificationTypeValues($this->Risk->getCalculationValues($method));
			return $this->find('count', array(
				'conditions' => array(
					'RiskClassification.risk_classification_type_id' => $values,
					'RiskClassification.id' => $classificationId
				)
			));
		}

		return false;
	}

	public function resaveScores($classificationId) {
		$classification = $this->find('first', array(
			'conditions' => array(
				'RiskClassification.id' => $classificationId
			),
			'recursive' => -1
		));

		$relatedRisks = $this->getRelatedRisks($classificationId);
		$ret = $this->resaveRelatedRisksScore($relatedRisks);

		return $ret;
	}

	public function resaveRelatedRisksScore($relatedRisks) {
		$riskIds = $relatedRisks['riskIds'];
		$tpRiskIds = $relatedRisks['tpRiskIds'];
		$bcRiskIds = $relatedRisks['bcRiskIds'];

		$ret = true;
		if (!empty($riskIds)) {
			$ret &= $this->Risk->calculateAndSaveRiskScoreById($riskIds);
		}
		if (!empty($tpRiskIds)) {
			$ret &= $this->ThirdPartyRisk->calculateAndSaveRiskScoreById($tpRiskIds);
		}
		if (!empty($bcRiskIds)) {
			$ret &= $this->BusinessContinuity->calculateAndSaveRiskScoreById($bcRiskIds);
		}

		$ret &= $this->triggerStatuses($relatedRisks);

		return $ret;
	}

	/**
	 * Get all Risk Classification data with Risk relation to optimize db queries.
	 */
	public function getAllData($joinModel) {
		$join = $this->getAssociated($joinModel);
		$assocForeignKey = $join['associationForeignKey'];

		$this->bindModel(array(
			'hasMany' => array(
				$join['with']
			)
		));

		$data = $this->find('all', array(
			'fields' => array('RiskClassification.name', 'RiskClassification.value', 'RiskClassification.criteria'),
			'contain' => array(
				'RiskClassificationType' => array(
					'fields' => array('id', 'name')
				),
				$join['with'] => array(
					'fields' => array($assocForeignKey)
				)
			)
		));

		$formattedData = $joinIds = array();
		foreach ($data as $classification) {
			$formattedData[$classification['RiskClassification']['id']] = $classification;

			foreach ($classification[$join['with']] as $assocData) {
				if (!isset($joinIds[$assocData[$assocForeignKey]])) {
					$joinIds[$assocData[$assocForeignKey]] = array();
				}

				$joinIds[$assocData[$assocForeignKey]][] = $assocData[$join['foreignKey']];
			}
		}

		return array(
			'formattedData' => $formattedData,
			'joinIds' => $joinIds
		);
	}
}
