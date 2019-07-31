<?php
/**
 * @package       app.Controller
 */

App::uses('AppController', 'Controller');
App::uses('Inflector', 'Utility');
App::uses('AuthComponent', 'Controller/Component');

abstract class SectionBaseController extends AppController {
	public $helpers = array(
		// both optional
		// 'ImportTool.ImportTool',
		// 'Workflows.Workflows'
	);

	public $components = array(
		'Session',// 'Paginator',
		// all filters optional
		// 'Pdf', 'Search.Prg', 'AdvancedFilters',
		'DebugKit.Toolbar' => [
			'panels' => [
				'DebugKit.History' => false,
				'DebugKit.Variables' => false,
				'AppVariables',
				'DebugKit.Include' => false,
				'FieldData.FieldData',
				'AppCrud'
			]
		],

		// ajax component will be removed
		'Ajax' => array(
			'actions' => array('add', 'edit'),
			'modules' => array('comments', 'records', 'attachments', 'notifications')
		),
		// custom fields are optional
		// 'CustomFields.CustomFieldsMgt',
		'Crud.Crud' => [
			'listeners' => [
				'Crud.DebugKit'
			],
			// actions disabled by default
			'actions' => [
				'index' => [
					'enabled' => false,
					'className' => 'AppIndex',
					'viewVar' => 'data',
					'contain' => []
				],
				'add' => [
					'enabled' => false,
					'className' => 'AppAdd',
				],
				'edit' => [
					'enabled' => false,
					'className' => 'AppEdit',
				],
				'delete' => [
					'enabled' => false,
					'className' => 'AppDelete',
				],
				'trash' => [
					'enabled' => false,
					'className' => 'Trash',
				]
			]
		]
	);

	public $title = null;
	public $subTitle = null;

	/**
	 * Merge components and helpers variables with AppController and also with this class at first.
	 */
	public function __construct($request = null, $response = null) {
		$merge = array('components', 'helpers');
		$this->_mergeVars($merge, 'SectionBaseController', true);

		parent::__construct($request, $response);
	}

	public function beforeFilter() {
		// if ($this->request->is('ajax')) {
			// $this->Components->disable('Debugkit.Toolbar');
		// }
		
		$this->Crud->removeListener('RelatedModels');

		// model-related class variables
		$this->modelLabel = Inflector::singularize($this->model()->label());
		// $this->_integrityCheck();

		$this->Crud->on('beforeRender', array($this, '_initOptions'));

		$this->Crud->on('beforePaginate', array($this, '_beforePaginate'));
		$this->Crud->on('beforeRender', array($this, '_beforeRender'));
		$this->Crud->on('beforeHandle', array($this, '_beforeHandle'));

		$this->Crud->on('beforeSave', array($this, '_beforeItemSave'));
		$this->Crud->on('afterSave', array($this, '_afterItemSave'));

		parent::beforeFilter();
	}

	/**
	 * Get the current referenced model instance.
	 * 
	 * @return Model
	 */
	public function model() {
		return $this->{$this->modelClass};
	}

	public function _initOptions() {
		// By default all data needed for add/edit form is set through Field Data layer
		// This sets all input variables as well as $FieldDataCollection class instance
		$this->set($this->model()->getFieldDataEntity()->getViewOptions());

		// Title and subtitle for a view
		if (!isset($this->viewVars['title_for_layout'])) {
			$this->set('title_for_layout', $this->title);
		}
		$this->set('subtitle_for_layout', $this->subTitle);
	}

	/**
	 * Checks if section classes are in order.
	 */
	protected function _integrityCheck() {
		if (!in_array('SectionBase', class_parents($this->model()))) {
			trigger_error(__('Model %s should extend SectionBase model.', get_class($this->model())));
		}

		$Helper = Inflector::pluralize($this->model()->alias);
		$HelperClass = $Helper . 'Helper';
		App::uses($HelperClass, 'View/Helper');

		if (!class_exists($HelperClass)) {
			trigger_error(__('Helper class %s for this section is missing.', $HelperClass));
		}

		if (!in_array('SectionBaseHelper', class_parents($HelperClass))) {
			trigger_error(__('Helper class %s should extend SectionBaseHelper class.', get_class($HelperClass)));
		}
	}

	/**
	 * Triggers only crud action handle execution.
	 */
	protected function handleCrudAction($action, $args = array()) {
		if (empty($args)) {
			$args = $this->request->params['pass'];
		}
		$subject = $this->Crud->trigger('beforeHandle', compact('args', 'action'));

		return $this->Crud->action($action)->handle($subject);
	}

	public function _beforeRender(CakeEvent $event) {
		$this->_setWidgetData();
	}

	/**
	 * Configure new breadcrumbs listed in the layout.
	 */
	protected function _setBreadcrumbs(CakeEvent $event) {
		$this->set('use_new_breadcrumbs', true);
		
		$subject = $event->subject;
        $actionClass = $subject->crud->action();
        $actionLabel = $actionClass->mapActionToLabel();
        $sectionLabel = $subject->model->label();

        $crumbs[] = [
        	'name' => $sectionLabel,
        	'link' => [
	            'controller' => $subject->model->getMappedController(),
	            'action' => 'index'
	        ]
        ];
        
        $crumbs[] = [
        	'name' => $actionLabel,
        	'link' => null
        ];

        // row-level action gets object title
        if ($actionClass::ACTION_SCOPE) {
        	$record = $subject->model->getRecordTitle($subject->args);

            if (!empty($record)) {
               	$crumbs[] = [
               		'name' => $record,
               		'link' => null
	            ];
            }
        }

        $this->set('use_new_breadcrumbs_data', $crumbs);
	}

	/**
	 * Set widget associated data for for the View.
	 */
	protected function _setWidgetData($model = null) {
		if ($model === null) {
			$model = $this->model()->alias;
		}
		$cacheKey = $model . '_' . AuthComponent::user('id');
		if (($data = Cache::read($cacheKey, 'widget_data')) === false) {

			$widgets = ['Comment', 'Attachment', 'NotificationObject'];
			$data = ['_model' => $model];

			foreach ($widgets as $widget) {
				$Model = ClassRegistry::init($widget);
				$widgetData = $Model->find('all', [
					'conditions' => [
						$Model->alias . '.model' => $model
					],
					'fields' => [
						$Model->alias . '.foreign_key',
						$Model->alias . '.created',
						"max({$Model->alias}.created) as max_created"
					],
					'group' => [$Model->alias . '.foreign_key'],
					'recursive' => -1
				]);

				$data[$Model->alias] = Hash::combine($widgetData, '{n}.' . $Model->alias . '.foreign_key', '{n}.0.max_created');;
			}

			$this->loadModel($model);
			if ($this->{$model}->Behaviors->enabled('InspectLog')) {
				$data['WidgetViewLogs'] = $this->{$model}->getInspectLogWidgetLastData();
			}

			Cache::write($cacheKey, $data, 'widget_data');
		}

		$this->set('WidgetData', $data);
	}

	public function _beforePaginate(CakeEvent $event) {
	}

	public function _beforeHandle(CakeEvent $event) {
		$this->_setBreadcrumbs($event);
	}

	public function _beforeItemSave(CakeEvent $event) {
		$model = $event->subject->model;

		if (isset($event->subject->id)) {
			$ret = $model->beforeItemSave($event->subject->id);
			if (!$ret) {
				trigger_error(sprintf('Error occured while triggering status manager beforeItemSave in %s', $model->alias));
			}
		}
	}

	public function _afterItemSave(CakeEvent $event) {
		$model = $event->subject->model;

		if (isset($event->subject->id)) {
			$ret = $model->afterItemSave($event->subject->id);
			if (!$ret) {
				trigger_error(sprintf('Error occured while triggering status manager afterItemSave in %s', $model->alias));
			}
		}
	}
}
