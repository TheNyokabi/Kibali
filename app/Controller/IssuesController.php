<?php
class IssuesController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session','Paginator', 'Ajax' => array(
		'actions' => array('index', 'edit', 'add', 'delete'),
	) );

	public function beforeFilter() {
		parent::beforeFilter();

		if ($this->request->params['action'] == 'index') {
			$controller = controllerFromModel($this->request->params['pass'][0]);
			$this->Ajax->setRedirects(array(
				'index' => array(
					'url' => array('controller' => $controller, 'action' => 'index')
				)
			));
		}
	}

	public function index($model, $foreign_key) {
		if (empty($model) || empty($foreign_key)) {
			$this->actionUnavailable($this->referer());
		}

		$issueModel = $model . 'Issue';
		$this->set('issueModel', $issueModel);

		// $this->loadModel($issueModel);
		$this->loadModel($model);
		$title = $this->{$model}->getRecordTitle($foreign_key);

		if ($title) {
			$this->set('title_for_layout', __('Issues for "%s"', $title));
		}
		else {
			$this->set('title_for_layout', __('Issues'));
		}

		$this->set( 'subtitle_for_layout', __('Issues for this item.') );

		$this->paginate = array(
			'conditions' => array(
				'Issue.foreign_key' => $foreign_key,
				'Issue.model' => $model
			),
			'contain' => array(
				'Attachment' => array(
					'conditions' => array(
						'Attachment.model' => $issueModel
					)
				),
				'Comment' => array(
					'conditions' => array(
						'Comment.model' => $issueModel
					)
				)
			),
			'order' => array('Issue.created' => 'DESC'),
			'limit' => $this->getPageLimit(),
			'recursive' => 1
		);

		$data = $this->paginate( 'Issue' );
		$backUrl = $this->getIndexUrl($model, $foreign_key);

		$this->set('data', $data);
		$this->set('model', $model);
		$this->set('foreign_key', $foreign_key);
		$this->set('backUrl', $backUrl);
	}

	public function add($model, $foreign_key) {
		$this->set( 'title_for_layout', __('Create a Issue') );
		$this->set( 'subtitle_for_layout', __( '' ) );

		$this->set('model', $model);
		$this->set('foreign_key', $foreign_key);

		$issueModel = $model . 'Issue';
		$this->set('issueModel', $issueModel);

		$backUrl = array('controller' => 'issues', 'action' => 'index', $model,  $foreign_key);
		$this->set('backUrl', $backUrl);

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
			$this->loadModel($issueModel);
			unset($this->request->data[$issueModel]['id']);
			$this->request->data[$issueModel]['user_id'] = $this->logged['id'];
			$this->request->data[$issueModel]['model'] = $model;
			$this->request->data[$issueModel]['foreign_key'] = $foreign_key;
			$this->{$issueModel}->set($this->request->data);

			if ( $this->{$issueModel}->validates() ) {
				$dataSource = $this->{$issueModel}->getDataSource();
				$dataSource->begin();

				$ret = $this->{$issueModel}->save(null);
				$ret &= $this->{$issueModel}->afterSaveTrigger($model, $foreign_key);

				if ($ret) {
					$dataSource->commit();

					//trigger ObjectStatus
					$this->{$issueModel}->triggerAssociatedObjectStatus($this->{$issueModel}->id);

					$this->Ajax->success();

					$this->Session->setFlash( __( 'Issue was successfully added.' ), FLASH_OK );
					// $this->redirect($backUrl);
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

	public function edit($id = null) {
		$id = (int) $id;

		$data = $this->Issue->find( 'first', array(
			'conditions' => array(
				'Issue.id' => $id
			),
			'recursive' => -1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}
		$this->Ajax->processEdit($id);

		$issueModel = $data['Issue']['model'] . 'Issue';
		$this->set('issueModel', $issueModel);

		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Issue' ) );
		$backUrl = array('controller' => 'issues', 'action' => 'index', $data['Issue']['model'],  $data['Issue']['foreign_key']);
		$this->set('backUrl', $backUrl);
		$this->set('model', $data['Issue']['model']);
		$this->set('id', $id);

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
			$this->loadModel($issueModel);
			$this->{$issueModel}->set( $this->request->data[$issueModel] );

			if ( $this->{$issueModel}->validates() ) {
				$dataSource = $this->{$issueModel}->getDataSource();
				$dataSource->begin();

				$ret = $this->{$issueModel}->save(null);
				$ret &= $this->{$issueModel}->afterSaveTrigger($data['Issue']['model'], $data['Issue']['foreign_key']);

				if ($ret) {
					$dataSource->commit();

					//trigger ObjectStatus
					$this->{$issueModel}->triggerAssociatedObjectStatus($this->{$issueModel}->id);

					$this->Ajax->success();

					$this->Session->setFlash( __( 'Issue was successfully edited.' ), FLASH_OK );
					// $this->redirect($backUrl);
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
			$this->request->data[$issueModel] = $data['Issue'];
		}

		$this->render( 'add' );
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Issue'));
		$this->set('subtitle_for_layout', __('Delete a Issue.'));

		$data = $this->Issue->find('first', array(
			'conditions' => array(
				'Issue.id' => $id
			),
			'fields' => array('id', 'model', 'foreign_key'),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$issueModel = $data['Issue']['model'] . 'Issue';
		$this->set('issueModel', $issueModel);

		$backUrl = array('controller' => 'issues', 'action' => 'index', $data['Issue']['model'],  $data['Issue']['foreign_key']);
		$this->set('backUrl', $backUrl);
		$this->set('model', $data['Issue']['model']);

		if ($this->request->is('post') || $this->request->is('put')) {
			$this->loadModel($issueModel);

			if ($this->{$issueModel}->delete($id)) {
				//trigger ObjectStatus
				$this->Issue->triggerAssociatedObjectStatus($data);

				$this->Session->setFlash(__('Issue was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}
			$this->Ajax->success();

			// $this->redirect($backUrl);
		}
		else {
			$this->request->data = $data;
		}
	}
}
