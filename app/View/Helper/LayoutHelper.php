<?php
App::uses('AppHelper', 'View/Helper');
App::uses('Router', 'Routing');

class LayoutHelper extends AppHelper {
	public $helpers = ['Html', 'Icon'];

	/**
	 * Toolbar items.
	 *
	 * @var array
	 */
	protected $_toolbarItems = array();

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
	}

	/**
	 * Adds a link to the toolbar array.
	 *
	 * ### Options
	 *
	 * - 'prepend' Prepend the breadcrumb to. Using this option
	 *
	 * @param string $name Text for link
	 * @param string $link URL for link (if empty it won't be a link)
	 * @param array  $options
	 * @return self
	 * @see HtmlHelper::link() for details on $options that can be used.
	 */
	public function addToolbarItem($name, $link = null, $options = null) {
		$prepend = $icon = false;
		if (is_array($options)) {
			if (isset($options['prepend'])) {
				$prepend = $options['prepend'];
				unset($options['prepend']);
			}

			if (isset($options['icon'])) {
				$icon = $options['icon'];
				unset($options['icon']);
			}
		}

		// lets disable it here
		$options['escape'] = false;

		$name = $this->Html->tag('span', $name);

		// process icon if needed
		if ($icon) {
			$name = $this->Icon->icon($icon) . ' ' . $name;
		}

		if ($prepend) {
			array_unshift($this->_toolbarItems, array($name, $link, $options));
		} else {
			array_push($this->_toolbarItems, array($name, $link, $options));
		}
		return $this;
	}

	/**
	 * Toolbar items.
	 */
	public function getToolbarItems() {
		$toolbarItems = $this->_prepareToolbarItems($startText);
		if (!empty($toolbarItems)) {
			$out = array();
			foreach ($toolbarItems as $item) {
				if (!empty($item[1])) {
					$out[] = $this->Html->link($item[0], $item[1], $item[2]);
				} else {
					$out[] = $item[0];
				}
			}
			return $out;
		}
		return null;
	}

	// get the html for use in the view
	public function getToolbarItemList($options = array()) {
		$defaults = array('class' => '', 'activeClass' => 'open');
		$options = (array)$options + $defaults;
		$activeClass = $options['activeClass'];
		$ulOptions = $options;
		$toolbarItems = $this->_prepareToolbarItems();

		$result = '';
		if (!empty($toolbarItems)) {
			$currentRoute = Router::url(Router::reverseToArray(Router::getRequest()));
			$itemCount = count($toolbarItems);
			foreach ($toolbarItems as $which => $item) {
				$options = array();

				if ($which === 0) {
					$options['class'] = 'first';
				}

				if (empty($item[1])) {
					$elementContent = $item[0];
				} else {
					if ($currentRoute === Router::url($item[1]) && $activeClass !== false) {
						$options['class'] = $activeClass;
					}
					
					$elementContent = $this->Html->link($item[0], $item[1], $item[2]);
				}
				$result .= $this->Html->tag('li', $elementContent, $options);
			}
		}

		return $this->Html->tag('ul', $result, $ulOptions);
	}

/**
 * Prepends startText to crumbs array if set
 *
 * @param string $startText Text to prepend
 * @param bool $escape If the output should be escaped or not
 * @return array Crumb list including startText (if provided)
 */
	protected function _prepareToolbarItems() {
		$toolbarItems = $this->_toolbarItems;

		return $toolbarItems;
	}

}
