<?php
App::uses('ModuleBase', 'Lib');
class CustomFieldsModule extends ModuleBase {

	/**
	 * Get the max fields count value possible to configure within a Model if configured,
	 * Default value returned otherwise.
	 * 
	 * @param  string $model Model name.
	 * @return int           Maximum count of fields allowed to configure.
	 */
	public static function getMaxFieldsCount($model) {
		$const = sprintf('%s_CUSTOM_FIELD_MAX_FIELD', $model);
		if (defined($const)) {
			return constant($const);
		}

		return CUSTOM_FIELD_MAX_FIELD;
	}
}
