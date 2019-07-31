<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class AssetsController extends SectionBaseController {
	use SectionCrudTrait;

	public $helpers = array('ImportTool.ImportTool', 'Assets');
	public $components = array(
		'Paginator', 'Pdf', 'Search.Prg', 'AdvancedFilters', 'ObjectStatus.ObjectStatus',
		'CustomFields.CustomFieldsMgt' => [
			'model' => 'Asset'
		],
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					'contain' => [
						'BusinessUnit' => [
							'Legal' => [
								'fields' => ['name']
							]
						],
						'AssetMediaType' => [
							'fields' => ['name']
						],
						'AssetLabel' => [
							'fields' => ['name']
						],
						'Legal' => [
							'fields' => ['name']
						],
						'AssetOwner' => [
							'fields' => ['name']
						],
						'AssetGuardian' => [
							'fields' => ['name']
						],
						'AssetUser' => [
							'fields' => ['name']
						],
						'SecurityIncident' => [],
						'Legal' => [
							'fields' => ['name']
						],
					]
				]
			],
			'listeners' => ['Api', 'ApiPagination']
		],
		'Visualisation.Visualisation',
		'ReviewsPlanner.Reviews'
	);

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Asset Identification');
		$this->subTitle = __('If you plan to perform Risk Management you will need to identify assets, use this section to document key assets (this is not an inventory tool) for each one of the business units you defined before (Organisations / Business Units).');
	}

	/**
	 * Section callback to set additional variables and options for an action.
	 */
	public function _beforeRender(CakeEvent $event) {
		//add/edit
		$this->initOptions();

		//index
		$this->set('assetClassificationData', $this->getAssetClassificationsData());
		$this->setRelatedAssets();

		parent::_beforeRender($event);
	}

	public function trash() {
		$this->set('title_for_layout', __('Asset Identification (Trash)'));
		return $this->Crud->execute();
	}

	private function setRelatedAssets() {
		$this->loadModel('AssetsRelated');
		$assetsRelated = $this->AssetsRelated->find('all', array(
			'recursive' => -1
		));
		// debug($assetsRelated);exit;

		$assetsRelatedData = array();
		foreach ($assetsRelated as $a) {
			// if (!isset($assetsRelatedData[$a['AssetsRelated']['asset_id']])) {
			// 	$assetsRelatedData[$a['AssetsRelated']['asset_id']] = array();
			// }

			$assetsRelatedData[$a['AssetsRelated']['asset_id']][] = $a['AssetsRelated']['asset_related_id'];
			$assetsRelatedData[$a['AssetsRelated']['asset_related_id']][] = $a['AssetsRelated']['asset_id'];
		}
		foreach ($assetsRelatedData as $id => $a) {
			$assetsRelatedData[$id] = array_unique($a);
		}

		$allAssets = $this->Asset->find('all', array(
			'contain' => array(
				'Legal' => array(
					'fields' => array('id', 'name')
				)
			)
		));

		$allAssetsData = array();
		foreach ($allAssets as $key => $a) {
			$allAssetsData[$a['Asset']['id']] = $a;
		}

		// debug($allAssets);
		$this->set('assetsRelatedData', $assetsRelatedData);
		$this->set('allAssetsData', $allAssetsData);
	}

	/**
	 * Optimized database queries to get Asset Classification data for each Asset.
	 */
	private function getAssetClassificationsData() {
		return $this->Asset->getAssetClassificationsData();
	}

	private function sortAssetsArrayById( &$data ) {
		$sorted = array();
		foreach ( $data as $item ) {
			$sorted[ $item['Asset']['id'] ] = $item;
		}

		$data = $sorted;
	}

	private function findUncategorized( $business_units = array(), $assets = array() ) {
		$used = array();
		foreach ( $business_units as $bu ) {
			foreach ( $bu['Asset'] as $asset ) {
				if ( ! in_array( $asset['id'], $used ) ) {
					$used[] = $asset['id'];
				}
			}
		}

		$uncategorized = array();
		foreach ( $assets as $asset ) {
			if ( ! in_array( $asset['Asset']['id'], $used ) ) {
				$uncategorized[] = $asset['Asset']['id'];
			}
		}

		return $uncategorized;
	}

	private function findUncategorized2() {
		$tmp = $this->Asset->find( 'all', array(
			'conditions' => array(
			),
			'fields' => array(
				'Asset.*'
			),
			'contain' => array(
				'BusinessUnit' => array(
				),
				'AssetMediaType' => array(
					'fields' => array( 'name' )
				),
				'AssetLabel' => array(
					'fields' => array( 'name' )
				),
				'Legal' => array(
					'fields' => array( 'name' )
				),
				'AssetClassification' => array(
					'AssetClassificationType' => array()
				),
				'AssetOwner' => array(
					'fields' => array( 'name' )
				),
				'AssetGuardian' => array(
					'fields' => array( 'name' )
				),
				'AssetUser' => array(
					'fields' => array( 'name' )
				),
				/*'AssetMainContainer' => array(
					'fields' => array( 'name' )
				),*/
				// 'SecurityIncident' => array()
			),
			'recursive' => 2
		) );

		$data = array();
		foreach ( $tmp as $asset ) {
			if ( empty( $asset['BusinessUnit'] ) ) {
				$data[] = $asset;
			}
		}

		//debug( $data );
	}

	public function edit( $id = null ) {
		$this->title = __('Edit an Asset');
		return $this->Crud->execute();
	}

	private function initOptions() {
		$assetUsers = $this->Asset->BusinessUnit->find('list', array(
			'conditions' => array('BusinessUnit._hidden' => 1),
			'order' => array('BusinessUnit.id' => 'ASC'),
			'recursive' => -1
		));
		$this->set('assetUsers', $assetUsers);

		$this->loadModel( 'AssetClassificationType' );
		$classifications = $this->AssetClassificationType->find('all', array(
			'order' => array('AssetClassificationType.name' => 'ASC'),
			'recursive' => 1
		));
		$this->set( 'classifications', $classifications );
	}

	public function getLegals() {
		$this->allowOnlyAjax();
		$this->autoRender = false;

		$buIds = json_decode($this->request->query['buIds']);
		$data = $this->Asset->BusinessUnit->getLegalIds($buIds);

		echo json_encode($data);
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->Asset->find('first', array(
			'conditions' => array(
				'Asset.id' => $id
			),
			'contain' => array(
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'AssetUser',
				'AssetMediaType',
				'BusinessUnit',
				'AssetLabel',
				'AssetMediaType',
				'AssetOwner',
				'AssetGuardian',
				'AssetClassification' => array(
					'AssetClassificationType'
				),
				'Review' => array(
					'User'
				),
				'CustomFieldValue'
			),
			'recursive' => -1
		));

		$customFieldsData = $this->CustomFieldsMgt->setData();
		$item = array_merge($item, $customFieldsData);
		
		$this->set('item', $item);
		$vars = array(
			'item' => $item
		);

		$name = Inflector::slug($item['Asset']['name'], '-');

		$this->Pdf->renderPdf($name, '..'.DS.'Assets'.DS.'export_pdf', 'pdf', $vars, true);
	}
}
