<?php
class SettingGroup extends AppModel {
	public $name = 'SettingGroup';
	public $primaryKey = 'slug';

	public $actsAs = array(
		'Containable'
	);

	public $hasMany = array(
		'Setting' => array(
			'foreignKey' => 'setting_group_slug'
		),
		'SettingSubGroup' => array(
			'className' => 'SettingGroup',
			'foreignKey' => 'parent_slug'
		)
	);

	public function __construct($id = false, $table = null, $ds = null) {
		$this->label = __('Setting Groups');
		
		parent::__construct($id, $table, $ds);
	}

	public function getSettingGroup($conditions = array()){
		return $this->find('first', array(
			'conditions' => $conditions,
			'contain' => array(
				'Setting' => array(
					'conditions' => array(
						'Setting.hidden' => 0
					),
					'order' => array(
						'Setting.order' => 'asc'
					)
				)
			)
		));
	}

	public function getSettingGroups($conditions = array()){
		$conditions = am($conditions, array(
			'SettingGroup.parent_slug' => null,
			'SettingGroup.hidden' => 0
		));

		$subconditions = array();
		if (!Configure::read('debug')) {
			//Logs hidden while debug is null
			$subconditions['SettingSubGroup.slug !='] = array('MAILLOG');
		}

		$settingsGroup = $this->find('all', array(
			'conditions' => $conditions,
			'order' => array('SettingGroup.order' => 'asc'),
			'contain' => array(
				'SettingSubGroup' => array(
					'fields' => array('SettingSubGroup.slug', 'SettingSubGroup.name', 'SettingSubGroup.url'),
					'conditions' => am($subconditions, array('SettingSubGroup.hidden' => 0))
				)
			),
			'fields' => array('SettingGroup.name', 'SettingGroup.icon_code')
		));

		return $settingsGroup;
	}

}
