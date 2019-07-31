<?php
App::uses('Review', 'Model');
class AssetReview extends Review {
    protected $_relatedModel = 'Asset';

	public $useTable = 'reviews';

    public $actsAs = array(
        'Containable',
        'Search.Searchable',
        'HtmlPurifier.HtmlPurifier' => array(
            'config' => 'Strict',
        )
    );

	public function __construct($id = false, $table = null, $ds = null) {
		$this->mapping = $this->getMapping();
		$this->mapping['indexController'] = 'reviews';

        parent::__construct($id, $table, $ds);

        $this->label = __('Asset Reviews');

        $advancedFilter = array(
            __('General') => array(
                'parent' => array(
                    'type' => 'multiple_select',
                    'name' => __('Asset Name'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->_relatedModel . '.id',
                        'field' => $this->alias . '.foreign_key'
                    ),
                    'data' => array(
                        'method' => 'getAssetReviews'
                    ),
                    'contain' => array(
                        'Asset' => array(
                            'name'
                        )
                    )
                ),
            )
        );

        $this->mergeAdvancedFilterFields($advancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Asset Reviews'),
            'pdf_file_name' => __('asset_reviews'),
            'csv_file_name' => __('asset_reviews'),
            'url' => array(
                'controller' => 'reviews',
                'action' => 'filterIndex',
                'AssetReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            // 'reset' => array(
            //     'controller' => 'reviews',
            //     'action' => 'filterIndex',
            //     'AssetReview',
            //     '?' => array(
            //         'advanced_filter' => 1
            //     )
            // )
            'reset' => array(
                'controller' => 'assets',
                'action' => 'index'
            ),
            'bulk_actions' => array(
                BulkAction::TYPE_EDIT
            ),
            'history' => true,
            'trash' => array(
                'controller' => 'reviews',
                'action' => 'trash',
                'AssetReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'view_item' => array(
                'ajax_action' => array(
                    'controller' => 'reviews',
                    'action' => 'index',
                    'Asset'
                )
            ),
            'use_new_filters' => true
        );

        $this->advancedFilterSettings = am($this->advancedFilterSettings, $this->reviewFilterSettings);

        $this->initAdvancedFilter();

        $this->addRiskReviewField();
	}

    public function getUsers() {
        $this->User->virtualFields['full_name'] = 'CONCAT(User.name, " ", User.surname)';
        $data = $this->User->find('list', array(
            'fields' => array('User.id', 'User.full_name'),
        ));
        return $data;
    }

    public function getAssetReviews() {
        $data = $this->Asset->find('list', array(
            'fields' => array('Asset.id', 'Asset.name'),
        ));
        return $data;
    }
}
