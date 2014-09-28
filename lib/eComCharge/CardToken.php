<?php
namespace eComCharge;

class CardToken extends ApiAbstract {
  public $card;

  public function __construct($shop_id, $shop_key) {
    $this->card = new Card();

    parent::__construct($shop_id, $shop_key);
  }

  public function submit() {
    return new ResponseCardToken($this->_remoteRequest());
  }

  protected function _endpoint() {
    return $this->_service_domain . '/credit_cards';
  }

  protected function _buildRequestMessage() {
    $request = array(
      'request' => array(
        'holder' => $this->card->getCardHolder(),
        'number' => $this->card->getCardNumber(),
        'exp_month' => $this->card->getCardExpMonth(),
        'exp_year' => $this->card->getCardExpYear(),
        'token' => $this->card->getCardToken(),
      )
    );

    Logger::getInstance()->write($request, Logger::DEBUG, get_class() . '::' . __FUNCTION__);

    return $request;
  }

}
?>

