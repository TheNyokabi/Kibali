<?php
App::uses('SectionBaseHelper', 'View/Helper');
App::uses('AppModule', 'Lib');

class SectionItemsHelper extends SectionBaseHelper {
	public $settings = array();

	public function actionList($item, $options = []) {
		$exportUrl = array(
			'action' => 'exportPdf',
			$item['SectionItem']['id']
		);

		$this->Ajax->addToActionList(__('Export PDF'), $exportUrl, 'file', false);
		
		$options = am([
			'notifications' => true,
			'history' => true,
			WorkflowsModule::alias() => true,
			AppModule::instance('Visualisation')->getAlias() => true
		], $options);

		return parent::actionList($item, $options);
	}

	public function getStatuses($item) {
		return 'statuses';
	}

	public function getDate($item) {
		return $this->Ux->date($item['SectionItem']['date']);
	}

	public function getBelongsTo($item) {
		return $this->Ux->text($item['BelongsTo']['full_name']);
	}

	public function getHasAndBelongsToMany($item) {
		return $this->Users->listNames($item, 'HasAndBelongsToMany');
	}

	public function getText($item) {
		return $this->Ux->text($item['SectionItem']['text']);
	}

	public function getTags($item) {
		return $this->Taggable->showList($item, [
			'notFoundCallback' => [$this->Taggable, 'notFoundBlank']
		]);
	}

}
