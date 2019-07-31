<?php
App::uses('ModelBehavior', 'Model');
App::uses('Hash', 'Utility');
App::uses('SystemLog', 'SystemLogs.Model');
App::uses('Router', 'Routing');
App::uses('CakeText', 'Utiity');
App::uses('AuthComponent', 'Controller/Component');

class SystemLogsBehavior extends ModelBehavior {

/**
 * Default config
 *
 * @var array
 */
    protected $_defaults = [
        
    ];

    public $settings = [];

    private $_requestId = null;

/**
 * Setup
 *
 * @param Model $Model
 * @param array $settings
 * @throws RuntimeException
 * @return void
 */
    public function setup(Model $Model, $settings = []) {
        if (!isset($this->settings[$Model->alias])) {
            $this->settings[$Model->alias] = Hash::merge($this->_defaults, $settings);
        }

        $this->_loadLogsSettings($Model);
    }

/**
 * Load logs settings from model.
 * 
 * @param Model $Model
 * @return void
 */
    protected function _loadLogsSettings(Model $Model) {
        if (!$Model->hasMethod('getSystemLogsConfig')) {
            return trigger_error('SystemLogs: Model %s is missing system logs configuration when loading it up.', $Model->alias);
        }

        $config = $Model->getSystemLogsConfig();
        $this->settings[$Model->alias] = $config;
    }

/**
 * Get log config.
 * 
 * @param  Model $Model
 * @param  int $action Action.
 * @return array Log config.
 */
    public function logConfig(Model $Model, $action = null) {
        if ($action !== null) {
            return $this->settings[$Model->alias]['logs'][$action];
        }
        return $this->settings[$Model->alias]['logs'];
    }

/**
 * Create log of system event.
 * 
 * @param  Model $Model
 * @param  int $action Action.
 * @param  mixed $result Action result data.
 * @param  int $foreignKey Related item id.
 * @return boolean Success.
 */
    public function systemLog(Model $Model, $action, $foreignKey = null, $result = null, $message = null) {
        $SystemLog = ClassRegistry::init('SystemLogs.SystemLog');

        $request = Router::getRequest();

        $data = [
            'model' => $Model->modelFullName(),
            'foreign_key' => $foreignKey,
            'action' => $action,
            'result' => self::encodeResultData($result),
            'message' => $this->getMessage($Model, $action, $message),
            'user_model' => 'User', //this should be done more DRY in future
            'user_id' => AuthComponent::user('id'),
            'ip' => $request->clientIp(),
            'uri' => $request->here(),
            'request_id' => $this->_getRequestId(),
        ];

        $SystemLog->create();
        return ((boolean) $SystemLog->save($data));
    }

    public function bindSystemLog($Model) {
        if ($Model->getAssociated('SystemLog') === null) {
            $Model->bindModel([
                'hasMany' => [
                    'SystemLog' => [
                        'className' => 'SystemLogs.SystemLog',
                        'foreignKey' => 'foreign_key',
                        'conditions' => [
                            'SystemLog.model' => (!empty($Model->plugin)) ? "{$Model->plugin}.{$Model->name}" : $Model->name,
                        ],
                    ]
                ]
            ], false);
        }
    }

/**
 * Transfer data to string.
 * 
 * @param  mixed $result Action result data.
 * @return string String action result data.
 */
    public static function encodeResultData($result) {
        if (is_array($result) || is_object($result)) {
            $result = json_encode($result);
        }
        elseif ($result === false) {
            $result = 0;
        }

        return $result;
    }

/**
 * Get log message with replaced message params.
 * 
 * @param  Model $Model
 * @param  int $action Action.
 * @param  mixed $message Message or message params in array.
 * @return string String Message.
 */
    public function getMessage($Model, $action, $message) {
        $resultMessage = $message;
        $logConfig = $this->logConfig($Model, $action);

        if ($message === null && !empty($logConfig['message'])) {
            $resultMessage = $logConfig['message'];
        }
        elseif (is_array($message) && !empty($logConfig['message'])) {
            $resultMessage = vsprintf($logConfig['message'], $message);
        }

        return $resultMessage;
    }

/**
 * Transfer data to string.
 * 
 * @return return string Unique result data.
 */
    private function _getRequestId() {
        if ($this->_requestId === null) {
            $this->_requestId = CakeText::uuid();
        }

        return $this->_requestId;
    }

/**
 * Get list of all log actions in model.
 * 
 * @param  Model $Model
 * @param  int $action Action.
 * @return array List of log actions.
 */
    public function listSystemLogActions($Model) {
        $config = $this->logConfig($Model);
        return Hash::combine($config, '{n}.action', '{n}.label');
    }
}
