<?php
namespace eComCharge;

abstract class ApiAbstract {
  protected $_shop_id;

  protected $_shop_key;

  protected $_service_domain;
  protected $_service_url;
  protected $_pp_service_url;

  public function __construct($shop_id, $shop_key) {
    $this->_shop_id = $shop_id;
    $this->_shop_key = $shop_key;

    $this->_service_domain = 'https://processing.ecomcharge.com';
    $this->_service_url = $this->_service_domain . '/transactions';
    $this->_pp_service_url = 'https://checkout.ecomcharge.com';
  }

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
    return GatewayTransport::submit( $this->_shop_id, $this->_shop_key, $this->_endpoint(), $this->_buildRequestMessage() );
  }

  protected function _endpoint() {
    return $this->_service_url . '/' . $this->_getTransactionType();
  }

  protected function _getTransactionType() {
    list($module,$klass) = explode('\\', get_class($this));
    $klass = strtolower($klass) . 's';
    return $klass;
  }

}
?>
