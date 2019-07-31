<?php
/**
 * @package       AppPreview.Controller
 */
App::uses('AppPreviewAppController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');
App::uses('AbstractQuery', 'Lib/AdvancedFilters/Query');

class SectionItemsController extends AppPreviewAppController {
	use SectionCrudTrait;

	public $helpers = array('SectionItems', 'ImportTool.ImportTool', 'Workflows.Workflows');
	public $components = array(
		'Paginator', 'Search.Prg', 'AdvancedFilters', 'CustomFields.CustomFieldsMgt',
		'Crud.Crud' => [
			'actions' => [
				'index' => [
					// 'enabled' => true,
					'contain' => [
						'HasAndBelongsToMany' => [
							'fields' => ['full_name']
						],
						'HasAndBelongsToMany2' => ['fields' => ['id']],
						'HasMany' => ['fields' => ['id']],
						// 'Comment',
						// 'Attachment',
						'Tag',
						'BelongsTo' => [
							'fields' => ['full_name']
						]
					],
					// Filter configuration for index might be disabled this way (same for trash), enabled by default
					// 
					// 'filter' => [
					// 	'enabled' => false
					// ]
				]
			]
		],
		'Visualisation.Visualisation'
	);

	public function beforeFilter() {
		$this->Crud->enable(['index', 'add', 'edit', 'delete', 'trash']);

		parent::beforeFilter();

		$this->title = __('Section Items Title');
		$this->subTitle = __('Sub title test');
		// $this->Auth->allow();
		// $this->Auth->authorize = null;
	}

	public function index() {
		$this->title = __('Only For Index Custom Title');
		return $this->Crud->execute();
	}

/**
 * example of cutom (independent) use of advanced filters
 */
	public function filterView() {
		$this->title = __('Custom filter view');

		$params = [
			'user_id' => 1,
			'habtm2_id' => [2],
			'habtm2_id__comp_type' => AbstractQuery::COMPARISON_NOT_IN
		];

		$conditions = $this->AdvancedFilters->buildConditions('SectionItem', $params);

		$data = $this->SectionItem->find('all', [
			'fields' => ['SectionItem.id'],
			'conditions' => $conditions,
			'contain' => []
		]);

		debug($data);
		exit;
	}
}
