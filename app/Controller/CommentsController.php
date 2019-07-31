<?php
App::uses('ThirdPartyAuditsModule', 'ThirdPartyAudits.Lib');
// App::uses('VendorAssessmentsModule', 'VendorAssessments.Lib');

class CommentsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session', 'ComplianceAudits', 'Ajax', 'RequestHandler', 'Paginator', 'NotificationSystemMgt', 'Ajax' => array(
		'actions' => array('delete')
	));

	public function beforeFilter() {
		parent::beforeFilter();

		$this->NotificationSystemMgt->setupDefaultTypes();
		$this->Security->csrfUseOnce = false;
		// $this->Security->validatePost = true;
		
		if (in_array($this->request->params['action'], ['listComments'])) {
			$this->Security->csrfCheck = false;
		}

		//allows action if session key for that authentication is set
		ThirdPartyAuditsModule::allowAction($this);
		// VendorAssessmentsModule::allowAction($this);
	}

	public function index($model, $foreign_key) {
		if (empty($model) || empty($foreign_key)) {
			$this->actionUnavailable($this->referer());
		}

		$this->loadModel($model);
		$title = $this->{$model}->getRecordTitle($foreign_key);

		if ($title) {
			$this->set('title_for_layout', __('Comments for "%s"', $title));
		}
		else {
			$this->set('title_for_layout', __('Comments'));
		}

		$this->set( 'subtitle_for_layout', __('Comment and discuss about this item.') );

		$this->paginate = array(
			'conditions' => array(
				'Comment.foreign_key' => $foreign_key,
				'Comment.model' => $model
			),
			'fields' => array(
			),
			'order' => array('Comment.created' => 'DESC'),
			'limit' => $this->getPageLimit(),
			'recursive' => 0
		);

		$data = $this->paginate( 'Comment' );
		$backUrl = $this->getIndexUrl($model, $foreign_key);

		$this->set('data', $data);
		$this->set('model', $model);
		$this->set('foreign_key', $foreign_key);
		$this->set('backUrl', $backUrl);
	}

	public function delete( $id = null ) {
		$this->set('title_for_layout', __('Comment'));
		$this->set('subtitle_for_layout', __('Delete a Comment.'));
		$id = (int) $id;

		$data = $this->Comment->find( 'first', array(
			'conditions' => array(
				'Comment.id' => $id
			)
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		/*$model = $data['Comment']['model'];
		$foreign_key = $data['Comment']['foreign_key'];
		$backUrl = array('controller' => 'comments', 'action' => 'index', $model, $foreign_key);
		$this->set('backUrl', $backUrl);*/

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Comment->delete($id)) {
				$this->Session->setFlash(__('Comment was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->Ajax->success();
		}
		else {
			$this->request->data = $data;
		}

		$this->set('data', $data);
	}

	public function add( $model = null, $foreign_key = null ) {
		$this->set( 'title_for_layout', __( 'Insert Comment' ) );
		$this->initAddEditSubtitle();

		if ( $this->request->is( 'post' ) ) {
			unset( $this->request->data['Comment']['id'] );

			$this->request->data['Comment']['user_id'] = $this->logged['id'];
			$this->Comment->set( $this->request->data );

			if ( $this->Comment->validates() ) {
				if ( $this->Comment->save() ) {
					$this->ComplianceAudits->afterItemSave('Comment');

					$this->Session->setFlash( __( 'Comment was successfully added.' ), FLASH_OK );

					//log view of widget
					$this->loadModel($model);
					if ($this->{$model}->Behaviors->enabled('InspectLog')) {
						$this->{$model}->inspectLogWidget($foreign_key);
					}

					$this->redirect( array( 'controller' => 'comments', 'action' => 'index', $model, $foreign_key ) );
				} else {
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			} else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}

		$this->set( 'model', $model );
		$this->set( 'foreign_key', $foreign_key );
	}

	public function addAjax($model, $foreign_key) {
		$this->allowOnlyAjax();

		if ($this->request->is('post')) {
			unset($this->request->data['Comment']['id']);

			$this->request->data['Comment']['user_id'] = $this->logged['id'];
			$this->request->data['Comment']['model'] = $model;
			$this->request->data['Comment']['foreign_key'] = $foreign_key;
			$this->Comment->set($this->request->data);

			$dataSource = $this->Comment->getDataSource();
			$dataSource->begin();

			if ($this->Comment->validates()) {
				$save = $this->Comment->save();
				$save &= $this->ComplianceAudits->afterItemSave('Comment');

				if ($save) {
					$dataSource->commit();
					$this->Session->setFlash( __( 'Comment was successfully added.' ), FLASH_OK );
					$this->set('addedId', $this->Comment->id);

					$additionalData = $this->Comment->find('first', array(
						'conditions' => array(
							'Comment.id' => $this->Comment->id
						)
					));

					//log view of widget
					$this->loadModel($model);
					if ($this->{$model}->Behaviors->enabled('InspectLog')) {
						$this->{$model}->inspectLogWidget($foreign_key);
					}

					$ret = $this->NotificationSystemMgt->triggerHandler(array(
						'model' => 'Comment',
						'callback' => 'afterSave'
					), $model, $foreign_key, $additionalData);
				}
				else {
					$this->Session->setFlash( __( 'Error occured. Please try again.' ), FLASH_ERROR );
					$dataSource->rollback();
				}
				$this->request->data['Comment']['message'] = null;
			}
			else {
			}
		}

		// $this->set('model', $model);
		// $this->set('foreign_key', $foreign_key);
		// $this->set('data', $this->Comment->getByItem($model, $foreign_key));

		// $this->render('/Elements/itemSidebar/comments');
		
		$this->listComments($model, $foreign_key);
	}

	public function edit( $id = null ) {
		$id = (int) $id;

		if ( ! empty( $this->request->data ) ) {
			$id = (int) $this->request->data['Comment']['id'];
		}

		$data = $this->Comment->find( 'first', array(
			'conditions' => array(
				'Comment.id' => $id
			),
			'recursive' => -1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Comment' ) );
		$this->initAddEditSubtitle();
		$this->set( 'model', $data['Comment']['model'] );
		$this->set( 'foreign_key', $data['Comment']['foreign_key'] );

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {

			$this->Comment->set( $this->request->data );

			if ( $this->Comment->validates() ) {
				if ( $this->Comment->save() ) {
					$this->Session->setFlash( __( 'Comment was successfully edited.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'comments', 'action' => 'index', $data['Comment']['model'], $data['Comment']['foreign_key'] ) );
				}
				else {
					$this->Session->setFlash( __( 'Error while saving the data. Please try it again.' ), FLASH_ERROR );
				}
			} else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		}
		else {
			$this->request->data = $data;
		}

		$this->render( 'add' );
	}

	public function listComments($model, $foreign_key) {
		/*$count = $this->Comment->find('count', array(
			'conditions' => array(
				'Comment.model' => $model,
				'Comment.foreign_key' => $foreign_key
			),
			'order' => array('Comment.created' => 'DESC')
		));

		$this->Paginator->settings = array(
			'Comment' => array(
				'conditions' => array(
					'Comment.model' => $model,
					'Comment.foreign_key' => $foreign_key
				),
				'order' => array('Comment.created' => 'DESC'),
				'limit' => $this->request->data['limit']
			)
		);

		$comments = $this->paginate('Comment');
		$noMoreComments = ($count == count($comments)) ? true : false;

		$this->set('model', $model);
		$this->set('foreign_key', $foreign_key);
		$this->set('data', $comments);
		$this->set('noMoreComments', $noMoreComments);
		$this->set('paginateLabel', (count($comments) . '/' . $count));*/

		$this->Ajax->setCommentsData($model, $foreign_key);

		$this->render('/Elements/ajax-ui/comments');
	}

	private function initAddEditSubtitle() {
		$this->set( 'subtitle_for_layout', '' );
	}

	public function getIndexUrlFromComponent($model, $foreign_key) {
		return parent::getIndexUrl($model, $foreign_key);
	}

	public function initEmailFromComponent($to, $subject, $template, $data = array(), $layout = 'default', $from = NO_REPLY_EMAIL, $type = 'html') {
		return parent::initEmail($to, $subject, $template, $data, $layout, $from, $type);
	}

}
