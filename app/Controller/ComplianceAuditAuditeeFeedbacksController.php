<?php
class ComplianceAuditAuditeeFeedbacksController extends AppController {
	public $helpers = array( 'Html', 'Form' );
	public $components = array( 'Session', 'CsvView.CsvView', 'Search.Prg', 'AdvancedFilters', 'Paginator');

	public function index() {
		$this->set('title_for_layout', __('Third Party Audit Feedback'));
		$this->set('subtitle_for_layout', __('List of all answers provided by users.'));

		if ($this->AdvancedFilters->filter('ComplianceAuditAuditeeFeedback')) {
			return;
		}
	}

}