<?php
namespace BeGateway;

class CreditOperation extends ApiAbstract {
  public $card;
  public $money;
  protected $_description;
  protected $_tracking_id;

  public function __construct() {
    $this->money = new Money();
    $this->card = new Card();
  }

  public function setDescription($description) {
    $this->_description = $description;
  }
  public function getDescription() {
    return $this->_description;
  }

  public function setTrackingId($tracking_id) {
    $this->_tracking_id = $tracking_id;
  }
  public function getTrackingId() {
    return $this->_tracking_id;
  }

  protected function _buildRequestMessage() {
    $request = array(
      'request' => array(
        'amount' => $this->money->getCents(),
        'currency' => $this->money->getCurrency(),
        'description' => $this->getDescription(),
        'tracking_id' => $this->getTrackingId(),
        'credit_card' => array(
          'token' => $this->card->getCardToken(),
        ),
      )
    );

    Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

    return $request;
  }

}
?>
