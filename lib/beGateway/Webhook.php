<?php
namespace beGateway;

class Webhook extends Response {
  protected $_json_in = 'php://input';

  public function __construct() {
    $this->decodeReceivedJson();
  }

  public function isAuthorized() {
    return $this->_getShopIdFromAuthorization() == Settings::$shopId
           && $this->_getShopKeyFromAuthorization() == Settings::$shopKey;
  }

  public function decodeReceivedJson() {
    $this->_response = json_decode(file_get_contents($this->_json_in));
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
