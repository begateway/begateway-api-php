<?php
namespace BeGateway\PaymentMethod;

class EripTest extends \BeGateway\TestCase {

  public function test_getName() {
    $erip = $this->getTestObject();
    $this->assertEqual($erip->getName(), 'erip');
  }

  public function test_getParamsArray() {
    $erip = $this->getTestObject();
    $this->assertEqual($erip->getParamsArray(), array(
      'account_number' => '1234',
      'service_no' => '99999999',
      'order_id' => 100001,
      'service_info' => array('Test payment')
    ));
  }

  public function getTestObject() {
    return new Erip(array(
      'account_number' => '1234',
      'service_no' => '99999999',
      'order_id' => 100001,
      'service_info' => array('Test payment')
    ));
  }
}
