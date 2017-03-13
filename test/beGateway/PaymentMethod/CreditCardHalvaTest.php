<?php
namespace beGateway\PaymentMethod;

class CreditCardHalvaTest extends \beGateway\TestCase {

  public function test_getName() {
    $cc = $this->getTestObject();

    $this->assertEqual($cc->getName(), 'halva');
  }

  public function test_getParamsArray() {
    $cc = $this->getTestObject();

    $this->assertEqual($cc->getParamsArray(), array());
  }

  public function getTestObject() {
    return new CreditCardHalva;
  }
}
