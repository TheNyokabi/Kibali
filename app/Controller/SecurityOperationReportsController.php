<?php
App::uses('ReportsController', 'Controller');
class SecurityOperationReportsController extends ReportsController {
	public $uses = array(
		'Project', 'SecurityIncidentStatus', 'SecurityService', 'Asset', 'SecurityIncident'
	);

	public function index() {
		$this->set('title_for_layout', __('Security Operations Report'));
		$this->set('hidePageHeader', true);

		$today = CakeTime::format( 'Y-m-d', CakeTime::fromString( 'now' ) );

		$data = $this->Project->find( 'all', array(
			'conditions' => array(
				'Project.project_status_id' => PROJECT_STATUS_ONGOING,
				'Project.deadline <' => $today
			),
			'fields' => array( 'id', 'title', 'deadline' ),
			'order' => array(
				'Project.deadline' => 'ASC'
			),
			'recursive' => -1
		) );

		$ongoing_project_list1 = array();
		foreach ( $data as $item ) {
			$datetime1 = new DateTime( $today );
			$datetime2 = new DateTime( $item['Project']['deadline'] );
			$interval = $datetime1->diff( $datetime2 );
			$diff = $interval->format( '%a' );

			$tmp = array(
				'label' => $item['Project']['title'],
				'data' => array( array( $diff, 0 ) ),
				'bars' => array(
					'show' => true,
					'barWidth' => 0.1,
					'order' => 1
				)
			);

			$ongoing_project_list1[] = $tmp;
		}

		$this->set( 'ongoing_project_list1', $ongoing_project_list1 );

		$data = $this->Project->find( 'all', array(
			'conditions' => array(
				'Project.project_status_id' => PROJECT_STATUS_ONGOING,
				'Project.deadline >' => $today
			),
			'fields' => array( 'id', 'title', 'deadline' ),
			'order' => array(
				'Project.deadline' => 'ASC'
			),
			'limit' => 10,
			'recursive' => -1
		) );

		$ongoing_project_list2 = array();
		foreach ( $data as $item ) {
			$datetime1 = new DateTime( $today );
			$datetime2 = new DateTime( $item['Project']['deadline'] );
			$interval = $datetime1->diff( $datetime2 );
			$diff = $interval->format( '%a' );

			$tmp = array(
				'label' => $item['Project']['title'],
				'data' => array( array( $diff, 0 ) ),
				'bars' => array(
					'show' => true,
					'barWidth' => 0.1,
					'order' => 1
				)
			);

			$ongoing_project_list2[] = $tmp;
		}

		$this->set( 'ongoing_project_list2', $ongoing_project_list2 );

		$monthAgo = CakeTime::format( 'Y-m-d', CakeTime::fromString( '-1 month' ) );
		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));

		//security incident statuses
		$this->SecurityIncidentStatus->bindModel( array(
			'hasMany' => array( 'SecurityIncident' )
		) );
		$this->SecurityIncidentStatus->Behaviors->attach('Containable');
		$data = $this->SecurityIncidentStatus->find( 'all', array(
			'contain' => array(
				'SecurityIncident' => array(
					'fields' => array( 'id' )
				)
			)
		) );

		$security_incident_statuses = array();
		foreach ( $data as $item ) {
			$security_incident_statuses[] = array(
				'label' => $item['SecurityIncidentStatus']['name'],
				'data' => count( $item['SecurityIncident'] )
			);
		}

		$this->set( 'security_incident_statuses', $security_incident_statuses );

		//security incident classification
		$this->set('security_incident_classification', $this->getSecurityIncidentClassification());

		// each security control selected in security incidents
		$data = $this->SecurityService->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'SecurityIncident' => array(
					'fields' => array( 'id' )
				)
			)
		) );

		$controls_incidents = array();
		foreach ( $data as $item ) {
			if (empty($item['SecurityIncident'])) {
				continue;
			}

			$tmp = array(
				'label' => $item['SecurityService']['name'],
				'data' => array( array( count( $item['SecurityIncident'] ), 0 ) ),
				'bars' => array(
					'show' => true,
					'barWidth' => 0.1,
					'order' => 1
				)
			);

			$controls_incidents[] = $tmp;
		}

		$this->set( 'controls_incidents', $controls_incidents );

		//each asset selected in security incidents
		$data = $this->Asset->find( 'all', array(
			'fields' => array( 'id', 'name' ),
			'contain' => array(
				'SecurityIncident' => array(
					'fields' => array( 'id' )
				)
			)
		) );

		$assets_incidents = array();
		foreach ( $data as $item ) {
			if (empty($item['SecurityIncident'])) {
				continue;
			}

			$tmp = array(
				'label' => $item['Asset']['name'],
				'data' => array( array( count( $item['SecurityIncident'] ), 0 ) ),
				'bars' => array(
					'show' => true,
					'barWidth' => 0.1,
					'order' => 1
				)
			);

			$assets_incidents[] = $tmp;
		}

		$this->set( 'assets_incidents', $assets_incidents );

		$this->render('../Reports/security_operations');
	}

	private function getSecurityIncidentClassification() {
		// service classifications
		$data = $this->SecurityIncident->Classification->find('all', array(
			'fields' => array('name', 'COUNT(Classification.security_incident_id) as itemCount'),
			'group' => array('Classification.name'),
			'recursive' => -1
		));
		//debug($data);


		$classificationList = array();
		foreach ( $data as $classification ) {
			$classificationList[] = array(
				'label' => $classification['Classification']['name'],
				'data' => (int) $classification[0]['itemCount']
			);
		}

		$categorized = $this->SecurityIncident->Classification->find('list', array(
			'fields' => array('security_incident_id'),
			'group' => array('Classification.security_incident_id'),
			'recursive' => -1
		));

		$uncategorized = $this->SecurityIncident->find('count', array(
			'conditions' => array(
				'SecurityIncident.id !=' => $categorized
			),
			'recursive' => -1
		));

		$classificationList[] = array(
			'label' => __('Uncategorized'),
			'data' => $uncategorized
		);

		return $classificationList;
	}
	
}