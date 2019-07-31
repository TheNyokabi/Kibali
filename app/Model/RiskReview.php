<?php
App::uses('Review', 'Model');
class RiskReview extends Review {
    protected $_relatedModel = 'Risk';

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

        $this->label = __('Risk Reviews');

        $dvancedFilter = array(
            __('General') => array(
                'parent' => array(
                    'type' => 'multiple_select',
                    'name' => __('Asset Risk Title'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->_relatedModel . '.id',
                        'field' => $this->alias . '.foreign_key'
                    ),
                    'data' => array(
                        'method' => 'getRisks'
                    ),
                    'contain' => array(
                        'Risk' => array(
                            'title'
                        )
                    )
                ),
            )
        );

        $this->mergeAdvancedFilterFields($dvancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('asset Risk Reviews'),
            'pdf_file_name' => __('asset_risk_reviews'),
            'csv_file_name' => __('asset_risk_reviews'),
            'max_selection_size' => 10,
            'url' => array(
                'controller' => 'reviews',
                'action' => 'filterIndex',
                'RiskReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'reset' => array(
                'controller' => 'risks',
                'action' => 'index',
            ),
            'bulk_actions' => true,
            'history' => true,
            'trash' => array(
                'controller' => 'reviews',
                'action' => 'trash',
                'RiskReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'view_item' => array(
                'ajax_action' => array(
                    'controller' => 'reviews',
                    'action' => 'index',
                    'Risk'
                )
            ),
            'use_new_filters' => true
        );

        
        $this->advancedFilterSettings = am($this->advancedFilterSettings, $this->reviewFilterSettings);

        $this->initAdvancedFilter();

        $this->addRiskReviewField();
	}

    public function getRisks() {
        $data = $this->Risk->find('list', array(
            'fields' => array('Risk.id', 'Risk.title'),
            'order' => array('Risk.title' => 'ASC'),
        ));
        return $data;
    }
}



