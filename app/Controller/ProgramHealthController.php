<?php
class ProgramHealthController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $uses = array(
		'RiskClassification',
		'AssetClassification',
		'SecurityServiceClassification',
		'Risk',
		'ThirdPartyRisk',
		'BusinessContinuity',
		'Asset',
		'SecurityService',
		'CompliancePackage',
		'ComplianceManagement',
		'ThirdParty',
		'Project',
		'Tag'
	);
	public $components = array('Session', 'Pdf');
	private $riskData = array();

	public function index() {
		$this->set('title_for_layout', __('Program Health'));
		$this->set('subtitle_for_layout', __('This shows the overall status of the core sections of the system.'));

		$this->set($this->getData());
	}

	public function exportPdf() {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$vars = $this->getData();
		$this->set($vars);

		$name = 'program-health';
		$this->Pdf->renderPdf($name, '..'.DS.'ProgramHealth'.DS.'export_pdf', 'pdf', $vars, true);
	}

	/**
	 * Get data to set for the index/export view.
	 */
	private function getData() {
		// risks data
		$risksCount = array(
			'Risk' => $this->setRiskClassifications('Risk'),
			'ThirdPartyRisk' => $this->setRiskClassifications('ThirdPartyRisk'),
			'BusinessContinuity' => $this->setRiskClassifications('BusinessContinuity'),
		);
		
		// other data
		// $assetsCount = $this->setAssetClassifications();
		$controlsCount = $this->setSecurityServiceClassifications();
		$complianceCount = $this->setCompliance();
		$projectsCount = $this->setProjectClassifications();

		return array(
			// risks data
			'risksCount' => $risksCount,

			// other data
			// 'assetsCount' => $assetsCount,
			'controlsCount' => $controlsCount,
			'complianceCount' => $complianceCount,
			'projectsCount' => $projectsCount
		);
	}

	private function setRiskClassifications($model) {
		$risksCount = $this->{$model}->find('count');

		if ($risksCount) {
			$this->riskData[$model] = $this->getTagData($model);
			$this->set('riskClassifications', $this->riskData);
		}

		return $risksCount;
	}

	private function setProjectClassifications() {
		$projectsCount = $this->Project->find('count');

		if ($projectsCount) {
			$data = $this->getTagData('Project');

			$this->set(array(
				'projectClassifications' => $data['classifications'],
				'noProjectClassifications' => $data['noItemClassifications']
			));
		}

		return $projectsCount;
	}

	/**
	 * Get data associated with Tag module.
	 */
	private function getTagData($model) {
		$this->Tag->bindModel(array(
			'belongsTo' => array(
				$model => array(
					'foreignKey' => 'foreign_key',
					'conditions' => "Tag.model = '{$model}'"
				)
			)
		));

		$classificationsTmp = $this->Tag->find('all', array(
			'conditions' => array(
				'Tag.model' => $model
			),
			'contain' => array(
				$model => array(
					'conditions' => array(
						$model . '.workflow_status' => WORKFLOW_APPROVED
					)
				)
			)
		));

		$classifications = array();
		$ids = array();
		foreach ($classificationsTmp as $classification) {
			if (empty($classification[$model]['id'])) {
				continue;
			}

			$classifications[$classification['Tag']['title']][$model][] = $classification[$model];
			$ids[] = $classification['Tag']['foreign_key'];
		}

		$ids = array_unique($ids);

		$noItemClassifications = array(
			$model => array()
		);

		$items = $this->{$model}->find('all', array(
			'conditions' => array(
				$model . '.id !=' => $ids
			),
			'recursive' => -1
		));

		foreach ($items as $item) {
			$noItemClassifications[$model][] = $item[$model];
		}

		return array(
			'classifications' => $classifications,
			'noItemClassifications' => $noItemClassifications
		);
	}

	private function setCompliance() {
		$data = $this->ThirdParty->find('all', array(
			'fields' => array(
				'ThirdParty.id',
				'ThirdParty.name',
				'ThirdParty.description'
			),
			'contain' => array(
				'CompliancePackage' => $this->ComplianceManagement->packageContain
			),
			'order' => array('ThirdParty.id' => 'ASC')
		));

		$data = $this->ComplianceManagement->filterComplianceData($data);
		// debug($data);
		$this->set('compliance', $data);

		return count($data);
	}

	private function setSecurityServiceClassifications() {
		$controlsCount = $this->SecurityService->find('count');

		if ($controlsCount) {
			$controlClassificationsTmp = $this->SecurityServiceClassification->find('all', array(
				'contain' => array(
					'SecurityService' => array(
						'conditions' => array(
							'SecurityService.workflow_status' => WORKFLOW_APPROVED
						)
					)
				)
			));

			$controlClassifications = array();
			$controlIds = array();
			// debug($controlClassificationsTmp);
			// exit;
			foreach ($controlClassificationsTmp as $classification) {
				if (empty($classification['SecurityService']['id'])) {
					continue;
				}

				$controlClassifications[$classification['SecurityServiceClassification']['name']]['SecurityService'][] = $classification['SecurityService'];
				$controlIds[] = $classification['SecurityServiceClassification']['security_service_id'];
			}

			$controlIds = array_unique($controlIds);

			// debug($controlClassifications);
			// return true;

			/*$assetIds = array();
			foreach ($controlClassifications as $classification) {
				foreach ($classification['Asset'] as $asset) {
					$assetIds[] = $asset['id'];
				}
			}
	*/
			$noControlClassifications = array();

			$controls = $this->SecurityService->find('all', array(
				'conditions' => array(
					'SecurityService.id !=' => $controlIds
				),
				'recursive' => -1
			));

			foreach ($controls as $item) {
				$noControlClassifications['SecurityService'][] = $item['SecurityService'];
			}

			$this->set(array(
				'controlClassifications' => $controlClassifications,
				'noControlClassifications' => $noControlClassifications
			));
		}

		return $controlsCount;
	}

	private function setAssetClassifications() {
		$assetsCount = $this->Asset->find('count');

		if ($assetsCount) {
			$assetClassifications = $this->AssetClassification->find('all', array(
				'contain' => array(
					'Asset'
				)
			));

			$assetIds = array();
			foreach ($assetClassifications as $classification) {
				foreach ($classification['Asset'] as $asset) {
					$assetIds[] = $asset['id'];
				}
			}

			$noAssetClassification = array(
				'Asset' => array()
			);

			$assets = $this->Asset->find('all', array(
				'conditions' => array(
					'Asset.id !=' => $assetIds
				),
				'recursive' => -1
			));

			foreach ($assets as $item) {
				$noAssetClassification['Asset'][] = $item['Asset'];
			}

			// debug($noAssetClassification);

			$this->set(array(
				'assetClassifications' => $assetClassifications,
				'noAssetClassification' => $noAssetClassification
			));
		}

		return $assetsCount;
	}

	/**
	 * @deprecated
	 */
	private function __setRiskClassifications() {
		$risksCount = $this->Risk->find('count');
		$risksCount += $this->ThirdPartyRisk->find('count');
		$risksCount += $this->BusinessContinuity->find('count');

		if ($risksCount) {
			$riskClassifications = $this->RiskClassification->find('all', array(
				'contain' => array(
					'Risk',
					'ThirdPartyRisk',
					'BusinessContinuity'
				)
			));

			$riskIds = $tpRiskIds = $bcRiskIds = array();
			foreach ($riskClassifications as $classification) {
				foreach ($classification['Risk'] as $risk) {
					$riskIds[] = $risk['id'];
				}
				
				foreach ($classification['ThirdPartyRisk'] as $risk) {
					$tpRiskIds[] = $risk['id'];
				}

				foreach ($classification['BusinessContinuity'] as $risk) {
					$bcRiskIds[] = $risk['id'];
				}
			}

			$noClassification = array(
				'Risk' => array(),
				'ThirdPartyRisk' => array(),
				'BusinessContinuity' => array(),
			);

			$risks = $this->Risk->find('all', array(
				'conditions' => array(
					'Risk.id !=' => $riskIds
				),
				'recursive' => -1
			));

			$tpRisks = $this->ThirdPartyRisk->find('all', array(
				'conditions' => array(
					'ThirdPartyRisk.id !=' => $tpRiskIds
				),
				'recursive' => -1
			));

			$bcRisks = $this->BusinessContinuity->find('all', array(
				'conditions' => array(
					'BusinessContinuity.id !=' => $bcRiskIds
				),
				'recursive' => -1
			));

			foreach ($risks as $item) {
				$noClassification['Risk'][] = $item['Risk'];
			}
			foreach ($tpRisks as $item) {
				$noClassification['ThirdPartyRisk'][] = $item['ThirdPartyRisk'];
			}
			foreach ($bcRisks as $item) {
				$noClassification['BusinessContinuity'][] = $item['BusinessContinuity'];
			}

			// debug($noClassification);

			$this->set(array(
				'riskClassifications' => $riskClassifications,
				'noClassification' => $noClassification
			));
		}

		return $risksCount;
	}

	private function initOptions($id = null) {
		$this->set('hasCurrent', $this->ProgramScope->hasCurrentStatus($id));
	}

	private function initAddEditSubtitle() {
		$this->set('subtitle_for_layout', __('subtitle.'));
	}

}
