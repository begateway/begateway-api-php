<?php
namespace eComCharge;

class Webhook extends Response {
  protected $_shop_id;

  protected $_shop_key;

  protected $_json_in = 'php://input';

  public function __construct($shop_id, $shop_key) {
    $this->_shop_id = $shop_id;
    $this->_shop_key = $shop_key;
    $this->decodeReceivedJson();
  }

  public function isAuthorized() {
    return $this->_getShopIdFromAuthorization() == $this->_shop_id && $this->_getShopKeyFromAuthorization() == $this->_shop_key;
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
