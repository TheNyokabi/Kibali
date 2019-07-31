<?php
App::uses('AppHelper', 'View/Helper');
class ImportToolHelper extends AppHelper {
	public $helpers = array('Html', 'Eramba');
	public $settings = array();

	public function getUrl($model) {
		return array(
			'plugin' => 'importTool',
			'controller' => 'importTool',
			'action' => 'index',
			$model
		);
	}

	public function getDownloadUrl($model, $getData = false) {
		return array(
			'plugin' => 'importTool',
			'controller' => 'importTool',
			'action' => 'downloadTemplate',
			$model,
			$getData
		);
	}

	public function getPreviewUrl() {
		return array(
			'plugin' => 'importTool',
			'controller' => 'importTool',
			'action' => 'preview'
		);
	}

	public function getIndexLink($model) {
		if (is_array($model)) {
			$list = array();
			foreach ($model as $alias => $name) {
				$list[] = $this->Html->link($name, $this->getUrl($alias), array(
					'escape' => false
				));
			}

			$ul = $this->Html->nestedList($list, array(
				'class' => 'dropdown-menu pull-right',
				'style' => 'text-align: left;'
			));

			$btn = '<button class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-file"></i>' . __('Import') . ' <span class="caret"></span></button>';

			return $this->Html->div("btn-group group-merge", $btn . $ul);
		}
		else {
			return $this->Html->div("btn-group group-merge",
				$this->Html->link( '<i class="icon-file"></i>' . __('Import'), $this->getUrl($model), array(
					'class' => 'btn',
					'escape' => false
				))
			);
		}		
	}

	/**
	 * Show a detailed information about validation errors for a certain item.
	 */
	public function getValidationErrorsContent($validationErrors = array(), $model) {
		$content = '';
		foreach ($validationErrors as $fieldName => $errors) {
			$fieldLabel = getFieldData($model, $fieldName, 'label', array(
				'humanizeLabel' => false
			));

			if ($fieldLabel === false) {
				continue;
			}

			$content .= $fieldLabel;
			$content .= $this->Html->nestedList($errors);
		}

		return $content;
	}
}