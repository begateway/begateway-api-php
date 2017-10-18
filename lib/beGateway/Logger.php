<?php
namespace BeGateway;

class Logger {

  const INFO = 0;
  const WARNING = 1;
  const ERROR = 2;
  const DEBUG = 4;

  private $_level;
  private static $instance;
  private $_output = 'php://stderr';
  private $_message_callback = false;
  private $_mask = true;

  private function __construct() {
    $this->_level = self::INFO;
  }

  public function write($msg, $level = self::INFO, $place = '') {

    $p = '';
    if (!empty($place)) { $p = "( $place )"; }

    if ($this->_level >= $level) {
      $message = "[" . self::getLevelName($level) . " $p] => ";
      $message .= print_r($this->filter(var_export($msg, true)), true);
      $message .= PHP_EOL;
      if ($this->_output) { $this->sendToFile($message); }
      if ($this->_message_callback != false) { call_user_func($this->_message_callback, $message); }
    }
  }

  public function setLogLevel($level) {
    $this->_level = $level;
  }

  public function setPANfitering($option) {
    $this->_mask = $option;
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

  private function filter($message) {
    $card_filter = '/("number":")(\d{1})\d{8,13}(\d{4})(")/';
    $cvc_filter  = '/("verification_value":")(\d{3,4})(")/';
    $modified = $message;
    if ($this->_mask) {
      $modified = preg_replace($card_filter,'$1$2 xxxx $3$4', $modified);
      $modified = preg_replace($cvc_filter,'$1xxx$3', $modified);
    }

    return $modified;
  }
}
?>
