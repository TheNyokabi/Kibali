<?php
//paths to elements
define('CUSTOM_FIELDS_TYPES_ELEMENT_PATH', 'fieldTypes/');
define('CUSTOM_FIELDS_DISPLAY_ELEMENT_PATH', 'display/');

define('CUSTOM_FIELD_SETTING_STATUS_ENABLED', 1);
define('CUSTOM_FIELD_SETTING_STATUS_DISABLED', 0);

define('CUSTOM_FIELD_TYPE_SHORT_TEXT', 1);
define('CUSTOM_FIELD_TYPE_TEXTAREA', 2);
define('CUSTOM_FIELD_TYPE_DATE', 3);
define('CUSTOM_FIELD_TYPE_DROPDOWN', 4);

define('CUSTOM_FIELD_MAX_TABS', 3);
define('CUSTOM_FIELD_MAX_FIELD', 10);

// customized value for CUSTOM_FIELD_MAX_FIELD for a specific Model
// in a format {$Model}_CUSTOM_FIELD_MAX_FIELD
define('Asset_CUSTOM_FIELD_MAX_FIELD', 20);