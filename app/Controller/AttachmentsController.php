<?php
App::uses('ThirdPartyAuditsModule', 'ThirdPartyAudits.Lib');
// App::uses('VendorAssessmentsModule', 'VendorAssessments.Lib');

class AttachmentsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session', 'ComplianceAudits', 'NotificationSystemMgt', 'Ajax' => array(
		'actions' => array('delete')
	), 'AttachmentsMgt');

	public function beforeFilter() {
		parent::beforeFilter();

		$this->NotificationSystemMgt->setupDefaultTypes();

		$this->Security->csrfUseOnce = false;
		// $this->Security->validatePost = true;

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
			$this->set('title_for_layout', __('Attachments for "%s"', $title));
		}
		else {
			$this->set('title_for_layout', __('Attachments'));
		}

		$this->set( 'subtitle_for_layout', __('Upload and manage atachments for this item.') );

		$this->paginate = array(
			'conditions' => array(
				'Attachment.foreign_key' => $foreign_key,
				'Attachment.model' => $model
			),
			'fields' => array(
			),
			'order' => array('Attachment.id' => 'ASC'),
			'limit' => $this->getPageLimit(),
			'recursive' => 0
		);

		$data = $this->paginate( 'Attachment' );
		$backUrl = $this->getIndexUrl($model, $foreign_key);

		$this->set('backUrl', $backUrl);
		$this->set('title', $title);
		$this->set('data', $data);
		$this->set('model', $model);
		$this->set('foreign_key', $foreign_key);
	}

	/*public function delete( $id = null, $model = null, $foreign_key = null ) {
		$this->set('title_for_layout', __('Attachment'));
		$this->set('subtitle_for_layout', __('Delete a Attachment.'));
		$id = (int) $id;

		$data = $this->Attachment->find( 'first', array(
			'conditions' => array(
				'Attachment.id' => $id
			)
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Attachment->delete($id)) {
				$this->Session->setFlash(__('Attachment was successfully deleted.'), FLASH_OK);
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
			}

			$this->redirect( array( 'controller' => 'attachments', 'action' => 'index', $data['Attachment']['model'], $data['Attachment']['foreign_key']) );
		}
		else {
			$this->request->data = $data;
		}
	}*/

	public function delete($id) {
		$this->set('title_for_layout', __('Attachment'));
		// $this->set('subtitle_for_layout', __('Delete a Attachment.'));

		$this->allowOnlyAjax();

		$id = (int) $id;

		$data = $this->Attachment->find('first', array(
			'conditions' => array(
				'Attachment.id' => $id
			)
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$model = $data['Attachment']['model'];
		$foreign_key = $data['Attachment']['foreign_key'];

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Attachment->delete($id)) {
				$this->Attachment->logAttachment($data, true);
				$this->Session->setFlash(__('Attachment was successfully deleted.'), FLASH_OK);
				// $this->set('okMessage', __('Attachment was successfully deleted.'));
			}
			else {
				$this->Session->setFlash(__('Error while deleting the data. Please try it again.'), FLASH_ERROR);
				// $this->set('errorMessage', __('Error occured. Please try again.'));
			}
			$this->Ajax->success();
		}
		else {
			$this->request->data = $data;
		}

		$this->set('data', $data);
		// $this->set('attachments', $this->Attachment->getByItem($model, $foreign_key));
		// $this->render('/Elements/ajax-ui/attachmentsList');
	}

	public function add( $model = null, $foreign_key = null ) {
		$this->set( 'title_for_layout', __( 'Upload Attachment' ) );
		$this->initAddEditSubtitle();

		if ( $this->request->is( 'post' ) ) {
			unset( $this->request->data['Attachment']['id'] );
			$this->request->data['Attachment']['user_id'] = $this->logged['id'];

			$this->Attachment->set( $this->request->data );

			if ( $this->Attachment->validates() ) {
				if ( $this->Attachment->save() ) {
					$this->ComplianceAudits->afterItemSave('Attachment');

					//log view of widget
					$this->loadModel($model);
					if ($this->{$model}->Behaviors->enabled('InspectLog')) {
						$this->{$model}->inspectLogWidget($foreign_key);
					}

					$this->Session->setFlash( __( 'Attachment was successfully added.' ), FLASH_OK );
					$this->redirect( array( 'controller' => 'attachments', 'action' => 'index', $model, $foreign_key ) );
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

		$data = array(
			'model' => $model,
			'foreign_key' => $foreign_key,
			'user_id' => !empty($this->logged['id']) ? $this->logged['id'] : null,
			'file' => $this->request->params['form']['file']
		);

		$this->Attachment->create();
		$this->Attachment->set($data);

		if ($this->Attachment->validates()) {
			$save = $this->Attachment->save();
			$save &= $this->ComplianceAudits->afterItemSave('Attachment', array(
				'Attachment' => $data
			));

			if ($save) {
				$additionalData = $this->Attachment->find('first', array(
					'conditions' => array(
						'Attachment.id' => $this->Attachment->id
					)
				));

				//log view of widget
				$this->loadModel($model);
				if ($this->{$model}->Behaviors->enabled('InspectLog')) {
					$this->{$model}->inspectLogWidget($foreign_key);
				}

				$ret = $this->NotificationSystemMgt->triggerHandler(array(
					'model' => 'Attachment',
					'callback' => 'afterSave'
				), $model, $foreign_key, $additionalData);
			}
			else {
				// $this->header('HTTP/1.1 500 Internal server error');
				// exit();
				$this->response->statusCode(400);
				$this->set('msg', __('Error occured while uploading the file.'));
				$this->render('../Errors/errorAttachments');
				return;
			}
		}
		else {
			$this->response->statusCode(400);

			$this->set('msg', __('The only file types allowed are: %s', implode(', ', array_keys(getAllowedAttachmentExtensions()))));
			$this->render('../Errors/errorAttachments');
			return;
			// $this->header('HTTP/1.1 403 Forbidden');
			// $this->cakeError('errorAttachments');
			// throw new BadRequestException(__('This file type is not allowed.'));
			
		}

		exit(0);
	}

	public function getList($model, $foreign_key) {
		$this->allowOnlyAjax();

		$this->set('model', $model);
		$this->set('foreign_key', $foreign_key);
		$this->set('attachments', $this->Attachment->getByItem($model, $foreign_key));

		$this->render('/Elements/ajax-ui/attachmentsList');
	}

	private function initAddEditSubtitle() {
		$this->set( 'subtitle_for_layout', '' );
	}

	/**
	 * Generic action handler to download a file respecting ACL access rules.
	 *
	 * @deprecated Replaced with AppController::downloadAttachment() and will be removed.
	 */
	public function download($id) {
		$file = $this->Attachment->getFile($id);

		return $this->redirect(array(
			'controller' => controllerFromModel($file['Attachment']['model']),
			'action' => 'downloadAttachment',
			$id
		));
	}

	public function getIndexUrlFromComponent($model, $foreign_key) {
		return parent::getIndexUrl($model, $foreign_key);
	}

	public function initEmailFromComponent($to, $subject, $template, $data = array(), $layout = 'default', $from = NO_REPLY_EMAIL, $type = 'html') {
		return parent::initEmail($to, $subject, $template, $data, $layout, $from, $type);
	}

}
