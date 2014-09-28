<?php
namespace eComCharge;

class QueryByTrackingId extends ApiAbstract {
  protected $_tracking_id;

  protected function _endpoint() {
    return $this->_service_url . '/tracking_id/' . $this->getTrackingId();
  }
  public function setTrackingId($tracking_id) {
    $this->_tracking_id = $tracking_id;
  }
  public function getTrackingId() {
    return $this->_tracking_id;
  }
  protected function _buildRequestMessage() {
    return '';
  }
}
?>
