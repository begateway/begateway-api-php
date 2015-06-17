<?php
namespace beGateway;

class GatewayTransportExceptionTest extends TestCase {

  function setUp() {
    $this->_apiBase = Settings::$gatewayBase;
    Settings::$gatewayBase = 'https://thedomaindoesntexist.begateway.com';
  }

  function tearDown() {
    Settings::$gatewayBase = $this->_apiBase;
  }

  public function test_networkIssuesHandledCorrectly() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isError());
    $this->assertPattern("|thedomaindoesntexist.begateway.com|", $response->getMessage());

  }

  protected function getTestObject($threed = false) {

    $transaction = $this->getTestObjectInstance($threed);

    $transaction->money->setAmount(12.33);
    $transaction->money->setCurrency('EUR');
    $transaction->setDescription('test');
    $transaction->setTrackingId('my_custom_variable');

    $transaction->card->setCardNumber('4200000000000000');
    $transaction->card->setCardHolder('John Doe');
    $transaction->card->setCardExpMonth(1);
    $transaction->card->setCardExpYear(2030);
    $transaction->card->setCardCvc('123');

    $transaction->customer->setFirstName('John');
    $transaction->customer->setLastName('Doe');
    $transaction->customer->setCountry('LV');
    $transaction->customer->setAddress('Demo str 12');
    $transaction->customer->setCity('Riga');
    $transaction->customer->setZip('LV-1082');
    $transaction->customer->setIp('127.0.0.1');
    $transaction->customer->setEmail('john@example.com');

    return $transaction;
  }

  protected function getTestObjectInstance($threed = false) {
    self::authorizeFromEnv($threed);

    return new Authorization();
  }


}
?>
