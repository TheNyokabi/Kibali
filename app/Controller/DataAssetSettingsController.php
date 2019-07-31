<?php
App::uses('SectionBaseController', 'Controller');
App::uses('SectionCrudTrait', 'Controller/Trait');
App::uses('DataAssetSetting', 'Model');

class DataAssetSettingsController extends SectionBaseController {

    public $uses = ['DataAssetSetting', 'DataAssetInstance'];
    public $helpers = ['UserFields.UserField'];
    public $components = [
        'Search.Prg', 'Paginator', 'ObjectStatus.ObjectStatus',
        'Ajax' => [
            'actions' => ['setup'],
            'formDataActions' => ['setup']
        ],
        'Crud.Crud' => [
            'actions' => [
                'index' => [
                    'contain' => [
                        
                    ],
                ]
            ]
        ],
        'UserFields.UserFields' => [
            'fields' => ['DataOwner']
        ]
    ];

    public function beforeFilter() {
        // $this->Crud->enable(['setup']);

        parent::beforeFilter();

        $this->title = __('Data Asset Settings');
        $this->subTitle = __('');
    }

    public function setup($dataAssetInstanceId = null) {
        $dataAssetInstance = $this->DataAssetInstance->getItem($dataAssetInstanceId);

        $args = [];
        if (!empty($dataAssetInstance['DataAssetSetting']['id'])) {
            $args[] = $dataAssetInstance['DataAssetSetting']['id'];
        }

        $this->Crud->mapAction('setup', [
            'className' => (empty($dataAssetInstance['DataAssetSetting']['id'])) ? 'AppAdd' : 'AppEdit',
            'view' => 'setup'
        ]);

        $this->request->data['DataAssetSetting']['data_asset_instance_id'] = $dataAssetInstance['DataAssetInstance']['id'];
        $this->request->data['DataAssetSetting']['name'] = $dataAssetInstance['Asset']['name'];

        $this->set('dataAssetInstance', $dataAssetInstance);
        $this->set('modalPadding', true);
        $this->initOptions($dataAssetInstance);

        if (!empty($this->request->data['DataAssetSetting']['gdpr_enabled'])) {
            $this->DataAssetSetting->validate += $this->DataAssetSetting->validateGdpr;
        }

        $this->Crud->on('afterFind', array($this, '_afterFind'));

        return $this->Crud->execute(null, $args);
    }

    private function initOptions($dataAssetInstance) {
        $this->loadModel('BusinessUnit');
        $businessUnits = $this->BusinessUnit->find('all', [
            'joins' => [
                [
                    'table' => 'assets_business_units',
                    'alias' => 'AssetsBusinessUnit',
                    'type' => 'inner',
                    'conditions' => [
                        'AssetsBusinessUnit.business_unit_id = BusinessUnit.id',
                        'AssetsBusinessUnit.asset_id' => $dataAssetInstance['DataAssetInstance']['asset_id']
                    ]
                ]
            ],
            'contain' => $this->UserFields->attachFieldsToArray(['BusinessUnitOwner'], [], 'BusinessUnit')
        ]);

        $this->BusinessUnit->convertDataFromDb('BusinessUnitOwner', $businessUnits);
        
        $owners = [];
        foreach ($businessUnits as $businessUnit) {
            foreach ($businessUnit['BusinessUnitOwner'] as $item) {
                $owners[$item['id']] = $item['full_name_with_type'];
            }
        }

        $this->set('buOwners', $owners);
    }

    public function _afterFind(CakeEvent $event) {
        $data = $event->subject->item;
        if (!empty($data['SupervisoryAuthority'])) {
            $data['DataAssetSetting']['SupervisoryAuthority'] = Hash::extract($data['SupervisoryAuthority'], '{n}.country_id');
        }
        $event->subject->item = $data;
    }
}
