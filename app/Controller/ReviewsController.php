<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class ReviewsController extends SectionBaseController {

	use SectionCrudTrait;

	public $helpers = array();
	public $components = array(
		// reviews component handles correct model name configuration for CRUD
		'ReviewsManager',
		'Paginator', 
		'Ajax' => array(
			'actions' => array('index', 'edit', 'add', 'delete')
		),
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'Attachment',
						'Comment',
						'User',
					],
				],
				'add' => [
					'relatedModels' => false,
				],
				'edit' => [
					'relatedModels' => false,
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation'
	);

	public function beforeFilter() {
		$this->Crud->on('beforeFind', array($this, '_beforeFind'));

		parent::beforeFilter();

		if ($this->request->params['action'] == 'index') {
			$controller = controllerFromModel($this->request->params['pass'][0]);
			$this->Ajax->setRedirects(array(
				'index' => array(
					'url' => array('controller' => $controller, 'action' => 'index')
				)
			));
		}

		$this->title = __('Reviews');
		$this->subTitle = __('');
	}

	public function _beforeFind(CakeEvent $e) {
		$e->subject->query['recursive'] = -1;
	}

	// get the currently active review model name by convention.
	public function getReviewModel() {
		return $this->ReviewsManager->getReviewModel();
	}

	// get the related model to currently active review
	public function getRelatedModel() {
		return $this->ReviewsManager->getRelatedModel();
	}

	public function filterIndex($model = null) {
		if (empty($model)) {
			throw new NotFoundException();
		}

		$reviewModel = $this->getReviewModel();
		$this->loadModel($reviewModel);

		$this->set('title_for_layout', getModelLabel($reviewModel));

		Configure::write('Search.Prg.presetForm', array('model' => $reviewModel));
		$this->AdvancedFilters = $this->Components->load('AdvancedFilters');
		$this->AdvancedFilters->initialize($this);

		return $this->_executeCrudAction($reviewModel);
	}

	private function _setTitle($model, $foreign_key = null) {
		$this->loadModel($model);

		if (!empty($foreign_key)) {
			$title = $this->{$model}->getRecordTitle($foreign_key);
		}

		if (isset($title) && $title) {
			$this->title = __('Reviews for "%s"', $title);
		}
		else {
			$this->title = __('Reviews');
		}

		$this->subTitle = __('Reviews for this item.');
	}

	public function index($model, $foreign_key = null) {
		if (empty($model)) {
			$this->actionUnavailable($this->referer());
		}

		$reviewModel = $this->getReviewModel();
		$this->loadModel($reviewModel);

		$this->_setTitle($model, $foreign_key);

		$this->paginate = array(
			'conditions' => array(
				$reviewModel . '.foreign_key' => $foreign_key,
			),
			'order' => array($reviewModel . '.created' => 'DESC'),
		);

		Configure::write('Search.Prg.presetForm', array('model' => $reviewModel));
		$this->Prg = $this->Components->load('Search.Prg');
		$this->Prg->initialize($this);
		$this->Prg->commonProcess($reviewModel);

		$filterConditions = $this->{$reviewModel}->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions)) {
			$this->Paginator->settings['conditions'] = $filterConditions;
		}

		$this->set('model', $model);
		$this->set('foreign_key', $foreign_key);
		$this->set('reviewModel', $reviewModel);

		return $this->_executeCrudAction($reviewModel);
	}

	public function add($model, $foreign_key) {
		$this->title = __('Create a Review');

		$this->set('relatedModel', $model);
		$this->set('foreign_key', $foreign_key);

		$this->initData($model, $foreign_key);

		$reviewModel = $this->getReviewModel();
		$this->set('reviewModel', $reviewModel);

		$this->request->data[$reviewModel]['user_id'] = $this->logged['id'];
		$this->request->data[$reviewModel]['model'] = $model;
		$this->request->data[$reviewModel]['foreign_key'] = $foreign_key;

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->_executeCrudAction($reviewModel);
	}

	public function edit($id = null) {
		$this->title = __('Edit a Review');

		$id = (int) $id;

		$data = $this->Review->find( 'first', array(
			'conditions' => array(
				'Review.id' => $id
			),
			'recursive' => -1
		) );

		if (empty($data)) {
			throw new NotFoundException();
		}

		$this->initData($data['Review']['model'], $data['Review']['foreign_key'], $data['Review']['id']);

		$this->set('relatedModel', $data['Review']['model']);
		$this->set('id', $id);

		$reviewModel = $this->getReviewModel();

		$this->request->data[$reviewModel]['planned_date'] = $data['Review']['planned_date'];

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->_executeCrudAction($reviewModel);
	}

	public function _afterSave(CakeEvent $event) {
		if (!empty($event->subject->success)) {
			$this->Review->triggerAssociatedObjectStatus($event->subject->id);
		}
	}

	protected function initData($model, $foreign_key, $reviewId = null) {
		$reviewModel = $this->getReviewModel();
		$this->set('reviewModel', $reviewModel);

		$this->loadModel($reviewModel);

		$reviewCompleted = false;
		if ($reviewId) {
			$reviewCompleted = $this->{$reviewModel}->find('count', array(
				'conditions' => [
					$reviewModel . '.id' => $reviewId,
					$reviewModel . '.completed' => REVIEW_COMPLETE
				],
				'recursive' => -1
			));
		}
		$this->set('reviewCompleted', $reviewCompleted);

		$mainItem = $this->{$reviewModel}->{$model}->find('first', array(
			'conditions' => array(
				'id' => $foreign_key
			),
			'recursive' => -1
		));

		$this->set('mainItem', $mainItem);

		$prevReview = $this->{$reviewModel}->getLastCompletedReview($foreign_key);

		$futureConds = array(
			$reviewModel . '.foreign_key' => $foreign_key,
			$reviewModel . '.planned_date >' => date('Y-m-d'),
			$reviewModel . '.completed' => REVIEW_NOT_COMPLETE,
		);

		if (!empty($reviewId)) {
			$futureConds[$reviewModel . '.id !='] = $reviewId;
		}

		$futureReview = $this->{$reviewModel}->find('first', array(
			'conditions' => $futureConds,
			'recursive' => -1
		));
		
		$this->set('prevReview', $prevReview);
		$this->set('futureReview', $futureReview);
	}

	public function delete($id = null) {
		$data = $this->Review->find('first', array(
			'conditions' => array(
				'Review.id' => $id
			),
			'fields' => array('id', 'model', 'foreign_key'),
			'recursive' => -1
		));

		$reviewModel = $this->getReviewModel();

		$this->Crud->on('afterDelete', array($this, '_afterSave'));

		return $this->_executeCrudAction($reviewModel);
	}

	public function trash($model = null) {
		if (empty($model)) {
			throw new NotFoundException();
		}

		$reviewModel = $this->getReviewModel();

		$this->loadModel($reviewModel);

		Configure::write('Search.Prg.presetForm', array('model' => $reviewModel));
		$this->AdvancedFilters = $this->Components->load('AdvancedFilters');
		$this->AdvancedFilters->initialize($this);

		$this->set('title_for_layout', __('Reviews (Trash)'));

		return $this->_executeCrudAction($reviewModel);
	}

/**
 * execution of crud action with common process
 * 
 * @param  string $model review model to set
 */
	private function _executeCrudAction($model = null) {
		if ($model !== null) {
			$this->Crud->useModel($model);
		}

		$this->Crud->mapAction('filterIndex', ['className' => 'Filter', 'enabled' => false], true);

		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		return $this->Crud->execute();
	}
}
