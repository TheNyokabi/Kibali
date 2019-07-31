<?php
App::uses('Component', 'Controller');
class AjaxComponent extends Component {
	public $components = array('Session');
	public $settings = array();
	private $ajaxAction = null;

	public function __construct(ComponentCollection $collection, $settings = array()) {
		if (empty($this->settings)) {
			$this->settings = array(
				'modules' => array('comments', 'records', 'attachments'),
				'actions' => array('index', 'show', 'delete', 'add', 'edit'),
				'redirects' => array(),
				'formDataActions' => array(),
				'formDataModels' => array(),
			);
		}

		$settings = array_merge($this->settings, (array)$settings);
		parent::__construct($collection, $settings);
	}

	public function initialize(Controller $controller) {
		$this->controller = $controller;
		// $this->handleUrlAccess();

		$this->handleCustomActions();
		$this->setHeader();
		$this->setAction();

		// we set helper settings default model and controller for later use
		$this->setHelperSettings();
	}

	public function startup(Controller $controller) {
		$this->controller = $controller;
		$this->handleRedirects();

		// $this->setAction();
	}

	public function beforeRender(Controller $controller) {
		$this->controller = $controller;

		// we run it on beforeRender callback because it changes $request data and result in conflicts of called sooner.
		$this->setSessionFormData();
	}

	public function setRedirects($arr) {
		$this->settings['redirects'] = $arr;
	}

	private function handleRedirects() {
		$request = $this->controller->request;

		$conds = !$request->is('ajax');
		$conds &= !$request->is('api');
		$conds &= in_array($request->params['action'], $this->settings['actions']);
		$conds &= !empty($this->settings['redirects'][$request->params['action']]);

		// advancecd filters are not redirected in this handler
		$conds &= empty($request->query['advanced_filter']) && empty($request->data[$this->controller->modelClass]['advanced_filter']);
		
		if ($conds) {
			$options = $this->settings['redirects'][$request->params['action']];
			$this->controller->redirect($options['url']);
			exit;
		}
	}

	/**
	 * If accessing ajax url directly.
	 */
	private function handleUrlAccess() {
		$request = $this->controller->request;

		if (!$request->is('ajax')) {
			if (in_array($request->params['action'], $this->settings['actions'])) {
				$model = $this->controller->modelClass;
				$url = array('action' => 'index'/*, '?' => array('originalRequestedAction' => $request->params['action'])*/);
				if (!empty($model->mapping['indexController'])) {
					$url['controller'] = $model->mapping['indexController'];
				}
				$this->Session->write('ErambaAjax.directlyRequestedAjaxAction', $request);

				$this->controller->redirect($url);
			}
			else {
				if ($this->Session->check('ErambaAjax.directlyRequestedAjaxAction')) {
					$requested = $this->Session->read('ErambaAjax.directlyRequestedAjaxAction');
					$this->controller->set('pushState', array(
						'action' => $requested->params['action'],
						'url' => Router::reverse($requested)
					));
					$this->Session->delete('ErambaAjax.directlyRequestedAjaxAction');
				}
			}
		}
	}

	private function handleCustomActions() {
		$query = $this->controller->request->query;
		if (isset($query['_eramba_ajax'])) {
			$customAction = $query['_eramba_ajax'];
			return $this->{$customAction}($this->controller->request->data);
		}
	}

	private function store_session($data) {
		// handle hacks
		if (isset($data['Auth'])) {
			return false;
		}

		$keys = array_keys($data);
		foreach ($keys as $key) {
			$this->Session->write('ErambaAjax.formData.' . $key, $data[$key]);
		}

		$this->controller->request->query = array();
		// $this->controller->request->data = array();

		$this->controller->response->send();
		return $this->controller->_stop();
	}

	private function setSessionFormData() {
		$allowedActions = array("add", "edit");
		$allowedActions = array_merge($allowedActions, $this->settings['formDataActions']);
		if (!in_array($this->ajaxAction, $allowedActions) || $this->controller->request->is('post')) {
			return false;
		}

		$models = (!empty($this->settings['formDataModels'])) ? $this->settings['formDataModels'] : [$this->controller->modelClass];

		foreach ($models as $model) {
			if ($this->Session->check('ErambaAjax.formData.' . $model) && empty($this->controller->request->query['ignoreSessionData'])) {
				$this->controller->request->data[$model] = $this->Session->read('ErambaAjax.formData.' . $model);
				$this->Session->delete('ErambaAjax.formData.' . $model);
			}
		}
		
	}

	public function setHeader(){
		if(in_array($this->controller->action, $this->settings['actions'])){
			$this->controller->set('showHeader', true);
		}
	}

	public function setModalPadding() {
		$this->controller->set('modalPadding', true);
	}

	/*public function setModalClose() {
		$this->controller->set('modalClose', true);
	}*/

	public function success() {
		$this->controller->set('ajaxSuccess', true);

		$model = $this->controller->modelClass;
		$this->Session->delete('ErambaAjax.formData.' . $model);
	}

	private function setAction() {
		if (!$this->controller->request->is('ajax')) {
			return false;
		}

		$params = Router::parse($this->controller->request->here);
		$action = $params['action'];
		$this->ajaxAction = $action;
		$this->controller->set('ajaxAction', $this->ajaxAction);
		$this->controller->set('ajaxSection', $this->controller->modelClass);
	}

	/**
	 * Set helper settings default model and controller for later use.
	 */
	private function setHelperSettings() {
		$this->controller->helpers['Ajax'] = array(
			'controller' => lcfirst($this->controller->name),
			'model' => $this->controller->modelClass
		);
	}

	/**
	 * Set up data for show page.
	 *
	 * @param int $id Item ID.
	 * @param string $model Optionally set a model to adjust. Defaults to current model.
	 */
	public function processShow($id, $model = null) {
		if (empty($model)) {
			$model = $this->controller->modelClass;
		}

		$this->setCommonAssociations($model);
		$this->setCommonData($model, $id);
	}

	public function processEdit($id, $model = null) {
		if (empty($model)) {
			$model = $this->controller->modelClass;
		}

		$this->setCommonAssociations($model);
		$this->setCommonData($model, $id);

		$this->initAdditionalModules($model, $id);
	}

	public function initSidebarWidget($id, $model = null) {
		if (empty($model)) {
			$model = $this->controller->modelClass;
		}

		$this->setCommonAssociations($model);
		$this->setCommonData($model, $id);

		$this->initAdditionalModules($model, $id);
	}

	private function initAdditionalModules($model, $id) {
		if (in_array('notifications', $this->settings['modules'])) {
			$this->setNotificationsModule($model, $id);
		}
	}

	/**
	 * Associate models that are needed in general.
	 */
	private function setCommonAssociations($model) {
		$this->controller->loadModel($model);
		$class = $this->controller->{$model};

		if (!isset($class->hasMany['Attachment']) && in_array('attachments', $this->settings['modules'])) {
			$class->bindModel(array(
				'hasMany' => array(
					'Attachment' => array(
						'className' => 'Attachment',
						'foreignKey' => 'foreign_key',
						'conditions' => array(
							'Attachment.model' => $model
						)
					)
				)
			));
		}

		if (!isset($class->hasMany['Comment']) && in_array('comments', $this->settings['modules'])) {
			$class->bindModel(array(
				'hasMany' => array(
					'Comment' => array(
						'className' => 'Comment',
						'foreignKey' => 'foreign_key',
						'conditions' => array(
							'Comment.model' => $model
						)
					)
				)
			));
		}

		if (!isset($class->hasMany['SystemRecord']) && in_array('records', $this->settings['modules'])) {
			$class->bindModel(array(
				'hasMany' => array(
					'SystemRecord' => array(
						'className' => 'SystemRecord',
						'foreignKey' => 'foreign_key',
						'conditions' => array(
							'SystemRecord.model' => $model
						)
					)
				)
			));
		}
	}

	/**
	 * Set up common data for each item.
	 */
	private function setCommonData($model, $id) {
		//log view of widget
		if ($this->controller->{$model}->Behaviors->enabled('InspectLog')) {
			$this->controller->{$model}->inspectLogWidget($id);
		}

		if (!empty($this->settings['modules']) && in_array('comments', $this->settings['modules'])) {
			$this->setCommentsData($model, $id);
			$this->controller->set('pageLimit', 10);
		}
		
		if (!empty($this->settings['modules']) && in_array('records', $this->settings['modules'])) {
			$records = $this->controller->{$model}->SystemRecord->getByItem($model, $id);
			$recordTypes = getSystemRecordTypes(null, true);
			
			$this->controller->set('records', $records);
			$this->controller->set('recordTypes', $recordTypes);
		}
			
		if (!empty($this->settings['modules']) && in_array('records', $this->settings['modules'])) {
			$attachments = $this->controller->{$model}->Attachment->getByItem($model, $id);
			$attachmentsCount = $this->controller->{$model}->Attachment->find('count', array(
				'conditions' => array(
					'Attachment.model' => $model,
					'Attachment.foreign_key' => $id
				)
			));

			$this->controller->set('attachmentsCount', $attachmentsCount);
			$this->controller->set('attachments', $attachments);
		}
	}

	public function setCommentsData($model, $id) {
		$this->controller->loadModel('Comment');
		$count = $this->controller->Comment->find('count', array(
			'conditions' => array(
				'Comment.model' => $model,
				'Comment.foreign_key' => $id
			)
		));

		$this->controller->Paginator->settings = array(
			'Comment' => array(
				'conditions' => array(
					'Comment.model' => $model,
					'Comment.foreign_key' => $id
				),
				'order' => array('Comment.created' => 'DESC'),
				'limit' => isset($this->controller->request->data['limit']) ? $this->controller->request->data['limit'] : 10
			)
		);

		$comments = $this->controller->paginate('Comment');
		$noMoreComments = ($count == count($comments)) ? true : false;

		$this->controller->set('model', $model);
		$this->controller->set('foreign_key', $id);
		$this->controller->set('comments', $comments);
		$this->controller->set('commentsCount', $count);
		$this->controller->set('noMoreComments', $noMoreComments);
		$this->controller->set('paginateLabel', (count($comments) . '/' . $count));
	}

	public function setNotificationsModule($model, $id) {
		$this->controller->loadModel($model);
		$this->controller->{$model}->bindNotifications();

		$notifications = $this->controller->{$model}->NotificationObject->getByItem($model, $id);
		// debug($notifications);

		$excludeIds = array();
		foreach ($notifications as $n) {
			$excludeIds[] = $n['NotificationSystem']['id'];
		}

		$notificationsItemList = $this->controller->{$model}->NotificationObject->NotificationSystem->find('list', array(
			'conditions' => array(
				'NotificationSystem.model' => $model,
				'NotificationSystem.id !=' => $excludeIds,
				'NotificationSystem.type !=' => NOTIFICATION_TYPE_REPORT
			),
			'fields' => array('id', 'name'),
			'recursive' => -1
		));

		
		$this->controller->set('notificationsModule', true);
		$this->controller->set('model', $model);
		$this->controller->set('foreign_key', $id);

		$this->controller->set('notificationsItemList', $notificationsItemList);
		$this->controller->set('notifications', $notifications);
		$this->controller->set('notificationsCount', count($notifications));
	}

}
