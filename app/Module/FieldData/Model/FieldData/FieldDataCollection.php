<?php

App::uses('Model', 'Model');
App::uses('FieldDataEntity', 'FieldData.Model/FieldData');

/**
 * Class to provide access to Field Data Entities in bulk for a model.
 */
class FieldDataCollection implements ArrayAccess, Iterator {
	protected $_fields = array();

	public function __construct(array $fields = [], Model $Model) {
		$this->_fields = $fields;
		$this->Model = $Model;
	}

	/**
	 * Adds a new field object into this collection.
	 * 
	 * @param string $name  Field name.
	 * @param array  $params Options.
	 */
	public function add($name, $params = []) {
		if ($params instanceof FieldDataEntity) {
			$this->_fields[$name] = $params;
		}
		else {
			$params['_field'] = $name;
			$this->_fields[$name] = new FieldDataEntity($params, $this->Model);
		}

		return $this->_fields[$name];
	}

	public function get($name) {
		return $this->{$name};
	}

	public function has($name) {
		return isset($this->_fields[$name]) && $this->_fields[$name] instanceof FieldDataEntity;
	}

	/**
	 * Get a FieldDataEntity in a way $Collection->myField.
	 * 
	 * @param  string $name Field name.
	 * @return FieldDataEntity
	 */
	public function __get($name) {
		if (isset($this->_fields[$name])) {
			return $this->_fields[$name];
		}

		throw new InternalErrorException(sprintf('Field name %s doesnt exist.', $name));
	}

	public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->_fields[] = $value;
        } else {
            $this->_fields[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->_fields[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->_fields[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->_fields[$offset]) ? $this->_fields[$offset] : null;
    }

	public function rewind() {
		return reset($this->_fields);
	}
	public function current() {
		return current($this->_fields);
	}
	public function key() {
		return key($this->_fields);
	}
	public function next() {
		return next($this->_fields);
	}
	public function valid() {
		return key($this->_fields) !== null;
	}

	/**
	 * Methods returns a list of fields in the current collection of fields.
	 * Formatted: ['field_name' => 'Field Label']
	 *
	 * @param  null|array $fields  Array of fields that should be returned in the list,
	 *                             Null to disable this filter.
	 * @param  bool $onlyEditable  Return a list of editable fields only.
	 *                             
	 * @return array    		   List of fields.
	 */
	public function getList($fields = null, $onlyEditable = false) {
		$data = [];
		foreach ($this->_fields as $_field) {
			if (is_array($fields) && !in_array($_field->getFieldName(), $fields)) {
				continue;
			}

			if ($onlyEditable && !$_field->isEditable()) {
				continue;
			}
			
			$data[$_field->getFieldName()] = $_field->getLabel();
		}

		return $data;
	}

	/**
	 * Returns an array of all fields data that needs to be set for a view to work properly in add/edit forms.
	 * By default also includes $this class as $FieldDataCollection.
	 * 
	 * @return array Collection of data to set for a view.
	 */
	public function getViewOptions($objectKey = 'FieldDataCollection') {
		$data = [
			$objectKey => $this
		];
		
		$keys = [];
		foreach ($this->_fields as $key => $FieldDataEntity) {
			$options = call_user_func_array([$FieldDataEntity, 'getViewOptions'], func_get_args());

			if (!empty($options)) {
				$k = array_keys($options)[0];
				if (isset($data[$k])) {
					trigger_error(sprintf('Options array key name %s for field %s is already in use. Please use different key name for the field options.', $k, $FieldDataEntity->getFieldName()));
				}
				$data = am($data, $options);
			}
		}

		return $data;
	}
}