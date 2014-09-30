<?php
namespace eComCharge;

class Logger {

  const INFO = 0;
  const WARNING = 1;
  const ERROR = 2;
  const DEBUG = 4;

  private $_level;
  private static $instance;
  private $_output = 'php://stderr';
  private $_message_callback = false;

  private function __construct() {
    $this->_level = self::INFO;
  }

  public function write($msg, $level = self::INFO, $place = '') {

    $p = '';
    if (!empty($place)) { $p = "( $place )"; }

    if ($this->_level >= $level) {
      $message = "[" . self::getLevelName($level) . " $p] => ";
      $message .= print_r($msg, true);
      $message .= PHP_EOL;
      if ($this->_output) { $this->sendToFile($message); }
      if ($this->_message_callback != false) { call_user_func($this->_message_callback, $message); }
    }
  }

  public function setLogLevel($level) {
    $this->_level = $level;
  }

  public function setOutputCallback($callback) {
    $this->_message_callback = $callback;
  }

  public function setOutputFile($path) {
    $this->_output = $path;
  }

  public static function getLevelName($level) {
    switch($level) {
      case self::INFO : return 'INFO'; break;
      case self::WARNING : return 'WARNING'; break;
      case self::DEBUG : return 'DEBUG'; break;
      default:
        throw new \Exception('Unknown log level ' . $level);
      }
  }

  public static function getInstance() {
    if(!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  private function sendToFile($message) {
    $fh = fopen($this->_output, 'w+');
    fwrite($fh, $message);
    fclose($fh);
  }


}
?>
