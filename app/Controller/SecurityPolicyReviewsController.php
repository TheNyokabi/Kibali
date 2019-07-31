<?php
/**
 * @deprecated in favor of general Review section
 */
class SecurityPolicyReviewsController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session', 'Paginator', 'Ajax' => array(
		'actions' => array('index', 'edit', 'add', 'delete'),
		'redirects' => array(
			'index' => array(
				'url' => array('controller' => 'goals', 'action' => 'index')
			)
		)
	));

	/**
	 * Reviews index
	 * @param  int $id Security Policy ID.
	 */
	public function index($id) {
		$this->loadModel('SecurityPolicy');
		$title = $this->SecurityPolicy->getRecordTitle($id);

		if ($title) {
			$this->set('title_for_layout', __('Reviews for "%s"', $title));
		}
		else {
			$this->set('title_for_layout', __('Reviews'));
		}

		$this->set('subtitle_for_layout', __('Reviews for this item.'));

		$this->paginate = array(
			'conditions' => array(
				'SecurityPolicyReview.security_policy_id' => $id
			),
			'fields' => array(
			),
			'order' => array('SecurityPolicyReview.created' => 'DESC'),
			'limit' => $this->getPageLimit(),
			'recursive' => 1
		);

		$data = $this->paginate('SecurityPolicyReview');

		$this->initOptions();
		$this->set('data', $data);
	}

	public function edit($id = null) {
		$id = (int) $id;

		if ( ! empty( $this->request->data ) ) {
			$id = (int) $this->request->data['SecurityPolicyReview']['id'];
		}

		$data = $this->SecurityPolicyReview->find( 'first', array(
			'conditions' => array(
				'SecurityPolicyReview.id' => $id
			),
			'recursive' => -1
		) );

		if ( empty( $data ) ) {
			throw new NotFoundException();
		}
		$this->Ajax->processEdit($id);


		$this->set( 'edit', true );
		$this->set( 'title_for_layout', __( 'Edit a Review' ) );

		$backUrl = array('controller' => 'securityPolicyReviews', 'action' => 'index', $data['SecurityPolicyReview']['security_policy_id']);
		$this->set('backUrl', $backUrl);
		$this->set('id', $id);

		if ( $this->request->is( 'post' ) || $this->request->is( 'put' ) ) {
			$this->loadModel('SecurityPolicyReview');
			$this->SecurityPolicyReview->set($this->request->data);

			if ( $this->SecurityPolicyReview->validates() ) {
				$dataSource = $this->SecurityPolicyReview->getDataSource();
				$dataSource->begin();

				$ret = $this->SecurityPolicyReview->save(null);

				if ($ret) {
					$dataSource->commit();
					$this->Ajax->success();

					$this->Session->setFlash( __( 'Review was successfully edited.' ), FLASH_OK );
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
			$this->request->data = $data;
		}

		$this->initOptions();
		$this->render( 'add' );
	}

	public function delete($id = null) {
		$this->set('title_for_layout', __('Review'));
		$this->set('subtitle_for_layout', __('Delete a Review.'));

		$data = $this->SecurityPolicyReview->find('first', array(
			'conditions' => array(
				'SecurityPolicyReview.id' => $id
			),
			'fields' => array('id', 'security_policy_id'),
			'recursive' => -1
		));

		if (empty($data)) {
			throw new NotFoundException();
		}

		$backUrl = array('controller' => 'securityPolicyReviews', 'action' => 'index', $data['SecurityPolicyReview']['security_policy_id']);
		$this->set('backUrl', $backUrl);

		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->SecurityPolicyReview->delete($id)) {
				$this->Session->setFlash(__('Review was successfully deleted.'), FLASH_OK);
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

	private function initOptions() {
		$this->set('reviewers', $this->getUsersList());
	}
}
