<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');
App::uses('AdvancedFiltersComponent', 'Controller/Component');

class ComplianceFindingsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = [];
	public $components = ['Paginator', 'AdvancedFilters', 'Pdf', 'NotificationSystemMgt', 'ObjectStatus.ObjectStatus',
		'Ajax' => [
			'actions' => ['add', 'edit', 'delete']
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'className' => 'Filter',
				]
			]
		],
		'Visualisation.Visualisation'
	];

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Compliance Findings');
		$this->subTitle = __('This is the list of audit findings for a given audit.');
	}

	public function index($compliance_audit_id = null) {
        $response = $this->handleCrudAction('index');

		if ($response === false) {
			$this->redirect(array('controller' => 'complianceAudits', 'action' => 'index'));
		}
	}

	public function trash() {
		$this->set('title_for_layout', __('Compliance Findings (Trash)'));
		$this->set('subtitle_for_layout', __('This is the list of audit findings for a given audit.'));

		return $this->Crud->execute();
	}

	public function delete($id = null) {
		$this->subTitle = __('Delete a Compliance Finding.');

		return $this->Crud->execute();
	}

	public function add($compliance_audit_id = null, $compliance_package_item_id = null) {
		$this->title = __('Register a Compliance Finding');
		$this->initAddEditSubtitle();

		$this->loadModel('ComplianceAudit');
		$data = $this->ComplianceAudit->find('first', [
			'conditions' => ['ComplianceAudit.id' => $compliance_audit_id]
		]);
		if (empty($data)) {
			throw new NotFoundException();
		}

		$packageItem = $this->ComplianceFinding->CompliancePackageItem->getItem($compliance_package_item_id);
		$this->set('packageItem', $packageItem);
		$this->set('compliance_audit_id', $compliance_audit_id);
		$this->set('compliance_package_item_id', $compliance_package_item_id);
		$this->set('compliance_package_item_name', $this->getCompliancePackageItemName($compliance_package_item_id));

		$this->initOptions();

		if ($this->request->is('post')) {
			$this->invalidateDependencies();
		}

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->Crud->execute();
	}

	public function _afterSave(CakeEvent $event) {
		if ($event->subject->success) {
			$this->manageCreatedNotifications($event->subject->id);
		}
	}

	/**
	 * Send out notifications about created finding.
	 */
	private function manageCreatedNotifications($id) {
		$ret = true;

		$this->NotificationSystemMgt->setupDefaultTypes();
		if ($this->request->data['ComplianceFinding']['type'] == COMPLIANCE_FINDING_AUDIT) {
			$ret &= $this->NotificationSystemMgt->triggerHandler(array(
				'model' => 'ComplianceFinding',
				'callback' => 'afterSave',
				'type' => 'AuditFindingCreated',
			), 'ComplianceFinding', $id, array());
		}

		if ($this->request->data['ComplianceFinding']['type'] == COMPLIANCE_FINDING_ASSESED) {
			$ret &= $this->NotificationSystemMgt->triggerHandler(array(
				'model' => 'ComplianceFinding',
				'callback' => 'afterSave',
				'type' => 'AuditAssessedCreated',
			), 'ComplianceFinding', $id, array());
		}

		return $ret;
	}

	public function edit($id = null) {
		$this->title = __('Edit a Compliance Audit');
		$this->initAddEditSubtitle();

		$data = $this->ComplianceFinding->find('first', array(
			'conditions' => array(
				'ComplianceFinding.id' => $id
			),
			'recursive' => 1
		));
		if (empty($data)) {
			throw new NotFoundException();
		}

		$compliance_audit_id = $data['ComplianceFinding']['compliance_audit_id'];
		$compliance_package_item_id = $data['ComplianceFinding']['compliance_package_item_id'];
		$packageItem = $this->ComplianceFinding->CompliancePackageItem->getItem($compliance_package_item_id);
		
		$this->set('packageItem', $packageItem);
		$this->set( 'compliance_audit_id', $compliance_audit_id );
		$this->set( 'compliance_package_item_id', $compliance_package_item_id );
		$this->set( 'compliance_package_item_name', $this->getCompliancePackageItemName( $compliance_package_item_id ) );

		$this->initOptions();

		return $this->Crud->execute();
	}

	private function addJoins($id) {
		$ret = true;
		$ret &= $this->joinClassifications($this->request->data['ComplianceFinding']['classifications'], $this->ComplianceFinding->id);
		$ret &= $this->joinComplianceExceptions($this->request->data['ComplianceFinding']['compliance_exception_id'], $this->ComplianceFinding->id);
		$ret &= $this->joinThirdPartyRisks($this->request->data['ComplianceFinding']['third_party_risk_id'], $this->ComplianceFinding->id);

		return $ret;
	}

	/**
	 * Delete all many to many joins in related tables.
	 * @param  integer $id Risk ID
	 */
	private function deleteJoins( $id ) {
		$ret = $this->ComplianceFinding->Classification->deleteAll(array(
			'Classification.compliance_finding_id' => $id
		));

		$ret &= $this->ComplianceFinding->ComplianceExceptionsComplianceFinding->deleteAll(array(
			'ComplianceExceptionsComplianceFinding.compliance_finding_id' => $id
		));

		$ret &= $this->ComplianceFinding->ComplianceFindingsThirdPartyRisk->deleteAll(array(
			'ComplianceFindingsThirdPartyRisk.compliance_finding_id' => $id
		));

		return $ret;
	}

	private function joinClassifications($labels, $id) {
		if (empty($labels)) {
			return true;
		}

		$labels = explode(',', $labels);

		foreach ($labels as $name) {
			$tmp = array(
				'compliance_finding_id' => $id,
				'name' => $name
			);

			$this->ComplianceFinding->Classification->create();
			if (!$this->ComplianceFinding->Classification->save($tmp)) {
				return false;
			}
		}

		return true;
	}

	private function joinComplianceExceptions($list, $complianceFindingId) {
		if (!is_array($list)) {
			return true;
		}

		foreach ($list as $id) {
			$tmp = array(
				'compliance_finding_id' => $complianceFindingId,
				'compliance_exception_id' => $id
			);

			$this->ComplianceFinding->ComplianceExceptionsComplianceFinding->create();
			if (!$this->ComplianceFinding->ComplianceExceptionsComplianceFinding->save($tmp)) {
				return false;
			}
		}

		return true;
	}

	private function joinThirdPartyRisks($list, $complianceFindingId) {
		if (!is_array($list)) {
			return true;
		}

		foreach ($list as $id) {
			$tmp = array(
				'compliance_finding_id' => $complianceFindingId,
				'third_party_risk_id' => $id
			);

			$this->ComplianceFinding->ComplianceFindingsThirdPartyRisk->create();
			if (!$this->ComplianceFinding->ComplianceFindingsThirdPartyRisk->save($tmp)) {
				return false;
			}
		}

		return true;
	}

	private function getCompliancePackageItemName( $id = null ) {
		if ( $id == null ) {
			return false;
		}
		$id = (int) $id;

		$this->loadModel('CompliancePackageItem');
		$data = $this->CompliancePackageItem->find('first', array(
			'conditions' => array(
				'CompliancePackageItem.id' => $id
			),
			'fields' => array('CompliancePackageItem.name')
		));

		return $data['CompliancePackageItem']['name'];
	}

	private function initOptions() {
		$statuses = $this->ComplianceFinding->ComplianceFindingStatus->find('list', array(
			'order' => array('ComplianceFindingStatus.name' => 'ASC'),
			'recursive' => -1
		));

		$types = array(
			1 => __('Audit Finding'),
			2 => __('Assesed Item')
		);

		$classificationsTmp = $this->ComplianceFinding->Classification->find('list', array(
			'order' => array('Classification.name' => 'ASC'),
			'fields' => array('Classification.id', 'Classification.name'),
			'group' => array('Classification.name'),
			'recursive' => -1
		));
		$classifications = array();
		foreach ($classificationsTmp as $c) {
			$classifications[] = $c;
		}

		$complianceExceptions = $this->ComplianceFinding->ComplianceException->find('list', array(
			'order' => array('ComplianceException.title' => 'ASC'),
			'recursive' => -1
		));

		$thirdPartyRisks = $this->ComplianceFinding->ThirdPartyRisk->find('list', array(
			'order' => array('ThirdPartyRisk.title' => 'ASC'),
			'recursive' => -1
		));

		$this->set( 'statuses', $statuses );
		$this->set( 'types', $types );
		$this->set( 'classifications', $classifications );
		$this->set('complianceExceptions', $complianceExceptions);
		$this->set('thirdPartyRisks', $thirdPartyRisks);
	}

	private function invalidateDependencies() {
		if ($this->request->data['ComplianceFinding']['type'] == COMPLIANCE_FINDING_AUDIT) {
		}

		if ($this->request->data['ComplianceFinding']['type'] == COMPLIANCE_FINDING_ASSESED) {
			unset($this->ComplianceFinding->validate['deadline']);
		}
	}

	private function initAddEditSubtitle() {
		$this->subTitle = __('Use this form to create or edit an audit finding. Audit findings are used by the auditor to record what has been reviewed and if there was some non-compliance.');
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->ComplianceFinding->find('first', array(
			'conditions' => array(
				'ComplianceFinding.id' => $id
			)
		));

		// debug($item);

		$this->set('item', $item);
		$vars = array(
			'item' => $item
		);

		$name = Inflector::slug($item['ComplianceFinding']['title'], '-');
		// $this->render('..'.DS.'ComplianceFindings'.DS.'export_pdf');

		$this->Pdf->renderPdf($name, '..'.DS.'ComplianceFindings'.DS.'export_pdf', 'pdf', $vars, true);
	}

	public function initEmailFromComponent($to, $subject, $template, $data = array(), $layout = 'default', $from = NO_REPLY_EMAIL, $type = 'html') {
		return parent::initEmail($to, $subject, $template, $data, $layout, $from, $type);
	}

	public function getIndexUrlFromComponent($model, $foreign_key) {
		return parent::getIndexUrl($model, $foreign_key);
	}

}
