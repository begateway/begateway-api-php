<?php
namespace eComCharge;

class Logger {

  const INFO = 0;
  const WARNING = 1;
  const ERROR = 2;
  const DEBUG = 4;

  private $_level;
  private static $instance;

  private function __construct() {
    $this->_level = self::INFO;
  }

  public function write($msg, $level = self::INFO, $place = '') {

    $p = '';
    if (!empty($place)) { $p = "( $place )"; }

    if ($this->_level >= $level) {
      print("[" . self::getLevelName($level) . " $p] => ");
      if (is_string($msg)) { print($msg); } else { print_r($msg); }
      print(PHP_EOL);
    }
  }

  public function setLogLevel($level) {
    $this->_level = $level;
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


}
?>
