<?php

App::uses('Component', 'Controller');
App::uses('Group', 'Model');

/**
 * Component to manage Risk sections.
 */
class RisksManagerComponent extends Component {
	public $components = ['Session', 'Crud'];
	public $settings = [
	];

	/**
	 * Runtime configuration values.
	 * 
	 * @var array
	 */
	protected $_runtime = [
	];

	/**
	 * Reference to the current event manager.
	 *
	 * @var CakeEventManager
	 */
	// protected $_eventManager;

	public function __construct(ComponentCollection $collection, $settings = array()) {
		if (empty($this->settings)) {
			$this->settings = array(
			);
		}

		$settings = array_merge($this->settings, (array)$settings);
		parent::__construct($collection, $settings);

		$this->_runtime = $this->settings;
	}

	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$this->request = $this->controller->request;
	}

	/**
	 * Risk model class name for the current controller.
	 * 
	 * @return string
	 */
	public function modelClass() {
		return $this->Crud->getSubject()->modelClass;
	}

	/**
	 * Risk model object for the current controller.
	 * 
	 * @return object Instance of BaseRisk class.
	 */
	public function model() {
		return $this->Crud->getSubject()->model;
	}

	/**
	 * Process classification fields via ajax request.
	 * 
	 * @return void
	 */
	public function processClassifications()
	{
		// $this->controller->allowOnlyAjax();
		$this->controller->autoRender = false;

		$this->controller->set($this->getDataToSet());
		$this->controller->initOptions();

		return $this->renderElement();
	}

	/**
	 * Generic method to render classifications element to show/manage/calculate risk classifications.
	 * 
	 * @return View
	 */
	public function renderElement()
	{
		return $this->controller->render('../Elements/risks/risk_classifications/classifications_ajax');
	}

	/**
	 * Get the array of data that need to be set to manage classifications and risk calculation.
	 * 
	 * @return array Array of data
	 */
	public function getDataToSet()
	{
		$relatedItemIds = json_decode($this->request->query['relatedItemIds']);
		$this->request->query['relatedItemIds'] = $relatedItemIds;

		if (empty($relatedItemIds)) {
			$this->controller->set('classificationsNotPossible', true);
		}

		$classificationIds = json_decode($this->request->query['classificationIds']);
		if (empty($classificationIds)) {
			for ($i = 0; $i <= count($this->model()->getFormClassifications()); $i++) {
				$classificationIds[$i] = "";
			}
		}

		$this->request->query['classificationIds'] = $classificationIds;

		if (method_exists($this->model(), 'isMageritPossible')) {
			$this->controller->set('isMageritPossible', $this->model()->isMageritPossible($relatedItemIds));
		}

		$setData = $this->model()->getRiskCalculationData($classificationIds, $relatedItemIds);
		$data = array_merge($setData, $this->request->query);

		return $data;
	}
}
