<?php
class Cron extends AppModel {
	/**
	 * Maximum number in seconds on how long would take for "Cron is running at the moment" to release.
	 */
	const CRON_RUNNING_TOLERANCE = 60;

	public $useTable = 'cron';
	protected $allowedTypes = array(self::TYPE_HOURLY, self::TYPE_DAILY, self::TYPE_YEARLY);

	public $actsAs = array(
		'Search.Searchable',
		'HtmlPurifier.HtmlPurifier' => array(
			'config' => 'Strict',
			'fields' => array()
		)
	);

	/**
	 * The requestId is a unique ID generated once per CRON job to allow multiple record changes to be grouped by request
	 *
	 * @var string
	 */
	protected static $_requestId = null;

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Crons');

		$this->advancedFilter = array(
			__('General') => array(
				'id' => array(
					'type' => 'text',
					'name' => __('ID'),
					'filter' => false
				),
				'created' => array(
					'type' => 'date',
					'comparison' => true,
					'name' => __('Date'),
					'show_default' => true,
					'filter' => array(
						'type' => 'query',
						'method' => 'setComparisonType',
						'field' => 'Cron.created'
					),
				),
				'type' => array(
					'type' => 'select',
					'name' => __('Type'),
					'show_default' => true,
					'filter' => array(
						'type' => 'value',
					),
					'data' => array(
						'method' => 'getCronTypes',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'execution_time' => array(
					'type' => 'number',
					'comparison' => true,
					'name' => __('Execution Time'),
					'show_default' => true,
					'filter' => array(
						'type' => 'query',
						'method' => 'setComparisonType',
						'field' => 'Cron.execution_time'
					),
					'outputFilter' => array('Crons', 'getExecutionTimeLabel')
				),
				'status' => array(
					'type' => 'select',
					'name' => __('Status'),
					'show_default' => true,
					'filter' => array(
						'type' => 'value',
					),
					'data' => array(
						'method' => 'getCronStatuses',
						'empty' => __('All'),
						'result_key' => true,
					),
				),
				'message' => array(
					'type' => 'text',
					'name' => __('Message'),
					'show_default' => false,
					'filter' => array(
						'type' => 'subquery',
						'method' => 'findComplexType',
						'findField' => 'Cron.message',
						'field' => 'Cron.id',
					)
				),
			),
		);

		$this->advancedFilterSettings = array(
			'pdf_title' => __('Cron'),
			'pdf_file_name' => __('crons'),
			'csv_file_name' => __('crons'),
			'actions' => false,
			'url' => array(
				'controller' => 'cron',
				'action' => 'index',
				'?' => array(
					'advanced_filter' => 1
				)
			),
			'reset' => array(
				'controller' => 'cron',
				'action' => 'index',
				'?' => array(
					'advanced_filter' => 1
				)
			)
		);

		parent::__construct($id, $table, $ds);
	}

	/**
	 * Get request ID
	 *
	 * @return null|string The request ID.
	 */
	public static function requestId() {
		return self::$_requestId;
	}

	// set unique request it from cron crud action
	public static function setRequestId($uuid) {
		self::$_requestId = $uuid;
	}

	/**
	 * @deprecated use the static method below instead
	 */
	function getCronTypes() {
		return self::types();
	}

	// possible types of cron jobs
	public static function types($value = null) {
		$options = array(
			self::TYPE_HOURLY => __('Hourly'),
			self::TYPE_DAILY => __('Daily'),
			self::TYPE_YEARLY => __('Yearly')
		);
		return parent::enum($value, $options);
	}
	const TYPE_HOURLY = 'hourly';
	const TYPE_DAILY = 'daily';
	const TYPE_YEARLY = 'yearly';

	/**
	 * @deprecated use the static method below instead
	 */
	function getCronStatuses() {
		return self::statuses();
	}

	// possible statuses that cron job can result in
	public static function statuses($value = null) {
		$options = array(
			self::STATUS_SUCCESS => __('Success'),
			self::STATUS_ERROR => __('Error'),
		);
		return parent::enum($value, $options);
	}
	const STATUS_SUCCESS = 'success';
	const STATUS_ERROR = 'error';

	public function isCronTaskRunning($type) {
		// do not manage this during hourly cron
		if ($type == self::TYPE_HOURLY) {
			return false;
		}

		// for debugging purposes it is possible to init a cron job anytime without restriction
		if (Configure::read('Eramba.CRON_DISABLE_VALIDATION')) {
			return false;
		}

		$cacheStr = 'cron_type_' . $type;
		if (($data = Cache::read($cacheStr, 'cron')) === false) {
			return false;
		}

		if ($this->getSecondsToReleaseCron($type, $data) < 0) {
			return false;
		}
		
		return $data;
	}

	public function getSecondsToReleaseCron($type, $data) {
		return $data - strtotime('now') + self::CRON_RUNNING_TOLERANCE;
	}

	public function setCronTaskAsRunning($type, $running = true) {
		// do not manage this during hourly cron
		if ($type == self::TYPE_HOURLY) {
			return true;
		}

		$cacheStr = 'cron_type_' . $type;

		if (empty($running)) {
			return Cache::delete($cacheStr, 'cron');
		}

		return Cache::write($cacheStr, strtotime('now'), 'cron');
	}

	/**
	 * Checks if a certain cron task already exists according to a condition.
	 */
	public function cronTaskExists($type) {
		// for debugging purposes it is possible to init a cron job anytime without restriction
		if (Configure::read('Eramba.CRON_DISABLE_VALIDATION')) {
			return false;
		}

		$today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
		$hourAgo = CakeTime::format('Y-m-d H:i:s', CakeTime::fromString('-59 minutes'));

		$conds = array(
			'Cron.type' => $type,
			'Cron.status' => 'success'
		);

		if ($type == self::TYPE_HOURLY) {
			$conds['Cron.created >'] = $hourAgo;
		}

		if ($type == self::TYPE_DAILY) {
			$conds['DATE(Cron.created)'] = $today;
		}

		if ($type == self::TYPE_YEARLY) {
			$conds['YEAR(Cron.created)'] = CakeTime::format('Y', CakeTime::fromString('now'));
		}

		$data = $this->find('count', array(
			'conditions' => $conds,
			'recursive' => -1
		));

		return $data;
	}

	/**
	 * Save info record about a cron task that was run.
	 */
	public function saveCronTaskRecord($type = self::TYPE_DAILY, $status = self::STATUS_SUCCESS, $message = null) {
		if (!in_array($type, $this->allowedTypes)) {
			return false;
		}

		$data = array(
			'type' => $type,
			'execution_time' => scriptExecutionTime(),
			'status' => $status,
			'request_id' => self::requestId(),
			'url' => Router::fullBaseUrl(),
			'message' => $message
		);

		$this->create();
		$this->set($data);
		$ret = $this->save(null, false);
		$ret &= $this->setCronTaskAsRunning($type, false);

		return $ret;
	}

	public function getFailedJobIds() {
		return $this->find('list', array(
			'conditions' => array(
				'Cron.status' => self::STATUS_ERROR
			),
			'fields' => array('id', 'id'),
			'recursive' => -1
		));
	}
}
