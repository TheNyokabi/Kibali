<?php
class RiskCalculation extends AppModel {
	public $actsAs = array(
		'Containable',
		'RiskCalculationManager'
	);

	public $hasMany = array(
		'RiskCalculationValue'
	);

	public $methods = array();
	public $calcRules = array();

	public $validate = array(
		'method' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'You must choose one calculation method'
			)
		),
		'model' => array(
			'notEmpty' => array(
				'rule' => 'notBlank',
				'required' => true,
				'message' => 'This field is required'
			)
		),
	);

	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->setupCalculationsList();
	}

	/**
	 * Available methods for Risk Appetite.
	 */
	 public static function methods($value = null) {
		$options = array(
			self::METHOD_ERAMBA => __('Eramba'),
			self::METHOD_ERAMBA_MULTIPLY => __('Eramba Multiply'),
			self::METHOD_MAGERIT => __('Magerit')
		);
		return parent::enum($value, $options);
	}
	const METHOD_ERAMBA = 'eramba';
	const METHOD_ERAMBA_MULTIPLY = 'erambaMultiply';
	const METHOD_MAGERIT = 'magerit';

	public function beforeValidate($options = array()) {
		$this->validator()->add('model', 'inList', array(
			'rule' => array('inList', array_keys($this->calcRules)),
			'message' => __('Section does not support calculations')
		));

		$this->validator()->add('method', 'inList', array(
			'rule' => array('inList', array_values($this->calcRules[$this->data['RiskCalculation']['model']])),
			'message' => __('This calculation method is not supported in this section')
		));
		
		// if (!$this->_validateRiskAppetite()) {
			// $this->invalidate('method', __('Risk Calculation cannot be confgured as Magerit as it is not compatible with Risk Appetite Threshold settings which is already configured in Risk section'));
		// }

		if (!empty($this->data['RiskCalculation']['method'])) {
			$this->RiskCalculationValue->validator()->add('value', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This field is required')
			));

			$method = $this->data['RiskCalculation']['method'];
			$requiredConds = $method == self::METHOD_ERAMBA;
			$requiredConds = $requiredConds || $method == self::METHOD_ERAMBA_MULTIPLY;
			if ($requiredConds) {
				$maxCount = 10;
				if (count($this->data['RiskCalculationValue']) == 0 || count($this->data['RiskCalculationValue']) > $maxCount) {
					$this->invalidate('values', __('Choose at least 1 or maximum %d Classification Types', $maxCount));
					return false;
				} 
			}
		}

		return true;
	}

	/**
	 * Validate Risk Appetite method and check if combination of Magerit and Threshold are not configured as
	 * they are not compatible.
	 * 
	 * @return bool True on success, False otherwise.
	 */
	protected function _validateRiskAppetite()
	{
		$appetiteMethod = ClassRegistry::init('RiskAppetite')->getCurrentType();

		$conds = $appetiteMethod === RiskAppetite::TYPE_THRESHOLD;
		$conds &= $this->data['RiskCalculation']['method'] == self::METHOD_MAGERIT;
		if ($conds) {
			return false;
		}

		return true;
	}

	public function beforeSave($options = array()) {
		if (!empty($this->data['RiskCalculation']['method']) && $this->data['RiskCalculation']['method'] == 'eramba') {

			/*foreach ($this->data['RiskCalculationValue'] as $key => $val) {
				$this->data['RiskCalculationValue'][$key] = array(
					'field' => 'default',
					'value' => $val
				);
			}*/

		}
	}

	/**
	 * Compares request form data with existing data for changes.
	 */
	public function checkChanges() {
		// debug($this->data);
		$submittedData = array();
		if (!empty($this->data['RiskCalculationValue'])) {
			foreach ($this->data['RiskCalculationValue'] as $v) {
				if (!empty($v['value'])) {
					$submittedData[] = $v['value'];
				}
			}
		}

		$item = $this->find('first', array(
			'conditions' => array(
				'RiskCalculation.id' => $this->data['RiskCalculation']['id']
			),
			'fields' => array('method'),
			'recursive' => -1
		));

		$itemSettings = $this->RiskCalculationValue->find('list', array(
			'conditions' => array(
				'RiskCalculationValue.risk_calculation_id' => $this->data['RiskCalculation']['id'],
				'RiskCalculationValue.value !=' => ''
			),
			'fields' => array('value'),
			'recursive' => -1
		));


		$originalData = array_values($itemSettings);

		$changes = array();
		if ($originalData !== $submittedData) {
			$this->changesData = array(
				'settings' => array(
					'original' => $originalData,
					'request' => $submittedData
				)
			);
			$changes[] = 'settings';
		}

		if ($item['RiskCalculation']['method'] != $this->data['RiskCalculation']['method']) {
			$changes[] = 'method';
		}

		return $changes;

		// debug($originalData);debug($submittedData);
		// return array_intersect($originalData, $submittedData);
	}

}
