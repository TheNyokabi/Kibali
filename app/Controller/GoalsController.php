<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');

class GoalsController extends SectionBaseController {
    use SectionCrudTrait;
    
	public $helpers = [];
	public $components = [
        'Search.Prg', 'Paginator', 'Pdf', 'ObjectStatus.ObjectStatus',
        'Visualisation.Visualisation',
        'Ajax' => [
            'actions' => ['add', 'edit', 'delete'],
            'modules' => ['comments', 'records', 'attachments', 'notifications']
	   ],
       'Crud.Crud' => [
            'actions' => [
                'index' => [
                    'contain' => [
                        'Owner',
                        'Attachment',
                        'Comment',
                        'SecurityService',
                        'Risk' => [
                            'Asset'
                        ],
                        'ThirdPartyRisk' => [
                            'Asset'
                        ],
                        'BusinessContinuity',
                        'Project',
                        'SecurityPolicy',
                        'ProgramIssue' => [
                            'ProgramIssueType'
                        ]
                    ],
                ],
                'add' => [
                    'saveMethod' => 'saveAssociated',
                ],
                'edit' => [
                    'saveMethod' => 'saveAssociated',
                ]
            ]
        ],
        'CustomFields.CustomFieldsMgt' => array('model' => 'Goal'),
    ];

    public function beforeFilter() {
        $this->Crud->enable(['index', 'add', 'edit', 'delete']);

        parent::beforeFilter();

        $this->title = __('Program Goals & Objectives');
        $this->subTitle = __('Define your program goals and objectives. Select all applicable controls, risks and projects that will support this. Define your program metrics and evaluate them at regular periods of time.');
    }

	public function delete($id = null) {
		$this->title = __('Goals');
		$this->subTitle = __('Delete a Goal.');

		return $this->Crud->execute();
	}

	public function add() {
        $this->title = __('Create a Goal');

        $this->initAddEditSubtitle();

        $this->initOptions();

        $this->handleEmptyDates();

        return $this->Crud->execute();
	}

	public function edit($id = null) {
        $this->title = __('Edit a Goal');

        $this->initAddEditSubtitle();

        $this->initOptions();

        $this->handleEmptyDates();

        return $this->Crud->execute();
	}

    private function handleEmptyDates() {
        if ($this->request->is(['post', 'put'])) {
            $this->request->data['GoalAuditDate'] = array_filter((array) $this->request->data['GoalAuditDate']);
        }
    }

	private function initOptions() {
		$programIssues = $this->Goal->ProgramIssue->find('list', array(
			'conditions' => array(
				'ProgramIssue.status' => PROGRAM_ISSUE_CURRENT
			),
			'order' => array('ProgramIssue.name' => 'ASC'),
			'recursive' => -1
		));

		$this->set('programIssues', $programIssues);
	}

	private function initAddEditSubtitle() {
		$this->subTitle = false;
	}

	public function auditCalendarFormEntry() {
		$this->allowOnlyAjax();

		$data = $this->request->data;

		$this->set('formKey', (int) $data['formKey']);
		$this->set('model', 'GoalAuditDate');
		// if (!isset($data['field'])) {
		// 	$data['field'] = 'audit_calendar';
		// }
		// $this->set('field', $data['field']);

		$this->set('useNewCalendarConvention', true);
		$this->render('/Elements/ajax/audit_calendar_entry');
	}

	public function exportPdf($id) {
		$this->autoRender = false;
		$this->layout = 'pdf';

		$item = $this->Goal->find('first', array(
			'conditions' => array(
				'Goal.id' => $id
			),
			'contain' => array(
				'Attachment',
				'Comment' => array('User'),
				'SystemRecord' => array(
					'limit' => 20,
					'order' => array('created' => 'DESC'),
					'User'
				),
				'Owner',
				'SecurityService',
				'Risk',
				'ThirdPartyRisk',
				'BusinessContinuity',
				'Project',
				'SecurityPolicy',
				'ProgramIssue',
				'GoalAudit'
			)
		));

		$vars = array(
			'item' => $item
		);

		$this->set($vars);

		$name = Inflector::slug($item['Goal']['name'], '-');
		$this->Pdf->renderPdf($name, '..'.DS.'Goals'.DS.'export_pdf', 'pdf', $vars, true);
	}

}
