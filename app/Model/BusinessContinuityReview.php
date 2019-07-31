<?php
App::uses('Review', 'Model');
class BusinessContinuityReview extends Review {
    protected $_relatedModel = 'BusinessContinuity';

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

        $this->label = __('Buissness Impact Analysis Reviews');

        $dvancedFilter = array(
            __('General') => array(
                'parent' => array(
                    'type' => 'multiple_select',
                    'name' => __('Business Continuity Risk Title'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->_relatedModel . '.id',
                        'field' => $this->alias . '.foreign_key'
                    ),
                    'data' => array(
                        'method' => 'getBusinessContinuities'
                    ),
                    'contain' => array(
                        'BusinessContinuity' => array(
                            'title'
                        )
                    )
                ),
            )
        );

        $this->mergeAdvancedFilterFields($dvancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Business Continuity Reviews'),
            'pdf_file_name' => __('business_continuity_reviews'),
            'csv_file_name' => __('business_continuity_reviews'),
            'max_selection_size' => 10,
            'url' => array(
                'controller' => 'reviews',
                'action' => 'filterIndex',
                'BusinessContinuityReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'reset' => array(
                'controller' => 'businessContinuities',
                'action' => 'index',
            ),
            'bulk_actions' => true,
            'history' => true,
            'trash' => array(
                'controller' => 'reviews',
                'action' => 'trash',
                'BusinessContinuityReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'view_item' => array(
                'ajax_action' => array(
                    'controller' => 'reviews',
                    'action' => 'index',
                    'BusinessContinuity'
                )
            ),
            'use_new_filters' => true
        );

        $this->advancedFilterSettings = am($this->advancedFilterSettings, $this->reviewFilterSettings);
        
        $this->initAdvancedFilter();

        $this->addRiskReviewField();
	}

    public function getBusinessContinuities() {
        $data = $this->BusinessContinuity->find('list', array(
            'fields' => array('BusinessContinuity.id', 'BusinessContinuity.title'),
            'order' => array('BusinessContinuity.title' => 'ASC'),
        ));
        return $data;
    }
}
