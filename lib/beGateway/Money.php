<?php
namespace BeGateway;

class Money {
  protected $_amount;
  protected $_currency;
  protected $_cents;

  public function __construct($amount = 0, $currency = 'USD') {
    $this->_currency = $currency;
    $this->setAmount($amount);
  }

  public function getCents() {
    $cents = ($this->_cents) ? $this->_cents : intval(strval($this->_amount * $this->_currency_multiplyer()));
    return $cents;
  }

  public function setCents($cents) {
    $this->_cents = intval($cents);
    $this->_amount = NULL;
  }

  public function setAmount($amount){
    $this->_amount = $amount;
    $this->_cents = NULL;
  }
  public function getAmount() {
    if ($this->_amount) {
      $amount = $this->_amount;
    } else {
      $amount = $this->_cents / $this->_currency_multiplyer();
    }
    return floatval(strval($amount));
  }

  public function setCurrency($currency){
    $this->_currency = $currency;
  }
  public function getCurrency() {
    return $this->_currency;
  }

  private function _currency_power() {

    //array currency code => mutiplyer
    $exceptions = array(
        'BIF' => 0, 'BYR' => 0, 'CLF' => 0, 'CLP' => 0, 'CVE' => 0,
        'DJF' => 0, 'GNF' => 0, 'IDR' => 0, 'IQD' => 0, 'IRR' => 0,
        'ISK' => 0, 'JPY' => 0, 'KMF' => 0, 'KPW' => 0, 'KRW' => 0,
        'LAK' => 0, 'LBP' => 0, 'MMK' => 0, 'PYG' => 0, 'RWF' => 0,
        'SLL' => 0, 'STD' => 0, 'UYI' => 0, 'VND' => 0, 'VUV' => 0,
        'XAF' => 0, 'XOF' => 0, 'XPF' => 0, 'MOP' => 1, 'BHD' => 3,
        'JOD' => 3, 'KWD' => 3, 'LYD' => 3, 'OMR' => 3, 'TND' => 3
    );

    $power = 2; //default value
    foreach ($exceptions as $key => $value) {
        if (($this->_currency == $key)) {
            $power = $value;
            break;
        }
    }
    return $power;
  }

  private function _currency_multiplyer() {
    return pow(10,$this->_currency_power());
  }
}
?>
