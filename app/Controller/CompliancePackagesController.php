<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');
App::uses('Hash', 'Utility');
App::uses('ThirdParty', 'Model');
App::uses('CompliancePackage', 'Model');

class CompliancePackagesController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = [];
	public $components = ['Search.Prg', 'AdvancedFilters',
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'ThirdParty' => [
							'fields' => ['ThirdParty.third_party_type_id']
						],
						'CompliancePackageItem' => [
							'order' => ['CompliancePackageItem.item_id' => 'ASC'],
							'fields' => ['id', 'item_id', 'name', 'description'],
							'Comment',
							'Attachment',
						]
					],
				]
			]
		]
	];

	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
	}

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Compliance Packages');
		$this->subTitle = __('Eramba treats compliance requirements as Compliance Packages. Each specific requirement is called Compliance Package Items. In this module you can upload (CSV format) compliance packages or simply create, edit and delete them using the interface.');
	}

	public function index() {
		$this->Crud->on('beforePaginate', array($this, '_beforePaginate'));
		$this->Crud->on('afterPaginate', array($this, '_afterPaginate'));

		$this->title = __('Compliance Package Database');

		$this->Prg->commonProcess('CompliancePackage');
		unset($this->request->data['CompliancePackage']);

		$filterConditions = $this->CompliancePackage->parseCriteria($this->Prg->parsedParams());
		if (!empty($filterConditions) && empty($this->request->query['advanced_filter'])) {
			$this->Paginator->settings['conditions'] = $filterConditions;
			$this->set('filterConditions', true);
		}

		$this->Crud->enable('index');

		return $this->Crud->execute();
	}

	public function _afterSave(CakeEvent $event) {
		if ($event->subject->success) {
			$this->redirect(['action' => 'index']);
		}
	}

	public function _beforePaginate(CakeEvent $e) {
		// debug(Debugger::exportVar($e->subject->paginator,4));exit;
		$e->subject->paginator->settings['order'] = [
			'CompliancePackage.third_party_id' => 'ASC'
		];

		$e->subject->paginator->settings['limit'] = 1000;

		// only regulator third parties are shown
		$additionalConds = CompliancePackage::thirdPartyListingConditions();
		$e->subject->paginator->settings['conditions'] = am(
			$e->subject->paginator->settings['conditions'],
			$additionalConds
		);

		$groupList = $e->subject->model->ThirdParty->find('list', [
			'fields' => [
				'ThirdParty.id', 'ThirdParty.name',
			],
			'group' => ['ThirdParty.id'],
			'joins' => [
				[
					'alias' => 'CompliancePackage',
					'table' => 'compliance_packages',
					'type' => 'INNER',
					'conditions' => [
						'ThirdParty.id = CompliancePackage.third_party_id'
					]
				]
			],
			'recursive' => -1
		]);
		// debug($groupList);exit;

		$e->subject->controller->set('groupList', $groupList);

	}

	public function _afterPaginate(CakeEvent $e) {
		$data = Hash::combine($e->subject->items, '{n}.CompliancePackage.id', '{n}', '{n}.CompliancePackage.third_party_id');
		$e->subject->items = $data;
	}

	public function delete( $id = null ) {
		$this->subTitle = __('Delete a Compliance Package.');

		return $this->Crud->execute();
	}

	public function trash() {
		$this->set('title_for_layout', __('Compliance Packages (Trash)'));

		return $this->Crud->execute();
	}

	public function add($tp_id = null) {
		$this->title = __('Create a Compliance Package');

		$this->set('selected', $tp_id);
		$this->initOptions();

		$this->Crud->on('afterSave', array($this, '_afterSave'));

		return $this->Crud->execute();
	}

	public function edit($id = null) {
		$this->title = __('Edit a Compliance Package');

		$this->initOptions();

		return $this->Crud->execute();
	}

	/**
	 * Initialize options for join elements.
	 */
	private function initOptions() {
		$third_parties = $this->CompliancePackage->ThirdParty->find('list', array(
			'conditions' => CompliancePackage::thirdPartyListingConditions(),
			'order' => array('ThirdParty.name' => 'ASC'),
			'recursive' => -1
		));

		$this->set( 'third_parties', $third_parties );
	}

	private function initAddEditSubtitle() {
		$this->subTitle = false;
	}

	/**
	* CSV Import functionality for all controllers
	*
	*/
	public function import() {
		$this->set( 'title_for_layout', __( 'Upload a Complete Compliance Package' ) );
		$this->set( 'subtitle_for_layout', __( 'You can upload a complete compliance package from your computer using a Comma Separated File (CSV).' ) );

		if ( $this->request->is( 'post' ) ) {
			$this->CompliancePackage->set( $this->request->data );
			if ( $this->CompliancePackage->validates() ) {

				$third_party_id = $this->request->data['CompliancePackage']['third_party_id'];
				$tmp_name = $this->request->data['CompliancePackage']['CsvFile']['tmp_name'];
				if ( ( $handle = fopen( $tmp_name, 'r' ) ) !== FALSE ) {
					$last_cp = false;
					$cp_tmp = $cpi_tmp = array();
					$has_error = false;

					$dataSource = $this->CompliancePackage->getDataSource();
					$dataSource->begin();

					$ret = true;
					while ( ( $data = fgetcsv( $handle, 0, ',' ) ) !== FALSE ) {
						if ( count( $data ) != 7 ) {
							$has_error = true;
							continue;
						}

						if ( ! $last_cp || $last_cp != $data[1] ) {
							$last_cp = $data[1];
							$ret &= $this->importSave( $cp_tmp, $cpi_tmp );

							$cp_tmp = $cpi_tmp = array();
							$cp_tmp = array(
								'package_id' => $data[0],
								'name' => $data[1],
								'description' => $data[2],
								'third_party_id' => $third_party_id
							);
						}

						$cpi_tmp[] = array(
							'item_id' => $data[3],
							'name' => $data[4],
							'description' => $data[5],
							'audit_questionaire' => $data[6],
						);
					}
					$ret &= $this->importSave( $cp_tmp, $cpi_tmp );
					fclose( $handle );

					$Compliance = ClassRegistry::init('ComplianceManagement');
					$ret &= $Compliance->syncObjects();
					if ($ret) {
						$dataSource->commit();
							if ( $has_error ) {
							$this->Session->setFlash( __( 'CSV file was imported but one or more lines of the file were incomplete.' ), FLASH_WARNING );
						} else {
							$this->Session->setFlash( __( 'CSV file was successfully imported.' ), FLASH_OK );
						}
					}
					else {
						$dataSource->rollback();
						$this->Session->setFlash(__('Error occured while saving data. Please check the file and correct any missing data and then try it again.'), FLASH_ERROR);
					}

					$this->redirect( array( 'controller' => 'compliancePackages', 'action' => 'index' ) );
				}
			} else {
				$this->Session->setFlash( __( 'One or more inputs you entered are invalid. Please try again.' ), FLASH_ERROR );
			}
		} else {
		}

		$this->initOptions();
	}

	/**
	 * Save imported values for compliance package and compliance package items.
	 */
	private function importSave( $cp_arr = array(), $cpi_arr = array() ) {
		if ( empty( $cp_arr ) ) {
			return true;
		}

		$this->CompliancePackage->create();
		$ret = $this->CompliancePackage->save( $cp_arr );
		if (empty($ret) || empty($this->CompliancePackage->id)) {
			return false;
		}

		$cp_id = $this->CompliancePackage->id;

		foreach ( $cpi_arr as $key => $cpi ) {
			$cpi_arr[ $key ]['compliance_package_id'] = $cp_id;
		}

		$ret &= $this->CompliancePackage->CompliancePackageItem->saveAll( $cpi_arr, array('atomic' => false) );

		return $ret;
	}

	/**
	* Make a full copy from one of your existing Third Parties with Compliance Package keeping all settings.
	*/
	public function duplicate() {
		$this->set('title_for_layout', __('Duplicate a Compliance Package'));
		$this->set('subtitle_for_layout', __('Make a full copy of a Compliance Package, keeping it\'s original settings'));

		$this->set(array(
			'thirdParties' => $this->CompliancePackage->CompliancePackageItem->ComplianceManagement->getThirdParties()
		));

		if ($this->request->is('post') || $this->request->is('put')) {

			//first validate the duplication form before actually duplicating the objects
			$this->CompliancePackage->ThirdParty->validator()->add('third_party_id', 'notEmpty', array(
				'rule' => 'notBlank',
				'required' => true,
				'allowEmpty' => false,
				'message' => __('This field is required')
			));

			$this->CompliancePackage->ThirdParty->set($this->request->data);
			$preFormValidate = $this->CompliancePackage->ThirdParty->validates(array(
				'fieldList' => array('third_party_id', 'name')
			));

			if ($preFormValidate) {

				// remove the temporary validation rule for duplication form
				$this->CompliancePackage->ThirdParty->validator()->remove('third_party_id');

				// find and prepare all data associations for a duplicated save
				$data = $this->CompliancePackage->ThirdParty->find('first', array(
					'conditions' => array(
						'ThirdParty.id' => $this->request->data['ThirdParty']['third_party_id']
					),
					'contain' => array(

						//@todo try to optimize this with upcoming functionality migration
						'CompliancePackage' => array(
							'CompliancePackageItem' => array(
								'ComplianceManagement' => array(
									'Comment',
									'SecurityService' => array(
										'fields' => array('id')
									),
									'SecurityPolicy' => array(
										'fields' => array('id')
									),
									'Risk' => array(
										'fields' => array('id')
									),
									'ThirdPartyRisk' => array(
										'fields' => array('id')
									),
									'BusinessContinuity' => array(
										'fields' => array('id')
									),
									'Project' => array(
										'fields' => array('id')
									)
								)
							)
						)
					)
				));

				if (empty($data)) {
					throw new NotFoundException();
				}

				//@todo perform a more dynamic merge of data from duplicate form
				// $data = Hash::merge($data, $this->request->data);
				$data['ThirdParty']['name'] = $this->request->data['ThirdParty']['name'];

				//remove data we dont want to save
				unset($data['ThirdParty']['id']);
				unset($data['ThirdParty']['created']);
				unset($data['ThirdParty']['modified']);

				$data = Hash::remove($data, 'CompliancePackage.{n}.id');
				$data = Hash::remove($data, 'CompliancePackage.{n}.created');
				$data = Hash::remove($data, 'CompliancePackage.{n}.modified');

				$data = Hash::remove($data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.id');
				$data = Hash::remove($data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.created');
				$data = Hash::remove($data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.modified');

				$data = Hash::remove(
					$data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.ComplianceManagement.id'
				);
				$data = Hash::remove(
					$data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.ComplianceManagement.compliance_package_item_id'
				);
				$data = Hash::remove(
					$data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.ComplianceManagement.created'
				);
				$data = Hash::remove(
					$data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.ComplianceManagement.modified'
				);

				$data = Hash::remove(
					$data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.ComplianceManagement.Comment.{n}.id'
				);
				$data = Hash::remove(
					$data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.ComplianceManagement.Comment.{n}.created'
				);
				$data = Hash::remove(
					$data, 'CompliancePackage.{n}.CompliancePackageItem.{n}.ComplianceManagement.Comment.{n}.modified'
				);

				// handle HABTM relation saving in the Cake way
				foreach ($data['CompliancePackage'] as $packageKey => &$package) {
					foreach ($package['CompliancePackageItem'] as $key => &$item) {
						if (!isset($item['ComplianceManagement'])) {
							continue;
						}

						$item['ComplianceManagement']['Project'] = $this->parseComplianceHabtmJoins($item['ComplianceManagement'], 'Project');
						$item['ComplianceManagement']['SecurityService'] = $this->parseComplianceHabtmJoins($item['ComplianceManagement'], 'SecurityService');
						$item['ComplianceManagement']['SecurityPolicy'] = $this->parseComplianceHabtmJoins($item['ComplianceManagement'], 'SecurityPolicy');
						$item['ComplianceManagement']['Risk'] = $this->parseComplianceHabtmJoins($item['ComplianceManagement'], 'Risk');
						$item['ComplianceManagement']['ThirdPartyRisk'] = $this->parseComplianceHabtmJoins($item['ComplianceManagement'], 'ThirdPartyRisk');
						$item['ComplianceManagement']['BusinessContinuity'] = $this->parseComplianceHabtmJoins($item['ComplianceManagement'], 'BusinessContinuity');
					}
				}

				$this->CompliancePackage->ThirdParty->set($data);

				$dataSource = $this->CompliancePackage->ThirdParty->getDataSource();
				$dataSource->begin();

				$ret = $this->CompliancePackage->ThirdParty->saveAssociated($data, array(
					'deep' => true
				));
				
				if ($ret) {
					$dataSource->commit();

					$this->Session->setFlash(__('Compliance Package was successfully duplicated.'), FLASH_OK);
					$this->redirect(array('controller' => 'compliancePackages', 'action' => 'index', $this->CompliancePackage->ThirdParty->id));
				}
				else {
					$dataSource->rollback();
					$this->Session->setFlash(__('Error occured while saving the duplicated data. Please try it again.'), FLASH_ERROR);
				}
			}
		}
	}

	/**
	 * Helper method to parse whats is joined with a ComplianceManagement for duplication.
	 */
	protected function parseComplianceHabtmJoins($complianceManagementData, $modelName) {
		return Hash::extract($complianceManagementData, $modelName . '.{n}.id');
	}
}
