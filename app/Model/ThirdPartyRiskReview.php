<?php
App::uses('Review', 'Model');
class ThirdPartyRiskReview extends Review {
    protected $_relatedModel = 'ThirdPartyRisk';

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

        $this->label = __('Third Party Risk Reviews');

        $dvancedFilter = array(
            __('General') => array(
                'parent' => array(
                    'type' => 'multiple_select',
                    'name' => __('Third Party Risk Title'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->_relatedModel . '.id',
                        'field' => $this->alias . '.foreign_key'
                    ),
                    'data' => array(
                        'method' => 'getThirdPartyRisks'
                    ),
                    'contain' => array(
                        'ThirdPartyRisk' => array(
                            'title'
                        )
                    )
                ),
            )
        );

        $this->mergeAdvancedFilterFields($dvancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Third Party Risk Reviews'),
            'pdf_file_name' => __('third_party_risk_reviews'),
            'csv_file_name' => __('third_party_risk_reviews'),
            'max_selection_size' => 10,
            'url' => array(
                'controller' => 'reviews',
                'action' => 'filterIndex',
                'ThirdPartyRiskReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'reset' => array(
                'controller' => 'thirdPartyRisks',
                'action' => 'index',
            ),
            'bulk_actions' => true,
            'history' => true,
            'trash' => array(
                'controller' => 'reviews',
                'action' => 'trash',
                'ThirdPartyRiskReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'view_item' => array(
                'ajax_action' => array(
                    'controller' => 'reviews',
                    'action' => 'index',
                    'ThirdPartyRisk'
                )
            ),
            'use_new_filters' => true
        );

        $this->advancedFilterSettings = am($this->advancedFilterSettings, $this->reviewFilterSettings);

        $this->initAdvancedFilter();
        
        $this->addRiskReviewField();
	}

    public function getThirdPartyRisks() {
        $data = $this->ThirdPartyRisk->find('list', array(
            'fields' => array('ThirdPartyRisk.id', 'ThirdPartyRisk.title'),
            'order' => array('ThirdPartyRisk.title' => 'ASC'),
        ));
        return $data;
    }
}
