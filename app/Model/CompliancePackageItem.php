<?php
App::uses('Hash', 'Utility');

class CompliancePackageItem extends AppModel {
	public $displayField = 'name';
	
	public $actsAs = array(
		'Containable',
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array(
				'item_id', 'name', 'description', 'audit_questionaire'
			)
		)
	);

	public $mapping = array(
		// 'indexController' => 'compliancePackages',
		'indexController' => array(
			'basic' => 'compliancePackages',
			'advanced' => 'compliancePackageItems',
			'params' => array('compliance_package_id')
		),
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
		'item_id' => array(
			'rule' => 'notBlank',
			'required' => true,
			'allowEmpty' => false
		)
	);

	public $belongsTo = array(
		'CompliancePackage'
	);

	public $hasOne = array(
		'ComplianceManagement'
	);

	public $hasMany = array(
		'ComplianceFinding',
		'ComplianceAuditSetting',
		'Attachment' => array(
			'className' => 'Attachment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Attachment.model' => 'CompliancePackageItem'
			)
		),
		'Comment' => array(
			'className' => 'Comment',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'Comment.model' => 'CompliancePackageItem'
			)
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Compliance Package Items');

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'item_id' => array(
					'type' => 'text',
					'name' => __('Item ID'),
					'show_default' => true,
					'filter' => array(
						'type' => 'like',
					),
				),
				'name' => array(
					'type' => 'text',
					'name' => __('Name'),
					'show_default' => true,
					'filter' => array(
						'type' => 'like',
					),
				),
				'description' => array(
					'type' => 'text',
					'name' => __('Description'),
					'show_default' => true,
					'filter' => array(
						'type' => 'like',
					),
				),
				'compliance_audit_id' => array(
					'type' => 'select',
					'name' => __('Compliance Audits'),
					'show_default' => true,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findByComplianceAudit',
						'field' => 'CompliancePackageItem.id'
					),
					'data' => array(
						'method' => 'getComplianceAudits'
					),
					'many' => true,
					'field' => 'CompliancePackage.ThirdParty.ComplianceAudit.{n}.name',
					'containable' => array(
						'CompliancePackage' => array(
							'fields' => array('id'),
							'ThirdParty' => array(
								'fields' => array('id'),
								'ComplianceAudit' => array(
									'fields' => array('id', 'name'),
								)
							)
						)
					),
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Compliance Package Item'),
			'pdf_file_name' => __('compliance_package_items'),
			'csv_file_name' => __('compliance_package_items'),
			'actions' => false,
			'reset' => array(
				'controller' => 'complianceAudits',
				'action' => 'index'
			)
		);

		parent::__construct($id, $table, $ds);
	}

	public function findByComplianceAudit($data = array(), $filter) {
		$this->ComplianceAuditSetting->Behaviors->attach('Containable', array('autoFields' => false));
		$this->ComplianceAuditSetting->Behaviors->attach('Search.Searchable');

		$query = $this->ComplianceAuditSetting->getQuery('all', array(
			'conditions' => array(
				'ComplianceAuditSetting.compliance_audit_id' => $data[$filter['name']]
			),
			'fields' => array(
				'ComplianceAuditSetting.compliance_package_item_id'
			),
			'contain' => array()
		));

		return $query;
	}

	public function getComplianceAudits() {
		$data = $this->CompliancePackage->ThirdParty->ComplianceAudit->find('list', array(
			'fields' => array('ComplianceAudit.id', 'ComplianceAudit.name'),
			'joins' => array(
				array(
					'table' => 'third_parties',
					'alias' => 'ThirdParty',
					'type' => 'INNER',
					'conditions' => array(
						'ThirdParty.id = ComplianceAudit.third_party_id'
					),
				),
				array(
					'table' => 'compliance_packages',
					'alias' => 'CompliancePackage',
					'type' => 'INNER',
					'conditions' => array(
						'ThirdParty.id = CompliancePackage.third_party_id'
					)
				),
				array(
					'table' => 'compliance_package_items',
					'alias' => 'CompliancePackageItem',
					'type' => 'INNER',
					'conditions' => array(
						'CompliancePackage.id = CompliancePackageItem.compliance_package_id'
					)
				),
				array(
					'table' => 'compliance_audit_settings',
					'alias' => 'ComplianceAuditSetting',
					'type' => 'INNER',
					'conditions' => array(
						'CompliancePackageItem.id = ComplianceAuditSetting.compliance_package_item_id'
					)
				),
			),
			'group' => array('ComplianceAudit.id'),
			'contain' => array()
		));

		return $data;
	}

	public function afterSave($created, $options = array()) {
		//create audit setting records
		if ($created) {
			$data = $this->CompliancePackage->find('first', array(
				'conditions' => array(
					'CompliancePackage.id' => $this->data[$this->name]['compliance_package_id']
				),
				'fields' => array('id'),
				'contain' => array(
					'ThirdParty' => array(
						'fields' => array('id'),
						'ComplianceAudit' => array(
							'fields' => array('id')
						)
					)
				)
			));
			
			$ret = true;
			foreach ($data['ThirdParty']['ComplianceAudit'] as $audit) {
				$ret &= $this->ComplianceAuditSetting->syncSettings($audit['id']);
			}

			return $ret;
		}
		
		return true;
	}

	public function getItem($id) {
		$data = $this->find('first', array(
			'conditions' => array(
				'id' => $id
			),
			'recursive' => -1
		));

		return $data;
	}

	/**
	 * Binds an audit setting based on Audit ID.
	 */
	public function bindSingleComplianceAuditSetting($auditId) {
		$this->bindModel(array(
			'hasOne' => array(
				'ComplianceAuditSettingSingle' => array(
					'className' => 'ComplianceAuditSetting',
					'conditions' => array(
						'ComplianceAuditSettingSingle.compliance_audit_id' => $auditId
					)
				)
			)
		));
	}
}
