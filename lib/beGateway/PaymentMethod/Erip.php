<?php
namespace beGateway\PaymentMethod;

class Erip extends Base {
  protected $_params;

  public function __construct($params) {
    $this->_params = $params;
  }

  public function getParamsArray(){
    $arParams = array(
      'order_id' => $this->_params['order_id'],
      'account_number' => $this->_params['account_number'],
      'service_no' => $this->_params['service_no'],
    );

    $service_info = $this->_params['service_info'];
    if (gettype($service_info) == 'array' && !empty($service_info)) {
      $arParams['service_info'] = $service_info;
    }
    return $arParams;
  }
}
?>
