<?php
App::uses('SectionBase', 'Model');
App::uses('UserFields', 'UserFields.Lib');

class BusinessContinuityTask extends SectionBase {
	public $displayField = 'step';

	public $mapping = array(
		'indexController' => 'businessContinuityPlans',
		'indexController' => array(
			'basic' => 'businessContinuityTasks',
			'advanced' => 'businessContinuityTasks',
			'params' => array('goal_id')
		),
		/*'titleColumn' => array(
			'model' => 'BusinessContinuityPlan'
		),*/
		'titleColumn' => false,
		'logRecords' => true,
		'notificationSystem' => true,
		'workflow' => false
	);

	public $actsAs = array(
		'Containable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'step', 'when', 'who', 'does',
				'where', 'how'
			)
		),
		// 'Visualisation.Visualisation',
		'AuditLog.Auditable',
		'UserFields.UserFields' => [
			'fields' => [
				'AwarenessRole' => [
					'mandatory' => false
				]
			]
		]
	);

	public $validate = array(
		'step' => array(
			'rule' => 'numeric',
			'required' => true,
			'allowEmpty' => false
		),
		'when' => array(
			'rule' => 'notBlank',
			'allowEmpty' => false,
			'required' => true
		),
		'who' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'user_id' => array(
			'rule' => 'notBlank'
		),
		'does' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'where' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'how' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		)
	);

	public $belongsTo = array(
		'BusinessContinuityPlan'
	);

	public $hasMany = array(
		'BusinessContinuityTaskReminder',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'BusinessContinuityTask'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'BusinessContinuityTask'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'BusinessContinuityTask'
			)
		),
		'NotificationObject' => array(
			'className' => 'NotificationObject',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'NotificationObject.model' => 'BusinessContinuityTask'
			)
		),
	);

	public function __construct($id = false, $table = null, $ds = null)
	{
		//
		// Init helper Lib for UserFields Module
		$UserFields = new UserFields();
		//
		
		$this->label = __('Business Continuity Tasks');

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			],
		];

		$this->fieldData = [
			'business_continuity_plan_id' => [
				'label' => __('Business Continuity Plan'),
				'editable' => false,
			],
			'step' => [
				'label' => __('Plan Step'),
				'editable' => true,
				'description' => __('In this plan, where this step goes? Example: 1, 4, 6, Etc.')
			],
			'when' => [
				'label' => __('When'),
				'editable' => true,
				'description' => __('When reading an emergency procedure, is important to know who does what in particular when! Example: no longer than 5 minutes after declared the crisis.')
			],
			'who' => [
				'label' => __('Who'),
				'editable' => true,
				'description' => __('Who is executing this task? This shoud be an individual, a group, Etc.')
			],
			'AwarenessRole' => $UserFields->getFieldDataEntityData($this, 'AwarenessRole', [
				'label' => __('Awareness Role'), 
				'description' => __('The individual selected can get notifications at regular points in time reminding him of his responsabilities in this plan.')
			]),
			'does' => [
				'label' => __('Does Something'),
				'editable' => true,
				'description' => __('Valid examples: Warms up engines, Starts passive DC infrastructure. There\'s no point in writting how in details that is to be done since you shouldnt expect someone to learn to do something while in the middle of an emergency')
			],
			'where' => [
				'label' => __('Where'),
				'editable' => true,
				'description' => __('Where is the task executed?')
			],
			'how' => [
				'label' => __('How'),
				'editable' => true,
				'description' => __('How is the task executed?')
			],
		];
		
		parent::__construct($id, $table, $ds);
	}
	
	public function getRecordTitle($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'BusinessContinuityTask.id' => $id
			),
			'fields' => array(
				'BusinessContinuityTask.step',
				'BusinessContinuityPlan.title'
			),
			'recursive' => 0
		));

		return sprintf('%s (%s)', $data['BusinessContinuityTask']['step'], $data['BusinessContinuityPlan']['title']);
	}
}
