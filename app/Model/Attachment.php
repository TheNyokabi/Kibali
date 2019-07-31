<?php
App::uses('SidebarWidgetTrait', 'Model/Trait');

class Attachment extends AppModel {
	use SidebarWidgetTrait;

	public $mapping = array(
		'titleColumn' => 'filename',
		'logRecords' => true
	);

	public $actsAs = array(
		'Uploader.Attachment' => array(
			'file' => array(
				'nameCallback' => 'formatName',
				'tempDir' => TMP,
				'dbColumn' => 'filename',
				'metaColumns' => array(
					'ext' => 'extension',
					'type' => 'mime_type',
					'size' => 'file_size'
				)
			)
		),
		'Uploader.FileValidation' => array(
			'file' => array(
				// extensions defined in __construct
				//'extension' => array('csv', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pps', 'gif', 'jpg', 'png', 'jpeg'),
				'required' => array(
					'rule' => array('required'),
					'message' => 'File required',
				)
			)
		)
	);

	public $belongsTo = array(
		'User'
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Attachments');

		$allowedFiles = getAllowedAttachmentExtensions();

		$this->actsAs['Uploader.FileValidation']['file']['extension'] = array_keys($allowedFiles);
		$this->actsAs['Uploader.FileValidation']['file']['mimeType'] = array_values($allowedFiles);

		parent::__construct($id, $table, $ds);
	}

	public function beforeFind($query) {
		// we explicitly set only current user's attachments for NotificationSystemItemLog
		if (isset($query['conditions']['Attachment.model']) && $query['conditions']['Attachment.model'] == 'NotificationSystemItemLog') {
			$currentUser = $this->currentUser();
			$query['conditions']['Attachment.user_id'] = $currentUser['id'];
		}

		$query = $this->widgetBeforeFind($query);
		
		return $query;
	}

	public function afterSave($created, $options = array()) {
		if ($created) {
			// clear the index widget cache when added
			Cache::clearGroup('widget_data', 'widget_data');

		}

		//Project ObjectStatus trigger
		$this->triggerProjectObjectStatus($this->id);

		$this->logAttachment($this->id);
	}

	public function logAttachment($id, $delete = false) {
		$models = [
			'VendorAssessmentFeedback' => 'VendorAssessments.VendorAssessmentFeedback'
		];

		if (is_array($id)) {
			$data = $id;
		}
		else {
			$data = $this->find('first', [
				'conditions' => [
					"{$this->alias}.id" => $id
				],
				'recursive' => -1
			]);
		}

		if (empty($data) || !isset($models[$data[$this->alias]['model']])) {
			return false;
		}

		$Model = ClassRegistry::init($models[$data[$this->alias]['model']]);

		return $Model->logAttachment($data, $delete);
	}

	/**
	 * Triggers dependent Project statuses.
	 */
	public function triggerProjectObjectStatus($id) {
		$data = $this->find('first', [
			'conditions' => [
				'Attachment.id' => $id
			],
			'recursive' => -1
		]);

		$triggerModels = [
			'Project', 'ProjectAchievement', 'ProjectExpense'
		];

		if (empty($data) || !in_array($data['Attachment']['model'], $triggerModels)) {
			return false;
		}

		$Model = ClassRegistry::init($data['Attachment']['model']);

		return $Model->triggerObjectStatus('no_updates', $data['Attachment']['foreign_key']);
	}

	/**
	 * Get attachment data.
	 *
	 * @param  int   $id Attachment ID.
	 * @return array     Data.
	 */
	public function getFile($id) {
		return $this->find('first', array(
			'conditions' => array(
				'Attachment.id' => $id
			),
			'recursive' => -1
		));
	}

	/**
	 * Retrieve attachments associated with an item.
	 */
	public function getByItem($model, $foreign_key) {
		return $this->find('all', array(
			'conditions' => array(
				'Attachment.model' => $model,
				'Attachment.foreign_key' => $foreign_key
			),
			'order' => array('Attachment.created' => 'DESC'),
			'recursive' => 0
		));
	}

	public function formatName($name, $file) {
		return Inflector::slug($name, '-');
	}
}
