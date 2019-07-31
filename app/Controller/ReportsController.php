<?php
class ReportsController extends AppController {
	public $helpers = array( 'Html' );
	public $components = array( 'Session' );

	public function beforeFilter() {
		$this->Auth->allow( 'updateOvertimeCharts' );

		parent::beforeFilter();
	}

	/**
	 * Sort multidimensional array.
	 */
	protected function aasort( &$array, $key ) {
		$sorter = array();
		$ret = array();
		reset( $array );
		foreach ( $array as $ii => $va ) {
			$sorter[$ii] = $va[$key];
		}
		asort( $sorter );
		foreach ( $sorter as $ii => $va ) {
			$ret[$ii] = $array[$ii];
		}
		$array = $ret;
	}

	public function awareness() {
		$this->set( 'hidePageHeader', true );

		//missing
		$this->loadModel( 'AwarenessOvertimeGraph' );
		$data = $this->AwarenessOvertimeGraph->find( 'all', array(
			'conditions' => array(
				'AwarenessOvertimeGraph.awareness_program_id IS NOT NULL'
			),
			'group' => array( 'AwarenessOvertimeGraph.awareness_program_id' ),
			'fields' => array( 'id', 'awareness_program_id', 'title' )
		) );

		$programs = array();
		foreach ( $data as $program ) {
			$programId = $program['AwarenessOvertimeGraph']['awareness_program_id'];
			$programs[$programId] = $program['AwarenessOvertimeGraph']['title'];
		}
		
		$awareness_stats = array(
			'doing' => array(),
			'missing' => array(),
			'correct_answers' => array(),
			'average' => array()
		);
		foreach ( $programs as $programId => $programTitle ) {
			$data = $this->AwarenessOvertimeGraph->find( 'all', array(
				'conditions' => array(
					'AwarenessOvertimeGraph.awareness_program_id' => $programId
				)
			) );

			$doing = array();
			$missing = array();
			$correct_answers = array();
			$average = array();
			foreach ( $data as $chart ) {
				$doing[] = array( (int) $chart['AwarenessOvertimeGraph']['timestamp'] * 1000, (int) $chart['AwarenessOvertimeGraph']['doing'] );
				$missing[] = array( (int) $chart['AwarenessOvertimeGraph']['timestamp'] * 1000, (int) $chart['AwarenessOvertimeGraph']['missing'] );
				$correct_answers[] = array( (int) $chart['AwarenessOvertimeGraph']['timestamp'] * 1000, (int) $chart['AwarenessOvertimeGraph']['correct_answers'] );
				$average[] = array( (int) $chart['AwarenessOvertimeGraph']['timestamp'] * 1000, (int) $chart['AwarenessOvertimeGraph']['average'] );
			}

			$awareness_stats['doing'][] = array(
				'label' => $programTitle,
				'data' => $doing
			);
			$awareness_stats['missing'][] = array(
				'label' => $programTitle,
				'data' => $missing
			);
			$awareness_stats['correct_answers'][] = array(
				'label' => $programTitle,
				'data' => $correct_answers
			);
			$awareness_stats['average'][] = array(
				'label' => $programTitle,
				'data' => $average
			);
		}	

		$this->set( 'awareness_stats', $awareness_stats );	
	}

}
