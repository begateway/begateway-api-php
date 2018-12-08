<?php
namespace BeGateway;

class AdditionalData {
  protected $_receipt_text = array();
  protected $_contract = array();
  protected $_meta = array();

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
}
