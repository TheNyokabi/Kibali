<?php
class NewsController extends AppController {
	public $helpers = array('Html', 'Form');
	public $components = array('Session');

	public function beforeFilter() {
		$this->Auth->allow();

		parent::beforeFilter();
	}

	public function index() {
		$this->set('title_for_layout', __('News'));
		$this->set('subtitle_for_layout', __(''));

		$news = $this->News->get();
		if ($this->News->hasError()) {
			$this->set('errorMessage', $this->News->getErrorMessage());
		}
		else {
			$this->News->markAsRead();
		}

		$this->set('news', $news);
	}

	public function markAsRead() {
		$this->autoRender = false;

		$news = $this->News->markAsRead();
	}
}
