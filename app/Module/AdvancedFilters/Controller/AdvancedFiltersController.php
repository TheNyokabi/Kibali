<?php
App::uses('AdvancedFiltersComponent', 'Controller/Component');

class AdvancedFiltersController extends AdvancedFiltersAppController {
    public $name = 'AdvancedFilters';
    public $uses = array('AdvancedFilter', 'AdvancedFilterValue', 'AdvancedFilterUserSetting');
    public $components = array('AdvancedFilters');

    public function beforeFilter() {
        parent::beforeFilter();

        $this->title = __('Advanced Filters');
    }

    /**
     * redirect to AdvancedFilter detail
     * 
     * @param int $id
     */
    public function redirectAdvancedFilter($id) {
        $id = (int) $id;

        $filter = $this->AdvancedFilter->getFilter($id);
        if (empty($filter)) {
            throw new NotFoundException();
        }

        $model = $filter['AdvancedFilter']['model'];
        $this->loadModel($model);

        $values = $this->AdvancedFilter->getFilterValues($id);
        $query = AdvancedFilter::buildValues($values);

        $url = array();
        if (!empty($this->$model->advancedFilterSettings['url'])) {
            $url = array_merge($this->$model->advancedFilterSettings['url'], ['plugin' => null]);
            $url['?'] += $query;
        }
        else {
            $url = array(
                'plugin' => null,
                'controller' => controllerFromModel($model),
                'action' => 'index',
                '?' => am(array('advanced_filter' => true, 'advanced_filter_id' => $id), $query)
            );
        }

        return $this->redirect($url);
    }

    public function saveAdvancedFilter($id = null) {
        $edit = ($id != null) ? true : false;

        $data = ($edit) ? $this->request->data['Edit'] : $this->request->data['Create'];

        $this->advancedFilterSaveInit($data);

        if ($edit) {
            $filter = $this->AdvancedFilter->getFilter($id);
            if (empty($filter) || $filter['AdvancedFilter']['user_id'] != $this->logged['id']) {
                throw new NotFoundException();
            }

            $data['AdvancedFilter']['id'] = $id;

            $this->set('advancedFilterEdit', true);
        }

        $data['AdvancedFilter']['user_id'] = $this->logged['id'];

        $this->AdvancedFilter->set($data);

        if ($this->AdvancedFilter->validates()
            && $this->AdvancedFilters->validateSelection($this->request->data[$data['AdvancedFilter']['model']], $data['AdvancedFilter']['model'])
        ) {
            $this->AdvancedFilter->query('SET autocommit=0;');
            if ($this->AdvancedFilter->save()
                && $this->saveAdvancedFilterFields($this->AdvancedFilter->id, $data)
                && $this->saveAdvancedFilterUserSettings($this->AdvancedFilter->id, $data)
            ) {
                $this->request->query['advanced_filter_id'] = $this->AdvancedFilter->id;
                $this->AdvancedFilter->commit();
                $this->set('successMessage', __('Filter was saved.'));
            }
            else {
                $this->set('errorMessage', __('Something went wrong.'));
            }
        }

        $this->AdvancedFilters->setFilterSettings($data['AdvancedFilter']['model'], true);
        if (!empty($this->AdvancedFilter->id)) {
            $this->AdvancedFilters->setActiveFilterData($this->AdvancedFilter->getFilter($this->AdvancedFilter->id));
        }

        $this->render('/Elements/' . ADVANCED_FILTERS_ELEMENT_PATH . 'createForm');
    }

    private function advancedFilterSaveInit($requestData) {
        if (empty($this->AdvancedFilters)) {
            $this->loadModel($requestData['AdvancedFilter']['model']);
            Configure::write('Search.Prg.presetForm', array('model' => $requestData['AdvancedFilter']['model']));
            $this->AdvancedFilters = $this->Components->load('AdvancedFilters');
            $this->AdvancedFilters->initialize($this);
        }
    }

    private function saveAdvancedFilterFields($id, $data) {
        $this->AdvancedFilterValue->deleteAll(['advanced_filter_id' => $id]);

        foreach ($this->request->data[$data['AdvancedFilter']['model']] as $field => $value) {
            if ($field == 'advanced_filter_id') {
                continue;
            }
            $this->AdvancedFilterValue->create();
            $data = array(
                'advanced_filter_id' => $id,
                'field' => $field,
                'value' => (is_array($value)) ? implode(',', $value) : $value,
                'many' => (is_array($value)) ? ADVANCED_FILTER_VALUE_MANY : ADVANCED_FILTER_VALUE_ONE
            );
            if (!$this->AdvancedFilterValue->save($data)) {
                return false;
            }
        }
        return true;
    }

    private function saveAdvancedFilterUserSettings($id, $requestData) {
        $data = array(
            'advanced_filter_id' => $id,
            'user_id' => $this->logged['id']
        );

        $settings = $this->AdvancedFilterUserSetting->find('first', array(
            'conditions' => $data,
            'contain' => array()
        ));

        if (!empty($settings)) {
            $data['id'] = $settings['AdvancedFilterUserSetting']['id'];
        }

        $requestDataSetting = (!empty($requestData['AdvancedFilterUserSetting'])) ? $requestData['AdvancedFilterUserSetting'] : array();

        if (!empty($requestDataSetting['default_index'])) {
            $filters = $this->AdvancedFilterUserSetting->find('all', array(
                'fields' => array('AdvancedFilterUserSetting.id', 'AdvancedFilter.model'),
                'conditions' => array(
                    'AdvancedFilter.model' => $requestData['AdvancedFilter']['model'],
                    'AdvancedFilterUserSetting.user_id' => $this->logged['id']
                )
            ));

            $filterIds = Hash::extract($filters, '{n}.AdvancedFilterUserSetting.id');

            // if(($key = array_search($id, $filterIds)) !== false) {
            //     unset($messages[$key]);
            // }

            if (!empty($filters)) {
                $this->AdvancedFilterUserSetting->updateAll(array('default_index' => AdvancedFilterUserSetting::NOT_DEFAULT_INDEX), array(
                    'AdvancedFilterUserSetting.id' => $filterIds,
                ));
            }
        }

        $data = am($requestDataSetting, $data);

        $this->AdvancedFilterUserSetting->create($data);

        if (!$this->AdvancedFilterUserSetting->save()) {
            return false;
        }

        return true;
    }

    public function deleteAdvancedFilter($id) {
        $this->autoRender = false;

        $id = (int) $id;

        $data = $this->AdvancedFilter->find('first', array(
            'conditions' => array(
                'AdvancedFilter.id' => $id
            ),
            'recursive' => 0
        ));

        if (empty($data)) {
            exit;
        }

        $this->loadModel('NotificationSystem');
        $hasNotification = $this->NotificationSystem->find('count', array(
            'conditions' => array(
                'NotificationSystem.advanced_filter_id' => $data['AdvancedFilter']['id']
            )
        ));
        
        $response = array();
        if (!$hasNotification) {
            if ($this->AdvancedFilter->softDelete($id)) {
                $response['success'] = true;
            }
        }
        else {
            $response['error'] = __('Filter is being used by a notification and cannot be deleted.');
        }
        
        echo json_encode($response);
    }

    public function exportAdvancedFilterToPdf($model) {
        $this->autoRender = false;
        $this->layout = 'pdf';

        $this->loadModel($model);

        $this->loadCustomFields($model);

        $this->resetAdvancedFilters($model);

        $this->Pdf = $this->Components->load('Pdf');
        $this->Pdf->initialize($this);

        if (!$this->AdvancedFilters->filter($model, 'pdf', AdvancedFiltersComponent::FILTER_TYPE_PDF)) {
            throw new NotFoundException();
        }
        // $this->render('../Elements/advancedFilters/pdf');
        $this->Pdf->renderPdf($this->viewVars['filter']['settings']['pdf_file_name'], '/Elements/advancedFilters/pdf', 'pdf', $this->viewVars, true);
    }

    public function exportAdvancedFilterToCsv($model) {
        $this->loadModel($model);

        $this->loadCustomFields($model);

        $this->resetAdvancedFilters($model);

        if (!$this->AdvancedFilters->csv($model)) {
            throw new NotFoundException();
        }

        $this->autoRender = true;

        $this->response->download($this->$model->advancedFilterSettings['csv_file_name'] . '.csv');
        $this->viewClass = 'CsvView.Csv';
    }

    public function exportDailyCountResults($filterId) {
        $this->AdvancedFiltersCron = $this->Components->load('AdvancedFiltersCron');
        $this->AdvancedFiltersCron->initialize($this);

        $fileName = $this->AdvancedFiltersCron->exportDailyCountResults($filterId);
        if (empty($fileName)) {
            throw new NotFoundException();
        }

        $this->response->download($fileName . '.csv');
        $this->viewClass = 'CsvView.Csv';
    }

    public function exportDailyDataResults($filterId) {
        $this->AdvancedFiltersCron = $this->Components->load('AdvancedFiltersCron');
        $this->AdvancedFiltersCron->initialize($this);

        $fileName = $this->AdvancedFiltersCron->exportDailyDataResults($filterId);
        if (empty($fileName)) {
            throw new NotFoundException();
        }

        $this->response->download($fileName . '.csv');
        $this->viewClass = 'CsvView.Csv';
    }

    private function resetAdvancedFilters($model) {
        $this->presetVars = null;

        $this->Components->unload('AdvancedFilters');
        $this->Components->unload('Search.Prg');

        Configure::write('Search.Prg.presetForm', array('model' => $model));
        $this->AdvancedFilters = $this->Components->load('AdvancedFilters');
        $this->AdvancedFilters->initialize($this);
    }

    private function loadCustomFields($model) {
        $this->AdvancedFilters->resetCustomFields($model);
    }
}
