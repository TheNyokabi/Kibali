<?php
App::uses('SectionBase', 'Model');
App::uses('ImportToolModule', 'ImportTool.Lib');
App::uses('InheritanceInterface', 'Model/Interface');

class Process extends SectionBase implements InheritanceInterface {
	public $displayField = 'name';
	
	public $actsAs = array(
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'name', 'description'
			)
		),
		'CustomFields.CustomFields',
		'AuditLog.Auditable',
		'Utils.SoftDelete',
		'Visualisation.Visualisation',
	);

	public $mapping = array(
		'indexController' => 'processes',
		/*'indexController' => array(
			'basic' => 'processes',
			'advanced' => 'process',
			'action' => 'index',
			'params' => array('business_unit_id')
		),*/
		'titleColumn' => 'name',
		'logRecords' => true,
		'notificationSystem' => true,
		'workflow' => false
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		),
		'description' => array(
		),
		'rto' => array(
			'rule' => 'numeric',
			'required' => true,
			'allowEmpty' => false
		),
		'rpo' => array(
			'rule' => 'numeric',
			'required' => true,
			'allowEmpty' => false
		)
	);

	public $belongsTo = array(
		'BusinessUnit'
	);

	public $hasMany = array(
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'Process'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'Process'
			)
		),
		'SystemRecord' => array(
			'className' => 'SystemRecord',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'SystemRecord.model' => 'Process'
			)
		),
		'NotificationObject' => array(
			'className' => 'NotificationObject',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'NotificationObject.model' => 'Process'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Processes');
		$this->_group = 'organization';

		$this->fieldGroupData = [
			'default' => [
				'label' => __('General')
			]
		];

		$this->fieldData = [
			'business_unit_id' => [
				'label' => __('Business Unit'),
				'editable' => true,
			],
			'name' => [
				'label' => __('Name'),
				'editable' => true,
				'description' => __('The name of the process in the scope of this business unit. For example "Payroll", "Hiring".'),
			],
			'description' => [
				'label' => __('Description'),
				'editable' => true,
				// 'description' => __(''),
			],
			'rto' => [
				'label' => __('RTO'),
				'editable' => true,
				'description' => __('OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. How long (in days, hours, etc) should it take to get things back on (this should be less or equal to MTO). Most business people usually reply ASAP, but in practice that might not be accurate.'),
			],
			'rpo' => [
				'label' => __('MTO'),
				'editable' => true,
				'description' => __('OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. How long things can be broken before they become a serious business problem.'),
			],
			'rpd' => [
				'label' => __('Revenue per Hour'),
				'editable' => true,
				'description' => __('OPTIONAL: This value is only useful if you plan to BIA (Business Impact Analysis) your organisation with the Risk Management / Business Impact Analysis module. This should be used as a prioritization tool in order to Risk analyse those business that loss is too high and a mitigation -continuity plan- is cost effective.'),
			],
		];

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'name' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Process.name',
						'field' => 'Process.id',
					)
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Process.description',
						'field' => 'Process.id',
					)
				),
				'business_unit_id' => array(
					'type' => 'multiple_select',
					'name' => __('Business Unit'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Process.business_unit_id',
						'field' => 'Process.id',
					),
					'data' => array(
						'method' => 'getBusinessUnits'
					),
					'field' => 'BusinessUnit.name',
					'containable' => array(
						'BusinessUnit' => array(
							'fields' => array('name')
						)
					)
				),
				'rto' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('RTO'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Process.rto',
						'field' => 'Process.id',
					),
				),
				'rpo' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('MTO'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Process.rpo',
						'field' => 'Process.id',
					),
				),
				'rpd' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Revenue per Hour'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Process.rpd',
						'field' => 'Process.id',
					),
				),
			)
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Processes'),
			'pdf_file_name' => __('processes'),
			'csv_file_name' => __('processes'),
			'bulk_actions' => true,
			'history' => true,
            'trash' => true,
            'use_new_filters' => true
		);

		parent::__construct($id, $table, $ds);
	}

	public function parentModel() {
		return 'BusinessUnit';
	}

	public function parentNode() {
        return $this->visualisationParentNode('business_unit_id');
    }

	public function getBusinessUnits() {
		$data = $this->BusinessUnit->find('list', array(
			'order' => array('BusinessUnit.name' => 'ASC'),
			'recursive' => -1
		));
		return $data;
	}
}
