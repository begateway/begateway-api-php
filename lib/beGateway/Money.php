<?php
namespace beGateway;

class Money {
  protected $_amount;
  protected $_currency;
  protected $_cents;

  public function __construct($amount = 0, $currency = 'USD') {
    $this->_currency = $currency;
    $this->setAmount($amount);
  }

  public function getCents() {
    $cents = ($this->_cents) ? $this->_cents : (int)($this->_amount * $this->_currency_multiplyer());
    return $cents;
  }

  public function setCents($cents) {
    $this->_cents = (int)$cents;
    $this->_amount = NULL;
  }

  public function setAmount($amount){
    $this->_amount = (float)$amount;
    $this->_cents = NULL;
  }
  public function getAmount() {
    $amount = ($this->_amount) ? $this->_amount : (float)($this->_cents / $this->_currency_multiplyer());
    return $amount;
  }

  public function setCurrency($currency){
    $this->_currency = $currency;
  }
  public function getCurrency() {
    return $this->_currency;
  }

  private function _currency_multiplyer() {
    //array currency code => mutiplyer
    $exceptions = array(
        'BIF' => 1,
        'BYR' => 1,
        'CLF' => 1,
        'CLP' => 1,
        'CVE' => 1,
        'DJF' => 1,
        'GNF' => 1,
        'IDR' => 1,
        'IQD' => 1,
        'IRR' => 1,
        'ISK' => 1,
        'JPY' => 1,
        'KMF' => 1,
        'KPW' => 1,
        'KRW' => 1,
        'LAK' => 1,
        'LBP' => 1,
        'MMK' => 1,
        'PYG' => 1,
        'RWF' => 1,
        'SLL' => 1,
        'STD' => 1,
        'UYI' => 1,
        'VND' => 1,
        'VUV' => 1,
        'XAF' => 1,
        'XOF' => 1,
        'XPF' => 1,
        'MOP' => 10,
        'BHD' => 1000,
        'JOD' => 1000,
        'KWD' => 1000,
        'LYD' => 1000,
        'OMR' => 1000,
        'TND' => 1000
    );
    $multiplyer = 100; //default value
    foreach ($exceptions as $key => $value) {
        if (($this->_currency == $key)) {
            $multiplyer = $value;
            break;
        }
    }
    return $multiplyer;
  }
}
?>
