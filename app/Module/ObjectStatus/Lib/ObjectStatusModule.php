<?php
App::uses('ModuleBase', 'Lib');
App::uses('ClassRegistry', 'Utility');

class ObjectStatusModule extends ModuleBase {

/**
 * Sync all Object Statuses in all models.
 * 
 * @return boolean success
 */
	public function syncAllStatuses() {
		$modelNames = ['DataAssetInstance', 'Asset', 'Risk', 'ThirdPartyRisk', 'BusinessContinuity',
			'ComplianceAnalysisFinding', 'ServiceContract', 'PolicyException',
			'ComplianceException', 'ComplianceFinding', 'Project', 'RiskException', 'SecurityIncident', 'SecurityPolicy',
			'SecurityService', 'VendorAssessments.VendorAssessment', 'VendorAssessments.VendorAssessmentFinding',
			'VendorAssessments.VendorAssessmentFeedback'
		];

		$ret = true;

		foreach ($modelNames as $modelName) {
			try {
				$Model = ClassRegistry::init($modelName);

				if ($Model->Behaviors->enabled('ObjectStatus') && !empty($Model->useTable)) {
					$itemIds = $this->_getItemsList($Model);
					$ret &= $Model->triggerObjectStatus(null, $itemIds);
	      		}
			}
			catch(Exception $e) {
			}
		}

		return $ret;
	}

/**
 * Get list of all item ids in current model.
 * 
 * @param  Model $Model model instance
 * @return array list of ids
 */
	protected function _getItemsList($Model) {
		$ids = [];

		$data = $Model->find('list', [
			'recursive' => -1
		]);
		if (!empty($data)) {
			$ids = array_keys($data);
		}

		return $ids;
	}
}
