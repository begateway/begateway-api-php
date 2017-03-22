<?php
namespace beGateway;

class Webhook extends Response {

  protected $_json_source = 'php://input';

  public function __construct() {
    parent::__construct(file_get_contents($this->_json_source));
  }

  public function isAuthorized() {
    return $this->_getShopIdFromAuthorization() == Settings::$shopId
           && $this->_getShopKeyFromAuthorization() == Settings::$shopKey;
  }

  private function _getShopIdFromAuthorization() {
    if (isset($_SERVER['PHP_AUTH_USER']))
      return $_SERVER['PHP_AUTH_USER'];
    return '';
  }

  private function _getShopKeyFromAuthorization() {
    if (isset($_SERVER['PHP_AUTH_PW']))
      return $_SERVER['PHP_AUTH_PW'];
    return '';
  }
}
?>
