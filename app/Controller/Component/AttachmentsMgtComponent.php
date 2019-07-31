<?php
App::uses('Component', 'Controller');

class AttachmentsMgtComponent extends Component {

	public function startup(Controller $controller) {
		$this->controller = $controller;

		$this->controller->loadModel('Attachment');
		$this->Attachment = $this->controller->Attachment;
	}

	/**
	 * Prepares an attachment and forces a download to the client.
	 * 
	 * @param  int $id 			Attachment ID.
	 * @return CakeResponse     Response.
	 */
	public function download($id) {
		$this->_prepareFile($id);

		// Return response object to prevent controller from trying to render a view
		return $this->controller->response;
	}

	/**
	 * Configure a CakeResponse $response for a file.
	 * 
	 * @param  int $id Attachment ID.
	 * @return void
	 */
	protected function _prepareFile($id) {
		$file = $this->Attachment->getFile($id);
		$this->controller->response->file('webroot' . $file['Attachment']['filename'], array(
			'download' => true,
			'name' => basename($file['Attachment']['filename'])
		));
	}

}
