<?php
class NotificationSystemItemsUser extends AppModel {
	public $actsAs = array(
		'Containable'
	);

	public $belongsTo = array(
		'User'
	);
}
