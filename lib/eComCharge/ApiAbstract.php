<?php
namespace eComCharge;

abstract class ApiAbstract {
  protected abstract function _buildRequestMessage();

  public function submit() {
    try {
      $response = $this->_remoteRequest();
    } catch (\Exception $e) {
      $msg = $e->getMessage();
      $response = '{ "errors":"' . $msg . '", "message":"' . $msg . '" }';
    }
    return new Response($response);
  }

  protected function _remoteRequest() {
    return GatewayTransport::submit( Settings::$shopId, Settings::$shopKey , $this->_endpoint(), $this->_buildRequestMessage() );
  }

  protected function _endpoint() {
    return Settings::$apiBase . '/transactions/' . $this->_getTransactionType();
  }

  protected function _getTransactionType() {
    list($module,$klass) = explode('\\', get_class($this));
    $klass = strtolower($klass) . 's';
    return $klass;
  }
}
?>
