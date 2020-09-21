<?php
namespace BeGateway;

abstract class ApiAbstract {
  protected abstract function _buildRequestMessage();
  protected $_language;
  protected $_timeout_connect = 10;
  protected $_timeout_read    = 30;
  protected $_headers         = array();

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
    return GatewayTransport::submit(Settings::$shopId, Settings::$shopKey,
                                    $this->_endpoint(), $this->_buildRequestMessage(),
                                    $this->_headers,
                                    $this->_timeout_read, $this->_timeout_connect );
  }

  protected function _endpoint() {
    return Settings::$gatewayBase . '/transactions/' . $this->_getTransactionType();
  }

  protected function _getTransactionType() {
    list($module,$klass) = explode('\\', get_class($this));
    $klass = str_replace('Operation', '', $klass);
    $klass = strtolower($klass) . 's';
    return $klass;
  }
  public function setLanguage($language_code) {
    if (in_array($language_code, Language::getSupportedLanguages())) {
      $this->_language = $language_code;
    }else{
      $this->_language = Language::getDefaultLanguage();
    }
  }

  public function getLanguage() {
    return $this->_language;
  }

  public function setConnectTimeout($timeout) {
    $this->_timeout_connect = $timeout;
  }

  public function setTimeout($timeout) {
    $this->_timeout_read = $timeout;
  }

  public function setRequestHeaders($headers) {
    $this->_headers = $headers;
  }
}
?>
