<?php
App::uses('AppHelper', 'View/Helper');

class SystemLogsHelper extends AppHelper {
	public $helpers = ['Html', 'Form'];

	public function getLink($title, $model, $foreignKey = null, $options = array()) {
		$query = [
			'advanced_filter' => 1
		];

		if (is_array($foreignKey)) {
			$query = am($query, $foreignKey);
		}
		elseif ($foreignKey !== null) {
			$query['foreign_key'] = $foreignKey;
		}

		if (!empty($options['query'])) {
			$query = am($query, $options['query']);
		}

		$url = [
			'plugin' => 'system_logs',
			'controller' => 'systemLogs',
			'action' => 'index',
			$model,
			'?' => $query
		];

		$link = $this->Html->link($title, $url, $options);

		return $link;
	}
}