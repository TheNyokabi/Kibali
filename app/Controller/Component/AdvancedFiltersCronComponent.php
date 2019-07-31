<?php
App::uses('Component', 'Controller');
App::uses('AdvancedFiltersData', 'Lib');
App::uses('AdvancedFilterCron', 'Model');
App::uses('Cron', 'Model');

class AdvancedFiltersCronComponent extends Component {

    // const STATUS_SUCCESS = 'success';
    // const STATUS_ERROR = 'error';

    // const TYPE_COUNT = ADVANCED_FILTER_CRON_COUNT;
    // const TYPE_DATA = ADVANCED_FILTER_CRON_DATA;

    // const EXPORT_ROWS_LIMIT = 5000;

    protected $_defaults = array();

    private $error = false;
    private $errorMessages = array();

    private $crons = array();

    public $components = ['AdvancedFilters', 'Crud.Crud'];

    public $settings = [
        'listenerClass' => 'AdvancedFilters.AdvancedFiltersCron'
    ];

    public function __construct(ComponentCollection $collection, $settings = []) {
        if (empty($this->settings)) {
            $this->settings = [];
        }

        $settings = array_merge($this->settings, (array)$settings);
        parent::__construct($collection, $settings);
    }

    public function initialize(Controller $controller) {
        $this->controller = $controller;

        // $this->Crud->addListener('AdvancedFilterCron', $this->settings['listenerClass']);

        $this->controller->loadModel('AdvancedFilter');
        $this->controller->loadModel('AdvancedFilterCron');
        $this->controller->loadModel('AdvancedFilterCronResultItem');
    }

    public function getErrorMessages($implode = false) {
        return ($implode) ? implode(' ', $this->errorMessages) : $this->errorMessages;
    }

    private function error($message) {
        $this->error = true;
        $this->errorMessages[] = $message;
    }

    /**
     * execute all advanced filters with enabled log
     * 
     * @return boolean - success/failed
     */
    public function execute() {
        $filters = $this->controller->AdvancedFilter->find('all', array(
            'conditions' => array(
                'OR' => array(
                    'AdvancedFilter.log_result_count' => ADVANCED_FILTER_LOG_ACTIVE,
                    'AdvancedFilter.log_result_data' => ADVANCED_FILTER_LOG_ACTIVE,
                )
            ),
            'contain' => array(
                // 'AdvancedFilterValue',
                'AdvancedFilterCron' => array(
                    'conditions' => array(
                        'DATE(AdvancedFilterCron.created)' => date('Y-m-d')
                    )
                )
            )
        ));

        $ret = true;
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                if (!empty($filter['AdvancedFilterCron'])) {
                    continue;
                }
                if ($filter['AdvancedFilter']['log_result_count'] == ADVANCED_FILTER_LOG_ACTIVE) {
                    $ret &= $this->runFilter($filter, AdvancedFilterCron::TYPE_COUNT);
                }
                if ($filter['AdvancedFilter']['log_result_data'] == ADVANCED_FILTER_LOG_ACTIVE) {
                    $ret &= $this->runFilter($filter, AdvancedFilterCron::TYPE_DATA);
                    //remove obsolete items
                    $ret &= $this->controller->AdvancedFilterCronResultItem->removeObsoleteItems($filter['AdvancedFilter']['id']);
                }
            }
        }

        return $ret;
    }

    public function exportDataResults($fiterId, $type = 'csv') {
        $fiterId = (int) $fiterId;

        $filter = $this->controller->AdvancedFilter->getFilter($fiterId);

        if (empty($filter)) {
            return false;
        }

        $model = $filter['AdvancedFilter']['model'];

        $this->AdvancedFilters->buildRequest($fiterId);
        $this->initAdvancedFilter($model);

        if ($type == 'csv') {
            $this->AdvancedFilters->csv($model);
        }
        else {
            $this->AdvancedFilters->pdf($model);
        }

        $this->controller->request->query = array();
        $this->controller->request->data = array();

        $fileName = Inflector::slug($filter['AdvancedFilter']['name'], '-') . '_daily-data-results';

        return $fileName;
    }

    /**
     * sets data for count results export
     * 
     * @param  int $fiterId
     * @return mixed - (string) filename/(bool) false
     */
    public function exportDailyCountResults($fiterId) {
        $fiterId = (int) $fiterId;

        $failedJobs = $this->controller->AdvancedFilter->AdvancedFilterCron->Cron->getFailedJobIds();
        $filter = $this->controller->AdvancedFilter->find('first', array(
            'conditions' => array(
                'AdvancedFilter.id' => $fiterId
            ),
            'contain' => array(
                'AdvancedFilterCron' => array(
                    'conditions' => array(
                        'AdvancedFilterCron.type' => AdvancedFilterCron::TYPE_COUNT,
                        'AdvancedFilterCron.cron_id !=' => $failedJobs 
                    ),
                    'limit' => AdvancedFilterCron::EXPORT_ROWS_LIMIT,
                    'order' => array('AdvancedFilterCron.created' => 'DESC'),
                )
            )
        ));

        if (empty($filter)) {
            return false;
        }

        $this->setCountCsvData($filter);

        $fileName = Inflector::slug($filter['AdvancedFilter']['name'], '-') . '_daily-count-results';

        return $fileName;
    }

    /**
     * sets data for data results export
     * 
     * @param  int $fiterId
     * @param  string $type csv|pdf
     * @return mixed - (string) filename/(bool) false
     */
    public function exportDailyDataResults($fiterId) {
        $fiterId = (int) $fiterId;

        $filter = $this->controller->AdvancedFilter->find('first', array(
            'conditions' => array(
                'AdvancedFilter.id' => $fiterId,
            ),
            'contain' => array('AdvancedFilterValue')
        ));

        if (empty($filter)) {
            return false;
        }

        $filterData = $this->getDataCronData($fiterId);

        $this->setDataCsvData($filterData, $filter);

        $fileName = Inflector::slug($filter['AdvancedFilter']['name'], '-') . '_daily-data-results';

        return $fileName;
    }

    /**
     * init advanced filter
     * 
     * @param  string $model 
     */
    private function initAdvancedFilter($model) {
        $this->controller->loadModel($model);
        
        $this->controller->presetVars = null;

        $this->controller->Components->unload('AdvancedFilters');
        $this->controller->Components->unload('Search.Prg');

        Configure::write('Search.Prg.presetForm', array('model' => $model));

        $this->AdvancedFilters = $this->controller->Components->load('AdvancedFilters');
        $this->AdvancedFilters->initialize($this->controller);
        $this->AdvancedFilters->resetCustomFields($model);
    }

    /**
     * sets data for daily count CSV
     * 
     * @param array $filter
     */
    private function setCountCsvData($filter) {
        $_header = array('Date', 'Results Count');
        $_extract = array('date', 'count');
        $data = array();
        $_serialize = 'data';

        foreach ($filter['AdvancedFilterCron'] as $item) {
            $data[] = array('date' => $item['date'], 'count' => $item['result']);
        }

        $this->controller->set(compact('_header', '_extract', 'data', '_serialize'));
    }

    /**
     * sets data for daily data CSV
     * 
     * @param array $filtersData - reconstructed filter results data
     */
    private function setDataCsvData($filtersData, $filter) {
        $model = $filter['AdvancedFilter']['model'];

        $this->AdvancedFilters->buildRequest($filter['AdvancedFilter']['id'], 'data', $filter['AdvancedFilter']['model']);

        $this->initAdvancedFilter($model);

        // $data = end($filtersData);
        $data = array();
        foreach ($filtersData as $item) {
            if (empty($item['data'])) {
                continue;
            }
            foreach ($item['data'] as $dataItem) {
                $data[] = $dataItem;
            }
        }
        $this->AdvancedFilters->cronDataCsv($model, $data);
    }

    /**
     * find all data cron results items
     * 
     * @param  int $fiterId
     */
    private function getDataCronData($fiterId) {
        $filterData = $this->controller->AdvancedFilterCronResultItem->find('all', array(
            'conditions' => array(
                'AdvancedFilterCron.advanced_filter_id' => $fiterId,
                'Cron.status' => Cron::STATUS_SUCCESS
            ),
            'contain' => array(
                'AdvancedFilterCron' => array(
                    'Cron'
                )
            ),
            'limit' => AdvancedFilterCron::EXPORT_ROWS_LIMIT,
            'order' => array('AdvancedFilterCronResultItem.id' => 'DESC'),
            'joins' => array(
                array(
                    'table' => 'cron',
                    'alias' => 'Cron',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'AdvancedFilterCron.cron_id = Cron.id'
                    )
                )
            )
        ));

        $resultData = array();
        foreach ($filterData as $item) {
            $cronId = $item['AdvancedFilterCronResultItem']['advanced_filter_cron_id'];
            if (empty($resultData[$cronId]['cron'])) {
                $resultData[$cronId]['cron'] = $item['AdvancedFilterCron'];
            }
            $data = json_decode($item['AdvancedFilterCronResultItem']['data'], true);
            $data['__cron_date'] = $item['AdvancedFilterCron']['date'];
            $resultData[$cronId]['data'][] = $data;
        }

        return $resultData;
    }

    /**
     * execute advanced filter, create cron record
     * 
     * @param  array $filter
     * @param  $type
     * @return boolean - success/failed
     */
    private function runFilter($filter, $type) {
        $startTime = scriptExecutionTime();

        $model = $filter['AdvancedFilter']['model'];

        $this->AdvancedFilters->buildRequest($filter['AdvancedFilter']['id']);

        $this->initAdvancedFilter($model);

        $result = $this->AdvancedFilters->filterCron($filter, $type);
        $this->controller->set('data', $result);

        $time = scriptExecutionTime() - $startTime;
        $ret = $this->processFilterResult($filter, $result, $time, $type);

        $this->controller->request->query = array();
        $this->controller->request->data = array();
        
        return ($result !== false && $ret) ? true : false;
    }

    /**
     * proccess filter result, save advanced filter cron record
     *
     * @param  array $filter
     * @param  mixed $filterResult - filter result
     * @param  float $time - execution time
     * @param  int $type - filter type (count/data)
     * @return boolean - success/failed
     */
    private function processFilterResult($filter, $filterResult, $time, $type) {
        if ($filterResult === false) {
            $this->error(__('Advanced Filter Cron failed (advanced_filter_id = %s)', $filter['AdvancedFilter']['id']));
        }

        $data = array(
            'advanced_filter_id' => $filter['AdvancedFilter']['id'],
            'type' => $type,
            'result' => $this->getResultFromData($filterResult, $type),
            'execution_time' => $time,
        );

        $saveLog = $this->crons[] = $this->controller->AdvancedFilterCron->saveCronTaskRecord($data);
        if (!empty($saveLog['AdvancedFilterCron']['id']) && $type == AdvancedFilterCron::TYPE_DATA && !empty($filterResult)) {
            $filterCronId = $saveLog['AdvancedFilterCron']['id'];
            foreach ($filterResult as $item) {
                $saveLog &= $this->controller->AdvancedFilterCronResultItem->saveResultItem($filterCronId, $item);
            }
        }
        if (!$saveLog) {
            $this->error(__('Advanced Filter Cron - record saving failed (advanced_filter_id = %s)', $filter['AdvancedFilter']['id']));
        }

        return $saveLog;
    }

    /**
     * returns data to ready DB record
     * 
     * @param  mixed $filterResult - filter result
     * @param  int $type - filter type (count/data)
     * @return mixed $result
     */
    private function getResultFromData($filterResult, $type) {
        $result = 0;
        if (is_numeric($filterResult)) {
            $result = $filterResult;
        }
        elseif (is_array($filterResult)) {
            $result = count($filterResult);
        }

        return $result;
    }

    /**
     * save cron_id to created advanced filter crons records
     * 
     * @param  int $cronId
     * @return  boolean success/failed
     */
    public function assignCronIdToRecords($cronId) {
        if (empty($this->crons)) {
            return true;
        }

        $filterCronIds = array();
                
        foreach ($this->crons as $item) {
            $filterCronIds[] = $item['AdvancedFilterCron']['id'];
        }

        return $this->controller->AdvancedFilterCron->updateAll(array('cron_id' => $cronId), array(
            'AdvancedFilterCron.id' => $filterCronIds
        ));
    }
}
