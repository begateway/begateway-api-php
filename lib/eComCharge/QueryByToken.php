<?php
namespace eComCharge;

class QueryByToken extends ApiAbstract {
  protected $_token;

  protected function _endpoint() {
    return $this->_pp_service_url . '/ctp/api/checkouts/' . $this->getToken();
  }
  public function setToken($token) {
    $this->_token = $token;
  }
  public function getToken() {
    return $this->_token;
  }
  protected function _buildRequestMessage() {
    return '';
  }

  public function submit() {
    return new ResponseCheckout($this->_remoteRequest());
  }
}
?>
