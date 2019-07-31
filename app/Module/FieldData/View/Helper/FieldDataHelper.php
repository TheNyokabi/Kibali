<?php
App::uses('AppHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');
App::uses('FieldDataEntity', 'FieldData.Model/FieldData');
App::uses('Hash', 'Utility');
App::uses('CakeEvent', 'Event');
App::uses('CakeEventListener', 'Event');
App::uses('CakeEventManager', 'Event');

class FieldDataHelper extends AppHelper implements CakeEventListener {
	public $settings = array();
	public $helpers = array('Form', 'Html');
	// public $isCustomRenderer = false;
	
	/**
	 * Instance of the CakeEventManager this helper is using
	 * to dispatch inner events.
	 *
	 * @var CakeEventManager
	 */
	protected $_eventManager = null;

	public function __construct(View $view, $settings = array()) {
		parent::__construct($view, $settings);
		$this->settings = $settings;
	}

	public function implementedEvents() {
		return array(
			'FieldDataHelper.parseOptions' => array('callable' => 'parseOptions', 'passParams' => true),
			'FieldDataHelper.inputName' => array('callable' => 'inputName', 'passParams' => true),
			// 'FieldDataHelper.beforeInput' => array('callable' => 'beforeInput', 'passParams' => true),
		);
	}

	/**
	 * Returns the CakeEventManager manager instance that is handling any callbacks.
	 * You can use this instance to register any new listeners or callbacks to the
	 * model events, or create your own events and trigger them at will.
	 *
	 * @return CakeEventManager
	 */
	public function getEventManager() {
		if (empty($this->_eventManager)) {
			$this->_eventManager = new CakeEventManager();
			$this->_eventManager->attach($this);
		}

		return $this->_eventManager;
	}

	/**
	 * Checks viewVars for options array of data set for field provided as FieldDataEntity class.
	 * 
	 * @param  FieldDataEntity $Field
	 * @return bool            True if options data is set for this View, false otherwise.
	 */
	public function inputHasViewOptions(FieldDataEntity $Field) {
		return $this->getInputViewOptions($Field) !== null;
	}

	/**
	 * Get input field options which are set in the $this->_View.
	 * @param  FieldDataEntity $Field
	 * @return array|null      Array of options, or null if options are not set in the view.
	 */
	public function getInputViewOptions(FieldDataEntity $Field) {
		$varName = $Field->getVariableKey();

		return $this->_View->get($varName);
	}

	/**
	 * Provides an easier way to display many fields.
	 * 
	 * @param  array  $Fields  Array of FieldDataEntity classes.
	 * @param  array  $options Single set of options for all of the fields.
	 * @return string          Rendered inputs.
	 */
	public function inputs($Fields, $options = []) {
		$inputs = [];
		foreach ($Fields as $key => $FieldDataEntity) {
			if (!$FieldDataEntity->isEditable()) {
				continue;
			}
			
			$inputs[] = $this->input($FieldDataEntity, $options);
		}

		return implode('', $inputs);
	}

	// initialize a new helper class for dispatch
	public function dispatchHelper($name) {
		$Helper = $name;
		$HelperClass = $Helper . 'Helper';

		App::import('Helper', $Helper);
		return new $HelperClass($this->_View);
	}

	/**
	 * FieldData's own wrapper for a FormHelper::input() method that accepts FieldDataEntity class.
	 * 
	 * @param  FieldDataEntity|array $Field    Either a FieldDataEntity class instance, or array for hasMany association,
	 *                                         where first value is a FieldDataEntity instance and the second value is 
	 *                                         a index number in a field name, i.e 'HasMany.0.field_name.'
	 * @param  array                 $options
	 * @return string                
	 */
	public function input($Field, $options = array()) {
		// dd($Field);
		if (!$Field instanceof FieldDataEntity && is_array($Field) && isset($Field[0])) {
			$FieldDataEntity = $Field[0];
		}
		else {
			$FieldDataEntity = $Field;
		}

		if ($FieldDataEntity->getRenderHelper() !== null && get_class($this) != ($FieldDataEntity->getRenderHelper().'Helper')) {
			return $this->dispatchHelper($FieldDataEntity->getRenderHelper())->input($FieldDataEntity);
			// $dispatchedHelper = $this->dispatchHelper($FieldDataEntity->getRenderHelper());
			// $this->getEventManager()->attach($dispatchedHelper);
		}

		// $args = func_get_args();

		$index = $suffix = null;
		if (is_array($Field)) {
			$tmpField = $Field;
			$Field = array_values($tmpField);
			$Field = $tmpField[0];
			$index = $tmpField[1];
		}

		$options = $this->_parseOptions($Field, $options);
		
		$script = '';
		if ($Field->isTags()) {
			$script = $this->_tagsScript($Field);
		}

		// trigger parseOptions event
		$event = new CakeEvent('FieldDataHelper.parseOptions', $this, array($Field, $options));
		list($event->break, $event->breakOn) = array(true, false);
		$this->getEventManager()->dispatch($event);
		if ($event->isStopped()) {
			return false;
		}

		$options = $event->result === true ? $event->data[1] : $event->result;

		if ($index !== null) {
			$index .= '.';
		}
		$inputNameDefault = $Field->getModelName() . '.' . $index . $Field->getFieldName();

		// field name in the form
		$event = new CakeEvent('FieldDataHelper.inputName', $this, array($Field, $inputNameDefault, $index));
		list($event->break, $event->breakOn) = array(true, false);
		$this->getEventManager()->dispatch($event);
		if ($event->isStopped()) {
			return false;
		}

		$inputName = $event->result === true ? $event->data[1] : $event->result;

		//tmp - to force content before defined after
		if (!empty($options['beforeAfter'])) {
			$options['after'] = $options['beforeAfter'] . $options['after'];
		}

		if (isset($options['inputName'])) {
			$inputName = $options['inputName'];
			unset($options['inputName']);
		}

		$input = $this->Form->input($inputName, $options) . $script;

		// $afterInput = $Field->trigger('afterInput', [$Field, $index, $options]);

		return $input;
		// return $beforeInput . $input . $afterInput;
	}

	/**
	 * Called before echoing the input().
	 * 
	 * @param  array $options  Options for the input.
	 * @return mixed           False to stop rendering, True to continue as normal, array of customized $options.
	 */
	public function parseOptions(FieldDataEntity $Field, $options) {
		return true;
	}

	public function inputName($Field, $inputName, $index) {
		return true;
	}

	public function beforeInput($Field) {
		return true;
	}

	protected function _parseOptions(FieldDataEntity $Field, $options = []) {
		$defaultClass = ['form-control'];
		$defaultFormat = ['before', 'label', 'between', 'input', 'error', 'after'];

		$options = Hash::merge(array(
			// classes works as an array()
			'class' => $defaultClass,
			'div' => 'form-group',
			'label' => ['class' => 'col-md-2 control-label', 'text' => $Field->getLabel()],
			'between' => '<div class="col-md-10">',
			'after' => $this->help($Field) . $this->description($Field) . '</div>',
			'data-field-name' => $Field->getFieldName()
		), $options);

		// if ($this->inputHasViewOptions($Field)) {
		if ($Field->isSelectable()) {
			$selectClass = ['select2', 'col-md-12'];
			// handle custom class for a select field
			if ($options['class'] !== $defaultClass) {
				array_shift($options['class']);
				$selectClass = Hash::merge($selectClass, $options['class']);
			}

			$options['class'] = $selectClass;

			if ($Field->isType(FieldDataEntity::FIELD_TYPE_MULTIPLE)) {
				$options['multiple'] = true;
			}

			// Applies to single select field and tags
			if ($Field->getEmptyOption() !== null) {
				$options['data-placeholder'] = $Field->getEmptyOption();
			}

			// Setup empty option value for a single select field
			if (!$Field->hasMultiple() && $Field->getEmptyOption() !== null) {
				if (!isset($options['options'])) {
					// put an empty array key => value at the beginning of the options array for a select2 placeholder
					$fieldOptions = $this->getInputViewOptions($Field);
					if (!is_array($fieldOptions)) {
						$fieldOptions = [];
					}
				}
				else {
					$fieldOptions = $options['options'];
				}

				$fieldOptions = ['' => ''] + $fieldOptions;

				// custom config for select2 with empty value
				$options['options'] = $fieldOptions;
				$options['data-minimum-results-for-search'] = '-1';
				$options['data-allow-clear'] = true;
			}

			// Extra parameters required for a field for tags
			if ($Field->isType(FieldDataEntity::FIELD_TYPE_TAGS)) {
				$options['type'] = 'text';
				$options['class'] = [];
				$options['id'] = 'tags-' . $Field->getFieldName();

				// This is required and will be applied to the input field
				// before initializing select2 with taggable support to correctly setup HTML formatting.
				// FormHelper doesnt format hidden inputs with HTML
				$options['data-transform-properties'] = json_encode([
					'type' => 'hidden',
					'class' => 'col-md-12'
				]);
			}
		}
		else {
			$options['class'][] = 'form-control';
		}

		if ($Field->isType(FieldDataEntity::FIELD_TYPE_DATE)) {
			$options['type'] = 'text';
			$options['class'][] = 'datepicker';
		}

		if ($Field->isType(FieldDataEntity::FIELD_TYPE_TOGGLE)) {
			$options['type'] = 'checkbox';
			$options['class'][] = 'uniform';
			// unset($options['format']);
		}

		if (!isset($options['format'])) {
			$options['format'] = $defaultFormat;
		}

		// trigger parseOptions event
		// $event = new CakeEvent('FieldData.parseOptions', $this, array($options));
		// list($event->break, $event->breakOn) = array(true, false);
		// $this->getEventManager()->dispatch($event);
		// if ($event->isStopped()) {
		// 	return false;
		// }
		// $options = $event->result === true ? $event->data[0] : $event->result;

		// $options = $Field->trigger('parseOptions', [$options]);

		$options['class'] = implode(' ', $options['class']);

		return $options;
	}

	/**
	 * Returns a html formatted tag with help of the Field.
	 */
	public function help(FieldDataEntity $Field) {
		if ($Field->getHelp() === null) {
			return false;
		}

        return $this->Html->div('info-field-sign text-right', 
        	$this->Html->tag('span', '<i class="icon-info-sign"></i>', [
        		'class' => 'bs-popover',
        		'data-trigger' => 'hover',
				'data-placement' => 'right',
				'data-html' => true,
				'data-content' => $Field->getHelp(),
				'data-original-title' => __( 'Help' )
    		])
    	);
	}

	/**
	 * Returns a html formatted tag with description of the Field.
	 */
	public function description(FieldDataEntity $Field) {
		if ($Field->getDescription() === null) {
			return false;
		}

		$prefix = $this->Html->tag('span', '', ['class' => 'help-block-prefix']);

		return $this->Html->tag('span', $prefix . $Field->getDescription(), ['class' => 'help-block']);
	}

	/**
	 * script required for tags input init
	 */
	protected function _tagsScript(FieldDataEntity $Field) {
		$tags = array_values($this->getInputViewOptions($Field));
		if (empty($tags)) {
			$tags = array();
		}

		$displayField = 'title';
		$tagInstance = (_getModelInstance($Field->getModelName()))->{$Field->getFieldName()};
		if (!empty($tagInstance) && !empty($tagInstance->displayField)) {
			$displayField = $tagInstance->displayField;
		}

		$labels = null;
		if (isset($this->request->data[$Field->getFieldName()][0])) {
			$labelsArr = Hash::extract($this->request->data[$Field->getFieldName()], '{n}.' . $displayField);
			$labels = implode(',', $labelsArr);
		}

		if (!empty($labels)) {
			$this->request->data[$Field->getModelName()][$Field->getFieldName()] = $labels;
		}

		$tags = array_values($tags);

		$tags = "'" . self::jsonEncode($tags) . "'";
		$script = $this->Html->scriptblock('
			jQuery(function($) {
				var obj = $.parseJSON(' . $tags . ');
				var $ele = $("#tags-' . $Field->getFieldName() . '");
				var props = $ele.data("transformProperties");
				$ele
					.prop(props)
					.select2({
						tags: obj
					});
			})
		');

		return $script;
	}

	/**
	 * encodes data to json, escapes single quote marks 
	 * 
	 * @param  mixed $data
	 * @return string
	 */
	public static function jsonEncode($data) {
		return str_replace("'", "\'", json_encode($data));
	}
}