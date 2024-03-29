<?php
/**
 * Short description for class.
 *
 * @package	   cake
 * @subpackage	cake.tests.fixtures
 */

class LinkFixture extends CakeTestFixture {

	public $name = 'Link';

	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'url' => array('type' => 'string'),
	);

	public $records = array(
		array('id' => 11, 'url' => 'http://cakephp.org'),
		array('id' => 12, 'url' => 'http://google.com'),
	);
}