<?php
namespace BeGateway;

class QueryByTrackingId extends ApiAbstract {
  protected $_tracking_id;

  protected function _endpoint() {
    return Settings::$gatewayBase . '/v2/transactions/tracking_id/' . $this->getTrackingId();
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
