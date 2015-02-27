<?php
namespace eComCharge;

class QueryByUid extends ApiAbstract {
  protected $_uid;

  protected function _endpoint() {
    return Settings::$apiBase . '/transactions/' . $this->getUid();
  }
  public function setUid($uid) {
    $this->_uid = $uid;
  }
  public function getUid() {
    return $this->_uid;
  }
  protected function _buildRequestMessage() {
    return '';
  }
}
?>
