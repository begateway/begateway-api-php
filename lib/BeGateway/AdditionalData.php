<?php
namespace BeGateway;

class AdditionalData {
  protected $_receipt_text = array();
  protected $_contract = array();
  protected $_meta = array();
  protected $_fiscalization = array();
  protected $_platform_data = null;
  protected $_integration_data = null;

  public function setReceipt($receipt) {
    $this->_receipt_text = $receipt;
  }
  public function getReceipt() {
    return $this->_receipt_text;
  }

  public function setContract($contract) {
    $this->_contract = $contract;
  }
  public function getContract() {
    return $this->_contract;
  }

  public function setMeta($meta) {
    $this->_meta = $meta;
  }

  public function getMeta() {
    return $this->_meta;
  }

  public function setFiscalization($fiscalization) {
    $this->_fiscalization = $fiscalization;
  }

  public function getFiscalization() {
    return $this->_fiscalization;
  }

  public function setPlatformData($platform) {
    $this->_platform_data = strval($platform);
  }

  public function getPlatformData() {
    return $this->_platform_data;
  }

  public function setIntegrationData($module) {
    $this->_integration_data = strval($module);
  }

  public function getIntegrationData() {
    return $this->_integration_data;
  }
}
