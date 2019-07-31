<?php
App::uses('Review', 'Model');
class SecurityPolicyReview extends Review {
    protected $_relatedModel = 'SecurityPolicy';

    protected $_updates = false;

	public $useTable = 'reviews';

    public $actsAs = array(
        'Containable',
        'Search.Searchable',
        'HtmlPurifier.HtmlPurifier' => array(
            'config' => 'Strict',
        )
    );

    // public $hasOne = array(
    //     'SecurityPolicyReviewCustom'
    // );

	public function __construct($id = false, $table = null, $ds = null) {
		$this->mapping = $this->getMapping();
		$this->mapping['indexController'] = 'reviews';

        // $this->fieldData = array(
        //     'version' => array(
        //         'label' => __('Version'),
        //         'editable' => true,
        //     ),
        // );

		parent::__construct($id, $table, $ds);

        $this->label = __('Security Policy Reviews');

        $dvancedFilter = array(
            __('General') => array(
                'parent' => array(
                    'type' => 'multiple_select',
                    'name' => __('Policy Name'),
                    'show_default' => true,
                    'filter' => array(
                        'type' => 'subquery',
                        'method' => 'findComplexType',
                        'findField' => $this->_relatedModel . '.id',
                        'field' => $this->alias . '.foreign_key'
                    ),
                    'data' => array(
                        'method' => 'getSecurityPolicy'
                    ),
                    'contain' => array(
                        'SecurityPolicy' => array(
                            'index'
                        )
                    )
                ),
            )
        );

        $this->mergeAdvancedFilterFields($dvancedFilter);

        $this->advancedFilterSettings = array(
            'pdf_title' => __('Security Policy Reviews'),
            'pdf_file_name' => __('security_policy_reviews'),
            'csv_file_name' => __('security_policy_reviews'),
            'url' => array(
                'controller' => 'reviews',
                'action' => 'filterIndex',
                'SecurityPolicyReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'reset' => array(
                'controller' => 'securityPolicies',
                'action' => 'index',
            ),
            'bulk_actions' => true,
            'history' => true,
            'trash' => array(
                'controller' => 'reviews',
                'action' => 'trash',
                'SecurityPolicyReview',
                '?' => array(
                    'advanced_filter' => 1
                )
            ),
            'view_item' => array(
                'ajax_action' => array(
                    'controller' => 'reviews',
                    'action' => 'index',
                    'SecurityPolicy'
                )
            ),
            'use_new_filters' => true
        );

        $this->advancedFilterSettings = am($this->advancedFilterSettings, $this->reviewFilterSettings);

        $this->initAdvancedFilter();

        // $this->addReviewField();
	}

    public function associateAutoCreatedReview($description = '') {
        $today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
        
        return $this->associateReview($today, [
            'actual_date' => $today,
            'completed' => self::STATUS_COMPLETE,
            'description' => $description,
            'version' => $this->SecurityPolicy->field('version')
        ], false);
    }

    protected function addReviewField() {
        // review field
        $Review = $this->{$this->parentModel()}->getFieldDataEntity('next_review_date');

        if (!$this->hasFieldDataEntity('next_review_date')) {
            $this->getFieldCollection()
                ->add('next_review_date', $Review)
                ->toggleEditable(true);
        }

        $Version = $this->{$this->parentModel()}->getFieldDataEntity('version');
        $this->getFieldCollection()
            ->add('version', $Version)
            ->toggleEditable(true);
    }

    public function beforeValidate($options = array()) {
        // $this->data['SecurityPolicyReview']['version'] = $data[$this->_relatedModel]['version'];
        // parent::beforeValidate($options);

        // return true;
        // $ret = true;

        $data = $this->{$this->_relatedModel}->data;
        // debug($this->data);
        if (isset($this->data[$this->_relatedModel]['version'])) {
            $this->data['SecurityPolicyReview']['version'] = $this->data[$this->_relatedModel]['version'];
            // $this->data['SecurityPolicyReview']['version'] = $data[$this->_relatedModel]['version'];

        //     $this->_updates = true;
        }

        $ret = parent::beforeValidate($options);

//         if (isset($this->data['SecurityPolicy'])) {
//             $updates = $this->data['SecurityPolicy'];
//             $parentNode = $this->parentNode();

//             $policy = $this->SecurityPolicy->find('first', array(
//                 'conditions' => array(
//                     'id' => $parentNode[$this->_relatedModel]['id']
//                 ),
//                 'recursive' => -1
//             ));

//             $updates = am($policy['SecurityPolicy'], $updates);

//             $this->SecurityPolicy->create();
//             $this->SecurityPolicy->id = $updates['id'];
//             $this->SecurityPolicy->set($updates);

//             $ret &= $this->SecurityPolicy->validates(array(
//                 'fieldList' => array_keys($updates)
//             ));
// debug( $this->SecurityPolicy->validationErrors);
//             $this->_updates = $updates;
//         }

//         if (empty($ret)) {
//             $this->invalidate('SecurityPolicy', true);
//             // return false;
//         }

        return true;
    }

    public function afterSave($created, $options = array()) {
        parent::afterSave($created, $options);

        if (!empty($this->data['SecurityPolicyReview']['foreign_key'])) {
            $this->SecurityPolicy->updateDocumentVersion($this->data['SecurityPolicyReview']['foreign_key']);
        }
    }

    /*public function afterSave($created, $options = array()) {
        $ret = true;

        if (empty($this->id)) {
            // $ret &= parent::beforeSave($options);debug($ret);
        }

       

       
        if ($this->_updates) {
             $this->_updates = false;
             $ret &= parent::afterSave($created, $options);
         }
        //     $this->data['SecurityPolicyReview']['version'] = $this->_updates['version'];

        //     $ret &= $this->SecurityPolicy->beforeItemSave($this->SecurityPolicy->id);

        //     $ret &= $this->SecurityPolicy->logPolicy($this->SecurityPolicy->id);
        //     $updates = $this->_updates;

        //     $fields =  array_keys($updates);

        //     $this->SecurityPolicy->id = $updates['id'];
        //     $this->SecurityPolicy->set($updates);

        //     $ret &= $this->SecurityPolicy->save(null, array(
        //         'validate' => false,
        //         'fieldList' => $fields
        //     ));
        //     $ret &= $this->SecurityPolicy->afterItemSave($this->SecurityPolicy->id);
        // }

        return $ret;
    }*/

    // public function customFields($reviewId) {
    //     return $this->SecurityPolicyReviewCustom->find('first', array(
    //         'SecurityPolicyReviewCustom.review_id' => $reviewId
    //     ));
    // }

    public function getSecurityPolicy() {
        return $this->SecurityPolicy->getListWithType();
    }
}
