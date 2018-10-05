<?php
namespace BeGateway;

class Webhook extends Response {

  protected $_json_source = 'php://input';
  protected $_id  = null;
  protected $_key = null;

  public function __construct() {
    parent::__construct(file_get_contents($this->_json_source));
  }

  public function isAuthorized() {
    $this->process_auth_data();
    return $this->_id == Settings::$shopId
           && $this->_key == Settings::$shopKey;
  }

  private function process_auth_data() {
    $token = null;


    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
      $this->_id  = $_SERVER['PHP_AUTH_USER'];
      $this->_key = $_SERVER['PHP_AUTH_PW'];
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION']) && !is_null($_SERVER['HTTP_AUTHORIZATION'])) {
        $token = $_SERVER['HTTP_AUTHORIZATION'];
    } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && !is_null($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
        $token = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
    }

    if ($token != null) {
        if (strpos(strtolower($token), 'basic') === 0) {
            list($this->_id, $this->_key) = explode(':', base64_decode(substr($token, 6)));
        }
    }
  }
}
?>
