<?php

App::uses('AppController', 'Controller');

class ObjectVersionAppController extends AppController {
	public function cancelAction($model, $foreignKey = null) {
		return parent::cancelAction($model, $foreignKey);
	}
}
