<?php
App::uses('AppHelper', 'View/Helper');
App::Uses('CakeNumber', 'Utility');
App::Uses('CakeTime', 'Utility');
App::uses('CakeSession', 'Model/Datasource');

class UxHelper extends AppHelper {
    public static $infoFlashTimeout = 15000;

    public $helpers = array('Html', 'Text', 'Flash');
    public $settings = array();
    
    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
        $this->settings = $settings;
    }

    /**
     * Generic method to render default and information flash messages.
     * 
     * @return string Html/JS for the flash messages.
     */
    public function renderFlash() {
        $this->sanitizeObsoleteFlashSession('flash');
        $this->sanitizeObsoleteFlashSession('info');

        $render = [];
        $render[] = $this->Flash->render();

        $render[] = $this->Flash->render('info', [
            'params' => [
                'timeout' => self::$infoFlashTimeout,
                'renderTimeout' => 1500
            ]
        ]);

        return implode('', array_filter($render));
    }

    public function sanitizeObsoleteFlashSession($key) {
        if (CakeSession::check("Message.$key")) {
            $flash = CakeSession::read("Message.$key");
            $val = [];
            foreach ($flash as $key2 => $item) {
                if (!$this->filterNonNumericKeys($key2)) {
                    CakeSession::delete("Message.$key.$key2");
                }
            }
        }
    }

    public function filterNonNumericKeys($arr) {
        if (!is_numeric($arr)) {
            return false;
        }

        return true;
    }

    public function isMissing($date) {
        $today = CakeTime::format('Y-m-d', CakeTime::fromString('now'));
        $plannedDate = $date;

        return $plannedDate < $today;
    }

    public function outputCurrency($data, $options = array()) {
        return CakeNumber::currency($data);
    }

    public function outputPercentage($data, $options = array()) {
        return CakeNumber::toPercentage($data, 0);
    }

    public function getItemLink($name, $model, $id) {
        $controller = controllerFromModel($model);

        $link = $this->Html->link($name, array(
            'controller' => $controller,
            'action' => 'index',
            '?' => array(
                'id' => $id,
            )
        ));

        return $link;
    }

    /**
     * Get generic icon html.
     * 
     * @param  string $class   Icon class.
     * @param  array  $options TBD.
     * @return string          HTML.
     */
    public function getIcon($class, $options = array()) {
        $options = am(array(), $options);

        return $this->Html->tag('i', false, array(
            'class' => 'icon icon-' . $class
        ));
    }

    /**
     * Get styled alert box by type.
     * 
     * @param  string $text    Text inside of the box
     * @param  array  $options Options
     * @return string          Generated alert box
     */
    public function getAlert($text, $options = array()) {
        $options = am(array(
            'type' => 'warning',
            'class' => null
        ), $options);

        $class = array('alert', 'alert-' . $options['type']);
        if (!empty($options['class'])) {
            $class = am($class, $options['class']);
        }

        unset($options['type']);
        unset($options['class']);

        return $this->Html->div(
            implode($class, ' '), 
            $this->text($text, [
                'htmlentities' => false
            ]),
            $options
        );
    }

    /**
     * Commonly used output to display separated list of items.
     */
    public function commonListOutput($list, $separate = ', ') {
        return implode($separate, $list);
    }

    /**
     * General method to display a formatted date.
     */
    public function date($data) {
        $date = CakeTime::format(CakeTime::fromString($data), '%Y-%m-%d'); // '%Y-%m-%d %H:%M:%S'
        return $this->text($date);
    }

     /**
     * General method to display a formatted date.
     */
    public function datetime($data) {
        $date = CakeTime::format(CakeTime::fromString($data), '%Y-%m-%d %H:%M:%S');
        return $this->text($date);
    }

    /**
     * General wrapper method to display text.
     */
    public function text($value, $options = [])
    {
        // fallback for previous used second argument $emptySign = '-'
        if (is_string($options)) {
            $options = [
                'emptySign' => $options
            ];
        }

        // options for the text output
        $options = am(array(
            'emptySign' => '-',
            'htmlentities' => false
        ), $options);

        if (is_string($value) && !empty($value)) {
            if ($options['htmlentities'] === true) {
                $value = h($value);
            }

            $value = trim($value);
            $value = nl2br($value);
        } else {
            $value = $options['emptySign'];
        }

        return $value;
    }

    /**
     * Logout button for header <ul> list.
     */
    public function logoutBtn($url = null) {
        if ($url === null) {
            $url = ['controller' => 'users', 'action' => 'logout', 'admin' => false, 'plugin' => null];
        }

        return $this->Html->link(
            '<i class="icon-key"></i> '. __('Log Out'),
            $url,
            array('escape' => false)
        );
    }
}