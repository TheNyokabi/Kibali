<?php
App::uses('SectionBaseHelper', 'View/Helper');

class ReviewsHelper extends SectionBaseHelper {
    public $helpers = ['Html', 'Ajax', 'Eramba'];
    public $settings = array();
    
    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);

        $this->settings = $settings;
    }

    /**
     * By default fields are disabled with a single variable set in the View.
     * 
     * @return boolean
     */
    public function isFieldDisabled() {
    	$reviewCompleted = $this->_View->getVar('reviewCompleted');

    	return $reviewCompleted;
    }

    public function disableFieldParams($name = null) {
    	if ($this->isFieldDisabled() || ($this->_View->getVar('edit') && $name === 'planned_date')) {
    		return ['readonly' => true];
    	}

    	return [];
    }

    public function getLastReviewDate($item) {
        $emptyDate = '0000-00-00';
        $lastReviewDate = $emptyDate;

        foreach ($item['Review'] as $review) {
            $date = $review['planned_date'];
            $completed = $review['completed'] == Review::STATUS_COMPLETE;
            if (!empty($date) && $date > $lastReviewDate && !$completed && empty($review['deleted'])) {
                $lastReviewDate = $date;
            }
        }

        return ($lastReviewDate != $emptyDate) ? $lastReviewDate : $this->Eramba->getEmptyValue('');
    }

    //next review if exists, if not last review
    public function getReviewDate($item) {
        //next review
        $emptyDate = '9999-12-31';
        $nextReviewDate = $emptyDate;
        $label = __('Next Review');

        foreach ($item['Review'] as $review) {
            $date = $review['planned_date'];
            $completed = $review['completed'] == Review::STATUS_COMPLETE;
            if (!empty($date) && $date >= date('Y-m-d') && $date < $nextReviewDate && !$completed && empty($review['deleted'])) {
                $nextReviewDate = $date;
            }
        }

        if ($nextReviewDate != $emptyDate) {
            return [
                'label' => $label,
                'date' => $nextReviewDate
            ];
        }

        $emptyDate = '0000-00-00';
        $lastReviewDate = $emptyDate;
        $label = __('Last Review');

        foreach ($item['Review'] as $review) {
            $date = $review['actual_date'];
            $completed = $review['completed'] == Review::STATUS_COMPLETE;
            if (!empty($date) && $date <= date('Y-m-d') && $date > $lastReviewDate && $completed && empty($review['deleted'])) {
                $lastReviewDate = $date;
            }
        }

        $lastReviewDate = ($lastReviewDate != $emptyDate) ? $lastReviewDate : $this->Eramba->getEmptyValue('');

        return [
            'label' => $label,
            'date' => $lastReviewDate
        ];
    }

    public function getLastCompletedReview($item) {
        $orderedReviews = Hash::sort($item['Review'], '{n}.version', 'ASC');

        $emptyDate = '0000-00-00';
        $lastReviewDate = $emptyDate;
        $lastReview = null;

        foreach ($orderedReviews as $review) {
            $date = $review['actual_date'];
            $completed = $review['completed'] == Review::STATUS_COMPLETE;
            if (!empty($date) && $date <= date('Y-m-d') && $date >= $lastReviewDate && $completed && empty($review['deleted'])) {
                $lastReviewDate = $date;
                $lastReview = $review;
            }
        }

        return $lastReview;
    }
}